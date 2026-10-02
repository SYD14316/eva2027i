-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 02-10-2026 a las 18:31:21
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
-- Base de datos: `eva2027i`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `acude`
--

CREATE TABLE `acude` (
  `id` int(10) UNSIGNED NOT NULL,
  `actividad` text NOT NULL,
  `tipo` text NOT NULL,
  `profesor` text NOT NULL,
  `sexo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bach_acude`
--

CREATE TABLE `bach_acude` (
  `id` int(10) UNSIGNED NOT NULL,
  `carrera` text DEFAULT NULL,
  `grado` text DEFAULT NULL,
  `sexo` text DEFAULT NULL,
  `situacion` text DEFAULT NULL,
  `actividad` text DEFAULT NULL,
  `tipo` text DEFAULT NULL,
  `profesor` text DEFAULT NULL,
  `r01` text DEFAULT NULL,
  `r02` text DEFAULT NULL,
  `r03` text DEFAULT NULL,
  `r04` text DEFAULT NULL,
  `r05` text DEFAULT NULL,
  `r06` text DEFAULT NULL,
  `r07` text DEFAULT NULL,
  `r08` text DEFAULT NULL,
  `r09` text DEFAULT NULL,
  `r10` text DEFAULT NULL,
  `r11` text DEFAULT NULL,
  `r12` text DEFAULT NULL,
  `r13` text DEFAULT NULL,
  `r14` text DEFAULT NULL,
  `r15` text DEFAULT NULL,
  `r16` text DEFAULT NULL,
  `r17` text DEFAULT NULL,
  `r18` text DEFAULT NULL,
  `r01c` text DEFAULT NULL,
  `r02c` text DEFAULT NULL,
  `r03c` text DEFAULT NULL,
  `r04c` text DEFAULT NULL,
  `r05c` text DEFAULT NULL,
  `r06c` text DEFAULT NULL,
  `r07c` text DEFAULT NULL,
  `r08c` text DEFAULT NULL,
  `r09c` text DEFAULT NULL,
  `r10c` text DEFAULT NULL,
  `r11c` text DEFAULT NULL,
  `r12c` text DEFAULT NULL,
  `r13c` text DEFAULT NULL,
  `r14c` text DEFAULT NULL,
  `r15c` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bach_coaddi`
--

CREATE TABLE `bach_coaddi` (
  `id` int(10) UNSIGNED NOT NULL,
  `carrera` text DEFAULT NULL,
  `grado` text DEFAULT NULL,
  `sexo` text DEFAULT NULL,
  `situacion` text DEFAULT NULL,
  `r01` text DEFAULT NULL,
  `r02` text DEFAULT NULL,
  `r03` text DEFAULT NULL,
  `r04` text DEFAULT NULL,
  `r05` text DEFAULT NULL,
  `r06` text DEFAULT NULL,
  `r07` text DEFAULT NULL,
  `r08` text DEFAULT NULL,
  `r09` text DEFAULT NULL,
  `r10` text DEFAULT NULL,
  `r11` text DEFAULT NULL,
  `r12` text DEFAULT NULL,
  `r13` text DEFAULT NULL,
  `r14` text DEFAULT NULL,
  `r15` text DEFAULT NULL,
  `r16` text DEFAULT NULL,
  `r17` text DEFAULT NULL,
  `r18` text DEFAULT NULL,
  `r19` text DEFAULT NULL,
  `r20` text DEFAULT NULL,
  `r21` text DEFAULT NULL,
  `r22` text DEFAULT NULL,
  `r23` text DEFAULT NULL,
  `r24` text DEFAULT NULL,
  `r25` text DEFAULT NULL,
  `r26` text DEFAULT NULL,
  `r27` text DEFAULT NULL,
  `r28` text DEFAULT NULL,
  `r29` text DEFAULT NULL,
  `r30` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bach_general`
--

CREATE TABLE `bach_general` (
  `id` int(10) UNSIGNED NOT NULL,
  `carrera` text DEFAULT NULL,
  `grado` text DEFAULT NULL,
  `sexo` text DEFAULT NULL,
  `situacion` text DEFAULT NULL,
  `r01` text DEFAULT NULL,
  `r02` text DEFAULT NULL,
  `r03` text DEFAULT NULL,
  `r04` text DEFAULT NULL,
  `r05` text DEFAULT NULL,
  `r06` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bach_profesores`
--

CREATE TABLE `bach_profesores` (
  `id` int(10) UNSIGNED NOT NULL,
  `grupo` text DEFAULT NULL,
  `nombre` text DEFAULT NULL,
  `materia` text DEFAULT NULL,
  `sexo` text DEFAULT NULL,
  `carrera_a` text DEFAULT NULL,
  `grado_a` text DEFAULT NULL,
  `sexo_a` text DEFAULT NULL,
  `r01` text DEFAULT NULL,
  `r02` text DEFAULT NULL,
  `r03` text DEFAULT NULL,
  `r04` text DEFAULT NULL,
  `r05` text DEFAULT NULL,
  `r06` text DEFAULT NULL,
  `r07` text DEFAULT NULL,
  `r08` text DEFAULT NULL,
  `r09` text DEFAULT NULL,
  `r10` text DEFAULT NULL,
  `r11` text DEFAULT NULL,
  `r12` text DEFAULT NULL,
  `r13` text DEFAULT NULL,
  `r14` text DEFAULT NULL,
  `r15` text DEFAULT NULL,
  `r16` text DEFAULT NULL,
  `r17` text DEFAULT NULL,
  `r18` text DEFAULT NULL,
  `r19` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bach_titulares`
--

CREATE TABLE `bach_titulares` (
  `id` int(10) UNSIGNED NOT NULL,
  `grupo` text DEFAULT NULL,
  `nombre` text DEFAULT NULL,
  `sexo` text DEFAULT NULL,
  `carrera_a` text DEFAULT NULL,
  `grado_a` text DEFAULT NULL,
  `sexo_a` text DEFAULT NULL,
  `r01` text DEFAULT NULL,
  `r02` text DEFAULT NULL,
  `r03` text DEFAULT NULL,
  `r04` text DEFAULT NULL,
  `r05` text DEFAULT NULL,
  `r06` text DEFAULT NULL,
  `r07` text DEFAULT NULL,
  `r08` text DEFAULT NULL,
  `r09` text DEFAULT NULL,
  `r10` text DEFAULT NULL,
  `r11` text DEFAULT NULL,
  `r12` text DEFAULT NULL,
  `r13` text DEFAULT NULL,
  `r14` text DEFAULT NULL,
  `r15` text DEFAULT NULL,
  `r16` text DEFAULT NULL,
  `r17` text DEFAULT NULL,
  `r18` text DEFAULT NULL,
  `r19` text DEFAULT NULL,
  `r20` text DEFAULT NULL,
  `r21` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carreras`
--

CREATE TABLE `carreras` (
  `id` int(10) UNSIGNED NOT NULL,
  `departamento` text NOT NULL,
  `carrera` text NOT NULL,
  `coordinador` text NOT NULL,
  `sexo` text NOT NULL,
  `sello` text NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `departamentos`
--

CREATE TABLE `departamentos` (
  `id` int(10) UNSIGNED NOT NULL,
  `departamento` text NOT NULL,
  `jefe` text NOT NULL,
  `sexo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deporteycultura`
--

CREATE TABLE `deporteycultura` (
  `id` int(10) UNSIGNED NOT NULL,
  `actividad` text NOT NULL,
  `tipo` text NOT NULL,
  `profesor` text NOT NULL,
  `sexo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` int(10) UNSIGNED NOT NULL,
  `matricula` text NOT NULL,
  `grupo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_deporteycultura_c_por_coordinadores`
--

CREATE TABLE `lic_deporteycultura_c_por_coordinadores` (
  `id` int(10) UNSIGNED NOT NULL,
  `r01` float NOT NULL,
  `r02` float NOT NULL,
  `r03` float NOT NULL,
  `r04` float NOT NULL,
  `r05` float NOT NULL,
  `r06` float NOT NULL,
  `r07` float NOT NULL,
  `r08` float NOT NULL,
  `r09` float NOT NULL,
  `r10` float NOT NULL,
  `r11` float NOT NULL,
  `r12` float NOT NULL,
  `r13` float NOT NULL,
  `r14` float NOT NULL,
  `r15` float NOT NULL,
  `r16` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_deporteycultura_c_por_jefes`
--

CREATE TABLE `lic_deporteycultura_c_por_jefes` (
  `id` int(10) UNSIGNED NOT NULL,
  `r01` float NOT NULL,
  `r02` float NOT NULL,
  `r03` float NOT NULL,
  `r04` float NOT NULL,
  `r05` float NOT NULL,
  `r06` float NOT NULL,
  `r07` float NOT NULL,
  `r08` float NOT NULL,
  `r09` float NOT NULL,
  `r10` float NOT NULL,
  `r11` float NOT NULL,
  `r12` float NOT NULL,
  `r13` float NOT NULL,
  `r14` float NOT NULL,
  `r15` float NOT NULL,
  `r16` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_deporteycultura_c_por_profesores`
--

CREATE TABLE `lic_deporteycultura_c_por_profesores` (
  `id` int(10) UNSIGNED NOT NULL,
  `r01` float NOT NULL,
  `r02` float NOT NULL,
  `r03` float NOT NULL,
  `r04` float NOT NULL,
  `r05` float NOT NULL,
  `r06` float NOT NULL,
  `r07` float NOT NULL,
  `r08` float NOT NULL,
  `r09` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_deporteycultura_p_por_alumnos`
--

CREATE TABLE `lic_deporteycultura_p_por_alumnos` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` text NOT NULL,
  `taller` text NOT NULL,
  `sexo` text NOT NULL,
  `carrera` text NOT NULL,
  `grado` text NOT NULL,
  `r01` float NOT NULL,
  `r02` float NOT NULL,
  `r03` float NOT NULL,
  `r04` float NOT NULL,
  `r05` float NOT NULL,
  `r06` float NOT NULL,
  `r07` float NOT NULL,
  `r08` float NOT NULL,
  `r09` float NOT NULL,
  `r10` float NOT NULL,
  `r11` float NOT NULL,
  `r12` float NOT NULL,
  `r13` float NOT NULL,
  `r14` float NOT NULL,
  `r15` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_deporteycultura_p_por_coordinadores`
--

CREATE TABLE `lic_deporteycultura_p_por_coordinadores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` text NOT NULL,
  `sexo` text NOT NULL,
  `r01` float NOT NULL,
  `r02` float NOT NULL,
  `r03` float NOT NULL,
  `r04` float NOT NULL,
  `r05` float NOT NULL,
  `r06` float NOT NULL,
  `r07` float NOT NULL,
  `r08` float NOT NULL,
  `r09` float NOT NULL,
  `r10` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_deporteycultura_p_por_profesores`
--

CREATE TABLE `lic_deporteycultura_p_por_profesores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` text NOT NULL,
  `sexo` text NOT NULL,
  `r01` float NOT NULL,
  `r02` float NOT NULL,
  `r03` float NOT NULL,
  `r04` float NOT NULL,
  `r05` float NOT NULL,
  `r06` float NOT NULL,
  `r07` float NOT NULL,
  `r08` float NOT NULL,
  `r09` float NOT NULL,
  `r10` float NOT NULL,
  `r11` float NOT NULL,
  `r12` float NOT NULL,
  `r13` float NOT NULL,
  `r14` float NOT NULL,
  `r15` float NOT NULL,
  `r16` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_erys`
--

CREATE TABLE `lic_erys` (
  `id` int(10) UNSIGNED NOT NULL,
  `carrera` text NOT NULL,
  `grado` text NOT NULL,
  `sexo` text NOT NULL,
  `situacion` text NOT NULL,
  `r01` float DEFAULT NULL,
  `r02` float DEFAULT NULL,
  `r03` float DEFAULT NULL,
  `r04` float DEFAULT NULL,
  `r05` float DEFAULT NULL,
  `r06` float DEFAULT NULL,
  `r07` float DEFAULT NULL,
  `r08` float DEFAULT NULL,
  `r09` float DEFAULT NULL,
  `r10` float DEFAULT NULL,
  `r11` float DEFAULT NULL,
  `r12` float DEFAULT NULL,
  `r13` float DEFAULT NULL,
  `r14` float DEFAULT NULL,
  `r15` float DEFAULT NULL,
  `r16` float DEFAULT NULL,
  `r17` float DEFAULT NULL,
  `r18` float DEFAULT NULL,
  `r19` float DEFAULT NULL,
  `r20` float DEFAULT NULL,
  `r21` float DEFAULT NULL,
  `r22` text DEFAULT NULL,
  `r23` float DEFAULT NULL,
  `r24` float DEFAULT NULL,
  `r25` float DEFAULT NULL,
  `r26` float DEFAULT NULL,
  `r27` float DEFAULT NULL,
  `r28` float DEFAULT NULL,
  `r29` float DEFAULT NULL,
  `r30` float DEFAULT NULL,
  `r31` float DEFAULT NULL,
  `r32` float DEFAULT NULL,
  `r33` float DEFAULT NULL,
  `r34` float DEFAULT NULL,
  `r35` float DEFAULT NULL,
  `r36` float DEFAULT NULL,
  `r37` float DEFAULT NULL,
  `r38` float DEFAULT NULL,
  `r39` float DEFAULT NULL,
  `r40` float DEFAULT NULL,
  `r41` float DEFAULT NULL,
  `r42` float DEFAULT NULL,
  `r43` float DEFAULT NULL,
  `r44` float DEFAULT NULL,
  `r45` float DEFAULT NULL,
  `r46` float DEFAULT NULL,
  `r47` float DEFAULT NULL,
  `r48` float DEFAULT NULL,
  `r49` text DEFAULT NULL,
  `r50` float DEFAULT NULL,
  `r51` float DEFAULT NULL,
  `r52` float DEFAULT NULL,
  `r53` float DEFAULT NULL,
  `r54` text DEFAULT NULL,
  `r55` float DEFAULT NULL,
  `r56` float DEFAULT NULL,
  `r57` float DEFAULT NULL,
  `r58` float DEFAULT NULL,
  `r59` float DEFAULT NULL,
  `r60` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_erys_profesores`
--

CREATE TABLE `lic_erys_profesores` (
  `id` int(10) UNSIGNED NOT NULL,
  `carrera` text NOT NULL,
  `grado` text NOT NULL,
  `sexo` text NOT NULL,
  `situacion` text NOT NULL,
  `r01` float DEFAULT NULL,
  `r02` float DEFAULT NULL,
  `r03` float DEFAULT NULL,
  `r04` float DEFAULT NULL,
  `r05` float DEFAULT NULL,
  `r06` float DEFAULT NULL,
  `r07` float DEFAULT NULL,
  `r08` float DEFAULT NULL,
  `r09` float DEFAULT NULL,
  `r10` float DEFAULT NULL,
  `r11` float DEFAULT NULL,
  `r12` float DEFAULT NULL,
  `r13` float DEFAULT NULL,
  `r14` float DEFAULT NULL,
  `r15` float DEFAULT NULL,
  `r16` float DEFAULT NULL,
  `r17` float DEFAULT NULL,
  `r18` float DEFAULT NULL,
  `r19` float DEFAULT NULL,
  `r20` float DEFAULT NULL,
  `r21` float DEFAULT NULL,
  `r22` text DEFAULT NULL,
  `r23` float DEFAULT NULL,
  `r24` float DEFAULT NULL,
  `r25` float DEFAULT NULL,
  `r26` float DEFAULT NULL,
  `r27` float DEFAULT NULL,
  `r28` float DEFAULT NULL,
  `r29` float DEFAULT NULL,
  `r30` float DEFAULT NULL,
  `r31` float DEFAULT NULL,
  `r32` float DEFAULT NULL,
  `r33` float DEFAULT NULL,
  `r34` float DEFAULT NULL,
  `r35` float DEFAULT NULL,
  `r36` float DEFAULT NULL,
  `r37` float DEFAULT NULL,
  `r38` float DEFAULT NULL,
  `r39` float DEFAULT NULL,
  `r40` float DEFAULT NULL,
  `r41` float DEFAULT NULL,
  `r42` float DEFAULT NULL,
  `r43` float DEFAULT NULL,
  `r44` float DEFAULT NULL,
  `r45` float DEFAULT NULL,
  `r46` float DEFAULT NULL,
  `r47` float DEFAULT NULL,
  `r48` float DEFAULT NULL,
  `r49` text DEFAULT NULL,
  `r50` float DEFAULT NULL,
  `r51` float DEFAULT NULL,
  `r52` float DEFAULT NULL,
  `r53` float DEFAULT NULL,
  `r54` text DEFAULT NULL,
  `r55` float DEFAULT NULL,
  `r56` float DEFAULT NULL,
  `r57` float DEFAULT NULL,
  `r58` float DEFAULT NULL,
  `r59` float DEFAULT NULL,
  `r60` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_fidco`
--

CREATE TABLE `lic_fidco` (
  `id` int(10) UNSIGNED NOT NULL,
  `carrera` text NOT NULL,
  `grado` text NOT NULL,
  `sexo` text NOT NULL,
  `situacion` text NOT NULL,
  `r01` float NOT NULL,
  `r02` float NOT NULL,
  `r03` float NOT NULL,
  `r04` float NOT NULL,
  `r05` float NOT NULL,
  `r06` float NOT NULL,
  `r07` float NOT NULL,
  `r08` float NOT NULL,
  `r09` float NOT NULL,
  `r10` float NOT NULL,
  `r11` float NOT NULL,
  `r12` float NOT NULL,
  `r13` float NOT NULL,
  `r14` float NOT NULL,
  `r15` float NOT NULL,
  `r16` text NOT NULL,
  `r17` text NOT NULL,
  `r18` text NOT NULL,
  `r19` text NOT NULL,
  `r20` text NOT NULL,
  `r21` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_ingles`
--

CREATE TABLE `lic_ingles` (
  `id` int(10) UNSIGNED NOT NULL,
  `carrera` text NOT NULL,
  `grado` text NOT NULL,
  `sexo` text NOT NULL,
  `situacion` text NOT NULL,
  `r01` float DEFAULT NULL,
  `r02` float DEFAULT NULL,
  `r03` float DEFAULT NULL,
  `r04` float DEFAULT NULL,
  `r05` float DEFAULT NULL,
  `r06` float DEFAULT NULL,
  `r07` float DEFAULT NULL,
  `r08` float DEFAULT NULL,
  `r09` float DEFAULT NULL,
  `r10` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_materiasi_por_alumnos`
--

CREATE TABLE `lic_materiasi_por_alumnos` (
  `id` int(10) UNSIGNED NOT NULL,
  `carrera` text NOT NULL,
  `grado` text NOT NULL,
  `aplicacion` int(11) DEFAULT NULL,
  `r01` float NOT NULL,
  `r02` float NOT NULL,
  `r03` float NOT NULL,
  `r04` float NOT NULL,
  `r05` float NOT NULL,
  `r06` float NOT NULL,
  `r07` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_materiasi_por_coordinadores`
--

CREATE TABLE `lic_materiasi_por_coordinadores` (
  `id` int(10) UNSIGNED NOT NULL,
  `r01` text NOT NULL,
  `r02` text NOT NULL,
  `r03` text NOT NULL,
  `r04` text NOT NULL,
  `r05` text NOT NULL,
  `r06` text NOT NULL,
  `r07` text NOT NULL,
  `r08` text NOT NULL,
  `r09` text NOT NULL,
  `r10` text NOT NULL,
  `r11` text NOT NULL,
  `r12` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_materiasi_por_jefes`
--

CREATE TABLE `lic_materiasi_por_jefes` (
  `id` int(10) UNSIGNED NOT NULL,
  `r01` float NOT NULL,
  `r02` float NOT NULL,
  `r03` float NOT NULL,
  `r04` float NOT NULL,
  `r05` float NOT NULL,
  `r06` float NOT NULL,
  `r07` float NOT NULL,
  `r08` float NOT NULL,
  `r09` float NOT NULL,
  `r10` float NOT NULL,
  `r11` float NOT NULL,
  `r12` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_materiasi_por_profesores`
--

CREATE TABLE `lic_materiasi_por_profesores` (
  `id` int(10) UNSIGNED NOT NULL,
  `r01` float NOT NULL,
  `r02` float NOT NULL,
  `r03` float NOT NULL,
  `r04` float NOT NULL,
  `r05` float NOT NULL,
  `r06` float NOT NULL,
  `r07` float NOT NULL,
  `r08` float NOT NULL,
  `r09` float NOT NULL,
  `r10` float NOT NULL,
  `r11` float NOT NULL,
  `r12` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_profesores_por_alumnos`
--

CREATE TABLE `lic_profesores_por_alumnos` (
  `id` int(10) UNSIGNED NOT NULL,
  `grupo` text DEFAULT NULL,
  `nombre` text DEFAULT NULL,
  `materia` text DEFAULT NULL,
  `sello` text DEFAULT NULL,
  `sexo` text DEFAULT NULL,
  `carrera` text DEFAULT NULL,
  `grado` text DEFAULT NULL,
  `aplicacion` text DEFAULT NULL,
  `r01` float DEFAULT NULL,
  `r02` float DEFAULT NULL,
  `r03` float DEFAULT NULL,
  `r04` float DEFAULT NULL,
  `r05` float DEFAULT NULL,
  `r06` text DEFAULT NULL,
  `r07` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_profesores_por_coordinadores`
--

CREATE TABLE `lic_profesores_por_coordinadores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` text NOT NULL,
  `sexo` text NOT NULL,
  `carrera` text NOT NULL,
  `sello` text NOT NULL,
  `aplicacion` int(11) DEFAULT NULL,
  `r01` float DEFAULT NULL,
  `r02` float DEFAULT NULL,
  `r03` float DEFAULT NULL,
  `r04` float DEFAULT NULL,
  `r05` float DEFAULT NULL,
  `r06` text DEFAULT NULL,
  `r07` text DEFAULT NULL,
  `r08` text DEFAULT NULL,
  `r09` text DEFAULT NULL,
  `r10` text DEFAULT NULL,
  `r11` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lic_profesores_por_profesores`
--

CREATE TABLE `lic_profesores_por_profesores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` text NOT NULL,
  `sexo` text NOT NULL,
  `carrera` text NOT NULL,
  `materia` text DEFAULT NULL,
  `grupo` text DEFAULT NULL,
  `aplicacion` text DEFAULT NULL,
  `r01` float NOT NULL,
  `r02` float NOT NULL,
  `r03` float NOT NULL,
  `r04` float NOT NULL,
  `r05` float NOT NULL,
  `r06` text NOT NULL,
  `r07` text NOT NULL,
  `r08` text NOT NULL,
  `r09` text NOT NULL,
  `r10` text NOT NULL,
  `r11` text NOT NULL,
  `r12` text NOT NULL,
  `r13` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes`
--

CREATE TABLE `participantes` (
  `id` int(10) UNSIGNED NOT NULL,
  `nivel` text NOT NULL DEFAULT '',
  `correo` text NOT NULL,
  `matricula` text NOT NULL,
  `clave` text NOT NULL,
  `nombre` text NOT NULL DEFAULT '',
  `carrera` text NOT NULL DEFAULT '',
  `grado` text NOT NULL DEFAULT '',
  `sexo` text NOT NULL DEFAULT '',
  `situacion` text NOT NULL DEFAULT '',
  `evaluados` text NOT NULL DEFAULT '',
  `num_evaluados` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `total_evaluaciones` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `ultimo_acceso` text NOT NULL DEFAULT '',
  `nivel_acceso` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores`
--

CREATE TABLE `profesores` (
  `id` int(10) UNSIGNED NOT NULL,
  `nivel` text NOT NULL,
  `carrera` text NOT NULL,
  `grupo` text NOT NULL,
  `nombre` text NOT NULL,
  `materia` text NOT NULL,
  `sexo` text NOT NULL,
  `sello` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `titulares`
--

CREATE TABLE `titulares` (
  `id` int(10) UNSIGNED NOT NULL,
  `grupo` text NOT NULL,
  `nombre` text NOT NULL,
  `sexo` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `acude`
--
ALTER TABLE `acude`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bach_acude`
--
ALTER TABLE `bach_acude`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bach_coaddi`
--
ALTER TABLE `bach_coaddi`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bach_general`
--
ALTER TABLE `bach_general`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bach_profesores`
--
ALTER TABLE `bach_profesores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `bach_titulares`
--
ALTER TABLE `bach_titulares`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `carreras`
--
ALTER TABLE `carreras`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `deporteycultura`
--
ALTER TABLE `deporteycultura`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_deporteycultura_c_por_coordinadores`
--
ALTER TABLE `lic_deporteycultura_c_por_coordinadores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_deporteycultura_c_por_jefes`
--
ALTER TABLE `lic_deporteycultura_c_por_jefes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_deporteycultura_c_por_profesores`
--
ALTER TABLE `lic_deporteycultura_c_por_profesores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_deporteycultura_p_por_alumnos`
--
ALTER TABLE `lic_deporteycultura_p_por_alumnos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_deporteycultura_p_por_coordinadores`
--
ALTER TABLE `lic_deporteycultura_p_por_coordinadores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_deporteycultura_p_por_profesores`
--
ALTER TABLE `lic_deporteycultura_p_por_profesores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_erys`
--
ALTER TABLE `lic_erys`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_erys_profesores`
--
ALTER TABLE `lic_erys_profesores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_fidco`
--
ALTER TABLE `lic_fidco`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_ingles`
--
ALTER TABLE `lic_ingles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_materiasi_por_alumnos`
--
ALTER TABLE `lic_materiasi_por_alumnos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_materiasi_por_coordinadores`
--
ALTER TABLE `lic_materiasi_por_coordinadores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_materiasi_por_jefes`
--
ALTER TABLE `lic_materiasi_por_jefes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_materiasi_por_profesores`
--
ALTER TABLE `lic_materiasi_por_profesores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_profesores_por_alumnos`
--
ALTER TABLE `lic_profesores_por_alumnos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_profesores_por_coordinadores`
--
ALTER TABLE `lic_profesores_por_coordinadores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `lic_profesores_por_profesores`
--
ALTER TABLE `lic_profesores_por_profesores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `participantes`
--
ALTER TABLE `participantes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `profesores`
--
ALTER TABLE `profesores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `titulares`
--
ALTER TABLE `titulares`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `acude`
--
ALTER TABLE `acude`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bach_acude`
--
ALTER TABLE `bach_acude`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bach_coaddi`
--
ALTER TABLE `bach_coaddi`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bach_general`
--
ALTER TABLE `bach_general`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bach_profesores`
--
ALTER TABLE `bach_profesores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `bach_titulares`
--
ALTER TABLE `bach_titulares`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `carreras`
--
ALTER TABLE `carreras`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `departamentos`
--
ALTER TABLE `departamentos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `deporteycultura`
--
ALTER TABLE `deporteycultura`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_deporteycultura_c_por_coordinadores`
--
ALTER TABLE `lic_deporteycultura_c_por_coordinadores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_deporteycultura_c_por_jefes`
--
ALTER TABLE `lic_deporteycultura_c_por_jefes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_deporteycultura_c_por_profesores`
--
ALTER TABLE `lic_deporteycultura_c_por_profesores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_deporteycultura_p_por_alumnos`
--
ALTER TABLE `lic_deporteycultura_p_por_alumnos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_deporteycultura_p_por_coordinadores`
--
ALTER TABLE `lic_deporteycultura_p_por_coordinadores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_deporteycultura_p_por_profesores`
--
ALTER TABLE `lic_deporteycultura_p_por_profesores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_erys`
--
ALTER TABLE `lic_erys`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_erys_profesores`
--
ALTER TABLE `lic_erys_profesores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_fidco`
--
ALTER TABLE `lic_fidco`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_ingles`
--
ALTER TABLE `lic_ingles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_materiasi_por_alumnos`
--
ALTER TABLE `lic_materiasi_por_alumnos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_materiasi_por_coordinadores`
--
ALTER TABLE `lic_materiasi_por_coordinadores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_materiasi_por_jefes`
--
ALTER TABLE `lic_materiasi_por_jefes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_materiasi_por_profesores`
--
ALTER TABLE `lic_materiasi_por_profesores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_profesores_por_alumnos`
--
ALTER TABLE `lic_profesores_por_alumnos`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_profesores_por_coordinadores`
--
ALTER TABLE `lic_profesores_por_coordinadores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `lic_profesores_por_profesores`
--
ALTER TABLE `lic_profesores_por_profesores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `profesores`
--
ALTER TABLE `profesores`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `titulares`
--
ALTER TABLE `titulares`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
