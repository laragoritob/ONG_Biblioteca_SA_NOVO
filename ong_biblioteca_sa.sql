-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16/09/2025 às 19:15
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
(34, 'Machado de Assis', '(61) 98441-3344', 'machado@email.com', 'ativo');

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
(17, 'Editora Saraiva', '(61) 93333-6060', 'contato@saraiva.com.br', 'ativo'),
(18, 'Suma', '(47) 92837-7382', 'suma@gmail.com', 'ativo'),
(20, 'Paralela', '(47) 63627-2637', 'paralela@gmail.com', 'ativo'),
(21, 'Princips', '(47) 92877-3882', 'princips@gmail.com', 'ativo');

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
  `Data_Ultima_Renovacao` date DEFAULT NULL,
  `Status_Emprestimo` enum('Pendente','Devolvido') DEFAULT 'Pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `emprestimo`
--

INSERT INTO `emprestimo` (`Cod_Emprestimo`, `Cod_Cliente`, `Cod_Livro`, `Data_Emprestimo`, `Data_Devolucao`, `Data_Ultima_Renovacao`, `Status_Emprestimo`) VALUES
(4, 8, 15, '2025-09-08', '2025-09-14', NULL, 'Devolvido'),
(5, 9, 14, '2025-09-08', '2025-09-28', NULL, 'Devolvido'),
(6, 10, 20, '2025-09-09', '2025-09-15', NULL, 'Pendente'),
(7, 13, 14, '2025-09-09', '2025-09-15', NULL, 'Pendente'),
(8, 12, 23, '2025-09-01', '2025-09-08', NULL, 'Devolvido');

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
(11, 'Infanto Juvenil'),
(12, 'Thriller'),
(13, 'Mistério');

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
(12, 21, 4, 9, 9, 'Harry Potter e a Pedra Filosofal', '1990-08-20', '2025-09-05', 10, '5', 0x3831696266596b34716d4c2e6a7067, 'ativo'),
(14, 20, 5, 9, 3, 'Amor & Gelato', '2016-06-03', '2025-09-05', 9, '8', 0x30373165333764342d643862322d343665372d393039652d3333333934656335636664392e6a7067, 'ativo'),
(15, 23, 17, 15, 6, 'It a Coisa', '1986-09-15', '2025-09-05', 15, '9', 0x69745f636f6973612e6a7067, 'ativo'),
(16, 26, 8, 14, 13, 'O Código da Vinci', '2006-06-19', '2025-09-05', 1, '14', 0x37316d617277582b6c794c2e5f5546313030302c313030305f514c38305f2e6a7067, 'ativo'),
(17, 1, 3, 19, 5, 'Jogos Vorazes I', '2012-02-15', '2025-09-09', 15, '09', 0x6a6f676f735f766f72617a65732e6a7067, 'ativo'),
(18, 22, 18, 15, 9, 'Fogo & Sangue I', '2018-11-20', '2025-09-09', 12, '17', 0x666f676f5f655f73616e6775652e6a7067, 'ativo'),
(20, 25, 6, 19, 3, 'O Hobbit', '2019-07-15', '2025-09-09', 12, '2', '', 'ativo'),
(21, 27, 20, 5, 3, 'Brida', '2017-07-05', '2025-09-09', 5, '08', '', 'ativo'),
(22, 28, 12, 13, 5, 'O Fim da Eternidade', '2019-09-19', '2025-09-09', 6, '20', 0x6f46696d6461457465726e69646164652e77656270, 'ativo'),
(23, 34, 21, 7, 3, 'Dom Casmurro ', '2019-05-02', '2025-09-09', 13, '23', 0x646f6d4361736d7572726f2e77656270, 'ativo'),
(27, 29, 7, NULL, 1, 'Estação Final', '2018-11-23', '2025-09-15', 19, '0', 0x646f6d4361736d7572726f2e77656270, 'ativo'),
(28, 30, 8, NULL, 2, 'Caminho de Volta', '2022-12-06', '2025-09-15', 6, '1', 0x6f636f727469636f2e6a7067, 'ativo'),
(29, 31, 9, NULL, 3, 'Pétalas ao Chão', '2018-06-30', '2025-09-15', 7, '2', 0x6f70657175656e6f7072696e636970652e6a7067, 'ativo'),
(30, 32, 10, NULL, 4, 'No Coração da Floresta', '2025-04-12', '2025-09-15', 11, '3', 0x6a6f676f735f766f72617a65732e6a7067, 'ativo'),
(31, 33, 11, NULL, 5, 'Céu de Outono', '2016-01-26', '2025-09-15', 3, '4', 0x69745f636f6973612e6a7067, 'ativo'),
(32, 34, 12, NULL, 6, 'A Casa ao Lado', '2021-02-04', '2025-09-15', 18, '5', 0x666f676f5f655f73616e6775652e6a7067, 'ativo'),
(33, 1, 13, NULL, 7, 'Um Lugar no Mundo', '2023-02-03', '2025-09-15', 20, '6', 0x76616d7069726f732e77656270, 'ativo'),
(34, 20, 14, NULL, 8, 'O Eco e a Montanha', '2016-04-30', '2025-09-15', 19, '7', 0x6f46696d6461457465726e69646164652e77656270, 'ativo'),
(35, 21, 15, NULL, 9, 'Velas ao Leste', '2021-03-02', '2025-09-15', 4, '8', 0x6f6c6976726f64616d6174656d61746963612e6a7067, 'ativo'),
(36, 22, 16, NULL, 10, 'Rosa dos Ventos', '2023-12-23', '2025-09-15', 6, '9', 0x70617261746f646f736f736761726f746f737175656a61616d65692e6a7067, 'ativo'),
(37, 23, 17, NULL, 11, 'Canções de Areia', '2025-07-30', '2025-09-15', 16, '10', 0x62726964612e6a7067, 'ativo'),
(38, 24, 18, NULL, 12, 'O Livro das Marés', '2023-05-02', '2025-09-15', 3, '11', 0x68656c656e612e6a7067, 'ativo'),
(39, 25, 20, NULL, 13, 'Viagem ao Centro do Dia', '2024-09-22', '2025-09-15', 7, '12', 0x6c6976726f312e6a7067, 'ativo'),
(40, 26, 21, NULL, 1, 'Passos na Neve', '2024-09-07', '2025-09-15', 15, '13', 0x646f6d4361736d7572726f2e77656270, 'ativo'),
(41, 27, 1, NULL, 2, 'Silêncio das Rochas', '2016-10-22', '2025-09-15', 10, '14', 0x6f636f727469636f2e6a7067, 'ativo'),
(42, 28, 2, NULL, 3, 'Entre Dois Rios', '2017-12-31', '2025-09-15', 5, '15', 0x6f70657175656e6f7072696e636970652e6a7067, 'ativo'),
(43, 29, 3, NULL, 4, 'O Sinal da Colina', '2021-11-13', '2025-09-15', 1, '16', 0x6a6f676f735f766f72617a65732e6a7067, 'ativo'),
(44, 30, 4, NULL, 5, 'Brisa de Abril', '2024-03-21', '2025-09-15', 19, '17', 0x69745f636f6973612e6a7067, 'ativo'),
(45, 31, 5, NULL, 6, 'A Última Estrada', '2016-03-16', '2025-09-15', 7, '18', 0x666f676f5f655f73616e6775652e6a7067, 'ativo'),
(46, 32, 6, NULL, 7, 'As Quatro Estações', '2017-12-27', '2025-09-15', 8, '19', 0x76616d7069726f732e77656270, 'ativo'),
(47, 33, 7, NULL, 8, 'Pássaros de Fogo', '2021-04-15', '2025-09-15', 16, '20', 0x6f46696d6461457465726e69646164652e77656270, 'ativo'),
(48, 34, 8, NULL, 9, 'A Torre e o Mar', '2019-12-27', '2025-09-15', 17, '21', 0x6f6c6976726f64616d6174656d61746963612e6a7067, 'ativo'),
(49, 1, 9, NULL, 10, 'Rastros na Terra', '2023-12-28', '2025-09-15', 14, '22', 0x70617261746f646f736f736761726f746f737175656a61616d65692e6a7067, 'ativo'),
(50, 20, 10, NULL, 11, 'Vento sobre a Planície', '2017-09-26', '2025-09-15', 13, '23', 0x62726964612e6a7067, 'ativo'),
(51, 1, 1, NULL, 1, 'Caminhos do Horizonte', '2022-06-08', '2025-09-15', 17, '0', 0x646f6d4361736d7572726f2e77656270, 'ativo'),
(52, 20, 2, NULL, 2, 'Marcas do Tempo', '2017-12-23', '2025-09-15', 12, '1', 0x6f636f727469636f2e6a7067, 'ativo'),
(53, 21, 3, NULL, 3, 'Sombras de Pedra', '2017-08-17', '2025-09-15', 7, '2', 0x6f70657175656e6f7072696e636970652e6a7067, 'ativo'),
(54, 22, 4, NULL, 4, 'Rumo ao Norte', '2023-03-01', '2025-09-15', 6, '3', 0x6a6f676f735f766f72617a65732e6a7067, 'ativo'),
(55, 23, 5, NULL, 5, 'Pássaros no Inverno', '2024-11-01', '2025-09-15', 2, '4', 0x69745f636f6973612e6a7067, 'ativo'),
(56, 24, 6, NULL, 6, 'Dança das Folhas', '2020-12-13', '2025-09-15', 4, '5', 0x666f676f5f655f73616e6775652e6a7067, 'ativo'),
(57, 25, 7, NULL, 7, 'O Livro do Vento', '2025-09-04', '2025-09-15', 17, '6', 0x76616d7069726f732e77656270, 'ativo'),
(58, 26, 8, NULL, 8, 'Sussurros do Mar', '2023-10-06', '2025-09-15', 10, '7', 0x6f46696d6461457465726e69646164652e77656270, 'ativo'),
(59, 27, 9, NULL, 9, 'Colheita de Estrelas', '2022-12-19', '2025-09-15', 17, '8', 0x6f6c6976726f64616d6174656d61746963612e6a7067, 'ativo'),
(60, 28, 10, NULL, 10, 'A Estrada de Casa', '2020-06-20', '2025-09-15', 1, '9', 0x70617261746f646f736f736761726f746f737175656a61616d65692e6a7067, 'ativo'),
(61, 29, 11, NULL, 11, 'Entre Montanhas', '2020-10-28', '2025-09-15', 19, '10', 0x62726964612e6a7067, 'ativo'),
(62, 30, 12, NULL, 12, 'Raio de Luar', '2019-01-16', '2025-09-15', 7, '11', 0x68656c656e612e6a7067, 'ativo'),
(63, 31, 13, NULL, 13, 'A Janela e o Rio', '2015-11-29', '2025-09-15', 10, '12', 0x6c6976726f312e6a7067, 'ativo'),
(64, 32, 14, NULL, 1, 'Trilho Antigo', '2025-05-06', '2025-09-15', 5, '13', 0x646f6d4361736d7572726f2e77656270, 'ativo'),
(65, 33, 15, NULL, 2, 'Cartas ao Amanhã', '2020-02-19', '2025-09-15', 11, '14', 0x6f636f727469636f2e6a7067, 'ativo'),
(66, 34, 16, NULL, 3, 'O Silvo do Trem', '2021-07-04', '2025-09-15', 12, '15', 0x6f70657175656e6f7072696e636970652e6a7067, 'ativo'),
(67, 1, 17, NULL, 4, 'Castelos de Areia', '2019-06-09', '2025-09-15', 10, '16', 0x6a6f676f735f766f72617a65732e6a7067, 'ativo'),
(68, 20, 18, NULL, 5, 'A Ilha e a Ponte', '2015-10-07', '2025-09-15', 13, '17', 0x69745f636f6973612e6a7067, 'ativo'),
(69, 21, 20, NULL, 6, 'O Poço da Memória', '2021-11-14', '2025-09-15', 4, '18', 0x666f676f5f655f73616e6775652e6a7067, 'ativo'),
(70, 22, 21, NULL, 7, 'Flor de Campo', '2025-08-11', '2025-09-15', 18, '19', 0x76616d7069726f732e77656270, 'ativo'),
(71, 23, 1, NULL, 8, 'A Lâmpada Azul', '2019-07-18', '2025-09-15', 14, '20', 0x6f46696d6461457465726e69646164652e77656270, 'ativo'),
(72, 24, 2, NULL, 9, 'Ladeira do Sul', '2023-09-27', '2025-09-15', 14, '21', 0x6f6c6976726f64616d6174656d61746963612e6a7067, 'ativo'),
(73, 25, 3, NULL, 10, 'O Velho Faroleiro', '2017-02-12', '2025-09-15', 19, '22', 0x70617261746f646f736f736761726f746f737175656a61616d65692e6a7067, 'ativo'),
(74, 26, 4, NULL, 11, 'Caderno de Viagens', '2023-08-25', '2025-09-15', 12, '23', 0x62726964612e6a7067, 'ativo'),
(75, 27, 5, NULL, 12, 'O Bosque Claro', '2017-12-29', '2025-09-15', 13, '24', 0x68656c656e612e6a7067, 'ativo'),
(76, 28, 6, NULL, 13, 'Caminho da Serra', '2024-10-24', '2025-09-15', 4, '25', 0x6c6976726f312e6a7067, 'ativo'),
(77, 29, 7, NULL, 1, 'Sombra na Areia', '2017-07-22', '2025-09-15', 11, '0', 0x646f6d4361736d7572726f2e77656270, 'ativo'),
(78, 30, 8, NULL, 2, 'Poeira de Estrelas', '2023-10-22', '2025-09-15', 5, '1', 0x6f636f727469636f2e6a7067, 'ativo'),
(79, 31, 9, NULL, 3, 'Chuva de Verão', '2020-05-21', '2025-09-15', 17, '2', 0x6f70657175656e6f7072696e636970652e6a7067, 'ativo'),
(80, 32, 10, NULL, 4, 'A Porta do Norte', '2016-01-09', '2025-09-15', 9, '3', 0x6a6f676f735f766f72617a65732e6a7067, 'ativo'),
(81, 33, 11, NULL, 5, 'O Pescador e o Céu', '2025-07-21', '2025-09-15', 19, '4', 0x69745f636f6973612e6a7067, 'ativo'),
(82, 34, 12, NULL, 6, 'Estrelas de Agosto', '2021-06-15', '2025-09-15', 12, '5', 0x666f676f5f655f73616e6775652e6a7067, 'ativo'),
(83, 1, 13, NULL, 7, 'Relva Molhada', '2020-05-22', '2025-09-15', 17, '6', 0x76616d7069726f732e77656270, 'ativo'),
(84, 20, 14, NULL, 8, 'O Jardim do Lago', '2019-08-05', '2025-09-15', 17, '7', 0x6f46696d6461457465726e69646164652e77656270, 'ativo'),
(85, 21, 15, NULL, 9, 'Tarde de Domingo', '2024-08-05', '2025-09-15', 16, '8', 0x6f6c6976726f64616d6174656d61746963612e6a7067, 'ativo'),
(86, 22, 16, NULL, 10, 'Luz na Colina', '2024-03-25', '2025-09-15', 2, '9', 0x70617261746f646f736f736761726f746f737175656a61616d65692e6a7067, 'ativo'),
(87, 23, 17, NULL, 11, 'O Vale Escondido', '2020-10-08', '2025-09-15', 19, '10', 0x62726964612e6a7067, 'ativo'),
(88, 24, 18, NULL, 12, 'Canto da Manhã', '2020-07-29', '2025-09-15', 17, '11', 0x68656c656e612e6a7067, 'ativo'),
(89, 25, 20, NULL, 13, 'Maré Baixa', '2021-01-11', '2025-09-15', 7, '12', 0x6c6976726f312e6a7067, 'ativo'),
(90, 26, 21, NULL, 1, 'Rosa do Campo', '2018-03-13', '2025-09-15', 17, '13', 0x646f6d4361736d7572726f2e77656270, 'ativo'),
(91, 27, 1, NULL, 2, 'Letras ao Vento', '2021-10-29', '2025-09-15', 6, '14', 0x6f636f727469636f2e6a7067, 'ativo'),
(92, 28, 2, NULL, 3, 'O Sítio do Leste', '2023-11-22', '2025-09-15', 10, '15', 0x6f70657175656e6f7072696e636970652e6a7067, 'ativo'),
(93, 29, 3, NULL, 4, 'O Passo da Serra', '2021-10-04', '2025-09-15', 11, '16', 0x6a6f676f735f766f72617a65732e6a7067, 'ativo'),
(94, 30, 4, NULL, 5, 'Cascata de Luz', '2021-07-10', '2025-09-15', 6, '17', 0x69745f636f6973612e6a7067, 'ativo'),
(95, 31, 5, NULL, 6, 'A Vila e o Rio', '2017-07-04', '2025-09-15', 4, '18', 0x666f676f5f655f73616e6775652e6a7067, 'ativo'),
(96, 32, 6, NULL, 7, 'O Moinho Antigo', '2025-07-17', '2025-09-15', 18, '19', 0x76616d7069726f732e77656270, 'ativo'),
(97, 33, 7, NULL, 8, 'Casa de Madeira', '2022-12-01', '2025-09-15', 13, '20', 0x6f46696d6461457465726e69646164652e77656270, 'ativo'),
(98, 34, 8, NULL, 9, 'Ponte sobre o Riacho', '2021-01-05', '2025-09-15', 3, '21', 0x6f6c6976726f64616d6174656d61746963612e6a7067, 'ativo'),
(99, 1, 9, NULL, 10, 'Caminho de Pedra', '2020-07-22', '2025-09-15', 11, '22', 0x70617261746f646f736f736761726f746f737175656a61616d65692e6a7067, 'ativo'),
(100, 20, 10, NULL, 11, 'Fogueira de Outono', '2022-10-20', '2025-09-15', 1, '23', 0x62726964612e6a7067, 'ativo'),
(110, 1, 1, NULL, 1, 'Horizontes Distantes', '2019-08-19', '2025-09-15', 1, '0', 0x646f6d4361736d7572726f2e77656270, 'ativo'),
(112, 20, 2, NULL, 2, 'Ecos do Tempo', '2021-03-11', '2025-09-15', 10, '1', 0x6f636f727469636f2e6a7067, 'ativo'),
(113, 21, 3, NULL, 3, 'Caminhos Cruzados', '2016-01-23', '2025-09-15', 4, '2', 0x6f70657175656e6f7072696e636970652e6a7067, 'ativo'),
(114, 22, 4, NULL, 4, 'Aurora Silenciosa', '2017-04-16', '2025-09-15', 15, '3', 0x6a6f676f735f766f72617a65732e6a7067, 'ativo'),
(115, 23, 5, NULL, 5, 'Entardecer no Vale', '2024-06-27', '2025-09-15', 8, '4', 0x69745f636f6973612e6a7067, 'ativo'),
(116, 24, 6, NULL, 6, 'Labirintos da Alma', '2023-03-11', '2025-09-15', 11, '5', 0x666f676f5f655f73616e6775652e6a7067, 'ativo'),
(117, 25, 7, NULL, 7, 'Constelações Perdidas', '2020-07-12', '2025-09-15', 3, '6', 0x76616d7069726f732e77656270, 'ativo'),
(118, 26, 8, NULL, 8, 'Mar Aberto', '2016-12-02', '2025-09-15', 4, '7', 0x6f46696d6461457465726e69646164652e77656270, 'ativo'),
(119, 27, 9, NULL, 9, 'Raízes Profundas', '2023-09-12', '2025-09-15', 13, '8', 0x6f6c6976726f64616d6174656d61746963612e6a7067, 'ativo'),
(120, 28, 10, NULL, 10, 'A Ponte Invisível', '2017-04-09', '2025-09-15', 18, '9', 0x70617261746f646f736f736761726f746f737175656a61616d65692e6a7067, 'ativo'),
(121, 29, 11, NULL, 11, 'Códigos Secretos', '2019-07-16', '2025-09-15', 6, '10', 0x62726964612e6a7067, 'ativo'),
(122, 30, 12, NULL, 12, 'Fragmentos de Memória', '2024-02-08', '2025-09-15', 12, '11', 0x68656c656e612e6a7067, 'ativo'),
(123, 31, 13, NULL, 13, 'Sopro do Norte', '2024-09-08', '2025-09-15', 16, '12', 0x6c6976726f312e6a7067, 'ativo'),
(124, 32, 14, NULL, 1, 'Círculos Inacabados', '2025-09-03', '2025-09-15', 13, '13', 0x646f6d4361736d7572726f2e77656270, 'ativo'),
(125, 33, 15, NULL, 2, 'Vértices do Destino', '2018-01-21', '2025-09-15', 11, '14', 0x6f636f727469636f2e6a7067, 'ativo'),
(126, 34, 16, NULL, 3, 'Mundos Paralelos', '2019-10-05', '2025-09-15', 9, '15', 0x6f70657175656e6f7072696e636970652e6a7067, 'ativo'),
(127, 1, 17, NULL, 4, 'Vozes Antigas', '2022-04-13', '2025-09-15', 20, '16', 0x6a6f676f735f766f72617a65732e6a7067, 'ativo'),
(128, 20, 18, NULL, 5, 'Ritmos do Vento', '2019-08-27', '2025-09-15', 1, '17', 0x69745f636f6973612e6a7067, 'ativo'),
(129, 21, 20, NULL, 6, 'Noturno Azul', '2024-04-16', '2025-09-15', 4, '18', 0x666f676f5f655f73616e6775652e6a7067, 'ativo'),
(130, 22, 21, NULL, 7, 'Amanhecer Dourado', '2024-05-28', '2025-09-15', 10, '19', 0x76616d7069726f732e77656270, 'ativo'),
(131, 23, 1, NULL, 8, 'Riacho Silencioso', '2023-07-22', '2025-09-15', 6, '20', 0x6f46696d6461457465726e69646164652e77656270, 'ativo'),
(132, 24, 2, NULL, 9, 'O Mapa Antigo', '2025-09-10', '2025-09-15', 17, '21', 0x6f6c6976726f64616d6174656d61746963612e6a7067, 'ativo'),
(133, 25, 3, NULL, 10, 'Jardins de Inverno', '2018-02-27', '2025-09-15', 8, '22', 0x70617261746f646f736f736761726f746f737175656a61616d65692e6a7067, 'ativo'),
(134, 26, 4, NULL, 11, 'Luz e Sombra', '2019-10-07', '2025-09-15', 9, '23', 0x62726964612e6a7067, 'ativo'),
(135, 27, 5, NULL, 12, 'Portas do Vento', '2018-02-15', '2025-09-15', 15, '24', 0x68656c656e612e6a7067, 'ativo'),
(136, 28, 6, NULL, 13, 'O Guardião do Farol', '2019-07-18', '2025-09-15', 8, '25', 0x6c6976726f312e6a7067, 'ativo');

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
(155, 'livro', 'INSERT', 12, NULL, 'Título: Harry Potter e a Pedra Filosofal', NULL, '2025-09-05 17:25:07', NULL),
(156, 'livro', 'INSERT', 13, NULL, 'Título: AAAAAAAAAAA', NULL, '2025-09-05 18:00:19', NULL),
(157, 'livro', 'DELETE', 13, 'Título: AAAAAAAAAAA', NULL, NULL, '2025-09-05 18:00:27', NULL),
(158, 'livro', 'INSERT', 14, NULL, 'Título: Amor & Gelato', NULL, '2025-09-05 19:38:34', NULL),
(159, 'livro', 'INSERT', 15, NULL, 'Título: It a Coisa', NULL, '2025-09-05 19:45:48', NULL),
(160, 'livro', 'INSERT', 16, NULL, 'Título: O Código da Vinci', NULL, '2025-09-05 20:04:43', NULL),
(161, 'autor', 'UPDATE', 34, 'Nome: Machado de Assis, Telefone: (61) 94444-3344, Email: machado@email.com', 'Nome: Machado de Assis, Telefone: (61) 94441-3344, Email: machado@email.com', 'root@localhost', '2025-09-08 17:31:50', NULL),
(162, 'autor', 'UPDATE', 34, 'Nome: Machado de Assis, Telefone: (61) 94441-3344, Email: machado@email.com', 'Nome: Machado de Assis, Telefone: (61) 98441-3344, Email: machado@email.com', 'root@localhost', '2025-09-08 17:33:29', NULL),
(163, 'emprestimo', 'INSERT', 4, NULL, 'Cliente: 8, Livro: 15, Data: 2025-09-08', 'root@localhost', '2025-09-08 17:34:27', NULL),
(164, 'livro', 'UPDATE', 15, 'Título: It a Coisa', 'Título: It a Coisa', NULL, '2025-09-08 17:34:27', NULL),
(165, 'livro', 'UPDATE', 15, 'Título: It a Coisa', 'Título: It a Coisa', NULL, '2025-09-08 17:36:51', NULL),
(166, 'emprestimo', 'UPDATE', 4, 'Cliente: 8, Livro: 15, Data: 2025-09-08', 'Cliente: 8, Livro: 15, Data: 2025-09-08', 'root@localhost', '2025-09-08 17:36:51', NULL),
(167, 'emprestimo', 'INSERT', 5, NULL, 'Cliente: 9, Livro: 14, Data: 2025-09-08', 'root@localhost', '2025-09-08 17:41:11', NULL),
(168, 'livro', 'UPDATE', 14, 'Título: Amor & Gelato', 'Título: Amor & Gelato', NULL, '2025-09-08 17:41:11', NULL),
(169, 'livro', 'INSERT', 17, NULL, 'Título: Jogos Vorazes I', NULL, '2025-09-09 17:30:06', NULL),
(170, 'livro', 'INSERT', 18, NULL, 'Título: Fogo & Sangue I', NULL, '2025-09-09 17:37:44', NULL),
(171, 'livro', 'INSERT', 19, NULL, 'Título: Fogo & Sangue I', NULL, '2025-09-09 17:39:31', NULL),
(172, 'livro', 'DELETE', 19, 'Título: Fogo & Sangue I', NULL, NULL, '2025-09-09 17:39:41', NULL),
(173, 'livro', 'INSERT', 20, NULL, 'Título: O Hobbit', NULL, '2025-09-09 17:48:51', NULL),
(174, 'livro', 'INSERT', 21, NULL, 'Título: Brida ', NULL, '2025-09-09 17:51:33', NULL),
(175, 'livro', 'INSERT', 22, NULL, 'Título: O Fim da Eternidade', NULL, '2025-09-09 17:56:03', NULL),
(176, 'livro', 'INSERT', 23, NULL, 'Título: Dom Casmurro ', NULL, '2025-09-09 18:03:56', NULL),
(177, 'livro', 'UPDATE', 21, 'Título: Brida ', 'Título: Brida', NULL, '2025-09-09 18:04:56', NULL),
(178, 'emprestimo', 'UPDATE', 5, 'Cliente: 9, Livro: 14, Data: 2025-09-08', 'Cliente: 9, Livro: 14, Data: 2025-09-08', 'root@localhost', '2025-09-09 18:14:57', NULL),
(179, 'emprestimo', 'UPDATE', 5, 'Cliente: 9, Livro: 14, Data: 2025-09-08', 'Cliente: 9, Livro: 14, Data: 2025-09-08', 'root@localhost', '2025-09-09 18:14:59', NULL),
(180, 'livro', 'UPDATE', 14, 'Título: Amor & Gelato', 'Título: Amor & Gelato', NULL, '2025-09-09 18:15:06', NULL),
(181, 'emprestimo', 'UPDATE', 5, 'Cliente: 9, Livro: 14, Data: 2025-09-08', 'Cliente: 9, Livro: 14, Data: 2025-09-08', 'root@localhost', '2025-09-09 18:15:06', NULL),
(182, 'emprestimo', 'INSERT', 6, NULL, 'Cliente: 10, Livro: 20, Data: 2025-09-09', 'root@localhost', '2025-09-09 18:27:50', NULL),
(183, 'livro', 'UPDATE', 20, 'Título: O Hobbit', 'Título: O Hobbit', NULL, '2025-09-09 18:27:50', NULL),
(184, 'emprestimo', 'INSERT', 7, NULL, 'Cliente: 13, Livro: 14, Data: 2025-09-09', 'root@localhost', '2025-09-09 18:28:26', NULL),
(185, 'livro', 'UPDATE', 14, 'Título: Amor & Gelato', 'Título: Amor & Gelato', NULL, '2025-09-09 18:28:26', NULL),
(186, 'emprestimo', 'INSERT', 8, NULL, 'Cliente: 12, Livro: 23, Data: 2025-09-01', 'root@localhost', '2025-09-09 18:35:39', NULL),
(187, 'livro', 'UPDATE', 23, 'Título: Dom Casmurro ', 'Título: Dom Casmurro ', NULL, '2025-09-09 18:36:00', NULL),
(188, 'emprestimo', 'UPDATE', 8, 'Cliente: 12, Livro: 23, Data: 2025-09-01', 'Cliente: 12, Livro: 23, Data: 2025-09-01', 'root@localhost', '2025-09-09 18:36:00', NULL),
(189, 'livro', 'INSERT', 110, NULL, 'Título: Horizontes Distantes', NULL, '2025-09-16 17:14:21', NULL),
(190, 'livro', 'INSERT', 112, NULL, 'Título: Ecos do Tempo', NULL, '2025-09-16 17:14:21', NULL),
(191, 'livro', 'INSERT', 113, NULL, 'Título: Caminhos Cruzados', NULL, '2025-09-16 17:14:21', NULL),
(192, 'livro', 'INSERT', 114, NULL, 'Título: Aurora Silenciosa', NULL, '2025-09-16 17:14:21', NULL),
(193, 'livro', 'INSERT', 115, NULL, 'Título: Entardecer no Vale', NULL, '2025-09-16 17:14:21', NULL),
(194, 'livro', 'INSERT', 116, NULL, 'Título: Labirintos da Alma', NULL, '2025-09-16 17:14:21', NULL),
(195, 'livro', 'INSERT', 117, NULL, 'Título: Constelações Perdidas', NULL, '2025-09-16 17:14:21', NULL),
(196, 'livro', 'INSERT', 118, NULL, 'Título: Mar Aberto', NULL, '2025-09-16 17:14:21', NULL),
(197, 'livro', 'INSERT', 119, NULL, 'Título: Raízes Profundas', NULL, '2025-09-16 17:14:21', NULL),
(198, 'livro', 'INSERT', 120, NULL, 'Título: A Ponte Invisível', NULL, '2025-09-16 17:14:21', NULL),
(199, 'livro', 'INSERT', 121, NULL, 'Título: Códigos Secretos', NULL, '2025-09-16 17:14:21', NULL),
(200, 'livro', 'INSERT', 122, NULL, 'Título: Fragmentos de Memória', NULL, '2025-09-16 17:14:21', NULL),
(201, 'livro', 'INSERT', 123, NULL, 'Título: Sopro do Norte', NULL, '2025-09-16 17:14:21', NULL),
(202, 'livro', 'INSERT', 124, NULL, 'Título: Círculos Inacabados', NULL, '2025-09-16 17:14:21', NULL),
(203, 'livro', 'INSERT', 125, NULL, 'Título: Vértices do Destino', NULL, '2025-09-16 17:14:21', NULL),
(204, 'livro', 'INSERT', 126, NULL, 'Título: Mundos Paralelos', NULL, '2025-09-16 17:14:21', NULL),
(205, 'livro', 'INSERT', 127, NULL, 'Título: Vozes Antigas', NULL, '2025-09-16 17:14:21', NULL),
(206, 'livro', 'INSERT', 128, NULL, 'Título: Ritmos do Vento', NULL, '2025-09-16 17:14:21', NULL),
(207, 'livro', 'INSERT', 129, NULL, 'Título: Noturno Azul', NULL, '2025-09-16 17:14:21', NULL),
(208, 'livro', 'INSERT', 130, NULL, 'Título: Amanhecer Dourado', NULL, '2025-09-16 17:14:21', NULL),
(209, 'livro', 'INSERT', 131, NULL, 'Título: Riacho Silencioso', NULL, '2025-09-16 17:14:21', NULL),
(210, 'livro', 'INSERT', 132, NULL, 'Título: O Mapa Antigo', NULL, '2025-09-16 17:14:21', NULL),
(211, 'livro', 'INSERT', 133, NULL, 'Título: Jardins de Inverno', NULL, '2025-09-16 17:14:21', NULL),
(212, 'livro', 'INSERT', 134, NULL, 'Título: Luz e Sombra', NULL, '2025-09-16 17:14:21', NULL),
(213, 'livro', 'INSERT', 135, NULL, 'Título: Portas do Vento', NULL, '2025-09-16 17:14:21', NULL),
(214, 'livro', 'INSERT', 136, NULL, 'Título: O Guardião do Farol', NULL, '2025-09-16 17:14:21', NULL),
(215, 'livro', 'INSERT', 27, NULL, 'Título: Estação Final', NULL, '2025-09-16 17:14:21', NULL),
(216, 'livro', 'INSERT', 28, NULL, 'Título: Caminho de Volta', NULL, '2025-09-16 17:14:21', NULL),
(217, 'livro', 'INSERT', 29, NULL, 'Título: Pétalas ao Chão', NULL, '2025-09-16 17:14:21', NULL),
(218, 'livro', 'INSERT', 30, NULL, 'Título: No Coração da Floresta', NULL, '2025-09-16 17:14:21', NULL),
(219, 'livro', 'INSERT', 31, NULL, 'Título: Céu de Outono', NULL, '2025-09-16 17:14:21', NULL),
(220, 'livro', 'INSERT', 32, NULL, 'Título: A Casa ao Lado', NULL, '2025-09-16 17:14:21', NULL),
(221, 'livro', 'INSERT', 33, NULL, 'Título: Um Lugar no Mundo', NULL, '2025-09-16 17:14:21', NULL),
(222, 'livro', 'INSERT', 34, NULL, 'Título: O Eco e a Montanha', NULL, '2025-09-16 17:14:21', NULL),
(223, 'livro', 'INSERT', 35, NULL, 'Título: Velas ao Leste', NULL, '2025-09-16 17:14:21', NULL),
(224, 'livro', 'INSERT', 36, NULL, 'Título: Rosa dos Ventos', NULL, '2025-09-16 17:14:21', NULL),
(225, 'livro', 'INSERT', 37, NULL, 'Título: Canções de Areia', NULL, '2025-09-16 17:14:21', NULL),
(226, 'livro', 'INSERT', 38, NULL, 'Título: O Livro das Marés', NULL, '2025-09-16 17:14:21', NULL),
(227, 'livro', 'INSERT', 39, NULL, 'Título: Viagem ao Centro do Dia', NULL, '2025-09-16 17:14:21', NULL),
(228, 'livro', 'INSERT', 40, NULL, 'Título: Passos na Neve', NULL, '2025-09-16 17:14:21', NULL),
(229, 'livro', 'INSERT', 41, NULL, 'Título: Silêncio das Rochas', NULL, '2025-09-16 17:14:21', NULL),
(230, 'livro', 'INSERT', 42, NULL, 'Título: Entre Dois Rios', NULL, '2025-09-16 17:14:21', NULL),
(231, 'livro', 'INSERT', 43, NULL, 'Título: O Sinal da Colina', NULL, '2025-09-16 17:14:21', NULL),
(232, 'livro', 'INSERT', 44, NULL, 'Título: Brisa de Abril', NULL, '2025-09-16 17:14:21', NULL),
(233, 'livro', 'INSERT', 45, NULL, 'Título: A Última Estrada', NULL, '2025-09-16 17:14:21', NULL),
(234, 'livro', 'INSERT', 46, NULL, 'Título: As Quatro Estações', NULL, '2025-09-16 17:14:21', NULL),
(235, 'livro', 'INSERT', 47, NULL, 'Título: Pássaros de Fogo', NULL, '2025-09-16 17:14:21', NULL),
(236, 'livro', 'INSERT', 48, NULL, 'Título: A Torre e o Mar', NULL, '2025-09-16 17:14:21', NULL),
(237, 'livro', 'INSERT', 49, NULL, 'Título: Rastros na Terra', NULL, '2025-09-16 17:14:21', NULL),
(238, 'livro', 'INSERT', 50, NULL, 'Título: Vento sobre a Planície', NULL, '2025-09-16 17:14:21', NULL),
(239, 'livro', 'INSERT', 51, NULL, 'Título: Caminhos do Horizonte', NULL, '2025-09-16 17:14:21', NULL),
(240, 'livro', 'INSERT', 52, NULL, 'Título: Marcas do Tempo', NULL, '2025-09-16 17:14:21', NULL),
(241, 'livro', 'INSERT', 53, NULL, 'Título: Sombras de Pedra', NULL, '2025-09-16 17:14:21', NULL),
(242, 'livro', 'INSERT', 54, NULL, 'Título: Rumo ao Norte', NULL, '2025-09-16 17:14:21', NULL),
(243, 'livro', 'INSERT', 55, NULL, 'Título: Pássaros no Inverno', NULL, '2025-09-16 17:14:21', NULL),
(244, 'livro', 'INSERT', 56, NULL, 'Título: Dança das Folhas', NULL, '2025-09-16 17:14:21', NULL),
(245, 'livro', 'INSERT', 57, NULL, 'Título: O Livro do Vento', NULL, '2025-09-16 17:14:21', NULL),
(246, 'livro', 'INSERT', 58, NULL, 'Título: Sussurros do Mar', NULL, '2025-09-16 17:14:21', NULL),
(247, 'livro', 'INSERT', 59, NULL, 'Título: Colheita de Estrelas', NULL, '2025-09-16 17:14:21', NULL),
(248, 'livro', 'INSERT', 60, NULL, 'Título: A Estrada de Casa', NULL, '2025-09-16 17:14:21', NULL),
(249, 'livro', 'INSERT', 61, NULL, 'Título: Entre Montanhas', NULL, '2025-09-16 17:14:21', NULL),
(250, 'livro', 'INSERT', 62, NULL, 'Título: Raio de Luar', NULL, '2025-09-16 17:14:21', NULL),
(251, 'livro', 'INSERT', 63, NULL, 'Título: A Janela e o Rio', NULL, '2025-09-16 17:14:21', NULL),
(252, 'livro', 'INSERT', 64, NULL, 'Título: Trilho Antigo', NULL, '2025-09-16 17:14:21', NULL),
(253, 'livro', 'INSERT', 65, NULL, 'Título: Cartas ao Amanhã', NULL, '2025-09-16 17:14:21', NULL),
(254, 'livro', 'INSERT', 66, NULL, 'Título: O Silvo do Trem', NULL, '2025-09-16 17:14:21', NULL),
(255, 'livro', 'INSERT', 67, NULL, 'Título: Castelos de Areia', NULL, '2025-09-16 17:14:21', NULL),
(256, 'livro', 'INSERT', 68, NULL, 'Título: A Ilha e a Ponte', NULL, '2025-09-16 17:14:21', NULL),
(257, 'livro', 'INSERT', 69, NULL, 'Título: O Poço da Memória', NULL, '2025-09-16 17:14:21', NULL),
(258, 'livro', 'INSERT', 70, NULL, 'Título: Flor de Campo', NULL, '2025-09-16 17:14:21', NULL),
(259, 'livro', 'INSERT', 71, NULL, 'Título: A Lâmpada Azul', NULL, '2025-09-16 17:14:21', NULL),
(260, 'livro', 'INSERT', 72, NULL, 'Título: Ladeira do Sul', NULL, '2025-09-16 17:14:21', NULL),
(261, 'livro', 'INSERT', 73, NULL, 'Título: O Velho Faroleiro', NULL, '2025-09-16 17:14:21', NULL),
(262, 'livro', 'INSERT', 74, NULL, 'Título: Caderno de Viagens', NULL, '2025-09-16 17:14:21', NULL),
(263, 'livro', 'INSERT', 75, NULL, 'Título: O Bosque Claro', NULL, '2025-09-16 17:14:21', NULL),
(264, 'livro', 'INSERT', 76, NULL, 'Título: Caminho da Serra', NULL, '2025-09-16 17:14:21', NULL),
(265, 'livro', 'INSERT', 77, NULL, 'Título: Sombra na Areia', NULL, '2025-09-16 17:14:21', NULL),
(266, 'livro', 'INSERT', 78, NULL, 'Título: Poeira de Estrelas', NULL, '2025-09-16 17:14:21', NULL),
(267, 'livro', 'INSERT', 79, NULL, 'Título: Chuva de Verão', NULL, '2025-09-16 17:14:21', NULL),
(268, 'livro', 'INSERT', 80, NULL, 'Título: A Porta do Norte', NULL, '2025-09-16 17:14:21', NULL),
(269, 'livro', 'INSERT', 81, NULL, 'Título: O Pescador e o Céu', NULL, '2025-09-16 17:14:21', NULL),
(270, 'livro', 'INSERT', 82, NULL, 'Título: Estrelas de Agosto', NULL, '2025-09-16 17:14:21', NULL),
(271, 'livro', 'INSERT', 83, NULL, 'Título: Relva Molhada', NULL, '2025-09-16 17:14:21', NULL),
(272, 'livro', 'INSERT', 84, NULL, 'Título: O Jardim do Lago', NULL, '2025-09-16 17:14:21', NULL),
(273, 'livro', 'INSERT', 85, NULL, 'Título: Tarde de Domingo', NULL, '2025-09-16 17:14:21', NULL),
(274, 'livro', 'INSERT', 86, NULL, 'Título: Luz na Colina', NULL, '2025-09-16 17:14:21', NULL),
(275, 'livro', 'INSERT', 87, NULL, 'Título: O Vale Escondido', NULL, '2025-09-16 17:14:21', NULL),
(276, 'livro', 'INSERT', 88, NULL, 'Título: Canto da Manhã', NULL, '2025-09-16 17:14:21', NULL),
(277, 'livro', 'INSERT', 89, NULL, 'Título: Maré Baixa', NULL, '2025-09-16 17:14:21', NULL),
(278, 'livro', 'INSERT', 90, NULL, 'Título: Rosa do Campo', NULL, '2025-09-16 17:14:21', NULL),
(279, 'livro', 'INSERT', 91, NULL, 'Título: Letras ao Vento', NULL, '2025-09-16 17:14:21', NULL),
(280, 'livro', 'INSERT', 92, NULL, 'Título: O Sítio do Leste', NULL, '2025-09-16 17:14:21', NULL),
(281, 'livro', 'INSERT', 93, NULL, 'Título: O Passo da Serra', NULL, '2025-09-16 17:14:21', NULL),
(282, 'livro', 'INSERT', 94, NULL, 'Título: Cascata de Luz', NULL, '2025-09-16 17:14:21', NULL),
(283, 'livro', 'INSERT', 95, NULL, 'Título: A Vila e o Rio', NULL, '2025-09-16 17:14:21', NULL),
(284, 'livro', 'INSERT', 96, NULL, 'Título: O Moinho Antigo', NULL, '2025-09-16 17:14:21', NULL),
(285, 'livro', 'INSERT', 97, NULL, 'Título: Casa de Madeira', NULL, '2025-09-16 17:14:21', NULL),
(286, 'livro', 'INSERT', 98, NULL, 'Título: Ponte sobre o Riacho', NULL, '2025-09-16 17:14:21', NULL),
(287, 'livro', 'INSERT', 99, NULL, 'Título: Caminho de Pedra', NULL, '2025-09-16 17:14:21', NULL),
(288, 'livro', 'INSERT', 100, NULL, 'Título: Fogueira de Outono', NULL, '2025-09-16 17:14:21', NULL);

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

--
-- Despejando dados para a tabela `multa`
--

INSERT INTO `multa` (`Cod_Multa`, `Cod_Emprestimo`, `Data_Multa`, `Valor_Multa`, `Status_Multa`) VALUES
(9, 6, '2025-09-05', 5.50, 'Pendente'),
(10, 8, '2025-09-09', 2.00, 'Pendente');

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
  MODIFY `Cod_Editora` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `emprestimo`
--
ALTER TABLE `emprestimo`
  MODIFY `Cod_Emprestimo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `funcionario`
--
ALTER TABLE `funcionario`
  MODIFY `Cod_Funcionario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de tabela `genero`
--
ALTER TABLE `genero`
  MODIFY `Cod_Genero` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `livro`
--
ALTER TABLE `livro`
  MODIFY `Cod_Livro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=137;

--
-- AUTO_INCREMENT de tabela `logs_auditoria`
--
ALTER TABLE `logs_auditoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=289;

--
-- AUTO_INCREMENT de tabela `multa`
--
ALTER TABLE `multa`
  MODIFY `Cod_Multa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
