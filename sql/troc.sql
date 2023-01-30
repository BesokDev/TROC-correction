-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 30 jan. 2023 à 16:51
-- Version du serveur : 5.7.31
-- Version de PHP : 7.4.9
--
-- Base de données : `troc`
--

CREATE DATABASE IF NOT EXISTS troc DEFAULT CHARACTER SET utf8 ;

USE troc;
-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
    `id_categorie` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `titre` varchar(255) NOT NULL,
    `mots_clefs` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `titre`, `mots_clefs`) VALUES
(4, 'VÃ©hicule', 'Voitures, Motos, Bateaux, VÃ©los, Equipement'),
(5, 'Immobilier', 'Ventes, Locations, Colocations, Bureaux, Logement'),
(6, 'Vacances', 'Camping, HÃ´tels, HÃ´tes'),
(7, 'VÃªtements', 'Jeans, Chaussures, Chemises, Robe, Pull, Tee-Shirt'),
(8, 'Loisirs', 'Films, Musiques, Livres'),
(9, 'Multimedia', 'Jeux vidÃ©o, Informatique, Image, Son, TÃ©lÃ©phone');

-- --------------------------------------------------------
--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
    `id_user` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `pseudo` varchar(20) NOT NULL,
    `password` varchar(255) NOT NULL,
    `nom` varchar(45) NOT NULL,
    `prenom` varchar(45) NOT NULL,
    `email` varchar(100) NOT NULL,
    `telephone` varchar(20) NOT NULL,
    `civilite` enum('m','f') NOT NULL,
    `statut` int(1) NOT NULL,
    `created_at` date NOT NULL,
    `updated_at` date DEFAULT NULL,
    `deleted_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id_user`, `pseudo`, `password`, `nom`, `prenom`, `email`, `telephone`, `civilite`, `statut`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'Marie501', '$2y$10$sIJggFDG7vHZXVSX.uA5B.nfyBNccwpnmMIBEkz7/F9XG0R.5Oew.', 'Dudu', 'Marie', 'marie@user.fr', '0553241010', 'f', 0, '2023-01-30', NULL, NULL),
(5, 'Luc14200', '$2y$10$cYfJtJmWISDYccxMw2haYu1ef3tXPjCnyxNI8m5EUSGeUgJXwrquy', 'Hassart', 'Luc', 'luc14200@site.fr', '0578451200', 'm', 0, '2023-01-30', NULL, NULL),
(6, 'admin', '$2y$10$rWG7XAjW1jIBahOz2teltOb.eF57LbxniU0PaicfRy349bE9/Rcnq', 'Besok', 'Jona', 'admin24@admin.fr', '0789562312', 'm', 1, '2023-01-30', '2023-01-30', NULL),
(7, 'Jean10', '$2y$10$uLCVjumhwG1ualsnWsfRfOwhYgntYqlHtPDHbJFR9Je8DAf.U5IAK', 'Passeh', 'Jean', 'jean10@user.fr', '0145494710', 'm', 0, '2023-01-30', NULL, NULL),
(8, 'test45', '$2y$10$BNQ5cQ88i.IU0GfnvkU/7eNs37dr/4qymVkflibJvtTUSVydXrjwm', 'Test45', 'Toto', 'test@test.fr', '0178451212', 'm', 0, '2023-01-30', '2023-01-30', NULL);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `photo`
--

INSERT INTO `photo` (`id_photo`, `photo1`, `photo2`, `photo3`, `photo4`, `photo5`) VALUES
(12, 'spacejoy-tauc4h7qf9s-unsplash_63cfd4d1ba2ee.jpg', NULL, NULL, NULL, NULL),
(13, 'peugeot-504-coupe-1983-1-_63cd3c8ceacec.jpg', NULL, NULL, NULL, NULL),
(14, '0bc24771-le-velo-cargo-longtail-r500-elec-de-decathlon-fait-son-entree-a-2700-wtmk_63cfd45d9b347.jpeg', NULL, NULL, NULL, NULL),
(16, 'apple-iphone-x-64-go-2_63d7ae828c4b2.jpg', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

DROP TABLE IF EXISTS `annonce`;
CREATE TABLE IF NOT EXISTS `annonce` (
  `id_annonce` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `titre` varchar(255) NOT NULL,
  `desc_courte` varchar(255) NOT NULL,
  `desc_longue` text NOT NULL,
  `prix` int(10) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `pays` varchar(20) NOT NULL,
  `ville` varchar(45) NOT NULL,
  `adresse` varchar(50) NOT NULL,
  `cp` int(5) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_photo` int(11) DEFAULT NULL,
  `id_categorie` int(11) DEFAULT NULL,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
      FOREIGN KEY (`id_user`) REFERENCES user(`id_user`),
      FOREIGN KEY (`id_photo`) REFERENCES photo(`id_photo`),
      FOREIGN KEY (`id_categorie`) REFERENCES categorie(`id_categorie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `annonce`
--

INSERT INTO `annonce` (`id_annonce`, `titre`, `desc_courte`, `desc_longue`, `prix`, `photo`, `pays`, `ville`, `adresse`, `cp`, `id_user`, `id_photo`, `id_categorie`, `created_at`, `updated_at`, `deleted_at`) VALUES
(4, 'Appartement 120m2', 'Vente\r\n\r\nGrand appartement, vaste et lumineux. Belle surface et grand jardin 500m2\r\n', 'Je vends mon appartement sans intermÃ©diaire, uniquement en direct aux particuliers.\r\nIl sâ€™agit dâ€™un trÃ¨s bel appartement familial rue de Prony Ã  Paris 17e vers la place PÃ©reire Ã  10 min du parc Monceau, 128.5 mÂ² Carrez, lumineux et au calme sur cour entiÃ¨rement, au 4e Ã©tage avec ascenseur.\r\nIl dispose de 3 chambres (possibilitÃ© dâ€™une 4e chambre dâ€™appoint), avec une grande cuisine dinatoire, un double living, deux coins bureaux pour le tÃ©lÃ©travail, une salle dâ€™eau, une salle de bains avec wc, des wc indÃ©pendants, 2 dressings et de nombreux rangements, une belle buanderie, une cave saine, un dÃ©barras, un local Ã  vÃ©lo fermÃ©.\r\nGrande hauteur sous plafonds (3m10).\r\nParquet dâ€™origine.\r\nTrÃ¨s bel immeuble et trÃ¨s bonne copropriÃ©tÃ©. Pas de travaux Ã  venir.\r\nCollÃ¨ge Pierre de Ronsard, Sainte Ursule.\r\nEcoles Renaudes / Fourcroy.\r\nParfait Ã©tat, dÃ©coration rÃ©cente.\r\nPlan modulable.\r\nVisite sur demande.\r\nContact par mail.', 225000, 'spacejoy-tauc4h7qf9s-unsplash_63cfd4d1ba2ee.jpg', 'France', 'Bergerac', '78 av. des Grands Ducs', 47250, 4, 12, 5, '2023-01-22', '2023-01-30', NULL),
(5, 'Peugeot 504 rouge', 'Voiture d\'Ã©poque, magnifique lÃ©gende', 'KilomÃ©trage : 160 000 km\r\n\r\nBelle 504 coupÃ©\r\nAllumage Ã©lectronique\r\n4 cylindres injection\r\nVÃ©hicule sans aucune corrosion\r\nDirection assistÃ©e\r\nVitres Ã©lectriques\r\nIntÃ©rieur en trÃ¨s bon Ã©tat\r\nParcours toutes distances\r\nContrÃ´le technique ok', 17300, 'peugeot-504-coupe-1983-1-_63cd3c8ceacec.jpg', 'France', 'Paris', '888 av. du grand huit', 75008, 5, 13, 4, '2023-01-22', '2023-01-30', NULL),
(6, 'VÃ©lo Ã©lectrique', 'Pour le transport urbain, parfait pour les trajets du quotidien sans prendre la voiture.\r\n\r\n// Moteur central SHIMANO E5000 de 40Nm\r\n// Batterie sur cadre iPowerFit de 400Wh\r\n// Ecran SHIMANO E500 avec 4 niveaux d\'assistance', 'Le moteur de Shimano le plus lÃ©ger Ã  ce jour offre une expÃ©rience de conduite naturelle Ã  la fois fluide et puissante, capable de prendre en charge toutes vos activitÃ©s quotidiennes, en toute fiabilitÃ©.\r\n\r\nIl ne pÃ¨se que 2,5 kg pour une sensation de lÃ©gÃ¨retÃ© et une meilleure prise en main.\r\n\r\nDÃ©tails :\r\n\r\n- Couple moteur de 40Nm\r\n\r\n- Passage facile d\'une option d\'assistance Ã©lectrique Ã  une autre.\r\n\r\n- Jusqu\'Ã  200 % d\'assistance (maximum) pour vous emmener partout oÃ¹ vous voulez aller sans effort physique.\r\n\r\n- Moteur compact et pratiquement silencieux : vous ne remarquerez que son assistance sans faille.\r\n\r\n- Sensation de conduite naturelle pour un confort Ã  l\'Ã©tat pur.\r\n\r\n- ConÃ§u pour Ãªtre utilisÃ© quelles que soient les conditions mÃ©tÃ©orologiques.', 2000, '0bc24771-le-velo-cargo-longtail-r500-elec-de-decathlon-fait-son-entree-a-2700-wtmk_63cfd45d9b347.jpeg', 'France', 'Bordeaux', '180 av. du Petit Paris', 33120, 5, 14, 4, '2023-01-23', '2023-01-30', NULL),
(8, 'iPhone X 64 Go', 'TÃ©lÃ©phone de la marque Apple. TrÃ¨s bon Ã©tat', 'DÃ©couvrez le smartphone le plus innovant dâ€™Apple : lâ€™iPhone X. Celui-ci est Ã©quipÃ© dâ€™un Ã©cran OLED de 5,8 pouces pour une expÃ©rience plus immersive que jamais grÃ¢ce Ã  ses couleurs somptueuses et au contraste 1 000 000:1. La camÃ©ra True Depth vous permettra de prendre des selfies dâ€™une qualitÃ© inÃ©galÃ©e avec un flou artistique et la fonction de reconnaissance faciale Face ID vous permettra de profiter de ce qui se fait de mieux en matiÃ¨re de sÃ©curitÃ©. Lâ€™iPhone X est Ã©galement Ã©quipÃ© de la nouvelle puce A11 Bionic pour une puissance et une intelligence hors norme capable de prendre en charge la rÃ©alitÃ© augmentÃ©e de maniÃ¨re fluide. ComplÃ©tez votre iPhone 10 avec la nouvelle Apple Watch Series 3 compatible 4G ainsi que les accessoires dÃ©diÃ©s : coques et protection dâ€™Ã©cran.', 250, 'apple-iphone-x-64-go-2_63d7ae828c4b2.jpg', 'France', 'Angers', '100 rue AmpÃ¨re', 49000, 7, 16, 9, '2023-01-30', '2023-01-30', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_user` int(11) DEFAULT NULL,
  `id_annonce` int(11) DEFAULT NULL,
  `commentaire` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
      FOREIGN KEY (`id_user`) REFERENCES user(`id_user`),
      FOREIGN KEY (`id_annonce`) REFERENCES annonce(`id_annonce`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `commentaire`
--

INSERT INTO `commentaire` (`id_commentaire`, `id_user`, `id_annonce`, `commentaire`, `created_at`, `updated_at`, `deleted_at`) VALUES
(10, 8, 6, 'Le vÃ©lo peut-il supporter une charge lourde ?', '2023-01-23 11:36:31', NULL, NULL),
(11, 7, 5, 'Est-elle dispo toujours ?', '2023-01-23 11:37:11', NULL, NULL),
(12, 7, 4, 'un test commentaire blabla', '2023-01-24 15:13:31', NULL, NULL),
(13, 7, 4, 'Super, trÃ¨s satisfait ! Vendeur sÃ©rieux :)', '2023-01-29 17:40:17', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `id_note` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `id_user_notant` int(11) NOT NULL,
  `id_user_auteur` int(11) NOT NULL,
  `note` int(3) NOT NULL,
  `avis` text,
  `created_at` date NOT NULL,
  `updated_at` date DEFAULT NULL,
  `deleted_at` date DEFAULT NULL,
      FOREIGN KEY (`id_user_notant`) REFERENCES user(`id_user`),
      FOREIGN KEY (`id_user_auteur`) REFERENCES user(`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `note`
--

INSERT INTO `note` (`id_note`, `id_user_notant`, `id_user_auteur`, `note`, `avis`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 4, 5, 5, 'super merci bcp !', '2023-01-24', NULL, NULL),
(3, 7, 5, 4, 'Merci beaucoup !', '2023-01-29', NULL, NULL),
(4, 4, 7, 2, 'trÃ¨s mauvais produit, Ã©cran rayÃ© et la coques est trÃ¨s usÃ©e !', '2023-01-30', NULL, NULL);

-- --------------------------------------------------------
