-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 14-Mar-2018 às 19:18
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
(1, 'HTML'),
(2, 'CSS'),
(3, 'Javascript'),
(4, 'Jquery'),
(5, 'PHP'),
(6, 'SQL'),
(7, 'Django'),
(8, 'Python');

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
(1, 6, 0, 0),
(1, 8, 0, 0),
(1, 10, 0, 0),
(2, 6, 0, 0),
(2, 8, 0, 0),
(2, 10, 0, 0),
(3, 6, 0, 0),
(3, 8, 0, 0),
(3, 10, 0, 0),
(4, 6, 0, 0),
(4, 8, 0, 0),
(4, 10, 0, 0),
(5, 6, 0, 0),
(5, 8, 0, 0),
(5, 10, 0, 0),
(6, 6, 0, 0),
(6, 8, 0, 0),
(6, 10, 0, 0),
(7, 6, 0, 0),
(7, 8, 0, 0),
(7, 10, 0, 0),
(8, 6, 0, 0),
(8, 8, 0, 0),
(8, 10, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_projeto`
--

CREATE TABLE `atlas_projeto` (
  `id` int(11) NOT NULL,
  `nome` text NOT NULL,
  `scrum_master` int(11) DEFAULT NULL,
  `data_inicio` date NOT NULL,
  `prazo` date DEFAULT NULL,
  `cliente` text NOT NULL,
  `observacoes` text,
  `estagio` enum('Desenvolvimento','Entrege','Manutenção') NOT NULL DEFAULT 'Desenvolvimento'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `atlas_projeto`
--

INSERT INTO `atlas_projeto` (`id`, `nome`, `scrum_master`, `data_inicio`, `prazo`, `cliente`, `observacoes`, `estagio`) VALUES
(1, 'Atlas', 6, '2018-03-01', '2018-03-28', 'Compact Jr', '', 'Entrege');

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
(1, 8, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_projeto_sprint`
--

CREATE TABLE `atlas_projeto_sprint` (
  `id` int(11) NOT NULL,
  `projeto` int(11) NOT NULL,
  `nome` varchar(70) NOT NULL,
  `data_inicio` date NOT NULL,
  `prazo` date NOT NULL,
  `estagio` enum('Desenvolvimento','Revisão','Concluída') NOT NULL DEFAULT 'Desenvolvimento',
  `retrospectiva` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_projeto_sprint_tarefa`
--

CREATE TABLE `atlas_projeto_sprint_tarefa` (
  `id` int(11) NOT NULL,
  `idsprint` int(11) NOT NULL,
  `idmicro` int(11) NOT NULL,
  `desempenho` enum('Muito baixo','Baixo','Normal','Alto','Muito Alto') DEFAULT NULL,
  `historico_atual` enum('Incompleta','Instável','Qualificada') DEFAULT NULL,
  `historico_novo` enum('incompleta','Instável','Qualificada') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_projeto_sprint_tarefa_responsavel`
--

CREATE TABLE `atlas_projeto_sprint_tarefa_responsavel` (
  `idtarefa` int(11) NOT NULL,
  `idusuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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

--
-- Extraindo dados da tabela `atlas_projeto_tarefa_macro`
--

INSERT INTO `atlas_projeto_tarefa_macro` (`id`, `idprojeto`, `nome`, `descricao`) VALUES
(1, 1, 'Design', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_projeto_tarefa_micro`
--

CREATE TABLE `atlas_projeto_tarefa_micro` (
  `id` int(11) NOT NULL,
  `idmacro` int(11) NOT NULL,
  `nome` text NOT NULL,
  `descricao` text,
  `observacoes` text,
  `link_uteis` text,
  `prioridade` int(11) NOT NULL,
  `estimativa` int(11) NOT NULL,
  `estado` enum('Incompleta','Instável','Qualificada') NOT NULL DEFAULT 'Incompleta'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `atlas_projeto_tarefa_micro`
--

INSERT INTO `atlas_projeto_tarefa_micro` (`id`, `idmacro`, `nome`, `descricao`, `observacoes`, `link_uteis`, `prioridade`, `estimativa`, `estado`) VALUES
(1, 1, 'Criar logomarca nova', 'Não temos direitos autorais para utilizar a atual. Precisamos de uma nova.', '', '', 5, 3, 'Qualificada'),
(2, 1, 'Protótipo', 'Fazer os protótipos de telas', '', '', 1, 1, 'Qualificada');

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
(6, 'Christian Lemos', 'christian@compactjr.com', '$2y$10$71rqX2sI0Fi4pOMqL.ByzOYBmfUsjeqbqFQb/bvqB7L/J47rjK6MW', 'uploads/fotos/perfil_xWZIKXexGfVFaNFRy3wj.jpg', 1, NULL, 1),
(8, 'Eduardo Hirt', 'eduardo.hirt@compactjr.com', '$2y$10$vwhWsgyO7gxd/SPjAiEqj.gkSgDbAbjcbcmoB1jo9xhqEDgAXzxqy', 'uploads/fotos/perfil_w9AnmN2QbosxlnWGFJ50.jpg', 1, NULL, 1),
(10, 'Giovanni Sacchet', 'giovanni@compactjr.com', '$2y$10$PnCNazq1cFMvXHuyy8ni3OWNzfgUOOGYwlQetSXcT1v3nCVKs5Lt.', 'uploads/fotos/perfil_4tO5GCgb2KXuXRv6fEuI.jpg', 0, NULL, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_usuario_esqueci_senha`
--

CREATE TABLE `atlas_usuario_esqueci_senha` (
  `idusuario` int(11) NOT NULL,
  `chave` text NOT NULL,
  `data_hora` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `atlas_usuario_tentativa`
--

CREATE TABLE `atlas_usuario_tentativa` (
  `ip` varchar(40) NOT NULL,
  `tentativas` int(11) NOT NULL,
  `data_hora` datetime NOT NULL
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
-- Indexes for table `atlas_projeto_sprint`
--
ALTER TABLE `atlas_projeto_sprint`
  ADD PRIMARY KEY (`id`),
  ADD KEY `projeto` (`projeto`);

--
-- Indexes for table `atlas_projeto_sprint_tarefa`
--
ALTER TABLE `atlas_projeto_sprint_tarefa`
  ADD PRIMARY KEY (`id`,`idsprint`,`idmicro`),
  ADD KEY `idsprint` (`idsprint`),
  ADD KEY `idmicro` (`idmicro`);

--
-- Indexes for table `atlas_projeto_sprint_tarefa_responsavel`
--
ALTER TABLE `atlas_projeto_sprint_tarefa_responsavel`
  ADD PRIMARY KEY (`idtarefa`,`idusuario`),
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `atlas_projeto`
--
ALTER TABLE `atlas_projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `atlas_projeto_sprint`
--
ALTER TABLE `atlas_projeto_sprint`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `atlas_projeto_sprint_tarefa`
--
ALTER TABLE `atlas_projeto_sprint_tarefa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `atlas_projeto_tarefa_macro`
--
ALTER TABLE `atlas_projeto_tarefa_macro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `atlas_projeto_tarefa_micro`
--
ALTER TABLE `atlas_projeto_tarefa_micro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `atlas_usuario`
--
ALTER TABLE `atlas_usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
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
-- Limitadores para a tabela `atlas_projeto_sprint`
--
ALTER TABLE `atlas_projeto_sprint`
  ADD CONSTRAINT `atlas_projeto_sprint_ibfk_1` FOREIGN KEY (`projeto`) REFERENCES `atlas_projeto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `atlas_projeto_sprint_tarefa`
--
ALTER TABLE `atlas_projeto_sprint_tarefa`
  ADD CONSTRAINT `atlas_projeto_sprint_tarefa_ibfk_1` FOREIGN KEY (`idsprint`) REFERENCES `atlas_projeto_sprint` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `atlas_projeto_sprint_tarefa_ibfk_2` FOREIGN KEY (`idmicro`) REFERENCES `atlas_projeto_tarefa_micro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `atlas_projeto_sprint_tarefa_responsavel`
--
ALTER TABLE `atlas_projeto_sprint_tarefa_responsavel`
  ADD CONSTRAINT `atlas_projeto_sprint_tarefa_responsavel_ibfk_1` FOREIGN KEY (`idtarefa`) REFERENCES `atlas_projeto_sprint_tarefa` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `atlas_projeto_sprint_tarefa_responsavel_ibfk_2` FOREIGN KEY (`idusuario`) REFERENCES `atlas_projeto_desenvolvedor` (`idusuario`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Limitadores para a tabela `atlas_usuario_esqueci_senha`
--
ALTER TABLE `atlas_usuario_esqueci_senha`
  ADD CONSTRAINT `idusuario_fk_key` FOREIGN KEY (`idusuario`) REFERENCES `atlas_usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
