-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-07-2025 a las 22:52:04
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
-- Base de datos: `valoracion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(30) NOT NULL,
  `perfil_categoria_fk` int(11) NOT NULL,
  `ponderacion_categoria` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`, `perfil_categoria_fk`, `ponderacion_categoria`) VALUES
(1, 'Experiencia', 1, 60),
(2, 'Experiencia', 2, 50),
(3, 'Experto', 1, 5),
(4, 'Experto', 2, 0),
(5, 'Formación Laboral', 1, 15),
(6, 'Formación Laboral', 2, 10),
(7, 'Educación', 1, 20),
(8, 'Educación', 2, 40);

--
-- Disparadores `categorias`
--
DELIMITER $$
CREATE TRIGGER `actualizar_categoria` AFTER UPDATE ON `categorias` FOR EACH ROW BEGIN
    DECLARE cambios TEXT DEFAULT '';

    IF OLD.nombre_categoria <> NEW.nombre_categoria THEN
        SET cambios = CONCAT(cambios, 'nombre_categoria: ', OLD.nombre_categoria, ' -> ', NEW.nombre_categoria, '; ');
    END IF;

    IF OLD.perfil_categoria_fk <> NEW.perfil_categoria_fk THEN
        SET cambios = CONCAT(cambios, 'perfil_fk: ', OLD.perfil_categoria_fk, ' -> ', NEW.perfil_categoria_fk, '; ');
    END IF;

    IF OLD.ponderacion_categoria <> NEW.ponderacion_categoria THEN
        SET cambios = CONCAT(cambios, 'ponderacion: ', OLD.ponderacion_categoria, ' -> ', NEW.ponderacion_categoria, '; ');
    END IF;

    IF cambios <> '' THEN
        INSERT INTO historiales (fecha_hora, usuario_responsable, tipo_de_accion, accion)
        VALUES (NOW(), IFNULL(@usuario_actual, 'usuario no definido'), 'Actualización de categoría', cambios);
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `eliminar_categoria` AFTER DELETE ON `categorias` FOR EACH ROW BEGIN
    DECLARE datos TEXT;

    SET datos = CONCAT(
        'nombre_categoria: ', OLD.nombre_categoria, '; ',
        'perfil_fk: ', OLD.perfil_categoria_fk, '; ',
        'ponderacion: ', OLD.ponderacion_categoria
    );

    INSERT INTO historiales (fecha_hora, usuario_responsable, tipo_de_accion, accion)
    VALUES (NOW(), IFNULL(@usuario_actual, 'usuario no definido'), 'Eliminación de categoría', datos);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertar_categoria` BEFORE INSERT ON `categorias` FOR EACH ROW BEGIN
    DECLARE datos TEXT;

    SET datos = CONCAT(
        'nombre_categoria: ', NEW.nombre_categoria, '; ',
        'perfil_fk: ', NEW.perfil_categoria_fk, '; ',
        'ponderacion: ', NEW.ponderacion_categoria
    );

    INSERT INTO historiales (fecha_hora, usuario_responsable, tipo_de_accion, accion)
    VALUES (NOW(), IFNULL(@usuario_actual, 'usuario no definido'), 'Creación de categoría', datos);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiencias`
--

CREATE TABLE `experiencias` (
  `id_experiencia` int(11) NOT NULL,
  `resumen_experiencias_fk` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `dias` int(11) NOT NULL,
  `puntaje` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `experiencias`
--
DELIMITER $$
CREATE TRIGGER `actualizar_experiencias` AFTER UPDATE ON `experiencias` FOR EACH ROW BEGIN
    DECLARE cambios TEXT DEFAULT '';

    IF OLD.resumen_experiencias_fk <> NEW.resumen_experiencias_fk THEN
        SET cambios = CONCAT(cambios, 'resumen_experiencias_fk: ', OLD.resumen_experiencias_fk, ' -> ', NEW.resumen_experiencias_fk, '; ');
    END IF;

    IF OLD.fecha_inicio <> NEW.fecha_inicio THEN
        SET cambios = CONCAT(cambios, 'fecha_inicio: ', OLD.fecha_inicio, ' -> ', NEW.fecha_inicio, '; ');
    END IF;

    IF OLD.fecha_fin <> NEW.fecha_fin THEN
        SET cambios = CONCAT(cambios, 'fecha_fin: ', OLD.fecha_fin, ' -> ', NEW.fecha_fin, '; ');
    END IF;

    IF OLD.dias <> NEW.dias THEN
        SET cambios = CONCAT(cambios, 'dias: ', OLD.dias, ' -> ', NEW.dias, '; ');
    END IF;

    IF OLD.puntaje <> NEW.puntaje THEN
        SET cambios = CONCAT(cambios, 'puntaje: ', OLD.puntaje, ' -> ', NEW.puntaje, '; ');
    END IF;

    IF cambios <> '' THEN
        INSERT INTO historiales (
            fecha_hora, usuario_responsable, tipo_de_accion, accion
        ) VALUES (
            NOW(), IFNULL(@usuario_actual, 'usuario no definido'), 'Actualización de experiencia', cambios
        );
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `eliminar_experiencias` AFTER DELETE ON `experiencias` FOR EACH ROW BEGIN
    DECLARE datos TEXT;

    SET datos = CONCAT(
        'resumen_experiencias_fk: ', OLD.resumen_experiencias_fk, '; ',
        'fecha_inicio: ', OLD.fecha_inicio, '; ',
        'fecha_fin: ', OLD.fecha_fin, '; ',
        'dias: ', OLD.dias, '; ',
        'puntaje: ', OLD.puntaje
    );

    INSERT INTO historiales (
        fecha_hora, usuario_responsable, tipo_de_accion, accion
    ) VALUES (
        NOW(), IFNULL(@usuario_actual, 'usuario no definido'), 'Eliminación de experiencia', datos
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertar_experiencias` AFTER INSERT ON `experiencias` FOR EACH ROW BEGIN
    DECLARE datos TEXT;

    SET datos = CONCAT(
        'resumen_experiencias_fk: ', NEW.resumen_experiencias_fk, '; ',
        'fecha_inicio: ', NEW.fecha_inicio, '; ',
        'fecha_fin: ', NEW.fecha_fin, '; ',
        'dias: ', NEW.dias, '; ',
        'puntaje: ', NEW.puntaje
    );

    INSERT INTO historiales (
        fecha_hora, usuario_responsable, tipo_de_accion, accion
    ) VALUES (
        NOW(), IFNULL(@usuario_actual, 'usuario no definido'), 'Inserción de experiencia', datos
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `experiencias_resumen`
--

CREATE TABLE `experiencias_resumen` (
  `id_experiencias_resumen` int(11) NOT NULL,
  `postulante_experiencia_fk` int(11) NOT NULL,
  `subcategoria_experiencia_fk` int(11) NOT NULL,
  `puntaje_total` decimal(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formularios`
--

CREATE TABLE `formularios` (
  `id_formulario` int(11) NOT NULL,
  `programa_formulario_fk` int(11) NOT NULL,
  `perfil_formulario_fk` int(11) NOT NULL,
  `postulante_formulario_fk` int(11) NOT NULL,
  `usuario_formulario_fk` int(11) NOT NULL,
  `experiencia` decimal(11,2) NOT NULL,
  `experto` int(11) NOT NULL,
  `formacion_laboral` int(11) NOT NULL,
  `educacion` int(11) NOT NULL,
  `competencia` varchar(50) DEFAULT NULL,
  `cumple` tinyint(1) NOT NULL,
  `observaciones` varchar(150) DEFAULT NULL,
  `puntaje_total` float(100,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `formularios`
--
DELIMITER $$
CREATE TRIGGER `actualizar_formularios` AFTER UPDATE ON `formularios` FOR EACH ROW BEGIN
    DECLARE cambios TEXT DEFAULT '';

    IF OLD.programa_formulario_fk <> NEW.programa_formulario_fk THEN
        SET cambios = CONCAT(cambios, 'programa_formulario_fk: ', OLD.programa_formulario_fk, ' -> ', NEW.programa_formulario_fk, '; ');
    END IF;

    IF OLD.perfil_formulario_fk <> NEW.perfil_formulario_fk THEN
        SET cambios = CONCAT(cambios, 'perfil_formulario_fk: ', OLD.perfil_formulario_fk, ' -> ', NEW.perfil_formulario_fk, '; ');
    END IF;

    IF OLD.postulante_formulario_fk <> NEW.postulante_formulario_fk THEN
        SET cambios = CONCAT(cambios, 'postulante_formulario_fk: ', OLD.postulante_formulario_fk, ' -> ', NEW.postulante_formulario_fk, '; ');
    END IF;

    IF OLD.usuario_formulario_fk <> NEW.usuario_formulario_fk THEN
        SET cambios = CONCAT(cambios, 'usuario_formulario_fk: ', OLD.usuario_formulario_fk, ' -> ', NEW.usuario_formulario_fk, '; ');
    END IF;

    IF OLD.experiencia <> NEW.experiencia THEN
        SET cambios = CONCAT(cambios, 'experiencia: ', OLD.experiencia, ' -> ', NEW.experiencia, '; ');
    END IF;

    IF OLD.experto <> NEW.experto THEN
        SET cambios = CONCAT(cambios, 'experto: ', OLD.experto, ' -> ', NEW.experto, '; ');
    END IF;

    IF OLD.formacion_laboral <> NEW.formacion_laboral THEN
        SET cambios = CONCAT(cambios, 'formacion_laboral: ', OLD.formacion_laboral, ' -> ', NEW.formacion_laboral, '; ');
    END IF;

    IF OLD.educacion <> NEW.educacion THEN
        SET cambios = CONCAT(cambios, 'educacion: ', OLD.educacion, ' -> ', NEW.educacion, '; ');
    END IF;

    IF OLD.competencia <> NEW.competencia THEN
        SET cambios = CONCAT(cambios, 'competencia: ', OLD.competencia, ' -> ', NEW.competencia, '; ');
    END IF;

    IF OLD.cumple <> NEW.cumple THEN
        SET cambios = CONCAT(cambios, 'cumple: ', OLD.cumple, ' -> ', NEW.cumple, '; ');
    END IF;

    IF OLD.observaciones <> NEW.observaciones THEN
        SET cambios = CONCAT(cambios, 'observaciones: ', OLD.observaciones, ' -> ', NEW.observaciones, '; ');
    END IF;

    IF OLD.puntaje_total <> NEW.puntaje_total THEN
        SET cambios = CONCAT(cambios, 'puntaje_total: ', OLD.puntaje_total, ' -> ', NEW.puntaje_total, '; ');
    END IF;

    IF cambios <> '' THEN
        INSERT INTO historiales (
            fecha_hora, usuario_responsable, tipo_de_accion, accion
        ) VALUES (
            NOW(), IFNULL(@usuario_actual, 'usuario no definido'), 'Actualización de formulario', cambios
        );
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `eliminar_formularios` AFTER DELETE ON `formularios` FOR EACH ROW BEGIN
    DECLARE datos TEXT;

    SET datos = CONCAT(
        'programa_formulario_fk: ', OLD.programa_formulario_fk, '; ',
        'perfil_formulario_fk: ', OLD.perfil_formulario_fk, '; ',
        'postulante_formulario_fk: ', OLD.postulante_formulario_fk, '; ',
        'usuario_formulario_fk: ', OLD.usuario_formulario_fk, '; ',
        'experiencia: ', OLD.experiencia, '; ',
        'experto: ', OLD.experto, '; ',
        'formacion_laboral: ', OLD.formacion_laboral, '; ',
        'educacion: ', OLD.educacion, '; ',
        'competencia: ', OLD.competencia, '; ',
        'cumple: ', OLD.cumple, '; ',
        'observaciones: ', OLD.observaciones, '; ',
        'puntaje_total: ', OLD.puntaje_total
    );

    INSERT INTO historiales (
        fecha_hora, usuario_responsable, tipo_de_accion, accion
    ) VALUES (
        NOW(), IFNULL(@usuario_actual, 'usuario no definido'), 'Eliminación de formulario', datos
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertar_formularios` AFTER INSERT ON `formularios` FOR EACH ROW BEGIN
    DECLARE datos TEXT;

    SET datos = CONCAT(
        'programa_formulario_fk: ', NEW.programa_formulario_fk, '; ',
        'perfil_formulario_fk: ', NEW.perfil_formulario_fk, '; ',
        'postulante_formulario_fk: ', NEW.postulante_formulario_fk, '; ',
        'usuario_formulario_fk: ', NEW.usuario_formulario_fk, '; ',
        'experiencia: ', NEW.experiencia, '; ',
        'experto: ', NEW.experto, '; ',
        'formacion_laboral: ', NEW.formacion_laboral, '; ',
        'educacion: ', NEW.educacion, '; ',
        'competencia: ', NEW.competencia, '; ',
        'cumple: ', NEW.cumple, '; ',
        'observaciones: ', NEW.observaciones, '; ',
        'puntaje_total: ', NEW.puntaje_total
    );

    INSERT INTO historiales (
        fecha_hora, usuario_responsable, tipo_de_accion, accion
    ) VALUES (
        NOW(), IFNULL(@usuario_actual, 'usuario no definido'), 'Inserción de formulario', datos
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `formulario_detalles`
--

CREATE TABLE `formulario_detalles` (
  `id_detalles` int(11) NOT NULL,
  `formulario_detalle_fk` int(11) NOT NULL,
  `subcategoria_detalle_fk` int(11) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `valor` varchar(30) NOT NULL,
  `puntaje` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historiales`
--

CREATE TABLE `historiales` (
  `id_historial` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT current_timestamp(),
  `usuario_responsable` varchar(100) NOT NULL,
  `tipo_de_accion` text NOT NULL,
  `accion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perfiles`
--

CREATE TABLE `perfiles` (
  `id_perfil` int(11) NOT NULL,
  `nombre_perfil` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `perfiles`
--

INSERT INTO `perfiles` (`id_perfil`, `nombre_perfil`) VALUES
(1, 'Formación para el trabajo'),
(2, 'Educación formal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `postulantes`
--

CREATE TABLE `postulantes` (
  `id_postulante` int(11) NOT NULL,
  `documento` int(11) NOT NULL,
  `nombre_postulante` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `postulantes`
--
DELIMITER $$
CREATE TRIGGER `actualizar_postulante` AFTER UPDATE ON `postulantes` FOR EACH ROW BEGIN
    DECLARE cambios TEXT DEFAULT '';

    IF OLD.documento <> NEW.documento THEN
        SET cambios = CONCAT(cambios, 'documento: ', OLD.documento, ' -> ', NEW.documento, '; ');
    END IF;

    IF OLD.nombre_postulante <> NEW.nombre_postulante THEN
        SET cambios = CONCAT(cambios, 'nombre_postulante: ', OLD.nombre_postulante, ' -> ', NEW.nombre_postulante, '; ');
    END IF;

    INSERT INTO historiales (fecha_hora, usuario_responsable, tipo_de_accion, accion)
    VALUES (NOW(), IFNULL(@usuario_actual, 'usuario no definido'), 'Actualización de postulante', cambios);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `eliminar_postulante` AFTER DELETE ON `postulantes` FOR EACH ROW BEGIN
    DECLARE datos TEXT;

    SET datos = CONCAT(
        'documento: ', OLD.documento, '; ',
        'nombre_postulante: ', OLD.nombre_postulante
    );

    INSERT INTO historiales (fecha_hora, usuario_responsable, tipo_de_accion, accion)
    VALUES (NOW(), IFNULL(@usuario_actual, 'usuario no definido'), 'Eliminación de postulante', datos);
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertar_postulante` AFTER INSERT ON `postulantes` FOR EACH ROW BEGIN
    DECLARE datos TEXT;

    SET datos = CONCAT(
        'documento: ', NEW.documento, '; ',
        'nombre_postulante: ', NEW.nombre_postulante
    );

    INSERT INTO historiales (fecha_hora, usuario_responsable, tipo_de_accion, accion)
    VALUES (NOW(), IFNULL(@usuario_actual, 'usuario no definido'), 'Creación de postulante', datos);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `programas`
--

CREATE TABLE `programas` (
  `id_programa` int(11) NOT NULL,
  `nombre_programa` varchar(100) NOT NULL,
  `fecha_inicial` date NOT NULL,
  `fecha_final` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `programas`
--
DELIMITER $$
CREATE TRIGGER `actualizar_programas` AFTER UPDATE ON `programas` FOR EACH ROW BEGIN
    DECLARE cambios TEXT DEFAULT '';

    IF OLD.nombre_programa <> NEW.nombre_programa THEN
        SET cambios = CONCAT(cambios, 'nombre_programa: ', OLD.nombre_programa, ' -> ', NEW.nombre_programa, '; ');
    END IF;

    IF OLD.fecha_inicial <> NEW.fecha_inicial THEN
        SET cambios = CONCAT(cambios, 'fecha_inicial: ', OLD.fecha_inicial, ' -> ', NEW.fecha_inicial, '; ');
    END IF;

    IF OLD.fecha_final <> NEW.fecha_final THEN
        SET cambios = CONCAT(cambios, 'fecha_final: ', OLD.fecha_final, ' -> ', NEW.fecha_final, '; ');
    END IF;

    IF cambios <> '' THEN
        INSERT INTO historiales (
            fecha_hora,
            usuario_responsable,
            tipo_de_accion,
            accion
        ) VALUES (
            NOW(),
            IFNULL(@usuario_actual, 'usuario no definido'),
            'Actualización de programa',
            cambios
        );
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `eliminar_programa` AFTER DELETE ON `programas` FOR EACH ROW BEGIN
    DECLARE datos TEXT;

    SET datos = CONCAT(
        'nombre_programa: ', OLD.nombre_programa, '; ',
        'fecha_inicial: ', OLD.fecha_inicial, '; ',
        'fecha_final: ', OLD.fecha_final, '; '
    );

    INSERT INTO historiales (
        fecha_hora,
        usuario_responsable,
        tipo_de_accion,
        accion
    ) VALUES (
        NOW(),
        IFNULL(@usuario_actual, 'usuario no definido'),
        'Eliminación de programa',
        datos
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertar_programas` AFTER INSERT ON `programas` FOR EACH ROW BEGIN
    DECLARE datos TEXT;

    SET datos = CONCAT(
        'nombre_programa: ', NEW.nombre_programa, '; ',
        'fecha_inicial: ', NEW.fecha_inicial, '; ',
        'fecha_final: ', NEW.fecha_final, '; '
    );

    INSERT INTO historiales (
        fecha_hora,
        usuario_responsable,
        tipo_de_accion,
        accion
    ) VALUES (
        NOW(),
        IFNULL(@usuario_actual, 'usuario no definido'),
        'Inserción de programa',
        datos
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`) VALUES
(1, 'Administrador'),
(2, 'Usuario Principal'),
(3, 'Usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `subcategorias`
--

CREATE TABLE `subcategorias` (
  `id_subcategoria` int(11) NOT NULL,
  `categoria_subcategoria_fk` int(11) NOT NULL,
  `nombre_subcategoria` varchar(50) NOT NULL,
  `ponderacion_subcategoria_puntos` float(100,2) DEFAULT NULL,
  `ponderacion_subcategoria_porcentaje` int(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `subcategorias`
--

INSERT INTO `subcategorias` (`id_subcategoria`, `categoria_subcategoria_fk`, `nombre_subcategoria`, `ponderacion_subcategoria_puntos`, `ponderacion_subcategoria_porcentaje`) VALUES
(1, 1, 'Técnica', 2.50, 4),
(2, 1, 'Docente', 2.00, 3),
(3, 1, 'Instructor SENA', 3.50, 6),
(4, 2, 'Técnica', 2.50, 5),
(5, 2, 'Docente', 2.00, 4),
(6, 2, 'Instructor SENA', 3.50, 7),
(7, 3, 'Experto - Maestro - Artesano', NULL, 100),
(8, 4, 'Experto - Maestro - Artesano', 0.00, 0),
(9, 5, 'CAP- Técnico Laboral', NULL, 100),
(10, 6, 'CAP- Técnico Laboral', NULL, 100),
(11, 7, 'Educación básica', NULL, 10),
(12, 7, 'Educación media', NULL, 20),
(13, 7, 'Técnica Profesional', NULL, 70),
(14, 7, 'Especialización Técnica Profesional', NULL, 80),
(15, 7, 'Tecnología', NULL, 100),
(16, 7, 'Especialización Tecnológica', NULL, 100),
(17, 7, 'Profesional / Universitario', NULL, 100),
(18, 7, 'Especialización', NULL, 100),
(19, 7, 'Maestría', NULL, 100),
(20, 7, 'Doctorado', NULL, 100),
(21, 8, 'Educación básica', 0.00, 0),
(22, 8, 'Educación media', NULL, 0),
(23, 8, 'Técnica Profesional', NULL, 0),
(24, 8, 'Especialización Técnica Profesional', NULL, 0),
(25, 8, 'Tecnología', NULL, 20),
(26, 8, 'Especialización Tecnológica', NULL, 30),
(27, 8, 'Profesional / Universitario', NULL, 60),
(28, 8, 'Especialización', NULL, 80),
(29, 8, 'Maestría', NULL, 90),
(30, 8, 'Doctorado', NULL, 100);

--
-- Disparadores `subcategorias`
--
DELIMITER $$
CREATE TRIGGER `actualizar_subcategoria` AFTER UPDATE ON `subcategorias` FOR EACH ROW BEGIN
    DECLARE cambios TEXT DEFAULT '';

    IF OLD.nombre_subcategoria <> NEW.nombre_subcategoria THEN
        SET cambios = CONCAT(cambios, 'nombre_subcategoria: ', OLD.nombre_subcategoria, ' -> ', NEW.nombre_subcategoria, '; ');
    END IF;

    IF OLD.categoria_subcategoria_fk <> NEW.categoria_subcategoria_fk THEN
        SET cambios = CONCAT(cambios, 'categoria_fk: ', OLD.categoria_subcategoria_fk, ' -> ', NEW.categoria_subcategoria_fk, '; ');
    END IF;

    IF OLD.ponderacion_subcategoria_puntos <> NEW.ponderacion_subcategoria_puntos THEN
        SET cambios = CONCAT(cambios, 'ponderacion_puntos: ', OLD.ponderacion_subcategoria_puntos, ' -> ', NEW.ponderacion_subcategoria_puntos, '; ');
    END IF;

    IF OLD.ponderacion_subcategoria_porcentaje <> NEW.ponderacion_subcategoria_porcentaje THEN
        SET cambios = CONCAT(cambios, 'ponderacion_porcentaje: ', OLD.ponderacion_subcategoria_porcentaje, ' -> ', NEW.ponderacion_subcategoria_porcentaje, '; ');
    END IF;

    IF cambios <> '' THEN
        INSERT INTO historiales (
            fecha_hora,
            usuario_responsable,
            tipo_de_accion,
            accion
        ) VALUES (
            NOW(),
            IFNULL(@usuario_actual, 'usuario no definido'),
            'Actualización de subcategoría',
            cambios
        );
    END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `eliminar_subcategoria` AFTER DELETE ON `subcategorias` FOR EACH ROW BEGIN
    DECLARE datos TEXT;

    SET datos = CONCAT(
        'nombre_subcategoria: ', OLD.nombre_subcategoria, '; ',
        'categoria_fk: ', OLD.categoria_subcategoria_fk, '; ',
        'ponderacion_puntos: ', OLD.ponderacion_subcategoria_puntos, '; ',
        'ponderacion_porcentaje: ', OLD.ponderacion_subcategoria_porcentaje
    );

    INSERT INTO historiales (
        fecha_hora,
        usuario_responsable,
        tipo_de_accion,
        accion
    ) VALUES (
        NOW(),
        IFNULL(@usuario_actual, 'usuario no definido'),
        'Eliminación de subcategoría',
        datos
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertar_subcategoria` AFTER INSERT ON `subcategorias` FOR EACH ROW BEGIN
    DECLARE datos TEXT;

    SET datos = CONCAT(
        'nombre_subcategoria: ', NEW.nombre_subcategoria, '; ',
        'categoria_fk: ', NEW.categoria_subcategoria_fk, '; ',
        'ponderacion_puntos: ', NEW.ponderacion_subcategoria_puntos, '; ',
        'ponderacion_porcentaje: ', NEW.ponderacion_subcategoria_porcentaje
    );

    INSERT INTO historiales (
        fecha_hora,
        usuario_responsable,
        tipo_de_accion,
        accion
    ) VALUES (
        NOW(),
        IFNULL(@usuario_actual, 'usuario no definido'),
        'Creación de subcategoría',
        datos
    );
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `rol_usuario_fk` int(11) NOT NULL,
  `nombre_usuario` varchar(30) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `activo` tinyint(1) NOT NULL,
  `clave` varchar(200) NOT NULL,
  `documento` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `rol_usuario_fk`, `nombre_usuario`, `correo`, `activo`, `clave`, `documento`) VALUES
(1, 1, 'Admin', 'admin@gmail.com', 1, '$2y$10$fs.pqLdhAq2ZWdN4oIJGE./KcwjCclstIcIEFqjeBAPeXKHSkyEwe', 1),
(2, 2, 'coordinador', 'coordinador@gmail.com', 1, '$2y$10$Zbewe.BtMcU1x./Qv4zam.3OKiCHzlQnG7HNcnoLmrz9Iw959XH7K', 2),
(3, 3, 'usuario', 'usuario@gmail.com', 1, '$2y$10$5YNsR3lZk9yNhOrIbyfzJ.ZHWHkS/b6sZoQEFo3nkZ1J3zrBFcNaS', 3);

--
-- Disparadores `usuarios`
--
DELIMITER $$
CREATE TRIGGER `actualizar_usuario` AFTER UPDATE ON `usuarios` FOR EACH ROW BEGIN
    DECLARE cambios TEXT DEFAULT '';

    IF OLD.nombre_usuario <> NEW.nombre_usuario THEN
        SET cambios = CONCAT(cambios, 'nombre_usuario: ', OLD.nombre_usuario, ' -> ', NEW.nombre_usuario, '; ');
    END IF;

    IF OLD.correo <> NEW.correo THEN
        SET cambios = CONCAT(cambios, 'correo: ', OLD.correo, ' -> ', NEW.correo, '; ');
    END IF;

    IF OLD.documento <> NEW.documento THEN
        SET cambios = CONCAT(cambios, 'documento: ', OLD.documento, ' -> ', NEW.documento, '; ');
    END IF;

    IF OLD.rol_usuario_fk <> NEW.rol_usuario_fk THEN
        SET cambios = CONCAT(cambios, 'rol: ', OLD.rol_usuario_fk, ' -> ', NEW.rol_usuario_fk, '; ');
    END IF;

    IF OLD.activo <> NEW.activo THEN
        SET cambios = CONCAT(cambios, 'activo: ', OLD.activo, ' -> ', NEW.activo, '; ');
    END IF;

    INSERT INTO historiales (
        fecha_hora, 
        usuario_responsable, 
        tipo_de_accion, 
        accion
    )
    VALUES (
        NOW(),
        IFNULL(@usuario_actual, 'usuario no definido'),
        'Actualización de usuario',
        cambios
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `eliminar_usuario` AFTER DELETE ON `usuarios` FOR EACH ROW BEGIN
    DECLARE eliminado TEXT;

    SET eliminado = CONCAT(
        'nombre_usuario: ', OLD.nombre_usuario, '; ',
        'correo: ', OLD.correo, '; ',
        'documento: ', OLD.documento, '; ',
        'rol: ', OLD.rol_usuario_fk, '; ',
        'activo: ', OLD.activo
    );

    INSERT INTO historiales (
        fecha_hora, 
        usuario_responsable, 
        tipo_de_accion, 
        accion
    )
    VALUES (
        NOW(), 
        IFNULL(@usuario_actual, 'usuario no definido'), 
        'Eliminación de usuario',
        eliminado
    );
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `insertar_usuario` AFTER INSERT ON `usuarios` FOR EACH ROW BEGIN
    DECLARE nuevo_usuario TEXT;

    SET nuevo_usuario = CONCAT(
        'nombre_usuario: ', NEW.nombre_usuario, '; ',
        'correo: ', NEW.correo, '; ',
        'documento: ', NEW.documento, '; ',
        'rol: ', NEW.rol_usuario_fk, '; ',
        'activo: ', NEW.activo
    );

    INSERT INTO historiales (
        fecha_hora, 
        usuario_responsable, 
        tipo_de_accion, 
        accion
    )
    VALUES (
        NOW(),
        IFNULL(@usuario_actual, 'usuario no definido'),
        'Creación de usuario',
        nuevo_usuario
    );
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD KEY `perfil_categoria_fk` (`perfil_categoria_fk`);

--
-- Indices de la tabla `experiencias`
--
ALTER TABLE `experiencias`
  ADD PRIMARY KEY (`id_experiencia`),
  ADD KEY `subcategoria_experiencia` (`resumen_experiencias_fk`),
  ADD KEY `subcategoria_experiencia_fk` (`resumen_experiencias_fk`),
  ADD KEY `resumen_experiencias_fk` (`resumen_experiencias_fk`);

--
-- Indices de la tabla `experiencias_resumen`
--
ALTER TABLE `experiencias_resumen`
  ADD PRIMARY KEY (`id_experiencias_resumen`),
  ADD KEY `postulante_experiencia_fk` (`postulante_experiencia_fk`,`subcategoria_experiencia_fk`),
  ADD KEY `subcategoria_experiencia_fk` (`subcategoria_experiencia_fk`);

--
-- Indices de la tabla `formularios`
--
ALTER TABLE `formularios`
  ADD PRIMARY KEY (`id_formulario`),
  ADD KEY `programa_formulario_fk` (`programa_formulario_fk`,`postulante_formulario_fk`),
  ADD KEY `postulante_formulario_fk` (`postulante_formulario_fk`),
  ADD KEY `usuario_formulario_fk` (`usuario_formulario_fk`);

--
-- Indices de la tabla `formulario_detalles`
--
ALTER TABLE `formulario_detalles`
  ADD PRIMARY KEY (`id_detalles`),
  ADD KEY `formulario_detalle_fk` (`formulario_detalle_fk`,`subcategoria_detalle_fk`),
  ADD KEY `subcategoria_detalle_fk` (`subcategoria_detalle_fk`);

--
-- Indices de la tabla `historiales`
--
ALTER TABLE `historiales`
  ADD PRIMARY KEY (`id_historial`);

--
-- Indices de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  ADD PRIMARY KEY (`id_perfil`);

--
-- Indices de la tabla `postulantes`
--
ALTER TABLE `postulantes`
  ADD PRIMARY KEY (`id_postulante`);

--
-- Indices de la tabla `programas`
--
ALTER TABLE `programas`
  ADD PRIMARY KEY (`id_programa`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  ADD PRIMARY KEY (`id_subcategoria`),
  ADD KEY `categoria_subcategoria_fk` (`categoria_subcategoria_fk`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `rol_usuario` (`rol_usuario_fk`),
  ADD KEY `rol_usuario_fk` (`rol_usuario_fk`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `experiencias`
--
ALTER TABLE `experiencias`
  MODIFY `id_experiencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `experiencias_resumen`
--
ALTER TABLE `experiencias_resumen`
  MODIFY `id_experiencias_resumen` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT de la tabla `formularios`
--
ALTER TABLE `formularios`
  MODIFY `id_formulario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `formulario_detalles`
--
ALTER TABLE `formulario_detalles`
  MODIFY `id_detalles` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `historiales`
--
ALTER TABLE `historiales`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `perfiles`
--
ALTER TABLE `perfiles`
  MODIFY `id_perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `postulantes`
--
ALTER TABLE `postulantes`
  MODIFY `id_postulante` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `programas`
--
ALTER TABLE `programas`
  MODIFY `id_programa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  MODIFY `id_subcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD CONSTRAINT `categorias_ibfk_1` FOREIGN KEY (`perfil_categoria_fk`) REFERENCES `perfiles` (`id_perfil`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `experiencias`
--
ALTER TABLE `experiencias`
  ADD CONSTRAINT `experiencias_ibfk_1` FOREIGN KEY (`resumen_experiencias_fk`) REFERENCES `experiencias_resumen` (`id_experiencias_resumen`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `experiencias_resumen`
--
ALTER TABLE `experiencias_resumen`
  ADD CONSTRAINT `experiencias_resumen_ibfk_1` FOREIGN KEY (`postulante_experiencia_fk`) REFERENCES `postulantes` (`id_postulante`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `experiencias_resumen_ibfk_2` FOREIGN KEY (`subcategoria_experiencia_fk`) REFERENCES `subcategorias` (`id_subcategoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `formularios`
--
ALTER TABLE `formularios`
  ADD CONSTRAINT `formularios_ibfk_2` FOREIGN KEY (`programa_formulario_fk`) REFERENCES `programas` (`id_programa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `formularios_ibfk_3` FOREIGN KEY (`postulante_formulario_fk`) REFERENCES `postulantes` (`id_postulante`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `formularios_ibfk_4` FOREIGN KEY (`usuario_formulario_fk`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `formulario_detalles`
--
ALTER TABLE `formulario_detalles`
  ADD CONSTRAINT `formulario_detalles_ibfk_1` FOREIGN KEY (`formulario_detalle_fk`) REFERENCES `formularios` (`id_formulario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `formulario_detalles_ibfk_2` FOREIGN KEY (`subcategoria_detalle_fk`) REFERENCES `subcategorias` (`id_subcategoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `subcategorias`
--
ALTER TABLE `subcategorias`
  ADD CONSTRAINT `subcategorias_ibfk_1` FOREIGN KEY (`categoria_subcategoria_fk`) REFERENCES `categorias` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_usuario_fk`) REFERENCES `roles` (`id_rol`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
