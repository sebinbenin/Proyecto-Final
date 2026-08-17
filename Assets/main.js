fetch("../Assets/main.html")
    .then(response => {
        console.log("Respuesta:", response.status);

        if (!response.ok) {
            throw new Error("No se encontró main.html");
        }

        return response.text();
    })
    .then(html => {

        console.log("Componentes cargados correctamente");

        const temp = document.createElement("div");
        temp.innerHTML = html;

        const header = temp.querySelector("#headerPrincipal");
        const footer = temp.querySelector("#footerPrincipal");

        console.log("Header:", header);
        console.log("Footer:", footer);

        if (header) {
            document.getElementById("header").appendChild(header);
        }

        if (footer) {
            document.getElementById("footer").appendChild(footer);
        }

    })
    .catch(error => {
        console.error("ERROR:", error);
    });