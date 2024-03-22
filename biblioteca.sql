-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 07/12/2023 às 04:08
-- Versão do servidor: 10.4.28-MariaDB
-- Versão do PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `biblioteca`
--
CREATE DATABASE IF NOT EXISTS `biblioteca` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `biblioteca`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `administrador`
--

CREATE TABLE `administrador` (
  `id` int(11) NOT NULL,
  `status` varchar(100) NOT NULL,
  `login` varchar(100) NOT NULL,
  `senha` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `administrador`
--

INSERT INTO `administrador` (`id`, `status`, `login`, `senha`) VALUES
(2, 'Ativo', 'liviamaria', '0'),
(3, 'Inativo', 'joaobidoia', '0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `autor`
--

CREATE TABLE `autor` (
  `id` int(11) NOT NULL,
  `status` varchar(45) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `autor`
--

INSERT INTO `autor` (`id`, `status`, `nome`) VALUES
(1, 'Ativo', 'V.E Schwab'),
(2, 'Ativo', 'Taylor Jenkins Reid'),
(3, 'Ativo', 'Jane Austen'),
(4, 'Ativo', 'Agatha Christie'),
(5, 'Ativo', 'G.B Baldassari'),
(6, 'Ativo', 'Sally Rooney'),
(7, 'Ativo', 'Ilana Casoy'),
(8, 'Ativo', 'Raphael Montes'),
(9, 'Ativo', 'Carla Madeira'),
(10, 'Ativo', 'Alice Oseman'),
(11, 'Ativo', 'Theago Neiva'),
(12, 'Ativo', 'David Levithan'),
(13, 'Ativo', 'Nathan Hill'),
(14, 'Ativo', 'Jeff Kinney'),
(16, 'Ativo', 'Augusto Cury'),
(17, 'Ativo', 'Adam Silvera'),
(18, 'Ativo', 'Pedro Rhuas'),
(19, 'Ativo', 'Stephen Chbosky'),
(20, 'Ativo', 'ND Steverson'),
(21, 'Ativo', 'Benjamin Alire Saenz'),
(22, 'Ativo', 'Marcos Rey'),
(23, 'Ativo', 'Val Emmich'),
(24, 'Ativo', 'Rick Riordan'),
(25, 'Ativo', 'J.R.R. Tolkien'),
(26, 'Ativo', 'Machado de Assis'),
(27, 'Ativo', 'Itamar Vieira Junior'),
(28, 'Ativo', 'Angela Davis');

-- --------------------------------------------------------

--
-- Estrutura para tabela `editora`
--

CREATE TABLE `editora` (
  `id` int(11) NOT NULL,
  `status` varchar(45) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `editora`
--

INSERT INTO `editora` (`id`, `status`, `nome`) VALUES
(1, 'Ativo', 'FTD'),
(2, 'Ativo', 'Paralela'),
(3, 'Ativo', 'Arqueiro'),
(4, 'Ativo', 'Record'),
(5, 'Ativo', 'Companhia das Letras'),
(6, 'Ativo', 'Galera'),
(7, 'Ativo', 'Seguinte'),
(8, 'Ativo', 'HarperCollins'),
(9, 'Ativo', 'VR Editora'),
(10, 'Ativo', 'Intrínseca'),
(11, 'Ativo', 'Rocco'),
(12, 'Ativo', 'Quill Tree Books'),
(13, 'Ativo', 'Global'),
(14, 'Ativo', 'Martin Claret'),
(15, 'Ativo', 'Antofágica'),
(16, 'Ativo', 'Todavia'),
(17, 'Ativo', 'Boitempo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `emprestimo`
--

CREATE TABLE `emprestimo` (
  `id` int(11) NOT NULL,
  `statusEmprestimo` varchar(100) DEFAULT NULL,
  `dataEmprestimo` datetime DEFAULT current_timestamp(),
  `dataPrevistaDevolucao` date DEFAULT NULL,
  `idLeitor` int(11) DEFAULT NULL,
  `valorMulta` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `emprestimo`
--

INSERT INTO `emprestimo` (`id`, `statusEmprestimo`, `dataEmprestimo`, `dataPrevistaDevolucao`, `idLeitor`, `valorMulta`) VALUES
(1, 'Em andamento', '2023-12-06 22:36:56', '2023-12-14', 1, NULL),
(2, 'Finalizado', '2023-12-06 23:23:15', '2023-12-14', 2, 7),
(3, 'Em andamento', '2023-12-06 23:26:05', '2023-12-14', 3, NULL),
(4, 'Em atraso', '2023-12-06 23:28:49', '2023-12-14', 4, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `genero`
--

CREATE TABLE `genero` (
  `id` int(11) NOT NULL,
  `status` varchar(45) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `genero`
--

INSERT INTO `genero` (`id`, `status`, `nome`) VALUES
(1, 'Ativo', 'Drama'),
(2, 'Ativo', 'Romance'),
(3, 'Ativo', 'Fantasia'),
(4, 'Ativo', 'Mistério'),
(5, 'Ativo', 'True Crime'),
(6, 'Ativo', 'Terror'),
(7, 'Ativo', 'Aventura'),
(8, 'Ativo', 'Autoajuda'),
(9, 'Ativo', 'Graphic Novel'),
(10, 'Ativo', 'Infantil'),
(11, 'Ativo', 'Literatura Brasileira'),
(12, 'Ativo', 'Sociologia');

-- --------------------------------------------------------

--
-- Estrutura para tabela `itensdeemprestimo`
--

CREATE TABLE `itensdeemprestimo` (
  `idLivro` int(11) NOT NULL,
  `idEmprestimo` int(11) NOT NULL,
  `dataDevolvido` date DEFAULT NULL,
  `statusItem` varchar(14) NOT NULL,
  `dataPrevDev` date NOT NULL,
  `dataRenovacao` date DEFAULT NULL,
  `multa` float DEFAULT NULL,
  `quantRenov` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `itensdeemprestimo`
--

INSERT INTO `itensdeemprestimo` (`idLivro`, `idEmprestimo`, `dataDevolvido`, `statusItem`, `dataPrevDev`, `dataRenovacao`, `multa`, `quantRenov`) VALUES
(8, 1, NULL, 'Emprestado', '2023-12-14', NULL, NULL, 0),
(4, 1, NULL, 'Emprestado', '2023-12-14', NULL, NULL, 0),
(29, 2, '2023-12-07', 'Devolvido', '2023-11-30', NULL, 7, 0),
(28, 3, NULL, 'Emprestado', '2023-12-14', NULL, NULL, 0),
(32, 3, NULL, 'Emprestado', '2023-12-14', NULL, NULL, 0),
(22, 4, NULL, 'Emprestado', '2023-12-02', NULL, NULL, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `leitor`
--

CREATE TABLE `leitor` (
  `id` int(11) NOT NULL,
  `status` varchar(45) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `endereco` varchar(100) NOT NULL,
  `dn` date NOT NULL,
  `cpf` varchar(14) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nomeResp` varchar(100) DEFAULT NULL,
  `cpfResp` varchar(14) DEFAULT NULL,
  `telResp` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `leitor`
--

INSERT INTO `leitor` (`id`, `status`, `nome`, `telefone`, `endereco`, `dn`, `cpf`, `email`, `senha`, `nomeResp`, `cpfResp`, `telResp`) VALUES
(1, 'Pendente', 'João Vitor Bidoia', '(44) 99758-6323', 'Avenida Brasil n°123', '2005-01-06', '529.981.651-44', 'joaobidoia@gmail.com', 'jnnon', '', '', ''),
(2, 'Ativo', 'Leandro', '(44) 99985-0471', 'Rua Prudentópolis', '1993-10-07', '285.400.570-85', 'leandrohs@gmail.com', '215416310', '', '', ''),
(3, 'Pendente', 'Lívia Maria', '(44) 99710-7375', 'Avenida Brasil', '2005-11-17', '789.456.456', 'liviamariadossantos998@gmail.com', '123456789', NULL, NULL, NULL),
(4, 'Pendente', 'Larissa Tinelli', '(44) 99722-2068', 'Rua Gastão Vidigal n°187', '2005-02-18', '138.112.579-43', 'larissatinelli@gmail.com', '12345', NULL, NULL, NULL),
(5, 'Ativo', 'Leonardo Alves', '(44) 99931-1723', 'Rua Porecatu n°26', '2004-07-15', '137.007.469-70', 'leoalves@gmail.com', 'leonardo1234', NULL, NULL, NULL),
(6, 'Ativo', 'Cirlei Aparecida da Silva Santos', '(44) 99910-1635', 'Rua Prudentópolis n°266', '1970-11-24', '834.225.529-87', 'cirleissa@gmail.com', 'cirlei123', NULL, NULL, NULL),
(7, 'Ativo', 'Julia de Souza Lavorenti', '(44) 99785-5623', 'Rua Presidente Castelo Branco - 567', '2004-01-04', '568.474.760-44', 'julavorenti@gmail.com', 'julia123', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `livro`
--

CREATE TABLE `livro` (
  `id` int(11) NOT NULL,
  `idEditora` int(11) DEFAULT NULL,
  `idGenero` int(11) DEFAULT NULL,
  `statusLivro` varchar(45) NOT NULL,
  `titulo` varchar(250) NOT NULL,
  `pag` varchar(45) DEFAULT NULL,
  `isbn` varchar(15) DEFAULT NULL,
  `edicao` varchar(45) NOT NULL,
  `arquivo` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `livro`
--

INSERT INTO `livro` (`id`, `idEditora`, `idGenero`, `statusLivro`, `titulo`, `pag`, `isbn`, `edicao`, `arquivo`) VALUES
(1, 1, 3, 'Disponível', 'A vida invisível de Addie La Rue', '499', '6555872551', '7', 'ADDIE.jpg'),
(2, 2, 2, 'Disponível', 'Os sete maridos de Evelyn Hugo', '360', '8584391509', '1', 'evelyn.jpg'),
(3, 2, 1, 'Disponível', 'Daisy Jones and The Six', '360 ', '8584391401', '1', 'daisy.jpg'),
(4, 1, 1, 'Emprestado', 'Pessoas Normais', '264', '8535932569', '1', 'pessoasnormais.jpg'),
(5, 4, 1, 'Disponível', 'Tudo é rio', '210', '6555871784', '10', 'Tudoerio.jpg'),
(6, 7, 2, 'Disponível', 'Heartstopper Vol.1', '288', '8555341612', '1', 'heart.jpg'),
(7, 8, 4, 'Disponível', 'Assassinato no Expresso Oriente', '240', '8595086788', '1', 'expresso.jpg'),
(8, 7, 1, 'Emprestado', 'O fim de tudo', '392', '25165165', '2', 'ofimdetudo.jpg'),
(9, 6, 2, 'Disponível', 'Dois Garotos se beijando', '272', '464564', '1', 'doisgarotos.jpg'),
(10, 7, 1, 'Disponível', 'Nix', '600', '1656544', '3', 'nix.jpg'),
(11, 3, 7, 'Disponível', 'Diário de um Banana vol.1', '224', '8576831309', '2', 'diariodeum.jpg'),
(20, 3, 2, 'Disponível', 'Os dois morrem no final', '416', '655560302X', '1', 'osdoismorremnofinal.jpg'),
(21, 7, 2, 'Disponível', 'Enquanto eu não te encontro', '272', '855534154X', '1', 'enquanto.jpg'),
(22, 11, 1, 'Emprestado', 'As vantagens de ser invisível', '288', '6555320699', '1', 'asvantagensdeserinv.jpg'),
(23, 12, 9, 'Disponível', 'Nimona', '272', '123456879', '2', 'nimona.jpg'),
(24, 7, 2, 'Disponível', 'Aristóteles e Dante Descobrem os Segredos do Universo', '392', '8565765350', '1', 'aridante.jpg'),
(25, 13, 10, 'Disponível', 'Sozinha no Mundo', '128', '8526010433', '18', 'sozinha.jpg'),
(26, 7, 2, 'Disponível', 'Querido Evan Hansen', '336', '8555340837', '1', 'queridoevan.jpg'),
(27, 14, 2, 'Disponível', 'Orgulho e Preconceito', '424', '8544001823', '1', 'orgulho.jpg'),
(28, 14, 2, 'Emprestado', 'Cartas de Jane Austen', '768', '655910267X', '1', 'cartas.jpg'),
(29, 3, 7, 'Disponível', 'Percy Jackson e o Ladrão de Raios', '400', '6555606533', '1', 'percy.jpg'),
(31, 8, 3, 'Disponível', 'O Hobbit', '336', '8595084742', '1', 'hobbit.jpg'),
(32, 15, 11, 'Emprestado', 'Helena', '416', '6586490634', '1', 'helena.jpg'),
(33, 16, 11, 'Disponível', 'Torto Arado', '264', '6580309318', '1', 'tortoarado.jpg'),
(34, 15, 12, 'Disponível', 'Mulheres, raça e classe', '248', '8575595032', '3', 'mulherracaeclasse.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `livroautor`
--

CREATE TABLE `livroautor` (
  `idLivro` int(11) NOT NULL,
  `idAutor` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `livroautor`
--

INSERT INTO `livroautor` (`idLivro`, `idAutor`) VALUES
(1, 1),
(2, 2),
(3, 2),
(4, 6),
(5, 9),
(6, 10),
(7, 4),
(8, 11),
(9, 12),
(10, 13),
(11, 14),
(20, 17),
(21, 18),
(22, 19),
(23, 20),
(24, 21),
(25, 22),
(26, 23),
(27, 3),
(28, 3),
(29, 24),
(31, 25),
(32, 26),
(33, 27),
(34, 28);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `administrador`
--
ALTER TABLE `administrador`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `editora`
--
ALTER TABLE `editora`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idLeitor` (`idLeitor`);

--
-- Índices de tabela `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `itensdeemprestimo`
--
ALTER TABLE `itensdeemprestimo`
  ADD KEY `idLivro` (`idLivro`),
  ADD KEY `idEmprestimo` (`idEmprestimo`);

--
-- Índices de tabela `leitor`
--
ALTER TABLE `leitor`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `livro`
--
ALTER TABLE `livro`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idEditora` (`idEditora`),
  ADD KEY `idGenero` (`idGenero`);

--
-- Índices de tabela `livroautor`
--
ALTER TABLE `livroautor`
  ADD PRIMARY KEY (`idLivro`,`idAutor`),
  ADD KEY `idAutor` (`idAutor`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `administrador`
--
ALTER TABLE `administrador`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `autor`
--
ALTER TABLE `autor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de tabela `editora`
--
ALTER TABLE `editora`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `genero`
--
ALTER TABLE `genero`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `leitor`
--
ALTER TABLE `leitor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `livro`
--
ALTER TABLE `livro`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD CONSTRAINT `emprestimo_ibfk_1` FOREIGN KEY (`idLeitor`) REFERENCES `leitor` (`id`);

--
-- Restrições para tabelas `itensdeemprestimo`
--
ALTER TABLE `itensdeemprestimo`
  ADD CONSTRAINT `idEmprestimo` FOREIGN KEY (`idEmprestimo`) REFERENCES `emprestimo` (`id`),
  ADD CONSTRAINT `idLivro` FOREIGN KEY (`idLivro`) REFERENCES `livro` (`id`);

--
-- Restrições para tabelas `livro`
--
ALTER TABLE `livro`
  ADD CONSTRAINT `idEditora` FOREIGN KEY (`idEditora`) REFERENCES `editora` (`id`),
  ADD CONSTRAINT `idGenero` FOREIGN KEY (`idGenero`) REFERENCES `genero` (`id`);

--
-- Restrições para tabelas `livroautor`
--
ALTER TABLE `livroautor`
  ADD CONSTRAINT `livroautor_ibfk_1` FOREIGN KEY (`idLivro`) REFERENCES `livro` (`id`),
  ADD CONSTRAINT `livroautor_ibfk_2` FOREIGN KEY (`idAutor`) REFERENCES `autor` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
