-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 18-08-2026 a las 14:44:22
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `utec`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `descripcion` varchar(255) DEFAULT NULL,
  `imagen` varchar(150) DEFAULT 'sin_imagen.png',
  `categoria` varchar(80) DEFAULT NULL,
  `stock_total` int(11) NOT NULL DEFAULT 0,
  `stock_disponible` int(11) NOT NULL DEFAULT 0,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`, `imagen`, `categoria`, `stock_total`, `stock_disponible`, `creado_en`) VALUES
(1, 'Proyector Epson X49', 'Proyector portátil para aulas', 'proyector_epson.jpg', 'Audiovisual', 3, 2, '2026-08-15 15:48:52'),
(2, 'Televisión Inteligente 8K 140Hz', 'Smart TV para presentaciones y muestras', 'tv_8k.jpg', 'Audiovisual', 1, 0, '2026-08-15 15:48:52'),
(3, 'Alargue Eléctrico 5 Tomas', 'Zapatilla eléctrica con 5 tomas', 'alargue_electrico.jpg', 'Electricidad', 6, 3, '2026-08-15 15:48:52'),
(4, 'Notebook Dell Latitude', 'Notebook para uso docente', 'notebook_dell.jpg', 'Informática', 5, 2, '2026-08-15 15:48:52'),
(5, 'Tablet Samsung Galaxy Tab', 'Tablet 10\" para actividades en aula', 'tablet_samsung.jpg', 'Informática', 8, 5, '2026-08-15 15:48:52'),
(6, 'Parlante Bluetooth JBL', 'Parlante portátil recargable', 'parlante_jbl.jpg', 'Audio', 4, 4, '2026-08-15 15:48:52'),
(7, 'Micrófono Inalámbrico', 'Micrófono de mano con receptor UHF', 'microfono_inalambrico.jpg', 'Audio', 2, 0, '2026-08-15 15:48:52'),
(8, 'Cámara Fotográfica Digital', 'Cámara réflex para proyectos audiovisuales', 'camara_digital.jpg', 'Audiovisual', 2, 1, '2026-08-15 15:48:52'),
(9, 'Cámara Web Full HD', 'Webcam para videollamadas y clases virtuales', 'camara_web.jpg', 'Informática', 6, 6, '2026-08-15 15:48:52'),
(11, 'Cable HDMI 5 metros', 'Cable HDMI para conexión a proyector/TV', 'cable_hdmi.jpg', 'Accesorios', 10, 7, '2026-08-15 15:48:52'),
(12, 'Adaptador HDMI a VGA', 'Adaptador para equipos sin entrada HDMI', 'adaptador_hdmi_vga.jpg', 'Accesorios', 5, 3, '2026-08-15 15:48:52'),
(14, 'Pizarra Digital Portátil', 'Pizarra interactiva portátil', 'pizarra_digital.jpg', 'Audiovisual', 1, 1, '2026-08-15 15:48:52'),
(15, 'Calculadora Científica', 'Calculadora para clases de matemática/física', 'calculadora_cientifica.jpg', 'Material didáctico', 15, 10, '2026-08-15 15:48:52'),
(16, 'Kit de Robótica Educativa', 'Kit de armado para taller de robótica', 'kit_robotica.jpg', 'Material didáctico', 3, 0, '2026-08-15 15:48:52'),
(17, 'Impresora Portátil', 'Impresora térmica portátil', 'impresora_portatil.jpg', 'Informática', 2, 1, '2026-08-15 15:48:52'),
(18, 'Trípode para Cámara', 'Trípode ajustable de aluminio', 'tripode.jpg', 'Accesorios', 4, 3, '2026-08-15 15:48:52'),
(19, 'Auriculares con Micrófono', 'Auriculares para laboratorio de idiomas', 'auriculares.jpg', 'Audio', 12, 9, '2026-08-15 15:48:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `fecha_reserva` datetime NOT NULL,
  `fecha_devolucion` datetime DEFAULT NULL,
  `estado` enum('pendiente','aprobada','rechazada','devuelta') NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `producto_id`, `usuario_id`, `cantidad`, `fecha_reserva`, `fecha_devolucion`, `estado`) VALUES
(1, 12, 3, 1, '2026-08-15 12:51:46', '2026-08-15 12:59:55', 'devuelta'),
(2, 12, 3, 1, '2026-08-15 12:51:51', '2026-08-15 12:59:53', 'devuelta'),
(3, 12, 3, 1, '2026-08-15 12:51:52', '2026-08-15 12:59:51', 'devuelta'),
(4, 3, 3, 1, '2026-08-15 12:58:22', '2026-08-15 12:59:49', 'devuelta'),
(5, 3, 3, 1, '2026-08-15 12:58:24', '2026-08-15 12:59:48', 'devuelta'),
(6, 3, 3, 1, '2026-08-15 12:58:25', '2026-08-15 12:59:45', 'devuelta'),
(7, 15, 3, 1, '2026-08-15 15:10:33', '2026-08-15 15:10:38', 'devuelta'),
(8, 12, 3, 1, '2026-08-15 15:21:43', '2026-08-15 15:21:54', 'devuelta'),
(9, 9, 3, 4, '2026-08-15 15:22:05', '2026-08-15 15:22:09', 'devuelta'),
(10, 12, 3, 1, '2026-08-15 15:43:17', '2026-08-15 15:43:22', 'devuelta'),
(11, 12, 3, 1, '2026-08-16 11:30:47', '2026-08-16 11:34:37', 'devuelta'),
(12, 12, 3, 1, '2026-08-16 11:30:50', '2026-08-16 11:34:34', 'devuelta'),
(13, 12, 3, 1, '2026-08-16 11:30:50', '2026-08-16 11:34:36', 'devuelta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `rol` enum('alumno','profesor','administrador') NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado') NOT NULL DEFAULT 'aprobado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `correo`, `password`, `rol`, `estado`) VALUES
(3, 'admin@correo.com', '$2y$10$8zKtFliXyAeYxE28TrN1sui1UGw.9jHxuvenfkya.2zLiEEhnRt9i', 'administrador', 'aprobado'),
(5, 'profesor@prueba.com', '$2y$10$kz8Rr6.rKC88M8ihCjW2BeL0.cfIC/ASnaAJsQXfTClvjekvNgbES', 'profesor', 'aprobado');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `correo` (`correo`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
