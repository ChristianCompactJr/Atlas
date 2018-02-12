-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 12-Fev-2018 às 19:33
-- Versão do servidor: 10.1.21-MariaDB
-- PHP Version: 7.1.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `atlas`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_habilidades`
--

CREATE TABLE `atlas_habilidades` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_habilidade_usuario`
--

CREATE TABLE `atlas_habilidade_usuario` (
  `idhabilidade` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `valor` int(11) NOT NULL,
  `interesse` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_usuario`
--

CREATE TABLE `atlas_usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(90) NOT NULL,
  `email` text NOT NULL,
  `senha` text NOT NULL,
  `foto` text,
  `administrador` tinyint(1) NOT NULL,
  `token` varchar(45) DEFAULT NULL,
  `ativo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Extraindo dados da tabela `atlas_usuario`
--

INSERT INTO `atlas_usuario` (`id`, `nome`, `email`, `senha`, `foto`, `administrador`, `token`, `ativo`) VALUES
(6, 'Christian Luã Lemos', 'christian@compactjr.com', '$2y$10$XBQ.esicOMf0xICB8L9P7uuIs1sgwq4p0f4ONG5lg7wn4uMYc7KK.', 'uploads/fotos/perfil_xWZIKXexGfVFaNFRy3wj.jpg', 1, NULL, 1),
(8, 'Eduardo Hirt', 'eduardo.hirt@compactjr.com', '$2y$10$vwhWsgyO7gxd/SPjAiEqj.gkSgDbAbjcbcmoB1jo9xhqEDgAXzxqy', 'uploads/fotos/perfil_w9AnmN2QbosxlnWGFJ50.jpg', 1, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_usuario_esqueci_senha`
--

CREATE TABLE `atlas_usuario_esqueci_senha` (
  `idusuario` int(11) NOT NULL,
  `chave` text NOT NULL,
  `data_hora` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_usuario_tentativa`
--

CREATE TABLE `atlas_usuario_tentativa` (
  `ip` varchar(40) NOT NULL,
  `tentativas` int(11) NOT NULL,
  `data_hora` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `atlas_habilidades`
--
ALTER TABLE `atlas_habilidades`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `atlas_habilidade_usuario`
--
ALTER TABLE `atlas_habilidade_usuario`
  ADD PRIMARY KEY (`idhabilidade`,`idusuario`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indexes for table `atlas_usuario`
--
ALTER TABLE `atlas_usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `token` (`token`);

--
-- Indexes for table `atlas_usuario_esqueci_senha`
--
ALTER TABLE `atlas_usuario_esqueci_senha`
  ADD PRIMARY KEY (`idusuario`);

--
-- Indexes for table `atlas_usuario_tentativa`
--
ALTER TABLE `atlas_usuario_tentativa`
  ADD PRIMARY KEY (`ip`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `atlas_habilidades`
--
ALTER TABLE `atlas_habilidades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
--
-- AUTO_INCREMENT for table `atlas_usuario`
--
ALTER TABLE `atlas_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `atlas_habilidade_usuario`
--
ALTER TABLE `atlas_habilidade_usuario`
  ADD CONSTRAINT `atlas_habilidade_usuario_ibfk_1` FOREIGN KEY (`idhabilidade`) REFERENCES `atlas_habilidades` (`id`),
  ADD CONSTRAINT `atlas_habilidade_usuario_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `atlas_usuario` (`id`);

--
-- Limitadores para a tabela `atlas_usuario_esqueci_senha`
--
ALTER TABLE `atlas_usuario_esqueci_senha`
  ADD CONSTRAINT `idusuario_fk_key` FOREIGN KEY (`idusuario`) REFERENCES `atlas_usuario` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
