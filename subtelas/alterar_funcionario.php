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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Sala Arco-íris</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/cadastros.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1) !important;
        }
    </style>
</head>

<body>
    <div class="page-wrapper">
        <header class="header">
            <a href="consultar_funcionario.php" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
            <h1>Alterar Funcionário</h1>
        </header>

        <main class="main-content">
            <div class="container">
                <form class="formulario" id="form_pessoal" method="POST" action="">
                    <section class="form-section">
                        <h2 class="section-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>
                            Informações Pessoais
                        </h2>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="nome">Nome Completo</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($funcionario['Nome']) ?>" required placeholder="Digite o nome completo">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="data_nascimento">Data de Nascimento</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($funcionario['Data_Nascimento']) ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="sexo">Sexo</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                        <circle cx="9" cy="7" r="4"/>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                    </svg>
                                    <select id="sexo" name="sexo" class="custom-select" required>
                                        <option value="" disabled>Selecione o sexo</option>
                                        <option value="Feminino" <?= $funcionario['Sexo'] == 'Feminino' ? 'selected' : '' ?>>Feminino</option>
                                        <option value="Masculino" <?= $funcionario['Sexo'] == 'Masculino' ? 'selected' : '' ?>>Masculino</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="input-group">
                                <label for="cpf">CPF</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14,2 14,8 20,8"/>
                                        <line x1="16" y1="13" x2="8" y2="13"/>
                                        <line x1="16" y1="17" x2="8" y2="17"/>
                                        <polyline points="10,9 9,9 8,9"/>
                                    </svg>
                                    <input type="text" id="cpf" name="cpf" value="<?= htmlspecialchars($funcionario['CPF']) ?>" maxlength="14" oninput="formatCPF(this)" required placeholder="000.000.000-00">
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
                                <input type="email" id="email" name="email" value="<?= htmlspecialchars($funcionario['Email']) ?>" required placeholder="exemplo@email.com">
                            </div>
                        </div>

                        <br>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="telefone">Telefone</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                                    </svg>
                                    <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($funcionario['Telefone']) ?>" required placeholder="(00) 00000-0000">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <h2 class="section-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            Informações Profissionais
                        </h2>
                        
                        <div class="form-row">
                            <div class="input-group">
                                <label for="cod_perfil">Cargo</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <select id="cod_perfil" name="cod_perfil" class="custom-select" required>
                                        <?php foreach ($perfis as $perfil): ?>
                                            <option value="<?= $perfil['Cod_Perfil'] ?>" 
                                                <?= ($funcionario['Cod_Perfil'] == $perfil['Cod_Perfil']) ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($perfil['Nome_Perfil']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="data_efetivacao">Data de Efetivação</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                                        <line x1="16" y1="2" x2="16" y2="6"/>
                                        <line x1="8" y1="2" x2="8" y2="6"/>
                                        <line x1="3" y1="10" x2="21" y2="10"/>
                                    </svg>
                                    <input type="date" id="data_efetivacao" name="data_efetivacao" value="<?= htmlspecialchars($funcionario['Data_Efetivacao']) ?>" required>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <h2 class="section-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                            Endereço
                        </h2>
                        
                        <div class="form-row">
                            <div class="input-group">
                                <label for="cep">CEP</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="cep" name="cep" value="<?= htmlspecialchars($funcionario['CEP']) ?>" placeholder="00000-000">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="uf">Estado</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="uf" name="uf" value="<?= htmlspecialchars($funcionario['UF']) ?>" maxlength="2" pattern="[A-Z]{2}" title="Digite apenas letras maiúsculas (ex: SP, RJ)" placeholder="Digite o estado">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="cidade">Cidade</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="cidade" name="cidade" value="<?= htmlspecialchars($funcionario['Cidade']) ?>" placeholder="Digite a cidade">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="bairro">Bairro</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($funcionario['Bairro']) ?>" placeholder="Digite o bairro">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="rua">Rua</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="rua" name="rua" value="<?= htmlspecialchars($funcionario['Rua']) ?>" placeholder="Digite a rua">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="num_residencia">Número</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="num_residencia" name="num_residencia" value="<?= htmlspecialchars($funcionario['Num_Residencia']) ?>" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')" placeholder="0000">
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="form-section">
                        <h2 class="section-title">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                                <polyline points="10,17 15,12 10,7"/>
                                <line x1="15" y1="12" x2="3" y2="12"/>
                            </svg>
                            Acesso ao Sistema
                        </h2>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="usuario">Usuário</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <input type="text" id="usuario" name="usuario" value="<?= htmlspecialchars($funcionario['Usuario']) ?>" required placeholder="Digite o nome de usuário">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="senha">Senha</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                        <circle cx="12" cy="16" r="1"/>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                    </svg>
                                    <input type="password" id="senha" name="senha" value="<?= htmlspecialchars($funcionario['Senha']) ?>" placeholder="Digite a senha (opcional)">
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary" id="btnCadastrar">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Salvar Alterações
                        </button>
                        
                        <a href="consultar_funcionario.php" class="btn btn-secondary">
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
    <script src="subtelas_javascript/sidebar.js"></script>
    
    <script>
        // Validação do formulário com alert de confirmação
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault(); // Previne o envio automático
            
            const nome = document.getElementById('nome').value.trim();
            const cpf = document.getElementById('cpf').value.trim();
            const email = document.getElementById('email').value.trim();
            const usuario = document.getElementById('usuario').value.trim();
            
            if (nome === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O nome do funcionário é obrigatório!',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return false;
            }
            if (cpf === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O CPF do funcionário é obrigatório!',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return false;
            }
            if (email === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O email do funcionário é obrigatório!',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return false;
            }
            if (usuario === '') {
                Swal.fire({
                    icon: 'error',
                    title: 'Erro de Validação',
                    text: 'O usuário do funcionário é obrigatório!',
                    customClass: {
                        title: 'swal2-title-arial',
                        confirmButton: 'swal2-confirm'
                    }
                });
                return false;
            }
            
            // Se passou por todas as validações, mostra o alert de confirmação
            Swal.fire({
                title: 'Sucesso!',
                text: 'Funcionário alterado com sucesso!',
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    title: 'swal2-title-arial',
                    confirmButton: 'swal2-confirm'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit(); // Envia o formulário após confirmação
                }
            });
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

        // Função para formatar CPF
        function formatCPF(input) {
            let value = input.value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
            if (value.length > 11) { // CPF tem 11 dígitos
                value = value.substring(0, 11);
            }
            let formattedValue = '';
            if (value.length > 0) {
                formattedValue += value.substring(0, 3);
                if (value.length > 3) {
                    formattedValue += '.';
                    formattedValue += value.substring(3, 6);
                    if (value.length > 6) {
                        formattedValue += '.';
                        formattedValue += value.substring(6, 9);
                        if (value.length > 9) {
                            formattedValue += '-';
                            formattedValue += value.substring(9, 11);
                        }
                    }
                }
            }
            input.value = formattedValue;
        }
    </script>
</body>
</html>
