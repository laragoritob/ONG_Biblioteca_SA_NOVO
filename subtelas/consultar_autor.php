<?php
session_start();
require_once '../conexao.php';

//VERIFCA SE O USARIO TEM PERMISSAO DE adm OU secretaria
if($_SESSION['perfil'] !=1){
  echo "<script>alert('Acesso negado!');window.location.href='../gerente.php';</script>";
  exit();
}

$autor = []; //INICIALIZA A VARIAVEL PARA EVITAR ERROS

//SE FORMULARIO FOR ENVIADO, BUSCA USUARIO PELO O ID OU NOME
if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['busca'])){
  $busca = trim($_POST['busca']);

  //VERIFICA SE A BUSCA É UM NUMERO OU UM NOME 
  if(is_numeric($busca)){
    $sql = "SELECT * FROM autor WHERE Cod_Autor = :busca ORDER BY Nome_Autor ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':busca', $busca, PDO::PARAM_INT);
  } else {
    $sql = "SELECT * FROM autor WHERE Nome_Autor LIKE :busca_nome_autor ORDER BY Nome_Autor ASC";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':busca_nome_autor', "$busca%", PDO::PARAM_STR);
  }
  
  try {
    $stmt->execute();
    $autor = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
  }
} else {
  //BUSCA TODOS OS AUTORES SE NÃO HOUVER BUSCA
  $sql = "SELECT * FROM autor ORDER BY Nome_Autor ASC";
  
  try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $autor = $stmt->fetchAll(PDO::FETCH_ASSOC);
  } catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
  }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Sala Arco-íris</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css" />
  <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Botão para abrir sidebar -->
    <button class="sidebar-toggle" id="sidebar-toggle">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <line x1="3" y1="12" x2="21" y2="12"></line>
            <line x1="3" y1="18" x2="21" y2="18"></line>
        </svg>
    </button>

    <!-- Sidebar Lateral -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>ONG Biblioteca</h3>
        </div>
        <nav class="sidebar-nav">
            <ul>
                <li><a href="../gerente.php">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9,22 9,12 15,12 15,22"></polyline>
                    </svg>
                    Dashboard
                </a></li>
                
                <li class="dropdown">
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Cliente
                    </a>
                    <div class="dropdown-menu">
                        <a href="cadastro_cliente.php">Cadastrar Cliente</a>
                        <a href="consultar_crianca.php">Consultar Criança</a>
                        <a href="consultar_responsavel.php">Consultar Responsável</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                        Funcionário
                    </a>
                    <div class="dropdown-menu">
                        <a href="cadastro_funcionario.php">Cadastrar Funcionário</a>
                        <a href="consultar_funcionario.php">Consultar Funcionário</a>
                        <a href="relatorio_funcionario.php">Relatório Funcionário</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Livro
                    </a>
                    <div class="dropdown-menu">
                        <a href="registrar_livro.php">Registrar Livro</a>
                        <a href="consultarlivro.php">Consultar Livro</a>
                        <a href="controleestoque.php">Controle de Estoque</a>
                        <a href="catalogar_livro.php">Catalogar Livro</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Empréstimo
                    </a>
                    <div class="dropdown-menu">
                        <a href="registrar_emprestimo.php">Registrar Empréstimo</a>
                        <a href="consultar_emprestimo.php">Consultar Empréstimo</a>
                        <a href="consultar_multa.php">Consultar Multa</a>
                        <a href="devolver_livro.php">Devolver Livro</a>
                        <a href="renovar_emprestimo.php">Renovar Empréstimo</a>
                    </div>
                </li>
                
                <li class="dropdown">
                    <a href="#">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        Doador
                    </a>
                    <div class="dropdown-menu">
                        <a href="registrar_doador.php">Registrar Doador</a>
                        <a href="consultar_doador.php">Consultar Doador</a>
                        <a href="historico_doacoes.php">Histórico de Doações</a>
                    </div>
                </li>
            </ul>
        </nav>
        <div class="sidebar-footer">
            <p>© 2025 ONG Biblioteca</p>
        </div>
    </div>

    <!-- Overlay para fechar sidebar -->
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="page-wrapper">
    <header>
        <form action="../gerente.php" method="POST">
            <button class="btn-voltar">← Voltar</button>
        </form>
        <h1>Consultar Autores</h1>
    </header>
    
<form action="consultar_autor.php" method="POST">
  <div id="search-container">
    <div class="input-wrapper">
      <span class="icon">🔎</span>
      <input type="text" id="search-input" name="busca" placeholder="Buscar autor por nome ou código..." required>
    </div>
  </div>
</form>

<nav>
  <?php if(!empty($autor)):?>
        <table id="funcionarios-table">
            <tr>
                <th> ID </th>
                <th> NOME DO AUTOR </th>
                <th> TELEFONE </th>
                <th> EMAIL </th>
                <th> AÇÕES </th>
            </tr>
            <?php foreach($autor as $autor_item):?>
            <tr>
                <td> <?=htmlspecialchars($autor_item['Cod_Autor'])?></td>
                <td> <?=htmlspecialchars($autor_item['Nome_Autor'])?></td>
                <td> <?=htmlspecialchars($autor_item['Telefone'])?></td>
                <td> <?=htmlspecialchars($autor_item['Email'])?></td>
                <td> 
                    <a href="alterar_autor.php?id=<?=htmlspecialchars($autor_item['Cod_Autor'])?>">Alterar</a>
                    <a href="excluir_autor.php?id=<?=htmlspecialchars($autor_item['Cod_Autor'])?>" onclick="return confirm('Tem certeza que deseja excluir este autor?')">Excluir</a>
                </td>
            </tr>
            <?php endforeach;?>
        </table>
        <?php else:?>
            <p> Nenhum autor encontrado.</p>
        <?php endif;?>
                 <br>
         </nav>
    </div>
  <script src="subtelas_javascript/sidebar.js"></script>
</body>
</html>