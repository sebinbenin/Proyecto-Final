const darkModeBtn = document.getElementById("darkModeToggle");

function setDarkMode(active) {
    document.documentElement.setAttribute("data-theme", active ? "dark" : "light");
    darkModeBtn.innerHTML = active
        ? '<i class="fa-solid fa-sun"></i>'
        : '<i class="fa-solid fa-moon"></i>';
    if (active) {
        localStorage.setItem("darkMode", "true");
    } else {
        localStorage.removeItem("darkMode");
    }
}

const saved = localStorage.getItem("darkMode") === "true";
if (saved) {
    setDarkMode(true);
}

darkModeBtn.addEventListener("click", () => {
    setDarkMode(document.documentElement.getAttribute("data-theme") !== "dark");
});