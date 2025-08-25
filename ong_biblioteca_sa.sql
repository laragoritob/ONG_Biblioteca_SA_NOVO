-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 25/08/2025 às 21:55
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `ong_biblioteca_sa`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `autor`
--

CREATE TABLE `autor` (
  `Cod_Autor` int(11) NOT NULL,
  `Nome_Autor` varchar(255) DEFAULT NULL,
  `Telefone` varchar(255) DEFAULT NULL,
  `Email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `autor`
--

INSERT INTO `autor` (`Cod_Autor`, `Nome_Autor`, `Telefone`, `Email`) VALUES
(1, 'Suzanne Collins', '(21) 98123-5638', 'suzannecollins@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cliente`
--

CREATE TABLE `cliente` (
  `Cod_Cliente` int(11) NOT NULL,
  `Cod_Perfil` int(11) DEFAULT NULL,
  `Nome` varchar(50) NOT NULL,
  `CPF` varchar(15) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Sexo` varchar(10) NOT NULL,
  `Nome_Responsavel` varchar(50) DEFAULT NULL,
  `Telefone` varchar(20) NOT NULL,
  `Data_Nascimento` date NOT NULL,
  `CEP` varchar(20) NOT NULL,
  `UF` char(2) NOT NULL,
  `Cidade` varchar(30) NOT NULL,
  `Bairro` varchar(30) NOT NULL,
  `Rua` varchar(40) NOT NULL,
  `Num_Residencia` int(11) NOT NULL,
  `Foto` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`Cod_Cliente`, `Cod_Perfil`, `Nome`, `CPF`, `Email`, `Sexo`, `Nome_Responsavel`, `Telefone`, `Data_Nascimento`, `CEP`, `UF`, `Cidade`, `Bairro`, `Rua`, `Num_Residencia`, `Foto`) VALUES
(2, 1, 'Guilherme Vinicius Schwarz', '928.759.274-87', 'guilhermevinicius@gmail.com', 'Ma', 'Johnny Schwarz', '(87) 53386-5862', '2007-08-17', '89220-618', 'SC', 'Joinville', 'Costa e Silva', 'Rua Pavão', 1234, 0x6c6f676f5f7472616e732e706e67);

-- --------------------------------------------------------

--
-- Estrutura para tabela `doador`
--

CREATE TABLE `doador` (
  `Cod_Doador` int(11) NOT NULL,
  `Nome_Doador` varchar(255) DEFAULT NULL,
  `Telefone` varchar(255) DEFAULT NULL,
  `Email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `doador`
--

INSERT INTO `doador` (`Cod_Doador`, `Nome_Doador`, `Telefone`, `Email`) VALUES
(1, 'Frank Ocean', '(21) 92636-6969', 'frankocean@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `editora`
--

CREATE TABLE `editora` (
  `Cod_Editora` int(11) NOT NULL,
  `Nome_Editora` varchar(255) DEFAULT NULL,
  `Telefone` varchar(255) DEFAULT NULL,
  `Email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `editora`
--

INSERT INTO `editora` (`Cod_Editora`, `Nome_Editora`, `Telefone`, `Email`) VALUES
(1, 'Moderna', '(47) 98231-2647', 'moderna_editora@gmail.com');

-- --------------------------------------------------------

--
-- Estrutura para tabela `emprestimo`
--

CREATE TABLE `emprestimo` (
  `Cod_Emprestimo` int(11) NOT NULL,
  `Cod_Cliente` int(11) NOT NULL,
  `Cod_Livro` int(11) NOT NULL,
  `Data_Emprestimo` date NOT NULL,
  `Data_Devolucao` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `funcionario`
--

CREATE TABLE `funcionario` (
  `Cod_Funcionario` int(11) NOT NULL,
  `Cod_Perfil` int(11) DEFAULT NULL,
  `Nome` varchar(50) NOT NULL,
  `CPF` varchar(15) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Sexo` varchar(10) NOT NULL,
  `Telefone` varchar(20) NOT NULL,
  `Data_Nascimento` date NOT NULL,
  `Data_Efetivacao` date NOT NULL,
  `CEP` varchar(20) NOT NULL,
  `UF` char(2) NOT NULL,
  `Cidade` varchar(30) NOT NULL,
  `Bairro` varchar(30) NOT NULL,
  `Rua` varchar(40) NOT NULL,
  `Num_Residencia` int(11) NOT NULL,
  `Usuario` varchar(20) NOT NULL,
  `Senha` varchar(20) NOT NULL,
  `Foto` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionario`
--

INSERT INTO `funcionario` (`Cod_Funcionario`, `Cod_Perfil`, `Nome`, `CPF`, `Email`, `Sexo`, `Telefone`, `Data_Nascimento`, `Data_Efetivacao`, `CEP`, `UF`, `Cidade`, `Bairro`, `Rua`, `Num_Residencia`, `Usuario`, `Senha`, `Foto`) VALUES
(5, 1, 'Sérgio Luiz da Silveira', '123.456.789-10', '', 'Masculino', '(47) 91234-5678', '1980-09-11', '2005-02-20', '80010-030', 'PR', 'Curitiba', 'Centro', 'Praça Rui Barbosa', 29, 'sergio_luiz', '12345678', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `genero`
--

CREATE TABLE `genero` (
  `Cod_Genero` int(11) NOT NULL,
  `Nome_Genero` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `genero`
--

INSERT INTO `genero` (`Cod_Genero`, `Nome_Genero`) VALUES
(1, 'Ação'),
(2, 'Aventura'),
(3, 'Romance'),
(4, 'Suspense'),
(5, 'Ficção Científica'),
(6, 'Terror'),
(7, 'Educacional'),
(8, 'Horror'),
(9, 'Fantasia'),
(10, 'Autobiografia');

-- --------------------------------------------------------

--
-- Estrutura para tabela `livro`
--

CREATE TABLE `livro` (
  `Cod_Livro` int(11) NOT NULL,
  `Cod_Autor` int(11) DEFAULT NULL,
  `Cod_Editora` int(11) DEFAULT NULL,
  `Cod_Doador` int(11) DEFAULT NULL,
  `Cod_Genero` int(11) DEFAULT NULL,
  `Titulo` varchar(50) NOT NULL,
  `Data_Lancamento` varchar(12) DEFAULT NULL,
  `Data_Registro` varchar(12) DEFAULT NULL,
  `Quantidade` int(11) NOT NULL,
  `Num_Prateleira` char(2) DEFAULT NULL,
  `Foto` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `multa`
--

CREATE TABLE `multa` (
  `Cod_Multa` int(11) NOT NULL,
  `Cod_Cliente` int(11) NOT NULL,
  `Cod_Livro` int(11) NOT NULL,
  `Cod_Emprestimo` int(11) NOT NULL,
  `Data_Multa` date NOT NULL,
  `Valor_Multa` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil_cliente`
--

CREATE TABLE `perfil_cliente` (
  `Cod_Perfil` int(11) NOT NULL,
  `Nome_Perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perfil_cliente`
--

INSERT INTO `perfil_cliente` (`Cod_Perfil`, `Nome_Perfil`) VALUES
(1, 'Criança'),
(2, 'Responsável');

-- --------------------------------------------------------

--
-- Estrutura para tabela `perfil_funcionario`
--

CREATE TABLE `perfil_funcionario` (
  `Cod_Perfil` int(11) NOT NULL,
  `Nome_Perfil` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `perfil_funcionario`
--

INSERT INTO `perfil_funcionario` (`Cod_Perfil`, `Nome_Perfil`) VALUES
(1, 'Gerente'),
(2, 'Gestor'),
(3, 'Bibliotecário'),
(4, 'Recreador'),
(5, 'Repositor');

-- --------------------------------------------------------

--
-- Estrutura para tabela `relatorio`
--

CREATE TABLE `relatorio` (
  `Cod_Relatorio` int(11) NOT NULL,
  `Cod_Funcionario` int(11) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Data_Relatorio` date NOT NULL,
  `Tipo_Arquivo` varchar(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `autor`
--
ALTER TABLE `autor`
  ADD PRIMARY KEY (`Cod_Autor`);

--
-- Índices de tabela `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`Cod_Cliente`),
  ADD KEY `fk_Cod_Perfil` (`Cod_Perfil`);

--
-- Índices de tabela `doador`
--
ALTER TABLE `doador`
  ADD PRIMARY KEY (`Cod_Doador`);

--
-- Índices de tabela `editora`
--
ALTER TABLE `editora`
  ADD PRIMARY KEY (`Cod_Editora`);

--
-- Índices de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD PRIMARY KEY (`Cod_Emprestimo`),
  ADD KEY `FK_Cliente_Emprestimo` (`Cod_Cliente`),
  ADD KEY `FK_Livro_Emprestimo` (`Cod_Livro`);

--
-- Índices de tabela `funcionario`
--
ALTER TABLE `funcionario`
  ADD PRIMARY KEY (`Cod_Funcionario`),
  ADD KEY `fk_Cod_Perfil_Func` (`Cod_Perfil`);

--
-- Índices de tabela `genero`
--
ALTER TABLE `genero`
  ADD PRIMARY KEY (`Cod_Genero`);

--
-- Índices de tabela `livro`
--
ALTER TABLE `livro`
  ADD PRIMARY KEY (`Cod_Livro`),
  ADD KEY `fk_Cod_Autor` (`Cod_Autor`),
  ADD KEY `fk_Cod_Editora` (`Cod_Editora`),
  ADD KEY `fk_Cod_Doador` (`Cod_Doador`),
  ADD KEY `fk_Cod_Genero` (`Cod_Genero`);

--
-- Índices de tabela `multa`
--
ALTER TABLE `multa`
  ADD PRIMARY KEY (`Cod_Multa`),
  ADD KEY `FK_Cliente_Multa` (`Cod_Cliente`),
  ADD KEY `FK_Livro_Multa` (`Cod_Livro`),
  ADD KEY `FK_Multa_Emprestimo` (`Cod_Emprestimo`);

--
-- Índices de tabela `perfil_cliente`
--
ALTER TABLE `perfil_cliente`
  ADD PRIMARY KEY (`Cod_Perfil`);

--
-- Índices de tabela `perfil_funcionario`
--
ALTER TABLE `perfil_funcionario`
  ADD PRIMARY KEY (`Cod_Perfil`);

--
-- Índices de tabela `relatorio`
--
ALTER TABLE `relatorio`
  ADD PRIMARY KEY (`Cod_Relatorio`),
  ADD KEY `FK_Relatorio_Funcionario` (`Cod_Funcionario`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `autor`
--
ALTER TABLE `autor`
  MODIFY `Cod_Autor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `Cod_Cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `doador`
--
ALTER TABLE `doador`
  MODIFY `Cod_Doador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `editora`
--
ALTER TABLE `editora`
  MODIFY `Cod_Editora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  MODIFY `Cod_Emprestimo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `Cod_Funcionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `genero`
--
ALTER TABLE `genero`
  MODIFY `Cod_Genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `livro`
--
ALTER TABLE `livro`
  MODIFY `Cod_Livro` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `multa`
--
ALTER TABLE `multa`
  MODIFY `Cod_Multa` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `perfil_cliente`
--
ALTER TABLE `perfil_cliente`
  MODIFY `Cod_Perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `perfil_funcionario`
--
ALTER TABLE `perfil_funcionario`
  MODIFY `Cod_Perfil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `relatorio`
--
ALTER TABLE `relatorio`
  MODIFY `Cod_Relatorio` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_Cod_Perfil` FOREIGN KEY (`Cod_Perfil`) REFERENCES `perfil_cliente` (`Cod_Perfil`);

--
-- Restrições para tabelas `emprestimo`
--
ALTER TABLE `emprestimo`
  ADD CONSTRAINT `FK_Cliente_Emprestimo` FOREIGN KEY (`Cod_Cliente`) REFERENCES `cliente` (`Cod_Cliente`),
  ADD CONSTRAINT `FK_Livro_Emprestimo` FOREIGN KEY (`Cod_Livro`) REFERENCES `livro` (`Cod_Livro`);

--
-- Restrições para tabelas `funcionario`
--
ALTER TABLE `funcionario`
  ADD CONSTRAINT `fk_Cod_Perfil_Func` FOREIGN KEY (`Cod_Perfil`) REFERENCES `perfil_funcionario` (`Cod_Perfil`);

--
-- Restrições para tabelas `livro`
--
ALTER TABLE `livro`
  ADD CONSTRAINT `fk_Cod_Autor` FOREIGN KEY (`Cod_Autor`) REFERENCES `autor` (`Cod_Autor`),
  ADD CONSTRAINT `fk_Cod_Doador` FOREIGN KEY (`Cod_Doador`) REFERENCES `doador` (`Cod_Doador`),
  ADD CONSTRAINT `fk_Cod_Editora` FOREIGN KEY (`Cod_Editora`) REFERENCES `editora` (`Cod_Editora`),
  ADD CONSTRAINT `fk_Cod_Genero` FOREIGN KEY (`Cod_Genero`) REFERENCES `genero` (`Cod_Genero`);

--
-- Restrições para tabelas `multa`
--
ALTER TABLE `multa`
  ADD CONSTRAINT `FK_Cliente_Multa` FOREIGN KEY (`Cod_Cliente`) REFERENCES `cliente` (`Cod_Cliente`),
  ADD CONSTRAINT `FK_Livro_Multa` FOREIGN KEY (`Cod_Livro`) REFERENCES `livro` (`Cod_Livro`),
  ADD CONSTRAINT `FK_Multa_Emprestimo` FOREIGN KEY (`Cod_Emprestimo`) REFERENCES `emprestimo` (`Cod_Emprestimo`);

--
-- Restrições para tabelas `relatorio`
--
ALTER TABLE `relatorio`
  ADD CONSTRAINT `FK_Relatorio_Funcionario` FOREIGN KEY (`Cod_Funcionario`) REFERENCES `funcionario` (`Cod_Funcionario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
