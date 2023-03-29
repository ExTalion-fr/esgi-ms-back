
--
-- Base de données : `esgi-ms`
--
CREATE DATABASE IF NOT EXISTS `esgi-ms` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `esgi-ms`;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
    `userId` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `userName` varchar(30) NOT NULL,
    `userEmail` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Structure de la table `pins`
--

CREATE TABLE `pins` (
    `pinsId` int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `pinsName` varchar(30) NOT NULL,
    `pinsCitation` varchar(500) NOT NULL,
    `pinsUserId` int(11) NOT NULL,
    `pinsCreatedAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Contraintes pour la table `pins`
--
ALTER TABLE `pins`
    ADD CONSTRAINT `FK_PINS_PINS_USER_ID_USER_ID` FOREIGN KEY (`pinsUserId`) REFERENCES `user` (`userId`) ON DELETE CASCADE ON UPDATE CASCADE;
