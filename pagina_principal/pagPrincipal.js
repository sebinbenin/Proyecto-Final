document.addEventListener("DOMContentLoaded", () => {
  const carrusel = document.querySelector('.carrusel-automatico');
  const elementosCambio = document.querySelectorAll('.cambio');

  // Control de seguridad por si hay algún error de tipeo en las clases del HTML
  if (!carrusel || elementosCambio.length === 0) {
    console.warn("¡Ojo! No se encontró la clase '.carrusel-automatico' o '.cambio' en tu HTML.");
    return;
  }

  let indiceActual = 0;
  const tiempoIntervalo = 5000;
  let intervaloCarrusel;

  function siguienteElemento() {
    // Quita la clase al elemento que se está mostrando actualmente
    elementosCambio[indiceActual].classList.remove('slide-automatico');
    
    // El operador % asegura que vuelva a 0 al llegar al final (Bucle infinito)
    indiceActual = (indiceActual + 1) % elementosCambio.length;
    
    // Añade la clase al nuevo elemento para mostrarlo
    elementosCambio[indiceActual].classList.add('slide-automatico');
  }

  function iniciarCarrusel() {
    clearInterval(intervaloCarrusel); // Limpia residuos de intervalos anteriores
    intervaloCarrusel = setInterval(siguienteElemento, tiempoIntervalo);
  }

  function detenerCarrusel() {
    clearInterval(intervaloCarrusel);
  }

  // Arranca el bucle infinito automáticamente
  iniciarCarrusel();

  // Pausa cuando el mouse entra al mapa/imagen y reanuda cuando sale
  carrusel.addEventListener('mouseenter', detenerCarrusel);
  carrusel.addEventListener('mouseleave', iniciarCarrusel);
});

const track = document.getElementById('track');
const prevBtn = document.querySelector('.nav-prev');
const nextBtn = document.querySelector('.nav-next');

// --- Flechas: desplazan el scroll horizontal ---
const scrollAmount = 280; // ancho de slide + gap aprox.
prevBtn.addEventListener('click', () => {
  track.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
});
nextBtn.addEventListener('click', () => {
  track.scrollBy({ left: scrollAmount, behavior: 'smooth' });
});

// --- Drag con mouse (desktop) sin romper el click en el link ---
let isDown = false;
let startX = 0;
let scrollLeftStart = 0;
let moved = 0; // cuánto se movió el mouse durante el drag

track.addEventListener('mousedown', (e) => {
  isDown = true;
  startX = e.pageX;
  scrollLeftStart = track.scrollLeft;
  moved = 0;
});

window.addEventListener('mousemove', (e) => {
  if (!isDown) return;
  const dx = e.pageX - startX;
  moved = Math.abs(dx);
  track.scrollLeft = scrollLeftStart - dx;
});

window.addEventListener('mouseup', () => {
  isDown = false;
});

// Si hubo arrastre real, cancelamos el click para que no dispare el link
track.querySelectorAll('.slide').forEach((link) => {
  link.addEventListener('click', (e) => {
    if (moved > 5) { // umbral en píxeles
      e.preventDefault();
    }
  });
});