<?php
session_start();
require_once '../conexao.php';

if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 2 && $_SESSION['perfil'] != 3) {
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
    header('Location: consultar_doador.php');
    exit;
}

$id = intval($_GET['id']);

// Buscar dados do doador
$sql = "SELECT 
          d.Cod_Doador,
          d.Nome_Doador,
          d.Telefone,
          d.Email
        FROM doador d
        WHERE d.Cod_Doador = :id";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $doador = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$doador) {
        header('Location: consultar_doador.php');
        exit;
    }
} catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
}

// Processar formulário de alteração
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    
    if (empty($nome)) {
        $erro = "Nome é obrigatório";
    } elseif (empty($email)) {
        $erro = "Email é obrigatório";
    } else {
        try {
            $sql_update = "UPDATE doador 
                          SET Nome_Doador = :nome,
                              Telefone = :telefone,
                              Email = :email
                          WHERE Cod_Doador = :id";
            
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindParam(':nome', $nome);
            $stmt_update->bindParam(':telefone', $telefone);
            $stmt_update->bindParam(':email', $email);
            $stmt_update->bindParam(':id', $id);
            
            if ($stmt_update->execute()) {
                $sucesso = "Doador alterado com sucesso!";
                // Recarregar dados do doador
                $stmt->execute();
                $doador = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $erro = "Erro ao alterar doador";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao alterar doador: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ONG Biblioteca - Alterar Doador</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/cadastros.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <?php include 'includes/sidebar-dropdown.php'; ?>
    <div class="page-wrapper">
        <header class="header">
            <form action="consultar_doador.php" method="POST">
                <button class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </button>
            </form>
            <h1>Alterar Doador</h1>
        </header>

        <main class="main-content">
            <div class="container">
                <form class="formulario" method="POST" action="">
                    
                    <section class="form-section">
                        <h2 class="section-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            Informações do Doador
                        </h2>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="cod_doador">Código do Doador</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14,2 14,8 20,8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                        <polyline points="10,9 9,9 8,9"/>
                                    </svg>
                                    <input type="text" id="cod_doador" value="<?= htmlspecialchars($doador['Cod_Doador']) ?>" readonly>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="nome">Nome Completo</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($doador['Nome_Doador']) ?>" required placeholder="Digite o nome completo">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="telefone">Telefone</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($doador['Telefone']) ?>" placeholder="(00) 00000-0000">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="email">E-mail</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                        <polyline points="22,6 12,13 2,6"/>
                                    </svg>
                                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($doador['Email']) ?>" required placeholder="exemplo@email.com">
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/>
                                <polyline points="17,21 17,13 7,13 7,21"/>
                                <polyline points="7,3 7,8 15,8"/>
                            </svg>
                            Salvar Alterações
                        </button>
                        
                        <a href="consultar_doador.php" class="btn btn-secondary">
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


    
    <script>
        // Mostrar notificações baseadas no PHP
        <?php if (isset($sucesso)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Sucesso!',
                    text: '<?= addslashes($sucesso) ?>',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
            });
        <?php endif; ?>
        
        <?php if (isset($erro)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro!',
                    text: '<?= addslashes($erro) ?>',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
            });
        <?php endif; ?>

        // Validação do formulário
        document.querySelector('form').addEventListener('submit', function(e) {
            const nome = document.getElementById('nome').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (nome === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O nome do doador é obrigatório!',
                    confirmButtonColor: '#ffbcfc'
                });
                return false;
            }
            
            if (email === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O email do doador é obrigatório!',
                    confirmButtonColor: '#ffbcfc'
                });
                return false;
            }
            
            // Confirmação antes de salvar
            Swal.fire({
                title: 'Confirmar Alteração',
                text: 'Tem certeza que deseja salvar as alterações?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#ffbcfc',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, salvar!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (!result.isConfirmed) {
                    e.preventDefault();
                    return false;
                }
            });
        });

        // Formatação automática de telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                if (value.length === 11) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
                } else if (value.length === 10) {
                    value = value.replace(/(\d{2})(\d{4})(\d{4})/, '($1) $2-$3');
                }
                e.target.value = value;
            }
        });

        // Validação de email
        document.getElementById('email').addEventListener('blur', function() {
            const email = this.value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (email && !emailRegex.test(email)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Formato de Email Inválido',
                    text: 'Por favor, insira um email válido!',
                    confirmButtonColor: '#ffbcfc'
                });
                this.focus();
            }
        });
    </script>
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
</body>
</html>
