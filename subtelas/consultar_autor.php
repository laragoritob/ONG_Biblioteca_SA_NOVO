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

    .filtro-container {
      display: flex;
      gap: 15px;
      align-items: center;
      margin-bottom: 20px;
      flex-wrap: wrap;
    }
    
    #search-container {
      display: flex;
      align-items: center;
      gap: 15px;
      flex: 1;
      min-width: 300px;
    }
    
    .input-wrapper {
      flex: 1;
      position: relative;
    }
    
    .filtro-select {
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      background: white;
      min-width: 150px;
      font-size: 14px;
      color: #333;
      cursor: pointer;
      transition: border-color 0.3s;
    }
    
    .filtro-select:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
    }
    
    .btn-filtrar {
      padding: 10px 20px;
      background:rgb(83, 86, 238);
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
      font-weight: 500;
    }
    
    .btn-filtrar:hover {
      background:rgb(53, 69, 211);
    }
    
    .btn-limpar {
      padding: 10px 20px;
      background: #e53e3e;
      color: white;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      transition: background 0.3s;
      font-weight: 500;
    }
    
    .btn-limpar:hover {
      background: #c53030;
    }
    
    /* Responsividade */
    @media (max-width: 768px) {
      .filtro-container {
        flex-direction: column;
        align-items: stretch;
      }
      
      #search-container {
        flex-direction: column;
        min-width: auto;
      }
      
      .input-wrapper {
        width: 100%;
      }
      
      .filtro-select {
        min-width: auto;
        width: 100%;
      }
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
    <div class="filtro-container">
      <form action="consultar_autor.php" method="POST" id="search-container">
        <div class="input-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-search-icon lucide-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;z-index:1;color:#9ca3af;">
          <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
        </svg>
          <input type="text" id="search-input" name="busca" placeholder="Buscar por ID ou nome..." value="<?= htmlspecialchars(isset($_POST['busca']) ? $_POST['busca'] : '') ?>" onkeyup="filtrarTabela()">
        </div>
        
        <button type="submit" class="btn-filtrar">Buscar</button>
        <button type="button" class="btn-limpar" onclick="limparFiltros()">Limpar</button>
      </form>
        </div>

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
    
    // Função para excluir autor
    function excluirAutor(id) {
        if (confirm('Tem certeza que deseja excluir este autor?')) {
            // Redirecionar para a página de exclusão com o ID do autor
            window.location.href = 'excluir_autor.php?id=' + id;
        }
    }
    
    // Função para filtrar tabela em tempo real
    function filtrarTabela() {
        var input = document.getElementById('search-input');
        var filter = input.value.toLowerCase();
        var table = document.getElementById('funcionarios-table');
        var tr = table.getElementsByTagName('tr');

        for (var i = 1; i < tr.length; i++) {
            var td = tr[i].getElementsByTagName('td');
            var found = false;
            
            for (var j = 0; j < td.length; j++) {
                var cell = td[j];
                if (cell) {
                    var txtValue = cell.textContent || cell.innerText;
                    if (txtValue.toLowerCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            
            if (found) {
                tr[i].style.display = '';
            } else {
                tr[i].style.display = 'none';
            }
        }
    }
    
    // Função para limpar filtros
    function limparFiltros() {
        document.getElementById('search-input').value = '';
        filtrarTabela(); // Mostra todas as linhas novamente
    }
  </script>
</html>