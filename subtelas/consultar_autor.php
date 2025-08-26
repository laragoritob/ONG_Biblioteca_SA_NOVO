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
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <br>
    <header>
        <button class="btn-voltar" onclick="window.history.back()">← Voltar</button>
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
</body>
</html>