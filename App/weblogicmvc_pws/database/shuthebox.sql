-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 15-Jun-2020 às 04:02
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
-- Estrutura da tabela `matches`
--

DROP TABLE IF EXISTS `matches`;
CREATE TABLE IF NOT EXISTS `matches` (
  `idJogo` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pontuacao` int(4) UNSIGNED NOT NULL,
  `data` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `vencedor` enum('G','P','E') CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  `idUsername` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`idJogo`),
  KEY `FK_idUsername` (`idUsername`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=cp1250;

-- --------------------------------------------------------

--
-- Estrutura da tabela `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `username` varchar(30) CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(50) CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  `primeiro_nome` text CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  `apelido` text CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  `dataNascimento` date NOT NULL,
  `password` varchar(100) CHARACTER SET cp1250 COLLATE cp1250_general_ci NOT NULL,
  `dataRegisto` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ultimoLogin` datetime DEFAULT NULL,
  `bloqueado` tinyint(1) NOT NULL DEFAULT '0',
  `dataBloqueado` date DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=125 DEFAULT CHARSET=cp1250;

--
-- Extraindo dados da tabela `users`
--

INSERT INTO `users` (`username`, `id`, `email`, `primeiro_nome`, `apelido`, `dataNascimento`, `password`, `dataRegisto`, `ultimoLogin`, `bloqueado`, `dataBloqueado`, `admin`) VALUES
('admin', 123, 'admin@gmail.com', 'admin', 'admin', '2020-06-15', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2020-06-15 04:59:39', NULL, 0, NULL, 1),
('user', 124, 'user@gmail.com', 'user', 'user', '2020-06-15', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', '2020-06-15 05:01:03', NULL, 0, NULL, 0);

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `matches`
--
ALTER TABLE `matches`
  ADD CONSTRAINT `FK_idUsername` FOREIGN KEY (`idUsername`) REFERENCES `users` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
