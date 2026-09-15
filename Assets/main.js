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
const bpInject = document.createElement("script");
bpInject.src = "https://cdn.botpress.cloud/webchat/v5.0/inject.js";
bpInject.onload = () => {
    const bpConfig = document.createElement("script");
    bpConfig.src = "https://files.bpcontent.cloud/2026/09/15/11/20260915115955-Q0PVG833.js";
    document.body.appendChild(bpConfig);
};
document.body.appendChild(bpInject);
    })
    .catch(error => {
        console.error("ERROR:", error);
    });