-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-04-2024 a las 01:17:02
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
-- Estructura de tabla para la tabla `admin_subcategorias`
--

CREATE TABLE `admin_subcategorias` (
  `id_subcategoria` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `nombre_subcategoria` varchar(255) NOT NULL,
  `estatus_subcategoria` varchar(255) NOT NULL,
  `fecha_created` varchar(255) NOT NULL,
  `id_created` int(11) NOT NULL,
  `fecha_updated` varchar(255) NOT NULL,
  `id_updated` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `admin_subcategorias`
--

INSERT INTO `admin_subcategorias` (`id_subcategoria`, `id_categoria`, `nombre_subcategoria`, `estatus_subcategoria`, `fecha_created`, `id_created`, `fecha_updated`, `id_updated`) VALUES
(1, 1, 'Gamas Le\'Mussa', '1', '2023-05-06 11:29:47', 0, '', 0),
(2, 1, 'Productos para Le\'Mussa', '1', '2023-05-06 11:30:12', 0, '', 0),
(3, 1, ' Polygel Le\'Mussa', '1', '2023-05-06 11:42:51', 0, '', 0),
(4, 1, 'Gel painting Le\'Mussa', '1', '2023-05-06 11:55:47', 0, '', 0),
(5, 2, 'Productos para uñas', '1', '2023-05-06 11:56:18', 0, '', 0),
(6, 2, 'Decoración para uñas', '1', '2023-05-06 11:56:33', 0, '', 0),
(7, 2, 'Efectos', '1', '2023-05-06 11:56:56', 0, '', 0),
(8, 2, 'Lamparas para uñas', '1', '2023-05-06 11:57:22', 0, '', 0),
(9, 2, 'Limas', '1', '2023-05-06 11:57:32', 0, '', 0),
(10, 2, 'Pulidores', '1', '2023-05-06 12:00:16', 0, '', 0),
(11, 2, 'Puntas para pulidores', '1', '2023-05-06 12:00:28', 0, '', 0),
(12, 2, 'Tipos de uñas', '1', '2023-05-06 12:01:07', 0, '', 0),
(13, 2, 'Extractores de polvos', '1', '2023-05-06 12:01:28', 0, '', 0),
(14, 2, 'Utencilios', '1', '2023-05-06 12:01:37', 0, '', 0),
(15, 3, 'Productos para cejas', '1', '2023-05-06 12:06:10', 0, '', 0),
(16, 3, 'Microblading', '1', '2023-05-06 12:06:42', 0, '', 0),
(17, 4, 'Pestañas', '1', '2023-05-06 12:08:29', 0, '', 0),
(18, 4, 'Pegamentos', '1', '2023-05-06 12:08:44', 0, '', 0),
(19, 4, 'Removedores', '1', '2023-05-06 12:08:54', 0, '', 0),
(20, 4, 'Utencilios y Herramientas', '1', '2023-05-06 12:13:45', 0, '', 0),
(21, 4, 'Rizados permanentes', '1', '2023-05-06 12:14:52', 0, '', 0),
(22, 5, 'Cepillo Alaciador', '1', '2023-05-06 12:15:49', 0, '', 0),
(23, 5, 'Planchas', '1', '2023-05-06 12:16:13', 0, '', 0),
(24, 5, 'Rizadoras', '1', '2023-05-06 12:16:29', 0, '', 0),
(25, 5, 'Ferros', '1', '2023-05-06 12:16:50', 0, '', 0),
(26, 5, 'Secadores', '1', '2023-05-06 12:17:08', 0, '', 0),
(27, 6, 'Brochas', '1', '2023-05-06 12:18:32', 0, '', 0),
(28, 6, 'Utensilios', '1', '2023-05-06 12:18:44', 0, '', 0),
(29, 7, 'Cosmetiqueras', '1', '2023-05-06 12:20:07', 0, '', 0),
(30, 8, 'Masajeadores corporales', '1', '2023-05-06 12:22:51', 0, '', 0),
(31, 9, 'Rasuradoras', '1', '2023-05-06 12:23:26', 0, '', 0),
(32, 9, 'Depiladores', '1', '2023-05-06 12:23:42', 0, '', 0),
(33, 10, 'Productos para el hogar', '1', '2023-05-06 12:25:32', 0, '', 0),
(34, 11, 'Organizadores', '1', '2023-05-06 12:26:02', 0, '', 0),
(35, 11, 'Eléctricos', '1', '2023-05-06 12:26:16', 0, '', 0),
(36, 11, 'Lámparas de escritorio', '1', '2023-05-06 12:26:38', 0, '', 0),
(37, 11, 'Espejos', '1', '2023-05-06 12:26:59', 0, '', 0),
(38, 11, 'Productos corporales', '1', '2023-05-06 12:27:18', 0, '', 0),
(39, 11, 'Fotografía', '1', '2023-05-06 12:27:29', 0, '', 0);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admin_subcategorias`
--
ALTER TABLE `admin_subcategorias`
  ADD PRIMARY KEY (`id_subcategoria`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admin_subcategorias`
--
ALTER TABLE `admin_subcategorias`
  MODIFY `id_subcategoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
