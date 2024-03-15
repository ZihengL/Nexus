-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 15, 2024 at 07:39 AM
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
  `stripeID` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(1000) NOT NULL DEFAULT 'N/A',
  `files` varchar(100) NOT NULL,
  `media` varchar(50) NOT NULL,
  `releaseDate` date NOT NULL DEFAULT current_timestamp(),
  `ratingAverage` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`id`, `developerID`, `stripeID`, `title`, `description`, `files`, `media`, `releaseDate`, `ratingAverage`) VALUES
(1, 3, 0, 'Fantastic Adventure', 'Embark on an epic journey', 'adventure.exe', 'b', '2024-01-26', 2.5),
(2, 4, 0, 'Space Odyssey', 'Explore the depths of outer space', 'space.exe', 'a,b', '2024-02-06', 4),
(3, 2, 0, 'Medieval Kingdoms', 'Rule your own kingdom', 'kingdoms.exe', 'a', '2024-01-17', 4.25),
(4, 4, 0, 'Super Cat', 'Flying kitty', 'kitty.exe', '', '2024-01-14', 2.5),
(5, 6, 0, 'restaurant', 'cooking', '', '', '2024-02-24', 0),
(6, 6, 76, 'doggo fights', 'bonk', '', '', '2024-02-24', 1);

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
(1, 2, 1),
(2, 2, 4),
(3, 4, 1),
(7, 2, 17);

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
  `userID` int(11) NOT NULL,
  `gameID` int(11) NOT NULL,
  `timestamp` date NOT NULL DEFAULT current_timestamp(),
  `rating` float NOT NULL,
  `comment` varchar(1000) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `userID`, `gameID`, `timestamp`, `rating`, `comment`) VALUES
(15, 1, 4, '2024-02-22', 5, 'This is a review comment'),
(24, 1, 5, '2024-02-25', 1, 'This is a review comment'),
(17, 2, 4, '2024-02-22', 2.5, 'This is a review comment update'),
(25, 11, 2, '0000-00-00', 3, 'asdjkahsdjkashdasjklhdas'),
(44, 12, 1, '2024-03-15', 2.5, 'asdasdasdasas');

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
(11, 'chocolate'),
(17, 'metroidvania'),
(3, 'rpg'),
(4, 'singleplayer'),
(2, 'wholesome');

-- --------------------------------------------------------

--
-- Table structure for table `tokens`
--

CREATE TABLE `tokens` (
  `id` int(11) NOT NULL,
  `sub` int(11) NOT NULL COMMENT 'user_id',
  `exp` int(11) NOT NULL COMMENT 'expiration_time',
  `sha` varchar(256) NOT NULL COMMENT 'hashed token'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tokens`
--

INSERT INTO `tokens` (`id`, `sub`, `exp`, `sha`) VALUES
(62, 9, 1710465455, '02a77fb3732c1b1d2aae48c611f5516e281a48ebcffc0bcad20ca1f78a2c3623'),
(63, 9, 1710465509, 'c7f9979750120f03c4a077248ee17d9685d2925ad4375c300287f7ea1911b025'),
(64, 9, 1710465974, 'ba898f68042d450e4574b0459226dcb91e33d29dbdd11633ee02327c453de8da'),
(65, 9, 1710466006, '2f299f2f4286334a3ae2fa274486a5801fc7d112b1e6f98a18181e614dfe641f'),
(66, 9, 1710466161, 'f4d9d34416fe9527a3b17681c7cfbf0a710ae9f2ef5324e7493570e903e83c9a'),
(67, 9, 1710466217, '3470e98bd4382dad3640a2bcf8cc50e4425b7c3332e0d7efc7267c4a55fdfe28'),
(68, 9, 1710466272, 'f90715064ecf0f7428bfee33c13d3b1fd17f3ab773ed5d84d05fa2e5c89e0b3c'),
(69, 9, 1710466596, '2ba4ea9e6d4378427bd242305694d29f28cb161d9103e8722173fd09cc0f1cf7'),
(71, 9, 1710469898, 'ce80a21e0f8b855ad2c8a00a8ee708220b33569d4ca14e54bd2c833777e53d7d'),
(72, 9, 1710469954, 'c0bb7be5fce9865a9cfbd912ac2161fd141730a5824a0210e3221ec8f520b2ff'),
(73, 9, 1710470026, 'ddba0cd4f08eab09fcc3fc78dbe16aa5a3ffc3a6607d4140eea75279ef1d3e6b'),
(80, 9, 1710471005, '2eb7686f4936e3547d38c96f97bdd71bfe2b655d23c2ac9094ae50c1c90ec472'),
(82, 9, 1710474462, '41e9d1924b2d5fb2179b6f91a4ac3ee9fe931ca4446ab57ed5c71ba0d00dbdad'),
(83, 9, 1710474545, '84b3e5ee625b07fdeded6428ed005f5f66b0003fe0ac688f44cbbe4527261634'),
(84, 9, 1710474621, 'e3e2526d55c4e7e17cc5d5e18f1fbca333e6f0f9b5bb6068a8ba8acb04c57a77'),
(85, 9, 1710474799, '1fc77964c8a68ce6b048fd0c2fbeed2890c3a92b87f0119d2ab5dbb6ac837e26'),
(86, 9, 1710474927, '98f14bf881eaafb2395c99789299fbe9e4fb74021f59b88cc50f01fcaa1ffc16'),
(90, 9, 1710539728, 'e764cf4f3a4da52462b7ce128adccb5f4a92d449421ef85a080c584489ea3861'),
(115, 11, 1710559894, 'd0a396821f0cfdb71e8e2fd48203fde208868e670cefe762e32f7478d4df3d86'),
(117, 11, 1710563975, '552054cb96b01f181d1bb5cd94319c226acbe16855656ff392ca3144a4d7ae4d'),
(121, 9, 1710564732, '6259f3d627259e1c134efcb292e809bcbd7b41d0420fda9ce2ae6c7cd3608f55'),
(122, 11, 1710565475, '76d502ad2661ba6ef08f1fdf857d5d3f7a04e5bb01363a9213ca52ecab19643a'),
(123, 11, 1710565522, 'a59795187bdf28d4566cf15cd82da89a8836146ade12da1618208694cd1c0767'),
(124, 11, 1710565618, 'f833e969d0e2e15a218cb042bc1d0afd393fd35d7fd20a51fa37b6c53bae6f47'),
(125, 11, 1710565984, '20df0d369c5d741633d378027aed88e593d488d243b372f05e29e17cd717f35d'),
(126, 11, 1710566511, '45879aa488173ea2ee494f6791707764ee61345f0ecdac5162be5994897a4104'),
(127, 11, 1710566542, '6c96db43e0e4c4046450113fe1aa59b61738784376151cf163cf97e677ede3e4'),
(128, 11, 1710566555, 'b630674ec72283b5f05a8bf99a25e783a141784b22a8e54a7abfc2f7312df441'),
(129, 11, 1710566609, 'af16b570406e84b1dca89d4da6391d961750c4f99891dcb359f7476cefccc61c'),
(130, 11, 1710566676, 'c2c27b53471ff7c4af5baebbfb8d9323043457bc7ee82be1282a82847400617e'),
(131, 11, 1710567117, '1d796492d69cb8df1ee8eb45a4ffe1c8d61669ea9f9ace33e4980b1c627544f3'),
(132, 11, 1710567339, '74ae4b36fa368e351f0cf9af16ae0f587dee485a61178ea522cb5ea36da63b66'),
(133, 11, 1710567356, 'bfda8a03faed7c15f8c95be0e033f55a66c98c839ee6aa3fafcab8aee9296352'),
(134, 11, 1710567475, 'fc7fcadb605a9c00a85404c04b638b95735e0217dfb4a5cbff6e4cc26975f070'),
(135, 11, 1710567971, 'b710fc3e1f2d23c34a13a3edc5e36e4db40f8b5386fa57ca2530fcbd0c795683'),
(136, 11, 1710567987, '75994ad063d7f35a0cbcca28dcb7757fac5ed3feeb0880873570a8d98597e7e5'),
(137, 11, 1710568029, '00646fb87818b099b5338da5f1a9a780b4594affa07d569575f92233d5287393'),
(138, 11, 1710568084, '33b2b0d9a0e53710b12fb705799bd5ca5571c0eb1e394852b38519daf8ac8750'),
(139, 11, 1710568140, '2ebf59c207a7136a27c4ea5d4f7df8b807b2890f6e6355748b4d3453691ab0c0'),
(140, 11, 1710568299, '491c8f9c3540c9fbd1f9d8ee970397b47fef6907956cdee4f771b2680e2af183'),
(141, 11, 1710568313, '2745c37022075e12c00ce9b5b74a9c0f8720953807b3deec76f68c60f2695d2c'),
(142, 11, 1710568452, '3a55b55f6ec902ee137baebb096be85561dd4bba9a5f738f5c57db25f356fd97'),
(143, 11, 1710569032, '1f3eaa47dd68b35edfdc4d150976c9ece0b6b6e07fd0eb1bd8698b89c3591944'),
(144, 11, 1710569033, '0a2acd427f0ae1f8f31bd2940163d5077028d4a7760dc25431b12d5ba8eec2d2'),
(145, 11, 1710569199, '14ee0e767dc123f66ac748c09e43dafead5de3b94e35b9d5e0901a58e8b7edeb'),
(146, 11, 1710569226, 'bcc6dde4afaff01b42da4912a98b1c530f355b4ca63c60d907664acdb79e48f9'),
(147, 11, 1710569243, '76d55c235bacb0223db51c4ce672c41e56ec3e4eb2e933ae45982b6e434b9e7e'),
(148, 11, 1710569261, '4e6666f06cd2f72ad00c76ece2230f02a173237136c24ac2ca9e3155d59d0f64'),
(149, 11, 1710569312, 'df43f3c170fbf7b9d0d1feddf58ce11fdb722362b4bab71960245031d44426c5'),
(150, 11, 1710569445, '17b56083e72c8a522b85a56f6d9c27f344b9048a586944007259e7dee24dd91e'),
(151, 11, 1710569456, '5a5d187b06b5ddf4826ef5879ab70f31fef7ea592be14955d5f6d13e148c2ffe'),
(152, 11, 1710569558, '81608196f7cecebf91ed52c870f9458b002a277ed24d306db9ad5be3ed97e734'),
(153, 11, 1710569574, '850d81fbfdf380eeb4f861b075a8c5624f872eb1c17272a7e29b3e24463490ca'),
(154, 11, 1710569607, 'cba936259e9b3a1062bd84264c73a46b7c6f9e66c96c2340a4fc9a9cfe90d047'),
(155, 11, 1710569647, '064eab3b2179c336c2314797d70416dbdb953e78026af44cb1265823c2ae1034'),
(156, 11, 1710569679, '0600f2ddb6b4295e20de5471c1167a525417dcc612d1bbcdc0ca5195071f6dfc'),
(157, 11, 1710569865, '21e7242fd856a93056cfb817e25c75b1f97e954939dec1756c1def63cf0b75f5'),
(158, 11, 1710570043, 'e03338af41b3a37e2a10107502279c0a72aad00ac1e47bf05d5467f42947f37d'),
(159, 11, 1710570095, '6a19974ef9a541c824babe607bea6bd04395ea99f38dfe8b37c23f9232936470'),
(160, 11, 1710570150, '151e1dbea63012a333cc4ee72c9c62ffa53ca827502998e2d69417f80f3e1955'),
(161, 11, 1710570705, 'a85d2478f765b8656edd9e0b8e3e486b43a9b89b9ade7c7db17708274aad42d5'),
(162, 11, 1710572012, '106d68912f72971fd043860a5067b650fe8f52226df5f559dee1b1b1dbf6aa4e'),
(163, 11, 1710572064, '9829a7592741ded73c7fd27576cb356db91251ca3bc3eaf27754f982f1ed95c6'),
(164, 11, 1710572081, 'ec7993b3c5a8d692b6be3540047c22f0184431645e44b5d1ca65fdcef3bb2e5f'),
(165, 11, 1710572086, 'cdb8aa4cc8126123fe96b17f0c7d4959d8b809b9dbe8e345c23ebfbf60fc3e8e'),
(166, 11, 1710572851, 'b4066c7b844834ee5b67a08fc5009eeb488ba9957cec935c9402367bd862116c'),
(167, 11, 1710572864, '050134dd1a92ac5d6b8c820610ed3532a14f24ecb1fc5687c72e95311035dc2a'),
(168, 11, 1710572874, '924fc54221c50845a41a67f53f3cff6e96e89d9ff1b819a0821ace505613fd6e'),
(169, 11, 1710572950, 'f169c6cd7d143bc2d32be72f59f275eaf576253bc6d4a6293b7f1abf86679178'),
(170, 11, 1710573013, 'e9439b0251533e30a2e9195b61aee07e5a64e9e15b2f10fed25de5179501c86c'),
(171, 11, 1710573029, '27dee2a4aca9294572e9694c73400b67332ab678265093fe799c017ced10b045'),
(172, 11, 1710573623, '8b94195c5710a7ee165d26d77d1914923d945fa92b5c629c5994ffcfe487b187'),
(173, 11, 1710581168, '8abdec326c6ac3abf57b67becf917d576f9841a7a16febda468eed62c8ed506e'),
(174, 11, 1710712507, '5579d162ebc227e54dabe873fb459485555c30ad180377113dd3c6ab06794b71'),
(175, 11, 1710716875, '5afc6da18ff704a38c969af9de8da8dc9991015dd6b81f93b282f115fdb3b99a'),
(176, 11, 1710736761, '2c41f8934c5ef325ccc869af28d2c752e5f4153ffba333439cbf5f87d8e30919'),
(177, 9, 1710736823, 'e4aaae2713b15e4d20cc55bb5334be6cbf4529158ffeb06b13e6a1983e799a08'),
(178, 11, 1711064199, 'bafcadbad23a86344cec4e045ce10b89db68f4dcf18fe5232568a6d908e81d60'),
(179, 11, 1711064750, 'e816f9978a66fe98cd7c659276dbaf168160b3d6b7efb79af7356cd0bc35080a'),
(180, 11, 1711066145, 'ef31653a40b417257953ccb91098217b5751caa7e438835c739a60b2352e21d8'),
(181, 11, 1711066679, '5db66bac591dca416abe9841578d35ba0c6ad04b95f8faf06cd65c48cea272a9'),
(182, 11, 1711066709, '060e90b96abb3f679795de5a745027b0fc8daa028a49823dd5cb7d6c16cafe15'),
(183, 11, 1711066903, 'c29d92e78931ed9b54fa3b1e13bb84de4e52b7c34b367b91ff5b57aa28ce0f71'),
(184, 11, 1711066959, 'f9480c17edd570f1d78763c762107ffdb78dd18a3199630f1549ec5c3772087d'),
(185, 11, 1711067028, '6f0d89dd2d2d2ca2e68f0d992bc516b89277bbf023415fefb916a0098690314a'),
(186, 11, 1711067067, '6b8e39b84c70b363e9eebc181f3fd73c6553f5c5a5a2997cd154f1f603e39276'),
(187, 11, 1711067138, '1f13b6c1a4cb6229ba21a1e6b09b9e6fc9cb6937952d26eaa98d3fc5569cbe6a'),
(188, 11, 1711067343, '2346a22f834da454232b139411ae6ebb95b52e403f302adbd0ef6606deff0ebb'),
(189, 11, 1711067960, '0bd8008e82384038c6ff2e4eaca7cef627987ea52eed99daca1f3f0ab901b1c9'),
(190, 11, 1711073349, '4d5ad4a1dda4d230c23dd3a129b3d13e1087aaf58f88b4d941a8fa17838e7d9c'),
(191, 11, 1711073379, '50e348cc1738beaaf20f116368875038d12e41ae39b67914eee1e49c5dbc1c28'),
(192, 11, 1711073417, '6e9e888857f8759a07c9f6b801d0db427c58e0dfec764af9c3bb237da3c252a5'),
(193, 11, 1711073449, '4697256b35331c080defb59460e94b2bcfe32640a1223d59be624eba88a505b1'),
(194, 11, 1711073475, '5522177563818fe7e90415e4cf4bfeb54ec8ea5cfb1cbfa8b6558a63bb383b3d'),
(195, 11, 1711073491, '29345f57c3a62888d98f5a77ea918aedf2e3ec6f5071449cd495337aaf2a02ba'),
(196, 11, 1711073519, '8cabc28431a5b436cd5c4c65df15ed5e46d8f0d756e9e4d1e0ff435ea3210a5b'),
(197, 11, 1711073582, '2291029ac7e28a309d88e4d85b9edb34052a9d3cb50f33f415196a14cae56827'),
(198, 11, 1711073672, '8def1af3e62a39e3a8be85165af291307ad561f6313ad5c516bcf2ccd8c54fe7'),
(199, 11, 1711074393, 'd9a2d9e4cc668c002466a3ac121e3c6ab9cf072a7abd2eeeb938f36ad4060a84'),
(200, 11, 1711074422, 'cd63143c26ff61ddff74a620e31cafcf54e8ffc32b19fd37afbd9211645b9f9b'),
(201, 11, 1711074442, '82c55ba1efe6c9172314383dc3cb95a25791b89964bc77211892d565c6ba5cb1'),
(202, 11, 1711074473, 'dde318fbcf8e6cc9381c390cc476a7b54aeda867466ba0e3637366d074f5529f'),
(203, 11, 1711074519, 'f2f6dd0210f7d3186e27470ac22668c5dbade59adf52cd0b41543bdf2a5c767b'),
(204, 11, 1711074526, '924cb75fb78f728d119cfbb649d3ee9ee8d108dc70ab048df418744c94bb0e5f'),
(205, 11, 1711074613, '12cd45670518993a1f7dd631844f76b39067a17c5a191995c696f9916cc7f5f6'),
(206, 11, 1711074637, 'b0b5bcdc0f913eba018fee8cbddccb38f1de8f00947f04f2930465335aaff3dc'),
(207, 11, 1711074676, '99a06c17095c971423bd6d044b61f1f6270ab5d5679a54e2802b3cba3b85d3b6'),
(208, 11, 1711074773, 'a5a0650161c394519bcc71f8aeeae665be6cc8fbe1df1af70b0d80ad9795e8f3'),
(209, 11, 1711074794, 'ec77bbeaa9e37d6b9fdaa47663d0fae7a0c75e1174bf0ec733de7d4be5bd9abf'),
(210, 11, 1711075254, '5f2fc3bc134325f41d12e94974b18d4f25e67ee1110c274326c1f03b1e903697'),
(211, 11, 1711075320, '5e23708f6ccc47deabdbcc42536ce4ff4031b65791bb735bd589d10942562c13'),
(212, 11, 1711075415, 'c5fbcb47d26221f41dd577d0cba7b0f8a32d49708bca16c134ab49febf4c2398'),
(213, 11, 1711075629, 'f9bb92e53a386a4643c545f2af6a86fa7420f10365617cd799a2fd36cc1a5511'),
(214, 11, 1711075797, 'a4dcb380bcbfe1323bea4c2dd0d93f7ab8e8f2771179e2305d31bbd09ee71727'),
(215, 11, 1711075927, '14d350dae051606227b1abeee7352b602e90b409658d5ce95babc9baad305293'),
(218, 11, 1711076487, 'd8a5752646dc47ac714992137487e0697c57a596fe426d74b605dbd531cf864a'),
(219, 11, 1711076498, '9a960dd60b7f1175c1f3b52d51d234679270ef4545ad90c9031cfe299e52d17f'),
(225, 11, 1711078404, '614d934ce8987335ce988d9094303ecc8aca1e0eae6f58b2ae6986c55a789ebc'),
(229, 11, 1711079649, 'f77247f7f2efb0b714d766275fdb2bf06a22169da68afe61fe81b38bf41a075c'),
(230, 11, 1711079877, '45931741f6973c1ca2e3b0d0b7fdda9ef1719ad4d6783fece3e339b849c9724b'),
(262, 11, 1711082220, '1180de32130f71481a30be4aca0c49283038e2220a7ca3a812e347ee92426268'),
(263, 11, 1711082252, 'fe0c9f48869dacb1e240d8851848e12e7fe1ead0b8c539e9b43faa30a37665db'),
(264, 11, 1711082321, 'cbb2b6f8e0a3a682f82f705e2465861e35ddc71d16a2dcbe7a3f3ad5b807ff37'),
(265, 11, 1711082331, '7782d2018a89830f4a8e8597c9e1666d04d2de3b1eedcbe98ff7d269a31c3583'),
(266, 11, 1711082373, 'd56fafbf9693e26cab5d8befc55cf9f040ce9eed8ba73e346cc5321e6ba18f09'),
(267, 11, 1711082408, 'bbadf81f55751258c6b9c91f3ef0fd398e884361c045b9924aeeb8df7c7958cf'),
(268, 11, 1711082437, '76df8f4af5475b6bbca4b3f9418c88517b6ce352a4c258944dba21b6852101a4'),
(269, 11, 1711082482, 'd8e286f19dcf07840c61ea6ba306a4d1e859b9f39a9df67b48a88c9a83649d67'),
(270, 11, 1711082497, '4f816d587adc67320fb4bf302703b759ea4430e7edb4c6806e67c8e491700758'),
(271, 11, 1711082564, '94646e3abcd3233903b65aec51ae0d7f7bebd92c107fe38437cbcc0b65fa4a30'),
(272, 11, 1711082606, '6bdfc3c7abc2e3e66f3c8476b99ab661802bcf7d1d5610b1e21691010c58fd34'),
(273, 11, 1711083257, 'f93b7b7e31d8f0ef42bca2cc38e1e9a26e45d2f461a596ee41a69836ccc25c18'),
(274, 11, 1711083282, '079218cfb130f16ec1f57a687cb85fa4a52e84ad04f5d039734055c3e5340aa8'),
(275, 11, 1711083296, '619be902cfc84cebff99d561761a1549b126320ab6f7004b04ba805659faa2be'),
(276, 11, 1711083348, 'a9e2cc913c7bdf2512985265c84cad86155d191379546387bbddd900b8b7d4f2'),
(277, 11, 1711083420, '2b44afa70041e5430e43020d1e9b580355422fe3b4da324165f9e69db4dada33'),
(279, 12, 1711087745, 'bc98e31af691f4875a072ffa09a583ba9fb125a3ec3e1d9c590a0ce7ba3e290c');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `donatorID` int(11) NOT NULL,
  `recipientID` int(11) NOT NULL,
  `amount` double NOT NULL DEFAULT 0,
  `timestamp` timestamp(6) NOT NULL DEFAULT current_timestamp(6)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(500) NOT NULL,
  `phoneNumber` bigint(20) DEFAULT NULL,
  `picture` varchar(500) DEFAULT NULL,
  `isAdmin` tinyint(1) DEFAULT 0,
  `isOnline` tinyint(1) NOT NULL DEFAULT 0,
  `description` varchar(500) DEFAULT NULL,
  `name` varchar(50) DEFAULT 'N/A',
  `lastName` varchar(50) DEFAULT 'N/A',
  `creationDate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `phoneNumber`, `picture`, `isAdmin`, `isOnline`, `description`, `name`, `lastName`, `creationDate`) VALUES
(1, 'john_doe', '$2y$10$nmakzte3PwS/95V/K2cqUO/EN8nxutO6BYnXVWqBXB63gcvAYdbne', 'john.doe@example.com', 1234567890, 'cvatar_path', 0, 0, '', '', '', '0000-00-00'),
(2, 'alice_smith', '$2y$10$bD0KtNARFKLJ3e51sxTqv..X91IkIbkASHxccYPWcUhNezNRcjJx6', 'alice.smith@example.com', 987654321, 'avatar_alice.jpg', 0, 0, '', '', '', '0000-00-00'),
(3, 'bob_jones', '$2y$10$QroH2ylQrU3aqx7zeLMS2eUhuPAgT1byd89aeyv5zpv2vo4B/Vf5C', 'bob.jones@example.com', 555666777, 'avatar_bob.png', 0, 0, '', '', '', '0000-00-00'),
(4, 'emma_davis', '$2y$10$roDD0M7SrdDJFKwSYhI4qePO1XkHs7C4h5Sn/pEjD6si9RD/OfTqe', 'emma.davis@example.com', 111222333, 'avatar_emma.jpg', 0, 0, '', '', '', '0000-00-00'),
(5, 'jane_doe', '$2y$10$nZV2jn6b1ExK9MHQOZmKY.hjmFnmWanw2XRu5p8.uaj0K5Wie0zzO', 'jane.doe@example.com', 2234567890, 'bvatar_path ', 0, 0, '0', '0', '0', '0000-00-00'),
(6, 'john_doe', '$2y$10$xpynWrLMrU.CS/gkl.HszuvgpMtDxQ4QuiFxEIuB4Kxug99zI5gDy', 'addaasda', 33344555, 'avatar_path ', 0, 0, '', '', '', '0000-00-00'),
(7, 'tostitos', '$2y$10$Sj0mvySDyMjwOdGIO96hmO4DMybe9bxktLb.C/m1rJRjagjRan43.', 'b', NULL, 'avatar_path ', NULL, 0, NULL, NULL, NULL, '0000-00-00'),
(9, 'playgroundtest', '$2y$10$ateRNXpi6B7lY7DhPV.4kepnBU1mdeevwRMcuj1DWFptjO2aZBizm', 'testUser@email', NULL, NULL, NULL, 0, NULL, NULL, NULL, '2024-03-06'),
(11, '1111131231232121312', '$2y$10$I6cqvdjn76FRmLoSoAa5B.ZVG0pSIH.j7Ny5E61bkHq2SAKkF2pkG', 'test@test', NULL, NULL, NULL, 1, NULL, '123', '', '2024-03-08'),
(12, 'a', '$2y$10$nkOaq3CLcQBa.lrx5.8GCeYEHDLZb/LL6ZQwlbv9ZncIiKTLgHRrq', 'a@a', NULL, NULL, 0, 1, NULL, NULL, NULL, '2024-03-15');

-- --------------------------------------------------------

--
-- Table structure for table `users_downloads`
--

CREATE TABLE `users_downloads` (
  `id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `gameID` int(11) NOT NULL,
  `downloadDate` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  ADD PRIMARY KEY (`gameId`,`tagId`) USING BTREE COMMENT 'Composite primary key',
  ADD UNIQUE KEY `idx_id` (`id`),
  ADD KEY `idx_gameId` (`gameId`),
  ADD KEY `idx_tagId` (`tagId`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`userID`,`gameID`) USING BTREE,
  ADD UNIQUE KEY `id` (`id`) USING BTREE,
  ADD KEY `idx_userID` (`userID`) USING BTREE,
  ADD KEY `idx_gameID` (`gameID`);

--
-- Indexes for table `tags`
--
ALTER TABLE `tags`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_name` (`name`);

--
-- Indexes for table `tokens`
--
ALTER TABLE `tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_sha` (`sha`),
  ADD KEY `idx_sub` (`sub`) USING BTREE;

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_recipientID` (`recipientID`),
  ADD KEY `idx_donatorID` (`donatorID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `idx_email` (`email`);

--
-- Indexes for table `users_downloads`
--
ALTER TABLE `users_downloads`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_userID` (`userID`),
  ADD KEY `idx_gameID` (`gameID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `games`
--
ALTER TABLE `games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `gamestags`
--
ALTER TABLE `gamestags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `tags`
--
ALTER TABLE `tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tokens`
--
ALTER TABLE `tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users_downloads`
--
ALTER TABLE `users_downloads`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `games`
--
ALTER TABLE `games`
  ADD CONSTRAINT `developed_by` FOREIGN KEY (`developerID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `gamestags`
--
ALTER TABLE `gamestags`
  ADD CONSTRAINT `is_tag_from` FOREIGN KEY (`tagId`) REFERENCES `tags` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `is_tag_of` FOREIGN KEY (`gameId`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `review_of` FOREIGN KEY (`gameID`) REFERENCES `games` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `reviewed_by` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `tokens`
--
ALTER TABLE `tokens`
  ADD CONSTRAINT `owned_by` FOREIGN KEY (`sub`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `paid_by` FOREIGN KEY (`donatorID`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `paid_to` FOREIGN KEY (`recipientID`) REFERENCES `users` (`id`);

--
-- Constraints for table `users_downloads`
--
ALTER TABLE `users_downloads`
  ADD CONSTRAINT `downloaded_by` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `item_downloaded` FOREIGN KEY (`gameID`) REFERENCES `games` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
