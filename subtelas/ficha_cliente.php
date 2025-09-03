<?php
  session_start();
  require_once '../conexao.php';

  // VERIFICA SE O USUÁRIO TEM PERMISSÃO
  if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3 && $_SESSION['perfil'] != 4) {
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
  $cliente = null;
  $erro = null;
  $cliente_id = null;

  // VERIFICA SE FOI PASSADO UM ID DE CLIENTE
  if (isset($_GET['id']) && !empty($_GET['id'])) {
    $cliente_id = $_GET['id'];
  } elseif (isset($_POST['cliente_id']) && !empty($_POST['cliente_id'])) {
    $cliente_id = $_POST['cliente_id'];
  }

  if ($cliente_id) {
    try {
      // BUSCA OS DADOS COMPLETOS DO CLIENTE
      $sql = "SELECT c.Cod_Cliente, c.Nome, c.CPF, c.Email, c.Sexo, c.Nome_Responsavel, c.Telefone, c.Data_Nascimento, c.CEP, c.UF, c.Cidade, c.Bairro, c.Rua, c.Num_Residencia, c.Foto, pc.Nome_Perfil 
                FROM cliente c 
                LEFT JOIN perfil_cliente pc ON c.Cod_Perfil = pc.Cod_Perfil 
                WHERE c.Cod_Cliente = :cliente_id";
      
      $stmt = $pdo->prepare($sql);
      $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
      $stmt->execute();
      $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
      
      if (!$cliente) {
        $erro = "Cliente não encontrado.";
      }
      
    } catch (PDOException $e) {
      $erro = "Erro na consulta: " . $e->getMessage();
    }
  } else {
    $erro = "ID do cliente não fornecido.";
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>ONG Biblioteca - Ficha do Cliente</title>
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
    
    .ficha-header .cliente-id {
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
    
    .foto-cliente {
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
  <div class="page-wrapper">
    <header>
      <a href="consultar_cliente.php" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
      <h1>Ficha do Cliente</h1>
    </header>

    <?php if (isset($erro)): ?>
      <div class="erro-mensagem">
        <h3>Erro</h3>
        <p><?= htmlspecialchars($erro) ?></p>
        <a href="consultar_cliente.php" class="btn-voltar-ficha">Voltar à Consulta</a>
      </div>
    <?php elseif ($cliente): ?>
      <div class="ficha-container">
        <div class="ficha-header">
          <h1><?= htmlspecialchars($cliente['Nome']) ?></h1>
          <div class="cliente-id">ID: <?= htmlspecialchars($cliente['Cod_Cliente']) ?></div>
        </div>
        
        <div class="ficha-content">
          <!-- Foto do Cliente -->
          <div class="foto-container">
            <?php if (!empty($cliente['Foto']) && $cliente['Foto']): ?>
              <!-- Debug: Nome da foto: <?= htmlspecialchars($cliente['Foto']) ?> -->
              <img src="subtelas_img/<?= htmlspecialchars($cliente['Foto']) ?>" alt="Foto do Cliente" class="foto-cliente" onerror="console.log('Erro ao carregar imagem:', this.src); this.style.display='none'; this.nextElementSibling.style.display='flex';">
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
                <div class="info-value"><?= htmlspecialchars($cliente['Nome']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">CPF</div>
                <div class="info-value"><?= htmlspecialchars($cliente['CPF']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value"><?= htmlspecialchars($cliente['Email']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Sexo</div>
                <div class="info-value"><?= htmlspecialchars($cliente['Sexo']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Data de Nascimento</div>
                <div class="info-value"><?= date("d/m/Y", strtotime($cliente['Data_Nascimento'])) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Perfil</div>
                <div class="info-value"><?= htmlspecialchars($cliente['Nome_Perfil'] ?? 'Não definido') ?></div>
              </div>
            </div>
          </div>
          
          <!-- Informações de Contato -->
          <div class="info-section">
            <h3>Informações de Contato</h3>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">Telefone</div>
                <div class="info-value"><?= htmlspecialchars($cliente['Telefone']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Nome do Responsável</div>
                <div class="info-value"><?= htmlspecialchars($cliente['Nome_Responsavel']) ?></div>
              </div>
            </div>
          </div>
          
          <!-- Endereço -->
          <div class="info-section">
            <h3>Endereço</h3>
            <div class="info-grid">
              <div class="info-item">
                <div class="info-label">CEP</div>
                <div class="info-value"><?= htmlspecialchars($cliente['CEP']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">UF</div>
                <div class="info-value"><?= htmlspecialchars($cliente['UF']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Cidade</div>
                <div class="info-value"><?= htmlspecialchars($cliente['Cidade']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Bairro</div>
                <div class="info-value"><?= htmlspecialchars($cliente['Bairro']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Rua</div>
                <div class="info-value"><?= htmlspecialchars($cliente['Rua']) ?></div>
              </div>
              <div class="info-item">
                <div class="info-label">Número da Residência</div>
                <div class="info-value"><?= htmlspecialchars($cliente['Num_Residencia']) ?></div>
              </div>
            </div>
          </div>
          
          <!-- Botão Voltar -->
          <div style="text-align: center;">
            <a href="consultar_cliente.php" class="btn-voltar-ficha">← Voltar à Consulta</a>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

  <script src="subtelas_javascript/consultas.js"></script>

</body>
</html>
