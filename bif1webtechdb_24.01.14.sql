-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Jan 2024 um 15:48
-- Server-Version: 10.4.28-MariaDB
-- PHP-Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `bif1webtechdb`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `buchungen`
--

CREATE TABLE `buchungen` (
  `bnr` bigint(20) NOT NULL,
  `anreise` date NOT NULL,
  `abreise` date NOT NULL,
  `fruehstueck` tinyint(4) NOT NULL DEFAULT 0,
  `parkplatz` tinyint(4) NOT NULL DEFAULT 0,
  `haustiere` tinyint(4) NOT NULL DEFAULT 0,
  `fk_userid` bigint(20) NOT NULL,
  `fk_znr` bigint(20) NOT NULL,
  `timestamp` datetime DEFAULT NULL,
  `rstatus` varchar(256) DEFAULT NULL,
  `gesamtpreis` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `news`
--

CREATE TABLE `news` (
  `id` bigint(20) NOT NULL,
  `path` varchar(256) DEFAULT NULL,
  `text` varchar(280) NOT NULL,
  `timestamp` bigint(20) DEFAULT NULL,
  `title` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `news`
--

INSERT INTO `news` (`id`, `path`, `text`, `timestamp`, `title`) VALUES
(37, 'uploads\\news\\thumbnail_659713b6ea430_1.jpg', 'Willkommen zum neuen Hotel!', 1704399798, 'Willkommen!'),
(38, 'uploads\\news\\thumbnail_65971505e0695_2.png', 'Neu renovierte Doppel- und Einzelzimmer', 1704400133, 'Coole Zimmer!'),
(41, 'uploads\\news\\thumbnail_659d25e474cdc_fdb43e6fac1c51d183d4696349686d74.jpg', 'Ausruhen beim neuen Garten! Ab 09.01.2024', 1704797668, 'Neuer Garten'),
(42, 'uploads\\news\\thumbnail_659d50171abd9_pexels-samira-mva-18126162.jpg', 'Ab 2024 bieten wir unser Hotel auch als Hochzeits-location an! Bei Anfrage bitte Kontakt per Email', 1704808471, 'Hochzeits-location');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(260) NOT NULL,
  `useremail` varchar(100) NOT NULL,
  `vorname` varchar(256) NOT NULL,
  `nachname` varchar(256) NOT NULL,
  `anrede` varchar(256) NOT NULL,
  `status` text NOT NULL DEFAULT 'aktiv',
  `typ` text NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `useremail`, `vorname`, `nachname`, `anrede`, `status`, `typ`) VALUES
(1, 'user', '$2y$10$.KL.J2N.neD/6m4db95E0ON7TSRkKikxPpHx5yJkG/YFg01upDCM6', 'user@user', 'user', 'user', 'Fr', 'aktiv', 'user'),
(19, 'felix', '$2y$10$2.KpHmT3bQzcYiayfJDEz.cs63/0wKZV8y7.tRag2hPIVE3iPEZdq', 'felixschober1998@gmail.com', 'Felix', 'Schober', 'Hr', 'aktiv', 'admin'),
(20, 'admin', '$2y$10$l80WGzyHEcYersAH.tIQmO43WvNkeMHGmQrsUely8Nt2JtuguFxvK', 'admin@gmail.com', '', '', '', 'aktiv', 'admin'),
(21, 'andi', '$2y$10$Tu0Hd4f9Pb6ePzEaY4bzTuMXSLj13LH5VJknBG0BcO/7UQPPQmI1K', 'asfd@gmail.com', 'Andrei', 'Brate', '', 'aktiv', 'admin');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `zimmern`
--

CREATE TABLE `zimmern` (
  `zimmernr` bigint(20) NOT NULL,
  `zimmertyp` varchar(15) NOT NULL,
  `frei` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Daten für Tabelle `zimmern`
--

INSERT INTO `zimmern` (`zimmernr`, `zimmertyp`, `frei`) VALUES
(1, 'Einzelzimmer', 1),
(2, 'Einzelzimmer', 1),
(3, 'Einzelzimmer', 1),
(4, 'Doppelzimmer', 1),
(5, 'Doppelzimmer', 1),
(6, 'Doppelzimmer', 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `buchungen`
--
ALTER TABLE `buchungen`
  ADD PRIMARY KEY (`bnr`),
  ADD KEY `FK_buchungen_zimmern` (`fk_znr`),
  ADD KEY `FK_buchungen_users` (`fk_userid`);

--
-- Indizes für die Tabelle `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `zimmern`
--
ALTER TABLE `zimmern`
  ADD PRIMARY KEY (`zimmernr`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `buchungen`
--
ALTER TABLE `buchungen`
  MODIFY `bnr` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT für Tabelle `news`
--
ALTER TABLE `news`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT für Tabelle `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT für Tabelle `zimmern`
--
ALTER TABLE `zimmern`
  MODIFY `zimmernr` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `buchungen`
--
ALTER TABLE `buchungen`
  ADD CONSTRAINT `FK_buchungen_users` FOREIGN KEY (`fk_userid`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_buchungen_zimmern` FOREIGN KEY (`fk_znr`) REFERENCES `zimmern` (`zimmernr`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
