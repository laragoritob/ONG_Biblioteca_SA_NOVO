<?php
session_start();
require_once '../conexao.php';

// Verificar se foi passado um ID
if (!isset($_GET['id'])) {
    header('Location: consultar_autor.php');
    exit;
}

$id = intval($_GET['id']);

// Buscar dados do autor
$sql = "SELECT 
          Cod_Autor,
          Nome_Autor,
          Telefone,
          Email
        FROM autor 
        WHERE Cod_Autor = :id";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $autor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$autor) {
        header('Location: consultar_autor.php');
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
        // Validação do telefone
        if (!empty($telefone)) {
            $telefone_limpo = preg_replace('/\D/', '', $telefone); // Remove caracteres não numéricos
            if (strlen($telefone_limpo) < 10 || strlen($telefone_limpo) > 11) {
                $erro = "O telefone deve ter 10 ou 11 dígitos";
            }
        }
        
        if (!isset($erro)) {
            try {
                $sql_update = "UPDATE autor 
                              SET Nome_Autor = :nome,
                                  Telefone = :telefone,
                                  Email = :email
                              WHERE Cod_Autor = :id";
                
                $stmt_update = $pdo->prepare($sql_update);
                $stmt_update->bindParam(':nome', $nome);
                $stmt_update->bindParam(':telefone', $telefone);
                $stmt_update->bindParam(':email', $email);
                $stmt_update->bindParam(':id', $id);
                
                if ($stmt_update->execute()) {
                    $sucesso = "Autor alterado com sucesso!";
                    // Recarregar dados do autor
                    $stmt->execute();
                    $autor = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $erro = "Erro ao alterar autor";
                }
            } catch (PDOException $e) {
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
    <link rel="stylesheet" type="text/css" href="subtelas_css/notification-modal.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="page-wrapper">
        <header>
            <form action="consultar_autor.php" method="POST">
                <button class="btn-voltar">← Voltar</button>
            </form>
            <h1>Alterar Autor</h1>
        </header>

        <div class="main-content">
            <div class="formulario">


                <form method="POST" action="">
                    <div class="form-section">
                        <div class="section-title">
                            📋 Informações do Autor
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="nome">Nome Completo *</label>
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
                                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($autor['Telefone']) ?>" maxlength="15" oninput="formatTelefone(this)" placeholder="(00) 00000-0000">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                            <div class="input-group">
                                <label for="email">Email *</label>
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
                    confirmButtonColor: '#ffbcfc'
                });
                return false;
            }
            
            if (email === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O email do autor é obrigatório!',
                    confirmButtonColor: '#ffbcfc'
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
                        confirmButtonColor: '#ffbcfc'
                    });
                    return false;
                }
            }
            
            // Confirmação antes de salvar
            Swal.fire({
                title: 'Confirmar Alteração',
                text: 'Tem certeza que deseja salvar as alterações?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Sim, Salvar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ffbcfc',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Continua com o envio do formulário
                } else {
                    e.preventDefault();
                    return false;
                }
            });
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
</body>
</html>
