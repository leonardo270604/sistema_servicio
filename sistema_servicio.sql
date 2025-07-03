-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-07-2025 a las 23:01:47
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
-- Base de datos: `sistema_servicio`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `IDDEPTO` int(11) NOT NULL,
  `DESCRIPCION` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `departamentos`
--

INSERT INTO `departamentos` (`IDDEPTO`, `DESCRIPCION`) VALUES
(1, 'DEPTO. ADMINISTRATIVO'),
(2, 'DEPTO. ACADÉMICO'),
(3, 'DEPTO. DE EXTENSIÓN EDUCATIVA'),
(4, 'DEPTO. DE VINCULACIÓN'),
(5, 'DEPTO. JURÍDICO'),
(6, 'DEPTO. INFORMÁTICA'),
(7, 'DEPTO. PLANEACION'),
(8, 'DEPTO. DE RECURSOS HUMANOS'),
(9, 'DIRECCIÓN DE PLANTEL'),
(10, 'DEPTO. CONTROL ESCOLAR'),
(11, 'OTRO'),
(12, 'DEPTO. DE RECURSOS MATERIALES');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_comentarios`
--

CREATE TABLE `historial_comentarios` (
  `id` int(11) NOT NULL,
  `id_servicio` int(11) NOT NULL,
  `solicitante` varchar(100) DEFAULT NULL,
  `departamento` varchar(100) DEFAULT NULL,
  `tipo_servicio` varchar(100) DEFAULT NULL,
  `fecha_solicitud` datetime DEFAULT NULL,
  `tecnico` varchar(100) DEFAULT NULL,
  `comentario` text DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `historial_comentarios`
--

INSERT INTO `historial_comentarios` (`id`, `id_servicio`, `solicitante`, `departamento`, `tipo_servicio`, `fecha_solicitud`, `tecnico`, `comentario`, `fecha_registro`) VALUES
(1, 9, 'Leonardo', 'DEPTO. ACADÉMICO', 'Mantenimiento a equipo de computo', '2025-06-30 20:23:10', 'Ramon', 'gjhklkjkl', '2025-06-30 18:28:26'),
(2, 11, 'Leonardo', 'DEPTO. ACADÉMICO', 'Mantenimiento a equipo de computo', '2025-06-30 20:30:03', 'Ramon', 'lñklñk', '2025-06-30 18:41:40'),
(3, 12, 'Leonardo', 'DEPTO. ACADÉMICO', 'Impresora', '2025-06-30 20:30:06', 'Andres', 'Se instalo una nueva impresora', '2025-06-30 18:43:39'),
(4, 14, 'Leonardo', 'DEPTO. ACADÉMICO', 'Instalar equipo de computo', '2025-06-30 20:44:51', 'Ramon', 'hgyfcgjhv', '2025-06-30 18:50:08'),
(5, 17, 'Home', 'DEPTO. INFORMÁTICA', 'Impresora', '2025-06-30 13:28:19', 'Ramon', 'sfs<fdkjkl', '2025-06-30 19:28:55'),
(6, 16, 'Home', 'DEPTO. INFORMÁTICA', 'Cuenta de correo', '2025-06-30 20:48:10', 'Ramon', 'Se reestableció una contraseña', '2025-06-30 21:32:17'),
(7, 13, 'Leonardo', 'DEPTO. ACADÉMICO', 'Problema con carpetas compartidas', '2025-06-30 20:41:05', 'Andres', 'Se abrió una nueva carpeta  ', '2025-07-01 15:20:12'),
(8, 13, 'Andres', 'DEPTO. INFORMÁTICA', 'Problema con carpetas compartidas', '2025-06-30 20:41:05', 'Andres', 'Se abrió una nueva carpeta grt', '2025-07-01 15:38:59'),
(9, 12, 'Leonardo', 'DEPTO. ACADÉMICO', 'Impresora', '2025-06-30 20:30:06', NULL, 'Se instalo una nueva impresora ph', '2025-07-01 15:54:08'),
(10, 5, 'Leonardo', 'DEPTO. ACADÉMICO', 'Internet', '2025-06-30 18:50:20', 'Andres', 'Se instalo una antena', '2025-07-01 15:57:01'),
(11, 4, 'Leonardo', 'DEPTO. ACADÉMICO', 'Cuenta de correo', '2025-06-30 18:50:14', 'Andres', 'Se creo una nueva cuenta de correo', '2025-07-01 16:15:33'),
(12, 12, 'Leonardo', 'DEPTO. ACADÉMICO', 'Impresora', '2025-06-30 20:30:06', 'Andres', 'Se instalo una nueva impresora', '2025-07-01 16:17:30'),
(13, 13, 'Leonardo', 'DEPTO. ACADÉMICO', 'Problema con carpetas compartidas', '2025-06-30 20:41:05', 'Andres', 'Se abrió una nueva carpeta', '2025-07-01 16:17:35'),
(14, 11, 'Leonardo', 'DEPTO. ACADÉMICO', 'Mantenimiento a equipo de computo', '2025-06-30 20:30:03', 'Ramon', 'Se cambio un disco mecánico a un solido', '2025-07-01 16:21:18'),
(15, 6, 'Leonardo', 'DEPTO. ACADÉMICO', 'Cuenta de correo', '2025-06-30 19:04:38', 'Jaime', 'Se cambio la contraseña de un correo', '2025-07-01 16:23:00'),
(16, 2, 'Leonardo', 'DEPTO. ACADÉMICO', 'Cuenta de correo', '2025-06-30 18:22:37', 'Ramon', 'Se creo una cuenta', '2025-07-01 14:02:43'),
(17, 9, 'Leonardo', 'DEPTO. ACADÉMICO', 'Mantenimiento a equipo de computo', '2025-06-30 20:23:10', 'Ramon', 'Se reinicio un equipo de computo', '2025-07-02 15:46:48'),
(18, 15, 'Leonardo', 'DEPTO. ACADÉMICO', 'Internet', '2025-06-30 20:45:04', 'Andres', 'Se instalo una nueva antena de wi-fi', '2025-07-02 16:18:12'),
(19, 19, 'Home', 'DEPTO. INFORMÁTICA', 'Instalar equipo de computo', '2025-07-01 09:18:52', 'Andres', 'Se instalo un monitor', '2025-07-02 18:08:11'),
(20, 17, 'Home', 'DEPTO. INFORMÁTICA', 'Impresora', '2025-06-30 13:28:19', 'Ramon', 'Se configuro una nueva impresora', '2025-07-02 18:49:00'),
(21, 7, 'Leonardo', 'DEPTO. ACADÉMICO', 'Desarrollo de sistema', '2025-06-30 19:04:47', 'Ramon', 'Se creo un sistema de inventario', '2025-07-02 19:35:09');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicios`
--

CREATE TABLE `servicios` (
  `id_servicio` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_tipo_servicio` int(11) NOT NULL,
  `fecha_solicitud` datetime DEFAULT current_timestamp(),
  `estado` enum('pendiente','en proceso','completado') DEFAULT 'pendiente',
  `id_tecnico` int(11) DEFAULT NULL,
  `comentario` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicios`
--

INSERT INTO `servicios` (`id_servicio`, `id_usuario`, `id_tipo_servicio`, `fecha_solicitud`, `estado`, `id_tecnico`, `comentario`) VALUES
(1, 4, 2, '2025-06-30 16:25:33', 'completado', 2, 'Se creo un sistema para préstamo de libros'),
(2, 4, 1, '2025-06-30 18:22:37', 'completado', 2, 'Se creo una cuenta'),
(3, 4, 2, '2025-06-30 18:43:43', 'completado', 2, 'dfjxhjhc'),
(4, 4, 1, '2025-06-30 18:50:14', 'completado', 3, 'Se creo una nueva cuenta de correo'),
(5, 4, 3, '2025-06-30 18:50:20', 'completado', 3, 'Se instalo una antena'),
(6, 4, 1, '2025-06-30 19:04:38', 'completado', 2, 'Se cambio la contraseña de un correo'),
(7, 4, 2, '2025-06-30 19:04:47', 'completado', 2, 'Se creo un sistema de inventario'),
(8, 4, 6, '2025-06-30 19:38:44', 'completado', 6, 'Se configuro una nueva impresora'),
(9, 4, 5, '2025-06-30 20:23:10', 'completado', 2, 'Se reinicio un equipo de computo'),
(10, 6, 1, '2025-06-30 20:14:10', 'completado', 3, 'Se cambio la contraseña'),
(11, 4, 5, '2025-06-30 20:30:03', 'completado', 2, 'Se cambio un disco mecánico a un solido'),
(12, 4, 6, '2025-06-30 20:30:06', 'completado', 3, 'Se instalo una nueva impresora'),
(13, 4, 8, '2025-06-30 20:41:05', 'completado', 3, 'Se abrió una nueva carpeta'),
(14, 4, 4, '2025-06-30 20:44:51', 'completado', 2, 'Se instalo un monitor'),
(15, 4, 3, '2025-06-30 20:45:04', 'completado', 3, 'Se instalo una nueva antena de wi-fi'),
(16, 6, 1, '2025-06-30 20:48:10', 'completado', 2, 'Se reestableció una contraseña'),
(17, 6, 6, '2025-06-30 13:28:19', 'completado', 2, 'Se configuro una nueva impresora'),
(18, 6, 1, '2025-06-30 14:08:02', 'pendiente', 2, NULL),
(19, 6, 4, '2025-07-01 09:18:52', 'completado', 3, 'Se instalo un monitor'),
(20, 6, 2, '2025-07-01 09:18:56', 'pendiente', 3, NULL),
(21, 6, 6, '2025-07-01 10:46:00', 'pendiente', 2, NULL),
(22, 6, 4, '2025-07-01 10:48:32', 'pendiente', 2, NULL),
(23, 4, 1, '2025-07-01 10:50:35', 'pendiente', NULL, NULL),
(24, 4, 6, '2025-07-02 13:36:12', 'pendiente', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tiposervicio`
--

CREATE TABLE `tiposervicio` (
  `id_tipo_servicio` int(11) NOT NULL,
  `nombre_servicio` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tiposervicio`
--

INSERT INTO `tiposervicio` (`id_tipo_servicio`, `nombre_servicio`) VALUES
(1, 'Cuenta de correo'),
(2, 'Desarrollo de sistema'),
(3, 'Internet'),
(4, 'Instalar equipo de computo'),
(5, 'Mantenimiento a equipo de computo'),
(6, 'Impresora'),
(7, 'Problema con sistema operativo'),
(8, 'Problema con carpetas compartidas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `idpersonal` int(11) DEFAULT NULL,
  `IDDEPTO` int(11) DEFAULT NULL,
  `rol` enum('usuario','técnico','admin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `usuario`, `contrasena`, `idpersonal`, `IDDEPTO`, `rol`) VALUES
(1, 'Jaime', '789654', NULL, 6, 'admin'),
(2, 'Ramon', '987654', NULL, 6, 'técnico'),
(3, 'Andres', '456123', NULL, 6, 'técnico'),
(4, 'Leonardo', '237910', NULL, 2, 'usuario'),
(6, 'Home', '237911', NULL, 6, 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`IDDEPTO`);

--
-- Indices de la tabla `historial_comentarios`
--
ALTER TABLE `historial_comentarios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD PRIMARY KEY (`id_servicio`),
  ADD KEY `fk_usuario` (`id_usuario`),
  ADD KEY `fk_tipo_servicio` (`id_tipo_servicio`),
  ADD KEY `fk_tecnico` (`id_tecnico`);

--
-- Indices de la tabla `tiposervicio`
--
ALTER TABLE `tiposervicio`
  ADD PRIMARY KEY (`id_tipo_servicio`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `IDDEPTO` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `historial_comentarios`
--
ALTER TABLE `historial_comentarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `servicios`
--
ALTER TABLE `servicios`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `tiposervicio`
--
ALTER TABLE `tiposervicio`
  MODIFY `id_tipo_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `servicios`
--
ALTER TABLE `servicios`
  ADD CONSTRAINT `fk_tecnico` FOREIGN KEY (`id_tecnico`) REFERENCES `usuarios` (`id_usuario`),
  ADD CONSTRAINT `fk_tipo_servicio` FOREIGN KEY (`id_tipo_servicio`) REFERENCES `tiposervicio` (`id_tipo_servicio`),
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
