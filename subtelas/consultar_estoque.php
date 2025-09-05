<?php
// Inicia a sessão para verificar autenticação e perfil do usuário
session_start();

// Inclui o arquivo de conexão com o banco de dados
require_once '../conexao.php';

// Verifica se o usuário tem permissão para acessar esta página
// Gerente (perfil 1), Bibliotecário (perfil 3), Recreador (perfil 4) e Repositor (perfil 5) podem consultar estoque
if ($_SESSION['perfil'] != 1 && $_SESSION['perfil'] != 3 && $_SESSION['perfil'] != 4 && $_SESSION['perfil'] != 5) {
    // Se não tem permissão, exibe alerta e redireciona para login
    echo "<script>alert('Acesso Negado!');window.location.href='../index.php';</script>";
    exit();
}

// Define qual página o usuário deve retornar baseado em seu perfil
switch ($_SESSION['perfil']) {
    case 1: // Gerente - pode acessar todas as funcionalidades
        $linkVoltar = "../gerente.php";
        break;
    case 2: // Gestor - não tem acesso a esta página
        $linkVoltar = "../gestor.php";
        break;
    case 3: // Bibliotecário - pode consultar estoque
        $linkVoltar = "../bibliotecario.php";
        break;
    case 4: // Recreador - pode consultar estoque
        $linkVoltar = "../recreador.php";
        break;
    case 5: // Repositor - pode consultar estoque
        $linkVoltar = "../repositor.php";
        break;
    default:
        // Se perfil não for reconhecido, redireciona para login
        $linkVoltar = "../index.php";
        break;
}

// Consulta SQL para buscar todos os livros com suas informações de estoque
$sql = "SELECT 
          l.Cod_Livro,
          l.Titulo,
          l.Quantidade,
          l.Num_Prateleira,
          l.status,
          a.Nome_Autor,
          e.Nome_Editora,
          g.Nome_Genero
        FROM livro l
        LEFT JOIN autor a ON l.Cod_Autor = a.Cod_Autor
        LEFT JOIN editora e ON l.Cod_Editora = e.Cod_Editora
        LEFT JOIN genero g ON l.Cod_Genero = g.Cod_Genero
        ORDER BY l.Quantidade ASC, l.Titulo ASC";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $livros = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Contar livros com estoque baixo
    $estoque_baixo = array_filter($livros, function($livro) {
        return $livro['Quantidade'] < 5 && $livro['status'] == 'ativo';
    });
    
    $total_livros = count($livros);
    $total_estoque_baixo = count($estoque_baixo);
    
} catch (PDOException $e) {
    $erro = "Erro na consulta: " . $e->getMessage();
    $livros = [];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Controle de Estoque</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/consultas.css">
    <link rel="stylesheet" type="text/css" href="subtelas_css/sidebar-dropdown.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-left: 4px solid;
        }
        
        .stat-card.total {
            border-left-color: #3b82f6;
        }
        
        .stat-card.baixo {
            border-left-color: #f59e0b;
        }
        
        .stat-card.critico {
            border-left-color: #ef4444;
        }
        
        .stat-card.zero {
            border-left-color: #dc2626;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            color: #6b7280;
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        
        .estoque-baixo {
            background-color: #fef3c7 !important;
            border-left: 4px solid #f59e0b !important;
        }
        
        .estoque-critico {
            background-color: #fef2f2 !important;
            border-left: 4px solid #ef4444 !important;
        }
        
        .estoque-zero {
            background-color: #fef2f2 !important;
            border-left: 4px solid #dc2626 !important;
            color: #dc2626 !important;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-ativo {
            background-color: #d1fae5;
            color: #065f46;
        }
        
        .status-inativo {
            background-color: #f3f4f6;
            color: #6b7280;
        }
        
        .alert-banner {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 600;
        }
        
        .alert-banner.critical {
            background: linear-gradient(135deg, #ef4444, #dc2626);
        }
        
        .search-container {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            align-items: center;
        }
        
        .btn-filtrar {
            padding: 10px 20px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            font-weight: 500;
        }
        
        .btn-filtrar:hover {
            background: #2563eb;
        }
        
        .btn-limpar {
            padding: 10px 20px;
            background: #6b7280;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
            font-weight: 500;
        }
        
        .btn-limpar:hover {
            background: #4b5563;
        }
        
        @media (max-width: 768px) {
            .stats-container {
                grid-template-columns: 1fr;
            }
            
            .search-container {
                flex-direction: column;
                align-items: stretch;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/sidebar-dropdown.php'; ?>
    <div class="page-wrapper">
        <header class="header">
            <a href="<?= $linkVoltar ?>" class="btn-voltar">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Voltar
            </a>
            <h1>📦 Controle de Estoque</h1>
        </header>
        
        <?php if (isset($erro)): ?>
            <div style="text-align: center; padding: 20px; color: #d32f2f; background-color: #ffebee; border: 1px solid #f44336; border-radius: 4px; margin: 20px;">
                <p><strong>Erro:</strong> <?= htmlspecialchars($erro) ?></p>
            </div>
        <?php endif; ?>
        
        <!-- Estatísticas do Estoque -->
        <div class="stats-container">
            <div class="stat-card total">
                <div class="stat-number" style="color: #3b82f6;"><?= $total_livros ?></div>
                <div class="stat-label">Total de Livros</div>
            </div>
            
            <div class="stat-card baixo">
                <div class="stat-number" style="color: #f59e0b;"><?= $total_estoque_baixo ?></div>
                <div class="stat-label">Estoque Baixo (< 5)</div>
            </div>
            
            <?php
            $estoque_critico = array_filter($livros, function($livro) {
                return $livro['Quantidade'] <= 2 && $livro['status'] == 'ativo';
            });
            $total_critico = count($estoque_critico);
            ?>
            
            <div class="stat-card critico">
                <div class="stat-number" style="color: #ef4444;"><?= $total_critico ?></div>
                <div class="stat-label">Estoque Crítico (≤ 2)</div>
            </div>
            
            <?php
            $estoque_zero = array_filter($livros, function($livro) {
                return $livro['Quantidade'] == 0 && $livro['status'] == 'ativo';
            });
            $total_zero = count($estoque_zero);
            ?>
            
            <div class="stat-card zero">
                <div class="stat-number" style="color: #dc2626;"><?= $total_zero ?></div>
                <div class="stat-label">Sem Estoque (0)</div>
            </div>
        </div>
        
        <!-- Alertas -->
        <?php if ($total_zero > 0): ?>
            <div class="alert-banner critical">
                🚨 ALERTA CRÍTICO: <?= $total_zero ?> livro(s) estão sem estoque!
            </div>
        <?php elseif ($total_critico > 0): ?>
            <div class="alert-banner critical">
                ⚠️ ALERTA: <?= $total_critico ?> livro(s) com estoque crítico (≤ 2 unidades)!
            </div>
        <?php elseif ($total_estoque_baixo > 0): ?>
            <div class="alert-banner">
                ⚠️ ATENÇÃO: <?= $total_estoque_baixo ?> livro(s) com estoque baixo (< 5 unidades)!
            </div>
        <?php endif; ?>
        
        <!-- Busca -->
        <form action="consultar_estoque.php" method="POST">
            <div class="search-container">
                <div style="flex: 1;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none;z-index:1;color:#9ca3af;">
                        <path d="m21 21-4.34-4.34"/><circle cx="11" cy="11" r="8"/>
                    </svg>
                    <input type="text" id="search-input" name="busca" placeholder="Buscar por título, autor ou editora..." value="<?= htmlspecialchars(isset($_POST['busca']) ? $_POST['busca'] : '') ?>" style="width: 100%; padding-left: 40px;">
                </div>
                <button type="submit" class="btn-filtrar">Buscar</button>
                <button type="button" class="btn-limpar" onclick="document.getElementById('search-input').value=''; document.forms[0].submit();">Limpar</button>
            </div>
        </form>
        
        <!-- Tabela de Estoque -->
        <nav>
            <table id="estoque-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>TÍTULO</th>
                        <th>AUTOR</th>
                        <th>EDITORA</th>
                        <th>GÊNERO</th>
                        <th>PRATELEIRA</th>
                        <th>QUANTIDADE</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($livros) && is_array($livros)): ?>
                        <?php foreach ($livros as $livro): ?>
                            <?php
                            $classe_linha = '';
                            if ($livro['status'] == 'ativo') {
                                if ($livro['Quantidade'] == 0) {
                                    $classe_linha = 'estoque-zero';
                                } elseif ($livro['Quantidade'] <= 2) {
                                    $classe_linha = 'estoque-critico';
                                } elseif ($livro['Quantidade'] < 5) {
                                    $classe_linha = 'estoque-baixo';
                                }
                            }
                            ?>
                            <tr class="<?= $classe_linha ?>">
                                <td><?= htmlspecialchars($livro['Cod_Livro']) ?></td>
                                <td><?= htmlspecialchars($livro['Titulo']) ?></td>
                                <td><?= htmlspecialchars($livro['Nome_Autor'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($livro['Nome_Editora'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($livro['Nome_Genero'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($livro['Num_Prateleira'] ?? 'N/A') ?></td>
                                <td>
                                    <strong style="font-size: 1.1em;">
                                        <?= $livro['Quantidade'] ?>
                                    </strong>
                                    <?php if ($livro['Quantidade'] < 5 && $livro['status'] == 'ativo'): ?>
                                        <span style="color: #f59e0b; margin-left: 5px;">⚠️</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="status-badge status-<?= $livro['status'] ?>">
                                        <?= ucfirst($livro['status']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="8">Nenhum livro encontrado</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </nav>
    </div>

    <script src="subtelas_javascript/sidebar-dropdown.js"></script>
    
    <script>
        // Função para filtrar tabela pelo input de busca
        function filtrarTabela() {
            const input = document.getElementById("search-input").value.toLowerCase();
            const rows = document.querySelectorAll("#estoque-table tbody tr");
            
            rows.forEach(row => {
                const texto = row.textContent.toLowerCase();
                row.style.display = texto.includes(input) ? "" : "none";
            });
        }
        
        // Adicionar evento de busca em tempo real
        document.getElementById('search-input').addEventListener('input', filtrarTabela);
        
        // Mostrar alerta se houver estoque baixo
        <?php if ($total_zero > 0): ?>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '🚨 Alerta Crítico de Estoque',
                    html: `
                        <div style='text-align: center;'>
                            <p style='margin: 20px 0; font-size: 16px; color: #dc2626;'>Existem livros sem estoque!</p>
                            <div style='background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 15px; margin: 15px 0;'>
                                <p style='margin: 8px 0; font-size: 14px;'><strong>📚 Livros sem estoque:</strong> <?= $total_zero ?></p>
                                <p style='margin: 8px 0; font-size: 14px;'><strong>⚠️ Ação necessária:</strong> Reposição urgente</p>
                            </div>
                        </div>
                    `,
                    icon: 'error',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Entendi'
                });
            });
        <?php elseif ($total_critico > 0): ?>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '⚠️ Alerta de Estoque Crítico',
                    html: `
                        <div style='text-align: center;'>
                            <p style='margin: 20px 0; font-size: 16px; color: #f59e0b;'>Existem livros com estoque crítico!</p>
                            <div style='background: #fffbeb; border: 1px solid #fed7aa; border-radius: 8px; padding: 15px; margin: 15px 0;'>
                                <p style='margin: 8px 0; font-size: 14px;'><strong>📚 Livros com estoque crítico:</strong> <?= $total_critico ?></p>
                                <p style='margin: 8px 0; font-size: 14px;'><strong>⚠️ Ação necessária:</strong> Reposição recomendada</p>
                            </div>
                        </div>
                    `,
                    icon: 'warning',
                    confirmButtonColor: '#f59e0b',
                    confirmButtonText: 'Entendi'
                });
            });
        <?php endif; ?>
    </script>
</body>
</html>
