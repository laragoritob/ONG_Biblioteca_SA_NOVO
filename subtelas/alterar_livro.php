<?php
session_start();
require_once '../conexao.php';

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

// Verificar se foi passado um ID
if (!isset($_GET['id'])) {
    header('Location: consultar_livro.php');
    exit;
}

$id = intval($_GET['id']);

// Buscar dados do livro com JOIN para obter informações relacionadas
$sql = "SELECT 
          l.Cod_Livro,
          l.Cod_Autor,
          l.Cod_Editora,
          l.Cod_Doador,
          l.Cod_Genero,
          l.Titulo,
          l.Data_Lancamento,
          l.Data_Registro,
          l.Quantidade,
          l.Num_Prateleira,
          l.Foto,
          a.Nome_Autor,
          e.Nome_Editora,
          d.Nome_Doador
        FROM livro l
        LEFT JOIN autor a ON l.Cod_Autor = a.Cod_Autor
        LEFT JOIN editora e ON l.Cod_Editora = e.Cod_Editora
        LEFT JOIN doador d ON l.Cod_Doador = d.Cod_Doador
        WHERE l.Cod_Livro = :id";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $livro = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$livro) {
        header('Location: consultar_livro.php');
        exit;
    }
} catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
}

// Processar formulário de alteração
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $cod_autor = trim($_POST['cod_autor']);
    $cod_editora = trim($_POST['cod_editora']);
    $cod_doador = trim($_POST['cod_doador']);
    $cod_genero = trim($_POST['cod_genero']);
    $data_lancamento = trim($_POST['data_lancamento']);
    $data_registro = trim($_POST['data_registro']);
    $quantidade = trim($_POST['quantidade']);
    $num_prateleira = trim($_POST['num_prateleira']);
    
    if (empty($titulo)) {
        $erro = "Título é obrigatório";
    } elseif (empty($cod_autor)) {
        $erro = "Autor é obrigatório";
    } elseif (empty($cod_editora)) {
        $erro = "Editora é obrigatória";
    } else {
        try {
            $sql_update = "UPDATE livro 
                          SET Titulo = :titulo,
                              Cod_Autor = :cod_autor,
                              Cod_Editora = :cod_editora,
                              Cod_Doador = :cod_doador,
                              Cod_Genero = :cod_genero,
                              Data_Lancamento = :data_lancamento,
                              Data_Registro = :data_registro,
                              Quantidade = :quantidade,
                              Num_Prateleira = :num_prateleira
                          WHERE Cod_Livro = :id";
            
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindParam(':titulo', $titulo);
            $stmt_update->bindParam(':cod_autor', $cod_autor);
            $stmt_update->bindParam(':cod_editora', $cod_editora);
            $stmt_update->bindParam(':cod_doador', $cod_doador);
            $stmt_update->bindParam(':cod_genero', $cod_genero);
            $stmt_update->bindParam(':data_lancamento', $data_lancamento);
            $stmt_update->bindParam(':data_registro', $data_registro);
            $stmt_update->bindParam(':quantidade', $quantidade);
            $stmt_update->bindParam(':num_prateleira', $num_prateleira);
            $stmt_update->bindParam(':id', $id);
            
            if ($stmt_update->execute()) {
                $sucesso = "Livro alterado com sucesso!";
                // Recarregar dados do livro
                $stmt->execute();
                $livro = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $erro = "Erro ao alterar livro";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao alterar livro: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Alterar Livro</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/cadastros.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .swal2-title-arial {
            font-family: Arial, sans-serif !important;
            font-weight: bold !important;
        }
        
        .swal2-html-arial {
            font-family: Arial, sans-serif !important;
            font-size: 16px !important;
        }
        
        /* Estilo dos botões igual ao cadastro_funcionario */
        .swal2-confirm {
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1.5rem !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        }
        
        .swal2-confirm:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
        }
        
        .swal2-confirm:focus {
            outline: 2px solid #6366f1 !important;
            outline-offset: 2px !important;
        }
        
        .swal2-cancel {
            background: #dc2626 !important;
            color: white !important;
            border: none !important;
            border-radius: 0.5rem !important;
            padding: 0.75rem 1.5rem !important;
            font-size: 0.8rem !important;
            font-weight: 600 !important;
            cursor: pointer !important;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1) !important;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        }
        
        .swal2-cancel:hover {
            background: #b91c1c !important;
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <header class="header">
            <a href="consultar_livro.php" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
            <h1>Alterar Livro</h1>
        </header>
        
        <main class="main-content">
            <div class="container">
                    <form class="formulario" method="POST" action="">
                    <?php if (isset($erro)): ?>
                        <div class="alert alert-error" style="background: #fee2e2; color: #dc2626; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #fecaca;">
                            <?= htmlspecialchars($erro) ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($sucesso)): ?>
                        <div class="alert alert-success" style="background: #dcfce7; color: #16a34a; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; border: 1px solid #bbf7d0;">
                            <?= htmlspecialchars($sucesso) ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="form-section">
                        <h2 class="section-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14,2 14,8 20,8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10,9 9,9 8,9"></polyline>
                            </svg>
                            Informações do Livro
                        </h2>

                        <div class="form-row" style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div class="input-group" style="flex: 2;">
                                <label for="titulo">Título: </label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 640 640" fill="none" stroke="currentColor" stroke-width="40">
                                        <path d="M192 576L512 576C529.7 576 544 561.7 544 544C544 526.3 529.7 512 512 512L512 445.3C530.6 438.7 544 420.9 544 400L544 112C544 85.5 522.5 64 496 64L448 64L448 233.4C448 245.9 437.9 256 425.4 256C419.4 256 413.6 253.6 409.4 249.4L368 208L326.6 249.4C322.4 253.6 316.6 256 310.6 256C298.1 256 288 245.9 288 233.4L288 64L192 64C139 64 96 107 96 160L96 480C96 533 139 576 192 576zM160 480C160 462.3 174.3 448 192 448L448 448L448 512L192 512C174.3 512 160 497.7 160 480z"/>
                                    </svg>
                                    <input type="text" id="titulo" name="titulo" value="<?= htmlspecialchars($livro['Titulo']) ?>" required placeholder="Digite o titulo do livro">
                                </div>
                            </div>

                            <div class="input-group" style="flex: 0.5;">
                                <label for="id_autor">ID do Autor</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <input type="number" id="cod_autor" name="cod_autor" value="<?= htmlspecialchars($livro['Cod_Autor']) ?>" placeholder="ID" style="width: 100px;">
                                </div>
                            </div>

                            <div class="input-group" style="flex: 1.5;">
                                <label for="autor">Nome do Autor</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="none" stroke="currentColor" stroke-width="40">
                                        <path d="M432.5 82.3L382.4 132.4L507.7 257.7L557.8 207.6C579.7 185.7 579.7 150.3 557.8 128.4L511.7 82.3C489.8 60.4 454.4 60.4 432.5 82.3zM343.3 161.2L342.8 161.3L198.7 204.5C178.8 210.5 163 225.7 156.4 245.5L67.8 509.8C64.9 518.5 65.9 528 70.3 535.8L225.7 380.4C224.6 376.4 224.1 372.3 224.1 368C224.1 341.5 245.6 320 272.1 320C298.6 320 320.1 341.5 320.1 368C320.1 394.5 298.6 416 272.1 416C267.8 416 263.6 415.4 259.7 414.4L104.3 569.7C112.1 574.1 121.5 575.1 130.3 572.2L394.6 483.6C414.3 477 429.6 461.2 435.6 441.3L478.8 297.2L478.9 296.7L343.4 161.2z"/>
                                    </svg>
                                    <input type="text" id="autor" name="autor" value="<?= htmlspecialchars($livro['Nome_Autor']) ?>" required placeholder="Nome do autor" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="form-row" style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div class="input-group" style="flex: 2.0">
                                <label for="data_lancamento">Data de Lançamento</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    <input type="date" id="data_lancamento" name="data_lancamento" value="<?= htmlspecialchars($livro['Data_Lancamento']) ?>" required placeholder="Digite a data de lançamento">
                                </div>
                            </div>

                             <div class="input-group" style="flex: 0.5;">
                                <label for="cod_genero">ID do Gênero</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <input type="number" id="cod_genero_id" name="cod_genero_id" value="<?= htmlspecialchars($livro['Cod_Genero']) ?>" placeholder="ID" style="width: 100px;">
                                </div>
                            </div>

                            <div class="input-group" style="flex: 1.5">
                                <label for="genero">Gênero</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 640 640" fill="none" stroke="currentColor" stroke-width="40"> 
                                        <path d="M27 182L55.5 343.7C69.5 423.2 131.8 485.5 211.3 499.5L224 501.7C207.5 473.1 196.9 441 193.4 407.2L169.3 411.5C159.6 413.2 150.5 405.7 152.4 396C157.2 371.3 171.5 349.4 192.1 335.1L192.1 260.5C190.7 261.3 189.1 261.8 187.4 262.1L124.4 273.2C115.7 274.7 107.1 268.8 108.5 260.1C111.6 240.5 126.9 224.1 147.6 220.4C164.8 217.4 181.5 223.9 192.2 236.2L192.2 213.5C192.2 191 199.1 161.1 224.5 140.1C250.5 118.6 292.2 96.2 349.4 85.9C318.9 69.6 263.1 53.9 185.6 67.5C105.3 81.7 57.6 117.6 35.5 143.6C26.5 154.1 24.7 168.5 27.1 182.1zM240 202.7L240 377.5C240 458.2 290.5 530.4 366.4 557.9L394.1 568C408.2 573.1 423.7 573.1 437.8 568L465.6 558C541.5 530.4 592 458.3 592 377.5L592 202.7C592 195.8 589.9 188.9 585 184.1C562.4 161.6 506.8 128.1 416 128.1C325.2 128.1 269.6 161.7 247 184.1C242.1 189 240 195.8 240 202.7zM306.1 389.8C304.7 382.8 313.1 378.8 318.8 383.2C345.7 403.8 379.4 416.1 416 416.1C452.6 416.1 486.2 403.8 513.2 383.2C518.9 378.8 527.3 382.8 525.9 389.8C515.8 441.2 470.4 480.1 416 480.1C361.6 480.1 316.2 441.3 306.1 389.8zM306.6 288.3C313.2 269.5 331 256 352 256C373 256 390.9 269.5 397.4 288.3C400.3 296.7 392.9 304 384 304L320 304C311.2 304 303.7 296.6 306.6 288.3zM512 304L448 304C439.2 304 431.7 296.6 434.6 288.3C441.1 269.5 459 256 480 256C501 256 518.9 269.5 525.4 288.3C528.3 296.7 520.9 304 512 304z"/>
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    <select id="cod_genero" name="cod_genero" class="custom-select" required>
                                        <option value="">Selecione o(s) gênero(s)</option>
                                        <option value="1" <?= ($livro['Cod_Genero'] == '1') ? 'selected' : '' ?>>Ação</option>
                                        <option value="2" <?= ($livro['Cod_Genero'] == '2') ? 'selected' : '' ?>>Aventura</option>
                                        <option value="3" <?= ($livro['Cod_Genero'] == '3') ? 'selected' : '' ?>>Romance</option>
                                        <option value="4" <?= ($livro['Cod_Genero'] == '4') ? 'selected' : '' ?>>Suspense</option>
                                        <option value="5" <?= ($livro['Cod_Genero'] == '5') ? 'selected' : '' ?>>Ficção Científica</option>
                                        <option value="6" <?= ($livro['Cod_Genero'] == '6') ? 'selected' : '' ?>>Terror</option>
                                        <option value="7" <?= ($livro['Cod_Genero'] == '7') ? 'selected' : '' ?>>Educacional</option>
                                        <option value="8" <?= ($livro['Cod_Genero'] == '8') ? 'selected' : '' ?>>Horror</option>
                                        <option value="9" <?= ($livro['Cod_Genero'] == '9') ? 'selected' : '' ?>>Fantasia</option>
                                        <option value="10" <?= ($livro['Cod_Genero'] == '10') ? 'selected' : '' ?>>Autobiografia</option>
                                        <option value="11" <?= ($livro['Cod_Genero'] == '11') ? 'selected' : '' ?>>Infanto Juvenil</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-row" style="display: flex; gap: 1rem; align-items: flex-start;">
                            <div class="input-group" style="flex: 2.0;">
                                <label for="foto">Foto do Livro</label>
                                <div class="file-upload-wrapper">
                                    <input type="text" name="seletor_arquivo" id="seletor_arquivo" readonly placeholder="Nenhum arquivo selecionado" class="file-display">
                                    <input type="file" id="foto" name="foto" accept=".png, .jpeg, .jpg" style="display: none;" multiple onchange="atualizarNomeArquivo()">
                                    <button type="button" class="file-select-btn" onclick="document.getElementById('foto').click()">
                                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                            <polyline points="7,10 12,15 17,10"/>
                                            <line x1="12" y1="15" x2="12" y2="3"/>
                                        </svg>
                                        Selecionar Foto
                                    </button>
                                </div>
                            </div>

                            <div class="input-group" style="flex: 0.5;">
                                <label for="cod_editora">ID da Editora</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <input type="number" id="cod_editora" name="cod_editora" value="<?= htmlspecialchars($livro['Cod_Editora']) ?>" required placeholder="ID">
                                </div>
                            </div> 

                            <div class="input-group" style="flex: 1.5;">
                                <label for="nome_editora">Nome da Editora</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="none" stroke="currentColor" stroke-width="50">
                                        <path d="M96.5 160L96.5 309.5C96.5 326.5 103.2 342.8 115.2 354.8L307.2 546.8C332.2 571.8 372.7 571.8 397.7 546.8L547.2 397.3C572.2 372.3 572.2 331.8 547.2 306.8L355.2 114.8C343.2 102.7 327 96 310 96L160.5 96C125.2 96 96.5 124.7 96.5 160zM208.5 176C226.2 176 240.5 190.3 240.5 208C240.5 225.7 226.2 240 208.5 240C190.8 240 176.5 225.7 176.5 208C176.5 190.3 190.8 176 208.5 176z"/>
                                    </svg>
                                    <input type="text" id="nome_editora" name="nome_editora" value="<?= htmlspecialchars($livro['Nome_Editora']) ?>" required placeholder="Nome da editora" readonly>
                                </div>
                            </div> 
                        </div>

                        <br>

                        <div class="form-section">
                            <h2 class="section-title">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                    <circle cx="12" cy="7" r="4"/>
                                </svg>
                                Informações da Biblioteca
                            </h2>
                                
                            <div class="form-row">
                                <div class="input-group">
                                    <label for="data_registro">Data de Registro</label>
                                    <div class="input-wrapper">
                                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                            <line x1="16" y1="2" x2="16" y2="6"/>
                                            <line x1="8" y1="2" x2="8" y2="6"/>
                                            <line x1="3" y1="10" x2="21" y2="10"/>
                                        </svg>
                                        <input type="date" id="data_registro" name="data_registro" value="<?= htmlspecialchars($livro['Data_Registro']) ?>" required placeholder="Digite a data de registro">
                                    </div>
                                </div>
                            
                                <div class="input-group">
                                    <label for="num_prateleira">Número da Prateleira</label>
                                    <div class="input-wrapper">
                                        <svg class="input-icon" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="none" stroke="currentColor" stroke-width="40">
                                            <path d="M129.5 464L179.5 304L558.9 304L508.9 464L129.5 464zM320.2 512L509 512C530 512 548.6 498.4 554.8 478.3L604.8 318.3C614.5 287.4 591.4 256 559 256L179.6 256C158.6 256 140 269.6 133.8 289.7L112.2 358.4L112.2 160C112.2 151.2 119.4 144 128.2 144L266.9 144C270.4 144 273.7 145.1 276.5 147.2L314.9 176C328.7 186.4 345.6 192 362.9 192L480.2 192C489 192 496.2 199.2 496.2 208L544.2 208C544.2 172.7 515.5 144 480.2 144L362.9 144C356 144 349.2 141.8 343.7 137.6L305.3 108.8C294.2 100.5 280.8 96 266.9 96L128.2 96C92.9 96 64.2 124.7 64.2 160L64.2 448C64.2 483.3 92.9 512 128.2 512L320.2 512z"/>
                                        </svg>
                                        <input type="number" id="num_prateleira" name="num_prateleira" value="<?= htmlspecialchars($livro['Num_Prateleira']) ?>" required placeholder="Digite o número da prateleira">
                                    </div>
                                </div>
                            </div>

                            <div class="form-row" style="display: flex; gap: 1rem; align-items: flex-start;">
                                <div class="input-group" style="flex: 2.0;">
                                    <label for="quantidade">Quantidade</label>
                                    <div class="input-wrapper">
                                        <svg class="input-icon" width="20" height="20" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" fill="none" stroke="currentColor" stroke-width="40">
                                            <path d="M112 120C112 106.7 101.3 96 88 96C74.7 96 64 106.7 64 120L64 464C64 508.2 99.8 544 144 544L552 544C565.3 544 576 533.3 576 520C576 506.7 565.3 496 552 496L144 496C126.3 496 112 481.7 112 464L112 120zM216 192L424 192C437.3 192 448 181.3 448 168C448 154.7 437.3 144 424 144L216 144C202.7 144 192 154.7 192 168C192 181.3 202.7 192 216 192zM216 256C202.7 256 192 266.7 192 280C192 293.3 202.7 304 216 304L360 304C373.3 304 384 293.3 384 280C384 266.7 373.3 256 360 256L216 256zM216 368C202.7 368 192 378.7 192 392C192 405.3 202.7 416 216 416L488 416C501.3 416 512 405.3 512 392C512 378.7 501.3 368 488 368L216 368z"/>
                                        </svg>
                                        <input type="number" id="quantidade" name="quantidade" value="<?= htmlspecialchars($livro['Quantidade']) ?>" required placeholder="Digite a quantidade do livro">
                                    </div>
                                </div>

                                <div class="input-group" style="flex: 0.5;">
                                    <label for="cod_doador">ID do Doador</label>
                                    <div class="input-wrapper">
                                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                        <input type="number" id="cod_doador" name="cod_doador" value="<?= htmlspecialchars($livro['Cod_Doador']) ?>" required placeholder="ID">
                                    </div>
                                </div>

                                <div class="input-group" style="flex: 1.5;">
                                    <label for="nome_doador">Nome do Doador</label>
                                    <div class="input-wrapper">
                                        <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                            <circle cx="12" cy="7" r="4"/>
                                        </svg>
                                        <input type="text" id="nome_doador" name="nome_doador" value="<?= htmlspecialchars($livro['Nome_Doador']) ?>" required placeholder="Nome do doador" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="btn-salvar">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Salvar Alterações
                            </button>

                            <a href="consultar_livro.php" class="btn btn-secondary">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    <line x1="10" y1="11" x2="10" y2="17"/>
                                    <line x1="14" y1="11" x2="14" y2="17"/>
                                </svg>
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </main>
        </div>

    <script src="subtelas_javascript/validaCadastro.js"></script>
    <script src="subtelas_javascript/buscarID.js"></script>
    <script>
        // Validação específica para alterar livro
        document.querySelector('form').addEventListener('submit', function(e) {
            const titulo = document.getElementById('titulo').value.trim();
            const cod_autor = document.getElementById('cod_autor').value.trim();
            const cod_editora = document.getElementById('cod_editora').value.trim();
            const quantidade = document.getElementById('quantidade').value.trim();
            
            if (titulo === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O título do livro é obrigatório!',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return false;
            }
            
            if (cod_autor === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O código do autor é obrigatório!',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return false;
            }
            
            if (cod_editora === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O código da editora é obrigatório!',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return false;
            }
            
            // Validação da quantidade
            if (quantidade !== '' && parseInt(quantidade) < 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Quantidade Inválida',
                    text: 'A quantidade deve ser maior ou igual a zero!',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return false;
            }
        });
</body>
</html>