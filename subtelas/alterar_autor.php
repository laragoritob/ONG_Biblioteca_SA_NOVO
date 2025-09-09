<?php
// Inicia a sessão para verificar autenticação e perfil do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se o usuário tem permissão para acessar esta página
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3) {

    // Se não tem permissão, exibe alerta e redireciona para login
    echo "<script>alert('Acesso Negado!');window.location.href='../index.php';</script>";
    exit();
}


// Define qual página o usuário deve retornar baseado em seu perfil
switch ($_SESSION['perfil']) {
    case 1: // Gerente - pode acessar todas as funcionalidades
        $linkVoltar = "../gerente.php";
        break;
    case 2: // Gestor - não tem acesso a esta página, mas mantido para consistência
        $linkVoltar = "../gestor.php";
        break;
    case 3: // Bibliotecário - pode gerenciar autores
        $linkVoltar = "../bibliotecario.php";
        break;
    case 4: // Recreador - não tem acesso a esta página
        $linkVoltar = "../recreador.php";
        break;
    case 5: // Repositor - não tem acesso a esta página
        $linkVoltar = "../repositor.php";
        break;
    default:
        // Se perfil não for reconhecido, redireciona para login
        $linkVoltar = "../index.php";
        break;
}

// Verifica se foi passado um ID válido via GET
// Se não houver ID, redireciona para a página de consulta
if (!isset($_GET['id'])) {
    header('Location: consultar_autor.php');
    exit;
}

// Converte o ID para inteiro para segurança (previne SQL injection)
$id = intval($_GET['id']);

// Consulta o banco de dados para obter os dados atuais do autor que será editado
$sql = "SELECT 
          Cod_Autor,
          Nome_Autor,
          Telefone,
          Email
        FROM autor 
        WHERE Cod_Autor = :id";

try {
    // Prepara a consulta SQL usando prepared statement (segurança)
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $autor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Se não encontrou o autor, redireciona para consulta
    if (!$autor) {
        header('Location: consultar_autor.php');
        exit;
    }
} catch (PDOException $e) {
    // Em caso de erro na consulta, exibe mensagem e para execução
    die("Erro na consulta: " . $e->getMessage());
}

// Executado apenas quando o formulário é enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Remove espaços em branco do início e fim dos campos
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
// Verifica se os campos obrigatórios foram preenchidos
    if (empty($nome)) {
        $erro = "Nome é obrigatório";
    } elseif (empty($email)) {
        $erro = "Email é obrigatório";
    } else {

// Se o telefone foi preenchido, valida o formato
        if (!empty($telefone)) {
            // Remove todos os caracteres que não são dígitos
            $telefone_limpo = preg_replace('/\D/', '', $telefone);
            // Verifica se tem 10 (telefone fixo) ou 11 (celular) dígitos
            if (strlen($telefone_limpo) < 10 || strlen($telefone_limpo) > 11) {
                $erro = "O telefone deve ter 10 ou 11 dígitos";
            }
        }
        
// Se não há erros de validação, procede com a atualização
        if (!isset($erro)) {
            try {
                // Query SQL para atualizar os dados do autor
                $sql_update = "UPDATE autor 
                              SET Nome_Autor = :nome,
                                  Telefone = :telefone,
                                  Email = :email
                              WHERE Cod_Autor = :id";
                
                // Prepara a query usando prepared statement
                $stmt_update = $pdo->prepare($sql_update);
                $stmt_update->bindParam(':nome', $nome);
                $stmt_update->bindParam(':telefone', $telefone);
                $stmt_update->bindParam(':email', $email);
                $stmt_update->bindParam(':id', $id);
                
                // Executa a atualização
                if ($stmt_update->execute()) {
                    // Se sucesso, define mensagem de sucesso
                    $sucesso = "Autor alterado com sucesso!";
                    // Recarrega os dados do autor para exibir na tela
                    $stmt->execute();
                    $autor = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    // Se falhou, define mensagem de erro
                    $erro = "Erro ao alterar autor";
                }
            } catch (PDOException $e) {
                // Em caso de erro na execução, captura e exibe a mensagem
                $erro = "Erro ao alterar autor: " . $e->getMessage();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ONG Biblioteca - Alterar Autor</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css" />
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css">

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
    <?php include 'includes/sidebar-dropdown.php'; ?>
    <div class="page-wrapper">
        <header class="header">
            <a href="consultar_autor.php" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
            <h1>Alterar Autor</h1>
        </header>

        <div class="main-content">
            <div class="formulario">
                <form method="POST" action="">
                    <div class="form-section">
                        <div class="section-title">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            Informações do Autor
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="nome">Nome Completo</label>
                                <div class="input-wrapper">
                                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($autor['Nome_Autor']) ?>" required>
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="telefone">Telefone</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($autor['Telefone']) ?>" maxlength="15" oninput="formatTelefone(this)" placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>

                            <div class="input-group">
                                <label for="email">Email</label>
                                <div class="input-wrapper">
                                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($autor['Email']) ?>" required>
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                    <polyline points="22,6 12,13 2,6"/>
                                </svg>
                                </div>
                            </div>
                        </div>

                    <div class="botao">
                        <button type="submit" id="btn-salvar" class="btn">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Salvar Alterações
                        </button>
                        <a href="consultar_autor.php" id="cancelar-edicao" class="btn">
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
        </div>
    </div>

    <script src="subtelas_javascript/validaCadastro.js"></script>
    <script>
        // Validação específica para alterar autor
        document.querySelector('form').addEventListener('submit', function(e) {
            const nome = document.getElementById('nome').value.trim();
            const email = document.getElementById('email').value.trim();
            const telefone = document.getElementById('telefone').value.trim();
            
            if (nome === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O nome do autor é obrigatório!',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return false;
            }
            
            if (email === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O email do autor é obrigatório!',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return false;
            }
            
            // Validação do telefone
            if (telefone !== '') {
                const telefoneLimpo = telefone.replace(/\D/g, '');
                if (telefoneLimpo.length < 10 || telefoneLimpo.length > 11) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'error',
                        title: 'Telefone Inválido',
                        text: 'O telefone deve ter 10 ou 11 dígitos!',
                        customClass: {
                            title: 'swal2-title-arial',
                            confirmButton: 'swal2-confirm'
                        }
                    });
                    return false;
                }
            }
        });
    </script>
    
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
    </script>
    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
</body>
</html>
