<?php
    session_start();
    require_once '../conexao.php';

    // VERIFICA SE O USUÁRIO TEM PERMISSÃO
    // SUPONDO QUE O PERFIL 1 SEJA O ADMINISTRADOR
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

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nome_autor = trim($_POST['nome_autor']);
        $telefone = trim($_POST['telefone']);
        $email = trim($_POST['email']);

        // Validação do telefone
        $telefone_limpo = preg_replace('/\D/', '', $telefone); // Remove caracteres não numéricos
        if (strlen($telefone_limpo) < 10 || strlen($telefone_limpo) > 11) {
            $erro = "O telefone deve ter 10 ou 11 dígitos!";
        } else {
            $sql = "INSERT INTO autor (nome_autor,telefone,email) 
                        VALUES (:nome_autor,:telefone,:email)";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome_autor', $nome_autor);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                $sucesso = "Autor cadastrado com sucesso!";
            } else {
                $erro = "Erro ao cadastrar autor!";
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
    <link rel="stylesheet" type="text/css" href="subtelas_css/notification-modal.css">
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
    </style>

</head>
<body>
    <div class="page-wrapper">
        <header class="header">
            <a href="<?= $linkVoltar ?>" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
            <h1>Registrar Autor</h1>
        </header>

        <div class="main-content">
            <form class="formulario" id="form_autor" action="registrar_autor.php" method="post">

                <div class="form-section">
                    <div class="section-title">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14,2 14,8 20,8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10,9 9,9 8,9"></polyline>
                        </svg>
                        Informações do Autor
                    </div>
                    
                    <div class="form-row">
                        <div class="input-group">
                            <label>Nome do Autor</label>
                            <div class="input-wrapper">
                                <input type="text" name="nome_autor" required id="nome_autor" placeholder="Digite o nome do autor" oninput="validarNome(this)">
                                <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                            </div>
                        </div>

                        <div class="input-group">
                            <label>Telefone</label>
                            <div class="input-wrapper">
                                <input type="text" name="telefone" required id="telefone" maxlength="15" oninput="formatTelefone(this)" required placeholder="(00) 00000-0000">
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
                                <input type="email" id="email" name="email" required placeholder="exemplo@email.com">
                            </div>
                        </div>
                </div>

                <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="btnCadastrar">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Cadastrar Autor
                        </button>


                        <button type="reset" class="btn btn-secondary" onclick="document.getElementById('form_pessoal').reset(); document.getElementById('arquivo').value = '';">
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
    <script>
        // Função para validar nome em tempo real
        function validarNome(input) {
            const nome = input.value.trim();
            
            if (nome.length > 0 && nome.length < 3) {
                input.style.borderColor = '#dc2626';
                input.style.backgroundColor = '#fef2f2';
            } else {
                input.style.borderColor = '';
                input.style.backgroundColor = '';
            }
        }
        
        // Validação específica para registrar autor
        document.getElementById('form_autor').addEventListener('submit', function(e) {
            const nome = document.getElementById('nome_autor').value.trim();
            const telefone = document.getElementById('telefone').value.trim();
            const email = document.getElementById('email').value.trim();
            
            // Validação do nome
            if (nome.length < 3) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Nome Inválido',
                    text: 'O nome deve conter pelo menos 3 letras!',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return false;
            }
            
            // Validação do email
            if (email === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Email Obrigatório',
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
    
    <script src="subtelas_javascript/notification-modal.js"></script>
    <script>
        // Mostrar notificações baseadas no PHP
        <?php if (isset($sucesso)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('success', 'Sucesso!', '<?= addslashes($sucesso) ?>');
            });
        <?php endif; ?>
        
        <?php if (isset($erro)): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showNotification('error', 'Erro!', '<?= addslashes($erro) ?>');
            });
        <?php endif; ?>
    </script>
</html>