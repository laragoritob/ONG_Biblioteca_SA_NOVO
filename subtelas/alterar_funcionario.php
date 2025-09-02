<?php
session_start();
require_once '../conexao.php';

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

// Verificar se foi passado um ID
if (!isset($_GET['id'])) {
    header('Location: consultar_funcionario.php');
    exit;
}

$id = intval($_GET['id']);

// Buscar dados do funcionário com todos os campos
$sql = "SELECT 
          f.Cod_Funcionario,
          f.Cod_Perfil,
          f.Nome,
          f.CPF,
          f.Email,
          f.Sexo,
          f.Telefone,
          f.Data_Nascimento,
          f.Data_Efetivacao,
          f.CEP,
          f.UF,
          f.Cidade,
          f.Bairro,
          f.Rua,
          f.Num_Residencia,
          f.Usuario,
          f.Senha,
          f.Foto,
          p.Nome_Perfil
        FROM funcionario f
        JOIN perfil_funcionario p ON f.Cod_Perfil = p.Cod_Perfil
        WHERE f.Cod_Funcionario = :id";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$funcionario) {
        header('Location: consultar_funcionario.php');
        exit;
    }
} catch (PDOException $e) {
    die("Erro na consulta: " . $e->getMessage());
}

// Buscar todos os perfis para o select
$sql_perfis = "SELECT Cod_Perfil, Nome_Perfil FROM perfil_funcionario ORDER BY Nome_Perfil";
try {
    $stmt_perfis = $pdo->prepare($sql_perfis);
    $stmt_perfis->execute();
    $perfis = $stmt_perfis->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar perfis: " . $e->getMessage());
}

// Processar formulário de alteração
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome']);
    $cpf = trim($_POST['cpf']);
    $email = trim($_POST['email']);
    $sexo = $_POST['sexo'];
    $telefone = trim($_POST['telefone']);
    $data_nascimento = $_POST['data_nascimento'];
    $data_efetivacao = $_POST['data_efetivacao'];
    $cep = trim($_POST['cep']);
    $uf = $_POST['uf'];
    $cidade = trim($_POST['cidade']);
    $bairro = trim($_POST['bairro']);
    $rua = trim($_POST['rua']);
    $num_residencia = trim($_POST['num_residencia']);
    $usuario = trim($_POST['usuario']);
    $senha = trim($_POST['senha']);
    $cod_perfil = intval($_POST['cod_perfil']);
    
    if (empty($nome)) {
        $erro = "Nome é obrigatório";
    } elseif (empty($cpf)) {
        $erro = "CPF é obrigatório";
    } elseif (empty($email)) {
        $erro = "Email é obrigatório";
    } elseif (empty($usuario)) {
        $erro = "Usuário é obrigatório";
    } else {
        try {
            $sql_update = "UPDATE funcionario 
                          SET Nome = :nome,
                              CPF = :cpf,
                              Email = :email,
                              Sexo = :sexo,
                              Telefone = :telefone,
                              Data_Nascimento = :data_nascimento,
                              Data_Efetivacao = :data_efetivacao,
                              CEP = :cep,
                              UF = :uf,
                              Cidade = :cidade,
                              Bairro = :bairro,
                              Rua = :rua,
                              Num_Residencia = :num_residencia,
                              Usuario = :usuario,
                              Cod_Perfil = :cod_perfil
                          WHERE Cod_Funcionario = :id";
            
            $stmt_update = $pdo->prepare($sql_update);
            $stmt_update->bindParam(':nome', $nome);
            $stmt_update->bindParam(':cpf', $cpf);
            $stmt_update->bindParam(':email', $email);
            $stmt_update->bindParam(':sexo', $sexo);
            $stmt_update->bindParam(':telefone', $telefone);
            $stmt_update->bindParam(':data_nascimento', $data_nascimento);
            $stmt_update->bindParam(':data_efetivacao', $data_efetivacao);
            $stmt_update->bindParam(':cep', $cep);
            $stmt_update->bindParam(':uf', $uf);
            $stmt_update->bindParam(':cidade', $cidade);
            $stmt_update->bindParam(':bairro', $bairro);
            $stmt_update->bindParam(':rua', $rua);
            $stmt_update->bindParam(':num_residencia', $num_residencia);
            $stmt_update->bindParam(':usuario', $usuario);
            $stmt_update->bindParam(':cod_perfil', $cod_perfil);
            $stmt_update->bindParam(':id', $id);
            
            // Se uma nova senha foi fornecida, atualizar também
            if (!empty($senha)) {
                $sql_update_senha = "UPDATE funcionario SET Senha = :senha WHERE Cod_Funcionario = :id";
                $stmt_senha = $pdo->prepare($sql_update_senha);
                $stmt_senha->bindParam(':senha', $senha);
                $stmt_senha->bindParam(':id', $id);
                $stmt_senha->execute();
            }
            
            if ($stmt_update->execute()) {
                $sucesso = "Funcionário alterado com sucesso!";
                // Recarregar dados do funcionário
                $stmt->execute();
                $funcionario = $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                $erro = "Erro ao alterar funcionário";
            }
        } catch (PDOException $e) {
            $erro = "Erro ao alterar funcionário: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>ONG Biblioteca - Alterar Funcionário</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css" />
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="page-wrapper">
        <header>
            <a href="<?= $linkVoltar ?>" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
            <h1>Alterar Funcionário</h1>
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
                            📋 Informações do Funcionário
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="cod_funcionario">Código do Funcionário</label>
                                <div class="input-wrapper">
                                    <input type="text" id="cod_funcionario" value="<?= htmlspecialchars($funcionario['Cod_Funcionario']) ?>" readonly>
                                    <span class="input-icon">🆔</span>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="nome">Nome Completo *</label>
                                <div class="input-wrapper">
                                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($funcionario['Nome']) ?>" required>
                                    <span class="input-icon">👤</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="cpf">CPF *</label>
                                <div class="input-wrapper">
                                    <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($funcionario['CPF']) ?>" required>
                                    <span class="input-icon">🆔</span>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="email">Email *</label>
                                <div class="input-wrapper">
                                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($funcionario['Email']) ?>" required>
                                    <span class="input-icon">✉️</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="sexo">Sexo</label>
                                <div class="input-wrapper">
                                    <select id="sexo" name="sexo" required>
                                        <option value="M" <?= ($funcionario['Sexo'] == 'M') ? 'selected' : '' ?>>Masculino</option>
                                        <option value="F" <?= ($funcionario['Sexo'] == 'F') ? 'selected' : '' ?>>Feminino</option>
                                        <option value="O" <?= ($funcionario['Sexo'] == 'O') ? 'selected' : '' ?>>Outro</option>
                                    </select>
                                    <span class="input-icon">🚻</span>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="telefone">Telefone</label>
                                <div class="input-wrapper">
                                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($funcionario['Telefone']) ?>">
                                    <span class="input-icon">📞</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="data_nascimento">Data de Nascimento</label>
                                <div class="input-wrapper">
                                    <input type="date" id="data_nascimento" name="data_nascimento" value="<?= $funcionario['Data_Nascimento'] ?>" required>
                                    <span class="input-icon">🎂</span>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="data_efetivacao">Data de Efetivação</label>
                                <div class="input-wrapper">
                                    <input type="date" id="data_efetivacao" name="data_efetivacao" value="<?= $funcionario['Data_Efetivacao'] ?>" required>
                                    <span class="input-icon">📅</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="cep">CEP</label>
                                <div class="input-wrapper">
                                    <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($funcionario['CEP']) ?>">
                                    <span class="input-icon">📍</span>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="uf">UF</label>
                                <div class="input-wrapper">
                                    <input type="text" id="uf" name="uf" value="<?= htmlspecialchars($funcionario['UF']) ?>" maxlength="2" pattern="[A-Z]{2}" title="Digite apenas letras maiúsculas (ex: SP, RJ)">
                                    <span class="input-icon">🇧🇷</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="cidade">Cidade</label>
                                <div class="input-wrapper">
                                    <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($funcionario['Cidade']) ?>">
                                    <span class="input-icon">🏙️</span>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="bairro">Bairro</label>
                                <div class="input-wrapper">
                                    <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($funcionario['Bairro']) ?>">
                                    <span class="input-icon">🏘️</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="rua">Rua</label>
                                <div class="input-wrapper">
                                    <input type="text" id="rua" name="rua" value="<?= htmlspecialchars($funcionario['Rua']) ?>">
                                    <span class="input-icon">🛣️</span>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="num_residencia">Número da Residência</label>
                                <div class="input-wrapper">
                                    <input type="text" id="num_residencia" name="num_residencia" value="<?= htmlspecialchars($funcionario['Num_Residencia']) ?>">
                                    <span class="input-icon">🏠</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="usuario">Usuário</label>
                                <div class="input-wrapper">
                                    <input type="text" id="usuario" name="usuario" value="<?= htmlspecialchars($funcionario['Usuario']) ?>" required>
                                    <span class="input-icon">👤</span>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="senha">Senha</label>
                                <div class="input-wrapper">
                                    <input type="password" id="senha" name="senha" value="<?= htmlspecialchars($funcionario['Senha']) ?>">
                                    <span class="input-icon">🔑</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="cod_perfil">Perfil/Função</label>
                                <div class="input-wrapper">
                                    <select id="cod_perfil" name="cod_perfil" required>
                                        <?php foreach ($perfis as $perfil): ?>
                                            <option value="<?= $perfil['Cod_Perfil'] ?>" 
                                                    <?= ($perfil['Cod_Perfil'] == $funcionario['Cod_Perfil']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($perfil['Nome_Perfil']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <span class="input-icon">👷</span>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="perfil_atual">Perfil Atual</label>
                                <div class="input-wrapper">
                                    <input type="text" id="perfil_atual" value="<?= htmlspecialchars($funcionario['Nome_Perfil']) ?>" readonly>
                                    <span class="input-icon">ℹ️</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="botao">
                        <button type="submit" id="btn-salvar" class="btn">
                            💾 Salvar Alterações
                        </button>
                        <a href="consultar_funcionario.php" id="cancelar-edicao" class="btn">
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
            const cpf = document.getElementById('cpf').value.trim();
            const email = document.getElementById('email').value.trim();
            const usuario = document.getElementById('usuario').value.trim();
            
            if (nome === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O nome do funcionário é obrigatório!'
                });
                return false;
            }
            if (cpf === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O CPF do funcionário é obrigatório!'
                });
                return false;
            }
            if (email === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O email do funcionário é obrigatório!'
                });
                return false;
            }
            if (usuario === '') {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O usuário do funcionário é obrigatório!'
                });
                return false;
            }
        });

        // Formatação automática de datas
        document.getElementById('data_nascimento').addEventListener('change', function() {
            const data = this.value;
            if (data) {
                const dataObj = new Date(data);
                const hoje = new Date();
                if (dataObj > hoje) {
                    alert('A data de nascimento não pode ser no futuro!');
                    this.value = '';
                }
            }
        });

        document.getElementById('data_efetivacao').addEventListener('change', function() {
            const data = this.value;
            if (data) {
                const dataObj = new Date(data);
                const hoje = new Date();
                if (dataObj > hoje) {
                    alert('A data de efetivação não pode ser no futuro!');
                    this.value = '';
                }
            }
        });
    </script>
</body>
</html>
