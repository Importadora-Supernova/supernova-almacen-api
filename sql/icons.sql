-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 07-04-2024 a las 01:17:34
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
-- Estructura de tabla para la tabla `icons`
--

CREATE TABLE `icons` (
  `id` int(11) NOT NULL,
  `nombre_icon` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `icons`
--

INSERT INTO `icons` (`id`, `nombre_icon`) VALUES
(1, 'mdi-account'),
(2, 'mdi-account-alert'),
(3, 'mdi-account-box'),
(4, 'mdi-account-cancel'),
(5, 'mdi-account-cash'),
(6, 'mdi-account-check'),
(7, 'mdi-account-lock'),
(8, 'mdi-alarm'),
(9, 'mdi-airplane'),
(10, 'mdi-alarm-panel'),
(11, 'mdi-alert'),
(12, 'mdi-alert-circle'),
(13, 'mdi-ambulance'),
(14, 'mdi-android'),
(15, 'mdi-apple'),
(16, 'mdi-apple-icloud'),
(17, 'mdi-apps'),
(18, 'mdi-archive'),
(19, 'mdi-attachment'),
(20, 'mdi-atv'),
(21, 'mdi-auto-fix'),
(22, 'mdi-bag-suitcase'),
(23, 'mdi-bank'),
(24, 'mdi-barcode'),
(25, 'mdi-basket'),
(26, 'mdi-bed'),
(27, 'mdi-bell'),
(28, 'mdi-bell-cancel'),
(29, 'mdi-bell-ring'),
(30, 'mdi-bike'),
(31, 'mdi-book'),
(32, 'mdi-book-open-page-variant'),
(33, 'mdi-bookshelf'),
(34, 'mdi-boom-gate-up'),
(35, 'mdi-border-color'),
(36, 'mdi-briefcase'),
(37, 'mdi-briefcase-edit'),
(38, 'mdi-briefcase-eye'),
(39, 'mdi-bus'),
(40, 'mdi-button-cursor'),
(41, 'mdi-cached'),
(42, 'mdi-cake-variant'),
(43, 'mdi-calculator'),
(44, 'mdi-calculator-variant'),
(45, 'mdi-calendar'),
(46, 'mdi-calendar-clock'),
(47, 'mdi-calendar-edit'),
(48, 'mdi-calendar-month'),
(49, 'mdi-calendar-remove'),
(50, 'mdi-camera'),
(51, 'mdi-camera-iris'),
(52, 'mdi-cancel'),
(53, 'mdi-car'),
(54, 'mdi-car-clock'),
(55, 'mdi-car-cog'),
(56, 'mdi-car-info'),
(57, 'mdi-car-off'),
(58, 'mdi-car-key'),
(59, 'mdi-card-account-details'),
(60, 'mdi-cart'),
(61, 'mdi-cart-arrow-up'),
(62, 'mdi-cart-arrow-right'),
(63, 'mdi-cash'),
(64, 'mdi-cash-lock'),
(65, 'mdi-cash-check'),
(66, 'mdi-cash-clock'),
(67, 'mdi-cash-register'),
(68, 'mdi-cash-remove'),
(69, 'mdi-cellphone-arrow-down'),
(70, 'mdi-cellphone'),
(71, 'mdi-chart-areaspline'),
(72, 'mdi-chart-bar'),
(73, 'mdi-chart-bell-curve'),
(74, 'mdi-chart-donut'),
(75, 'mdi-chart-line-variant'),
(76, 'mdi-chart-pie'),
(77, 'mdi-chat'),
(78, 'mdi-chat-plus'),
(79, 'mdi-chat-processing'),
(80, 'mdi-check-bold'),
(81, 'mdi-check-circle'),
(82, 'mdi-chip'),
(83, 'mdi-city'),
(84, 'mdi-clipboard'),
(85, 'mdi-clipboard-edit'),
(86, 'mdi-clipboard-clock'),
(87, 'mdi-clipboard-file'),
(88, 'mdi-clipboard-search'),
(89, 'mdi-clock'),
(90, 'mdi-clock-edit'),
(91, 'mdi-clock-check'),
(92, 'mdi-clock-remove'),
(93, 'mdi-close'),
(94, 'mdi-close-circle'),
(95, 'mdi-cloud'),
(96, 'mdi-cloud-check'),
(97, 'mdi-cloud-download'),
(98, 'mdi-cog'),
(99, 'mdi-cogs'),
(100, 'mdi-comment'),
(101, 'mdi-comment-account'),
(102, 'mdi-comment-check'),
(103, 'mdi-comment-eye'),
(104, 'mdi-comment-edit'),
(105, 'mdi-content-cut'),
(106, 'mdi-content-save'),
(107, 'mdi-content-paste'),
(108, 'mdi-credit-card'),
(109, 'mdi-credit-card-check'),
(110, 'mdi-credit-card-clock'),
(111, 'mdi-credit-card-edit'),
(112, 'mdi-credit-card-lock'),
(113, 'mdi-crop-free'),
(114, 'mdi-cube'),
(115, 'mdi-cube-off'),
(116, 'mdi-cube-outline'),
(117, 'mdi-cube-scan'),
(118, 'mdi-currency-btc'),
(119, 'mdi-currency-brl'),
(120, 'mdi-currency-cny'),
(121, 'mdi-currency-eth'),
(122, 'mdi-currency-eur'),
(123, 'mdi-currency-fra'),
(124, 'mdi-currency-gbp'),
(125, 'mdi-currency-ils'),
(126, 'mdi-currency-inr'),
(127, 'mdi-currency-jpy'),
(128, 'mdi-currency-krw'),
(129, 'mdi-currency-kzt'),
(130, 'mdi-currency-mnt'),
(131, 'mdi-currency-ngn'),
(132, 'mdi-currency-php'),
(133, 'mdi-currency-rial'),
(134, 'mdi-currency-rub'),
(135, 'mdi-currency-rupee'),
(136, 'mdi-currency-try'),
(137, 'mdi-currency-twd'),
(138, 'mdi-currency-uah'),
(139, 'mdi-currency-usd'),
(140, 'mdi-data-matrix'),
(141, 'mdi-data-matrix-edit'),
(142, 'mdi-data-matrix-remove'),
(143, 'mdi-data-matrix-scan'),
(144, 'mdi-database'),
(145, 'mdi-database-arrow-down'),
(146, 'mdi-database-check'),
(147, 'mdi-database-cog'),
(148, 'mdi-database-eye'),
(149, 'mdi-database-search'),
(150, 'mdi-delete'),
(151, 'mdi-delete-circle'),
(152, 'mdi-delete-empty'),
(153, 'mdi-dev-to'),
(154, 'mdi-dns'),
(155, 'mdi-docker'),
(156, 'mdi-dolly'),
(157, 'mdi-domain'),
(158, 'mdi-dots-circle'),
(159, 'mdi-dots-grid'),
(160, 'mdi-dots-horizontal'),
(161, 'mdi-dots-horizontal-circle'),
(162, 'mdi-dots-vertical'),
(163, 'mdi-download'),
(164, 'mdi-download-lock'),
(165, 'mdi-draw'),
(166, 'mdi-dresser'),
(167, 'mdi-dropbox'),
(168, 'mdi-dump-truck'),
(169, 'mdi-earth'),
(170, 'mdi-email'),
(171, 'mdi-email-fast'),
(172, 'mdi-email-newsletter'),
(173, 'mdi-eraser'),
(174, 'mdi-exclamation-thick'),
(175, 'mdi-export'),
(176, 'mdi-export-variant'),
(177, 'mdi-eye'),
(178, 'mdi-eye-check'),
(179, 'mdi-eye-off'),
(180, 'mdi-eye-settings'),
(181, 'mdi-face-agent'),
(182, 'mdi-face-recognition'),
(183, 'mdi-factory'),
(184, 'mdi-feature-search'),
(185, 'mdi-ferry'),
(186, 'mdi-file');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `icons`
--
ALTER TABLE `icons`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `icons`
--
ALTER TABLE `icons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
