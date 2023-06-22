-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 19 juin 2023 à 07:36
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
CREATE DATABASE test;
USE test;
--
-- Base de données : `test`
--

-- --------------------------------------------------------

--
-- Structure de la table `forms`
--

DROP TABLE IF EXISTS `forms`;
CREATE TABLE IF NOT EXISTS `forms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `sender_id` int NOT NULL,
  `sender_email` varchar(50) NOT NULL,
  `receiver_id` int NOT NULL,
  `receiver_email` varchar(50) NOT NULL,
  `object` varchar(50) NOT NULL,
  `message` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_sender_id` (`sender_id`),
  KEY `fk_sender_email` (`sender_email`),
  KEY `fk_receiver_id` (`receiver_id`),
  KEY `fk_receiver_email` (`receiver_email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `forms`
--

INSERT INTO `forms` (`id`, `sender_id`, `sender_email`, `receiver_id`, `receiver_email`, `object`, `message`) VALUES
(1, 11, 'carriere.remy@outlook.fr', 12, 'john.smith@gmail.com', 'test', 'dq^sodjfpoqhf ozeirhqouhf mlzef\r\n');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `address` varchar(70) NOT NULL,
  `password` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `phone`, `address`, `password`) VALUES
(11, 'Rémy', 'Carrière', 'carriere.remy@outlook.fr', '', '', '$2y$10$AaScJvASkK.rCGNOpzwUsuy.j9Lzo7Z1jIrqBrLsQLDr3l1mj1t6m'),
(12, 'john', 'smith', 'john.smith@gmail.com', '', '', '$2y$10$YxG2ySV1FWgT5zMkGvQlueQP0B042a7qJDxVzvq66r1u2OHsUK8cy');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `forms`
--
ALTER TABLE `forms`
  ADD CONSTRAINT `fk_receiver_email` FOREIGN KEY (`receiver_email`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `fk_receiver_id` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `fk_sender_email` FOREIGN KEY (`sender_email`) REFERENCES `users` (`email`),
  ADD CONSTRAINT `fk_sender_id` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
