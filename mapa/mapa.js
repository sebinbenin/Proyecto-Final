// =====================================================================
// MAPA 3D DE UN EDIFICIO FICTICIO DE UTEC
// Muestra en qué piso está cada salón y cómo llegar desde la entrada.
// El edificio mide 40 x 24: pasillo en el medio (z entre -2 y 2),
// salones al norte (z negativo) y al sur (z positivo), escalera al fondo (este).
// =====================================================================

const ALTO_PISO = 10;    // separación vertical entre pisos (vista "despiezada")
const ALTO_PARED = 2.4;
const GROSOR = 0.18;
const ESCALERA = { x: 18, z: -3 };

const PISOS = [
    { n: 0, nombre: 'Planta baja', corto: 'Planta baja' },
    { n: 1, nombre: 'Primer piso', corto: '1.er piso' },
    { n: 2, nombre: 'Segundo piso', corto: '2.º piso' },
];

// tipo: dibuja los muebles. destacado: salón con página y modelo 3D propio.
const SALAS = [
    // ---------- PLANTA BAJA ----------
    { id: 'hall', nombre: 'Hall de entrada', piso: 0, x: [-5, 5], z: [2, 12], tipo: 'hall', lista: false },
    { id: 'anfiteatro', nombre: 'Anfiteatro', piso: 0, x: [-20, -5], z: [2, 12], tipo: 'anfiteatro', destacado: true,
      pagina: '../anfiteatro/anfiteatro.html', modelo: '1e60224711714040888949d88f919b9e' },
    { id: 'banos-pb', nombre: 'Baños', piso: 0, x: [5, 10], z: [2, 12], tipo: 'banos' },
    { id: 'direccion', nombre: 'Dirección', piso: 0, x: [10, 20], z: [2, 12], tipo: 'oficina' },
    { id: 'biblioteca', nombre: 'Biblioteca', piso: 0, x: [-20, 2], z: [-12, -2], tipo: 'biblioteca', destacado: true,
      pagina: '../biblioteca/biblioteca.html', modelo: '270abad26be246dd85e0b56dfbef7aa9' },
    { id: 'profesores', nombre: 'Sala de profesores', piso: 0, x: [2, 16], z: [-12, -2], tipo: 'oficina' },
    { id: 'escalera-0', nombre: 'Escalera', piso: 0, x: [16, 20], z: [-12, -2], tipo: 'escalera', lista: false },

    // ---------- PRIMER PISO ----------
    { id: 'informatica', nombre: 'Laboratorio de Informática', piso: 1, x: [-20, -4], z: [-12, -2], tipo: 'informatica', destacado: true,
      pagina: '../Laboratorioinformatica/Laboratorio-informatica.html', modelo: 'd7ba2cd29eb441d29793a7f8acd97971' },
    { id: 'aula-mb', nombre: 'Aula 3.º MB', piso: 1, x: [-4, 6], z: [-12, -2], tipo: 'aula' },
    { id: 'aula-ma', nombre: 'Aula 3.º MA', piso: 1, x: [6, 16], z: [-12, -2], tipo: 'aula' },
    { id: 'escalera-1', nombre: 'Escalera', piso: 1, x: [16, 20], z: [-12, -2], tipo: 'escalera', lista: false },
    { id: 'fisica', nombre: 'Salón de Física', piso: 1, x: [-20, -4], z: [2, 12], tipo: 'laboratorio', destacado: true,
      pagina: '../SalonDeFisica/SalonDeFisica.html', modelo: '996c0447b4654d6e8b8144993cbc6473' },
    { id: 'banos-1', nombre: 'Baños', piso: 1, x: [-4, 2], z: [2, 12], tipo: 'banos', lista: false },
    { id: 'aula-2mb', nombre: 'Aula 2.º MB', piso: 1, x: [2, 20], z: [2, 12], tipo: 'aula' },

    // ---------- SEGUNDO PISO ----------
    { id: 'quimica', nombre: 'Laboratorio de Química', piso: 2, x: [-20, -4], z: [-12, -2], tipo: 'laboratorio', destacado: true,
      pagina: '../Laboratorioquimica/laboratorio-quimica.html', modelo: '722a288fdfae45e099f8710eadf17c18' },
    { id: 'deposito', nombre: 'Depósito de materiales', piso: 2, x: [-4, 6], z: [-12, -2], tipo: 'deposito' },
    { id: 'aula-optico', nombre: 'Aula Óptico', piso: 2, x: [6, 16], z: [-12, -2], tipo: 'aula' },
    { id: 'escalera-2', nombre: 'Escalera', piso: 2, x: [16, 20], z: [-12, -2], tipo: 'escalera', lista: false },
    { id: 'ciencias', nombre: 'Salón de Ciencias', piso: 2, x: [-20, -4], z: [2, 12], tipo: 'laboratorio', destacado: true,
      pagina: '../SalonDeCiencias/SalonDeCiencias.html', modelo: '4047d6968c7f4bf7a8c208d8f979351b' },
    { id: 'banos-2', nombre: 'Baños', piso: 2, x: [-4, 2], z: [2, 12], tipo: 'banos', lista: false },
    { id: 'estudio', nombre: 'Sala de estudio', piso: 2, x: [2, 20], z: [2, 12], tipo: 'estudio' },
];

const COLORES = {
    destacado: 0xffe3a3,
    aula: 0xe6def5,
    servicio: 0xeceef2,
    escalera: 0xcbb8ee,
    hall: 0xf3eefa,
    seleccionado: 0xffc43d,
    pared: 0xffffff,
    borde: 0x511d96,
    losa: 0xf7f4fc,
    madera: 0xc9a27a,
    oscuro: 0x3d3a45,
    gris: 0x9a97a3,
    camino: 0xff3d7f,
};

// ---------------------------------------------------------------------
// ESCENA
// ---------------------------------------------------------------------
const contenedor = document.getElementById('escena');
const escena = new THREE.Scene();
const camara = new THREE.PerspectiveCamera(42, 1, 0.1, 500);
const VISTA_GENERAL = new THREE.Vector3(30, 52, 62);
camara.position.copy(VISTA_GENERAL);

const render = new THREE.WebGLRenderer({ antialias: true });
render.setPixelRatio(Math.min(window.devicePixelRatio, 2));
render.shadowMap.enabled = true;
render.shadowMap.type = THREE.PCFSoftShadowMap;
contenedor.appendChild(render.domElement);

const controles = new THREE.OrbitControls(camara, render.domElement);
controles.enableDamping = true;
controles.maxPolarAngle = Math.PI / 2.1;
controles.minDistance = 12;
controles.maxDistance = 120;
controles.target.set(0, ALTO_PISO, 0);

escena.add(new THREE.HemisphereLight(0xffffff, 0xd9d0e5, 0.75));
const sol = new THREE.DirectionalLight(0xffffff, 0.65);
sol.position.set(25, 60, 30);
sol.castShadow = true;
sol.shadow.mapSize.set(2048, 2048);
Object.assign(sol.shadow.camera, { left: -35, right: 35, top: 35, bottom: -35, near: 1, far: 140 });
escena.add(sol);

function colorDeFondo() {
    const oscuro = document.documentElement.getAttribute('data-theme') === 'dark';
    escena.background = new THREE.Color(oscuro ? 0x12264a : 0xffffff);
}
colorDeFondo();
new MutationObserver(colorDeFondo).observe(document.documentElement, { attributes: true, attributeFilter: ['data-theme'] });

// ---------------------------------------------------------------------
// AYUDANTES PARA ARMAR EL EDIFICIO
// ---------------------------------------------------------------------
function caja(padre, ancho, alto, prof, color, x, y, z, opciones = {}) {
    const material = new THREE.MeshStandardMaterial({ color, roughness: 0.85 });
    const malla = new THREE.Mesh(new THREE.BoxGeometry(ancho, alto, prof), material);
    malla.position.set(x, y + alto / 2, z);
    malla.castShadow = opciones.sombra !== false;
    malla.receiveShadow = true;
    padre.add(malla);
    return malla;
}

function cilindro(padre, radio, alto, color, x, y, z) {
    const malla = new THREE.Mesh(new THREE.CylinderGeometry(radio, radio, alto, 16), new THREE.MeshStandardMaterial({ color, roughness: 0.8 }));
    malla.position.set(x, y + alto / 2, z);
    malla.castShadow = true;
    padre.add(malla);
    return malla;
}

// Pared a lo largo de X (en z fijo) o de Z (en x fijo), con un hueco de puerta opcional
function pared(padre, eje, desde, hasta, fijo, hueco) {
    const tramos = hueco === undefined ? [[desde, hasta]] : [[desde, hueco - 0.9], [hueco + 0.9, hasta]];
    tramos.forEach(([a, b]) => {
        if (b - a < 0.05) return;
        const largo = b - a, medio = (a + b) / 2;
        const malla = eje === 'x'
            ? caja(padre, largo, ALTO_PARED, GROSOR, COLORES.pared, medio, 0, fijo)
            : caja(padre, GROSOR, ALTO_PARED, largo, COLORES.pared, fijo, 0, medio);
        const bordes = new THREE.LineSegments(new THREE.EdgesGeometry(malla.geometry),
            new THREE.LineBasicMaterial({ color: COLORES.borde, transparent: true, opacity: 0.35 }));
        malla.add(bordes);
    });
}

// Cartel de texto que siempre mira a la cámara
function cartel(texto, opciones = {}) {
    const lienzo = document.createElement('canvas');
    const ctx = lienzo.getContext('2d');
    const tam = opciones.tam || 46;
    ctx.font = `bold ${tam}px Arial`;
    const ancho = Math.ceil(ctx.measureText(texto).width) + 60;
    lienzo.width = ancho;
    lienzo.height = tam + 40;
    ctx.font = `bold ${tam}px Arial`;
    ctx.fillStyle = opciones.fondo || 'rgba(255,255,255,0.92)';
    const r = lienzo.height / 2;
    ctx.beginPath();
    ctx.moveTo(r, 0); ctx.lineTo(ancho - r, 0); ctx.arc(ancho - r, r, r, -Math.PI / 2, Math.PI / 2);
    ctx.lineTo(r, lienzo.height); ctx.arc(r, r, r, Math.PI / 2, Math.PI * 1.5); ctx.fill();
    ctx.fillStyle = opciones.color || '#302044';
    ctx.textBaseline = 'middle';
    ctx.fillText(texto, 30, lienzo.height / 2 + 2);

    const textura = new THREE.CanvasTexture(lienzo);
    textura.anisotropy = 4;
    const sprite = new THREE.Sprite(new THREE.SpriteMaterial({ map: textura, depthTest: false, transparent: true }));
    const escala = opciones.escala || 0.02;
    sprite.scale.set(lienzo.width * escala, lienzo.height * escala, 1);
    sprite.renderOrder = 10;
    return sprite;
}

// ---------------------------------------------------------------------
// MUEBLES DE CADA TIPO DE SALÓN
// ---------------------------------------------------------------------
const MUEBLES = {
    biblioteca(g, s) {
        for (let x = s.x[0] + 2; x < s.x[1] - 6; x += 3) {
            caja(g, 0.5, 2, 6, COLORES.madera, x, 0, s.z[0] + 4.5);
        }
        for (let x = s.x[1] - 5; x < s.x[1] - 1; x += 2.2) {
            caja(g, 1.6, 0.75, 1.2, COLORES.madera, x, 0, s.z[1] - 3);
        }
    },
    anfiteatro(g, s) {
        caja(g, 10, 0.5, 2, COLORES.oscuro, (s.x[0] + s.x[1]) / 2, 0, s.z[1] - 1.4); // escenario
        caja(g, 8, 1.8, 0.1, 0xffffff, (s.x[0] + s.x[1]) / 2, 0.6, s.z[1] - 0.3);    // pantalla
        for (let fila = 0; fila < 5; fila++) {
            caja(g, 12, 0.3 + fila * 0.25, 0.9, 0x8e7dbe, (s.x[0] + s.x[1]) / 2, 0, s.z[0] + 1.3 + fila * 1.2);
        }
    },
    informatica(g, s) {
        for (let x = s.x[0] + 2.5; x < s.x[1] - 1; x += 3.3) {
            for (let z = s.z[0] + 2; z < s.z[1] - 1.5; z += 2.6) {
                caja(g, 2.4, 0.75, 1, 0xffffff, x, 0, z);
                caja(g, 0.8, 0.55, 0.08, COLORES.oscuro, x, 0.75, z - 0.25);
            }
        }
    },
    laboratorio(g, s) {
        for (let x = s.x[0] + 3; x < s.x[1] - 2; x += 4.5) {
            caja(g, 3.2, 0.9, 1.4, COLORES.oscuro, x, 0, (s.z[0] + s.z[1]) / 2 - 1.6);
            caja(g, 3.2, 0.9, 1.4, COLORES.oscuro, x, 0, (s.z[0] + s.z[1]) / 2 + 1.8);
            cilindro(g, 0.18, 0.5, 0x2a9d8f, x - 0.8, 0.9, (s.z[0] + s.z[1]) / 2 - 1.6);
            cilindro(g, 0.15, 0.4, 0xef476f, x + 0.6, 0.9, (s.z[0] + s.z[1]) / 2 + 1.8);
        }
        caja(g, s.x[1] - s.x[0] - 2, 1, 0.8, 0xffffff, (s.x[0] + s.x[1]) / 2, 0, s.z[0] < 0 ? s.z[0] + 0.6 : s.z[1] - 0.6);
    },
    aula(g, s) {
        const pizarraZ = s.z[0] < 0 ? s.z[0] + 0.2 : s.z[1] - 0.2;
        caja(g, 4, 1.3, 0.1, 0x2f5d50, (s.x[0] + s.x[1]) / 2, 0.8, pizarraZ);
        for (let x = s.x[0] + 1.6; x < s.x[1] - 1; x += 2) {
            for (let z = s.z[0] + 2.6; z < s.z[1] - 1.2; z += 1.8) {
                caja(g, 1.2, 0.7, 0.7, COLORES.madera, x, 0, z);
            }
        }
    },
    oficina(g, s) {
        const cx = (s.x[0] + s.x[1]) / 2, cz = (s.z[0] + s.z[1]) / 2;
        caja(g, 5, 0.75, 2, COLORES.madera, cx, 0, cz);
        [-1.5, 0, 1.5].forEach(d => cilindro(g, 0.3, 0.6, COLORES.gris, cx + d, 0, cz + 1.6));
        cilindro(g, 0.35, 0.7, 0x06a77d, s.x[1] - 1, 0, s.z[0] + 1);
    },
    banos(g, s) {
        for (let x = s.x[0] + 1; x < s.x[1] - 0.5; x += 1.6) {
            caja(g, 1.2, 2, 1.4, 0xa0c4ff, x, 0, s.z[1] - 1.2);
        }
    },
    escalera(g, s) {
        for (let i = 0; i < 9; i++) {
            caja(g, 3.2, 0.27 * (i + 1), 0.9, 0xb39ddb, (s.x[0] + s.x[1]) / 2, 0, s.z[1] - 1.5 - i * 0.9, { sombra: false });
        }
    },
    hall(g, s) {
        caja(g, 3.5, 1.1, 1, 0x511d96, 0, 0, 6);
        cilindro(g, 0.45, 0.9, 0x06a77d, -3.8, 0, 10.5);
        cilindro(g, 0.45, 0.9, 0x06a77d, 3.8, 0, 10.5);
    },
    deposito(g, s) {
        for (let x = s.x[0] + 1.5; x < s.x[1] - 1; x += 2.5) {
            caja(g, 1.6, 2.1, 0.8, COLORES.gris, x, 0, s.z[0] + 0.8);
        }
    },
    estudio(g, s) {
        for (let x = s.x[0] + 3; x < s.x[1] - 2; x += 4.5) {
            cilindro(g, 1, 0.75, 0xffffff, x, 0, (s.z[0] + s.z[1]) / 2 + 1);
        }
    },
};

// ---------------------------------------------------------------------
// ARMAR LOS PISOS
// ---------------------------------------------------------------------
const gruposPiso = [];
const pisosDeClic = [];   // mallas del piso de cada salón, para detectar clics

PISOS.forEach(p => {
    const grupo = new THREE.Group();
    grupo.position.y = p.n * ALTO_PISO;
    grupo.userData.piso = p.n;
    escena.add(grupo);
    gruposPiso[p.n] = grupo;

    // Losa del piso
    caja(grupo, 40.6, 0.3, 24.6, COLORES.losa, 0, -0.3, 0, { sombra: false });

    // Nombre del piso
    const nombre = cartel(p.nombre, { fondo: '#511d96', color: '#ffffff', tam: 40 });
    nombre.position.set(-25.5, 0.6, 0);
    grupo.add(nombre);
});

SALAS.forEach(s => {
    const grupo = gruposPiso[s.piso];
    const ancho = s.x[1] - s.x[0], prof = s.z[1] - s.z[0];
    const cx = (s.x[0] + s.x[1]) / 2, cz = (s.z[0] + s.z[1]) / 2;
    const norte = s.z[1] <= -2;    // los salones del norte tienen la puerta en z = -2

    s.centro = { x: cx, z: cz };
    s.puerta = { x: cx, z: norte ? -2 : 2 };
    if (s.tipo === 'escalera') s.puerta = { x: 18, z: -2 };

    const colorBase = s.destacado ? COLORES.destacado
        : s.tipo === 'aula' ? COLORES.aula
        : s.tipo === 'escalera' ? COLORES.escalera
        : s.tipo === 'hall' ? COLORES.hall
        : COLORES.servicio;

    // Piso del salón (es lo que se toca para elegirlo)
    const piso = caja(grupo, ancho - 0.1, 0.06, prof - 0.1, colorBase, cx, 0, cz, { sombra: false });
    piso.userData.sala = s;
    piso.userData.colorBase = colorBase;
    s.malla = piso;
    pisosDeClic.push(piso);

    // Paredes: la del lado del pasillo lleva puerta
    if (s.tipo === 'hall') {
        pared(grupo, 'x', s.x[0], s.x[1], s.z[1], 0);     // entrada principal
    } else {
        pared(grupo, 'x', s.x[0], s.x[1], norte ? s.z[1] : s.z[0], s.puerta.x);
        pared(grupo, 'x', s.x[0], s.x[1], norte ? s.z[0] : s.z[1]);
        pared(grupo, 'z', s.z[0], s.z[1], s.x[0]);
        pared(grupo, 'z', s.z[0], s.z[1], s.x[1]);
    }

    (MUEBLES[s.tipo] || (() => {}))(grupo, s);

    // Cartel con el nombre
    if (s.tipo !== 'escalera' || s.piso === 0) {
        const texto = s.tipo === 'escalera' ? 'Escalera' : s.nombre;
        const etiqueta = cartel(texto, s.destacado
            ? { fondo: '#ffc43d', color: '#511d96', tam: 46 }
            : { tam: 34, escala: 0.019 });
        etiqueta.position.set(cx, ALTO_PARED + 1.3, cz);
        grupo.add(etiqueta);
        s.etiqueta = etiqueta;
    }
});

// Cartel de la entrada principal
const entrada = cartel('🚪 Entrada', { fondo: '#06a77d', color: '#ffffff', tam: 44 });
entrada.position.set(0, 1.6, 14.2);
gruposPiso[0].add(entrada);

// ---------------------------------------------------------------------
// CAMINO DESDE LA ENTRADA
// ---------------------------------------------------------------------
let camino = null, marcador = null, avance = 0;

function puntosDelCamino(s) {
    const y0 = 0.35, yN = s.piso * ALTO_PISO + 0.35;
    const p = (x, y, z) => new THREE.Vector3(x, y, z);
    const puntos = [p(0, y0, 15), p(0, y0, 12)];
    if (s.id === 'hall') return [...puntos, p(0, y0, 7)];

    if (s.piso === 0) {
        puntos.push(p(0, y0, 0), p(s.puerta.x, y0, 0));
    } else {
        puntos.push(p(0, y0, 0), p(ESCALERA.x, y0, 0), p(ESCALERA.x, y0, ESCALERA.z),
                    p(ESCALERA.x, yN, ESCALERA.z), p(ESCALERA.x, yN, 0), p(s.puerta.x, yN, 0));
    }
    puntos.push(p(s.puerta.x, yN, s.puerta.z), p(s.centro.x, yN, s.centro.z));
    return puntos;
}

function dibujarCamino(s) {
    if (camino) { escena.remove(camino); camino.geometry.dispose(); }
    if (marcador) escena.remove(marcador);

    const puntos = puntosDelCamino(s);
    const ruta = new THREE.CurvePath();
    for (let i = 0; i < puntos.length - 1; i++) {
        if (puntos[i].distanceTo(puntos[i + 1]) > 0.01) ruta.add(new THREE.LineCurve3(puntos[i], puntos[i + 1]));
    }
    camino = new THREE.Mesh(new THREE.TubeGeometry(ruta, 300, 0.2, 8, false),
        new THREE.MeshStandardMaterial({ color: COLORES.camino, emissive: COLORES.camino, emissiveIntensity: 0.35 }));
    camino.userData.ruta = ruta;
    escena.add(camino);

    marcador = new THREE.Mesh(new THREE.SphereGeometry(0.55, 24, 16),
        new THREE.MeshStandardMaterial({ color: 0xffffff, emissive: COLORES.camino, emissiveIntensity: 0.6 }));
    escena.add(marcador);
    avance = 0;
}

// ---------------------------------------------------------------------
// INDICACIONES EN PALABRAS
// ---------------------------------------------------------------------
// "El Laboratorio…", "La Biblioteca…", "Los Baños…"
function conArticulo(nombre) {
    const primera = nombre.split(' ')[0];
    const articulo = ['Biblioteca', 'Sala', 'Dirección'].includes(primera) ? 'La'
        : primera === 'Baños' ? 'Los' : 'El';
    return `${articulo} ${nombre}`;
}

function indicaciones(s) {
    const nombrePiso = PISOS[s.piso].nombre.toLowerCase();
    const pasos = ['Entrá por la puerta principal al Hall de entrada (planta baja).'];
    if (s.id === 'hall') return pasos;

    const norte = s.puerta.z < 0;
    if (s.piso === 0) {
        pasos.push('Caminá derecho hasta el pasillo.');
        const haciaOeste = s.puerta.x < 0;
        if (Math.abs(s.puerta.x) < 1.5) {
            pasos.push(`${conArticulo(s.nombre)} está justo enfrente.`);
        } else {
            pasos.push(`Girá a la ${haciaOeste ? 'izquierda' : 'derecha'} por el pasillo.`);
            const lado = (haciaOeste === norte) ? 'derecha' : 'izquierda';
            pasos.push(`${conArticulo(s.nombre)} ${s.nombre === 'Baños' ? 'están' : 'está'} en la puerta a tu ${lado}.`);
        }
    } else {
        pasos.push('Caminá hasta el pasillo y girá a la derecha: la escalera está al fondo.');
        pasos.push(`Subí por la escalera hasta el ${nombrePiso}.`);
        pasos.push('Al salir de la escalera, caminá por el pasillo hacia la izquierda.');
        // Cuenta cuántas puertas de ese mismo lado hay antes de llegar
        const delMismoLado = SALAS.filter(o => o.piso === s.piso && o.tipo !== 'escalera'
            && (o.puerta.z < 0) === norte && o.puerta.x > s.puerta.x);
        const numero = delMismoLado.length + 1;
        const ordinal = ['primera', 'segunda', 'tercera', 'cuarta'][numero - 1] || `${numero}.ª`;
        pasos.push(`${conArticulo(s.nombre)} es la ${ordinal} puerta a tu ${norte ? 'derecha' : 'izquierda'}.`);
    }
    return pasos;
}

// ---------------------------------------------------------------------
// MOSTRAR / OCULTAR / ATENUAR PISOS
// ---------------------------------------------------------------------
let pisoElegido = 'todos';
let salaElegida = null;

function opacidadGrupo(grupo, opacidad) {
    grupo.traverse(obj => {
        if (!obj.material) return;
        obj.material.transparent = opacidad < 1 || obj.isSprite || obj.isLineSegments;
        if (obj.material.userData.base === undefined) obj.material.userData.base = obj.material.opacity;
        obj.material.opacity = obj.material.userData.base * opacidad;
        obj.material.depthWrite = opacidad === 1 && !obj.isSprite;
    });
}

function actualizarPisos() {
    gruposPiso.forEach((grupo, n) => {
        grupo.visible = pisoElegido === 'todos' || Number(pisoElegido) === n;
        const atenuar = salaElegida && pisoElegido === 'todos' && n !== salaElegida.piso;
        opacidadGrupo(grupo, atenuar ? 0.18 : 1);
    });
    // Para que no se amontonen los carteles: los salones secundarios solo muestran
    // su nombre cuando se mira un piso solo o el piso del salón elegido
    SALAS.forEach(s => {
        if (!s.etiqueta || s.destacado) return;
        s.etiqueta.visible = Number(pisoElegido) === s.piso || (salaElegida && salaElegida.piso === s.piso);
    });
    if (salaElegida && salaElegida.etiqueta) salaElegida.etiqueta.visible = true;
    document.querySelectorAll('#botones-pisos button').forEach(b => b.classList.toggle('activo', b.dataset.piso === String(pisoElegido)));
}

document.querySelectorAll('#botones-pisos button').forEach(boton => {
    boton.addEventListener('click', () => {
        pisoElegido = boton.dataset.piso;
        actualizarPisos();
        const y = pisoElegido === 'todos' ? ALTO_PISO : Number(pisoElegido) * ALTO_PISO;
        moverCamara(salaElegida && (pisoElegido === 'todos' || salaElegida.piso === Number(pisoElegido))
            ? salaElegida : { centro: { x: 0, z: 0 }, piso: y / ALTO_PISO }, pisoElegido === 'todos' && !salaElegida);
    });
});

// ---------------------------------------------------------------------
// CÁMARA SUAVE
// ---------------------------------------------------------------------
let destinoObjetivo = null, destinoCamara = null;

function moverCamara(s, general) {
    const y = s.piso * ALTO_PISO;
    // Mira un poco hacia el centro del edificio para que se vea también la entrada y la escalera
    destinoObjetivo = new THREE.Vector3(s.centro.x * 0.6, y, s.centro.z * 0.5);
    destinoCamara = general
        ? VISTA_GENERAL.clone()
        : destinoObjetivo.clone().add(new THREE.Vector3(18, 48, 46));
}

// ---------------------------------------------------------------------
// ELEGIR UN SALÓN
// ---------------------------------------------------------------------
function elegirSala(s) {
    if (salaElegida) {
        salaElegida.malla.material.color.setHex(salaElegida.malla.userData.colorBase);
        salaElegida.malla.material.emissive.setHex(0x000000);
    }
    salaElegida = s;
    s.malla.material.color.setHex(COLORES.seleccionado);
    pisoElegido = 'todos';
    actualizarPisos();
    dibujarCamino(s);
    moverCamara(s);

    document.querySelectorAll('.salon-btn').forEach(b => b.classList.toggle('activo', b.dataset.id === s.id));
    document.getElementById('detalle').classList.remove('oculto');
    document.getElementById('detalle-piso').textContent = PISOS[s.piso].nombre;
    document.getElementById('detalle-nombre').textContent = s.nombre;
    document.getElementById('detalle-pasos').innerHTML = indicaciones(s).map(t => `<li>${t}</li>`).join('');
    document.getElementById('btn-modelo').classList.toggle('oculto', !s.modelo);
    const pagina = document.getElementById('btn-pagina');
    pagina.classList.toggle('oculto', !s.pagina);
    if (s.pagina) pagina.href = s.pagina;
    history.replaceState(null, '', '#' + s.id);
}

// Lista de botones agrupada por piso
const lista = document.getElementById('lista-salones');
PISOS.forEach(p => {
    const salas = SALAS.filter(s => s.piso === p.n && s.lista !== false);
    lista.insertAdjacentHTML('beforeend', `<div class="grupo-piso"><h3>${p.nombre}</h3><div class="salones">${
        salas.map(s => `<button type="button" class="salon-btn" data-id="${s.id}">${s.nombre}</button>`).join('')
    }</div></div>`);
});
lista.addEventListener('click', e => {
    const boton = e.target.closest('.salon-btn');
    if (boton) elegirSala(SALAS.find(s => s.id === boton.dataset.id));
});

// Clic sobre un salón en el 3D (sin contar cuando se arrastra para girar)
const rayo = new THREE.Raycaster();
let inicioClic = null;
render.domElement.addEventListener('pointerdown', e => { inicioClic = { x: e.clientX, y: e.clientY }; });
render.domElement.addEventListener('pointerup', e => {
    if (!inicioClic || Math.hypot(e.clientX - inicioClic.x, e.clientY - inicioClic.y) > 6) return;
    const r = render.domElement.getBoundingClientRect();
    rayo.setFromCamera({ x: ((e.clientX - r.left) / r.width) * 2 - 1, y: -((e.clientY - r.top) / r.height) * 2 + 1 }, camara);
    const tocables = pisosDeClic.filter(m => m.parent.visible && m.material.opacity > 0.5);
    const toque = rayo.intersectObjects(tocables)[0];
    if (toque && toque.object.userData.sala.tipo !== 'escalera') elegirSala(toque.object.userData.sala);
});

// ---------------------------------------------------------------------
// VENTANA DEL MODELO DE SKETCHFAB (en blanco y negro, con opción a color)
// ---------------------------------------------------------------------
const modal = document.getElementById('modal');
const iframe = document.getElementById('modal-iframe');
const botonColor = document.getElementById('btn-color');

document.getElementById('btn-modelo').addEventListener('click', () => {
    if (!salaElegida || !salaElegida.modelo) return;
    document.getElementById('modal-titulo').textContent = salaElegida.nombre;
    iframe.src = `https://sketchfab.com/models/${salaElegida.modelo}/embed?autostart=1`;
    iframe.classList.add('modelo-gris');
    botonColor.textContent = 'Ver a color';
    modal.classList.remove('oculto');
});
botonColor.addEventListener('click', () => {
    const gris = iframe.classList.toggle('modelo-gris');
    botonColor.textContent = gris ? 'Ver a color' : 'Ver en blanco y negro';
});
function cerrarModal() {
    modal.classList.add('oculto');
    iframe.src = 'about:blank';
}
document.getElementById('btn-cerrar').addEventListener('click', cerrarModal);
modal.addEventListener('click', e => { if (e.target === modal) cerrarModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') cerrarModal(); });

// ---------------------------------------------------------------------
// ANIMACIÓN
// ---------------------------------------------------------------------
function ajustarTamano() {
    const { clientWidth: w, clientHeight: h } = contenedor;
    render.setSize(w, h);
    camara.aspect = w / h;
    camara.updateProjectionMatrix();
}
new ResizeObserver(ajustarTamano).observe(contenedor);
ajustarTamano();

actualizarPisos();

const reloj = new THREE.Clock();
function animar() {
    const dt = reloj.getDelta();
    const t = reloj.elapsedTime;

    if (destinoObjetivo) {
        controles.target.lerp(destinoObjetivo, 0.06);
        camara.position.lerp(destinoCamara, 0.06);
        if (camara.position.distanceTo(destinoCamara) < 0.1) destinoObjetivo = null;
    }

    if (camino && marcador) {
        avance = (avance + dt * 0.18) % 1;
        marcador.position.copy(camino.userData.ruta.getPointAt(avance));
    }
    if (salaElegida) {
        salaElegida.malla.material.emissive.setHex(COLORES.seleccionado);
        salaElegida.malla.material.emissiveIntensity = 0.25 + Math.sin(t * 4) * 0.2;
    }

    controles.update();
    render.render(escena, camara);
    requestAnimationFrame(animar);
}
animar();

// Si se entra con #quimica, #biblioteca, etc., se elige ese salón
const desdeEnlace = SALAS.find(s => '#' + s.id === location.hash);
if (desdeEnlace) elegirSala(desdeEnlace);
