// Modelos 3D de Sketchfab de cada salón:
// - se muestran en blanco y negro para que todos combinen (con un botón para verlos a color)
// - se agrega un enlace al mapa del edificio para ver en qué piso queda el salón
// Uso: <script src="../Assets/modelos.js" data-salon="quimica"></script>

(function () {
    const salon = document.currentScript.dataset.salon;

    const estilos = document.createElement('style');
    estilos.textContent = `
        iframe.modelo-gris { filter: grayscale(1); transition: filter 0.3s ease; }
        .modelo-acciones { display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin: 12px auto; }
        .modelo-acciones a, .modelo-acciones button {
            font: bold 15px Arial, sans-serif; padding: 10px 16px; border-radius: 8px; cursor: pointer;
            text-decoration: none; border: 1px solid #511d96;
        }
        .modelo-acciones a { background: #511d96; color: #fff !important; }
        .modelo-acciones button { background: transparent; color: #511d96; }
        [data-theme="dark"] .modelo-acciones a { background: var(--acento); border-color: var(--acento); color: var(--texto-boton) !important; }
        [data-theme="dark"] .modelo-acciones button { color: var(--acento); border-color: var(--acento); }
    `;
    document.head.appendChild(estilos);

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('iframe[src*="sketchfab.com"]').forEach(function (iframe) {
            iframe.classList.add('modelo-gris');

            const acciones = document.createElement('div');
            acciones.className = 'modelo-acciones';
            acciones.innerHTML =
                '<a href="../mapa/mapa.html#' + salon + '">📍 Ver dónde queda en el edificio</a>' +
                '<button type="button">Ver a color</button>';

            const boton = acciones.querySelector('button');
            boton.addEventListener('click', function () {
                const gris = iframe.classList.toggle('modelo-gris');
                boton.textContent = gris ? 'Ver a color' : 'Ver en blanco y negro';
            });

            // Se ubica justo antes del modelo (o de su contenedor)
            const contenedor = iframe.closest('.sketchfab-embed-wrapper') || iframe;
            contenedor.parentNode.insertBefore(acciones, contenedor);
        });
    });
})();
