-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-04-2024 a las 01:17:10
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `u983270445_prueba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admin_categorias`
--

CREATE TABLE `admin_categorias` (
  `id_categoria` int(11) NOT NULL,
  `id_depa` int(11) NOT NULL,
  `nombre_categoria` varchar(255) NOT NULL,
  `estatus_categoria` varchar(255) NOT NULL,
  `fecha_created` varchar(255) NOT NULL,
  `id_created` int(11) NOT NULL,
  `fecha_updated` varchar(255) NOT NULL,
  `id_updated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `admin_categorias`
--

INSERT INTO `admin_categorias` (`id_categoria`, `id_depa`, `nombre_categoria`, `estatus_categoria`, `fecha_created`, `id_created`, `fecha_updated`, `id_updated`) VALUES
(1, 1, 'Le\'Mussa', '1', '2023-05-06 11:26:29', 0, '', 0),
(2, 1, 'Productos para Uñas', '1', '2023-05-06 11:26:44', 0, '', 0),
(3, 1, 'Productos para Cejas', '1', '2023-05-06 11:26:55', 0, '', 0),
(4, 1, 'Productos para Pestañas', '1', '2023-05-06 11:27:09', 0, '', 0),
(5, 1, 'Productos para el Cabello', '1', '2023-05-06 11:27:20', 0, '', 0),
(6, 1, 'Brochas y Relacionados', '1', '2023-05-06 11:27:30', 0, '', 0),
(7, 1, 'Cosmetiqueras', '1', '2023-05-06 11:27:39', 0, '', 0),
(8, 1, 'Masajeadores corporales', '1', '2023-05-06 11:27:52', 0, '', 0),
(9, 1, 'Rasuradoras', '1', '2023-05-06 11:28:09', 0, '', 0),
(10, 1, 'Le\'Mussa Home', '1', '2023-05-06 11:28:32', 0, '', 0),
(11, 1, 'Otros', '1', '2023-05-06 11:28:35', 0, '', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin_categorias`
--
ALTER TABLE `admin_categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin_categorias`
--
ALTER TABLE `admin_categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
