document.addEventListener("DOMContentLoaded", function() {
            const headers = document.querySelectorAll(".accordion-header");

            headers.forEach((header, index) => {
                // Restaurar estado guardado
                const savedState = localStorage.getItem("accordion-" + index);
                const content = header.nextElementSibling;
                if (savedState === "open") content.style.display = "block";

                header.addEventListener("click", function() {
                    if (content.style.display === "block") {
                        content.style.display = "none";
                        localStorage.setItem("accordion-" + index, "closed");
                    } else {
                        content.style.display = "block";
                        localStorage.setItem("accordion-" + index, "open");
                    }
                });
            });
        });