-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 26-09-2019 a las 01:12:30
-- Versión del servidor: 10.4.6-MariaDB
-- Versión de PHP: 7.3.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de datos: `blog`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `twitter`
--

CREATE TABLE `twitter` (
  `id` int(11) NOT NULL AUTO_INCREMENT=20,
  `user_id` int(11) NOT NULL,
  `twitter_id` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `twitter`
--

INSERT INTO `twitter` (`id`, `user_id`, `twitter_id`) VALUES
(1, 3, '1176902935764250625'),
(2, 12, '1175453384800952320'),
(3, 12, '1176538479624445952');

-- Indices de la tabla `twitter`
--
ALTER TABLE `twitter`
  ADD PRIMARY KEY (`id`);