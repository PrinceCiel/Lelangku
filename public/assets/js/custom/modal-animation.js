/**
 * modal-animation.js
 * Pendekatan: observe DOM untuk modal yang di-init kapanpun
 */

(function () {
    function attachModalAnimation(modal) {
        // Jangan pasang listener dobel
        if (modal._animationAttached) return;
        modal._animationAttached = true;

        let isClosing = false;

        // ── OPEN ──
        modal.addEventListener("show.bs.modal", () => {
            isClosing = false;
            const dialog = modal.querySelector(".modal-dialog");

            // Hidden state
            dialog.style.opacity = "0";
            dialog.style.transform = "scale(0.85) translateY(24px)";
            dialog.style.filter = "blur(6px)";
            dialog.style.transition = "none";

            // Paksa reflow
            void dialog.offsetHeight;

            // Animate in
            requestAnimationFrame(() => {
                dialog.style.transition = [
                    "opacity 0.45s cubic-bezier(0.34,1.56,0.64,1)",
                    "transform 0.45s cubic-bezier(0.34,1.56,0.64,1)",
                    "filter 0.4s ease",
                ].join(", ");
                dialog.style.opacity = "1";
                dialog.style.transform = "scale(1) translateY(0)";
                dialog.style.filter = "blur(0px)";

                animateFieldsIn(modal);
            });
        });

        modal.addEventListener("shown.bs.modal", () => {
            const dialog = modal.querySelector(".modal-dialog");
            dialog.style.cssText = "";
        });

        // ── CLOSE ──
        modal.addEventListener("hide.bs.modal", (e) => {
            if (isClosing) return;
            e.preventDefault();
            isClosing = true;

            const dialog = modal.querySelector(".modal-dialog");
            dialog.style.transition =
                "opacity 0.22s ease-out, transform 0.22s ease-out, filter 0.22s ease-out";
            dialog.style.opacity = "0";
            dialog.style.transform = "scale(0.92) translateY(10px)";
            dialog.style.filter = "blur(4px)";

            setTimeout(() => {
                dialog.style.cssText = "";
                // Ambil instance yang SUDAH ADA, jangan buat baru
                const instance = bootstrap.Modal.getInstance(modal);
                if (instance) {
                    isClosing = true; // tetap true biar ga loop
                    instance.hide();
                }
            }, 230);
        });

        modal.addEventListener("hidden.bs.modal", () => {
            modal.querySelector(".modal-dialog").style.cssText = "";
            isClosing = false;
        });
    }

    function animateFieldsIn(modal) {
        const cols = modal.querySelectorAll(
            '.modal-body .row > [class*="col"]',
        );
        cols.forEach((col, i) => {
            col.style.opacity = "0";
            col.style.transform = "scale(0.88) translateY(14px)";
            col.style.transition = "none";
            void col.offsetHeight;

            setTimeout(
                () => {
                    col.style.transition =
                        "opacity 0.4s cubic-bezier(0.34,1.56,0.64,1), transform 0.4s cubic-bezier(0.34,1.56,0.64,1)";
                    col.style.opacity = "1";
                    col.style.transform = "scale(1) translateY(0)";
                },
                80 + i * 55,
            );

            setTimeout(
                () => {
                    col.style.cssText = "";
                },
                80 + i * 55 + 500,
            );
        });
    }

    // ── Observe DOM: pasang listener ke modal kapanpun dia muncul ──
    // Ini handle kasus modal yang di-init SETELAH window.load
    const domObserver = new MutationObserver(() => {
        document.querySelectorAll(".modal").forEach(attachModalAnimation);
    });

    domObserver.observe(document.body, {
        childList: true,
        subtree: true,
    });

    // Pasang juga ke yang udah ada sekarang
    document.addEventListener("DOMContentLoaded", () => {
        document.querySelectorAll(".modal").forEach(attachModalAnimation);
    });

    // Dan pas load selesai
    window.addEventListener("load", () => {
        document.querySelectorAll(".modal").forEach(attachModalAnimation);
    });
})();
