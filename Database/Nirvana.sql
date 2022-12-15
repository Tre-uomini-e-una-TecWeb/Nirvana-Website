-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Dic 15, 2022 alle 21:42
-- Versione del server: 10.4.25-MariaDB
-- Versione PHP: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Nirvana`
--
CREATE DATABASE IF NOT EXISTS `Nirvana` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `Nirvana`;

-- --------------------------------------------------------

--
-- Struttura della tabella `Prenotazioni`
--

DROP TABLE IF EXISTS `Prenotazioni`;
CREATE TABLE IF NOT EXISTS `Prenotazioni` (
  `Utente` varchar(30) NOT NULL,
  `DataOra` datetime NOT NULL,
  `Trattamento` varchar(250) NOT NULL,
  `Stato` char(1) NOT NULL,
  PRIMARY KEY (`Utente`,`DataOra`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `Utenti`
--

DROP TABLE IF EXISTS `Utenti`;
CREATE TABLE IF NOT EXISTS `Utenti` (
  `Username` varchar(30) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `DataNascita` date NOT NULL,
  `Email` varchar(254) NOT NULL,
  `Telefono` char(10) NOT NULL,
  `Password` binary(64) NOT NULL,
  `Privilegi` tinyint(1) NOT NULL,
  PRIMARY KEY (`Username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Utenti`
--

INSERT INTO `Utenti` (`Username`, `Nome`, `Cognome`, `DataNascita`, `Email`, `Telefono`, `Password`, `Privilegi`) VALUES
('user', 'Utente', 'Utente', '1981-07-12', 'user@gmail.com', '1234567890', 0xee11cbb19052e40b07aac0ca060c23ee000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000, 0);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
