<?php
  session_start();
  require_once '../conexao.php';

  // VERIFICA SE O USUÁRIO TEM PERMISSÃO
  if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2) {
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
  $funcionario = null;
  $erro = null;
  $funcionario_id = null;

  // VERIFICA SE FOI PASSADO UM ID DE FUNCIONÁRIO
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $funcionario_id = $_GET['id'];
  } elseif (isset($_POST['funcionario_id']) && !empty($_POST['funcionario_id'])) {
    $funcionario_id = $_POST['funcionario_id'];
  }

  if ($funcionario_id) {
    try {
      // BUSCA OS DADOS COMPLETOS DO FUNCIONÁRIO
      $sql = "SELECT f.Cod_Funcionario, f.Nome, f.CPF, f.Email, f.Sexo, f.Telefone, f.Data_Nascimento, f.CEP, f.UF, f.Cidade, f.Bairro, f.Rua, f.Num_Residencia, f.Foto, f.Data_Efetivacao, f.Usuario, pf.Nome_Perfil as Cargo
                FROM funcionario f 
                LEFT JOIN perfil_funcionario pf ON f.Cod_Perfil = pf.Cod_Perfil
                WHERE f.Cod_Funcionario = :funcionario_id";
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':funcionario_id', $funcionario_id, PDO::PARAM_INT);
      $stmt->execute();
      $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$funcionario) {
        $erro = "Funcionário não encontrado.";
      }
      
    } catch (PDOException $e) {
      $erro = "Erro na consulta: " . $e->getMessage();
    }
  } else {
    $erro = "ID do funcionário não fornecido.";
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Ficha do Funcionário</title>
  <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css" />
  <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css" />
  <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css">
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
    
    .ficha-header .funcionario-id {
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
    
    .foto-funcionario {
      width: 150px;
      height: 150px;
      border-radius: 50%;
      object-fit: cover;
      border: 4px solid #667eea;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }
    
    .foto-placeholder {
      width: 150px;
      height: 150px;
      border-radius: 50%;
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
    <?php include 'includes/sidebar-dropdown.php'; ?>
  <div class="page-wrapper">
    <header>
      <a href="consultar_funcionario.php" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
      <h1>Ficha do Funcionário</h1>
    </header>

    <?php if (isset($erro)): ?>
      <div class="erro-mensagem">
        <h3>Erro</h3>
        <p><?= htmlspecialchars($erro) ?></p>
        <a href="consultar_funcionario.php" class="btn-voltar-ficha">Voltar à Consulta</a>
      </div>
    <?php elseif ($funcionario): ?>
      <div class="ficha-container">
        <div class="ficha-header">
          <h1><?= htmlspecialchars($funcionario['Nome']) ?></h1>
          <div class="funcionario-id">ID: <?= htmlspecialchars($funcionario['Cod_Funcionario']) ?></div>
        </div>
        
        <div class="ficha-content">
          <!-- Foto do Funcionário -->
          <div class="foto-container">
            <?php if (!empty($funcionario['Foto']) && $funcionario['Foto']): ?>
              <!-- Debug: Nome da foto: <?= htmlspecialchars($funcionario['Foto']) ?> -->
              <img src="subtelas_img/<?= htmlspecialchars($funcionario['Foto']) ?>" alt="Foto do Funcionário" class="foto-funcionario" onerror="console.log('Erro ao carregar imagem:', this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';">
              <div class="foto-placeholder" style="display: none;">
                👤
              </div>
            <?php else: ?>
              <div class="foto-placeholder">
                👤
              </div>
            <?php endif; ?>
          </div>
          
          <!-- Informações Pessoais -->
          <div class="info-section">
            <h3>Informações Pessoais</h3>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Nome Completo</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['Nome']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">CPF</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['CPF']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['Email']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Sexo</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['Sexo']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Data de Nascimento</div>
                <div class="info-value"><?= date("d/m/Y", strtotime($funcionario['Data_Nascimento'])) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Cargo</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['Cargo'] ?? 'Não definido') ?></div>
              </div>
            </div>
          </div>
          
          <!-- Informações Profissionais -->
          <div class="info-section">
            <h3>Informações Profissionais</h3>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Data de Efetivação</div>
                <div class="info-value"><?= date("d/m/Y", strtotime($funcionario['Data_Efetivacao'])) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Usuário</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['Usuario']) ?></div>
              </div>
            </div>
          </div>
          
          <!-- Informações de Contato -->
          <div class="info-section">
            <h3>Informações de Contato</h3>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Telefone</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['Telefone']) ?></div>
              </div>
            </div>
          </div>
          
          <!-- Endereço -->
          <div class="info-section">
            <h3>Endereço</h3>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">CEP</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['CEP']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">UF</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['UF']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Cidade</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['Cidade']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Bairro</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['Bairro']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Rua</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['Rua']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Número da Residência</div>
                <div class="info-value"><?= htmlspecialchars($funcionario['Num_Residencia']) ?></div>
              </div>
            </div>
          </div>
          
          <!-- Botão Voltar -->
          <div style="text-align: center;">
            <a href="consultar_funcionario.php" class="btn-voltar-ficha">← Voltar à Consulta</a>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script src="subtelas_javascript/consultas.js"></script>
  <script src="subtelas_javascript/sidebar-dropdown.js"></script>
</body>
</html>
