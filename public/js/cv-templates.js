function useTemplate(templateName) { window.location.href = "create-cv-form.php?template=" + encodeURIComponent(templateName); }
const filterButtons = document.querySelectorAll(".filter-btn");
filterButtons.forEach(button => button.addEventListener("click", function () { filterButtons.forEach(btn => btn.classList.remove("active")); this.classList.add("active"); }));
