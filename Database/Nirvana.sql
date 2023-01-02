-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Gen 02, 2023 alle 18:02
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

-- --------------------------------------------------------

--
-- Struttura della tabella `Prenotazioni`
--

CREATE TABLE `Prenotazioni` (
  `Utente` varchar(30) NOT NULL,
  `DataOra` datetime NOT NULL,
  `Trattamento` varchar(250) NOT NULL,
  `Stato` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Prenotazioni`
--

INSERT INTO `Prenotazioni` (`Utente`, `DataOra`, `Trattamento`, `Stato`) VALUES
('user', '2022-10-13 12:40:00', 'rimpolpante', 'A'),
('user', '2022-12-21 19:20:00', 'rimpolpante', 'A'),
('user', '2022-12-24 14:02:00', 'seboRegolatore', 'A'),
('user', '2022-12-28 18:32:00', 'puliziaViso', 'A'),
('user', '2023-01-11 14:25:00', 'desensibilizzante', 'A'),
('user', '2023-01-11 17:15:00', 'pastaZucchero', 'A'),
('user', '2023-01-18 13:30:00', 'anticellulitico', 'A'),
('user', '2023-01-19 18:15:00', 'ceretta', 'A'),
('user', '2023-01-24 12:50:00', 'manicurePosaSmalto', 'P');

-- --------------------------------------------------------

--
-- Struttura della tabella `Utenti`
--

CREATE TABLE `Utenti` (
  `Username` varchar(30) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Cognome` varchar(50) NOT NULL,
  `DataNascita` date NOT NULL,
  `Email` varchar(254) NOT NULL,
  `Telefono` char(10) NOT NULL,
  `Password` binary(64) NOT NULL,
  `Privilegi` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dump dei dati per la tabella `Utenti`
--

INSERT INTO `Utenti` (`Username`, `Nome`, `Cognome`, `DataNascita`, `Email`, `Telefono`, `Password`, `Privilegi`) VALUES
('user', 'Utente', 'Utente', '1981-07-12', 'user@gmail.com', '1234567890', 0xee11cbb19052e40b07aac0ca060c23ee000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000, 0);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Prenotazioni`
--
ALTER TABLE `Prenotazioni`
  ADD PRIMARY KEY (`Utente`,`DataOra`);

--
-- Indici per le tabelle `Utenti`
--
ALTER TABLE `Utenti`
  ADD PRIMARY KEY (`Username`);

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `Prenotazioni`
--
ALTER TABLE `Prenotazioni`
  ADD CONSTRAINT `Prenotazioni_ibfk_1` FOREIGN KEY (`Utente`) REFERENCES `Utenti` (`Username`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
