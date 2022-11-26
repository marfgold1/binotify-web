const dropdowns = document.querySelectorAll(".dropdown");

dropdowns.forEach((dropdown) => {
    const select = dropdown.querySelector(".select");
    const caret = dropdown.querySelector(".caret");
    const menu = dropdown.querySelector(".menu");

    select.addEventListener("click", () => {
        caret.classList.toggle("caret-rotate");
        menu.classList.toggle("menu-open");
    });
});
