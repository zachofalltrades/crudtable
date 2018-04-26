-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 29, 2015 at 01:36 AM
-- Server version: 5.6.11
-- PHP Version: 5.5.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `monkey`
--
CREATE DATABASE IF NOT EXISTS `animals` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `animals`;

-- --------------------------------------------------------

--
-- Table structure for table `animals`
--

CREATE TABLE IF NOT EXISTS `animals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `genus` varchar(50) NOT NULL,
  `species` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `apes`
--

INSERT INTO `animals` (`id`, `name`, `genus`, `species`) VALUES
(1, 'gorilla', 'Gorilla', 'gorilla'),
(2, 'modern humans', 'Homo', 'sapiens'),
(3, 'cave man', 'Homo', 'erectus'),
(4, 'common chimpanzee', 'Pan', 'troglodytes'),
(5, 'bonobo chimpanzee', 'Pan', 'paniscus'),
(6, 'Sumatran orangutan', 'Pongo', 'abelii'),
(7, 'Bornean orangutan', 'Pongo', 'pygmaeus');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
