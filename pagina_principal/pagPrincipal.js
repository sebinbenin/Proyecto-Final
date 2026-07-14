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