-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 13-Maio-2020 às 12:21
-- Versão do servidor: 8.0.18
-- versão do PHP: 7.0.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `shuthebox`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `jogo`
--

DROP TABLE IF EXISTS `jogo`;
CREATE TABLE IF NOT EXISTS `jogo` (
  `idJogo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pontuacao` int(4) UNSIGNED NOT NULL,
  `data` datetime NOT NULL,
  `vencedor` enum('G','P','E') CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  `username` varchar(30) CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  PRIMARY KEY (`idJogo`),
  KEY `FK_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1250;

-- --------------------------------------------------------

--
-- Estrutura da tabela `utilizadores`
--

DROP TABLE IF EXISTS `utilizadores`;
CREATE TABLE IF NOT EXISTS `utilizadores` (
  `username` varchar(30) CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  `primeiro_nome` text CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  `apelido` text CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  `dataNascimento` date NOT NULL,
  `password` varchar(50) CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  `dataRegisto` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimoLogin` datetime DEFAULT NULL,
  `bloqueado` tinyint(1) NOT NULL DEFAULT '0',
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=cp1250;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `jogo`
--
ALTER TABLE `jogo`
  ADD CONSTRAINT `FK_username` FOREIGN KEY (`username`) REFERENCES `utilizadores` (`username`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
