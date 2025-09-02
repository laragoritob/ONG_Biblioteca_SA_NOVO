<?php
  session_start();
  require_once '../conexao.php';

  // VERIFICA SE O USUÁRIO TEM PERMISSÃO
  if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3 && $_SESSION['perfil'] != 4 && $_SESSION['perfil'] != 5) {
        echo "<script>alert('Acesso Negado!');window.location.href='../index.php';</script>";
        exit();
    }

    // Determina a página de "voltar" dependendo do perfil do usuário
    switch ($_SESSION['perfil']) {
        case 1: // Gerente
            $linkVoltar = "../gerente.php";
            break;
        case 2: // Gestor
            $linkVoltar = "../gestor.php";
            break;
        case 3: // Bibliotecário
            $linkVoltar = "../bibliotecario.php";
            break;
        case 4: // Recreador
            $linkVoltar = "../recreador.php";
            break;
        case 5: // Repositor
            $linkVoltar = "../repositor.php";
            break;
        default:
            // PERFIL NÃO RECONHECIDO, REDIRECIONA PARA LOGIN
            $linkVoltar = "../index.php";
            break;
    }

  // INICIALIZA VARIÁVEIS
  $livro = null;
  $erro = null;
  $livro_id = null;

  // VERIFICA SE FOI PASSADO UM ID DE LIVRO
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $livro_id = $_GET['id'];
  } elseif (isset($_POST['livro_id']) && !empty($_POST['livro_id'])) {
    $livro_id = $_POST['livro_id'];
  }

  if ($livro_id) {
    try {
      // BUSCA OS DADOS COMPLETOS DO LIVRO
      $sql = "SELECT l.Cod_Livro, l.Titulo, l.Data_Lancamento, l.Data_Registro, l.Quantidade, l.Num_Prateleira, l.Foto,
                     a.Nome_Autor as Autor, e.Nome_Editora as Editora, d.Nome_Doador as Doador, g.Nome_Genero as Genero
                FROM livro l 
                LEFT JOIN autor a ON l.Cod_Autor = a.Cod_Autor
                LEFT JOIN editora e ON l.Cod_Editora = e.Cod_Editora
                LEFT JOIN doador d ON l.Cod_Doador = d.Cod_Doador
                LEFT JOIN genero g ON l.Cod_Genero = g.Cod_Genero
                WHERE l.Cod_Livro = :livro_id";
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':livro_id', $livro_id, PDO::PARAM_INT);
      $stmt->execute();
      $livro = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$livro) {
        $erro = "Livro não encontrado.";
      }
      
    } catch (PDOException $e) {
      $erro = "Erro na consulta: " . $e->getMessage();
    }
  } else {
    $erro = "ID do livro não fornecido.";
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Ficha do Livro</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css" />
  <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    a {
      text-decoration: none;
    }

    .ficha-container {
      max-width: 800px;
      margin: 20px auto;
      background: white;
      border-radius: 10px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      overflow: hidden;
    }
    
    .ficha-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 30px;
      text-align: center;
    }
    
    .ficha-header h1 {
      margin: 0;
      font-size: 28px;
      font-weight: 600;
    }
    
    .ficha-header .livro-id {
      font-size: 18px;
      opacity: 0.9;
      margin-top: 5px;
    }
    
    .ficha-content {
      padding: 30px;
    }
    
    .info-section {
      margin-bottom: 30px;
    }
    
    .info-section h3 {
      color: #333;
      border-bottom: 2px solid #667eea;
      padding-bottom: 10px;
      margin-bottom: 20px;
      font-size: 20px;
    }
    
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }
    
    .info-item {
      background: #f8f9fa;
      padding: 15px;
      border-radius: 8px;
      border-left: 4px solid #667eea;
    }
    
    .info-label {
      font-weight: 700;
      color: #495057;
      font-size: 13px;
      text-transform: uppercase;
      letter-spacing: 1px;
      margin-bottom: 8px;
      position: relative;
      display: inline-block;
      padding: 4px 8px;
      background: rgba(102, 126, 234, 0.1);
      border-radius: 4px;
      color: #667eea;
      font-size: 12px;
    }
    
    .info-value {
      color: #333;
      font-size: 16px;
      font-weight: 500;
    }
    
    .foto-container {
      text-align: center;
      margin: 20px 0;
    }
    
    .foto-livro {
      width: 200px;
      height: 280px;
      border-radius: 8px;
      object-fit: cover;
      border: 4px solid #667eea;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    .foto-placeholder {
      width: 200px;
      height: 280px;
      border-radius: 8px;
      background: #e9ecef;
      border: 4px solid #667eea;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      color: #6c757d;
      font-size: 48px;
    }
    
    .btn-voltar-ficha {
      display: inline-block;
      padding: 12px 24px;
      background: #667eea;
      color: white;
      text-decoration: none;
      border-radius: 6px;
      font-weight: 500;
      transition: background 0.3s;
      margin-top: 20px;
    }
    
    .btn-voltar-ficha:hover {
      background: #5a6fd8;
    }
    
    .erro-mensagem {
      text-align: center;
      padding: 40px;
      color: #d32f2f;
      background-color: #ffebee;
      border: 1px solid #f44336;
      border-radius: 8px;
      margin: 20px;
    }
    
    @media (max-width: 768px) {
      .ficha-container {
        margin: 10px;
      }
      
      .ficha-header {
        padding: 20px;
      }
      
      .ficha-content {
        padding: 20px;
      }
      
      .info-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <div class="page-wrapper">
    <header>
      <a href="<?= $linkVoltar ?>" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
      <h1>Ficha do Livro</h1>
    </header>

    <?php if (isset($erro)): ?>
      <div class="erro-mensagem">
        <h3>Erro</h3>
        <p><?= htmlspecialchars($erro) ?></p>
        <a href="consultar_livro.php" class="btn-voltar-ficha">Voltar à Consulta</a>
      </div>
    <?php elseif ($livro): ?>
      <div class="ficha-container">
        <div class="ficha-header">
          <h1><?= htmlspecialchars($livro['Titulo']) ?></h1>
          <div class="livro-id">ID: <?= htmlspecialchars($livro['Cod_Livro']) ?></div>
        </div>
        
        <div class="ficha-content">
          <!-- Foto do Livro -->
          <div class="foto-container">
            <?php if (!empty($livro['Foto'])): ?>
              <img src="subtelas_img/<?= htmlspecialchars($livro['Foto']) ?>" alt="Foto do Livro" class="foto-livro">
            <?php else: ?>
              <div class="foto-placeholder">
                📚
              </div>
            <?php endif; ?>
          </div>
          
          <!-- Informações Básicas -->
          <div class="info-section">
            <h3>Informações Básicas</h3>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Título</div>
                <div class="info-value"><?= htmlspecialchars($livro['Titulo']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Autor</div>
                <div class="info-value"><?= htmlspecialchars($livro['Autor'] ?? 'Não informado') ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Editora</div>
                <div class="info-value"><?= htmlspecialchars($livro['Editora'] ?? 'Não informada') ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Gênero</div>
                <div class="info-value"><?= htmlspecialchars($livro['Genero'] ?? 'Não informado') ?></div>
              </div>
            </div>
          </div>
          
          <!-- Informações de Estoque -->
          <div class="info-section">
            <h3>Informações de Estoque</h3>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Quantidade Disponível</div>
                <div class="info-value"><?= htmlspecialchars($livro['Quantidade']) ?> exemplares</div>
              </div>
              <div class="info-item">
                <div class="info-label">Número da Prateleira</div>
                <div class="info-value"><?= htmlspecialchars($livro['Num_Prateleira'] ?? 'Não definida') ?></div>
              </div>
            </div>
          </div>
          
          <!-- Informações de Data -->
          <div class="info-section">
            <h3>Informações de Data</h3>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Data de Lançamento</div>
                <div class="info-value"><?= !empty($livro['Data_Lancamento']) ? date("d/m/Y", strtotime($livro['Data_Lancamento'])) : 'Não informada' ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Data de Registro</div>
                <div class="info-value"><?= !empty($livro['Data_Registro']) ? date("d/m/Y", strtotime($livro['Data_Registro'])) : 'Não informada' ?></div>
              </div>
            </div>
          </div>
          
          <!-- Informações de Origem -->
          <div class="info-section">
            <h3>Informações de Origem</h3>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Doador</div>
                <div class="info-value"><?= htmlspecialchars($livro['Doador'] ?? 'Não informado') ?></div>
              </div>
            </div>
          </div>
          
          <!-- Botão Voltar -->
          <div style="text-align: center;">
            <a href="consultar_livro.php" class="btn-voltar-ficha">← Voltar à Consulta</a>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script src="subtelas_javascript/consultas.js"></script>
  <script src="subtelas_javascript/sidebar.js"></script>
</body>
</html>