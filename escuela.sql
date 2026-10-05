-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-10-2026 a las 16:10:13
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `escuela`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumnos`
--

CREATE TABLE `alumnos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apaterno` varchar(50) NOT NULL,
  `amaterno` varchar(50) DEFAULT NULL,
  `dom` varchar(100) DEFAULT NULL,
  `mail` varchar(100) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  `id_grupo` varchar(50) DEFAULT NULL,
  `estatus` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alumnos`
--

INSERT INTO `alumnos` (`id`, `nombre`, `apaterno`, `amaterno`, `dom`, `mail`, `tel`, `id_grupo`, `estatus`) VALUES
(1, 'Alma Azucena', 'Brito', 'Rios', 'NURU', 'azulzcn@gmail.com', '5566638548', '8t1', 1),
(2, 'nruruc', 'dm cd', 'Brito Rios', 'dkcvfd', 'd@mfc', '5566638548', '8t1', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ba_grupos`
--

CREATE TABLE `ba_grupos` (
  `id_baja` int(11) NOT NULL,
  `id_grupo` int(11) DEFAULT NULL,
  `nombre_alumno` varchar(100) NOT NULL,
  `grupo` varchar(50) NOT NULL,
  `matricula` varchar(50) NOT NULL,
  `carrera` varchar(100) NOT NULL,
  `semestre` varchar(20) NOT NULL,
  `estatus_grupo` varchar(20) NOT NULL,
  `fecha_baja` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id_grupo` int(11) NOT NULL,
  `nombre_alumno` varchar(100) NOT NULL,
  `grupo` varchar(50) NOT NULL,
  `matricula` varchar(50) NOT NULL,
  `carrera` varchar(100) NOT NULL,
  `semestre` varchar(20) NOT NULL,
  `estatus_grupo` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id_grupo`, `nombre_alumno`, `grupo`, `matricula`, `carrera`, `semestre`, `estatus_grupo`) VALUES
(10, 'alma ', '8t1', 'L251250033@gamadero2.tecnm.mx', 'tic´s', '8', 'Activo'),
(11, 'nuaa', '8t1', 'xcx', 'tic´s', '4', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

CREATE TABLE `materias` (
  `id_mat` int(11) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `id_prof` int(11) NOT NULL,
  `estatus` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id_mat`, `descripcion`, `id_prof`, `estatus`) VALUES
(1, 'matematica', 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id_prof` int(11) NOT NULL,
  `nom_prof` varchar(100) NOT NULL,
  `apaterno_prof` varchar(100) NOT NULL,
  `amaterno_prof` varchar(100) DEFAULT NULL,
  `dom_prof` varchar(150) DEFAULT NULL,
  `mail_prof` varchar(100) DEFAULT NULL,
  `tel_prof` varchar(20) DEFAULT NULL,
  `estatus_prof` varchar(20) DEFAULT NULL,
  `estatus` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `profesores`
--

INSERT INTO `profesores` (`id_prof`, `nom_prof`, `apaterno_prof`, `amaterno_prof`, `dom_prof`, `mail_prof`, `tel_prof`, `estatus_prof`, `estatus`) VALUES
(1, 'nruruc', 'dm cd', 'Brito Rios', 'dkcvfd', 'd@mfc', '5566638548', NULL, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ba_grupos`
--
ALTER TABLE `ba_grupos`
  ADD PRIMARY KEY (`id_baja`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id_grupo`);

--
-- Indices de la tabla `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`id_mat`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id_prof`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alumnos`
--
ALTER TABLE `alumnos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ba_grupos`
--
ALTER TABLE `ba_grupos`
  MODIFY `id_baja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id_grupo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `materias`
--
ALTER TABLE `materias`
  MODIFY `id_mat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id_prof` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
