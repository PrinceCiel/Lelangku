/**
 * lelang.js
 * DataTable + Filter (tanggal range & status via initComplete) + Modal Add/Edit + Delete SweetAlert
 */

("use strict");

window.addEventListener("beforeunload", function () {
    if (typeof Swal !== "undefined") Swal.close();
});

// ── Bootstrap modal instances ────────────────────────────────
const addLelangModal = new bootstrap.Modal(
    document.getElementById("modalAddLelang"),
);
const editLelangModal = new bootstrap.Modal(
    document.getElementById("modalEditLelang"),
);

// ── Flatpickr instances untuk populate edit ──────────────────
let fpMulaiAdd = null;
let fpBerakhirAdd = null;
let fpMulaiEdit = null;
let fpBerakhirEdit = null;

// appendTo body → calendar tidak ketutupan modal
const FP_CONFIG = {
    enableTime: true,
    dateFormat: "Y-m-d H:i",
    time_24hr: true,
    allowInput: true,
    appendTo: document.body,
};

// ── Month map untuk custom range filter ─────────────────────
const MONTH_MAP = {
    Jan: 0,
    Feb: 1,
    Mar: 2,
    Apr: 3,
    Mei: 4,
    May: 4,
    Jun: 5,
    Jul: 6,
    Agu: 7,
    Aug: 7,
    Sep: 8,
    Okt: 9,
    Oct: 9,
    Nov: 10,
    Des: 11,
    Dec: 11,
};

function parseIndonesianDate(str) {
    if (!str) return null;
    const m = str.match(/(\d{2})\s+(\w+)\s+(\d{4})/);
    if (!m) return null;
    const month = MONTH_MAP[m[2]];
    if (month === undefined) return null;
    return new Date(parseInt(m[3], 10), month, parseInt(m[1], 10));
}

function parseInputDate(val) {
    if (!val) return null;
    const [y, mo, d] = val.split("-").map(Number);
    return new Date(y, mo - 1, d);
}

// ── Auto-generate kode lelang: LLG-YYYYMMDD-XXXX ────────────
function generateKodeLelang() {
    const now = new Date();
    const ymd =
        now.getFullYear().toString() +
        String(now.getMonth() + 1).padStart(2, "0") +
        String(now.getDate()).padStart(2, "0");
    const rand = Math.floor(1000 + Math.random() * 9000);
    return `LLG-${ymd}-${rand}`;
}

const STATUS_ORDER = { dibuka: 0, ditutup: 1, selesai: 2 };

// ── Custom range filter — hanya aktif di tabel lelang ────────
$.fn.dataTable.ext.search.push(function (settings, data) {
    if (!settings.nTable.classList.contains("datatables-lelang")) return true;

    const min = parseInputDate($("#filterDateMin").val());
    const max = parseInputDate($("#filterDateMax").val());
    const mulai = parseIndonesianDate(data[4]); // jadwal_mulai aja

    if (!mulai) return true;
    if (!min && !max) return true;
    if (min && !max) return mulai >= min; // dari tanggal → no upper limit
    if (!min && max) return mulai <= max; // sampai tanggal → no lower limit
    return mulai >= min && mulai <= max; // range → mulai dalam rentang
});

// ── Status badge map ─────────────────────────────────────────
const STATUS_MAP = {
    dibuka: { label: "Dibuka", cls: "bg-label-warning" },
    ditutup: { label: "Ditutup", cls: "bg-label-danger" },
    selesai: { label: "Selesai", cls: "bg-label-success" },
};

// ════════════════════════════════════════════════════════════
document.addEventListener("DOMContentLoaded", function () {
    // ── Init Flatpickr ───────────────────────────────────────
    fpMulaiAdd = flatpickr("#addJadwalMulai", FP_CONFIG);
    fpBerakhirAdd = flatpickr("#addJadwalBerakhir", FP_CONFIG);
    fpMulaiEdit = flatpickr("#editJadwalMulai", FP_CONFIG);
    fpBerakhirEdit = flatpickr("#editJadwalBerakhir", FP_CONFIG);

    const dtEl = document.querySelector(".datatables-lelang");
    if (!dtEl) return;

    // ── Init DataTable ───────────────────────────────────────
    const dt = new DataTable(dtEl, {
        data: typeof lelangsData !== "undefined" ? lelangsData : [],

        columns: [
            { data: "id" }, // 0  responsive ctrl
            { data: "id" }, // 1  checkbox
            { data: "kode_lelang" }, // 2  kode
            { data: "barang" }, // 3  nama barang
            { data: "jadwal_mulai" }, // 4  tgl mulai
            { data: "jadwal_berakhir" }, // 5  tgl berakhir
            { data: "status" }, // 6  status
            { data: "id" }, // 7  aksi
        ],

        columnDefs: [
            // 0: responsive control
            {
                targets: 0,
                className: "control",
                searchable: false,
                orderable: false,
                responsivePriority: 2,
                render: () => "",
            },

            // 1: checkbox
            {
                targets: 1,
                orderable: false,
                searchable: false,
                responsivePriority: 3,
                checkboxes: true,
                render: () =>
                    '<input type="checkbox" class="dt-checkboxes form-check-input">',
                checkboxes: {
                    selectAllRender:
                        '<input type="checkbox" class="form-check-input">',
                },
            },

            // 2: kode lelang
            {
                targets: 2,
                responsivePriority: 1,
                render: function (data, type) {
                    if (type !== "display") return data;
                    return `<code class="text-primary fw-semibold">${data}</code>`;
                },
            },

            // 3: nama barang + foto
            {
                targets: 3,
                responsivePriority: 1,
                render: function (data, type, full) {
                    const nama = full.barang?.nama ?? "—";
                    const foto = full.barang?.foto ?? null;
                    let img = foto
                        ? `<img src="/storage/${foto}" alt="${nama}" class="rounded" style="width:38px;height:38px;object-fit:cover;">`
                        : `<span class="avatar-initial rounded-2 bg-label-primary">${nama.substring(0, 2).toUpperCase()}</span>`;
                    return `
                        <div class="d-flex justify-content-start align-items-center">
                            <div class="avatar-wrapper">
                                <div class="avatar avatar me-2 me-sm-4 rounded-2 bg-label-secondary">${img}</div>
                            </div>
                            <div class="d-flex flex-column">
                                <h6 class="text-nowrap mb-0">${nama}</h6>
                            </div>
                        </div>`;
                },
            },

            // 4: tanggal mulai
            {
                targets: 4,
                render: function (data, type) {
                    if (!data) return "—";
                    const d = new Date(data);
                    const fmt =
                        d.toLocaleDateString("id-ID", {
                            day: "2-digit",
                            month: "short",
                            year: "numeric",
                        }) +
                        ", " +
                        d.toLocaleTimeString("id-ID", {
                            hour: "2-digit",
                            minute: "2-digit",
                        });
                    return type === "display"
                        ? `<span><i class="ri ri-calendar-line me-1 text-muted"></i>${fmt}</span>`
                        : fmt;
                },
            },

            // 5: tanggal berakhir
            {
                targets: 5,
                render: function (data, type) {
                    if (!data) return "—";
                    const d = new Date(data);
                    const fmt =
                        d.toLocaleDateString("id-ID", {
                            day: "2-digit",
                            month: "short",
                            year: "numeric",
                        }) +
                        ", " +
                        d.toLocaleTimeString("id-ID", {
                            hour: "2-digit",
                            minute: "2-digit",
                        });
                    return type === "display"
                        ? `<span><i class="ri ri-calendar-check-line me-1 text-muted"></i>${fmt}</span>`
                        : fmt;
                },
            },

            // 6: status
            {
                targets: 6,
                type: "status", // ← tambah ini
                render: function (data, type) {
                    const s = STATUS_MAP[data] ?? {
                        label: data,
                        cls: "bg-label-secondary",
                    };
                    return type === "display"
                        ? `<span class="badge rounded-pill ${s.cls} text-capitalize">${s.label}</span>`
                        : data;
                },
            },

            // 7: aksi — target modal pakai id (bukan slug)
            {
                targets: 7,
                title: "Actions",
                searchable: false,
                orderable: false,
                responsivePriority: 1,
                render: function (data, type, full) {
                    const id = full.id;
                    const status = full.status;

                    let editBtn = "";
                    let deleteBtn = "";

                    if (status === "ditutup") {
                        editBtn = `
                            <button type="button"
                                    class="btn btn-icon text-warning rounded-pill waves-effect edit-lelang"
                                    data-id="${id}" title="Edit">
                                <i class="icon-base ri ri-edit-box-line icon-22px"></i>
                            </button>`;
                        deleteBtn = `
                            <button type="button"
                                    class="btn btn-icon text-danger rounded-pill waves-effect delete-lelang"
                                    data-id="${id}" data-kode="${full.kode_lelang}" title="Hapus">
                                <i class="icon-base ri ri-delete-bin-2-line icon-22px"></i>
                            </button>`;
                    } else if (status === "selesai") {
                        deleteBtn = `
                            <button type="button"
                                    class="btn btn-icon text-danger rounded-pill waves-effect delete-lelang"
                                    data-id="${id}" data-kode="${full.kode_lelang}" title="Hapus">
                                <i class="icon-base ri ri-delete-bin-2-line icon-22px"></i>
                            </button>`;
                    }

                    return `
                        <div class="d-inline-block text-nowrap">
                            <button type="button"
                                    class="btn btn-icon text-primary rounded-pill waves-effect"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalDetail-${id}"
                                    title="Detail">
                                <i class="icon-base ri ri-eye-line icon-22px"></i>
                            </button>
                            ${editBtn}
                            ${deleteBtn}
                        </div>`;
                },
            },
        ],

        select: { style: "multi", selector: "td:nth-child(2)" },
        order: [[6, "asc"]],

        layout: {
            topStart: {
                rowClass:
                    "card-header d-flex border-top rounded-0 flex-wrap py-0 flex-column flex-md-row align-items-start",
                features: [
                    {
                        search: {
                            className: "me-5 ms-n4 pe-5 mb-n6 mb-md-0",
                            placeholder: "Cari lelang…",
                            text: "_INPUT_",
                        },
                    },
                ],
            },
            topEnd: {
                rowClass: "row m-3 mx-2 my-0 justify-content-between",
                features: [
                    {
                        pageLength: { menu: [5, 10, 25, 50], text: "_MENU_" },
                        buttons: [
                            {
                                extend: "collection",
                                className:
                                    "btn btn-outline-secondary dropdown-toggle me-4 waves-effect",
                                text: '<span class="d-flex align-items-center"><i class="icon-base ri ri-upload-2-line icon-16px me-sm-1"></i><span class="d-none d-sm-inline-block">Export</span></span>',
                                buttons: [
                                    {
                                        extend: "print",
                                        text: '<span class="d-flex align-items-center"><i class="icon-base ri ri-printer-line me-1"></i>Print</span>',
                                        exportOptions: {
                                            columns: [2, 3, 4, 5, 6],
                                            format: {
                                                body: (inner) => {
                                                    const el =
                                                        document.createElement(
                                                            "div",
                                                        );
                                                    el.innerHTML = inner;
                                                    return (
                                                        el.textContent ||
                                                        el.innerText ||
                                                        ""
                                                    );
                                                },
                                            },
                                        },
                                    },
                                    {
                                        extend: "csv",
                                        text: '<span class="d-flex align-items-center"><i class="icon-base ri ri-file-text-line me-1"></i>Csv</span>',
                                        exportOptions: {
                                            columns: [2, 3, 4, 5, 6],
                                        },
                                    },
                                    {
                                        extend: "excel",
                                        text: '<span class="d-flex align-items-center"><i class="icon-base ri ri-file-excel-line me-1"></i>Excel</span>',
                                        exportOptions: {
                                            columns: [2, 3, 4, 5, 6],
                                        },
                                    },
                                    {
                                        extend: "pdf",
                                        text: '<span class="d-flex align-items-center"><i class="icon-base ri ri-file-pdf-line me-1"></i>Pdf</span>',
                                        exportOptions: {
                                            columns: [2, 3, 4, 5, 6],
                                        },
                                        orientation: "landscape",
                                    },
                                ],
                            },
                            {
                                text: '<i class="icon-base ri ri-add-line me-0 me-sm-1 icon-16px"></i><span class="d-none d-sm-inline-block">Tambah Lelang</span>',
                                className: "add-new btn btn-primary",
                                action: function () {
                                    window.triggerTableLoader(function () {
                                        // ← tambah ini
                                        document.getElementById(
                                            "addKodeLelang",
                                        ).value = generateKodeLelang();
                                        addLelangModal.show();
                                    });
                                },
                            },
                        ],
                    },
                ],
            },
            bottomStart: {
                rowClass: "row mx-3 justify-content-between",
                features: ["info"],
            },
            bottomEnd: "paging",
        },

        displayLength: 5,

        language: {
            paginate: {
                next: '<i class="icon-base ri ri-arrow-right-s-line scaleX-n1-rtl icon-22px"></i>',
                previous:
                    '<i class="icon-base ri ri-arrow-left-s-line scaleX-n1-rtl icon-22px"></i>',
                first: '<i class="icon-base ri ri-skip-back-mini-line scaleX-n1-rtl icon-22px"></i>',
                last: '<i class="icon-base ri ri-skip-forward-mini-line scaleX-n1-rtl icon-22px"></i>',
            },
        },

        responsive: {
            details: {
                display: DataTable.Responsive.display.modal({
                    header: (row) =>
                        "Details of " + (row.data().kode_lelang ?? ""),
                }),
                type: "column",
                renderer: function (api, rowIdx, columns) {
                    const data = columns
                        .map((col) =>
                            col.title !== ""
                                ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                               <td>${col.title}:</td><td>${col.data}</td>
                           </tr>`
                                : "",
                        )
                        .join("");
                    if (!data) return false;
                    const div = document.createElement("div");
                    div.classList.add("table-responsive");
                    const table = document.createElement("table");
                    table.classList.add("table");
                    const tbody = document.createElement("tbody");
                    tbody.innerHTML = data;
                    table.appendChild(tbody);
                    div.appendChild(table);
                    return div;
                },
            },
        },

        // ── initComplete: inject filter inputs ke placeholder div ──
        initComplete: function () {
            const api = this.api();

            // ── Filter: Status ──────────────────────────────
            api.columns(6).every(function () {
                const column = this;
                const select = document.createElement("select");
                select.id = "filterStatus";
                select.className = "form-select text-capitalize";
                select.innerHTML = '<option value="">Select Status</option>';
                document.querySelector(".lelang_status")?.appendChild(select);

                select.addEventListener("change", function () {
                    const val = select.value;
                    column
                        .search(val ? "^" + val + "$" : "", true, false)
                        .draw();
                });

                // Isi dari data unik
                column
                    .data()
                    .unique()
                    .sort()
                    .each(function (d) {
                        if (!d) return;
                        const opt = document.createElement("option");
                        opt.value = d;
                        opt.textContent =
                            d.charAt(0).toUpperCase() + d.slice(1);
                        select.appendChild(opt);
                    });
            });

            // ── Filter: Dari Tanggal ────────────────────────
            const wrapperMin = document.querySelector(".lelang_date_min");
            if (wrapperMin) {
                wrapperMin.innerHTML = `
        <label class="form-label small fw-semibold mb-1">Dari Tanggal</label>
        <div class="input-group input-group-merge input-group-sm" data-fp-min>
            <span class="input-group-text"><i class="ri ri-calendar-line"></i></span>
            <input type="text" id="filterDateMin" class="form-control" placeholder="Pilih tanggal awal" data-input>
            <span class="input-group-text cursor-pointer" data-clear title="Reset">
                <i class="ri ri-close-line"></i>
            </span>
        </div>`;
                flatpickr("[data-fp-min]", {
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    wrap: true,
                    appendTo: document.body,
                    onReady: function (_, __, instance) {
                        instance.calendarContainer.style.zIndex = "99999";
                    },
                    onChange: function () {
                        api.draw();
                    },
                    onClose: function () {
                        api.draw();
                    },
                });
            }

            // ── Filter: Sampai Tanggal ──────────────────────
            const wrapperMax = document.querySelector(".lelang_date_max");
            if (wrapperMax) {
                wrapperMax.innerHTML = `
        <label class="form-label small fw-semibold mb-1">Sampai Tanggal</label>
        <div class="input-group input-group-merge input-group-sm" data-fp-max>
            <span class="input-group-text"><i class="ri ri-calendar-check-line"></i></span>
            <input type="text" id="filterDateMax" class="form-control" placeholder="Pilih tanggal akhir" data-input>
            <span class="input-group-text cursor-pointer" data-clear title="Reset">
                <i class="ri ri-close-line"></i>
            </span>
        </div>`;
                flatpickr("[data-fp-max]", {
                    dateFormat: "Y-m-d",
                    allowInput: true,
                    wrap: true,
                    appendTo: document.body,
                    onReady: function (_, __, instance) {
                        instance.calendarContainer.style.zIndex = "99999";
                    },
                    onChange: function () {
                        api.draw();
                    },
                    onClose: function () {
                        api.draw();
                    },
                });
            }

            // ← HAPUS event listener $(document).on("change", "#filterDateMin, #filterDateMax")
            // karena sudah pakai onChange di flatpickr

            // Event listener setelah inject
            $(document).on(
                "change",
                "#filterDateMin, #filterDateMax",
                function () {
                    api.draw();
                },
            );
        },
    });

    // ── Fix class setelah render (sama persis barang.js) ────
    setTimeout(() => {
        [
            { sel: ".dt-buttons .btn", rm: "btn-secondary" },
            { sel: ".dt-search .form-control", add: "ms-0" },
            { sel: ".dt-search", add: "mb-0 mb-md-5" },
            { sel: ".dt-layout-table", rm: "row mt-2" },
            { sel: ".dt-layout-end", add: "gap-md-2 gap-0 mt-0" },
            { sel: ".dt-layout-start", add: "mt-0" },
            {
                sel: ".dt-layout-full",
                rm: "col-md col-12",
                add: "table-responsive",
            },
        ].forEach(({ sel, rm, add }) => {
            document.querySelectorAll(sel).forEach((el) => {
                rm && rm.split(" ").forEach((c) => el.classList.remove(c));
                add && add.split(" ").forEach((c) => el.classList.add(c));
            });
        });
    }, 100);

    // ── Reset filter ─────────────────────────────────────────
    $("#resetFilter").on("click", function () {
        window.triggerTableLoader(function () {
            $("#filterDateMin, #filterDateMax").val("");

            // Reset flatpickr instance juga
            document.querySelector("[data-fp-min]")?._flatpickr?.clear();
            document.querySelector("[data-fp-max]")?._flatpickr?.clear();

            $("#filterStatus").val("");
            dt.column(6).search("").draw();
        });
    });

    // ── Generate ulang kode ───────────────────────────────────
    document
        .getElementById("btnGenKode")
        ?.addEventListener("click", function () {
            document.getElementById("addKodeLelang").value =
                generateKodeLelang();
        });
}); // end DOMContentLoaded

// ── Edit: populate modal ──────────────────────────────────────
$(document).on("click", ".edit-lelang", function () {
    const id = $(this).data("id");
    const lelang = lelangsData.find((l) => l.id == id);
    if (!lelang) return;

    const form = document.getElementById("formEditLelang");
    form.action = `/admin/lelang/${id}`;

    document.getElementById("editKodeLelang").value = lelang.kode_lelang;
    document.getElementById("editBarang").value =
        lelang.id_barang ?? lelang.barang?.id ?? "";

    if (fpMulaiEdit) fpMulaiEdit.setDate(lelang.jadwal_mulai, false);
    if (fpBerakhirEdit) fpBerakhirEdit.setDate(lelang.jadwal_berakhir, false);

    document
        .querySelectorAll('#modalEditLelang input[name="status"]')
        .forEach((r) => {
            r.checked = r.value === lelang.status;
        });

    window.triggerTableLoader(function () {
        // ← ganti dari editLelangModal.show() langsung
        editLelangModal.show();
    });
});

// ── Delete: SweetAlert confirm ────────────────────────────────
$(document).on("click", ".delete-lelang", function () {
    const id = $(this).data("id");
    const kode = $(this).data("kode");
    const token = $('meta[name="csrf-token"]').attr("content");
    const isDark =
        document.documentElement.getAttribute("data-bs-theme") === "dark";

    Swal.fire({
        title: "Delete!",
        html: `<span style="line-height:1.8">
                   Apakah anda yakin akan menghapus lelang <strong>${kode}</strong>?
               </span>`,
        icon: "warning",
        width: "520px",
        theme: isDark ? "dark" : "light",
        showCancelButton: true,
        confirmButtonText: "Ya, yakin!",
        cancelButtonText: "Batal",
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-outline-danger ms-1",
            popup: "rounded-4",
        },
        buttonsStyling: false,
    }).then(function (result) {
        if (!result.isConfirmed) return;

        const form = document.createElement("form");
        form.action = `/admin/lelang/${id}`;
        form.method = "POST";
        form.innerHTML = `
            <input type="hidden" name="_token"  value="${token}">
            <input type="hidden" name="_method" value="DELETE">
        `;
        document.body.appendChild(form);

        if (typeof window.triggerTableLoader === "function") {
            window.triggerTableLoader(function () {
                Swal.close();
                setTimeout(() => form.submit(), 50);
            });
        } else {
            form.submit();
        }
    });
});
