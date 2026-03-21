("use strict");

window.addEventListener("beforeunload", function () {
    if (typeof Swal !== "undefined") {
        Swal.close();
    }
});

const addKategoriModal = new bootstrap.Modal(
    document.getElementById("addKategori"),
);
const editKategoriModal = new bootstrap.Modal(
    document.getElementById("editKategori"),
);

document.addEventListener("DOMContentLoaded", function () {
    const dt_kategori_table = document.querySelector(".datatables-kategori");

    if (dt_kategori_table) {
        var dt_kategori = new DataTable(dt_kategori_table, {
            data: typeof kategoriData !== "undefined" ? kategoriData : [],
            columns: [
                { data: "id" },
                {
                    data: "id",
                    orderable: false,
                    render: DataTable.render.select(),
                },
                { data: "nama" },
                { data: "slug" },
                { data: "id" },
            ],
            columnDefs: [
                {
                    // Responsive control column
                    className: "control",
                    searchable: false,
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function () {
                        return "";
                    },
                },
                {
                    // Checkbox column
                    targets: 1,
                    orderable: false,
                    searchable: false,
                    responsivePriority: 3,
                    render: function () {
                        return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                    },
                    checkboxes: {
                        selectAllRender:
                            '<input type="checkbox" class="form-check-input">',
                    },
                },
                {
                    // Nama Kategori with foto
                    targets: 2,
                    responsivePriority: 1,
                    render: function (data, type, full, meta) {
                        const nama = full["nama"];
                        const foto = full["foto"];
                        const id = full["id"];

                        let imgOutput;
                        if (foto) {
                            imgOutput = `<img src="/storage/${foto}" alt="Kategori-${id}" class="rounded" style="width:38px;height:38px;object-fit:cover;">`;
                        } else {
                            const states = [
                                "success",
                                "danger",
                                "warning",
                                "info",
                                "dark",
                                "primary",
                                "secondary",
                            ];
                            const state =
                                states[
                                    Math.floor(Math.random() * states.length)
                                ];
                            const initials = nama.substring(0, 2).toUpperCase();
                            imgOutput = `<span class="avatar-initial rounded-2 bg-label-${state}">${initials}</span>`;
                        }

                        return `
                            <div class="d-flex justify-content-start align-items-center">
                                <div class="avatar-wrapper">
                                    <div class="avatar avatar me-2 me-sm-4 rounded-2 bg-label-secondary">${imgOutput}</div>
                                </div>
                                <div class="d-flex flex-column">
                                    <h6 class="text-nowrap mb-0">${nama}</h6>
                                </div>
                            </div>`;
                    },
                },
                {
                    // Slug
                    targets: 3,
                    responsivePriority: 4,
                    render: function (data, type, full, meta) {
                        return `<span class="badge bg-label-secondary">${full["slug"]}</span>`;
                    },
                },
                {
                    // Actions
                    targets: -1,
                    title: "Actions",
                    searchable: false,
                    orderable: false,
                    responsivePriority: 1,
                    render: function (data, type, full, meta) {
                        const slug = full["slug"];
                        const id = full["id"];

                        return `
                            <div class="d-inline-block text-nowrap">
                                <button type="button" class="btn btn-icon text-primary rounded-pill waves-effect"
                                    data-bs-toggle="modal" data-bs-target="#modalShow-${slug}">
                                    <i class="icon-base ri ri-eye-line icon-22px"></i>
                                </button>
                                <button type="button" class="btn btn-icon text-warning rounded-pill waves-effect edit-kategori"
                                    data-id="${id}" data-slug="${slug}">
                                    <i class="icon-base ri ri-edit-box-line icon-22px"></i>
                                </button>
                                <button type="button" class="btn btn-icon text-danger rounded-pill waves-effect delete-kategori"
                                    data-id="${id}" data-name="${full["nama"]}">
                                    <i class="icon-base ri ri-delete-bin-2-line icon-22px"></i>
                                </button>
                            </div>`;
                    },
                },
            ],
            select: { style: "multi", selector: "td:nth-child(2)" },
            order: [2, "asc"],
            layout: {
                topStart: {
                    rowClass:
                        "card-header d-flex border-top rounded-0 flex-wrap py-0 flex-column flex-md-row align-items-start",
                    features: [
                        {
                            search: {
                                className: "me-5 ms-n4 pe-5 mb-n6 mb-md-0",
                                placeholder: "Cari Kategori",
                                text: "_INPUT_",
                            },
                        },
                    ],
                },
                topEnd: {
                    rowClass: "row m-3 mx-2 my-0 justify-content-between",
                    features: [
                        {
                            pageLength: {
                                menu: [5, 10, 25, 50],
                                text: "_MENU_",
                            },
                            buttons: [
                                {
                                    text: '<i class="icon-base ri ri-add-line me-0 me-sm-1 icon-16px"></i><span class="d-none d-sm-inline-block">Add Kategori</span>',
                                    className: "add-new btn btn-primary",
                                    action: function () {
                                        if (
                                            typeof window.triggerTableLoader ===
                                            "function"
                                        ) {
                                            window.triggerTableLoader(
                                                function () {
                                                    addKategoriModal.show();
                                                },
                                            );
                                        } else {
                                            addKategoriModal.show();
                                        }
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
            displayLength: 10,
            language: {
                paginate: {
                    next: '<i class="icon-base ri ri-arrow-right-s-line scaleX-n1-rtl icon-22px"></i>',
                    previous:
                        '<i class="icon-base ri ri-arrow-left-s-line scaleX-n1-rtl icon-22px"></i>',
                },
            },
            responsive: {
                details: {
                    display: DataTable.Responsive.display.modal({
                        header: function (row) {
                            const data = row.data();
                            return "Detail: " + data["nama"];
                        },
                    }),
                    type: "column",
                    renderer: function (api, rowIdx, columns) {
                        const data = columns
                            .map(function (col) {
                                return col.title !== ""
                                    ? `<tr data-dt-row="${col.rowIndex}" data-dt-column="${col.columnIndex}">
                                        <td>${col.title}:</td>
                                        <td>${col.data}</td>
                                       </tr>`
                                    : "";
                            })
                            .join("");
                        if (data) {
                            const div = document.createElement("div");
                            div.classList.add("table-responsive");
                            const table = document.createElement("table");
                            div.appendChild(table);
                            table.classList.add("table");
                            const tbody = document.createElement("tbody");
                            tbody.innerHTML = data;
                            table.appendChild(tbody);
                            return div;
                        }
                        return false;
                    },
                },
            },
            initComplete: function () {
                // Tidak ada filter tambahan — hanya DataTable default
            },
        });
    }

    // Fix class styling setelah render
    setTimeout(() => {
        const elementsToModify = [
            { selector: ".dt-buttons .btn", classToRemove: "btn-secondary" },
            { selector: ".dt-search .form-control", classToAdd: "ms-0" },
            { selector: ".dt-search", classToAdd: "mb-0 mb-md-5" },
            { selector: ".dt-layout-table", classToRemove: "row mt-2" },
            { selector: ".dt-layout-end", classToAdd: "gap-md-2 gap-0 mt-0" },
            { selector: ".dt-layout-start", classToAdd: "mt-0" },
            {
                selector: ".dt-layout-end .dt-buttons.btn-group",
                classToAdd: "mb-md-0 mb-5",
            },
            {
                selector: ".dt-layout-full",
                classToRemove: "col-md col-12",
                classToAdd: "table-responsive",
            },
        ];

        elementsToModify.forEach(({ selector, classToRemove, classToAdd }) => {
            document.querySelectorAll(selector).forEach((element) => {
                if (classToRemove)
                    classToRemove
                        .split(" ")
                        .forEach((c) => element.classList.remove(c));
                if (classToAdd)
                    classToAdd
                        .split(" ")
                        .forEach((c) => element.classList.add(c));
            });
        });
    }, 100);
});

// ===== DELETE =====
$(document).on("click", ".delete-kategori", function () {
    const id = $(this).data("id");
    const name = $(this).data("name");
    const token = $('meta[name="csrf-token"]').attr("content");
    const isDark =
        document.documentElement.getAttribute("data-bs-theme") === "dark";

    Swal.fire({
        title: "Hapus Kategori!",
        html: `<span style="line-height:25px;">Apakah anda yakin akan menghapus kategori <strong>"${name}"</strong>?</span>`,
        icon: "warning",
        width: "520px",
        theme: isDark ? "dark" : "light",
        showCancelButton: true,
        confirmButtonText: "Ya, hapus!",
        cancelButtonText: "Batal",
        customClass: {
            confirmButton: "btn btn-danger",
            cancelButton: "btn btn-outline-danger ms-1",
            popup: "rounded-4",
        },
        buttonsStyling: false,
    }).then(function (result) {
        if (result.isConfirmed) {
            const form = document.createElement("form");
            form.action = `/admin/kategori/${id}`;
            form.method = "POST";
            form.innerHTML = `
                <input type="hidden" name="_token" value="${token}">
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
        }
    });
});

// ===== EDIT =====
$(document).on("click", ".edit-kategori", function () {
    const id = $(this).data("id");
    const slug = $(this).data("slug");

    const kategori = kategoriData.find((k) => k.id == id);
    if (!kategori) return;

    const form = document.getElementById("formEditKategori");

    // Set route update — sesuaikan dengan route kamu
    form.action = `/admin/kategori/${id}`;

    // Isi field
    document.getElementById("editNamaKategori").value = kategori.nama;

    // Foto preview
    const previewWrapper = document.getElementById("editFotoPreviewWrapper");
    const previewImg = document.getElementById("editFotoPreview");
    if (kategori.foto) {
        previewImg.src = `/storage/${kategori.foto}`;
        previewWrapper.style.display = "block";
    } else {
        previewWrapper.style.display = "none";
    }

    if (typeof window.triggerTableLoader === "function") {
        window.triggerTableLoader(function () {
            editKategoriModal.show();
        });
    } else {
        editKategoriModal.show();
    }
});
DropzoneManager.init({
    modalAddId: "addKategori",
    modalEditId: "editKategori",
    formAddId: "formAddKategori",
    formEditId: "formEditKategori",
    dzAddSelector: "#dropzone-add-kategori",
    dzEditSelector: "#dropzone-edit-kategori",
    fotoRequired: true,
});
