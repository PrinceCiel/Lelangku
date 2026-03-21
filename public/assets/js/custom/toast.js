// assets/js/custom/toast.js

function showCardToast(type, message) {
    const wrapper = document.getElementById("cardToastWrapper");
    if (!wrapper) return;

    const icons = {
        success: "ri ri-checkbox-circle-fill",
        error: "ri ri-close-circle-fill",
        warning: "ri ri-error-warning-fill",
        info: "ri ri-information-fill",
    };

    const toast = document.createElement("div");
    toast.className = `card-toast toast-${type}`;
    toast.innerHTML = `
        <div class="card-toast-content">
            <div class="card-toast-icon">
                <i class="${icons[type] || icons.info}"></i>
            </div>
            <span>${message}</span>
        </div>
        <div class="card-toast-progress"></div>
    `;

    wrapper.appendChild(toast);

    setTimeout(() => {
        toast.classList.add("toast-hide");
        setTimeout(() => toast.remove(), 350);
    }, 3000);
}

// ── Auto trigger dari query param ──
window.addEventListener("load", function () {
    const params = new URLSearchParams(window.location.search);
    const toast = params.get("toast");

    if (!toast) return;

    const config = {
        // toast crud admin
        success: { type: "success", msg: "Data berhasil disimpan!" },
        updated: { type: "success", msg: "Data berhasil diperbarui!" },
        deleted: { type: "success", msg: "Data berhasil dihapus!" },
        // toast auth
        google_error: { type: "error", msg: "Login Google gagal!" },
        google_email_exists: { type: "error", msg: "Email sudah terdaftar!" },
        acc_mismatch: { type: "error", msg: "Email atau password salah!" },
        acc_email_exists: { type: "error", msg: "Email sudah terdaftar!" },
        error: { type: "error", msg: "Terjadi kesalahan!" },
    };

    const { type, msg } = config[toast] || { type: "info", msg: "Selesai!" };
    showCardToast(type, msg);

    // Bersihkan URL
    const cleanUrl = new URL(window.location.href);
    cleanUrl.searchParams.delete("toast");
    window.history.replaceState({}, "", cleanUrl.toString());
});
