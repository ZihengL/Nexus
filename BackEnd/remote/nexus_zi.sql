-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 29, 2024 at 12:58 PM
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
  `developerID` int(11) NOT NULL,
  `stripeID` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `files` varchar(100) NOT NULL,
  `media` varchar(50) NOT NULL,
  `videos` set('pathVideo') NOT NULL,
  `releaseDate` date DEFAULT NULL,
  `ratingAverage` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `developerID`, `stripeID`, `title`, `description`, `files`, `media`, `videos`, `releaseDate`, `ratingAverage`) VALUES
(1, 3, 0, 'Fantastic Adventure', 'Embark on an epic journey', 'adventure.exe', 'b', '', '2024-01-26', 3.5),
(2, 4, 0, 'Space Odyssey', 'Explore the depths of outer space', 'space.exe', 'a,b', '', '2024-02-06', 4),
(3, 2, 0, 'Medieval Kingdoms', 'Rule your own kingdom', 'kingdoms.exe', 'a', '', '2024-01-17', 4.25),
(4, 4, 0, 'Super Cat', 'Flying kitty', 'kitty.exe', '', '', '2024-01-14', 2.5),
(5, 6, 0, 'restaurant', 'cooking', '', '', '', '2024-02-24', 0),
(6, 6, 76, 'doggo fights', 'bonk', '', '', '', '2024-02-24', 1);

-- --------------------------------------------------------

--
-- Table structure for table `gamestags`
--

CREATE TABLE `gamestags` (
  `id` int(11) NOT NULL,
  `gameId` int(11) NOT NULL,
  `tagId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gamestags`
--

INSERT INTO `gamestags` (`id`, `gameId`, `tagId`) VALUES
(1, 2, 4),
(2, 4, 1),
(3, 1, 5),
(9, 5, 11);

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
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `gameID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `timestamp` date NOT NULL,
  `rating` float NOT NULL,
  `comment` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `gameID`, `userID`, `timestamp`, `rating`, `comment`) VALUES
(15, 4, 1, '2024-02-22', 5, 'This is a review comment'),
(17, 4, 2, '2024-02-22', 2.5, 'This is a review comment update'),
(24, 10, 1, '2024-02-25', 1, 'This is a review comment');

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
(4, 'singleplayer'),
(11, 'chocolate');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `sub` int(11) NOT NULL COMMENT 'user_id',
  `exp` int(11) NOT NULL COMMENT 'expiration_time',
  `sha` varchar(256) DEFAULT NULL COMMENT 'hashed token'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `sub`, `exp`, `sha`) VALUES
(15, 10, 1709234951, '3fe754d4f6f63b08c89d7c070dabca7d41936eb5b9c0fb8f13e122fa6c41eeb8');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `drive_id` varchar(50) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(500) NOT NULL,
  `phoneNumber` bigint(20) DEFAULT NULL,
  `picture` varchar(500) DEFAULT NULL,
  `isAdmin` tinyint(1) DEFAULT NULL,
  `isOnline` tinyint(1) DEFAULT NULL,
  `description` varchar(500) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `lastName` varchar(50) DEFAULT NULL,
  `creationDate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `drive_id`, `username`, `password`, `email`, `phoneNumber`, `picture`, `isAdmin`, `isOnline`, `description`, `name`, `lastName`, `creationDate`) VALUES
(1, '', 'john_doe', '$2y$10$nmakzte3PwS/95V/K2cqUO/EN8nxutO6BYnXVWqBXB63gcvAYdbne', 'john.doe@example.com', 1234567890, 'cvatar_path', 0, 0, '', '', '', 0),
(2, '', 'alice_smith', '$2y$10$bD0KtNARFKLJ3e51sxTqv..X91IkIbkASHxccYPWcUhNezNRcjJx6', 'alice.smith@example.com', 987654321, 'avatar_alice.jpg', 0, 0, '', '', '', 0),
(3, '', 'bob_jones', '$2y$10$QroH2ylQrU3aqx7zeLMS2eUhuPAgT1byd89aeyv5zpv2vo4B/Vf5C', 'bob.jones@example.com', 555666777, 'avatar_bob.png', 0, 0, '', '', '', 0),
(4, '', 'emma_davis', '$2y$10$roDD0M7SrdDJFKwSYhI4qePO1XkHs7C4h5Sn/pEjD6si9RD/OfTqe', 'emma.davis@example.com', 111222333, 'avatar_emma.jpg', 0, 0, '', '', '', 0),
(5, '', 'john_doe', '$2y$10$nZV2jn6b1ExK9MHQOZmKY.hjmFnmWanw2XRu5p8.uaj0K5Wie0zzO', 'john.doe@example.com', 2234567890, 'bvatar_path ', 0, 0, '0', '0', '0', 0),
(6, '', 'john_doe', '$2y$10$xpynWrLMrU.CS/gkl.HszuvgpMtDxQ4QuiFxEIuB4Kxug99zI5gDy', 'addaasda', 33344555, 'avatar_path ', 0, 0, '', '', '', 0),
(7, 'taxes', 'tostitos', '$2y$10$Sj0mvySDyMjwOdGIO96hmO4DMybe9bxktLb.C/m1rJRjagjRan43.', 'b', NULL, 'avatar_path ', NULL, NULL, NULL, NULL, NULL, 2024);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `games`
--
ALTER TABLE `games`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_developerID` (`developerID`);

--
-- Indexes for table `gamestags`
--
ALTER TABLE `gamestags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_sub` (`sub`) USING BTREE,
  ADD KEY `idx_exp` (`exp`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `gamestags`
--
ALTER TABLE `gamestags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `developed_by` FOREIGN KEY (`developerID`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
