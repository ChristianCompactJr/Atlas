-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 23-Fev-2018 às 01:00
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `atlas_habilidades`
--

INSERT INTO `atlas_habilidades` (`id`, `nome`) VALUES
(17, 'HTML'),
(18, 'CSS'),
(19, 'Javascript'),
(20, 'SQL'),
(21, 'Python'),
(22, 'Django'),
(23, 'Jquery'),
(24, 'PHP');

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_habilidade_usuario`
--

CREATE TABLE `atlas_habilidade_usuario` (
  `idhabilidade` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `valor` int(11) NOT NULL,
  `interesse` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `atlas_habilidade_usuario`
--

INSERT INTO `atlas_habilidade_usuario` (`idhabilidade`, `idusuario`, `valor`, `interesse`) VALUES
(17, 6, 72, 1),
(17, 8, 0, 0),
(17, 10, 0, 0),
(17, 11, 0, 0),
(18, 6, 90, 1),
(18, 8, 0, 0),
(18, 10, 0, 0),
(18, 11, 0, 0),
(19, 6, 55, 1),
(19, 8, 0, 0),
(19, 10, 0, 0),
(19, 11, 0, 0),
(20, 6, 70, 1),
(20, 8, 0, 0),
(20, 10, 0, 0),
(20, 11, 0, 0),
(21, 6, 25, 1),
(21, 8, 0, 0),
(21, 10, 0, 0),
(21, 11, 0, 0),
(22, 6, 10, 1),
(22, 8, 0, 0),
(22, 10, 0, 0),
(22, 11, 0, 0),
(23, 6, 77, 1),
(23, 8, 0, 0),
(23, 10, 0, 0),
(23, 11, 0, 0),
(24, 6, 85, 1),
(24, 8, 0, 0),
(24, 10, 0, 0),
(24, 11, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_projeto`
--

CREATE TABLE `atlas_projeto` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL,
  `scrum_master` int(11) DEFAULT NULL,
  `data_inicio` text NOT NULL,
  `prazo` text,
  `cliente` text NOT NULL,
  `backlog` text,
  `observacoes` text,
  `estagio` enum('Desenvolvimento','Entrege','Manutenção') NOT NULL DEFAULT 'Desenvolvimento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `atlas_projeto`
--

INSERT INTO `atlas_projeto` (`id`, `nome`, `scrum_master`, `data_inicio`, `prazo`, `cliente`, `backlog`, `observacoes`, `estagio`) VALUES
(9, 'Atlas', 6, '26-01-2018', '05-03-2018', 'Compact Jr', '', '', 'Desenvolvimento'),
(13, 'SM Estacas', 8, '31-01-2018', '28-02-2018', 'SM Estacas', '', '', 'Desenvolvimento'),
(14, 'Help!', 6, '14-01-2018', '28-02-2018', 'Help consultoria', 'Um back qualquer', 'Obs qualquer', 'Desenvolvimento'),
(15, 'Nani?', 10, '20-02-2018', '27-02-2018', '?', '123', '123', 'Desenvolvimento'),
(16, 'CR Campeiro 7', 6, '11-02-2018', '28-02-2018', 'UFSM', '', '', 'Desenvolvimento'),
(17, 'Optimus', 6, '11-02-2018', '28-02-2018', 'Optimus', '', '', 'Desenvolvimento');

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_projeto_desenvolvedor`
--

CREATE TABLE `atlas_projeto_desenvolvedor` (
  `idprojeto` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `atlas_projeto_desenvolvedor`
--

INSERT INTO `atlas_projeto_desenvolvedor` (`idprojeto`, `idusuario`, `ativo`) VALUES
(9, 8, 1),
(13, 6, 0),
(13, 10, 1),
(13, 11, 1),
(14, 10, 1),
(14, 11, 1),
(15, 6, 1),
(15, 8, 0),
(16, 8, 1),
(16, 10, 1),
(16, 11, 1),
(17, 10, 1),
(17, 11, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_projeto_tarefa_macro`
--

CREATE TABLE `atlas_projeto_tarefa_macro` (
  `id` int(11) NOT NULL,
  `idprojeto` int(11) DEFAULT NULL,
  `nome` text NOT NULL,
  `descricao` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_projeto_tarefa_micro`
--

CREATE TABLE `atlas_projeto_tarefa_micro` (
  `id` int(11) NOT NULL,
  `idmacro` int(11) NOT NULL,
  `nome` text NOT NULL,
  `descricao` text,
  `tempo_previsto` text,
  `link_uteis` text,
  `concluida` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_projeto_tarefa_micro_responsavel`
--

CREATE TABLE `atlas_projeto_tarefa_micro_responsavel` (
  `idmicro` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `atlas_usuario`
--

INSERT INTO `atlas_usuario` (`id`, `nome`, `email`, `senha`, `foto`, `administrador`, `token`, `ativo`) VALUES
(6, 'Christian Luã Lemos', 'christian@compactjr.com', '$2y$10$XBQ.esicOMf0xICB8L9P7uuIs1sgwq4p0f4ONG5lg7wn4uMYc7KK.', 'uploads/fotos/perfil_xWZIKXexGfVFaNFRy3wj.jpg', 1, NULL, 1),
(8, 'Eduardo Hirt', 'eduardo.hirt@compactjr.com', '$2y$10$vwhWsgyO7gxd/SPjAiEqj.gkSgDbAbjcbcmoB1jo9xhqEDgAXzxqy', 'uploads/fotos/perfil_w9AnmN2QbosxlnWGFJ50.jpg', 0, NULL, 1),
(10, 'visitante', 'visitante@compactjr.com', '$2y$10$pLT/yf6LwwncBxGzHoBj2uzgup.3qUDvbtIbmrCg9R68czOOiRnAS', '', 0, NULL, 1),
(11, 'Chris', 'christianlualemosc@hotmail.com', '$2y$10$WUGbBATm2/xeK9RveTTqwOpT.AGdPmoRQwpCCVQsHOfld2YiaiaAa', '', 0, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_usuario_esqueci_senha`
--

CREATE TABLE `atlas_usuario_esqueci_senha` (
  `idusuario` int(11) NOT NULL,
  `chave` text NOT NULL,
  `data_hora` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `atlas_usuario_esqueci_senha`
--

INSERT INTO `atlas_usuario_esqueci_senha` (`idusuario`, `chave`, `data_hora`) VALUES
(6, 'tL86CUMOON07qTO9gcRFG9gSxHxxamPOo5ItAkkGA5cdJMrfRrFuluXd0TU6', '13-02-2018 01:44:24');

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_usuario_tentativa`
--

CREATE TABLE `atlas_usuario_tentativa` (
  `ip` varchar(40) NOT NULL,
  `tentativas` int(11) NOT NULL,
  `data_hora` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
-- Indexes for table `atlas_projeto`
--
ALTER TABLE `atlas_projeto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scrum_master` (`scrum_master`);

--
-- Indexes for table `atlas_projeto_desenvolvedor`
--
ALTER TABLE `atlas_projeto_desenvolvedor`
  ADD PRIMARY KEY (`idprojeto`,`idusuario`),
  ADD KEY `idusuario` (`idusuario`);

--
-- Indexes for table `atlas_projeto_tarefa_macro`
--
ALTER TABLE `atlas_projeto_tarefa_macro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idprojeto` (`idprojeto`);

--
-- Indexes for table `atlas_projeto_tarefa_micro`
--
ALTER TABLE `atlas_projeto_tarefa_micro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idmacro` (`idmacro`);

--
-- Indexes for table `atlas_projeto_tarefa_micro_responsavel`
--
ALTER TABLE `atlas_projeto_tarefa_micro_responsavel`
  ADD PRIMARY KEY (`idmicro`,`idusuario`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- AUTO_INCREMENT for table `atlas_projeto`
--
ALTER TABLE `atlas_projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `atlas_projeto_tarefa_macro`
--
ALTER TABLE `atlas_projeto_tarefa_macro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `atlas_projeto_tarefa_micro`
--
ALTER TABLE `atlas_projeto_tarefa_micro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `atlas_usuario`
--
ALTER TABLE `atlas_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `atlas_habilidade_usuario`
--
ALTER TABLE `atlas_habilidade_usuario`
  ADD CONSTRAINT `atlas_habilidade_usuario_ibfk_1` FOREIGN KEY (`idhabilidade`) REFERENCES `atlas_habilidades` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `atlas_habilidade_usuario_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `atlas_usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `atlas_projeto`
--
ALTER TABLE `atlas_projeto`
  ADD CONSTRAINT `atlas_projeto_ibfk_1` FOREIGN KEY (`scrum_master`) REFERENCES `atlas_usuario` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Limitadores para a tabela `atlas_projeto_desenvolvedor`
--
ALTER TABLE `atlas_projeto_desenvolvedor`
  ADD CONSTRAINT `atlas_projeto_desenvolvedor_ibfk_1` FOREIGN KEY (`idprojeto`) REFERENCES `atlas_projeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `atlas_projeto_desenvolvedor_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `atlas_usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `atlas_projeto_tarefa_macro`
--
ALTER TABLE `atlas_projeto_tarefa_macro`
  ADD CONSTRAINT `atlas_projeto_tarefa_macro_ibfk_1` FOREIGN KEY (`idprojeto`) REFERENCES `atlas_projeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `atlas_projeto_tarefa_micro`
--
ALTER TABLE `atlas_projeto_tarefa_micro`
  ADD CONSTRAINT `atlas_projeto_tarefa_micro_ibfk_1` FOREIGN KEY (`idmacro`) REFERENCES `atlas_projeto_tarefa_macro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `atlas_projeto_tarefa_micro_responsavel`
--
ALTER TABLE `atlas_projeto_tarefa_micro_responsavel`
  ADD CONSTRAINT `atlas_projeto_tarefa_micro_responsavel_ibfk_1` FOREIGN KEY (`idmicro`) REFERENCES `atlas_projeto_tarefa_micro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `atlas_projeto_tarefa_micro_responsavel_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `atlas_usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `atlas_usuario_esqueci_senha`
--
ALTER TABLE `atlas_usuario_esqueci_senha`
  ADD CONSTRAINT `idusuario_fk_key` FOREIGN KEY (`idusuario`) REFERENCES `atlas_usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
