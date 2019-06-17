-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 12, 2019 at 09:39 PM
-- Server version: 10.1.39-MariaDB
-- PHP Version: 7.3.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `rest`
--

-- --------------------------------------------------------

--
-- Table structure for table `alimentos`
--

CREATE TABLE `alimentos` (
  `id_alim` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `nombre_alim` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion_alim` text COLLATE utf8_spanish_ci NOT NULL,
  `u_medida` varchar(20) COLLATE utf8_spanish_ci NOT NULL,
  `tiempo_prep` int(3) NOT NULL,
  `precio_unit` float NOT NULL,
  `id_tipo_cocina` int(10) NOT NULL,
  `tiempo_menu` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `foto_alim` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `existencia` int(3) NOT NULL,
  `id_estab` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `alimentos`
--

INSERT INTO `alimentos` (`id_alim`, `nombre_alim`, `descripcion_alim`, `u_medida`, `tiempo_prep`, `precio_unit`, `id_tipo_cocina`, `tiempo_menu`, `foto_alim`, `existencia`, `id_estab`) VALUES
('CAR76', 'Sirloin fino', 'Corte grueso con especias regionales', 'pieza', 10, 43.87, 4, 'Botana', './assets/img/hamburguesa.jpg', 2, 6),
('CEV86t', 'ceviche de camarones clasico', 'Incluye aguacate, cebolla, camarón rosado, y caracoles', 'orden', 15, 438.87, 4, 'Botana', './assets/img/hamburguesa.jpg', 2, 11),
('HAM02', 'e ', 'e', 'e', 10, 3566, 1, 'Plato fuerte', './assets/img/hamburguesa.jpg', 20, 0),
('HAM12', 'Hamburguesa vegetariana', 'Pizza elaborada únicamente con vegetales selectos de la región.', 'pieza', 20, 256.87, 1, 'Plato fuerte', './assets/img/hamburguesa.jpg', 2, 2),
('PAS435', 'Pasta bolognesa', 'Contiene carne de res molida, un toque de picante, especias de la región de Turquía,  y otras cosas mas.', 'Orden', 90, 300, 3, 'Entremes', './assets/img/hamburguesa.jpg', 4, 0),
('PIZ02', 'Pizza vegetariana', 'Pizza elaborada únicamente con vegetales selectos de la región.', 'pieza', 20, 345.65, 1, 'Plato fuerte', './assets/img/vacio.jpg', 2, 0),
('PIZ56', 'Pizza de camaron', 'Con camaroncitos bien fritos', 'pieza', 10, 43.87, 4, 'Botana', 'https://firebasestorage.googleapis.com/v0/b/ionic-u4.appspot.com/o/hamburguesa.jpg?alt=media&token=1b44534b-eb4f-40a9-bf2f-66c075dc7314', 2, 3),
('TAC12', 'Tacos al pastor', 'Tacos al pastor con queso.', 'pieza', 10, 23.87, 2, 'Plato fuerte', '/assets/imagenes/etc/etc/imagen2.png', 2, 3),
('TOR32', 'Torta milanesa', 'Tacos al pastor con queso.', 'pieza', 10, 43.87, 4, 'Plato fuerte', './assets/img/hamburguesa.jpg', 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `clientes`
--

CREATE TABLE `clientes` (
  `id_cte` int(10) NOT NULL,
  `nombre_cliente` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `primer_apellido_cliente` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `segundo_apellido_cliente` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `telefono_cliente` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `correo_cliente` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password_cliente` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `num_interior_cliente` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `num_exterior_cliente` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `calle_cliente` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cruzamiento1_calle_cliente` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cruzamiento2_calle_cliente` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `colonia_cliente` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `ciudad_cliente` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `ubicacion_gps_cliente` point DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `clientes`
--

INSERT INTO `clientes` (`id_cte`, `nombre_cliente`, `primer_apellido_cliente`, `segundo_apellido_cliente`, `telefono_cliente`, `correo_cliente`, `password_cliente`, `num_interior_cliente`, `num_exterior_cliente`, `calle_cliente`, `cruzamiento1_calle_cliente`, `cruzamiento2_calle_cliente`, `colonia_cliente`, `ciudad_cliente`, `ubicacion_gps_cliente`) VALUES
(1, 'paco', 'mendez', 'perez', '987654321', 'paco@mail', '1234', '2-bis', '56', 'margarita maza', '1 de mayo', '', 'caribe', 'Chetumal', ''),
(2, 'fernando', 'jimenez', 'may', '987654321', 'fer@mail', '1234', '', '56', 'heros', 'Managua', 'crescencio', 'americas', 'Chetumal', ''),
(3, 'gabriela', 'santos', 'may', '984354321', 'fer@mail', '1234', '', '56', 'emiliano zapata', 'bugambilias', 'jardines', 'jardines', 'Chetumal', ''),
(4, 'homero', 'jimeno', 'simpson', '765432', 'homer@mail', '1234', '43-b', '75', 'gregorio zapata', 'felipe angeles', 'centro', 'Centro', 'Chetumal', ''),
(5, 'jorge', 'jimeno', 'simpson', '765432', 'homer@mail', '1234', '43-b', '75', 'gregorio zapata', 'felipe angeles', 'centro', 'Centro', 'Chetumal', '');

-- --------------------------------------------------------

--
-- Table structure for table `detalles_pedido`
--

CREATE TABLE `detalles_pedido` (
  `folio` int(11) NOT NULL,
  `id_alim` varchar(8) COLLATE utf8_spanish_ci NOT NULL,
  `precio_unit_alim` float NOT NULL,
  `cantidad` float NOT NULL,
  `subtotal` float NOT NULL,
  `lugar_entrega` varchar(100) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `detalles_pedido`
--

INSERT INTO `detalles_pedido` (`folio`, `id_alim`, `precio_unit_alim`, `cantidad`, `subtotal`, `lugar_entrega`) VALUES
(105, 'HM02', 45.67, 4, 8765.43, 'donde sea');

-- --------------------------------------------------------

--
-- Table structure for table `empleado`
--

CREATE TABLE `empleado` (
  `id_empleado` int(8) NOT NULL,
  `nombre_emp` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `primer_apellido_emp` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `segundo_apellido_emp` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `puesto` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `id_estab` int(5) NOT NULL,
  `password_emp` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `correo_emp` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `claveApi` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `empleado`
--

INSERT INTO `empleado` (`id_empleado`, `nombre_emp`, `primer_apellido_emp`, `segundo_apellido_emp`, `puesto`, `id_estab`, `password_emp`, `correo_emp`, `claveApi`) VALUES
(15, 'freddy', 'perez', 'mendez', 'cajero', 9, '$2y$10$xn4W1AQUA2b3EGW4kz.sF.PO9e94yIN0Kjmj7cCEj9Mu3Ec9JUUVK', 'freddy@gmail.com', 'da0478a8aac9c1823da49a8ebd286239');

-- --------------------------------------------------------

--
-- Table structure for table `establecimientos`
--

CREATE TABLE `establecimientos` (
  `id_estab` int(5) NOT NULL,
  `nombre_estab` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `num_exterior_estab` varchar(5) COLLATE utf8_spanish_ci NOT NULL,
  `calle_estab` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cruzamiento1_calle_estab` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `cruzamiento2_calle_estab` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `colonia_estab` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `ciudad_estab` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `telefono_estab` varchar(10) COLLATE utf8_spanish_ci NOT NULL,
  `correo_estab` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `horarios` text COLLATE utf8_spanish_ci NOT NULL,
  `descripcion_estab` text COLLATE utf8_spanish_ci NOT NULL,
  `id_tipo_cocina` int(10) NOT NULL,
  `serv_domicilio` tinyint(1) NOT NULL,
  `serv_reserv` tinyint(1) NOT NULL,
  `calificacion` int(3) NOT NULL,
  `id_tipo_rest` int(10) NOT NULL,
  `ubicacion_gps_estab` point DEFAULT NULL,
  `foto_estab` varchar(255) COLLATE utf8_spanish_ci NOT NULL,
  `logo_estab` varchar(255) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `establecimientos`
--

INSERT INTO `establecimientos` (`id_estab`, `nombre_estab`, `num_exterior_estab`, `calle_estab`, `cruzamiento1_calle_estab`, `cruzamiento2_calle_estab`, `colonia_estab`, `ciudad_estab`, `telefono_estab`, `correo_estab`, `horarios`, `descripcion_estab`, `id_tipo_cocina`, `serv_domicilio`, `serv_reserv`, `calificacion`, `id_tipo_rest`, `ubicacion_gps_estab`, `foto_estab`, `logo_estab`) VALUES
(9, 'tacoloco', '34-A', 'Independencia', 'Efrain Aguilar', 'Mahatma Ghandi', 'centro', 'Chetumal', '98765432', 'loco@mail.com', 'todos los dias', 'Un lugar agradable', 1, 1, 1, 50, 1, NULL, 'https://firebasestorage.googleapis.com/v0/b/chatonline-8b1db.appspot.com/o/tacoloco.jpg?alt=media&amp;token=077ef0f3-f8f1-4be7-9504-a5f4aaabf114', 'vacio'),
(10, 'Las delicias', '32-B', 'Heroes', 'independencia', 'juarez', 'Centro', 'Chetumal', '98765432', 'negocio@gmail.com', 'pendiente', 'Un negocio cualquiera', 1, 1, 0, 89, 2, '', '/assets..', '/assets...'),
(11, 'El emporio', '32-B', 'Heroes', 'Heros', 'emiliano zapato', 'Centro', 'Chetumal', '463743', 'negocio@gmail.com', 'pendiente', 'Un negocio cualquiera', 1, 1, 0, 89, 2, '', '/assets..', '/assets...'),
(12, 'Dominos', '32-B', 'Heroes', 'Heros', 'emiliano zapato', 'Centro', 'Chetumal', '463743', 'negocio@gmail.com', 'pendiente', 'Un negocio cualquiera', 1, 1, 0, 89, 2, '', '/assets..', '/assets...'),
(13, 'La hamburguesa loca', '54', 'Heroes', 'Heros', 'emiliano zapato', 'Centro', 'Chetumal', '463743', 'negocio@gmail.com', 'pendiente', 'Un negocio cualquiera', 1, 1, 0, 89, 2, '', '/assets..', '/assets...'),
(14, 'El taco feliz', '54', 'Heroes', 'Heros', 'emiliano zapato', 'Centro', 'Chetumal', '463743', 'negocio@gmail.com', 'pendiente', 'Un negocio bueno', 1, 1, 0, 89, 2, '', '/assets..', '/assets...'),
(15, 'Don de Comer', '54', 'Heroes', 'Heros', 'emiliano zapato', 'Centro', 'Chetumal', '463743', 'negocio@gmail.com', 'pendiente', 'Un negocio bueno', 1, 1, 0, 89, 2, '', '/assets..', '/assets...'),
(16, 'Le gourmet', '54', 'Heroes', 'Heros', 'emiliano zapato', 'Centro', 'Chetumal', '463743', 'negocio@gmail.com', 'pendiente', 'Un negocio bueno', 1, 1, 0, 89, 2, '', '/assets..', '/assets...');

-- --------------------------------------------------------

--
-- Table structure for table `pedidos`
--

CREATE TABLE `pedidos` (
  `folio` int(11) NOT NULL,
  `id_cte` int(10) NOT NULL,
  `id_estab` int(5) NOT NULL,
  `hora_solicitud` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `status_pedido` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `forma_pago` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `total` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `pedidos`
--

INSERT INTO `pedidos` (`folio`, `id_cte`, `id_estab`, `hora_solicitud`, `status_pedido`, `forma_pago`, `total`) VALUES
(105, 2, 9, '23', 'pendiente', 'efectivo', 45),
(106, 1, 9, '43:7', 'pendiente', 'efectivo', 45),
(119, 5, 11, '03:36:56', 'No revisado', 'Efectivo', 0),
(125, 5, 11, '03:41:06', 'No revisado', 'Efectivo', 0);

-- --------------------------------------------------------

--
-- Table structure for table `publicidad`
--

CREATE TABLE `publicidad` (
  `id_pub` int(10) NOT NULL,
  `id_estab` int(5) NOT NULL,
  `nombre_pub` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `imagen_pub` varchar(100) COLLATE utf8_spanish_ci NOT NULL,
  `descripcion_pub` text COLLATE utf8_spanish_ci NOT NULL,
  `productos_pub` varchar(50) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `publicidad`
--

INSERT INTO `publicidad` (`id_pub`, `id_estab`, `nombre_pub`, `imagen_pub`, `descripcion_pub`, `productos_pub`) VALUES
(11, 9, 'Baghettes fin de semana', './assets/img/publicidad1.png', 'Todos los fines de semana en Pizzeria XXX,...', 'pizza mediana, pizza grande'),
(12, 9, 'Chelas gratis', './assets/img/publicidad2.png', 'Todos los fines de semana.', 'pizza mediana, pizza grande'),
(13, 10, 'Promoción de semana santa', './assets/img/publicidad3.jpg', 'Durante todo esta semana santa, en el consumo de un ceviche familiar, llevese otra orden gratis', 'Pendientes');

-- --------------------------------------------------------

--
-- Table structure for table `reservaciones`
--

CREATE TABLE `reservaciones` (
  `id_reservacion` int(10) NOT NULL,
  `id_estab` int(5) NOT NULL,
  `num_mesa` int(2) NOT NULL,
  `cantidad_personas` int(2) NOT NULL,
  `hora_reservacion` datetime NOT NULL,
  `hora_registro` datetime NOT NULL,
  `id_cte` int(10) NOT NULL,
  `status_reservacion` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `reservaciones`
--

INSERT INTO `reservaciones` (`id_reservacion`, `id_estab`, `num_mesa`, `cantidad_personas`, `hora_reservacion`, `hora_registro`, `id_cte`, `status_reservacion`) VALUES
(2, 11, 3, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3, 'pendiente'),
(3, 12, 1, 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 3, 'pendiente'),
(4, 9, 9, 56, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 1, 'pendiente'),
(5, 9, 9, 56, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 4, 'pendiente');

-- --------------------------------------------------------

--
-- Table structure for table `tipo_cocina`
--

CREATE TABLE `tipo_cocina` (
  `id_tipo_cocina` int(10) NOT NULL,
  `nombre_tipo_cocina` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `tipo_cocina`
--

INSERT INTO `tipo_cocina` (`id_tipo_cocina`, `nombre_tipo_cocina`) VALUES
(1, 'Vegetariana'),
(2, 'Arabe'),
(3, 'Yucateca'),
(4, 'Italiana'),
(5, 'Francesa'),
(6, 'Chetumaleña'),
(7, 'Mariscos'),
(8, 'Belizeña'),
(9, 'Rusa'),
(10, 'Esoterica'),
(11, 'Exotica'),
(12, 'Vegana');

-- --------------------------------------------------------

--
-- Table structure for table `tipo_restaurante`
--

CREATE TABLE `tipo_restaurante` (
  `id_tipo_rest` int(10) NOT NULL,
  `nombre_tipo_rest` varchar(30) COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Dumping data for table `tipo_restaurante`
--

INSERT INTO `tipo_restaurante` (`id_tipo_rest`, `nombre_tipo_rest`) VALUES
(1, 'Fonda'),
(2, 'Restaurante Familiar'),
(3, 'Taquería'),
(4, 'Tortería'),
(5, 'Pizzería'),
(6, 'Marisquería'),
(7, 'Bufet'),
(8, 'Restaurante comida rapida'),
(10, 'Puesto ambulante'),
(11, 'Restaurant bar'),
(12, 'Cocina economica');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alimentos`
--
ALTER TABLE `alimentos`
  ADD PRIMARY KEY (`id_alim`);

--
-- Indexes for table `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cte`);

--
-- Indexes for table `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD KEY `fk_folio` (`folio`);

--
-- Indexes for table `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`id_empleado`),
  ADD KEY `fk_id_estab` (`id_estab`);

--
-- Indexes for table `establecimientos`
--
ALTER TABLE `establecimientos`
  ADD PRIMARY KEY (`id_estab`),
  ADD KEY `id_tipo_cocina` (`id_tipo_cocina`),
  ADD KEY `id_tipo_rest` (`id_tipo_rest`);

--
-- Indexes for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`folio`),
  ADD KEY `id_cte` (`id_cte`),
  ADD KEY `id_estab` (`id_estab`);

--
-- Indexes for table `publicidad`
--
ALTER TABLE `publicidad`
  ADD PRIMARY KEY (`id_pub`),
  ADD KEY `id_estab` (`id_estab`);

--
-- Indexes for table `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD PRIMARY KEY (`id_reservacion`),
  ADD KEY `id_id_estab` (`id_estab`),
  ADD KEY `fk_id_cliente` (`id_cte`);

--
-- Indexes for table `tipo_cocina`
--
ALTER TABLE `tipo_cocina`
  ADD PRIMARY KEY (`id_tipo_cocina`);

--
-- Indexes for table `tipo_restaurante`
--
ALTER TABLE `tipo_restaurante`
  ADD PRIMARY KEY (`id_tipo_rest`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cte` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `empleado`
--
ALTER TABLE `empleado`
  MODIFY `id_empleado` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `establecimientos`
--
ALTER TABLE `establecimientos`
  MODIFY `id_estab` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `folio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=126;

--
-- AUTO_INCREMENT for table `publicidad`
--
ALTER TABLE `publicidad`
  MODIFY `id_pub` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `reservaciones`
--
ALTER TABLE `reservaciones`
  MODIFY `id_reservacion` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tipo_cocina`
--
ALTER TABLE `tipo_cocina`
  MODIFY `id_tipo_cocina` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `tipo_restaurante`
--
ALTER TABLE `tipo_restaurante`
  MODIFY `id_tipo_rest` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detalles_pedido`
--
ALTER TABLE `detalles_pedido`
  ADD CONSTRAINT `fk_folio` FOREIGN KEY (`folio`) REFERENCES `pedidos` (`folio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `fk_id_estab` FOREIGN KEY (`id_estab`) REFERENCES `establecimientos` (`id_estab`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `establecimientos`
--
ALTER TABLE `establecimientos`
  ADD CONSTRAINT `establecimientos_ibfk_1` FOREIGN KEY (`id_tipo_cocina`) REFERENCES `tipo_cocina` (`id_tipo_cocina`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `establecimientos_ibfk_2` FOREIGN KEY (`id_tipo_rest`) REFERENCES `tipo_restaurante` (`id_tipo_rest`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`id_cte`) REFERENCES `clientes` (`id_cte`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pedidos_ibfk_2` FOREIGN KEY (`id_estab`) REFERENCES `establecimientos` (`id_estab`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `publicidad`
--
ALTER TABLE `publicidad`
  ADD CONSTRAINT `publicidad_ibfk_1` FOREIGN KEY (`id_estab`) REFERENCES `establecimientos` (`id_estab`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD CONSTRAINT `fk_id_cliente` FOREIGN KEY (`id_cte`) REFERENCES `clientes` (`id_cte`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `id_id_estab` FOREIGN KEY (`id_estab`) REFERENCES `establecimientos` (`id_estab`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
