// ── GLOBAL DROPZONE MANAGER ──
// Reusable untuk semua modal di seluruh halaman.
// Cara pakai:
//   DropzoneManager.init({
//       modalAddId:    'addKategori',
//       modalEditId:   'editKategori',
//       formAddId:     'formAddKategori',
//       formEditId:    'formEditKategori',
//       dzAddSelector: '#dropzone-add-kategori',
//       dzEditSelector:'#dropzone-edit-kategori',
//       fotoRequired:  true,          // default: true — set false kalau foto opsional di add
//       toastAdd:      'success',     // default: 'success'
//       toastEdit:     'updated',     // default: 'updated'
//   });

("use strict");

const previewTemplate = `
<div class="dz-preview dz-file-preview">
    <div class="dz-details">
        <div class="dz-thumbnail">
            <img data-dz-thumbnail>
            <div class="dz-success-mark"></div>
            <div class="dz-error-mark"></div>
            <div class="dz-error-message"><span data-dz-errormessage></span></div>
            <div class="progress">
                <div class="progress-bar progress-bar-primary" role="progressbar"
                    aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
            </div>
        </div>
        <div class="dz-filename" data-dz-name></div>
        <div class="dz-size" data-dz-size></div>
    </div>
</div>`;

const isDark = () =>
    document.documentElement.getAttribute("data-bs-theme") === "dark";

function createDropzone(selector, formEl) {
    const url = formEl?.getAttribute("action") || "/";

    const dz = new Dropzone(selector, {
        url,
        previewTemplate,
        parallelUploads: 1,
        maxFilesize: 2,
        acceptedFiles: ".jpg,.jpeg,.png,.webp",
        addRemoveLinks: true,
        maxFiles: 1,
        autoProcessQueue: false,
        autoQueue: false,
    });

    dz.on("addedfile", function (file) {
        if (this.files.length > 1) this.removeFile(this.files[0]);

        let progress = 0;
        const interval = setInterval(() => {
            progress += Math.random() * 25 + 10;
            if (progress >= 100) {
                progress = 100;
                clearInterval(interval);
                dz.emit("success", file, "success", null);
                dz.emit("complete", file);
            }
            dz.emit(
                "uploadprogress",
                file,
                progress,
                (file.size * progress) / 100,
            );
        }, 80);
    });

    return dz;
}

function submitWithFetch(form, formData, toastParam) {
    bootstrap.Modal.getOrCreateInstance(form.closest(".modal"))?.hide();

    setTimeout(() => {
        if (typeof window.triggerTableLoader === "function") {
            window.triggerTableLoader(function () {
                fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: { "X-Requested-With": "XMLHttpRequest" },
                }).then((res) => {
                    if (res.redirected) {
                        const url = new URL(res.url);
                        url.searchParams.set("toast", toastParam);
                        window.location.href = url.toString();
                    }
                });
            });
        } else {
            fetch(form.action, {
                method: "POST",
                body: formData,
                headers: { "X-Requested-With": "XMLHttpRequest" },
            }).then((res) => {
                if (res.redirected) {
                    const url = new URL(res.url);
                    url.searchParams.set("toast", toastParam);
                    window.location.href = url.toString();
                }
            });
        }
    }, 250);
}

// ── Registry instance per modal (biar ga double-init) ──
const _instances = {};

const DropzoneManager = {
    /**
     * Daftarkan satu set modal (add + edit) dengan config masing-masing.
     *
     * @param {Object} cfg
     * @param {string} cfg.modalAddId       - ID modal add (tanpa #)
     * @param {string} cfg.modalEditId      - ID modal edit (tanpa #)
     * @param {string} cfg.formAddId        - ID form add (tanpa #)
     * @param {string} cfg.formEditId       - ID form edit (tanpa #)
     * @param {string} cfg.dzAddSelector    - CSS selector dropzone add (contoh: '#dropzone-add-barang')
     * @param {string} cfg.dzEditSelector   - CSS selector dropzone edit
     * @param {boolean} [cfg.fotoRequired]  - Apakah foto wajib di form add? (default: true)
     * @param {string}  [cfg.toastAdd]      - Param toast setelah add (default: 'success')
     * @param {string}  [cfg.toastEdit]     - Param toast setelah edit (default: 'updated')
     */
    init(cfg) {
        const {
            modalAddId,
            modalEditId,
            formAddId,
            formEditId,
            dzAddSelector,
            dzEditSelector,
            fotoRequired = true,
            toastAdd = "success",
            toastEdit = "updated",
        } = cfg;

        const modalAddEl = document.getElementById(modalAddId);
        const modalEditEl = document.getElementById(modalEditId);

        if (!modalAddEl || !modalEditEl) return; // halaman ini ga punya modal ini, skip

        // ── INIT ADD DROPZONE on first open ──
        modalAddEl.addEventListener("shown.bs.modal", function () {
            if (!_instances[modalAddId]) {
                _instances[modalAddId] = createDropzone(
                    dzAddSelector,
                    document.getElementById(formAddId),
                );
            }
        });

        modalAddEl.addEventListener("hidden.bs.modal", function () {
            _instances[modalAddId]?.removeAllFiles(true);
        });

        // ── INIT / RESET EDIT DROPZONE on open ──
        modalEditEl.addEventListener("shown.bs.modal", function () {
            if (!_instances[modalEditId]) {
                _instances[modalEditId] = createDropzone(
                    dzEditSelector,
                    document.getElementById(formEditId),
                );
            } else {
                _instances[modalEditId].removeAllFiles(true);
                _instances[modalEditId].options.url =
                    document
                        .getElementById(formEditId)
                        ?.getAttribute("action") || "/";
            }
        });

        modalEditEl.addEventListener("hidden.bs.modal", function () {
            _instances[modalEditId]?.removeAllFiles(true);
        });

        // ── SUBMIT ADD ──
        document
            .getElementById(formAddId)
            ?.addEventListener("submit", function (e) {
                e.preventDefault();

                if (!this.checkValidity()) {
                    this.reportValidity();
                    return;
                }

                const dz = _instances[modalAddId];

                if (fotoRequired && (!dz || dz.files.length === 0)) {
                    Swal.fire({
                        icon: "warning",
                        title: "Foto belum dipilih!",
                        text: "Silahkan upload foto terlebih dahulu.",
                        confirmButtonText: "OK",
                        customClass: {
                            confirmButton: "btn btn-primary",
                            popup: "rounded-4",
                        },
                        buttonsStyling: false,
                        theme: isDark() ? "dark" : "light",
                    });
                    return;
                }

                const formData = new FormData(this);
                if (dz && dz.files.length > 0) {
                    const f = dz.files[0];
                    formData.set(
                        "foto",
                        new File([f], f.name, { type: f.type }),
                    );
                }

                submitWithFetch(this, formData, toastAdd);
            });

        // ── SUBMIT EDIT ──
        document
            .getElementById(formEditId)
            ?.addEventListener("submit", function (e) {
                e.preventDefault();

                if (!this.checkValidity()) {
                    this.reportValidity();
                    return;
                }

                const dz = _instances[modalEditId];
                const formData = new FormData(this);

                if (dz && dz.files.length > 0) {
                    const f = dz.files[0];
                    formData.set(
                        "foto",
                        new File([f], f.name, { type: f.type }),
                    );
                }

                submitWithFetch(this, formData, toastEdit);
            });
    },
};

// Expose ke global
window.DropzoneManager = DropzoneManager;
