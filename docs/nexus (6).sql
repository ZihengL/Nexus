-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mar. 30 jan. 2024 à 21:21
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `nexus`
--

-- --------------------------------------------------------

--
-- Structure de la table `createdgames`
--

CREATE TABLE `createdgames` (
  `userId` int(11) NOT NULL,
  `gameId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `friendID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `friends`
--

INSERT INTO `friends` (`id`, `friendID`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `games`
--

CREATE TABLE `games` (
  `id` int(11) NOT NULL,
  `developperID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `files` varchar(100) NOT NULL,
  `images` set('a','b') NOT NULL,
  `videos` set('pathVideo') NOT NULL,
  `tags` varchar(100) NOT NULL,
  `releaseDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `games`
--

INSERT INTO `games` (`id`, `developperID`, `name`, `description`, `files`, `images`, `videos`, `tags`, `releaseDate`) VALUES
(1, 1, 'Super Game', 'An amazing game', 'game.exe', 'a', '', '', NULL),
(2, 3, 'Fantastic Adventure', 'Embark on an epic journey', 'adventure.exe', 'b', '', '', NULL),
(3, 4, 'Space Odyssey', 'Explore the depths of outer space', 'space.exe', 'a,b', '', '', NULL),
(4, 2, 'Medieval Kingdoms', 'Rule your own kingdom', 'kingdoms.exe', 'a', '', '', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `recipientID` int(11) NOT NULL,
  `senderID` int(11) NOT NULL,
  `message` varchar(500) NOT NULL,
  `timestamp` date NOT NULL,
  `typeMessage` varchar(100) NOT NULL,
  `IsRead` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notification`
--

INSERT INTO `notification` (`id`, `recipientID`, `senderID`, `message`, `timestamp`, `typeMessage`, `IsRead`) VALUES
(1, 2, 3, 'Hello! You have a new notification.', '2024-01-29', 'New Message', 0);

-- --------------------------------------------------------

--
-- Structure de la table `rewiews`
--

CREATE TABLE `rewiews` (
  `id` int(11) NOT NULL,
  `gameID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `timestamp` date NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `rewiews`
--

INSERT INTO `rewiews` (`id`, `gameID`, `userID`, `timestamp`, `rating`, `comment`) VALUES
(1, 1, 2, '2024-01-29', 5, 'This game is awesome!');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `user` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(500) NOT NULL,
  `phoneNumber` bigint(20) NOT NULL,
  `picture` varchar(500) NOT NULL,
  `IsAdmin` tinyint(1) NOT NULL,
  `IsOnline` tinyint(1) NOT NULL,
  `description` varchar(500) NOT NULL,
  `name` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `creationDate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `user`, `password`, `email`, `phoneNumber`, `picture`, `IsAdmin`, `IsOnline`, `description`, `name`, `lastName`, `creationDate`) VALUES
(1, 'john_doe', 'password123', 'john.doe@example.com', 1234567890, 'avatar_path', 0, 0, '', '', '', 0),
(2, 'alice_smith', 'pass123', 'alice.smith@example.com', 987654321, 'avatar_alice.jpg', 0, 0, '', '', '', 0),
(3, 'bob_jones', 'bobpass', 'bob.jones@example.com', 555666777, 'avatar_bob.png', 0, 0, '', '', '', 0),
(4, 'emma_davis', 'password123', 'emma.davis@example.com', 111222333, 'avatar_emma.jpg', 0, 0, '', '', '', 0);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `createdgames`
--
ALTER TABLE `createdgames`
  ADD KEY `userId` (`userId`,`gameId`),
  ADD KEY `gameId` (`gameId`);

--
-- Index pour la table `friends`
--
ALTER TABLE `friends`
  ADD KEY `id` (`id`),
  ADD KEY `friendID` (`friendID`);

--
-- Index pour la table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `developperID` (`developperID`);

--
-- Index pour la table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipientID` (`recipientID`,`senderID`),
  ADD KEY `senderID` (`senderID`);

--
-- Index pour la table `rewiews`
--
ALTER TABLE `rewiews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gameID` (`gameID`,`userID`),
  ADD KEY `userID` (`userID`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `rewiews`
--
ALTER TABLE `rewiews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `createdgames`
--
ALTER TABLE `createdgames`
  ADD CONSTRAINT `createdgames_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `createdgames_ibfk_2` FOREIGN KEY (`gameId`) REFERENCES `games` (`id`);

--
-- Contraintes pour la table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`friendID`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`developperID`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`recipientID`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`senderID`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `rewiews`
--
ALTER TABLE `rewiews`
  ADD CONSTRAINT `rewiews_ibfk_1` FOREIGN KEY (`gameID`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `rewiews_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
