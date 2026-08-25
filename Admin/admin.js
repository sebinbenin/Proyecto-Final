function cambiarPestana(seccionId, botonSeleccionado) {
    const contenidos = document.querySelectorAll('.contenido');
    
    // 1. Mostrar u ocultar los contenidos
    contenidos.forEach(contenido => {
        // Si el botón presionado es 'todas' O si el ID del contenido coincide con el botón
        if (seccionId === 'todas' || contenido.id === seccionId) {
            contenido.style.display = 'block'; // Mostrar
        } else {
            contenido.style.display = 'none';  // Ocultar
        }
    });

    // 2. Gestionar los colores de los botones (clase "active")
    const botones = document.querySelectorAll('.filtro');
    botones.forEach(boton => {
        boton.classList.remove('active');
    });
    
    // 3. Iluminar el botón presionado
    botonSeleccionado.classList.add('active');
}