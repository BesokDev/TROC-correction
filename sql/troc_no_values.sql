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
-- --------------------------------------------------------
