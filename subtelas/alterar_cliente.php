<?php
    session_start();
    require_once '../conexao.php';

    // VERIFICA SE O USUÁRIO TEM PERMISSÃO
    if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3 && $_SESSION['perfil'] != 4) {
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
        header('Location: consultar_cliente.php');
        exit();
    }

    $id = intval($_GET['id']);

    // Buscar dados do Cliente com todos os campos
    $sql = "SELECT 
            c.Cod_cliente,
            c.Cod_Perfil,
            c.Nome,
            c.CPF,
            c.Email,
            c.Sexo,
            c.Telefone,
            c.Data_Nascimento,
            c.CEP,
            c.UF,
            c.Cidade,
            c.Bairro,
            c.Rua,
            c.Num_Residencia,
            c.Foto,
            c.Nome_Responsavel,
            p.Nome_Perfil
            FROM cliente c
            JOIN perfil_cliente p ON c.Cod_Perfil = p.Cod_Perfil
            WHERE c.Cod_cliente = :id";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$cliente) {
            header('Location: consultar_cliente.php');
            exit;
        }
    } catch (PDOException $e) {
        die("Erro na consulta: " . $e->getMessage());
    }

    // Buscar todos os perfis para o select
    $sql_perfis = "SELECT Cod_Perfil, Nome_Perfil FROM perfil_cliente ORDER BY Nome_Perfil";
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
        $sexo = $cliente['Sexo']; // Usar valor atual do banco
        $telefone = trim($_POST['telefone']);
        $data_nascimento = $_POST['data_nascimento'];
        $cep = trim($_POST['cep']);
        $uf = $_POST['uf'];
        $cidade = trim($_POST['cidade']);
        $bairro = trim($_POST['bairro']);
        $rua = trim($_POST['rua']);
        $num_residencia = trim($_POST['num_residencia']);
        $nome_responsavel = trim($_POST['nome_responsavel']);
        $cod_perfil = $cliente['Cod_Perfil']; // Usar valor atual do banco
        
        // Processar upload da foto
        $foto = $cliente['Foto']; // Manter foto atual por padrão
        
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $arquivo_tmp = $_FILES['foto']['tmp_name'];
            $nome_arquivo = $_FILES['foto']['name'];
            $extensao = strtolower(pathinfo($nome_arquivo, PATHINFO_EXTENSION));
            
            // Verificar se é uma extensão válida
            $extensoes_validas = ['jpg', 'jpeg', 'png'];
            if (in_array($extensao, $extensoes_validas)) {
                // Gerar nome único para o arquivo
                $novo_nome = uniqid() . '.' . $extensao;
                $destino = 'subtelas_img/' . $novo_nome;
                
                // Mover arquivo para a pasta de imagens
                if (move_uploaded_file($arquivo_tmp, $destino)) {
                    // Se havia uma foto anterior, deletar
                    if (!empty($cliente['Foto']) && file_exists('subtelas_img/' . $cliente['Foto'])) {
                        unlink('subtelas_img/' . $cliente['Foto']);
                    }
                    $foto = $novo_nome;
                }
            }
        }
        
        if (empty($nome)) {
            $erro = "Nome é obrigatório";
        } elseif (empty($cpf)) {
            $erro = "CPF é obrigatório";
        } elseif (empty($email)) {
            $erro = "Email é obrigatório";
        } else {
            try {
                $sql_update = "UPDATE cliente 
                            SET Nome = :nome,
                                Nome_Responsavel = :nome_responsavel,
                                CPF = :cpf,
                                Email = :email,
                                Sexo = :sexo,
                                Telefone = :telefone,
                                Data_Nascimento = :data_nascimento,
                                CEP = :cep,
                                UF = :uf,
                                Cidade = :cidade,
                                Bairro = :bairro,
                                Rua = :rua,
                                Num_Residencia = :num_residencia,
                                Cod_Perfil = :cod_perfil,
                                Foto = :foto
                            WHERE Cod_cliente = :id";
                
                $stmt_update = $pdo->prepare($sql_update);
                $stmt_update->bindParam(':nome', $nome);
                $stmt_update->bindParam(':nome_responsavel', $nome_responsavel);
                $stmt_update->bindParam(':cpf', $cpf);
                $stmt_update->bindParam(':email', $email);
                $stmt_update->bindParam(':sexo', $sexo);
                $stmt_update->bindParam(':telefone', $telefone);
                $stmt_update->bindParam(':data_nascimento', $data_nascimento);
                $stmt_update->bindParam(':cep', $cep);
                $stmt_update->bindParam(':uf', $uf);
                $stmt_update->bindParam(':cidade', $cidade);
                $stmt_update->bindParam(':bairro', $bairro);
                $stmt_update->bindParam(':rua', $rua);
                $stmt_update->bindParam(':num_residencia', $num_residencia);
                $stmt_update->bindParam(':cod_perfil', $cod_perfil);
                $stmt_update->bindParam(':foto', $foto);
                $stmt_update->bindParam(':id', $id);
                
                if ($stmt_update->execute()) {
                    $sucesso = "Cliente alterado com sucesso!";
                    // Recarregar dados do Cliente
                    $stmt->execute();
                    $cliente = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $erro = "Erro ao alterar cliente";
                }
            } catch (PDOException $e) {
                $erro = "Erro ao alterar cliente: " . $e->getMessage();
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="page-wrapper">
        <header class="header">
            <a href="consultar_cliente.php" class="btn-voltar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 12H5M12 19l-7-7 7-7"/>
                    </svg>
                    Voltar
                </a>
            <h1>Alterar Cliente</h1>
        </header>
        
        <main class="main-content">
            <div class="container">
                <form class="formulario" id="form_pessoal" action="alterar_cliente.php?id=<?= $id ?>" method="post" enctype="multipart/form-data" onsubmit="return validaFormulario()">
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
                                    <input type="text" id="nome" name="nome" required placeholder="Digite o nome completo" value="<?= htmlspecialchars($cliente['Nome']) ?>">
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
                                    <input type="date" id="data_nascimento" name="data_nascimento" required min="1925-01-01" max="" id="dataNascimento" value="<?= htmlspecialchars($cliente['Data_Nascimento']) ?>">
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
                                    <input type="text" id="sexo" name="sexo" class="custom-select" required value="<?= htmlspecialchars($cliente['Sexo']) ?>" readonly>
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
                                    <input type="text" id="cpf" name="cpf" maxlength="14" oninput="formatCPF(this)" required placeholder="000.000.000-00" value="<?= htmlspecialchars($cliente['CPF']) ?>">
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
                                <input type="email" id="email" name="email" required placeholder="exemplo@email.com" value="<?= htmlspecialchars($cliente['Email']) ?>">
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
                                    <input type="text" id="telefone" name="telefone" maxlength="15" oninput="formatTelefone(this)" required placeholder="(00) 00000-0000" value="<?= htmlspecialchars($cliente['Telefone']) ?>">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="foto">Foto do Cliente</label>
                                <div class="file-upload-wrapper">
                                    <input type="text" name="seletor_arquivo" id="seletor_arquivo" readonly placeholder="Nenhum arquivo selecionado" class="file-display" value="<?= htmlspecialchars($cliente['Foto']) ?>">
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
                        </div>

                        <div class="form-row">
                            <div class="input-group">
                                <label for="responsavel">Nome do Responsável</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                        <circle cx="12" cy="7" r="4"/>
                                    </svg>
                                    <input type="text" id="nome_responsavel" name="nome_responsavel" required placeholder="Digite o nome do responsável" value="<?= htmlspecialchars($cliente['Nome_Responsavel']) ?>">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="perfil">Tipo de Cliente</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
                                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
                                        <path d="M9 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                                        <path d="M15 14a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                                    </svg>
                                    <input type="text" id="perfil" name="perfil" class="custom-select" required value="<?= htmlspecialchars($cliente['Nome_Perfil']) ?>" readonly>
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
                                <div class="input-wrapper cep-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="cep" name="cep" maxlength="9" oninput="formatCEP(this)" onblur="buscarCEP(this.value)" required placeholder="00000-000" value="<?= htmlspecialchars($cliente['CEP']) ?>">
                                    <button type="button" class="btn-cep" onclick="buscarCEP(document.getElementById('cep').value)">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <circle cx="11" cy="11" r="8"/>
                                            <path d="M21 21l-4.35-4.35"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="uf">Estado</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="uf" name="uf" required placeholder="Digite o estado" value="<?= htmlspecialchars($cliente['UF']) ?>">
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
                                    <input type="text" id="cidade" name="cidade" required placeholder="Digite a cidade" value="<?= htmlspecialchars($cliente['Cidade']) ?>">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="bairro">Bairro</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="bairro" name="bairro" required placeholder="Digite o bairro" value="<?= htmlspecialchars($cliente['Bairro']) ?>">
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
                                    <input type="text" id="rua" name="rua" required placeholder="Digite a rua" value="<?= htmlspecialchars($cliente['Rua']) ?>">
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="num_residencia">Número</label>
                                <div class="input-wrapper">
                                    <svg class="input-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                        <circle cx="12" cy="10" r="3"/>
                                    </svg>
                                    <input type="text" id="num_residencia" name="num_residencia" maxlength="4" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required placeholder="0000" value="<?= htmlspecialchars($cliente['Num_Residencia']) ?>">
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="form-actions">
                            <button type="submit" class="btn btn-primary" id="btnAlterar">
                             <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                 <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                             </svg>
                             Alterar Cliente
                         </button>
                    
                    </div>
                </form>
            </div>
        </main>
    </div>
</body>
    <script src="subtelas_javascript/validaAlterar.js"></script>

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
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'consultar_cliente.php';
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
</html>