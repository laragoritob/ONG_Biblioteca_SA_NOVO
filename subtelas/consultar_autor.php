<?php
session_start();
require_once '../conexao.php';

//VERIFICA SE O USUARIO TEM PERMISSAO DE GERENTE
if(!isset($_SESSION['perfil']) || $_SESSION['perfil'] != 1){
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
  <style>
    a {
        text-decoration: none;
    }
    </style>
</head>

<body>
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
                <button style="margin-right: 0.01rem;" onclick="editarAutor(<?= $autor_item['Cod_Autor'] ?>)">✏️</button>
                <button style="margin-left: 0.01rem;" onclick="excluirAutor(<?= $autor_item['Cod_Autor'] ?>)">🗑️</button>
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
  <script>
    function editarAutor(id) {
      window.location.href = 'alterar_autor.php?id=' + id;
    }
// Função para excluir funcionário
function excluirAutor(id) {
    if (confirm('Tem certeza que deseja excluir este autor?')) {
        // Redirecionar para a página de exclusão com o ID do autor
        window.location.href = 'excluir_autor.php?id=' + id;
    }
}
  </script>
</html>