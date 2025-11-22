-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-11-2025 a las 12:59:17
-- Versión del servidor: 10.4.19-MariaDB
-- Versión de PHP: 8.0.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+01:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4*/;

--
-- Base de datos: `xito_restaurante`
--
CREATE DATABASE IF NOT EXISTS `xito_restaurante` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `xito_restaurante`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(100) NOT NULL,
  `tipo_categoria` varchar(100) NOT NULL,
  `modalidad_producto` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nombre_categoria`, `tipo_categoria`, `modalidad_producto`) VALUES
(1, 'Comida', 'Tapa', 'Embutido'),
(2, 'Comida', 'Tapa', 'Variado'),
(3, 'Comida', 'Tapa', 'Carne'),
(4, 'Comida', 'Ración', 'Pescado'),
(5, 'Comida', 'Ración', 'Carne'),
(6, 'Bebida', 'Con alcohol', 'Con alcohol'),
(7, 'Bebida', 'Refresco', 'Refresco'),
(8, 'Postre', 'Dulce', 'Tarta'),
(9, 'Postre', 'Fruta', 'Variado'),
(10, 'Postre', 'Helado', 'Variado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mesas`
--

CREATE TABLE `mesas` (
  `id_mesa` int(11) NOT NULL,
  `disponibilidad_mesa` int(11) NOT NULL CHECK (`disponibilidad_mesa` in (0,1)),
  `capacidad_mesa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `mesas`
--

INSERT INTO `mesas` (`id_mesa`, `disponibilidad_mesa`, `capacidad_mesa`) VALUES
(1, 1, 1),
(2, 1, 4),
(3, 1, 6),
(4, 1, 8),
(5, 1, 10),
(6, 1, 3),
(7, 1, 5),
(8, 1, 7),
(9, 1, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ordenes`
--

CREATE TABLE `ordenes` (
  `id_orden` int(11) NOT NULL,
  `id_reserva` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `metodo_pago` varchar(50) NOT NULL,
  `precio_total` decimal(10,2) NOT NULL,
  `montante_adelantado` decimal(10,2) NOT NULL,
  `stripe_payment_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ordenes`
--

INSERT INTO `ordenes` (`id_orden`, `id_reserva`, `fecha`, `metodo_pago`, `precio_total`, `montante_adelantado`, `stripe_payment_id`) VALUES
(149, 191, '2025-11-21', 'Tarjeta de crédito', '45.60', '4.56', 'pi_3SVwyLC5kWSf4beJ1HN7OKc4');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_producto` int(11) NOT NULL,
  `id_categoria` int(11) DEFAULT NULL,
  `uds_stock` int(11) NOT NULL,
  `nombre_corto` varchar(50) NOT NULL,
  `nombre_largo` varchar(150) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_producto`, `id_categoria`, `uds_stock`, `nombre_corto`, `nombre_largo`, `descripcion`, `precio_unitario`) VALUES
(1, 7, 6, 'Coca-Cola', 'Coca-Cola', 'Refresco con gas', '2.50'),
(2, 7, 10, 'Coca-Cola Zero', 'Coca-Cola Zero', 'Refresco con gas sin azúcar', '2.50'),
(3, 7, 11, 'Coca-Cola Zero Zero', 'Coca-Cola Zero Zero', 'Refresco con gas sin azúcar ni cafeína', '2.50'),
(4, 7, 14, 'Nestea', 'Nestea', 'Refresco sin gas', '2.50'),
(5, 7, 20, 'Fanta de naranja', 'Fanta de naranja', 'Refresco con gas', '2.50'),
(6, 7, 21, 'Fanta de limón', 'Fanta de limón', 'Refresco con gas', '2.50'),
(7, 7, 19, 'Tónica', 'Tónica', 'Refresco con gas', '2.50'),
(8, 6, 18, 'Cerveza de barril', 'Cerveza de barril', 'Cerveza de barril', '2.80'),
(9, 6, 17, 'Cerveza tostada', 'Cerveza tostada', 'Cerveza tostada', '15.00'),
(10, 6, 20, 'Cerveza Cruzcampo', 'Cerveza Cruzcampo', 'Cerveza Cruzcampo', '2.40'),
(11, 6, 20, 'Vino tinto', 'Vino tino', 'Vino tinto', '3.20'),
(12, 6, 19, 'Vino blanco', 'Vino blanco', 'Vino blanco', '3.20'),
(13, 6, 19, 'Vino dulce', 'Vino dulce', 'Vino dulce', '3.20'),
(15, 1, -1, 'Tabla de quesos', 'Tabla de quesos', 'Variado de los mejores quesos nacionales', '13.90'),
(16, 2, 9, 'Patatas bravas', 'Patatas bravas', 'Deliciosas patatas bravas con un toque picante', '6.80'),
(17, 2, 5, 'Patatas con ali oli', 'Patatas con ali oli', 'Deliciosas patatas fritas con salsa ali oli cargada de ajo', '6.80'),
(18, 3, 13, 'Lagrimitas de pollo', 'Lagrimitas de pollo', 'Tapa de lagrimitas de pollo son salsa miel-mostaza', '8.90'),
(19, 3, 16, 'Tapa de torreznos', 'Tapa de torreznos', 'Torreznos de Soria de la máxima calidad', '13.20'),
(20, 4, 27, 'Bacalao dorado', 'Bacalao dorado', 'Bacalao dorado al más puro estilo portugués', '14.50'),
(21, 5, 16, 'Surtido de croquetas', 'Surtido de croquetas', 'Croquetas de bacalao, solomillo y rabo de todo', '16.70'),
(22, 5, 24, 'Torreznos', 'Torreznos', 'Torreznos de Soria de la máxima calidad', '21.90'),
(23, 5, 15, 'Solomillo al ajo tostado', 'Solomillo al ajo tostado', 'Solomillo tierno con salsa al ajo tostado', '18.50'),
(24, 5, 19, 'Solomillo al ajillo', 'Solomillo al ajillo', 'Solomillo tierno con salsa al ajillo', '18.50'),
(26, 5, 14, 'Solomillo a la pimienta', 'Solomillo a la pimienta', 'Solomillo tierno con salsa a la pimienta', '18.50'),
(27, 5, 18, 'Lagarto a la brasa', 'Lagarto a la brasa', 'Carne tierna de lagarto hecha a la brasa', '18.50'),
(28, 4, 19, 'Choco', 'Choco', 'Choco fresco del día', '20.00'),
(29, 4, 16, 'Merluza', 'Merluza', 'Merluza fresca del día', '19.40'),
(30, 4, 18, 'Lubina', 'Lubina', 'Lubina fresca del día', '17.50'),
(31, 8, 9, 'Tarta de chocolate', 'Tarta de chocolate', 'Delicioso bizcocho de chocolate recubierto con más chocolate', '10.00'),
(36, 8, 8, 'Tarta de queso', 'Tarta de queso', 'Deliciosa tarta de queso horneada', '10.00'),
(37, 8, 6, 'Tarta red velvet', 'Tarta red velvet', 'Tarta red velvet', '4.20'),
(38, 9, 4, 'Variado de frutas', 'Variado de frutas', 'Melón, sandía, plátano y uvas', '7.80'),
(40, 10, 48, 'Mochis de chocolate', 'Mochis de chocolate', 'Mochis de chocolate congelados', '5.00'),
(41, 8, 12, 'Tocinito de cielo', 'Tocinito de cielo', 'Tocinito de cielo', '4.20'),
(43, 1, 14, 'Tapa de jamón ibérico', 'Tapa de jamón ibérico', 'Jamón ibérico de la mejor calidad', '8.90');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos_ordenes`
--

CREATE TABLE `productos_ordenes` (
  `id_orden` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad_pedido` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `productos_ordenes`
--

INSERT INTO `productos_ordenes` (`id_orden`, `id_producto`, `cantidad_pedido`) VALUES
(149, 15, 2),
(149, 43, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id_reserva` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `numero_comensales` int(11) NOT NULL,
  `comanda_previa` int(11) NOT NULL CHECK (`comanda_previa` in (0,1))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id_reserva`, `id_usuario`, `numero_comensales`, `comanda_previa`) VALUES
(190, 1, 2, 0),
(191, 1, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas_mesas`
--

CREATE TABLE `reservas_mesas` (
  `id_reserva` int(11) NOT NULL,
  `id_mesa` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` datetime NOT NULL,
  `hora_fin` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `reservas_mesas`
--

INSERT INTO `reservas_mesas` (`id_reserva`, `id_mesa`, `fecha`, `hora_inicio`, `hora_fin`) VALUES
(190, 5, '2025-11-23', '2025-11-23 13:30:00', '2025-11-23 15:00:00'),
(191, 3, '2025-11-21', '2025-11-21 17:16:00', '2025-11-21 18:46:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(100) NOT NULL,
  `descripcion_rol` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`, `descripcion_rol`) VALUES
(1, 'Usuario', 'Usuario online con restricciones de acceso.'),
(2, 'Camarero', 'Trabajador con acceso restringido.'),
(3, 'Administrador', 'Usuario con acceso total al sistema.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `id_rol` int(11) DEFAULT NULL,
  `nombre_usuario` varchar(100) NOT NULL,
  `apellidos_usuario` varchar(150) NOT NULL,
  `password_usuario` varchar(255) NOT NULL,
  `email_usuario` varchar(150) NOT NULL,
  `telefono_usuario` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `id_rol`, `nombre_usuario`, `apellidos_usuario`, `password_usuario`, `email_usuario`, `telefono_usuario`) VALUES
(1, 1, 'Daniel', 'Torrado Jaramago', '$2y$10$0Q.GD98sxcQpThNMgcyWb.lJ2fDUJIn9JYpBg9ycsvkzkN3qsnUyu', 'danidtj@gmail.com', '8888888'),
(2, 1, 'lola', 'lola lola', '$2y$10$KkQQ9b4VsIdLmG0DEO0y3O6H6PFv2BuNGklFavZqIRmXt9p3aWzgK', 'lola@gmail.com', '555555'),
(3, 3, 'admin', 'admin', '$2y$10$vPYkkIr7xy3Uj.ZpmrGCi.sCTr5DKsvxxvu3eTkAwB62ghFtcrjra', 'admin@admin.com', '55555'),
(4, 2, 'camarero1', 'camarero1', '$2y$10$.J5Uv.Jr/BqQeHBaLmmWr.BvqlBX5.7FN0PCI60YoWUukdwu71PDm', 'camarero1@camarero.com', '0000000'),
(5, 1, 'diego', 'torrado sanchez', '$2y$10$5HtkgXSCWpnf7WRYu62GwOW5ySMBQy.48eSvSMNksCeN0Z0PR7hTa', 'dddd@gmail.com', '979797979797'),
(6, 1, 'dddd', 'dddd dddd', '$2y$10$V/VjTEnWHmUYO64UQJI3BeCZ3vh888mspuAh/lO4jfBj1Vt9BN5zm', 'dan@gmail.com', '939393939393'),
(7, 1, 'hhh', 'hhh hhhh', '$2y$10$2V.nenfUk1G85fdQzTPbz.jVs4/vzvJs2/ZxJQhF9BJMwV9AbN7I.', 'hhh@gmail.com', '884848484'),
(11, 1, 'bbbb', 'bbbbb', '$2y$10$3vYtXlOSIv1zLFS18aKfKepzSuC2WbkmXJXH1h1/Q8X.9gfDV3r5y', 'bbbb@gmail.com', '4444444444'),
(39, 1, 'cristian', 'retortillo corrales', '$2y$10$OypvX4y2pIW5L.ywvfQMROPRHAjraJvwJrxes.jFZqXdXLeBhPOji', 'retorjr@gmail.com', '999999888'),
(40, 1, 'xito', 'xito xito', '$2y$10$SW7xsraTz2YK2ChISSv3heEP69IwiyqpFbHyBJC5BDCtrA31oBAiy', 'xitorestaurante@gmail.com', '121212121');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `mesas`
--
ALTER TABLE `mesas`
  ADD PRIMARY KEY (`id_mesa`);

--
-- Indices de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD PRIMARY KEY (`id_orden`),
  ADD KEY `fk_ordenes_reservas` (`id_reserva`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `fk_productos_categorias` (`id_categoria`);

--
-- Indices de la tabla `productos_ordenes`
--
ALTER TABLE `productos_ordenes`
  ADD PRIMARY KEY (`id_orden`,`id_producto`),
  ADD KEY `fk_productosordenes_ordenes` (`id_orden`),
  ADD KEY `fk_productosordenes_productos` (`id_producto`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id_reserva`),
  ADD KEY `fk_reservas_usuarios` (`id_usuario`);

--
-- Indices de la tabla `reservas_mesas`
--
ALTER TABLE `reservas_mesas`
  ADD PRIMARY KEY (`id_reserva`,`id_mesa`),
  ADD KEY `fk_reservasmesas_mesas` (`id_mesa`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email_usuario` (`email_usuario`),
  ADD UNIQUE KEY `telefono_usuario` (`telefono_usuario`),
  ADD KEY `fk_usuarios_roles` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `mesas`
--
ALTER TABLE `mesas`
  MODIFY `id_mesa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `ordenes`
--
ALTER TABLE `ordenes`
  MODIFY `id_orden` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id_reserva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ordenes`
--
ALTER TABLE `ordenes`
  ADD CONSTRAINT `fk_ordenes_reservas` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categorias` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id_categoria`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos_ordenes`
--
ALTER TABLE `productos_ordenes`
  ADD CONSTRAINT `fk_productosordenes_ordenes` FOREIGN KEY (`id_orden`) REFERENCES `ordenes` (`id_orden`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_productosordenes_productos` FOREIGN KEY (`id_producto`) REFERENCES `productos` (`id_producto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `fk_reservas_usuarios` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `reservas_mesas`
--
ALTER TABLE `reservas_mesas`
  ADD CONSTRAINT `fk_reservasmesas_mesas` FOREIGN KEY (`id_mesa`) REFERENCES `mesas` (`id_mesa`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reservasmesas_reservas` FOREIGN KEY (`id_reserva`) REFERENCES `reservas` (`id_reserva`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_roles` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
