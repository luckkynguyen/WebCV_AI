function useTemplate(templateName) {

    const confirmUse = confirm(
        "Bạn muốn sử dụng mẫu CV: " + templateName + "?"
    );

    if (confirmUse) {

        // Sau này có thể chuyển sang:
        // create-cv.php?template=Modern

        window.location.href =
            "tao-cv.html?template=" +
            encodeURIComponent(templateName);
    }
}


/* =========================
   FILTER
========================= */

const filterButtons = document.querySelectorAll(".filter-btn");

filterButtons.forEach(button => {

    button.addEventListener("click", function () {

        filterButtons.forEach(btn => {
            btn.classList.remove("active");
        });

        this.classList.add("active");

    });

});