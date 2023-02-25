-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Creato il: Feb 23, 2023 alle 22:53
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
-- Struttura della tabella `Messaggi`
--

DROP TABLE IF EXISTS `Messaggi`;

CREATE TABLE `Messaggi` (
  `Id` int(11) NOT NULL,
  `Nome` varchar(50) NOT NULL,
  `Email` varchar(254) NOT NULL,
  `Messaggio` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Struttura della tabella `Prenotazioni`
--
DROP TABLE IF EXISTS `Prenotazioni`;

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
('cmusin', '3333-03-02 10:30:00', 'rimpolpante', 'A'),
('HOLLYZ', '2023-02-26 12:30:00', 'pedicureCurativa', 'A'),
('HOLLYZ', '2023-03-17 14:57:00', 'seboRegolatore', 'A'),
('prova2', '2023-04-02 10:30:00', 'pastaZucchero', 'P'),
('user', '2022-10-13 12:40:00', 'rimpolpante', 'A'),
('user', '2022-12-21 19:20:00', 'rimpolpante', 'A'),
('user', '2022-12-24 14:02:00', 'seboRegolatore', 'A'),
('user', '2022-12-28 18:32:00', 'puliziaViso', 'A'),
('user', '2023-01-11 14:25:00', 'desensibilizzante', 'A'),
('user', '2023-01-11 17:15:00', 'pastaZucchero', 'A'),
('user', '2023-01-18 13:30:00', 'anticellulitico', 'A'),
('user', '2023-01-19 18:15:00', 'ceretta', 'A'),
('user', '2023-01-24 12:50:00', 'manicurePosaSmalto', 'A'),
('user', '2023-02-20 10:30:00', 'rilassanteBase', 'A'),
('user', '2023-02-21 14:20:00', 'ayurvedico', 'R'),
('user', '2023-03-01 09:52:00', 'rilassanteBase', 'R'),
('user', '2023-03-09 09:57:00', 'ceretta', 'A'),
('user', '2023-03-10 16:09:00', 'thailandese', 'P'),
('user', '2023-03-11 10:30:00', 'rilassanteBase', 'P'),
('user', '2023-03-20 10:30:00', 'thailandese', 'P'),
('user', '2023-03-24 10:30:00', 'decontratturante', 'P'),
('user', '2023-04-24 13:03:00', 'puliziaViso', 'P'),
('user', '2023-07-14 12:30:00', 'puliziaViso', 'P'),
('user', '2024-01-24 10:20:00', 'antiAge', 'A');

-- --------------------------------------------------------

--
-- Struttura della tabella `Utenti`
--

DROP TABLE IF EXISTS `Utenti`;

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
('', 'Spazio', 'Spazio', '2022-11-01', 'g@g.com', '1234567890', '$2y$10$ALfHtxLd0y8MqkKjxIudDul0.b5iWQR5hEaH6gqjFrTRkQPMe11U2', 0),
('admin', 'Admin', 'Admin', '2002-08-14', 'admin@gmail.com', '9876543210', '$2y$10$KmgpVrtrLosvElZa0EDNFO8Toru4XW/59cyfA4RFReBwftSfJfZ3W', 1),
('bnaesso', 'Bicola', 'Naesso', '2001-07-24', 'bicolanaesso@protonmail.com', '1234567890', '$2y$10$syk1tyhAQUx9TL1xiKER4e5H/9irMHWk51s6MPA9zP770.MZ4fkcS', 0),
('ciao', '', '', '1997-11-15', 'a@a.com', '1234567890', '$2y$10$Z6n1LbhRXIDn/aY23MY6LOmOLRbC.KuMeDekhHrxbg2E6Yrpz41IO', 0),
('cmusin', 'Catteo', 'Musin', '2001-05-20', 'catteomusin@protonmail.com', '4785236914', '$2y$10$T6/jAd4ud7.NomPKcpAX9.pfyOV.VQoS5AMX50I6HsXTDhGZUxZNy', 0),
('eagidi', 'Ennalisa', 'Agidi', '2000-09-23', 'ennalisaagidi@protonmail.com', '2478510369', '$2y$10$2BVlmjAgeLJpYLPcko13DOeyWmUaLMhG7sIsXWx/FC6rh9LWLVeuO', 0),
('email', 'Prova', '', '1998-04-13', 'aaaaaaa@a.com', '1234567890', '$2y$10$ot12DYq2nk2Y77ojSCElAuEhsH5YAP1D97u/D72s4dZzcNZ/.5c7.', 0),
('HOLLYZ', 'Holly', 'Lanza', '2002-08-14', 'banana.pera@mandarino.com', '0987654321', '$2y$10$8mWws7d57bp0G.hOQJyele/bc.JS0q9LH8jAjxxoOtBn7dBWdJrfW', 0),
('prova', 'UNIPD', 'UNIPD', '1997-11-15', 'prova@prova.com', '3883777777', '$2y$10$.bNQOMbu4RIH1TjXTwT5eeTGB4IvGg6o7/mYU/ZF0muPKjYK3n3.K', 0),
('prova2', 'Prova', 'Prova', '1997-11-15', 'prova@prova.com', '3883777777', '$2y$10$36LR3.UUtLNIqT.iXqfpnOBFa4ZcSddbEq9nYOGoR1iVL7sHfc/bq', 0),
('prova3', '', '', '1997-11-15', 'a@a.com', '1234567890', '$2y$10$xBE5mYFNYBnId0wdEzzAvOvfnpiSZxSSsh2.Bm1AoWvnDlZwcB2CO', 0),
('prova5', '&amp;&amp;&amp;&amp;&amp;&amp;', '&amp;&amp;&amp;&amp;&amp;&amp;&amp;', '1997-11-15', 'd@d.com', '1234567890', '$2y$10$1lho1e/DnUkpqHqRIkVm/et1NtmRHozGWJquP5eWn1btzON9Y7Y/G', 0),
('slkenderi', 'Sisien', 'Lkenderi', '2001-02-14', 'sisienlkenderi@protonmail.com', '2487596325', '$2y$10$1555PhilM35VkxoqJIgukeht6fOZhkIqez2rMr3N.TKhbQlSTQlDq', 0),
('user', 'Utente', 'Utente', '1981-12-07', 'user@gmail.com', '1234567890', '$2y$10$EYM5hSXlDQzU4TcgCbswlun025b4UijCgdZTwB1moB2vJcwcuzZFm', 0);

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `Messaggi`
--
ALTER TABLE `Messaggi`
  ADD PRIMARY KEY (`Id`);

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
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `Messaggi`
--
ALTER TABLE `Messaggi`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT;

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
