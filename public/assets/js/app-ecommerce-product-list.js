/**
 * app-ecommerce-product-list-custom (Laravel Integration)
 */

("use strict");
window.addEventListener("beforeunload", function () {
    if (typeof Swal !== "undefined") {
        Swal.close();
    }
});
const addBarangModal = new bootstrap.Modal(
    document.getElementById("addBarang"),
);
// Datatable (js)
document.addEventListener("DOMContentLoaded", function (e) {
    let borderColor, bodyBg, headingColor;

    if (typeof config !== "undefined") {
        borderColor = config.colors.borderColor;
        bodyBg = config.colors.bodyBg;
        headingColor = config.colors.headingColor;
    }

    // Variable declaration for table
    const dt_product_table = document.querySelector(".datatables-products");

    // Status object mapping
    const statusObj = {
        Baru: { title: "Baru", class: "bg-label-success" },
        Bekas: { title: "Bekas", class: "bg-label-warning" },
        Rusak: { title: "Rusak", class: "bg-label-danger" },
    };

    // Get unique categories from barangs data (passed from Laravel)
    let categoryObj = {};
    if (typeof barangsData !== "undefined") {
        barangsData.forEach((item, index) => {
            if (item.kategori && item.kategori.nama) {
                categoryObj[item.kategori.nama] = { title: item.kategori.nama };
            }
        });
    }

    // E-commerce Products datatable
    if (dt_product_table) {
        var dt_products = new DataTable(dt_product_table, {
            data: typeof barangsData !== "undefined" ? barangsData : [],
            columns: [
                // columns according to data structure
                { data: "id" },
                {
                    data: "id",
                    orderable: false,
                    render: DataTable.render.select(),
                },
                { data: "nama" },
                { data: "kategori" },
                { data: "jumlah" },
                { data: "harga" },
                { data: "jumlah" },
                { data: "kondisi" },
                { data: "id" },
            ],
            columnDefs: [
                {
                    // For Responsive
                    className: "control",
                    searchable: false,
                    orderable: false,
                    responsivePriority: 2,
                    targets: 0,
                    render: function (data, type, full, meta) {
                        return "";
                    },
                },
                {
                    // For Checkboxes
                    targets: 1,
                    orderable: false,
                    searchable: false,
                    responsivePriority: 3,
                    checkboxes: true,
                    render: function () {
                        return '<input type="checkbox" class="dt-checkboxes form-check-input">';
                    },
                    checkboxes: {
                        selectAllRender:
                            '<input type="checkbox" class="form-check-input">',
                    },
                },
                {
                    // Product name with image
                    targets: 2,
                    responsivePriority: 1,
                    render: function (data, type, full, meta) {
                        let name = full["nama"];
                        let id = full["id"];
                        let image = full["foto"];

                        let output;

                        if (image) {
                            // For Product image from storage
                            let imageUrl = "/storage/" + image;
                            output = `<img src="${imageUrl}" alt="Product-${id}" class="rounded" style="width: 38px; height: 38px; object-fit: cover;">`;
                        } else {
                            // For Product badge
                            let stateNum = Math.floor(Math.random() * 6);
                            let states = [
                                "success",
                                "danger",
                                "warning",
                                "info",
                                "dark",
                                "primary",
                                "secondary",
                            ];
                            let state = states[stateNum];
                            let initials = name.substring(0, 2).toUpperCase();

                            output = `<span class="avatar-initial rounded-2 bg-label-${state}">${initials}</span>`;
                        }

                        // Creates full output for Product name and jenis_barang
                        let rowOutput = `
                            <div class="d-flex justify-content-start align-items-center product-name">
                                <div class="avatar-wrapper">
                                <div class="avatar avatar me-2 me-sm-4 rounded-2 bg-label-secondary">${output}</div>
                                </div>
                                <div class="d-flex flex-column">
                                <h6 class="text-nowrap mb-0">${name}</h6>
                                </div>
                            </div>
                            `;

                        return rowOutput;
                    },
                },
                {
                    // Category
                    targets: 3,
                    responsivePriority: 5,
                    render: function (data, type, full, meta) {
                        let categoryName =
                            full["kategori"] && full["kategori"]["nama"]
                                ? full["kategori"]["nama"]
                                : "Tidak ada kategori";

                        let categoryFoto =
                            full["kategori"] && full["kategori"]["foto"]
                                ? full["kategori"]["foto"]
                                : null;

                        if (type === "display") {
                            let badge;

                            if (categoryFoto) {
                                // Jika ada foto di database, tampilkan dari storage
                                let categoryImageUrl =
                                    "/storage/" + categoryFoto;
                                badge = `<span class="w-px-30 h-px-30 rounded-circle d-flex justify-content-center align-items-center bg-label-secondary me-3 overflow-hidden">
                                            <img src="${categoryImageUrl}" alt="${categoryName}" class="w-100 h-100 object-fit-cover">
                                        </span>`;
                            } else {
                                // Fallback jika tidak ada foto (pakai icon default atau inisial)
                                badge = `<span class="w-px-30 h-px-30 rounded-circle d-flex justify-content-center align-items-center bg-label-primary me-3">
                                    <i class="icon-base ri ri-folder-line icon-18px"></i>
                                </span>`;
                            }
                            return `
                                <span class="text-truncate d-flex align-items-center text-heading">
                                ${badge}${categoryName}
                                </span>`;
                        } else {
                            return categoryName;
                        }
                    },
                },
                {
                    // Stock (using jumlah field)
                    targets: 4,
                    orderable: false,
                    responsivePriority: 3,
                    render: function (data, type, full, meta) {
                        let stock = parseInt(full["jumlah"]);
                        let isInStock = stock > 0;

                        if (type === "display") {
                            return `
                <label class="switch switch-primary switch-sm">
                  <input type="checkbox" class="switch-input" ${isInStock ? "checked" : ""} disabled>
                  <span class="switch-toggle-slider">
                    <span class="switch-${isInStock ? "on" : "off"}"></span>
                  </span>
                </label>
                <span class="d-none">${isInStock ? "In_Stock" : "Out_of_Stock"}</span>
              `;
                        } else {
                            return isInStock ? "In_Stock" : "Out_of_Stock";
                        }
                    },
                },
                {
                    // Price
                    targets: 5,
                    render: function (data, type, full, meta) {
                        const price =
                            "Rp " +
                            parseInt(full["harga"]).toLocaleString("id-ID");
                        return "<span>" + price + "</span>";
                    },
                },
                {
                    // Qty
                    targets: 6,
                    responsivePriority: 4,
                    render: function (data, type, full, meta) {
                        const qty = full["jumlah"];
                        return "<span>" + qty + "</span>";
                    },
                },
                {
                    // Status (kondisi)
                    targets: -3,
                    render: function (data, type, full, meta) {
                        const kondisi = full["kondisi"];
                        const statusClass = statusObj[kondisi]
                            ? statusObj[kondisi].class
                            : "bg-label-secondary";

                        return `<span class="badge rounded-pill ${statusClass}" text-capitalized>${kondisi}</span>`;
                    },
                },
                {
                    // Actions
                    targets: -1,
                    title: "Actions",
                    searchable: false,
                    orderable: false,
                    render: function (data, type, full, meta) {
                        const slug = full["slug"];
                        const id = full["id"];

                        return `
              <div class="d-inline-block text-nowrap">
                <button type="button" class="btn btn-icon text-primary rounded-pill waves-effect" data-bs-toggle="modal" data-bs-target="#modalCenter-${slug}">
                  <i class="icon-base ri ri-eye-line icon-22px"></i>
                </button>
                <button type="button" class="btn btn-icon text-warning rounded-pill waves-effect edit-record"
                    data-id="${id}" data-slug="${slug}">
                    <i class="icon-base ri ri-edit-box-line icon-22px"></i>
                </button>
                <button type="button" class="btn btn-icon text-danger rounded-pill waves-effect delete-record" data-id="${id}" data-name="${full["nama"]}">
                    <i class="icon-base ri ri-delete-bin-2-line icon-22px"></i>
                </button>
              </div>
            `;
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
                                placeholder: "Search Product",
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
                                menu: [5, 10, 25, 50, 100],
                                text: "_MENU_",
                            },
                            buttons: [
                                {
                                    extend: "collection",
                                    className:
                                        "btn btn-outline-secondary dropdown-toggle me-4 waves-effect",
                                    text: '<span class="d-flex align-items-center"><i class="icon-base ri ri-upload-2-line icon-16px me-sm-1"></i> <span class="d-none d-sm-inline-block">Export</span></span>',
                                    buttons: [
                                        {
                                            extend: "print",
                                            text: `<span class="d-flex align-items-center"><i class="icon-base ri ri-printer-line me-1"></i>Print</span>`,
                                            exportOptions: {
                                                columns: [2, 3, 5, 6, 7, 8],
                                                format: {
                                                    body: function (
                                                        inner,
                                                        coldex,
                                                        rowdex,
                                                    ) {
                                                        if (inner.length <= 0)
                                                            return inner;
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
                                            customize: function (win) {
                                                if (
                                                    typeof config !==
                                                    "undefined"
                                                ) {
                                                    win.document.body.style.color =
                                                        config.colors.headingColor;
                                                    win.document.body.style.borderColor =
                                                        config.colors.borderColor;
                                                    win.document.body.style.backgroundColor =
                                                        config.colors.bodyBg;
                                                }
                                                const table =
                                                    win.document.body.querySelector(
                                                        "table",
                                                    );
                                                table.classList.add("compact");
                                                table.style.color = "inherit";
                                                table.style.borderColor =
                                                    "inherit";
                                                table.style.backgroundColor =
                                                    "inherit";
                                            },
                                        },
                                        {
                                            extend: "csv",
                                            text: `<span class="d-flex align-items-center"><i class="icon-base ri ri-file-text-line me-1"></i>Csv</span>`,
                                            exportOptions: {
                                                columns: [2, 3, 5, 6, 7, 8],
                                            },
                                        },
                                        {
                                            extend: "excel",
                                            text: `<span class="d-flex align-items-center"><i class="icon-base ri ri-file-excel-line me-1"></i>Excel</span>`,
                                            exportOptions: {
                                                columns: [2, 3, 5, 6, 7, 8],
                                            },
                                        },
                                        {
                                            extend: "pdf",
                                            text: `<span class="d-flex align-items-center"><i class="icon-base ri ri-file-pdf-line me-1"></i>Pdf</span>`,
                                            exportOptions: {
                                                columns: [2, 3, 5, 6, 7, 8],
                                            },
                                        },
                                        {
                                            extend: "copy",
                                            text: `<i class="icon-base ri ri-file-copy-line me-1"></i>Copy`,
                                            exportOptions: {
                                                columns: [2, 3, 5, 6, 7, 8],
                                            },
                                        },
                                    ],
                                },
                                {
                                    text: '<i class="icon-base ri ri-add-line me-0 me-sm-1 icon-16px"></i><span class="d-none d-sm-inline-block">Add Product</span>',
                                    className: "add-new btn btn-primary",
                                    action: function (e, dt, node, config) {
                                        window.triggerTableLoader(function () {
                                            // Pakai instance yang udah ada, JANGAN new lagi
                                            addBarangModal.show();
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

            // For responsive popup
            responsive: {
                details: {
                    display: DataTable.Responsive.display.modal({
                        header: function (row) {
                            const data = row.data();
                            return "Details of " + data["nama"];
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
                const api = this.api();

                // Adding status filter (kondisi)
                api.columns(-2).every(function () {
                    const column = this;
                    const select = document.createElement("select");
                    select.id = "ProductStatus";
                    select.className = "form-select text-capitalize";
                    select.innerHTML =
                        '<option value="">Select Status</option>';

                    document
                        .querySelector(".product_status")
                        .appendChild(select);

                    select.addEventListener("change", function () {
                        const val = select.value;
                        column
                            .search(val ? "^" + val + "$" : "", true, false)
                            .draw();
                    });

                    // Add unique kondisi values
                    const uniqueStatuses = [];
                    column
                        .data()
                        .unique()
                        .sort()
                        .each(function (d) {
                            if (d && uniqueStatuses.indexOf(d) === -1) {
                                uniqueStatuses.push(d);
                                const option = document.createElement("option");
                                option.value = d;
                                option.textContent = d;
                                select.appendChild(option);
                            }
                        });
                });

                // Adding category filter
                api.columns(3).every(function () {
                    const column = this;
                    const select = document.createElement("select");
                    select.id = "ProductCategory";
                    select.className = "form-select text-capitalize";
                    select.innerHTML = '<option value="">Category</option>';

                    document
                        .querySelector(".product_category")
                        .appendChild(select);

                    select.addEventListener("change", function () {
                        const val = select.value;
                        column.search(val, true, false).draw();
                    });

                    // Add unique categories
                    const uniqueCategories = [];
                    column
                        .data()
                        .unique()
                        .sort()
                        .each(function (d) {
                            // Extract category name from rendered HTML or object
                            let categoryName = "";
                            if (typeof d === "object" && d.nama) {
                                categoryName = d.nama;
                            } else if (typeof d === "string") {
                                const el = document.createElement("div");
                                el.innerHTML = d;
                                categoryName =
                                    el.textContent || el.innerText || "";
                            }

                            if (
                                categoryName &&
                                uniqueCategories.indexOf(categoryName) === -1
                            ) {
                                uniqueCategories.push(categoryName);
                                const option = document.createElement("option");
                                option.value = categoryName;
                                option.textContent = categoryName;
                                select.appendChild(option);
                            }
                        });
                });

                // Adding stock filter
                api.columns(4).every(function () {
                    const column = this;
                    const select = document.createElement("select");
                    select.id = "ProductStock";
                    select.className = "form-select text-capitalize";
                    select.innerHTML = '<option value="">Stock</option>';

                    document
                        .querySelector(".product_stock")
                        .appendChild(select);

                    select.addEventListener("change", function () {
                        const val = select.value;
                        column
                            .search(val ? "^" + val + "$" : "", true, false)
                            .draw();
                    });

                    // Add stock options
                    const option1 = document.createElement("option");
                    option1.value = "In_Stock";
                    option1.textContent = "In Stock";
                    select.appendChild(option1);

                    const option2 = document.createElement("option");
                    option2.value = "Out_of_Stock";
                    option2.textContent = "Out of Stock";
                    select.appendChild(option2);
                });
            },
        });
    }

    // Filter form control to default size
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
                if (classToRemove) {
                    classToRemove
                        .split(" ")
                        .forEach((className) =>
                            element.classList.remove(className),
                        );
                }
                if (classToAdd) {
                    classToAdd
                        .split(" ")
                        .forEach((className) =>
                            element.classList.add(className),
                        );
                }
            });
        });
    }, 100);
});
// Handle Delete dengan SweetAlert2
$(document).on('click', '.delete-record', function () {
    const id = $(this).data('id');
    const name = $(this).data('name');
    const token = $('meta[name="csrf-token"]').attr('content'); // Pastikan meta tag ini ada di layout
    // Sneat set data-bs-theme di <html> pas theme di-apply
    const isDark = document.documentElement.getAttribute('data-bs-theme') === 'dark';
    Swal.fire({
        title: "Delete!",
        text: `Barang "${name}"`,
        icon: "warning",
        html: `<span style="line-height: 25px;"> Apakah anda yakin akan menghapus data ini?</span>`,
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
        if (result.isConfirmed) {
            // Buat form dinamis untuk kirim request DELETE
            const form = document.createElement("form");
            form.action = `/admin/barang/${id}`;
            form.method = "POST";
            form.innerHTML = `
                <input type="hidden" name="_token" value="${token}">
                <input type="hidden" name="_method" value="DELETE">
            `;
            document.body.appendChild(form);
            // Tampilkan loader sebentar (opsional)
            window.triggerTableLoader(function () {
                Swal.close(); // tutup swal dulu
                setTimeout(() => form.submit(), 50); // submit setelah swal kelar cleanup
            });
        }
    });
});
// Tambahin di @push('script') atau di file JS custom lo
// Populate modal edit pas tombol edit diklik

const editBarangModal = new bootstrap.Modal(document.getElementById('editBarang'));

// Tangkap klik tombol edit di DataTable (delegasi karena row di-render dinamis)
$(document).on('click', '.edit-record', function () {
    const id      = $(this).data('id');
    const slug    = $(this).data('slug');

    // Cari data dari barangsData yang udah ada
    const barang  = barangsData.find(b => b.id == id);
    if (!barang) return;

    const form    = document.getElementById('formEditBarang');

    // Set action form ke route update
    form.action = `/admin/barang/${id}`;

    // Isi field
    document.getElementById('editNamaBarang').value     = barang.nama;
    setHargaEdit(barang.harga); // Fungsi khusus untuk format harga
    document.getElementById('editJumlahBarang').value   = barang.jumlah;
    document.getElementById('editDeskripsiBarang').value = barang.deskripsi ?? '';

    // Kategori
    document.getElementById('editKategoriBarang').value = barang.id_kategori;

    // Kondisi — set radio yang sesuai
    // const kondisiRadios = document.querySelectorAll('input[name="kondisi"][form!=""]');
    document.querySelectorAll('#editBarang input[name="kondisi"]').forEach(radio => {
        radio.checked = radio.value === barang.kondisi;
    });

    // Foto preview
    const previewWrapper = document.getElementById('editFotoPreviewWrapper');
    const previewImg     = document.getElementById('editFotoPreview');
    if (barang.foto) {
        previewImg.src          = `/storage/${barang.foto}`;
        previewWrapper.style.display = 'block';
    } else {
        previewWrapper.style.display = 'none';
    }

    // Buka modal dengan loader
    window.triggerTableLoader(function () {
        editBarangModal.show();
    });
});
