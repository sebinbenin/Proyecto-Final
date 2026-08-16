function mostrarMenu(){
    let x = document.getElementById("nav");
        if (x.className === "encabezados") {
            x.className += " responsive";
        }else{
            x.className = "encabezados";
    }
}

fetch("../Assets/main.html")
    .then(response => response.text())
    .then(html => {

        // Creamos un contenedor temporal
        const temp = document.createElement("div");
        temp.innerHTML = html;

        // Buscamos el header
        const header = temp.querySelector("#headerPrincipal");

        // Buscamos el footer
        const footer = temp.querySelector("#footerPrincipal");

        // Los colocamos en la página
        document.getElementById("header").appendChild(header);
        document.getElementById("footer").appendChild(footer);

    })
    .catch(error => {
        console.error("Error cargando los componentes:", error);
    });