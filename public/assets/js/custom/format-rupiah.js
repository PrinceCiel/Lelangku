// ── NUMBER FORMAT UNTUK INPUT HARGA ──
// Tambahin di @push('script') atau file JS custom lo

function formatRupiah(angka) {
    return parseInt(angka || 0).toLocaleString("id-ID");
}

function initHargaFormat(displayInputId, hiddenInputId) {
    const display = document.getElementById(displayInputId);
    const hidden = document.getElementById(hiddenInputId);
    if (!display || !hidden) return;

    // Format saat user ngetik
    display.addEventListener("input", function () {
        // Ambil hanya angka
        const raw = this.value.replace(/\D/g, "");
        hidden.value = raw;

        // Format dengan titik ribuan
        const formatted = raw ? parseInt(raw).toLocaleString("id-ID") : "";
        const cursorPos = this.selectionStart;
        const prevLen = this.value.length;

        this.value = formatted;

        // Jaga posisi cursor tetap natural
        const diff = formatted.length - prevLen;
        this.setSelectionRange(cursorPos + diff, cursorPos + diff);
    });

    // Format saat field di-focus (bersihkan kalau masih 0)
    display.addEventListener("focus", function () {
        if (this.value === "0") this.value = "";
    });

    // Format ulang saat blur
    display.addEventListener("blur", function () {
        const raw = hidden.value;
        this.value = raw ? parseInt(raw).toLocaleString("id-ID") : "";
    });
}

// Init untuk modal ADD
document
    .getElementById("addBarang")
    ?.addEventListener("show.bs.modal", function () {
        // Reset
        document.getElementById("hargaDisplayAdd").value = "";
        document.getElementById("hargaBarang").value = "";
    });

initHargaFormat("hargaDisplayAdd", "hargaBarang");

// Init untuk modal EDIT — populate saat data diisi
// Dipanggil manual dari fungsi populate edit, lihat edit-barang-modal.js
function setHargaEdit(nilai) {
    document.getElementById("hargaDisplayEdit").value = parseInt(
        nilai || 0,
    ).toLocaleString("id-ID");
    document.getElementById("editHargaBarang").value = nilai;
}

initHargaFormat("hargaDisplayEdit", "editHargaBarang");
