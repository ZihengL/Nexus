-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 12, 2024 at 04:46 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nexus`
--

-- --------------------------------------------------------

--
-- Table structure for table `createdgames`
--

CREATE TABLE `createdgames` (
  `userId` int(11) NOT NULL,
  `gameId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `friends`
--

CREATE TABLE `friends` (
  `id` int(11) NOT NULL,
  `friendID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `friends`
--

INSERT INTO `friends` (`id`, `friendID`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `games`
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
  `releaseDate` date DEFAULT NULL,
  `ratingAverage` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `developperID`, `name`, `description`, `files`, `images`, `videos`, `tags`, `releaseDate`, `ratingAverage`) VALUES
(1, 1, 'Super Game', 'An amazing game', 'game.exe', 'a', '', '', '2024-02-01', 2),
(2, 3, 'Fantastic Adventure', 'Embark on an epic journey', 'adventure.exe', 'b', '', '', '2024-01-26', 5),
(3, 4, 'Space Odyssey', 'Explore the depths of outer space', 'space.exe', 'a,b', '', '', '2024-02-06', 3),
(4, 2, 'Medieval Kingdoms', 'Rule your own kingdom', 'kingdoms.exe', 'a', '', '', '2024-01-17', 8),
(8, 4, 'Super Cat', 'Flying kitty', 'kitty.exe', '', '', '', '2024-01-14', 7);

-- --------------------------------------------------------

--
-- Table structure for table `gamestags`
--

CREATE TABLE `gamestags` (
  `gameId` int(11) NOT NULL,
  `tagId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gamestags`
--

INSERT INTO `gamestags` (`gameId`, `tagId`) VALUES
(1, 3),
(2, 1),
(4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
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
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `recipientID`, `senderID`, `message`, `timestamp`, `typeMessage`, `IsRead`) VALUES
(1, 2, 3, 'Hello! You have a new notification.', '2024-01-29', 'New Message', 0);

-- --------------------------------------------------------

--
-- Table structure for table `revoked_tokens`
--

CREATE TABLE `revoked_tokens` (
  `id` varchar(255) NOT NULL,
  `sub` int(11) NOT NULL COMMENT 'user_id',
  `exp` time NOT NULL COMMENT 'expiry_time',
  `rev` time NOT NULL DEFAULT current_timestamp() COMMENT 'revocation_time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revoked_tokens`
--

INSERT INTO `revoked_tokens` (`id`, `sub`, `exp`, `rev`) VALUES
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwNzQ5NDgyNywiZXhwIjoxNzA3NTgxMjI3LCJzdWIiOjF9.c_OXHd2XawVLF27Vh3qh-S454ckFDaCek9F7zF-gxW8', 1, '838:59:59', '838:59:59'),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwNzQ5NDkzNiwiZXhwIjoxNzA3NTgxMzM2LCJzdWIiOjF9.__j98QV-ypyjIFFABojO91ZxvxqrA-MiWOQorZ5dz4E', 1, '838:59:59', '838:59:59'),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwNzQzNDYyOCwiZXhwIjoxNzA3NTIxMDI4LCJzdWIiOjF9.s2fsWD1rFYfTvHf9TR-pUKvkvi5uB5rKGeHT1HXgyWA', 1, '838:59:59', '838:59:59');

-- --------------------------------------------------------

--
-- Table structure for table `rewiews`
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
-- Dumping data for table `rewiews`
--

INSERT INTO `rewiews` (`id`, `gameID`, `userID`, `timestamp`, `rating`, `comment`) VALUES
(1, 1, 2, '2024-01-29', 5, 'This game is awesome!');

-- --------------------------------------------------------

--
-- Table structure for table `tags`
--

CREATE TABLE `tags` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tags`
--

INSERT INTO `tags` (`id`, `name`) VALUES
(1, 'action'),
(2, 'wholesome'),
(3, 'rpg'),
(4, 'singleplayer');

-- --------------------------------------------------------

--
-- Table structure for table `user`
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
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `user`, `password`, `email`, `phoneNumber`, `picture`, `IsAdmin`, `IsOnline`, `description`, `name`, `lastName`, `creationDate`) VALUES
(1, 'john_doe', 'password123', 'john.doe@example.com', 1234567890, 'cvatar_path', 0, 0, '', '', '', 0),
(2, 'alice_smith', 'pass123', 'alice.smith@example.com', 987654321, 'avatar_alice.jpg', 0, 0, '', '', '', 0),
(3, 'bob_jones', 'bobpass', 'bob.jones@example.com', 555666777, 'avatar_bob.png', 0, 0, '', '', '', 0),
(4, 'emma_davis', 'password123', 'emma.davis@example.com', 111222333, 'avatar_emma.jpg', 0, 0, '', '', '', 0),
(5, 'john_doe', 'password123 	', 'john.doe@example.com 	', 2234567890, 'bvatar_path ', 0, 0, '0', '0', '0', 0),
(6, 'john_doe', 'mlem', 'addaasda', 33344555, 'avatar_path ', 0, 0, '', '', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `createdgames`
--
ALTER TABLE `createdgames`
  ADD KEY `userId` (`userId`,`gameId`),
  ADD KEY `gameId` (`gameId`);

--
-- Indexes for table `friends`
--
ALTER TABLE `friends`
  ADD KEY `id` (`id`),
  ADD KEY `friendID` (`friendID`);

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `developperID` (`developperID`);

--
-- Indexes for table `gamestags`
--
ALTER TABLE `gamestags`
  ADD PRIMARY KEY (`gameId`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `recipientID` (`recipientID`,`senderID`),
  ADD KEY `senderID` (`senderID`);

--
-- Indexes for table `revoked_tokens`
--
ALTER TABLE `revoked_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sub` (`sub`) USING BTREE,
  ADD KEY `idx_exp` (`exp`) USING BTREE;

--
-- Indexes for table `rewiews`
--
ALTER TABLE `rewiews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `gameID` (`gameID`,`userID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `rewiews`
--
ALTER TABLE `rewiews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `createdgames`
--
ALTER TABLE `createdgames`
  ADD CONSTRAINT `createdgames_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `createdgames_ibfk_2` FOREIGN KEY (`gameId`) REFERENCES `games` (`id`);

--
-- Constraints for table `friends`
--
ALTER TABLE `friends`
  ADD CONSTRAINT `friends_ibfk_1` FOREIGN KEY (`friendID`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `friends_ibfk_2` FOREIGN KEY (`id`) REFERENCES `user` (`id`);

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`developperID`) REFERENCES `user` (`id`);

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`recipientID`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `notification_ibfk_2` FOREIGN KEY (`senderID`) REFERENCES `user` (`id`);

--
-- Constraints for table `rewiews`
--
ALTER TABLE `rewiews`
  ADD CONSTRAINT `rewiews_ibfk_1` FOREIGN KEY (`gameID`) REFERENCES `games` (`id`),
  ADD CONSTRAINT `rewiews_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
