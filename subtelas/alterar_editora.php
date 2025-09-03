<?php
    session_start();
    require_once '../conexao.php';

    if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3) {
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
        header('Location: consultar_editora.php');
        exit();
    }

    $id = intval($_GET['id']);

    // Buscar dados do editora com todos os campos
    $sql = "SELECT 
                Cod_Editora,
                Nome_Editora,
                Telefone,
                Email
            FROM editora
            WHERE Cod_Editora = :id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $editora = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$editora) {
            header('Location: consultar_editora.php');
            exit;
        }
    } catch (PDOException $e) {
        die("Erro na consulta: " . $e->getMessage());
    }

    // Processar formulário de alteração
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nome = trim($_POST['nome_editora']);
        $email = trim($_POST['email']);
        $telefone = trim($_POST['telefone']);
        
        if (empty($nome)) {
            $erro = "Nome é obrigatório";
        } elseif (empty($email)) {
            $erro = "Email é obrigatório";
        } elseif (empty($telefone)) {
            $erro = "Telefone é obrigatório";
        } else {
            try {
                $sql_update = "UPDATE editora 
                            SET Nome_Editora = :nome,
                                Email = :email,
                                Telefone = :telefone
                            WHERE Cod_Editora = :id";
                
                $stmt_update = $pdo->prepare($sql_update);
                $stmt_update->bindParam(':nome', $nome);
                $stmt_update->bindParam(':email', $email);
                $stmt_update->bindParam(':telefone', $telefone);
                $stmt_update->bindParam(':id', $id);
                
                if ($stmt_update->execute()) {
                    $sucesso = "success";
                    // Recarregar dados do editora
                    $stmt->execute();
                    $editora = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $erro = "error";
                }
            } catch (PDOException $e) {
                $erro = "Erro ao alterar editora: " . $e->getMessage();
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Sala Arco-íris</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/cadastros.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/notification-modal.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="page-wrapper">
        <header class="header">
            <a href="consultar_editora.php" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
            <h1>Alterar Editora</h1>
        </header>
        
        <div class="main-content">
            <form class="formulario" id="form_doador" action="alterar_editora.php?id=<?= $id ?>"  method="post">

                <div class="form-section">
                    <div class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Informações da Editora
                    </div>
                    
                    <div class="form-row">
                        <div class="input-group">
                            <label>Nome da Editora</label>
                            <div class="input-wrapper">
                                <input type="text" name="nome_editora" required id="nome_editora" placeholder="Digite o nome da editora" value="<?= htmlspecialchars($editora['Nome_Editora']) ?>">
                                <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Telefone</label>
                            <div class="input-wrapper">
                                <input type="text" name="telefone" required id="telefone" maxlength="15" oninput="formatTelefone(this)" required placeholder="(00) 00000-0000" value="<?= htmlspecialchars($editora['Telefone']) ?>">
                                <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                            </div>
                        </div>
                    </div>
                    <div class="input-group">
                            <label for="email">E-mail</label>
                            <div class="input-wrapper">
                                <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                <input type="email" id="email" name="email" required placeholder="exemplo@email.com" value="<?= htmlspecialchars($editora['Email']) ?>">
                            </div>
                        </div>
                </div>

                <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="btnAlterar">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Alterar Editora
                        </button>

                        <button type="reset" class="btn btn-secondary" onclick="document.getElementById('form_doador').reset();">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                <line x1="10" y1="11" x2="10" y2="17"/>
                                <line x1="14" y1="11" x2="14" y2="17"/>
                            </svg>
                            Limpar Formulário
                        </button>
                    </div>
            </form>
        </div>
    </div>
</body>
<script src="subtelas_javascript/validaCadastro.js"></script>
<script src="subtelas_javascript/sidebar.js"></script>

<script src="subtelas_javascript/notification-modal.js"></script>
<script>
    // Mostrar notificações baseadas no PHP
    <?php if (isset($sucesso) && $sucesso === "success"): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showNotification('success', 'Sucesso!', 'Editora alterada com sucesso!');
            // Redirecionar após 2 segundos
            setTimeout(function() {
                window.location.href = 'consultar_editora.php';
            }, 2000);
        });
    <?php endif; ?>

    <?php if (isset($erro) && $erro === "error"): ?>
        document.addEventListener('DOMContentLoaded', function() {
            showNotification('error', 'Erro!', 'Erro ao alterar editora. Tente novamente.');
        });
    <?php endif; ?>
</script>
</html>