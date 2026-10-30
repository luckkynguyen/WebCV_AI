function useTemplate(templateName) {
    // Chọn mẫu CV xong phải chuyển đến biểu mẫu điền thông tin, không chuyển sang trang AI chat.
    window.location.href = "create-cv-form.php?template=" + encodeURIComponent(templateName);
}

/* Bộ lọc mẫu CV */
const filterButtons = document.querySelectorAll(".filter-btn");
filterButtons.forEach(button => {
    button.addEventListener("click", function () {
        filterButtons.forEach(btn => btn.classList.remove("active"));
        this.classList.add("active");
    });
});
