-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 05/09/2025 às 19:25
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
  `Email` varchar(255) NOT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `autor`
--

INSERT INTO `autor` (`Cod_Autor`, `Nome_Autor`, `Telefone`, `Email`, `status`) VALUES
(1, 'Suzanne Collins', '(21) 98123-5638', 'suzannecollins@gmail.com', 'ativo'),
(20, 'Jenna Evans Welch', '(47) 98266-3527', 'jennaevans@gmail.com', 'ativo'),
(21, 'J.K. Rowling', '(11) 99999-1234', 'jkrowling@email.com', 'ativo'),
(22, 'George R.R. Martin', '(21) 98888-5678', 'grrmartin@email.com', 'ativo'),
(23, 'Stephen King', '(31) 97777-4321', 'sking@email.com', 'ativo'),
(24, 'Agatha Christie', '(41) 96666-9876', 'achristie@email.com', 'inativo'),
(25, 'J.R.R. Tolkien', '(51) 95555-3456', 'jrrtolkien@email.com', 'ativo'),
(26, 'Dan Brown', '(61) 94444-6789', 'danbrown@email.com', 'ativo'),
(27, 'Paulo Coelho', '(71) 93333-1122', 'pcoelho@email.com', 'ativo'),
(28, 'Isaac Asimov', '(81) 92222-3344', 'iasimov@email.com', 'ativo'),
(29, 'Arthur Conan Doyle', '(91) 91111-5566', 'acdoyle@email.com', 'inativo'),
(30, 'Harper Lee', '(21) 98888-8899', 'hlee@email.com', 'inativo'),
(31, 'Ernest Hemingway', '(31) 97777-9900', 'ehemingway@email.com', 'inativo'),
(32, 'Gabriel García Márquez', '(41) 96666-0011', 'ggmarquez@email.com', 'inativo'),
(33, 'Clarice Lispector', '(51) 95555-2233', 'clispector@email.com', 'inativo'),
(34, 'Machado de Assis', '(61) 94444-3344', 'machado@email.com', 'ativo');

--
-- Acionadores `autor`
--
DELIMITER $$
CREATE TRIGGER `tr_autor_delete_audit` BEFORE DELETE ON `autor` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('autor', 'DELETE', OLD.Cod_Autor,
                     CONCAT('Nome: ', OLD.Nome_Autor, ', Telefone: ', OLD.Telefone, ', Email: ', OLD.Email),
                     USER(), @ip_usuario);
         END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_autor_insert_audit` AFTER INSERT ON `autor` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('autor', 'INSERT', NEW.Cod_Autor, 
                     CONCAT('Nome: ', NEW.Nome_Autor, ', Telefone: ', NEW.Telefone, ', Email: ', NEW.Email),
                     USER(), @ip_usuario);
         END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_autor_update_audit` AFTER UPDATE ON `autor` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('autor', 'UPDATE', NEW.Cod_Autor,
                     CONCAT('Nome: ', OLD.Nome_Autor, ', Telefone: ', OLD.Telefone, ', Email: ', OLD.Email),
                     CONCAT('Nome: ', NEW.Nome_Autor, ', Telefone: ', NEW.Telefone, ', Email: ', NEW.Email),
                     USER(), @ip_usuario);
         END
$$
DELIMITER ;

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
  `Foto` longblob NOT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cliente`
--

INSERT INTO `cliente` (`Cod_Cliente`, `Cod_Perfil`, `Nome`, `CPF`, `Email`, `Sexo`, `Nome_Responsavel`, `Telefone`, `Data_Nascimento`, `CEP`, `UF`, `Cidade`, `Bairro`, `Rua`, `Num_Residencia`, `Foto`, `status`) VALUES
(3, 1, 'Ian Lucas Borba', '985.672.685-78', 'ianlucas@gmail.com', 'Masculino', 'Joice Cristina dos Santos Borba', '(47) 99685-5520', '2009-03-10', '89228-835', 'SC', 'Joinville', 'Espinheiros', 'Rua Osvaldo Galiza', 342, 0x363862396465363964303461362e6a7067, 'ativo'),
(8, 1, 'João Vitor Atanazio', '182.648.267-47', 'joaovitor@gmail.com', 'Masculino', 'Claudia Regina de Souza', '(47) 98361-8391', '2007-07-21', '89203-275', 'SC', 'Joinville', 'Atiradores', 'Rodovia BR-101', 1642, 0x363862396531366563373363622e6a7067, 'ativo'),
(9, 1, 'Gustavo Tobler', '832.675.782-67', 'gustavotobler@gmail.com', 'Masculino', 'Maria Clara Toble da Silva', '(47) 98253-6247', '2017-09-11', '89201-000', 'SC', 'Joinville', 'Centro', 'Rua do Príncipe', 1233, 0x363862396534383933363530642e6a7067, 'ativo'),
(10, 1, 'Matheus Henrique Dela', '873.258.275-28', 'matheushenrique@gmail.com', 'Masculino', 'Samanta Ribeiro Dela', '(47) 98264-8274', '2018-06-20', '89216-560', 'SC', 'Joinville', 'Glória', 'Rua Nestor Hintz', 9122, 0x363862396535393663346530302e6a706567, 'ativo'),
(11, 1, 'Tatiane Vieira', '283.728.628-65', 'tatianevieira@gmail.com', 'Feminino', 'Isabella Vieira ', '(47) 98118-3746', '2010-10-14', '88807-278', 'SC', 'Criciúma', 'Santa Luzia', 'Rua 3 de Maio', 3785, 0x363862396537333232643665382e6a7067, 'ativo'),
(12, 2, 'Drake', '832.782.576-24', 'drake@gmail.com', 'Masculino', '', '(47) 98284-9273', '2000-01-09', '14024-230', 'SP', 'Ribeirão Preto', 'Jardim Canadá', 'Rua Toronto', 1212, 0x363862613232396365653964372e6a706567, 'ativo'),
(13, 1, 'Helena Lopes', '298.237.484-35', 'helenalopes@gmail.com', 'Feminino', 'Vanessa Carvalho Lopes', '(47) 92824-9306', '2018-04-05', '89218-112', 'SC', 'Joinville', 'Santo Antônio', 'Rua Dona Francisca', 4957, 0x363862613230626235346636342e6a7067, 'ativo');

--
-- Acionadores `cliente`
--
DELIMITER $$
CREATE TRIGGER `tr_cliente_delete_audit` BEFORE DELETE ON `cliente` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('cliente', 'DELETE', OLD.Cod_Cliente,
                     CONCAT('Nome: ', OLD.Nome, ', CPF: ', OLD.CPF, ', Email: ', OLD.Email),
                     USER(), @ip_usuario);
         END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_cliente_insert_audit` AFTER INSERT ON `cliente` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('cliente', 'INSERT', NEW.Cod_Cliente, 
                     CONCAT('Nome: ', NEW.Nome, ', CPF: ', NEW.CPF, ', Email: ', NEW.Email),
                     USER(), @ip_usuario);
         END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_cliente_update_audit` AFTER UPDATE ON `cliente` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('cliente', 'UPDATE', NEW.Cod_Cliente,
                     CONCAT('Nome: ', OLD.Nome, ', CPF: ', OLD.CPF, ', Email: ', OLD.Email),
                     CONCAT('Nome: ', NEW.Nome, ', CPF: ', NEW.CPF, ', Email: ', NEW.Email),
                     USER(), @ip_usuario);
         END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `doador`
--

CREATE TABLE `doador` (
  `Cod_Doador` int(11) NOT NULL,
  `Nome_Doador` varchar(255) DEFAULT NULL,
  `Telefone` varchar(255) DEFAULT NULL,
  `Email` varchar(255) DEFAULT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `doador`
--

INSERT INTO `doador` (`Cod_Doador`, `Nome_Doador`, `Telefone`, `Email`, `status`) VALUES
(1, 'Frank Ocean', '(21) 92636-6969', 'frankocean@gmail.com', 'ativo'),
(5, 'Lucas Almeida', '(11) 98888-1122', 'lucas.almeida@email.com', 'ativo'),
(6, 'Fernanda Costa', '(21) 97777-3344', 'fernanda.costa@email.com', 'ativo'),
(7, 'Rafael Sousa', '(31) 96666-5566', 'rafael.sousa@email.com', 'ativo'),
(8, 'Amanda Oliveira', '(41) 95555-7788', 'amanda.oliveira@email.com', 'inativo'),
(9, 'Carlos Mendes', '(51) 94444-9900', 'carlos.mendes@email.com', 'ativo'),
(10, 'Juliana Rocha', '(61) 93333-2211', 'juliana.rocha@email.com', 'ativo'),
(11, 'Marcelo Tavares', '(71) 92222-3344', 'marcelo.tavares@email.com', 'ativo'),
(12, 'Patrícia Lima', '(81) 91111-4455', 'patricia.lima@email.com', 'inativo'),
(13, 'Thiago Martins', '(91) 90000-5566', 'thiago.martins@email.com', 'ativo'),
(14, 'Bianca Ferreira', '(11) 98888-6677', 'bianca.ferreira@email.com', 'ativo'),
(15, 'André Nascimento', '(21) 97777-7788', 'andre.nascimento@email.com', 'ativo'),
(16, 'Renata Pires', '(31) 96666-8899', 'renata.pires@email.com', 'ativo'),
(17, 'Eduardo Barros', '(41) 95555-9900', 'eduardo.barros@email.com', 'inativo'),
(18, 'Sofia Ribeiro', '(51) 94444-1010', 'sofia.ribeiro@email.com', 'ativo'),
(19, 'Gustavo Cunha', '(61) 93333-1111', 'gustavo.cunha@email.com', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `editora`
--

CREATE TABLE `editora` (
  `Cod_Editora` int(11) NOT NULL,
  `Nome_Editora` varchar(255) DEFAULT NULL,
  `Telefone` varchar(255) DEFAULT NULL,
  `Email` varchar(255) NOT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `editora`
--

INSERT INTO `editora` (`Cod_Editora`, `Nome_Editora`, `Telefone`, `Email`, `status`) VALUES
(1, 'Moderna', '(47) 98231-2647', 'moderna_editora@gmail.com', 'ativo'),
(2, 'Panini', '(78) 35627-8562', 'panini@gmail.com', 'ativo'),
(3, 'Editora Rocco', '(11) 98888-1001', 'contato@rocco.com.br', 'ativo'),
(4, 'Companhia das Letras', '(21) 97777-2002', 'contato@companhiadasletras.com.br', 'ativo'),
(5, 'Editora Intrínseca', '(31) 96666-3003', 'atendimento@intrinseca.com.br', 'ativo'),
(6, 'HarperCollins Brasil', '(41) 95555-4004', 'contato@harpercollins.com.br', 'ativo'),
(7, 'Editora Record', '(51) 94444-5005', 'contato@record.com.br', 'ativo'),
(8, 'Editora Sextante', '(61) 93333-6006', 'contato@sextante.com.br', 'ativo'),
(9, 'Editora Globo Livros', '(71) 92222-7007', 'contato@globolivros.com.br', 'ativo'),
(10, 'Editora Objetiva', '(81) 91111-8008', 'contato@objetiva.com.br', 'ativo'),
(11, 'Editora Martins Fontes', '(91) 90000-9009', 'editorial@martinsfontes.com.br', 'ativo'),
(12, 'Editora Aleph', '(11) 98888-1010', 'contato@editoraaleph.com.br', 'ativo'),
(13, 'Editora Zahar', '(21) 97777-2020', 'contato@zahar.com.br', 'ativo'),
(14, 'Editora L&PM', '(31) 96666-3030', 'contato@lpm.com.br', 'ativo'),
(15, 'Editora Nova Fronteira', '(41) 95555-4040', 'contato@novafronteira.com.br', 'ativo'),
(16, 'Editora Scipione', '(51) 94444-5050', 'contato@scipione.com.br', 'ativo'),
(17, 'Editora Saraiva', '(61) 93333-6060', 'contato@saraiva.com.br', 'ativo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `emprestimo`
--

CREATE TABLE `emprestimo` (
  `Cod_Emprestimo` int(11) NOT NULL,
  `Cod_Cliente` int(11) NOT NULL,
  `Cod_Livro` int(11) NOT NULL,
  `Data_Emprestimo` date NOT NULL,
  `Data_Devolucao` date NOT NULL,
  `Status_Emprestimo` enum('Pendente','Devolvido') DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Acionadores `emprestimo`
--
DELIMITER $$
CREATE TRIGGER `tr_emprestimo_delete_audit` BEFORE DELETE ON `emprestimo` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('emprestimo', 'DELETE', OLD.Cod_Emprestimo,
                     CONCAT('Cliente: ', OLD.Cod_Cliente, ', Livro: ', OLD.Cod_Livro, ', Data: ', OLD.Data_Emprestimo),
                     USER(), @ip_usuario);
         END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_emprestimo_insert_audit` AFTER INSERT ON `emprestimo` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('emprestimo', 'INSERT', NEW.Cod_Emprestimo, 
                     CONCAT('Cliente: ', NEW.Cod_Cliente, ', Livro: ', NEW.Cod_Livro, ', Data: ', NEW.Data_Emprestimo),
                     USER(), @ip_usuario);
         END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_emprestimo_update_audit` AFTER UPDATE ON `emprestimo` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('emprestimo', 'UPDATE', NEW.Cod_Emprestimo,
                     CONCAT('Cliente: ', OLD.Cod_Cliente, ', Livro: ', OLD.Cod_Livro, ', Data: ', OLD.Data_Emprestimo),
                     CONCAT('Cliente: ', NEW.Cod_Cliente, ', Livro: ', NEW.Cod_Livro, ', Data: ', NEW.Data_Emprestimo),
                     USER(), @ip_usuario);
         END
$$
DELIMITER ;

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
  `Senha_Temporaria` varchar(255) DEFAULT NULL,
  `Foto` longblob NOT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `funcionario`
--

INSERT INTO `funcionario` (`Cod_Funcionario`, `Cod_Perfil`, `Nome`, `CPF`, `Email`, `Sexo`, `Telefone`, `Data_Nascimento`, `Data_Efetivacao`, `CEP`, `UF`, `Cidade`, `Bairro`, `Rua`, `Num_Residencia`, `Usuario`, `Senha`, `Senha_Temporaria`, `Foto`, `status`) VALUES
(5, 1, 'Sérgio Luiz da Silveira', '123.456.789-10', 'sergioluiz@gmail.com', 'Masculino', '(47) 91234-5678', '1980-09-11', '2005-02-20', '80010-030', 'PR', 'Curitiba', 'Centro', 'Praça Rui Barbosa', 29, 'sergio_luiz', '12345678', NULL, '', 'ativo'),
(12, 4, 'Dwayne Johnson', '985.735.298-72', 'therock@gmail.com', 'Masculino', '(98) 46794-8766', '1972-04-02', '2025-09-03', '89220-618', 'SC', 'Joinville', 'Costa e Silva', 'Rua Pavão', 1234, 'the_rock', '12345678', NULL, 0x7468655f726f636b2e6a7067, 'ativo'),
(18, 1, 'Silvio Luiz de Souza', '783.464.837-68', 'silvioluis@gmail.com', 'Masculino', '(47) 9881-2356', '1990-06-23', '2025-09-04', '89202-300', 'SC', 'Joinville', 'Bucarein', 'Rua Coronel Procópio Gomes', 1234, 'silvio_souza', '12345678', NULL, 0x657465726e6f5f73696c76696f2e6a7067, 'ativo'),
(19, 5, 'Marcos Paulo', '847.987.336-79', 'marcospaulo@gmail.com', 'Masculino', '(47) 98748-6338', '2007-12-25', '2025-09-04', '89215-025', 'SC', 'Joinville', 'Morro do Meio', 'Estrada Rolf Walter Goll', 145, 'marcos_paulo', '12345678', NULL, 0x6d6172636f735f7064696464792e6a7067, 'ativo'),
(20, 5, 'Kim Sunoo', '910.383.000-21', 'kimsunoo@gmail.com', 'Masculino', '(47) 91736-5201', '2001-08-07', '2025-09-04', '01503-010', 'SP', 'São Paulo', 'Liberdade', 'Praça da Liberdade - Japão', 729, 'kim_sunoo', '12345678', NULL, 0x73756e6f6f2e6a7067, 'ativo'),
(21, 5, 'George Joji Miller', '989.820.982-35', 'joji@gmail.com', 'Masculino', '(47) 94718-4722', '1995-02-12', '2025-09-04', '89227-050', 'SC', 'Joinville', 'Iririú', 'Rua dos Estados Unidos', 666, 'george_miller', '12345678', NULL, 0x6a6f6a692e6a7067, 'ativo'),
(22, 4, 'Maria Xuxa Meneghel', '927.293.737-21', 'xuxa@gmail.com', 'Masculino', '(47) 98126-0253', '1965-04-18', '2025-09-04', '02976-250', 'SP', 'São Paulo', 'Vila Zat', 'Rua Serra do Cachimbo', 860, 'xuxa_meneghel', '12345678', NULL, 0x787578615f6e6f76612e6a7067, 'ativo'),
(23, 4, 'Gerard Way', '826.740.027-46', 'gerardway@gmail.com', 'Masculino', '(47) 98124-0567', '1990-10-31', '2025-09-04', '09351-350', 'SP', 'Mauá', 'Parque das Américas', 'Rua Nova Jersey', 6969, 'gerard_way', '123456789', NULL, 0x6765726172645f7761792e6a7067, 'ativo'),
(24, 4, 'Mason Thames', '285.729.572-83', 'masonthames@gmail.com', 'Masculino', '(47) 98292-7465', '2000-03-08', '2025-09-04', '89086-847', 'SC', 'Indaial', 'Estados', 'Rua Texas', 1234, 'mason_thames', '12345678', NULL, 0x6d61736f6e5f7468616d65732e6a7067, 'inativo'),
(25, 3, 'Taylor Lautner', '827.582.752-33', 'taylorlautner@gmail.com', 'Masculino', '(47) 98017-6524', '2002-07-29', '2025-09-04', '04566-000', 'SP', 'São Paulo', 'Cidade Monções', 'Rua Michigan', 7786, 'taylor_lautner', '12345678', NULL, 0x7461796c6f725f6c6175746e65722e6a7067, 'ativo');

--
-- Acionadores `funcionario`
--
DELIMITER $$
CREATE TRIGGER `tr_funcionario_delete_audit` BEFORE DELETE ON `funcionario` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('funcionario', 'DELETE', OLD.Cod_Funcionario,
                     CONCAT('Nome: ', OLD.Nome, ', Data Nascimento: ', OLD.Data_Nascimento),
                     USER(), @ip_usuario);
         END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_funcionario_insert_audit` AFTER INSERT ON `funcionario` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('funcionario', 'INSERT', NEW.Cod_Funcionario, 
                     CONCAT('Nome: ', NEW.Nome, ', Data Nascimento: ', NEW.Data_Nascimento),
                     USER(), @ip_usuario);
         END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_funcionario_update_audit` AFTER UPDATE ON `funcionario` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('funcionario', 'UPDATE', NEW.Cod_Funcionario,
                     CONCAT('Nome: ', OLD.Nome, ', Data Nascimento: ', OLD.Data_Nascimento),
                     CONCAT('Nome: ', NEW.Nome, ', Data Nascimento: ', NEW.Data_Nascimento),
                     USER(), @ip_usuario);
         END
$$
DELIMITER ;

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
(10, 'Autobiografia'),
(11, 'Infanto Juvenil');

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
  `Foto` longblob NOT NULL,
  `status` enum('ativo','inativo') DEFAULT 'ativo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `livro`
--

INSERT INTO `livro` (`Cod_Livro`, `Cod_Autor`, `Cod_Editora`, `Cod_Doador`, `Cod_Genero`, `Titulo`, `Data_Lancamento`, `Data_Registro`, `Quantidade`, `Num_Prateleira`, `Foto`, `status`) VALUES
(12, 21, 4, 9, 9, 'Harry Potter e a Pedra Filosofal', '1990-08-20', '2025-09-05', 10, '5', 0x3831696266596b34716d4c2e6a7067, 'ativo');

--
-- Acionadores `livro`
--
DELIMITER $$
CREATE TRIGGER `tr_livro_delete_audit` BEFORE DELETE ON `livro` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('livro', 'DELETE', OLD.Cod_Livro,
                     CONCAT('Título: ', OLD.Titulo),
                     @usuario_sistema, @ip_usuario);
         END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_livro_insert_audit` AFTER INSERT ON `livro` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('livro', 'INSERT', NEW.Cod_Livro, 
                     CONCAT('Título: ', NEW.Titulo),
                     @usuario_sistema, @ip_usuario);
         END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `tr_livro_update_audit` AFTER UPDATE ON `livro` FOR EACH ROW BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('livro', 'UPDATE', NEW.Cod_Livro,
                     CONCAT('Título: ', OLD.Titulo),
                     CONCAT('Título: ', NEW.Titulo),
                     @usuario_sistema, @ip_usuario);
         END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `logs_auditoria`
--

CREATE TABLE `logs_auditoria` (
  `id` int(11) NOT NULL,
  `tabela` varchar(50) NOT NULL,
  `operacao` enum('INSERT','UPDATE','DELETE') NOT NULL,
  `id_registro` int(11) NOT NULL,
  `dados_anteriores` text DEFAULT NULL,
  `dados_novos` text DEFAULT NULL,
  `usuario` varchar(100) DEFAULT NULL,
  `data_operacao` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_usuario` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `logs_auditoria`
--

INSERT INTO `logs_auditoria` (`id`, `tabela`, `operacao`, `id_registro`, `dados_anteriores`, `dados_novos`, `usuario`, `data_operacao`, `ip_usuario`) VALUES
(1, 'cliente', 'UPDATE', 4, 'Nome: TESTE, CPF: 287.352.976-28, Email: teste@gmail.com', 'Nome: TESTE, CPF: 287.352.976-28, Email: teste@gmail.com', 'root@localhost', '2025-09-02 17:26:24', NULL),
(2, 'cliente', 'UPDATE', 4, 'Nome: TESTE, CPF: 287.352.976-28, Email: teste@gmail.com', 'Nome: TESTE 3, CPF: 287.352.976-28, Email: teste@gmail.com', 'root@localhost', '2025-09-02 17:28:54', NULL),
(3, 'cliente', 'UPDATE', 4, 'Nome: TESTE 3, CPF: 287.352.976-28, Email: teste@gmail.com', 'Nome: TESTE 3, CPF: 287.352.976-28, Email: teste@gmail.com', 'root@localhost', '2025-09-02 17:29:45', NULL),
(4, 'cliente', 'UPDATE', 4, 'Nome: TESTE 3, CPF: 287.352.976-28, Email: teste@gmail.com', 'Nome: TESTE 3, CPF: 287.352.976-28, Email: teste@gmail.com', 'root@localhost', '2025-09-02 17:32:11', NULL),
(5, 'cliente', 'UPDATE', 2, 'Nome: Guilherme Vinicius Schwarz, CPF: 928.759.274-87, Email: guilhermevinicius@gmail.com', 'Nome: Guilherme Vinicius Schwarz, CPF: 928.759.274-87, Email: guilhermevinicius@gmail.com', 'root@localhost', '2025-09-02 17:32:50', NULL),
(6, 'cliente', 'UPDATE', 3, 'Nome: Ian Lucas Borba, CPF: 985.672.685-78, Email: ianlucas@gmail.com', 'Nome: Ian Lucas Borba, CPF: 985.672.685-78, Email: ianlucas@gmail.com', 'root@localhost', '2025-09-02 17:33:06', NULL),
(7, 'cliente', 'UPDATE', 2, 'Nome: Guilherme Vinicius Schwarz, CPF: 928.759.274-87, Email: guilhermevinicius@gmail.com', 'Nome: Guilherme Vinicius Schwarz, CPF: 928.759.274-87, Email: guilhermevinicius@gmail.com', 'root@localhost', '2025-09-02 17:36:12', NULL),
(8, 'cliente', 'UPDATE', 4, 'Nome: TESTE 3, CPF: 287.352.976-28, Email: teste@gmail.com', 'Nome: TESTE 3, CPF: 287.352.976-28, Email: teste@gmail.com', 'root@localhost', '2025-09-02 17:36:30', NULL),
(9, 'funcionario', 'INSERT', 8, NULL, 'Nome: Mason Thames, Data Nascimento: 2025-09-02', 'root@localhost', '2025-09-02 19:14:56', NULL),
(10, 'funcionario', 'INSERT', 9, NULL, 'Nome: Mason Thames, Data Nascimento: 2025-09-02', 'root@localhost', '2025-09-02 19:21:12', NULL),
(11, 'funcionario', 'INSERT', 10, NULL, 'Nome: Mariska, Data Nascimento: 1964-01-23', 'root@localhost', '2025-09-02 19:22:47', NULL),
(12, 'funcionario', 'INSERT', 11, NULL, 'Nome: Paula, Data Nascimento: 2025-09-02', 'root@localhost', '2025-09-02 19:31:45', NULL),
(13, 'cliente', 'INSERT', 5, NULL, 'Nome: Gerard Way, CPF: 104.163.459-56, Email: gerard@gmail.com', 'root@localhost', '2025-09-02 19:33:40', NULL),
(14, 'cliente', 'UPDATE', 5, 'Nome: Gerard Way, CPF: 104.163.459-56, Email: gerard@gmail.com', 'Nome: Gerard Way, CPF: 104.163.459-56, Email: gerard@gmail.com', 'root@localhost', '2025-09-02 19:39:38', NULL),
(15, 'cliente', 'INSERT', 6, NULL, 'Nome: Drake, CPF: 104.163.459-56, Email: drake@gmail.com', 'root@localhost', '2025-09-02 19:41:17', NULL),
(16, 'cliente', 'INSERT', 7, NULL, 'Nome: rihanna, CPF: 985.672.685-78, Email: rihanna@gmail.com', 'root@localhost', '2025-09-02 19:46:56', NULL),
(17, 'livro', 'INSERT', 4, NULL, 'Título: Five Nights', NULL, '2025-09-02 19:48:35', NULL),
(18, 'autor', 'INSERT', 14, NULL, 'Nome: Gerard, Telefone: (87) 53386-5862, Email: gerard@gmail.com', 'root@localhost', '2025-09-02 19:49:49', NULL),
(19, 'autor', 'INSERT', 15, NULL, 'Nome: ana, Telefone: (44) 44444-4444, Email: ana@gmail.com', 'root@localhost', '2025-09-02 19:51:32', NULL),
(20, 'autor', 'INSERT', 16, NULL, 'Nome: ana, Telefone: (44) 44444-4444, Email: ana@gmail.com', 'root@localhost', '2025-09-02 19:51:37', NULL),
(21, 'autor', 'INSERT', 17, NULL, 'Nome: anaaa, Telefone: (32) 33684-9384, Email: ana@gmail.com', 'root@localhost', '2025-09-02 19:51:48', NULL),
(22, 'autor', 'INSERT', 18, NULL, 'Nome: anaaa, Telefone: (32) 33684-9384, Email: ana@gmail.com', 'root@localhost', '2025-09-02 19:52:55', NULL),
(23, 'autor', 'INSERT', 19, NULL, 'Nome: Gerard, Telefone: (28) 47286-2786, Email: aaaaaaa@gmail.om', 'root@localhost', '2025-09-02 19:53:07', NULL),
(24, 'autor', 'DELETE', 15, 'Nome: ana, Telefone: (44) 44444-4444, Email: ana@gmail.com', NULL, 'root@localhost', '2025-09-02 20:03:54', NULL),
(25, 'livro', 'DELETE', 4, 'Título: Five Nights', NULL, NULL, '2025-09-02 20:04:14', NULL),
(26, 'funcionario', 'UPDATE', 7, 'Nome: Bruno Henrique Ribeiro, Data Nascimento: 2009-03-11', 'Nome: Bruno Henrique Ribeiro, Data Nascimento: 2009-03-11', 'root@localhost', '2025-09-03 16:41:02', NULL),
(27, 'funcionario', 'UPDATE', 7, 'Nome: Bruno Henrique Ribeiro, Data Nascimento: 2009-03-11', 'Nome: Bruno Henrique Ribeiro, Data Nascimento: 2007-03-11', 'root@localhost', '2025-09-03 16:41:02', NULL),
(28, 'livro', 'UPDATE', 1, 'Título: Harry Potter', 'Título: Harry Potter', NULL, '2025-09-03 16:57:19', NULL),
(29, 'cliente', 'UPDATE', 3, 'Nome: Ian Lucas Borba, CPF: 985.672.685-78, Email: ianlucas@gmail.com', 'Nome: Ian Lucas Borba, CPF: 985.672.685-78, Email: ianlucas@gmail.com', 'root@localhost', '2025-09-03 16:57:29', NULL),
(30, 'cliente', 'UPDATE', 5, 'Nome: Gerard Way, CPF: 104.163.459-56, Email: gerard@gmail.com', 'Nome: Gerard Way, CPF: 104.163.459-56, Email: gerard@gmail.com', 'root@localhost', '2025-09-03 16:57:49', NULL),
(31, 'funcionario', 'UPDATE', 8, 'Nome: Mason Thames, Data Nascimento: 2025-09-02', 'Nome: Mason Thames, Data Nascimento: 2025-09-02', 'root@localhost', '2025-09-03 16:58:06', NULL),
(32, 'funcionario', 'UPDATE', 8, 'Nome: Mason Thames, Data Nascimento: 2025-09-02', 'Nome: Mason Thames, Data Nascimento: 2007-09-02', 'root@localhost', '2025-09-03 16:58:06', NULL),
(33, 'livro', 'UPDATE', 1, 'Título: Harry Potter', 'Título: Harry Potter', NULL, '2025-09-03 16:58:12', NULL),
(34, 'funcionario', 'UPDATE', 8, 'Nome: Mason Thames, Data Nascimento: 2007-09-02', 'Nome: Mason Thames, Data Nascimento: 2007-09-02', 'root@localhost', '2025-09-03 16:58:35', NULL),
(35, 'funcionario', 'UPDATE', 8, 'Nome: Mason Thames, Data Nascimento: 2007-09-02', 'Nome: Mason Thames, Data Nascimento: 2007-09-02', 'root@localhost', '2025-09-03 16:58:35', NULL),
(36, 'cliente', 'UPDATE', 3, 'Nome: Ian Lucas Borba, CPF: 985.672.685-78, Email: ianlucas@gmail.com', 'Nome: Ian Lucas Borba, CPF: 985.672.685-78, Email: ianlucas@gmail.com', 'root@localhost', '2025-09-03 16:58:44', NULL),
(37, 'funcionario', 'DELETE', 9, 'Nome: Mason Thames, Data Nascimento: 2025-09-02', NULL, 'root@localhost', '2025-09-03 17:14:42', NULL),
(40, 'emprestimo', 'UPDATE', 1, 'Cliente: 2, Livro: 1, Data: 2025-08-13', 'Cliente: 2, Livro: 1, Data: 2025-08-13', 'root@localhost', '2025-09-03 17:43:28', NULL),
(41, 'emprestimo', 'UPDATE', 2, 'Cliente: 2, Livro: 1, Data: 2025-08-12', 'Cliente: 2, Livro: 1, Data: 2025-08-12', 'root@localhost', '2025-09-03 17:43:28', NULL),
(42, 'emprestimo', 'UPDATE', 3, 'Cliente: 2, Livro: 1, Data: 2025-08-12', 'Cliente: 2, Livro: 1, Data: 2025-08-12', 'root@localhost', '2025-09-03 17:43:28', NULL),
(43, 'emprestimo', 'UPDATE', 3, 'Cliente: 2, Livro: 1, Data: 2025-08-12', 'Cliente: 2, Livro: 1, Data: 2025-08-12', 'root@localhost', '2025-09-03 17:56:22', NULL),
(44, 'emprestimo', 'UPDATE', 3, 'Cliente: 2, Livro: 1, Data: 2025-08-12', 'Cliente: 2, Livro: 1, Data: 2025-08-12', 'root@localhost', '2025-09-03 17:56:24', NULL),
(45, 'emprestimo', 'UPDATE', 3, 'Cliente: 2, Livro: 1, Data: 2025-08-12', 'Cliente: 2, Livro: 1, Data: 2025-08-12', 'root@localhost', '2025-09-03 17:56:26', NULL),
(46, 'livro', 'UPDATE', 1, 'Título: Harry Potter', 'Título: Harry Potter', NULL, '2025-09-03 17:56:33', NULL),
(47, 'emprestimo', 'UPDATE', 3, 'Cliente: 2, Livro: 1, Data: 2025-08-12', 'Cliente: 2, Livro: 1, Data: 2025-08-12', 'root@localhost', '2025-09-03 17:56:33', NULL),
(48, 'funcionario', 'INSERT', 12, NULL, 'Nome: Dwayne Johnson, Data Nascimento: 1972-04-02', 'root@localhost', '2025-09-03 18:59:20', NULL),
(49, 'livro', 'INSERT', 5, NULL, 'Título: Amor & Gelato', NULL, '2025-09-03 19:15:48', NULL),
(50, 'livro', 'DELETE', 5, 'Título: Amor & Gelato', NULL, NULL, '2025-09-03 19:17:37', NULL),
(51, 'funcionario', 'UPDATE', 7, 'Nome: Bruno Henrique Ribeiro, Data Nascimento: 2007-03-11', 'Nome: Bruno Henrique Ribeiro, Data Nascimento: 2007-03-11', 'root@localhost', '2025-09-03 22:28:51', NULL),
(52, 'funcionario', 'UPDATE', 8, 'Nome: Mason Thames, Data Nascimento: 2007-09-02', 'Nome: Mason Thames, Data Nascimento: 2007-09-02', 'root@localhost', '2025-09-03 22:28:59', NULL),
(53, 'funcionario', 'UPDATE', 10, 'Nome: Mariska, Data Nascimento: 1964-01-23', 'Nome: Mariska, Data Nascimento: 1964-01-23', 'root@localhost', '2025-09-03 22:29:05', NULL),
(54, 'funcionario', 'UPDATE', 11, 'Nome: Paula, Data Nascimento: 2025-09-02', 'Nome: Paula, Data Nascimento: 2025-09-02', 'root@localhost', '2025-09-03 22:29:11', NULL),
(55, 'funcionario', 'INSERT', 13, NULL, 'Nome: Lara Gorito Barbosa De Souza, Data Nascimento: 2007-02-05', 'root@localhost', '2025-09-04 00:29:32', NULL),
(56, 'funcionario', 'INSERT', 14, NULL, 'Nome: Lara Gorito Barbosa De Souza, Data Nascimento: 2007-02-05', 'root@localhost', '2025-09-04 00:42:51', NULL),
(57, 'funcionario', 'INSERT', 15, NULL, 'Nome: Lara Gorito Barbosa De Souza, Data Nascimento: 2007-02-05', 'root@localhost', '2025-09-04 00:43:09', NULL),
(58, 'funcionario', 'INSERT', 16, NULL, 'Nome: TESTE, Data Nascimento: 2000-07-06', 'root@localhost', '2025-09-04 00:44:16', NULL),
(59, 'funcionario', 'DELETE', 15, 'Nome: Lara Gorito Barbosa De Souza, Data Nascimento: 2007-02-05', NULL, 'root@localhost', '2025-09-04 00:44:31', NULL),
(60, 'funcionario', 'DELETE', 14, 'Nome: Lara Gorito Barbosa De Souza, Data Nascimento: 2007-02-05', NULL, 'root@localhost', '2025-09-04 00:44:36', NULL),
(61, 'funcionario', 'DELETE', 13, 'Nome: Lara Gorito Barbosa De Souza, Data Nascimento: 2007-02-05', NULL, 'root@localhost', '2025-09-04 00:44:56', NULL),
(62, 'funcionario', 'INSERT', 17, NULL, 'Nome: Lara Gorito Barbosa De Souza, Data Nascimento: 2000-02-05', 'root@localhost', '2025-09-04 00:59:23', NULL),
(63, 'funcionario', 'DELETE', 17, 'Nome: Lara Gorito Barbosa De Souza, Data Nascimento: 2000-02-05', NULL, 'root@localhost', '2025-09-04 00:59:38', NULL),
(64, 'funcionario', 'UPDATE', 10, 'Nome: Mariska, Data Nascimento: 1964-01-23', 'Nome: Mariska, Data Nascimento: 1964-01-23', 'root@localhost', '2025-09-04 01:49:23', NULL),
(65, 'funcionario', 'UPDATE', 10, 'Nome: Mariska, Data Nascimento: 1964-01-23', 'Nome: Mariska, Data Nascimento: 1964-01-23', 'root@localhost', '2025-09-04 01:50:20', NULL),
(66, 'funcionario', 'UPDATE', 10, 'Nome: Mariska, Data Nascimento: 1964-01-23', 'Nome: Mariska, Data Nascimento: 1964-01-23', 'root@localhost', '2025-09-04 01:51:08', NULL),
(67, 'funcionario', 'UPDATE', 10, 'Nome: Mariska, Data Nascimento: 1964-01-23', 'Nome: Mariska, Data Nascimento: 1964-01-23', 'root@localhost', '2025-09-04 02:01:59', NULL),
(68, 'funcionario', 'UPDATE', 10, 'Nome: Mariska, Data Nascimento: 1964-01-23', 'Nome: Mariska, Data Nascimento: 1964-01-23', 'root@localhost', '2025-09-04 02:02:30', NULL),
(69, 'funcionario', 'DELETE', 16, 'Nome: TESTE, Data Nascimento: 2000-07-06', NULL, 'root@localhost', '2025-09-04 16:47:22', NULL),
(70, 'cliente', 'DELETE', 4, 'Nome: TESTE 3, CPF: 287.352.976-28, Email: teste@gmail.com', NULL, 'root@localhost', '2025-09-04 16:47:29', NULL),
(71, 'cliente', 'DELETE', 7, 'Nome: rihanna, CPF: 985.672.685-78, Email: rihanna@gmail.com', NULL, 'root@localhost', '2025-09-04 16:47:50', NULL),
(72, 'funcionario', 'INSERT', 18, NULL, 'Nome: Silvio Luiz de Souza, Data Nascimento: 1990-06-23', 'root@localhost', '2025-09-04 17:22:21', NULL),
(73, 'funcionario', 'DELETE', 8, 'Nome: Mason Thames, Data Nascimento: 2007-09-02', NULL, 'root@localhost', '2025-09-04 17:23:23', NULL),
(74, 'funcionario', 'INSERT', 19, NULL, 'Nome: Marcos Paulo, Data Nascimento: 2007-12-25', 'root@localhost', '2025-09-04 17:31:15', NULL),
(75, 'funcionario', 'INSERT', 20, NULL, 'Nome: Kim Sunoo, Data Nascimento: 2001-08-07', 'root@localhost', '2025-09-04 17:44:23', NULL),
(76, 'funcionario', 'INSERT', 21, NULL, 'Nome: George Joji Miller, Data Nascimento: 1995-02-12', 'root@localhost', '2025-09-04 17:47:20', NULL),
(77, 'funcionario', 'INSERT', 22, NULL, 'Nome: Maria Xuxa Meneghel, Data Nascimento: 1965-04-18', 'root@localhost', '2025-09-04 17:51:59', NULL),
(78, 'cliente', 'DELETE', 5, 'Nome: Gerard Way, CPF: 104.163.459-56, Email: gerard@gmail.com', NULL, 'root@localhost', '2025-09-04 17:52:33', NULL),
(79, 'funcionario', 'INSERT', 23, NULL, 'Nome: Gerard Way, Data Nascimento: 1990-10-31', 'root@localhost', '2025-09-04 17:56:57', NULL),
(80, 'funcionario', 'INSERT', 24, NULL, 'Nome: Mason Thames, Data Nascimento: 2000-03-08', 'root@localhost', '2025-09-04 17:59:39', NULL),
(81, 'funcionario', 'INSERT', 25, NULL, 'Nome: Taylor Lautner, Data Nascimento: 2002-07-29', 'root@localhost', '2025-09-04 18:03:48', NULL),
(82, 'funcionario', 'DELETE', 7, 'Nome: Bruno Henrique Ribeiro, Data Nascimento: 2007-03-11', NULL, 'root@localhost', '2025-09-04 18:39:05', NULL),
(83, 'funcionario', 'DELETE', 10, 'Nome: Mariska, Data Nascimento: 1964-01-23', NULL, 'root@localhost', '2025-09-04 18:39:12', NULL),
(84, 'funcionario', 'DELETE', 11, 'Nome: Paula, Data Nascimento: 2025-09-02', NULL, 'root@localhost', '2025-09-04 18:39:21', NULL),
(85, 'cliente', 'UPDATE', 3, 'Nome: Ian Lucas Borba, CPF: 985.672.685-78, Email: ianlucas@gmail.com', 'Nome: Ian Lucas Borba, CPF: 985.672.685-78, Email: ianlucas@gmail.com', 'root@localhost', '2025-09-04 18:46:01', NULL),
(86, 'cliente', 'DELETE', 6, 'Nome: Drake, CPF: 104.163.459-56, Email: drake@gmail.com', NULL, 'root@localhost', '2025-09-04 18:46:30', NULL),
(87, 'cliente', 'UPDATE', 2, 'Nome: Guilherme Vinicius Schwarz, CPF: 928.759.274-87, Email: guilhermevinicius@gmail.com', 'Nome: Guilherme Vinicius Schwarz, CPF: 928.759.274-87, Email: guilhermevinicius@gmail.com', 'root@localhost', '2025-09-04 18:46:47', NULL),
(90, 'autor', 'DELETE', 2, 'Nome: Mason Thames, Telefone: (99) 99999-9999, Email: masonthames@gmail.com', NULL, 'root@localhost', '2025-09-04 18:48:56', NULL),
(91, 'autor', 'DELETE', 16, 'Nome: ana, Telefone: (44) 44444-4444, Email: ana@gmail.com', NULL, 'root@localhost', '2025-09-04 18:48:58', NULL),
(92, 'autor', 'DELETE', 17, 'Nome: anaaa, Telefone: (32) 33684-9384, Email: ana@gmail.com', NULL, 'root@localhost', '2025-09-04 18:49:00', NULL),
(93, 'autor', 'DELETE', 18, 'Nome: anaaa, Telefone: (32) 33684-9384, Email: ana@gmail.com', NULL, 'root@localhost', '2025-09-04 18:49:02', NULL),
(94, 'autor', 'DELETE', 14, 'Nome: Gerard, Telefone: (87) 53386-5862, Email: gerard@gmail.com', NULL, 'root@localhost', '2025-09-04 18:49:04', NULL),
(95, 'autor', 'DELETE', 19, 'Nome: Gerard, Telefone: (28) 47286-2786, Email: aaaaaaa@gmail.om', NULL, 'root@localhost', '2025-09-04 18:49:06', NULL),
(97, 'cliente', 'INSERT', 8, NULL, 'Nome: João Vitor Atanazio, CPF: 182.648.267-47, Email: joaovitor@gmail.com', 'root@localhost', '2025-09-04 18:58:54', NULL),
(99, 'cliente', 'INSERT', 9, NULL, 'Nome: Gustavo Tobler, CPF: 832.675.782-67, Email: gustavotobler@gmail.com', 'root@localhost', '2025-09-04 19:12:09', NULL),
(100, 'cliente', 'INSERT', 10, NULL, 'Nome: Matheus Henrique Dela, CPF: 873.258.275-28, Email: matheushenrique@gmail.com', 'root@localhost', '2025-09-04 19:16:38', NULL),
(101, 'cliente', 'INSERT', 11, NULL, 'Nome: Tatiane Vieira, CPF: 283.728.628-65, Email: tatianevieira@gmail.com', 'root@localhost', '2025-09-04 19:23:30', NULL),
(102, 'cliente', 'INSERT', 12, NULL, 'Nome: Drake, CPF: 832.782.576-29, Email: drake@gmail.com', 'root@localhost', '2025-09-04 19:34:07', NULL),
(103, 'funcionario', 'UPDATE', 24, 'Nome: Mason Thames, Data Nascimento: 2000-03-08', 'Nome: Mason Thames, Data Nascimento: 2000-03-08', 'root@localhost', '2025-09-04 22:43:03', NULL),
(104, 'cliente', 'UPDATE', 12, 'Nome: Drake, CPF: 832.782.576-29, Email: drake@gmail.com', 'Nome: Drake, CPF: 832.782.576-29, Email: drake@gmail.com', 'root@localhost', '2025-09-04 22:50:04', NULL),
(105, 'cliente', 'UPDATE', 12, 'Nome: Drake, CPF: 832.782.576-29, Email: drake@gmail.com', 'Nome: Drake, CPF: 832.782.576-29, Email: drake@gmail.com', 'root@localhost', '2025-09-04 22:50:13', NULL),
(106, 'cliente', 'UPDATE', 12, 'Nome: Drake, CPF: 832.782.576-29, Email: drake@gmail.com', 'Nome: Drake, CPF: 832.782.576-20, Email: drake@gmail.com', 'root@localhost', '2025-09-04 23:15:49', NULL),
(107, 'cliente', 'UPDATE', 12, 'Nome: Drake, CPF: 832.782.576-20, Email: drake@gmail.com', 'Nome: Drake, CPF: 832.782.576-24, Email: drake@gmail.com', 'root@localhost', '2025-09-04 23:16:34', NULL),
(108, 'cliente', 'UPDATE', 12, 'Nome: Drake, CPF: 832.782.576-24, Email: drake@gmail.com', 'Nome: Drake, CPF: 832.782.576-24, Email: drake@gmail.com', 'root@localhost', '2025-09-04 23:17:57', NULL),
(109, 'cliente', 'UPDATE', 12, 'Nome: Drake, CPF: 832.782.576-24, Email: drake@gmail.com', 'Nome: Drake, CPF: 832.782.576-24, Email: drake@gmail.com', 'root@localhost', '2025-09-04 23:18:18', NULL),
(112, 'emprestimo', 'DELETE', 1, 'Cliente: 2, Livro: 1, Data: 2025-08-13', NULL, 'root@localhost', '2025-09-04 23:19:49', NULL),
(113, 'emprestimo', 'DELETE', 2, 'Cliente: 2, Livro: 1, Data: 2025-08-12', NULL, 'root@localhost', '2025-09-04 23:19:49', NULL),
(114, 'emprestimo', 'DELETE', 3, 'Cliente: 2, Livro: 1, Data: 2025-08-12', NULL, 'root@localhost', '2025-09-04 23:19:49', NULL),
(115, 'cliente', 'DELETE', 2, 'Nome: Guilherme Vinicius Schwarz, CPF: 928.759.274-87, Email: guilhermevinicius@gmail.com', NULL, 'root@localhost', '2025-09-04 23:19:55', NULL),
(116, 'cliente', 'INSERT', 13, NULL, 'Nome: Helena Lopes, CPF: 298.237.484-35, Email: helenalopes@gmail.com', 'root@localhost', '2025-09-04 23:28:59', NULL),
(117, 'funcionario', 'UPDATE', 5, 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'root@localhost', '2025-09-04 23:31:09', NULL),
(118, 'funcionario', 'UPDATE', 5, 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'root@localhost', '2025-09-04 23:31:09', NULL),
(119, 'funcionario', 'UPDATE', 5, 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'root@localhost', '2025-09-04 23:31:33', NULL),
(120, 'funcionario', 'UPDATE', 5, 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'root@localhost', '2025-09-04 23:31:33', NULL),
(121, 'funcionario', 'UPDATE', 5, 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'root@localhost', '2025-09-04 23:34:54', NULL),
(122, 'funcionario', 'UPDATE', 5, 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'root@localhost', '2025-09-04 23:34:54', NULL),
(123, 'funcionario', 'UPDATE', 19, 'Nome: Marcos Paulo, Data Nascimento: 2007-12-25', 'Nome: Marcos Paulo, Data Nascimento: 2007-12-25', 'root@localhost', '2025-09-04 23:35:17', NULL),
(124, 'funcionario', 'UPDATE', 19, 'Nome: Marcos Paulo, Data Nascimento: 2007-12-25', 'Nome: Marcos Paulo, Data Nascimento: 2007-12-25', 'root@localhost', '2025-09-04 23:35:17', NULL),
(125, 'cliente', 'UPDATE', 12, 'Nome: Drake, CPF: 832.782.576-24, Email: drake@gmail.com', 'Nome: Drake, CPF: 832.782.576-24, Email: drake@gmail.com', 'root@localhost', '2025-09-04 23:36:43', NULL),
(126, 'cliente', 'UPDATE', 12, 'Nome: Drake, CPF: 832.782.576-24, Email: drake@gmail.com', 'Nome: Drake, CPF: 832.782.576-24, Email: drake@gmail.com', 'root@localhost', '2025-09-04 23:37:00', NULL),
(127, 'funcionario', 'UPDATE', 5, 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'root@localhost', '2025-09-04 23:40:01', NULL),
(128, 'funcionario', 'UPDATE', 5, 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'Nome: Sérgio Luiz da Silveira, Data Nascimento: 1980-09-11', 'root@localhost', '2025-09-04 23:40:01', NULL),
(129, 'livro', 'UPDATE', 1, 'Título: Harry Potter', 'Título: Harry Potter', NULL, '2025-09-05 16:40:02', NULL),
(130, 'livro', 'UPDATE', 1, 'Título: Harry Potter', 'Título: Harry Potter', NULL, '2025-09-05 16:40:16', NULL),
(131, 'livro', 'UPDATE', 1, 'Título: Harry Potter', 'Título: Harry Potter', NULL, '2025-09-05 16:41:46', NULL),
(132, 'livro', 'UPDATE', 1, 'Título: Harry Potter', 'Título: Harry Potter', NULL, '2025-09-05 16:41:53', NULL),
(133, 'livro', 'UPDATE', 1, 'Título: Harry Potter', 'Título: Harry Potter', NULL, '2025-09-05 16:43:45', NULL),
(134, 'autor', 'INSERT', 20, NULL, 'Nome: Jenna Evans Welch, Telefone: (47) 98266-3527, Email: jennaevans@gmail.com', 'root@localhost', '2025-09-05 16:55:57', NULL),
(135, 'autor', 'INSERT', 21, NULL, 'Nome: J.K. Rowling, Telefone: (11) 99999-1234, Email: jkrowling@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(136, 'autor', 'INSERT', 22, NULL, 'Nome: George R.R. Martin, Telefone: (21) 98888-5678, Email: grrmartin@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(137, 'autor', 'INSERT', 23, NULL, 'Nome: Stephen King, Telefone: (31) 97777-4321, Email: sking@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(138, 'autor', 'INSERT', 24, NULL, 'Nome: Agatha Christie, Telefone: (41) 96666-9876, Email: achristie@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(139, 'autor', 'INSERT', 25, NULL, 'Nome: J.R.R. Tolkien, Telefone: (51) 95555-3456, Email: jrrtolkien@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(140, 'autor', 'INSERT', 26, NULL, 'Nome: Dan Brown, Telefone: (61) 94444-6789, Email: danbrown@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(141, 'autor', 'INSERT', 27, NULL, 'Nome: Paulo Coelho, Telefone: (71) 93333-1122, Email: pcoelho@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(142, 'autor', 'INSERT', 28, NULL, 'Nome: Isaac Asimov, Telefone: (81) 92222-3344, Email: iasimov@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(143, 'autor', 'INSERT', 29, NULL, 'Nome: Arthur Conan Doyle, Telefone: (91) 91111-5566, Email: acdoyle@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(144, 'autor', 'INSERT', 30, NULL, 'Nome: Harper Lee, Telefone: (21) 98888-8899, Email: hlee@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(145, 'autor', 'INSERT', 31, NULL, 'Nome: Ernest Hemingway, Telefone: (31) 97777-9900, Email: ehemingway@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(146, 'autor', 'INSERT', 32, NULL, 'Nome: Gabriel García Márquez, Telefone: (41) 96666-0011, Email: ggmarquez@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(147, 'autor', 'INSERT', 33, NULL, 'Nome: Clarice Lispector, Telefone: (51) 95555-2233, Email: clispector@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(148, 'autor', 'INSERT', 34, NULL, 'Nome: Machado de Assis, Telefone: (61) 94444-3344, Email: machado@email.com', 'root@localhost', '2025-09-05 16:58:36', NULL),
(149, 'autor', 'UPDATE', 34, 'Nome: Machado de Assis, Telefone: (61) 94444-3344, Email: machado@email.com', 'Nome: Machado de Assis, Telefone: (61) 94444-3344, Email: machado@email.com', 'root@localhost', '2025-09-05 17:02:36', NULL),
(150, 'autor', 'UPDATE', 25, 'Nome: J.R.R. Tolkien, Telefone: (51) 95555-3456, Email: jrrtolkien@email.com', 'Nome: J.R.R. Tolkien, Telefone: (51) 95555-3456, Email: jrrtolkien@email.com', 'root@localhost', '2025-09-05 17:02:48', NULL),
(151, 'autor', 'UPDATE', 28, 'Nome: Isaac Asimov, Telefone: (81) 92222-3344, Email: iasimov@email.com', 'Nome: Isaac Asimov, Telefone: (81) 92222-3344, Email: iasimov@email.com', 'root@localhost', '2025-09-05 17:02:58', NULL),
(152, 'livro', 'INSERT', 8, NULL, 'Título: AAAAAAAAAAAAAAAAA', NULL, '2025-09-05 17:14:56', NULL),
(153, 'livro', 'DELETE', 8, 'Título: AAAAAAAAAAAAAAAAA', NULL, NULL, '2025-09-05 17:15:15', NULL),
(154, 'livro', 'DELETE', 1, 'Título: Harry Potter', NULL, NULL, '2025-09-05 17:24:03', NULL),
(155, 'livro', 'INSERT', 12, NULL, 'Título: Harry Potter e a Pedra Filosofal', NULL, '2025-09-05 17:25:07', NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `multa`
--

CREATE TABLE `multa` (
  `Cod_Multa` int(11) NOT NULL,
  `Cod_Emprestimo` int(11) NOT NULL,
  `Data_Multa` date NOT NULL,
  `Valor_Multa` decimal(10,2) NOT NULL,
  `Status_Multa` enum('Pendente','Paga') DEFAULT 'Pendente'
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
-- Índices de tabela `logs_auditoria`
--
ALTER TABLE `logs_auditoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_data_operacao` (`data_operacao`),
  ADD KEY `idx_tabela` (`tabela`),
  ADD KEY `idx_operacao` (`operacao`);

--
-- Índices de tabela `multa`
--
ALTER TABLE `multa`
  ADD PRIMARY KEY (`Cod_Multa`),
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
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `autor`
--
ALTER TABLE `autor`
  MODIFY `Cod_Autor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de tabela `cliente`
--
ALTER TABLE `cliente`
  MODIFY `Cod_Cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `doador`
--
ALTER TABLE `doador`
  MODIFY `Cod_Doador` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `editora`
--
ALTER TABLE `editora`
  MODIFY `Cod_Editora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  MODIFY `Cod_Emprestimo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `Cod_Funcionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `genero`
--
ALTER TABLE `genero`
  MODIFY `Cod_Genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `livro`
--
ALTER TABLE `livro`
  MODIFY `Cod_Livro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de tabela `logs_auditoria`
--
ALTER TABLE `logs_auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=156;

--
-- AUTO_INCREMENT de tabela `multa`
--
ALTER TABLE `multa`
  MODIFY `Cod_Multa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  ADD CONSTRAINT `FK_Multa_Emprestimo` FOREIGN KEY (`Cod_Emprestimo`) REFERENCES `emprestimo` (`Cod_Emprestimo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
