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
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ONG Biblioteca - Alterar Autor</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css" />
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">
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

                <form method="POST" action="">
                    <div class="form-section">
                        <div class="section-title">
                            📋 Informações do Autor
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="cod_autor">Código do Autor</label>
                                <div class="input-wrapper">
                                    <input type="text" id="cod_autor" value="<?= htmlspecialchars($autor['Cod_Autor']) ?>" readonly>
                                    <span class="input-icon">🆔</span>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="nome">Nome Completo *</label>
                                <div class="input-wrapper">
                                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($autor['Nome_Autor']) ?>" required>
                                    <span class="input-icon">👤</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="telefone">Telefone</label>
                                <div class="input-wrapper">
                                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($autor['Telefone']) ?>">
                                    <span class="input-icon">📞</span>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="email">Email *</label>
                                <div class="input-wrapper">
                                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($autor['Email']) ?>" required>
                                    <span class="input-icon">✉️</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="botao">
                        <button type="submit" id="btn-salvar" class="btn">
                            💾 Salvar Alterações
                        </button>
                        <a href="consultar_autor.php" id="cancelar-edicao" class="btn">
                            ❌ Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Validação do formulário
        document.querySelector('form').addEventListener('submit', function(e) {
            const nome = document.getElementById('nome').value.trim();
            const email = document.getElementById('email').value.trim();
            
            if (nome === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O nome do autor é obrigatório!'
                });
                return false;
            }
            
            if (email === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O email do autor é obrigatório!'
                });
                return false;
            }
            
            // Confirmação antes de salvar
            if (!confirm('Tem certeza que deseja salvar as alterações?')) {
                e.preventDefault();
                return false;
            }
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
                    text: 'Por favor, insira um email válido!'
                });
                this.focus();
            }
        });
    </script>
</body>
</html>
