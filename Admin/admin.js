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
// MOSTRAR PRODUCTOS
// ==========================================

function mostrarProductos(event) {

    event.preventDefault();


    const vistaSolicitudes =
        document.getElementById("vista-solicitudes");

    const vistaProductos =
        document.getElementById("vista-productos");


    vistaSolicitudes.classList.add("oculto");

    vistaProductos.classList.remove("oculto");

}



// ==========================================
// VOLVER A SOLICITUDES
// ==========================================

function mostrarSolicitudes(event) {

    event.preventDefault();


    const vistaSolicitudes =
        document.getElementById("vista-solicitudes");

    const vistaProductos =
        document.getElementById("vista-productos");


    vistaProductos.classList.add("oculto");

    vistaSolicitudes.classList.remove("oculto");

}