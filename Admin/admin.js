function cambiarPestana(pestana, boton) {

    const solicitudes = document.getElementById("solicitudes");
    const reservas = document.getElementById("reservas");

    const botones = document.querySelectorAll(".filtro");

    // Quitar clase activa
    botones.forEach(function(boton) {
        boton.classList.remove("active");
    });

    // Activar botón seleccionado
    if (boton) {
        boton.classList.add("active");
    }


    // TODAS

    if (pestana === "todas") {

        solicitudes.classList.remove("oculto");
        reservas.classList.remove("oculto");

    }


    // SOLICITUDES

    else if (pestana === "solicitudes") {

        solicitudes.classList.remove("oculto");
        reservas.classList.add("oculto");

    }


    // RESERVAS

    else if (pestana === "reservas") {

        solicitudes.classList.add("oculto");
        reservas.classList.remove("oculto");

    }

}



// ==========================================
// CAMBIAR DE VISTA (oculta todas y muestra una)
// ==========================================

const VISTAS = ["vista-solicitudes", "vista-productos", "vista-profesores", "vista-alumnos", "vista-horarios"];

function mostrarVista(event, idVista) {

    event.preventDefault();

    VISTAS.forEach(function(id) {
        document.getElementById(id).classList.toggle("oculto", id !== idVista);
    });

}

function mostrarProductos(event) { mostrarVista(event, "vista-productos"); }

function mostrarSolicitudes(event) { mostrarVista(event, "vista-solicitudes"); }

function mostrarProfesores(event) { mostrarVista(event, "vista-profesores"); }

function mostrarAlumnos(event) { mostrarVista(event, "vista-alumnos"); }

function mostrarHorarios(event) { mostrarVista(event, "vista-horarios"); }