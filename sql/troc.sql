-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 23 jan. 2023 à 11:38
-- Version du serveur : 5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `troc`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

DROP TABLE IF EXISTS `annonce`;
CREATE TABLE IF NOT EXISTS `annonce` (
  `id_annonce` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `desc_courte` varchar(255) NOT NULL,
  `desc_longue` text NOT NULL,
  `prix` varchar(10) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `pays` varchar(20) NOT NULL,
  `ville` varchar(45) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `cp` int(5) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_photo` int(11) DEFAULT NULL,
  `id_categorie` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_annonce`),
  KEY `id_user_idx` (`id_user`),
  KEY `id_photo_idx` (`id_photo`),
  KEY `id_categorie_idx` (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `annonce`
--

INSERT INTO `annonce` (`id_annonce`, `titre`, `desc_courte`, `desc_longue`, `prix`, `photo`, `pays`, `ville`, `adresse`, `cp`, `id_user`, `id_photo`, `id_categorie`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'Appartement 120m2', 'Location', 'Grand appartement, vaste et lumineux. Belle surface et grand jardin 500m2', '800', 'sidekix-media-eotucbv9jrs-unsplash_63cd3b42e024e.jpg', 'France', 'Bergerac', '78 av. des Grands Ducs', 47250, 1, 12, 5, '2023-01-22 14:33:00', NULL, NULL),
(5, 'Peugeot 504 rouge', 'Voiture qui roule', 'Magnifique lÃ©gende restaurÃ©e de la marque Peugeot', '25000', 'peugeot-504-coupe-1983-1-_63cd3c8ceacec.jpg', 'France', 'Paris', '888 av. du grand huit', 75008, 1, 13, 4, '2023-01-22 14:39:00', NULL, NULL),
(6, 'VÃ©lo', 'Un beau vÃ©lo', 'Parfait pour les trajets du quotidien sans prendre la voiture', '2000', '0bc24771-le-velo-cargo-longtail-r500-elec-de-decathlon-fait-son-entree-a-2700-wtmk_63ce62d37c93f.jpeg', 'France', 'Bordeaux', '45 av. du Petit Paris', 33120, 1, 14, 4, '2023-01-23 11:34:00', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `mots_clefs` text,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `titre`, `mots_clefs`) VALUES
(4, 'Vehicule', 'Voitures, Motos, Bateaux, Vélos, Equipement'),
(5, 'Immobilier', 'Ventes, Locations, Colocations, Bureaux, Logement'),
(6, 'Vacances', 'Camping, Hôtels, Hôtes'),
(7, 'VÃªtements', 'Jeans, Chaussures, Chemises, Robe, Pull, Tee-Shirt'),
(8, 'Loisirs', 'Films, Musiques, Livres');

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) DEFAULT NULL,
  `id_annonce` int(11) DEFAULT NULL,
  `commentaire` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_commentaire`),
  KEY `id_user` (`id_user`),
  KEY `id_annonce` (`id_annonce`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `id_user`, `id_annonce`, `commentaire`, `created_at`, `updated_at`, `deleted_at`) VALUES
(9, 1, 4, 'Cool !\r\n', '2023-01-23 11:35:46', NULL, NULL),
(10, 1, 6, 'Le vélo peut-il supporter une charge lourde ?', '2023-01-23 11:36:31', NULL, NULL),
(11, 1, 5, 'Est-elle dispo toujours ?', '2023-01-23 11:37:11', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `id_note` int(11) NOT NULL AUTO_INCREMENT,
  `id_user_notant` int(11) NOT NULL,
  `id_user_note` int(11) NOT NULL,
  `note` int(3) NOT NULL,
  `avis` text,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_note`),
  KEY `id_user_notant` (`id_user_notant`),
  KEY `id_user_note` (`id_user_note`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

DROP TABLE IF EXISTS `photo`;
CREATE TABLE IF NOT EXISTS `photo` (
  `id_photo` int(11) NOT NULL AUTO_INCREMENT,
  `photo1` varchar(255) DEFAULT NULL,
  `photo2` varchar(255) DEFAULT NULL,
  `photo3` varchar(255) DEFAULT NULL,
  `photo4` varchar(255) DEFAULT NULL,
  `photo5` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_photo`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `photo`
--

INSERT INTO `photo` (`id_photo`, `photo1`, `photo2`, `photo3`, `photo4`, `photo5`) VALUES
(12, 'sidekix-media-eotucbv9jrs-unsplash_63cd3b42e024e.jpg', NULL, NULL, NULL, NULL),
(13, 'peugeot-504-coupe-1983-1-_63cd3c8ceacec.jpg', NULL, NULL, NULL, NULL),
(14, '0bc24771-le-velo-cargo-longtail-r500-elec-de-decathlon-fait-son-entree-a-2700-wtmk_63ce62d37c93f.jpeg', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id_user` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nom` varchar(45) NOT NULL,
  `prenom` varchar(45) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `civilite` enum('m','f') NOT NULL,
  `statut` int(1) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `pseudo`, `password`, `nom`, `prenom`, `email`, `telephone`, `civilite`, `statut`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'admin', '$2y$10$Tl2BDUuk0iC7yWeOV9LdsO.wD4jLKRnshMInTeHHATWYXl8TQnsyu', 'Besok', 'Jojo', 'admin@admin.fr', '0553121213', 'm', 1, '2023-01-17 15:14:24', '2023-01-18 22:26:49', NULL),
(2, 'user', '$2y$10$E51DKpvVl1pAtpeuOjSqaODEnnU52WCaIufaqddg5emBAHIWXqQyO', 'toto', 'titi', 'user@user.fr', '0553121214', 'm', 0, '2023-01-18 12:15:20', '2023-01-18 22:25:57', NULL),
(3, 'test', '$2y$10$3u3qAs08hf4OMq4o3g5jge63sZ2sISTDKaFr1kLsVumco1SNOeVH6', 'test', 'test', 'test@test.fr', '0553241010', 'f', 1, '2023-01-18 22:02:47', '2023-01-18 22:22:34', NULL);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `annonce`
--
ALTER TABLE `annonce`
  ADD CONSTRAINT `id_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_photo` FOREIGN KEY (`id_photo`) REFERENCES `photo` (`id_photo`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `id_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `commentaire_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `commentaire_ibfk_2` FOREIGN KEY (`id_annonce`) REFERENCES `annonce` (`id_annonce`);

--
-- Contraintes pour la table `note`
--
ALTER TABLE `note`
  ADD CONSTRAINT `note_ibfk_1` FOREIGN KEY (`id_user_notant`) REFERENCES `user` (`id_user`),
  ADD CONSTRAINT `note_ibfk_2` FOREIGN KEY (`id_user_note`) REFERENCES `user` (`id_user`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
