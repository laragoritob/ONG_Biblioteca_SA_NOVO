<?php
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
        $linkVoltar = "../index.php";
        break;
}

// Definir permissões baseadas no perfil
$perfil = $_SESSION['perfil'];
$podeGerenciarFuncionarios = in_array($perfil, [1, 2]); // Gerente e Gestor
$podeGerenciarClientes = in_array($perfil, [1, 3, 4]); // Gerente, Bibliotecário e Recreador
$podeGerenciarLivros = in_array($perfil, [1, 3, 4, 5]); // Gerente, Bibliotecário, Recreador e Repositor
$podeGerenciarAutores = in_array($perfil, [1, 3]); // Gerente e Bibliotecário
$podeGerenciarEditoras = in_array($perfil, [1, 3]); // Gerente e Bibliotecário
$podeGerenciarDoadores = in_array($perfil, [1, 2, 3]); // Gerente, Gestor e Bibliotecário
$podeGerenciarEmprestimos = in_array($perfil, [1, 3, 4]); // Gerente, Bibliotecário e Recreador
$podeConsultarMultas = in_array($perfil, [1, 3, 4]); // Gerente, Bibliotecário e Recreador
?>

<!-- Sidebar Toggle Button -->
<button id="sidebar-toggle" class="sidebar-toggle">
    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <line x1="3" y1="6" x2="21" y2="6"/>
        <line x1="3" y1="12" x2="21" y2="12"/>
        <line x1="3" y1="18" x2="21" y2="18"/>
    </svg>
</button>

<!-- Sidebar Overlay -->
<div id="sidebar-overlay" class="sidebar-overlay"></div>

<!-- Sidebar Dropdown -->
<div id="sidebar-dropdown" class="sidebar-dropdown">
    <div class="sidebar-header">
        <h3>ONG Biblioteca</h3>
    </div>
    
    <nav class="sidebar-menu">
        <!-- Voltar -->
        <div class="sidebar-category">
            <h4 class="sidebar-category-title">Navegação</h4>
            <a href="<?= $linkVoltar ?>" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M19 12H5M12 19l-7-7 7-7"/>
                </svg>
                Voltar ao Menu
            </a>
        </div>
        
        <!-- Funcionários - Apenas Gerente e Gestor -->
        <?php if ($podeGerenciarFuncionarios): ?>
        <div class="sidebar-category">
            <h4 class="sidebar-category-title">Funcionários</h4>
            <a href="cadastro_funcionario.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Cadastrar Funcionário
            </a>
            <a href="consultar_funcionario.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Consultar Funcionário
            </a>
        </div>
        <?php endif; ?>

        <!-- Clientes - Gerente, Bibliotecário e Recreador -->
        <?php if ($podeGerenciarClientes): ?>
        <div class="sidebar-category">
            <h4 class="sidebar-category-title">Clientes</h4>
            <a href="cadastro_cliente.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Cadastrar Cliente
            </a>
            <a href="consultar_cliente.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Consultar Cliente
            </a>
        </div>
        <?php endif; ?>

        <!-- Livros - Gerente, Bibliotecário, Recreador e Repositor -->
        <?php if ($podeGerenciarLivros): ?>
        <div class="sidebar-category">
            <h4 class="sidebar-category-title">Livros</h4>
            <?php if (in_array($perfil, [1, 3, 5])): // Gerente, Bibliotecário e Repositor podem registrar ?>
            <a href="registrar_livro.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
                Registrar Livro
            </a>
            <?php endif; ?>
            <a href="consultar_livro.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Consultar Livro
            </a>
        </div>
        <?php endif; ?>

        <!-- Autores - Apenas Gerente e Bibliotecário -->
        <?php if ($podeGerenciarAutores): ?>
        <div class="sidebar-category">
            <h4 class="sidebar-category-title">Autores</h4>
            <a href="registrar_autor.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Registrar Autor
            </a>
            <a href="consultar_autor.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Consultar Autor
            </a>
        </div>
        <?php endif; ?>

        <!-- Editoras - Apenas Gerente e Bibliotecário -->
        <?php if ($podeGerenciarEditoras): ?>
        <div class="sidebar-category">
            <h4 class="sidebar-category-title">Editoras</h4>
            <a href="registrar_editora.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Registrar Editora
            </a>
            <a href="consultar_editora.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Consultar Editora
            </a>
        </div>
        <?php endif; ?>

        <!-- Doadores - Gerente, Gestor e Bibliotecário -->
        <?php if ($podeGerenciarDoadores): ?>
        <div class="sidebar-category">
            <h4 class="sidebar-category-title">Doadores</h4>
            <a href="registrar_doador.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                    <circle cx="12" cy="7" r="4"/>
                </svg>
                Registrar Doador
            </a>
            <a href="consultar_doador.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Consultar Doador
            </a>
        </div>
        <?php endif; ?>

        <!-- Empréstimos - Gerente, Bibliotecário e Recreador -->
        <?php if ($podeGerenciarEmprestimos): ?>
        <div class="sidebar-category">
            <h4 class="sidebar-category-title">Empréstimos</h4>
            <a href="registrar_emprestimo.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/>
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/>
                </svg>
                Registrar Empréstimo
            </a>
            <a href="consultar_emprestimo.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Consultar Empréstimo
            </a>
        </div>
        <?php endif; ?>

        <!-- Multas - Gerente, Bibliotecário e Recreador -->
        <?php if ($podeConsultarMultas): ?>
        <div class="sidebar-category">
            <h4 class="sidebar-category-title">Multas</h4>
            <a href="consultar_multa.php" class="sidebar-menu-item">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px;">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                Consultar Multa
            </a>
        </div>
        <?php endif; ?>
    </nav>
</div>
