// Confirmación en dos clics, sin usar confirm() (algunos navegadores bloquean ese cuadro).
// Uso: <a href="..." data-confirmar="¿Borrar?"> o <form data-confirmar="¿Seguro?">
// El primer clic cambia el texto del botón; el segundo (dentro de 6 segundos) confirma.

document.addEventListener("click", function (event) {
    const elemento = event.target.closest("a[data-confirmar], form[data-confirmar] button[type=submit]");
    if (!elemento) {
        return;
    }

    const boton = elemento;
    const mensaje = (boton.closest("form[data-confirmar]") || boton).dataset.confirmar;

    if (boton.dataset.armado === "si") {
        return; // segundo clic: sigue con el enlace o el envío del formulario
    }

    event.preventDefault();
    boton.dataset.armado = "si";
    boton.dataset.textoOriginal = boton.innerHTML;
    boton.innerHTML = mensaje;
    boton.classList.add("confirmando");

    setTimeout(function () {
        boton.dataset.armado = "";
        boton.innerHTML = boton.dataset.textoOriginal;
        boton.classList.remove("confirmando");
    }, 6000);
});
