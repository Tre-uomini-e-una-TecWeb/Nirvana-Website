-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Gen 31, 2023 alle 23:13
-- Versione del server: 10.4.27-MariaDB
-- Versione PHP: 8.2.0

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
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `Prenotazioni`
--

INSERT INTO `Prenotazioni` (`Utente`, `DataOra`, `Trattamento`, `Stato`) VALUES
('bnaesso', '2023-01-27 16:01:00', 'chakra', 'A'),
('bnaesso', '2023-02-16 14:56:00', 'puliziaViso', 'A'),
('HOLLYZ', '2023-02-26 12:30:00', 'pedicureCurativa', 'P'),
('HOLLYZ', '2023-03-17 14:57:00', 'seboRegolatore', 'A'),
('user', '2022-10-13 12:40:00', 'rimpolpante', 'A'),
('user', '2022-12-21 19:20:00', 'rimpolpante', 'A'),
('user', '2022-12-24 14:02:00', 'seboRegolatore', 'A'),
('user', '2022-12-28 18:32:00', 'puliziaViso', 'A'),
('user', '2023-01-11 14:25:00', 'desensibilizzante', 'A'),
('user', '2023-01-11 17:15:00', 'pastaZucchero', 'A'),
('user', '2023-01-18 13:30:00', 'anticellulitico', 'A'),
('user', '2023-01-19 18:15:00', 'ceretta', 'A'),
('user', '2023-01-24 12:50:00', 'manicurePosaSmalto', 'A'),
('user', '2023-02-16 10:31:00', 'chakra', 'A'),
('user', '2023-02-21 14:20:00', 'ayurvedico', 'R'),
('user', '2023-03-01 09:52:00', 'rilassanteBase', 'P'),
('user', '2023-03-09 09:57:00', 'ceretta', 'A'),
('user', '2023-03-11 10:30:00', 'rilassanteBase', 'P'),
('user', '2023-04-24 13:03:00', 'puliziaViso', 'P'),
('user', '2024-01-24 10:20:00', 'antiAge', 'A');

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
  `Password` varchar(255) NOT NULL,
  `Privilegi` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dump dei dati per la tabella `Utenti`
--

INSERT INTO `Utenti` (`Username`, `Nome`, `Cognome`, `DataNascita`, `Email`, `Telefono`, `Password`, `Privilegi`) VALUES
('admin', 'Admin', 'Admin', '2002-08-14', 'admin@gmail.com', '9876543210', '$2y$10$bRVUMGzG06/hJ2wkutn/S.qrE8naLsu6o3dyFhFbYmWeRzm85eY/W', 1),
('bnaesso', 'Bicola', 'Naesso', '2001-07-24', 'bicolanaesso@protonmail.com', '1234567890', '$2y$10$syk1tyhAQUx9TL1xiKER4e5H/9irMHWk51s6MPA9zP770.MZ4fkcS', 0),
('cmusin', 'Catteo', 'Musin', '2001-05-20', 'catteomusin@protonmail.com', '4785236914', '$2y$10$T6/jAd4ud7.NomPKcpAX9.pfyOV.VQoS5AMX50I6HsXTDhGZUxZNy', 0),
('eagidi', 'Ennalisa', 'Agidi', '2000-09-23', 'ennalisaagidi@protonmail.com', '2478510369', '$2y$10$2BVlmjAgeLJpYLPcko13DOeyWmUaLMhG7sIsXWx/FC6rh9LWLVeuO', 0),
('HOLLYZ', 'Holly', 'Lanza', '2002-08-14', 'banana.pera@mandarino.com', '0987654321', '$2y$10$8mWws7d57bp0G.hOQJyele/bc.JS0q9LH8jAjxxoOtBn7dBWdJrfW', 0),
('slkenderi', 'Sisien', 'Lkenderi', '2001-02-14', 'sisienlkenderi@protonmail.com', '2487596325', '$2y$10$1555PhilM35VkxoqJIgukeht6fOZhkIqez2rMr3N.TKhbQlSTQlDq', 0),
('user', 'Utente', 'Utente', '1981-07-12', 'user@gmail.com', '1234567890', '$2y$10$dKDd2pMBtmzMl4KsNqZ9HuLezOOMzzyEs6LNH6Gskx0AJZijZl7DW', 0);

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
