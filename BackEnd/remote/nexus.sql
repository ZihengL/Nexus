-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 19, 2024 at 07:06 AM
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
  `developerID` int(11) NOT NULL,
  `stripeID` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `files` varchar(100) NOT NULL,
  `media` varchar(50) NOT NULL,
  `videos` set('pathVideo') NOT NULL,
  `tags` varchar(100) NOT NULL,
  `releaseDate` date DEFAULT NULL,
  `ratingAverage` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `developerID`, `stripeID`, `title`, `description`, `files`, `media`, `videos`, `tags`, `releaseDate`, `ratingAverage`) VALUES
(1, 1, 0, 'Super Game', 'An amazing game', 'game.exe', 'a', '', '', '2024-02-01', 2),
(2, 3, 0, 'Fantastic Adventure', 'Embark on an epic journey', 'adventure.exe', 'b', '', '', '2024-01-26', 5),
(3, 4, 0, 'Space Odyssey', 'Explore the depths of outer space', 'space.exe', 'a,b', '', '', '2024-02-06', 3),
(4, 2, 0, 'Medieval Kingdoms', 'Rule your own kingdom', 'kingdoms.exe', 'a', '', '', '2024-01-17', 8),
(8, 4, 0, 'Super Cat', 'Flying kitty', 'kitty.exe', '', '', '', '2024-01-14', 7);

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
  `exp` int(11) NOT NULL COMMENT 'expiry_time',
  `rev` int(11) NOT NULL DEFAULT current_timestamp() COMMENT 'revocation_time'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `revoked_tokens`
--

INSERT INTO `revoked_tokens` (`id`, `sub`, `exp`, `rev`) VALUES
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwNzQ5NDgyNywiZXhwIjoxNzA3NTgxMjI3LCJzdWIiOjF9.c_OXHd2XawVLF27Vh3qh-S454ckFDaCek9F7zF-gxW8', 1, 8385959, 8385959),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwNzQ5NDkzNiwiZXhwIjoxNzA3NTgxMzM2LCJzdWIiOjF9.__j98QV-ypyjIFFABojO91ZxvxqrA-MiWOQorZ5dz4E', 1, 8385959, 8385959),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwNzQzNDYyOCwiZXhwIjoxNzA3NTIxMDI4LCJzdWIiOjF9.s2fsWD1rFYfTvHf9TR-pUKvkvi5uB5rKGeHT1HXgyWA', 1, 8385959, 8385959),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODA0OTQzOSwiZXhwIjoxNzA4MTM1ODM5LCJzdWIiOjF9.q4WbnaZb33--UADcZnnnUzY8gPy3qBghNWn0wY9P_y4', 1, 1708135839, 1708049439),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODMyMTcwNywiZXhwIjoxNzA4MzI1MzA3LCJzdWIiOjF9.N99tYrA9dxe-3xHyh8Q9xLwW2ZUPAI82l-1nUBlyiDw', 1, 1708325307, 1708321707),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODMyMTcwNywiZXhwIjoxNzA4NDA4MTA3LCJzdWIiOjF9.EaEYi7OqQFeWzWjBVtG3ssOwFKN-5G4CrOxxePH7N-w', 1, 1708408107, 1708321707),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODMyMTczNSwiZXhwIjoxNzA4MzI1MzM1LCJzdWIiOjF9.XCZpQatqALug8KnVI11vK43vD4FdoQBwAmlVdQhZxhI', 1, 1708325335, 1708321735),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODMyMTczNSwiZXhwIjoxNzA4NDA4MTM1LCJzdWIiOjF9.zPqbdv1h88TuKZeyiTJUAbr9y-EciHYhMBLP1bCGKcs', 1, 1708408135, 1708321735),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODMyMTg1MCwiZXhwIjoxNzA4MzI1NDUwLCJzdWIiOjF9.F50FD7MS0PtZyS8pq8LGB9V8FgD8zLMcSSLwBPNhsfg', 1, 1708325450, 1708321850),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODMyMTg1MCwiZXhwIjoxNzA4NDA4MjUwLCJzdWIiOjF9.Ui7080qtOEpiGdJVHp3TUU-Zxyqe7prlFF4rjVybYfk', 1, 1708408250, 1708321850),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODMyMTkxMiwiZXhwIjoxNzA4MzI1NTEyLCJzdWIiOjF9.Te74WpqwC08juAOZGcpnNc2uEi6mUDrVVlEAEIFpZAc', 1, 1708325512, 1708321912),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODMyMTkxMiwiZXhwIjoxNzA4NDA4MzEyLCJzdWIiOjF9.qeZD2nmNfi_yEvAWVg-VY7yX3kE-cse7qe0sdOIzKDA', 1, 1708408312, 1708321912),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODMyMTM0MiwiZXhwIjoxNzA4MzI0OTQyLCJzdWIiOjF9.j3lK_EEVx9e_PgBNCrM5tI8KsCdvVtblg0qcPwTqYkw', 1, 1708324942, 1708321342),
('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjQyMDgvTmV4dXMvQmFja0VuZCIsImF1ZCI6InRlbXBvcmFyeS1hdWRpZW5jZSIsImlhdCI6MTcwODMyMTM0MiwiZXhwIjoxNzA4NDA3NzQyLCJzdWIiOjF9.EZdWcuEb0rDAHe89Ub64naBOZ_hysTeg1NglIMh1hD0', 1, 1708407742, 1708321342);

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
(1, 'john_doe', '$2y$10$nmakzte3PwS/95V/K2cqUO/EN8nxutO6BYnXVWqBXB63gcvAYdbne', 'john.doe@example.com', 1234567890, 'cvatar_path', 0, 0, '', '', '', 0),
(2, 'alice_smith', '$2y$10$bD0KtNARFKLJ3e51sxTqv..X91IkIbkASHxccYPWcUhNezNRcjJx6', 'alice.smith@example.com', 987654321, 'avatar_alice.jpg', 0, 0, '', '', '', 0),
(3, 'bob_jones', '$2y$10$QroH2ylQrU3aqx7zeLMS2eUhuPAgT1byd89aeyv5zpv2vo4B/Vf5C', 'bob.jones@example.com', 555666777, 'avatar_bob.png', 0, 0, '', '', '', 0),
(4, 'emma_davis', '$2y$10$roDD0M7SrdDJFKwSYhI4qePO1XkHs7C4h5Sn/pEjD6si9RD/OfTqe', 'emma.davis@example.com', 111222333, 'avatar_emma.jpg', 0, 0, '', '', '', 0),
(5, 'john_doe', '$2y$10$nZV2jn6b1ExK9MHQOZmKY.hjmFnmWanw2XRu5p8.uaj0K5Wie0zzO', 'john.doe@example.com 	', 2234567890, 'bvatar_path ', 0, 0, '0', '0', '0', 0),
(6, 'john_doe', '$2y$10$xpynWrLMrU.CS/gkl.HszuvgpMtDxQ4QuiFxEIuB4Kxug99zI5gDy', 'addaasda', 33344555, 'avatar_path ', 0, 0, '', '', '', 0);

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
  ADD UNIQUE KEY `idx_developerID` (`id`,`developerID`),
  ADD KEY `idx_stripeID` (`stripeID`);

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
  ADD CONSTRAINT `games_ibfk_1` FOREIGN KEY (`developerID`) REFERENCES `user` (`id`);

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
