const imagenesNoche = [
    "Anfiteatro.PNG", "Laboratorio fisica.png", "barra de separacion-Photoroom.png",
    "Aprendizaje_practico.png", "Espacio_organizado.png", "Material_laboratorio.png",
    "Proteccion_y_prevencion.png", "Recurso_Educativo.png", "Zona_experimentacion.png",
    "anf1.png", "anf2.png", "anf3.png", "anf4.png", "anf5.png",
    "icono1-biblioteca.png", "icono2-biblioteca.png", "icono3-biblioteca.png",
    "icono4-biblioteca.png", "icono5-biblioteca.png", "icono6-biblioteca.png",
    "icono_1_engranaje.png", "icono_2_rayo.png", "icono_3_letra_m.png",
    "icono_4_letra_n.png", "icono_5_rectangulo.png", "icono_6_personas.png",
    "simbolos informatica comunidad-Photoroom.png", "simbolos informatica impresora-Photoroom.png",
    "simbolos informatica internet-Photoroom.png", "simbolos informatica pc-Photoroom.png",
    "simbolos informatica personas-Photoroom.png", "simbolos informatica seguridad-Photoroom.png"
];

function setDarkModeButton(active) {
    const botonLogin = document.getElementById("darkModeToggle");
    const botonTema = document.getElementById("botonTema");
    if (botonLogin) {
        botonLogin.innerHTML = active
            ? '<i class="fa-solid fa-sun"></i>'
            : '<i class="fa-solid fa-moon"></i>';
    }
    if (botonTema) {
        botonTema.textContent = active ? "☼" : "☾";
    }
}

function aplicarTema(oscuro) {
    document.documentElement.setAttribute("data-theme", oscuro ? "dark" : "light");

    document.querySelectorAll("img").forEach(img => {
        const src = img.getAttribute("src");
        if (!src || !imagenesNoche.includes(decodeURIComponent(src.split("/").pop()))) return;
        const normal = src.replace("imagenes/noche/", "imagenes/");
        img.setAttribute("src", oscuro ? normal.replace("imagenes/", "imagenes/noche/") : normal);
    });

    setDarkModeButton(oscuro);
}

function cambiarTema() {
    const oscuro = document.documentElement.getAttribute("data-theme") !== "dark";
    localStorage.setItem("darkMode", oscuro);
    aplicarTema(oscuro);
}

function setDarkMode(active) {
    localStorage.setItem("darkMode", active);
    aplicarTema(active);
}

document.documentElement.setAttribute("data-theme", localStorage.getItem("darkMode") === "true" ? "dark" : "light");
document.addEventListener("DOMContentLoaded", () => {
    aplicarTema(localStorage.getItem("darkMode") === "true");
    const botonLogin = document.getElementById("darkModeToggle");
    if (botonLogin && !botonLogin.hasAttribute("data-listener-attached")) {
        botonLogin.addEventListener("click", () => {
            setDarkMode(document.documentElement.getAttribute("data-theme") !== "dark");
        });
        botonLogin.setAttribute("data-listener-attached", "true");
    }
});
