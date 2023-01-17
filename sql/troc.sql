# SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
# SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
# SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema troc
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema troc
-- -----------------------------------------------------
CREATE DATABASE IF NOT EXISTS troc DEFAULT CHARACTER SET utf8 ;
USE troc ;

-- -----------------------------------------------------
-- Table user
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS user (
  `id_user` INT NOT NULL AUTO_INCREMENT,
  `pseudo` VARCHAR(20) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `nom` VARCHAR(45) NOT NULL,
  `prenom` VARCHAR(45) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `telephone` VARCHAR(20) NOT NULL,
  `civilite` ENUM('m', 'f') NOT NULL,
  `statut` INT(1) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id_user`)
  ) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table photo
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS photo (
  `id_photo` INT NOT NULL AUTO_INCREMENT,
  `photo1` VARCHAR(255) NULL,
  `photo2` VARCHAR(255) NULL,
  `photo3` VARCHAR(255) NULL,
  `photo4` VARCHAR(255) NULL,
  `photo5` VARCHAR(255) NULL,
  PRIMARY KEY (`id_photo`)
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table categorie
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS categorie (
  `id_categorie` INT NOT NULL AUTO_INCREMENT,
  `titre` VARCHAR(255) NOT NULL,
  `mots_clefs` TEXT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table annonce
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS annonce (
  `id_annonce` INT NOT NULL AUTO_INCREMENT,
  `titre` VARCHAR(255) NOT NULL,
  `desc_courte` VARCHAR(255) NOT NULL,
  `desc_longue` TEXT NOT NULL,
  `prix` VARCHAR(10) NOT NULL,
  `photo` VARCHAR(255) NOT NULL,
  `pays` VARCHAR(20) NOT NULL,
  `ville` VARCHAR(45) NOT NULL,
  `adresse` VARCHAR(50) NOT NULL,
  `cp` INT(5) NOT NULL,
  `id_user` INT NOT NULL,
  `id_photo` INT NULL,
  `id_categorie` INT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id_annonce`),
    FOREIGN KEY (`id_user`) REFERENCES user(`id_user`),
    FOREIGN KEY (`id_photo`) REFERENCES photo(`id_photo`),
    FOREIGN KEY (`id_categorie`) REFERENCES categorie(`id_categorie`)
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table note
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS note (
  `id_note` INT NOT NULL AUTO_INCREMENT,
  `id_user1` INT NULL,
  `id_user2` INT NOT NULL,
  `note` INT(3) NOT NULL,
  `avis` TEXT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id_note`),
    FOREIGN KEY (`id_user1`) REFERENCES user(`id_user`),
    FOREIGN KEY (`id_user2`) REFERENCES user(`id_user`)
) ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table commentaire
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS commentaire (
  `id_commentaire` INT NOT NULL AUTO_INCREMENT,
  `id_user` INT NULL,
  `id_annonce` INT NULL,
  `commentaire` TEXT NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`id_commentaire`),
    FOREIGN KEY (`id_user`) REFERENCES user(`id_user`),
    FOREIGN KEY (`id_annonce`) REFERENCES annonce(`id_annonce`)
) ENGINE = InnoDB;


# SET SQL_MODE=@OLD_SQL_MODE;
# SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
# SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
