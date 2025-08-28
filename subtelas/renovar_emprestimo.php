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
          Cod_Emprestimo,
          Data_Emprestimo,
          Data_Devolucao,
        FROM emprestimo 
        WHERE Cod_Emprestimo = :id";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $autor = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$autor) {
        header('Location: consultar_emprestimo.php');
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
</body>
</html>
