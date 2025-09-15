<?php
    session_start();
    require_once 'conexao.php';

    if (!isset($_SESSION['usuario'])) {
        header('Location: index.php');
        exit();
    }

    try {
        $usuarioLogado = $_SESSION['usuario'];
        $ipUsuario = $_SERVER['REMOTE_ADDR'];

        $pdo->exec("SET @usuario_sistema = " . $pdo->quote($usuarioLogado));
        $pdo->exec("SET @ip_usuario = " . $pdo->quote($ipUsuario));
    } catch (PDOException $e) {
        die("Erro ao definir variáveis de auditoria: " . $e->getMessage());
    }

    // Criar tabela de logs de auditoria se não existir
    $createLogTable = "
    CREATE TABLE IF NOT EXISTS `logs_auditoria` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `tabela` varchar(50) NOT NULL,
      `operacao` enum('INSERT','UPDATE','DELETE') NOT NULL,
      `id_registro` int(11) NOT NULL,
      `dados_anteriores` text,
      `dados_novos` text,
      `usuario` varchar(100),
      `data_operacao` timestamp DEFAULT CURRENT_TIMESTAMP,
      `ip_usuario` varchar(45),
      PRIMARY KEY (`id`),
      KEY `idx_data_operacao` (`data_operacao`),
      KEY `idx_tabela` (`tabela`),
      KEY `idx_operacao` (`operacao`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";

    try {
        $pdo->exec($createLogTable);
    } catch (PDOException $e) {
        // Tabela já existe ou erro na criação
    }

    // Criar triggers para auditoria automática
    $triggers = [
        // Triggers para tabela autor
        "CREATE TRIGGER IF NOT EXISTS tr_autor_insert_audit
         AFTER INSERT ON autor
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('autor', 'INSERT', NEW.Cod_Autor, 
                     CONCAT('Nome: ', NEW.Nome_Autor),
                     @usuario_sistema, @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_autor_update_audit
         AFTER UPDATE ON autor
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('autor', 'UPDATE', NEW.Cod_Autor,
                     CONCAT('Nome: ', OLD.Nome_Autor),
                     CONCAT('Nome: ', NEW.Nome_Autor),
                     @usuario_sistema, @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_autor_delete_audit
         BEFORE DELETE ON autor
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('autor', 'DELETE', OLD.Cod_Autor,
                     CONCAT('Nome: ', OLD.Nome_Autor),
                     @usuario_sistema, @ip_usuario);
         END",
        
        // Triggers para tabela cliente
        "CREATE TRIGGER IF NOT EXISTS tr_cliente_insert_audit
         AFTER INSERT ON cliente
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('cliente', 'INSERT', NEW.Cod_Cliente, 
                     CONCAT('Nome: ', NEW.Nome),
                     @usuario_sistema, @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_cliente_update_audit
         AFTER UPDATE ON cliente
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('cliente', 'UPDATE', NEW.Cod_Cliente,
                     CONCAT('Nome: ', OLD.Nome),
                     CONCAT('Nome: ', NEW.Nome),
                     @usuario_sistema, @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_cliente_delete_audit
         BEFORE DELETE ON cliente
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('cliente', 'DELETE', OLD.Cod_Cliente,
                     CONCAT('Nome: ', OLD.Nome),
                     @usuario_sistema, @ip_usuario);
         END",
        
        // Triggers para tabela funcionario
        "CREATE TRIGGER IF NOT EXISTS tr_funcionario_insert_audit
         AFTER INSERT ON funcionario
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('funcionario', 'INSERT', NEW.Cod_Funcionario, 
                     CONCAT('Nome: ', NEW.Nome),
                     @usuario_sistema, @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_funcionario_update_audit
         AFTER UPDATE ON funcionario
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('funcionario', 'UPDATE', NEW.Cod_Funcionario,
                     CONCAT('Nome: ', OLD.Nome),
                     CONCAT('Nome: ', NEW.Nome),
                     @usuario_sistema, @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_funcionario_delete_audit
         BEFORE DELETE ON funcionario
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('funcionario', 'DELETE', OLD.Cod_Funcionario,
                     CONCAT('Nome: ', OLD.Nome),
                     @usuario_sistema, @ip_usuario);
         END",
        
        // Triggers para tabela livro
        "CREATE TRIGGER IF NOT EXISTS tr_livro_insert_audit
         AFTER INSERT ON livro
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('livro', 'INSERT', NEW.Cod_Livro, 
                     CONCAT('Título: ', NEW.Titulo),
                     @usuario_sistema, @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_livro_update_audit
         AFTER UPDATE ON livro
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('livro', 'UPDATE', NEW.Cod_Livro,
                     CONCAT('Título: ', OLD.Titulo),
                     CONCAT('Título: ', NEW.Titulo),
                     @usuario_sistema, @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_livro_delete_audit
         BEFORE DELETE ON livro
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('livro', 'DELETE', OLD.Cod_Livro,
                     CONCAT('Título: ', OLD.Titulo),
                     @usuario_sistema, @ip_usuario);
         END",
        
        // Triggers para tabela emprestimo
        "CREATE TRIGGER IF NOT EXISTS tr_emprestimo_insert_audit
         AFTER INSERT ON emprestimo
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('emprestimo', 'INSERT', NEW.Cod_Emprestimo, 
                     CONCAT('Cliente: ', NEW.Cod_Cliente),
                     @usuario_sistema, @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_emprestimo_update_audit
         AFTER UPDATE ON emprestimo
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('emprestimo', 'UPDATE', NEW.Cod_Emprestimo,
                     CONCAT('Cliente: ', OLD.Cod_Cliente),
                     CONCAT('Cliente: ', NEW.Cod_Cliente),
                     @usuario_sistema, @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_emprestimo_delete_audit
         BEFORE DELETE ON emprestimo
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('emprestimo', 'DELETE', OLD.Cod_Emprestimo,
                     CONCAT('Cliente: ', OLD.Cod_Cliente),
                     @usuario_sistema, @ip_usuario);
         END"
    ];

    // Criar triggers se não existirem
    foreach ($triggers as $trigger) {
        try {
            $pdo->exec($trigger);
        } catch (PDOException $e) {
            // Trigger já existe ou erro na criação
        }
    }

    // Consultar logs da última semana
    $sql = "
    SELECT 
        l.tabela,
        l.operacao,
        l.id_registro,
        l.dados_anteriores,
        l.dados_novos,
        l.usuario,
        l.data_operacao,
        l.ip_usuario,
        CASE 
            WHEN l.operacao = 'INSERT' THEN 'Cadastro'
            WHEN l.operacao = 'UPDATE' THEN 'Alteração'
            WHEN l.operacao = 'DELETE' THEN 'Exclusão'
        END as operacao_pt
    FROM logs_auditoria l
    WHERE l.data_operacao >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    ORDER BY l.data_operacao DESC";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $logs = [];
        $erro = "Erro na consulta: " . $e->getMessage();
    }

    // Contadores para estatísticas
    $total_operacoes = count($logs);
    $cadastros = count(array_filter($logs, function($log) { return $log['operacao'] == 'INSERT'; }));
    $alteracoes = count(array_filter($logs, function($log) { return $log['operacao'] == 'UPDATE'; }));
    $exclusoes = count(array_filter($logs, function($log) { return $log['operacao'] == 'DELETE'; }));

    // Agrupar por tabela
    $logs_por_tabela = [];
    foreach ($logs as $log) {
        $tabela = $log['tabela'];
        if (!isset($logs_por_tabela[$tabela])) {
            $logs_por_tabela[$tabela] = [];
        }
        $logs_por_tabela[$tabela][] = $log;
    }

    // ===== RELATÓRIO DE LIVROS MAIS EMPRESTADOS =====
    
    // Consultar gêneros para filtro
    $sql_generos = "SELECT Cod_Genero, Nome_Genero FROM genero ORDER BY Nome_Genero";
    $stmt_generos = $pdo->prepare($sql_generos);
    $stmt_generos->execute();
    $generos = $stmt_generos->fetchAll(PDO::FETCH_ASSOC);

    // Consultar autores para filtro
    $sql_autores = "SELECT Cod_Autor, Nome_Autor FROM autor WHERE status = 'ativo' ORDER BY Nome_Autor";
    $stmt_autores = $pdo->prepare($sql_autores);
    $stmt_autores->execute();
    $autores = $stmt_autores->fetchAll(PDO::FETCH_ASSOC);

    // Filtros do formulário
    $filtro_genero = isset($_GET['filtro_genero']) ? (int)$_GET['filtro_genero'] : 0;
    $filtro_autor = isset($_GET['filtro_autor']) ? (int)$_GET['filtro_autor'] : 0;

    // Consulta para todos os livros cadastrados
    $sql_livros_emprestados = "
        SELECT 
            l.Cod_Livro,
            l.Titulo,
            a.Nome_Autor,
            g.Nome_Genero,
            e.Nome_Editora,
            COALESCE(COUNT(e_emp.Cod_Emprestimo), 0) as total_emprestimos,
            l.Quantidade as estoque_atual,
            l.Num_Prateleira
        FROM livro l
        LEFT JOIN autor a ON l.Cod_Autor = a.Cod_Autor
        LEFT JOIN genero g ON l.Cod_Genero = g.Cod_Genero
        LEFT JOIN editora e ON l.Cod_Editora = e.Cod_Editora
        LEFT JOIN emprestimo e_emp ON l.Cod_Livro = e_emp.Cod_Livro AND e_emp.Status_Emprestimo = 'Pendente'
        WHERE 1=1
    ";

    $params = [];
    if ($filtro_genero > 0) {
        $sql_livros_emprestados .= " AND l.Cod_Genero = :genero";
        $params[':genero'] = $filtro_genero;
    }
    if ($filtro_autor > 0) {
        $sql_livros_emprestados .= " AND l.Cod_Autor = :autor";
        $params[':autor'] = $filtro_autor;
    }

    $sql_livros_emprestados .= "
        GROUP BY l.Cod_Livro, l.Titulo, a.Nome_Autor, g.Nome_Genero, e.Nome_Editora, l.Quantidade, l.Num_Prateleira
        ORDER BY total_emprestimos DESC, l.Titulo ASC
    ";

    $stmt_livros = $pdo->prepare($sql_livros_emprestados);
    $stmt_livros->execute($params);
    $livros_emprestados = $stmt_livros->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para dados do gráfico - empréstimos por mês (últimos 6 meses)
    $sql_grafico_tempo = "
        SELECT 
            DATE_FORMAT(Data_Emprestimo, '%Y-%m') as mes_ano,
            DATE_FORMAT(Data_Emprestimo, '%b/%Y') as mes_formatado,
            COUNT(Cod_Emprestimo) as total_emprestimos
        FROM emprestimo 
        WHERE Data_Emprestimo >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
        GROUP BY DATE_FORMAT(Data_Emprestimo, '%Y-%m'), DATE_FORMAT(Data_Emprestimo, '%b/%Y')
        ORDER BY mes_ano ASC
    ";
    
    $stmt_grafico = $pdo->prepare($sql_grafico_tempo);
    $stmt_grafico->execute();
    $dados_grafico = $stmt_grafico->fetchAll(PDO::FETCH_ASSOC);

    // Consulta para todos os empréstimos ativos
    $sql_emprestimos_ativos = "
        SELECT 
            e.Cod_Emprestimo,
            e.Data_Emprestimo,
            e.Data_Devolucao,
            e.Status_Emprestimo,
            l.Titulo as titulo_livro,
            a.Nome_Autor,
            g.Nome_Genero,
            c.Nome as nome_cliente,
            c.Email as email_cliente
        FROM emprestimo e
        LEFT JOIN livro l ON e.Cod_Livro = l.Cod_Livro
        LEFT JOIN autor a ON l.Cod_Autor = a.Cod_Autor
        LEFT JOIN genero g ON l.Cod_Genero = g.Cod_Genero
        LEFT JOIN cliente c ON e.Cod_Cliente = c.Cod_Cliente
        WHERE e.Status_Emprestimo = 'Pendente'
        ORDER BY e.Data_Emprestimo DESC
    ";

    $stmt_emprestimos = $pdo->prepare($sql_emprestimos_ativos);
    $stmt_emprestimos->execute();
    $emprestimos_ativos = $stmt_emprestimos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head> 
    <meta charset="UTF-8">
    <title> ONG Bilbioteca - Gerente </title>
    <link rel="stylesheet" type="text/css" href="css/style.css" />
    <link rel="stylesheet" type="text/css" href="css/relatorios.css" />
    <script src="javascript/JS_Logout.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
</head>
<body> 
    <header> 
        <h1> Bem-Vindo, <?php echo $_SESSION['usuario']?>! </h1>
        <form action="logout.php" method="POST">
            <button type="submit" class="logout">🚶🏻‍♂️ Logout</button> 
        </form>
    </header>

    <!-- Seção de Relatórios em Destaque -->
    <div class="relatorios-section">
        <!-- Botões de alternância -->
        <div class="relatorio-tabs" style="display: flex; gap: 30px; margin-bottom: 20px; justify-content: center; border-bottom: 2px solid #e9ecef;">
            <button id="tab-livros" class="tab-button active" onclick="mostrarRelatorio('livros')" style="padding: 15px 0; border: none; background: none; color: #007bff; cursor: pointer; font-weight: bold; font-size: 16px; position: relative; transition: all 0.3s; border-bottom: 3px solid #007bff;">
                📚 Livros
            </button>
            <button id="tab-auditoria" class="tab-button" onclick="mostrarRelatorio('auditoria')" style="padding: 15px 0; border: none; background: none; color: #6c757d; cursor: pointer; font-weight: bold; font-size: 16px; position: relative; transition: all 0.3s;">
                📊 Auditoria
            </button>
        </div>

        <!-- Conteúdo de Livros -->
        <div id="conteudo-livros" class="relatorio-conteudo">
            <h2>📚 Relatório de Livros Mais Emprestados</h2>
        
        <!-- Filtros e Gráfico -->
        <div style="display: flex; gap: 20px; margin: 20px 0; flex-wrap: wrap;">
            <!-- Filtros -->
            <div class="filtros-container" style="background: white; padding: 25px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid #e9ecef; flex: 1; min-width: 400px;">
                <h3 style="color: #333; margin-bottom: 20px; font-size: 18px; font-weight: 600;">🔍 Filtros de Busca</h3>
                <form method="GET" style="display: flex; flex-direction: column; gap: 15px;">
                    <div style="display: flex; gap: 20px;">
                        <div class="filtro-group" style="flex: 1;">
                            <label for="filtro_genero" style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057; font-size: 14px;">📚 Gênero</label>
                            <select name="filtro_genero" id="filtro_genero" style="width: 100%; padding: 12px 15px; border: 2px solid #e9ecef; border-radius: 10px; background: white; color: #495057; font-size: 14px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <option value="0">Todos os Gêneros</option>
                                <?php foreach ($generos as $genero): ?>
                                    <option value="<?= $genero['Cod_Genero'] ?>" <?= $filtro_genero == $genero['Cod_Genero'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($genero['Nome_Genero']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filtro-group" style="flex: 1;">
                            <label for="filtro_autor" style="display: block; margin-bottom: 8px; font-weight: 600; color: #495057; font-size: 14px;">✍️ Autor</label>
                            <select name="filtro_autor" id="filtro_autor" style="width: 100%; padding: 12px 15px; border: 2px solid #e9ecef; border-radius: 10px; background: white; color: #495057; font-size: 14px; transition: all 0.3s ease; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                                <option value="0">Todos os Autores</option>
                                <?php foreach ($autores as $autor): ?>
                                    <option value="<?= $autor['Cod_Autor'] ?>" <?= $filtro_autor == $autor['Cod_Autor'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($autor['Nome_Autor']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="filtro-buttons" style="display: flex; gap: 15px; width: 100%;">
                        <button type="submit" onclick="aplicarFiltrosFormulario()" style="flex: 1; background: linear-gradient(135deg, #007bff 0%, #0056b3 100%); color: white; padding: 15px 25px; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 16px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(0,123,255,0.3);">
                            🔍 Aplicar Filtros
                        </button>
                        
                        <button type="button" onclick="limparFiltros()" style="flex: 1; background: linear-gradient(135deg, #6c757d 0%, #495057 100%); color: white; padding: 15px 25px; border: none; border-radius: 10px; cursor: pointer; font-weight: 600; font-size: 16px; transition: all 0.3s ease; box-shadow: 0 4px 15px rgba(108,117,125,0.3);">
                            🗑️ Limpar Filtros
                        </button>
                    </div>
                </form>
            </div>

            <!-- Gráfico Pequeno -->
            <div class="grafico-container" style="background: white; border-radius: 15px; padding: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border: 1px solid #e9ecef; flex: 1; min-width: 300px;">
                <h3 style="color: #333; margin-bottom: 15px; font-size: 16px; font-weight: 600;">📅 Empréstimos por Mês (Últimos 6 Meses)</h3>
                <div style="position: relative; height: 200px; width: 100%;">
                    <canvas id="graficoBarras"></canvas>
                </div>
            </div>
        </div>

        <!-- Botões de Filtro por Categoria -->
        <div class="categorias-grid" style="display: flex; gap: 15px; margin: 20px 0; flex-wrap: wrap; justify-content: center;">
            <button class="categoria-btn" onclick="filtrarPorCategoria('todos')" style="padding: 12px 20px; border: 2px solid #17a2b8; border-radius: 25px; background: white; color: #17a2b8; cursor: pointer; font-weight: bold; transition: all 0.3s; display: flex; align-items: center; gap: 8px;">
                🌐 Todos os Livros
            </button>
            <button class="categoria-btn" onclick="filtrarPorCategoria('mais_emprestados')" style="padding: 12px 20px; border: 2px solid #28a745; border-radius: 25px; background: white; color: #28a745; cursor: pointer; font-weight: bold; transition: all 0.3s; display: flex; align-items: center; gap: 8px;">
                📈 Mais Emprestados
            </button>
            <button class="categoria-btn" onclick="filtrarPorCategoria('emprestados')" style="padding: 12px 20px; border: 2px solid #dc3545; border-radius: 25px; background: white; color: #dc3545; cursor: pointer; font-weight: bold; transition: all 0.3s; display: flex; align-items: center; gap: 8px;">
                🔄 Emprestados
            </button>
        </div>

        <!-- Botões de ação para PDF -->
        <div class="btn-group" style="margin: 30px 0; display: flex; gap: 15px; justify-content: center; flex-wrap: wrap;">
                            <button class="btn-pdf-modern" onclick="gerarRelatorioLivros()" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                📚 Relatório de Livros
                            </button>
                            <button class="btn-pdf-modern" onclick="gerarRelatorioEmprestimos()" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);">
                                🔄 Empréstimos Ativos
                            </button>
                            <button class="btn-pdf-modern" onclick="gerarRelatorioCompletoLivros()" style="background: linear-gradient(135deg, #6f42c1 0%, #6610f2 100%);">
                                📊 Relatório Completo
                            </button>
                        </div>


        <!-- Tabela de livros mais emprestados -->
        <div class="livros-table-container" style="background: white; border-radius: 10px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin: 20px 0;">
            <h3 style="color: #333; margin-bottom: 20px;">📚 Todos os Livros</h3>
            
            <?php if (empty($livros_emprestados)): ?>
                <div style="text-align: center; padding: 40px; color: #666;">
                    <h4>📚 Nenhum livro encontrado</h4>
                    <p>Não há livros que atendam aos critérios de filtro selecionados.</p>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                        <thead>
                            <tr style="background: #f8f9fa;">
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: bold;">Título</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: bold;">Autor</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: bold;">Gênero</th>
                                <th style="padding: 12px; text-align: left; border-bottom: 2px solid #dee2e6; font-weight: bold;">Editora</th>
                                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: bold;">Empréstimos</th>
                                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: bold;">Estoque</th>
                                <th style="padding: 12px; text-align: center; border-bottom: 2px solid #dee2e6; font-weight: bold;">Prateleira</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($livros_emprestados as $index => $livro): ?>
                                <tr class="livro-row" style="border-bottom: 1px solid #dee2e6;">
                                    <td style="padding: 12px; font-weight: bold;">
                                        <?= htmlspecialchars($livro['Titulo']) ?>
                                    </td>
                                    <td style="padding: 12px;">
                                        <?= htmlspecialchars($livro['Nome_Autor'] ?? 'N/A') ?>
                                    </td>
                                    <td style="padding: 12px;">
                                        <span style="background: #e9ecef; padding: 4px 8px; border-radius: 15px; font-size: 12px;">
                                            <?= htmlspecialchars($livro['Nome_Genero'] ?? 'N/A') ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px;">
                                        <?= htmlspecialchars($livro['Nome_Editora'] ?? 'N/A') ?>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <span class="emprestimos-count" style="background: #28a745; color: white; padding: 4px 8px; border-radius: 15px; font-weight: bold;">
                                            <?= $livro['total_emprestimos'] ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <span class="estoque-count" style="background: #17a2b8; color: white; padding: 4px 8px; border-radius: 15px; font-weight: bold;">
                                            <?= $livro['estoque_atual'] ?>
                                        </span>
                                    </td>
                                    <td style="padding: 12px; text-align: center;">
                                        <span style="background: #6f42c1; color: white; padding: 4px 8px; border-radius: 15px; font-weight: bold;">
                                            <?= $livro['Num_Prateleira'] ?? 'N/A' ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                        
                </div>
            <?php endif; ?>
        </div>


        <!-- Botões de ação -->
        </div>

        <!-- Conteúdo de Auditoria -->
        <div id="conteudo-auditoria" class="relatorio-conteudo" style="display: none;">
            <h2>📊 Relatórios de Auditoria - Última Semana</h2>

<!-- Botões de Navegação por Tabela -->
                    <div class="tabelas-grid">
                        <button class="tabela-btn tabela-btn-autor" onclick="mostrarTabela('autor')">
                            📚 Autor
                        </button>
                        <button class="tabela-btn tabela-btn-cliente" onclick="mostrarTabela('cliente')">
                            👥 Cliente
                        </button>
                        <button class="tabela-btn tabela-btn-funcionario" onclick="mostrarTabela('funcionario')">
                            👨‍💼 Funcionário
                        </button>
                        <button class="tabela-btn tabela-btn-livro" onclick="mostrarTabela('livro')">
                            📖 Livro
                        </button>
                        <button class="tabela-btn tabela-btn-emprestimo" onclick="mostrarTabela('emprestimo')">
                            🔄 Empréstimo
                        </button>
                        <button class="tabela-btn tabela-btn-todas active" onclick="mostrarTodasTabelas()">
                            🌐 Todas as Tabelas
                        </button>
                    </div>

        <!-- Container flex para gráfico e filtros -->
        <div style="display: flex; align-items: flex-start; gap: 30px; margin: 20px;">

            <!-- Gráfico à esquerda -->
            <div class="grafico" style="width: 350px;">
                <canvas id="graficoOperacoes"></canvas>
            </div>

            <!-- Cards e botões à direita -->
            <div style="flex: 1;">
                
                <!-- Quadros de operações -->
                <div class="operacoes-grid">
                    <div class="operacao-card" onclick="filtrarPorOperacao('INSERT')">
                        <div class="texto-container">
                            <div class="operacao-title">Cadastros</div>
                            <div class="operacao-count"><?= $cadastros ?></div>
                        </div>
                        <div class="operacao-icon">📝</div>
                    </div>
                    
                    <div class="operacao-card" onclick="filtrarPorOperacao('TODOS')">
                        <div class="texto-container">
                            <div class="operacao-title">Todos</div>
                            <div class="operacao-count"><?= $total_operacoes ?></div>
                        </div>
                        <div class="operacao-icon">📊</div>
                    </div>
                    
                    <div class="operacao-card" onclick="filtrarPorOperacao('DELETE')">
                        <div class="texto-container">
                            <div class="operacao-title">Excluídos</div>
                            <div class="operacao-count"><?= $exclusoes ?></div>
                        </div>
                        <div class="operacao-icon">🗑️</div>
                    </div>
                    
                    <div class="operacao-card" onclick="filtrarPorOperacao('UPDATE')">
                        <div class="texto-container">
                            <div class="operacao-title">Alterações</div>
                            <div class="operacao-count"><?= $alteracoes ?></div>
                        </div>
                        <div class="operacao-icon">✏️</div>
                    </div>
                </div>

                
            </div>
        </div>

        <?php if (empty($logs)): ?>
            <div class="no-logs">
                <h3>📊 Nenhuma operação registrada na última semana</h3>
                <p>O sistema de auditoria está funcionando perfeitamente!</p>
                <p><strong>Próximos passos:</strong> Realize algumas operações (cadastros, alterações, exclusões) e elas aparecerão aqui automaticamente.</p>
            </div>
        <?php else: ?>
            <br><br>
            <!-- Botões de controle global -->
            <div class="controles-globais">
                <button class="btn-controle" onclick="expandirTodas()">
                    🔓 Expandir Todas
                </button>
                <button class="btn-controle" onclick="colapsarTodas()">
                    🔒 Colapsar Todas
                </button>
                <button class="btn-controle" onclick="expandirComOperacoes()">
                    📊 Apenas com Operações
                </button>
            </div>

            <div class="btn-group">
                <button class="btn-pdf-modern" onclick="gerarRelatorioCompleto()">
                    📊 Relatório Completo
                </button>
                <button class="btn-pdf-modern" onclick="gerarRelatorioResumido()">
                    📋 Resumo Executivo
                </button>
                <button class="btn-pdf-modern" onclick="gerarRelatorioPorTabela()">
                    🗂️ Por Categoria
                </button>
            </div>

            <!-- Logs agrupados por tabela -->
            <?php foreach ($logs_por_tabela as $tabela => $logs_tabela): ?>
                <div class="tabela-section" id="tabela-<?= $tabela ?>">
                    <div class="tabela-header" onclick="toggleTabela('<?= $tabela ?>')">
                        <h2 class="tabela-title">
                            <span class="toggle-icon" id="icon-<?= $tabela ?>">▼</span>
                            📋 <?= ucfirst(htmlspecialchars($tabela)) ?>
                            <span class="tabela-count">(<?= count($logs_tabela) ?> operações)</span>
                        </h2>
                    </div>
                    <div class="tabela-content" id="content-<?= $tabela ?>">
                        <?php foreach ($logs_tabela as $log): ?>
                            <div class="log-entry" data-tabela="<?= htmlspecialchars($log['tabela']) ?>" data-operacao="<?= htmlspecialchars($log['operacao']) ?>">
                                <div class="log-header">
                                    <div>
                                        <span class="log-type <?= strtolower($log['operacao']) ?>"><?= htmlspecialchars($log['operacao_pt']) ?></span>
                                        <strong>ID: <?= htmlspecialchars($log['id_registro']) ?></strong>
                                    </div>
                                </div>
                                
                                <?php if ($log['dados_anteriores'] || $log['dados_novos']): ?>
                                    <div class="log-details">
                                        <?php if ($log['dados_anteriores']): ?>
                                            <div><strong>Dados Anteriores:</strong> <?= htmlspecialchars($log['dados_anteriores']) ?></div>
                                        <?php endif; ?>
                                        <?php if ($log['dados_novos']): ?>
                                            <div><strong>Dados Novos:</strong> <?= htmlspecialchars($log['dados_novos']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            
            <!-- Botões de controle global -->
            <div class="controles-globais">
                <button class="btn-controle" onclick="expandirTodas()">
                    🔓 Expandir Todas
                </button>
                <button class="btn-controle" onclick="colapsarTodas()">
                    🔒 Colapsar Todas
                </button>
                <button class="btn-controle" onclick="expandirComOperacoes()">
                    📊 Apenas com Operações
                </button>
            </div>
        <?php endif; ?>
        <a href="#" class="btn-voltar" onclick="voltarAoTopo()"> Voltar para o topo ↑ </a>
        <div class="btn-group">
            <button class="btn-pdf-modern" onclick="gerarRelatorioCompleto()">
                📊 Relatório Completo
            </button>
            <button class="btn-pdf-modern" onclick="gerarRelatorioResumido()">
                📋 Resumo Executivo
            </button>
            <button class="btn-pdf-modern" onclick="gerarRelatorioPorTabela()">
                🗂️ Por Categoria
            </button>
        </div>
        </div>
    </div>

    <ul class="nav-bar">
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn"> Funcionários </a>
            <div class="dropdown-content">
                <a href="subtelas/cadastro_funcionario.php"> Registrar Funcionário </a>
                <a href="subtelas/consultar_funcionario.php"> Consultar Funcionários </a>
            </div>
        </li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn"> Clientes </a>
            <div class="dropdown-content">
                <a href="subtelas/cadastro_cliente.php"> Registrar Cliente </a>
                <a href="subtelas/consultar_cliente.php"> Consultar Clientes </a>
            </div>
        </li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn"> Livros </a>
            <div class="dropdown-content">
                <a href="subtelas/registrar_livro.php"> Registrar Livro </a>
                <a href="subtelas/consultar_livro.php"> Consultar Livros </a>
                <a href="subtelas/registrar_autor.php"> Registrar Autor </a>
                <a href="subtelas/consultar_autor.php"> Consultar Autores </a>
                <a href="subtelas/registrar_editora.php"> Registrar Editora </a>
                <a href="subtelas/consultar_editora.php"> Consultar Editoras </a>
            </div>
        </li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn"> Empréstimos </a>
            <div class="dropdown-content">
                <a href="subtelas/registrar_emprestimo.php"> Registrar Empréstimo </a>
                <a href="subtelas/consultar_emprestimo.php"> Consultar Empréstimos </a>
                <a href="subtelas/consultar_multa.php"> Consultar Multas </a>
            </div>
        </li>
        <li class="dropdown">
            <a href="javascript:void(0)" class="dropbtn"> Doador </a>
            <div class="dropdown-content">
                <a href="subtelas/registrar_doador.php"> Registrar Doador </a>
                <a href="subtelas/consultar_doador.php"> Consultar Doadores </a>
            </div>
        </li>
    </ul>

    <script>
    let grafico;

    function atualizarGrafico() {
        let qtdInsert = 0, qtdUpdate = 0, qtdDelete = 0;

        // Contar todas as operações, independentemente de estarem visíveis
        document.querySelectorAll('.log-entry').forEach(entry => {
            const operacao = entry.dataset.operacao;
            if (operacao === "INSERT") qtdInsert++;
            if (operacao === "UPDATE") qtdUpdate++;
            if (operacao === "DELETE") qtdDelete++;
        });

        const ctx = document.getElementById("graficoOperacoes").getContext("2d");
        if (grafico) grafico.destroy();

        grafico = new Chart(ctx, {
            type: "pie",
            data: {
                labels: ["Cadastrados", "Alterados", "Excluídos"],
                datasets: [{
                    data: [qtdInsert, qtdUpdate, qtdDelete],
                    backgroundColor: ["#4CAF50", "#FFC107", "#F44336"],
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { position: "bottom" }
                }
            }
        });
    }

    function filtrarPorOperacao(operacao) {
        document.querySelectorAll('.operacao-card').forEach(card => card.classList.remove('active'));

        const clickedCard = event.currentTarget;
        clickedCard.classList.add('active');

        document.querySelectorAll('.log-entry').forEach(entry => {
            const operacaoMatch = operacao === 'TODOS' || entry.dataset.operacao === operacao;
            const estaVisivelTabela = entry.closest('.tabela-section')?.style.display !== 'none';

            entry.style.display = (operacaoMatch && estaVisivelTabela) ? 'block' : 'none';
        });

        atualizarGrafico();
    }

    function mostrarTabela(tabelaNome) {
        document.querySelectorAll('.tabela-section').forEach(secao => {
            const contemTabela = secao.querySelectorAll('.log-entry[data-tabela="' + tabelaNome + '"]').length > 0;
            secao.style.display = contemTabela ? 'block' : 'none';
        });

        document.querySelectorAll('.tabela-btn').forEach(btn => btn.classList.remove('active'));
        event.currentTarget.classList.add('active');

        // Mostrar todas as operações da tabela
        document.querySelectorAll('.log-entry').forEach(entry => {
            entry.style.display = (entry.dataset.tabela === tabelaNome) ? 'block' : 'none';
        });

        atualizarGrafico();
    }

    function mostrarTodasTabelas() {
        document.querySelectorAll('.tabela-section').forEach(secao => secao.style.display = 'block');

        document.querySelectorAll('.tabela-btn').forEach(btn => btn.classList.remove('active'));
        event.currentTarget.classList.add('active');

        document.querySelectorAll('.log-entry').forEach(entry => {
            entry.style.display = 'block';
        });

        atualizarGrafico();
    }

    // Inicializa o gráfico ao carregar a página
    window.onload = () => {
        atualizarGrafico();
    };

    // Função para voltar ao topo da página
    function voltarAoTopo() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }

    // ===== NOVAS FUNÇÕES DE PDF MODERNAS =====
    
    // Função para gerar relatório completo
    async function gerarRelatorioCompleto() {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Cabeçalho
            doc.setFontSize(20);
            doc.setTextColor(44, 62, 80);
            doc.text('ONG Biblioteca - Relatório de Auditoria', 105, 20, { align: 'center' });
            
            doc.setFontSize(12);
            doc.setTextColor(52, 73, 94);
            doc.text(`Gerado em: ${new Date().toLocaleDateString('pt-BR')} às ${new Date().toLocaleTimeString('pt-BR')}`, 105, 30, { align: 'center' });
            
            doc.setFontSize(14);
            doc.setTextColor(41, 128, 185);
            doc.text('Resumo Executivo', 20, 45);
            
            // Estatísticas
            const total = <?= $total_operacoes ?>;
            const cadastros = <?= $cadastros ?>;
            const alteracoes = <?= $alteracoes ?>;
            const exclusoes = <?= $exclusoes ?>; 
            
            doc.setFontSize(10);
            doc.setTextColor(52, 73, 94);
            doc.text(`Total de Operações: ${total}`, 20, 55);
            doc.text(`Cadastros: ${cadastros}`, 20, 62);
            doc.text(`Alterações: ${alteracoes}`, 20, 69);
            doc.text(`Exclusões: ${exclusoes}`, 20, 76);
            
            // Dados das tabelas
            let yPos = 90;
            const logs = <?= json_encode($logs) ?>;
            const logsPorTabela = <?= json_encode($logs_por_tabela) ?>;
            
            Object.keys(logsPorTabela).forEach((tabela, index) => {
                if (yPos > 250) {
                    doc.addPage();
                    yPos = 20;
                }
                
                doc.setFontSize(12);
                doc.setTextColor(41, 128, 185);
                doc.text(`${tabela.charAt(0).toUpperCase() + tabela.slice(1)}`, 20, yPos);
                
                yPos += 8;
                doc.setFontSize(9);
                doc.setTextColor(52, 73, 94);
                
                logsPorTabela[tabela].forEach((log, logIndex) => {
                    if (yPos > 270) {
                        doc.addPage();
                        yPos = 20;
                    }
                    
                    const operacao = log.operacao === 'INSERT' ? 'Cadastro' : 
                                   log.operacao === 'UPDATE' ? 'Alteração' : 'Exclusão';
                    
                    doc.text(`${operacao} - ID: ${log.id_registro}`, 25, yPos);
                    if (log.dados_anteriores) {
                        yPos += 5;
                        doc.text(`Anterior: ${log.dados_anteriores}`, 30, yPos);
                    }
                    if (log.dados_novos) {
                        yPos += 5;
                        doc.text(`Novo: ${log.dados_novos}`, 30, yPos);
                    }
                    yPos += 8;
                });
                
                yPos += 5;
            });
            
            // Rodapé
            doc.setFontSize(8);
            doc.setTextColor(149, 165, 166);
            doc.text('ONG Biblioteca - Sistema de Auditoria Automática', 105, 280, { align: 'center' });
            
            // Salvar o PDF
            const nomeArquivo = `relatorio_auditoria_${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(nomeArquivo);
            
            Swal.fire({
                icon: 'success',
                title: 'PDF Gerado!',
                text: `Relatório completo salvo como "${nomeArquivo}"`,
                confirmButtonColor: '#ffbcfc'
            });
            
        } catch (error) {
            console.error('Erro ao gerar PDF:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro ao gerar PDF',
                text: 'Não foi possível gerar o relatório. Tente novamente.',
                confirmButtonColor: '#ffbcfc'
            });
        }
    }
    
    // Função para gerar relatório resumido
    async function gerarRelatorioResumido() {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Cabeçalho
            doc.setFontSize(18);
            doc.setTextColor(44, 62, 80);
            doc.text('ONG Biblioteca - Resumo Executivo', 105, 20, { align: 'center' });
            
            doc.setFontSize(10);
            doc.setTextColor(52, 73, 94);
            doc.text(`Período: Última semana (${new Date().toLocaleDateString('pt-BR')})`, 105, 30, { align: 'center' });
            
            // Estatísticas em tabela
            const dados = [
                ['Operação', 'Quantidade', 'Percentual'],
                ['Cadastros', <?= $cadastros ?>, `${((<?= $cadastros ?> / <?= $total_operacoes ?>) * 100).toFixed(1)}%`],
                ['Alterações', <?= $alteracoes ?>, `${((<?= $alteracoes ?> / <?= $total_operacoes ?>) * 100).toFixed(1)}%`],
                ['Exclusões', <?= $exclusoes ?>, `${((<?= $exclusoes ?> / <?= $total_operacoes ?>) * 100).toFixed(1)}%`],
                ['Total', <?= $total_operacoes ?>, '100%']
            ];
            
            doc.autoTable({
                startY: 40,
                head: [dados[0]],
                body: dados.slice(1),
                theme: 'grid',
                headStyles: { fillColor: [41, 128, 185] },
                styles: { fontSize: 10 }
            });
            
            // Gráfico de pizza como imagem
            const canvas = document.getElementById('graficoOperacoes');
                if (canvas) {
                    const imgData = canvas.toDataURL('image/png');

                    const x = 60;              // posição X do gráfico
                    const y = 100;             // posição Y do gráfico (início da imagem)
                    const pdfWidth = 80;       // largura do gráfico no PDF
                    const pdfHeight = 80;      // altura do gráfico no PDF

                    // Centralizar o texto considerando a largura da página (geralmente 210 para A4)
                    const pageWidth = doc.internal.pageSize.getWidth();
                    const textoY = y - 10;     // 10 unidades acima do gráfico

                    // Escrever o texto acima do gráfico, centralizado
                    doc.setFontSize(12);
                     doc.setTextColor(41, 128, 185);
                    doc.text('Distribuição das Operações', pageWidth / 2, textoY, { align: 'center' });

                    // Inserir a imagem do gráfico abaixo do texto
                    doc.addImage(imgData, 'PNG', x, y, pdfWidth, pdfHeight);
                }


            
            // Análise
            doc.setFontSize(12);
            doc.setTextColor(41, 128, 185);
            doc.text('Análise dos Dados:', 20, 200);
            
            doc.setFontSize(10);
            doc.setTextColor(52, 73, 94);
            doc.text('• Sistema de auditoria funcionando normalmente', 20, 210);
            doc.text('• Todas as operações estão sendo registradas', 20, 217);
            doc.text('• Rastreabilidade completa das alterações', 20, 224);
            
            // Salvar
            const nomeArquivo = `resumo_executivo_${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(nomeArquivo);
            
            Swal.fire({
                icon: 'success',
                title: 'Resumo Gerado!',
                text: `Relatório resumido salvo como "${nomeArquivo}"`,
                confirmButtonColor: '#ffbcfc'
            });
            
        } catch (error) {
            console.error('Erro ao gerar resumo:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro ao gerar resumo',
                text: 'Não foi possível gerar o resumo. Tente novamente.',
                confirmButtonColor: '#ffbcfc'
            });
        }
    }
    
    // Função para gerar relatório por tabela
    async function gerarRelatorioPorTabela() {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            const logsPorTabela = <?= json_encode($logs_por_tabela) ?>;
            let pagina = 1;
            
            Object.keys(logsPorTabela).forEach((tabela, index) => {
                if (index > 0) {
                    doc.addPage();
                    pagina++;
                }
                
                // Cabeçalho da página
                doc.setFontSize(16);
                doc.setTextColor(44, 62, 80);
                doc.text(`Relatório de Auditoria - ${tabela.charAt(0).toUpperCase() + tabela.slice(1)}`, 105, 20, { align: 'center' });
                
                doc.setFontSize(10);
                doc.setTextColor(52, 73, 94);
                doc.text(`Página ${pagina} - ${new Date().toLocaleDateString('pt-BR')}`, 105, 30, { align: 'center' });
                
                // Dados da tabela
                    const dados = logsPorTabela[tabela].map(log => [
                        log.operacao === 'INSERT' ? 'Cadastro' : 
                        log.operacao === 'UPDATE' ? 'Alteração' : 'Exclusão',
                        log.id_registro,
                        log.dados_novos || '-', 
                        new Date(log.data_operacao).toLocaleDateString('pt-BR')
                    ]);

                    doc.autoTable({
                        startY: 40,
                        head: [['Operação', 'ID', 'Dados Principais', 'Data']], 
                        body: dados,
                        theme: 'grid',
                        headStyles: { fillColor: [41, 128, 185] },
                        styles: { fontSize: 8 },
                        columnStyles: {
                            0: { cellWidth: 25 },
                            1: { cellWidth: 15 },
                            2: { cellWidth: 80 }, 
                            3: { cellWidth: 25 }
                        }
                    });

                
                // Estatísticas da tabela
                const totalTabela = logsPorTabela[tabela].length;
                const cadastrosTabela = logsPorTabela[tabela].filter(log => log.operacao === 'INSERT').length;
                const alteracoesTabela = logsPorTabela[tabela].filter(log => log.operacao === 'UPDATE').length;
                const exclusoesTabela = logsPorTabela[tabela].filter(log => log.operacao === 'DELETE').length;
                
                let yPos = doc.lastAutoTable.finalY + 10;
                doc.setFontSize(11);
                doc.setTextColor(41, 128, 185);
                doc.text(`Estatísticas da Tabela ${tabela}:`, 20, yPos);
                
                yPos += 8;
                doc.setFontSize(9);
                doc.setTextColor(52, 73, 94);
                doc.text(`Total: ${totalTabela} | Cadastros: ${cadastrosTabela} | Alterações: ${alteracoesTabela} | Exclusões: ${exclusoesTabela}`, 20, yPos);
            });
            
            // Salvar
            const nomeArquivo = `relatorio_por_tabela_${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(nomeArquivo);
            
            Swal.fire({
                icon: 'success',
                title: 'Relatório por Tabela Gerado!',
                text: `Relatório categorizado salvo como "${nomeArquivo}"`,
                confirmButtonColor: '#ffbcfc'
            });
            
        } catch (error) {
            console.error('Erro ao gerar relatório por tabela:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro ao gerar relatório',
                text: 'Não foi possível gerar o relatório por tabela. Tente novamente.',
                confirmButtonColor: '#ffbcfc'
            });
        }
    }

    // Função para expandir/colapsar todas as tabelas
    function expandirTodas() {
        document.querySelectorAll('.tabela-section').forEach(secao => {
            secao.style.display = 'block';
            secao.classList.remove('colapsada');
            secao.querySelector('.toggle-icon').textContent = '▼';
            secao.querySelector('.tabela-content').style.display = 'block';
        });
        document.querySelectorAll('.tabela-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector('.tabela-btn-todas').classList.add('active');
        
        // Atualizar gráfico
        setTimeout(atualizarGrafico, 100);
    }

    function colapsarTodas() {
        document.querySelectorAll('.tabela-section').forEach(secao => {
            secao.style.display = 'block';
            secao.classList.add('colapsada');
            secao.querySelector('.toggle-icon').textContent = '▶';
            secao.querySelector('.tabela-content').style.display = 'none';
        });
        document.querySelectorAll('.tabela-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector('.tabela-btn-todas').classList.add('active');
        
        // Atualizar gráfico
        setTimeout(atualizarGrafico, 100);
    }

    // Função para expandir apenas as tabelas com operações
    function expandirComOperacoes() {
        document.querySelectorAll('.tabela-section').forEach(secao => {
            const temOperacoes = secao.querySelectorAll('.log-entry').length > 0;
            if (temOperacoes) {
                secao.style.display = 'block';
                secao.classList.remove('colapsada');
                secao.querySelector('.toggle-icon').textContent = '▼';
                secao.querySelector('.tabela-content').style.display = 'block';
            } else {
                secao.style.display = 'none';
            }
        });
        document.querySelectorAll('.tabela-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelector('.tabela-btn-todas').classList.add('active');
        
        // Atualizar gráfico
        setTimeout(atualizarGrafico, 100);
    }

    // Função para alternar a visibilidade de uma tabela
    function toggleTabela(tabelaId) {
        const secao = document.getElementById('tabela-' + tabelaId);
        const conteudo = secao.querySelector('.tabela-content');
        const icon = secao.querySelector('#icon-' + tabelaId);

        if (conteudo.style.display === 'none' || secao.classList.contains('colapsada')) {
            conteudo.style.display = 'block';
            secao.classList.remove('colapsada');
            icon.textContent = '▼';
        } else {
            conteudo.style.display = 'none';
            secao.classList.add('colapsada');
            icon.textContent = '▶';
        }
        
        // Atualizar gráfico após toggle
        setTimeout(atualizarGrafico, 100);
    }

    // Inicializar estado das tabelas (todas expandidas por padrão)
    document.addEventListener('DOMContentLoaded', function() {
        // Aguardar um pouco para garantir que o DOM esteja carregado
        setTimeout(() => {
            document.querySelectorAll('.tabela-section').forEach(secao => {
                secao.classList.remove('colapsada');
                secao.querySelector('.tabela-content').style.display = 'block';
                secao.querySelector('.toggle-icon').textContent = '▼';
            });
        }, 100);
    });

    // ===== FUNÇÃO PARA ALTERNAR RELATÓRIOS =====
    
    function mostrarRelatorio(tipo) {
        // Ocultar todos os conteúdos
        document.getElementById('conteudo-auditoria').style.display = 'none';
        document.getElementById('conteudo-livros').style.display = 'none';
        
        // Remover classe active de todos os botões e resetar estilos
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active');
            btn.style.color = '#6c757d';
            btn.style.borderBottom = 'none';
        });
        
        // Mostrar o conteúdo selecionado
        if (tipo === 'auditoria') {
            document.getElementById('conteudo-auditoria').style.display = 'block';
            document.getElementById('tab-auditoria').classList.add('active');
            document.getElementById('tab-auditoria').style.color = '#007bff';
            document.getElementById('tab-auditoria').style.borderBottom = '3px solid #007bff';
        } else if (tipo === 'livros') {
            document.getElementById('conteudo-livros').style.display = 'block';
            document.getElementById('tab-livros').classList.add('active');
            document.getElementById('tab-livros').style.color = '#007bff';
            document.getElementById('tab-livros').style.borderBottom = '3px solid #007bff';
        }
    }

    // ===== FUNÇÕES PARA FILTROS DE LIVROS =====
    
    // Inicializar cores originais dos botões quando a página carregar
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.categoria-btn').forEach(btn => {
            if (!btn.getAttribute('data-original-color')) {
                btn.setAttribute('data-original-color', btn.style.color);
            }
        });
        
        // Adicionar efeitos de hover aos selects
        document.querySelectorAll('select').forEach(select => {
            select.addEventListener('focus', function() {
                this.style.borderColor = '#007bff';
                this.style.boxShadow = '0 0 0 3px rgba(0,123,255,0.1)';
            });
            
            select.addEventListener('blur', function() {
                this.style.borderColor = '#e9ecef';
                this.style.boxShadow = '0 2px 4px rgba(0,0,0,0.05)';
            });
            
            // Aplicar filtros automaticamente quando o valor mudar
            select.addEventListener('change', function() {
                aplicarFiltrosFormulario();
            });
        });
        
        // Adicionar efeitos de hover aos botões
        document.querySelectorAll('button[type="submit"], a[href="gerente.php"]').forEach(btn => {
            btn.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
                this.style.boxShadow = '0 6px 20px rgba(0,0,0,0.15)';
            });
            
            btn.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 15px rgba(0,0,0,0.1)';
            });
        });
        
        // Criar gráfico de barras quando a página carregar
        criarGraficoBarras();
    });
    
    function filtrarPorCategoria(categoria) {
        // Remover classe active de todos os botões
        document.querySelectorAll('.categoria-btn').forEach(btn => {
            btn.classList.remove('active');
            btn.style.background = 'white';
            // Restaurar a cor original do texto
            const originalColor = btn.getAttribute('data-original-color');
            if (originalColor) {
                btn.style.color = originalColor;
            }
        });
        
        // Adicionar classe active ao botão clicado
        event.currentTarget.classList.add('active');
        // Salvar a cor original se ainda não foi salva
        if (!event.currentTarget.getAttribute('data-original-color')) {
            event.currentTarget.setAttribute('data-original-color', event.currentTarget.style.color);
        }
        event.currentTarget.style.background = event.currentTarget.getAttribute('data-original-color');
        event.currentTarget.style.color = 'white';
        
        // Alterar título da tabela baseado na categoria
        const tituloTabela = document.querySelector('.livros-table-container h3');
        
        // Aplicar filtros de categoria
        switch(categoria) {
            case 'todos':
                // Mostrar todos os livros
                document.querySelectorAll('.livro-row').forEach(row => row.style.display = 'table-row');
                if (tituloTabela) tituloTabela.textContent = '📚 Todos os Livros';
                break;
            case 'mais_emprestados':
                // Mostrar apenas os 5 livros mais emprestados
                const todasAsLinhas = Array.from(document.querySelectorAll('.livro-row'));
                
                // Ordenar por número de empréstimos (decrescente)
                todasAsLinhas.sort((a, b) => {
                    const emprestimosA = parseInt(a.querySelector('.emprestimos-count').textContent);
                    const emprestimosB = parseInt(b.querySelector('.emprestimos-count').textContent);
                    return emprestimosB - emprestimosA;
                });
                
                // Mostrar apenas os primeiros 5 e esconder o resto
                todasAsLinhas.forEach((row, index) => {
                    row.style.display = index < 5 ? 'table-row' : 'none';
                });
                
                if (tituloTabela) tituloTabela.textContent = '📈 Top 5 Livros Mais Emprestados';
                break;
            case 'emprestados':
                // Mostrar apenas livros que estão emprestados (com empréstimos ativos)
                document.querySelectorAll('.livro-row').forEach(row => {
                    const emprestimos = parseInt(row.querySelector('.emprestimos-count').textContent);
                    // Mostrar livros que têm empréstimos ativos (maior que 0)
                    row.style.display = emprestimos > 0 ? 'table-row' : 'none';
                });
                if (tituloTabela) tituloTabela.textContent = '🔄 Livros Emprestados';
                break;
        }
    }

    // Função para aplicar filtros do formulário
    function aplicarFiltrosFormulario() {
        const form = document.querySelector('form[method="GET"]');
        if (form) {
            form.submit();
        }
    }

    // Função para limpar filtros
    function limparFiltros() {
        window.location.href = 'gerente.php';
    }

    // ===== FUNÇÕES PARA RELATÓRIOS DE LIVROS =====
    
    // Função para criar gráfico de barras
    function criarGraficoBarras() {
        const ctx = document.getElementById('graficoBarras');
        if (!ctx) return;
        
        const dadosGrafico = <?= json_encode($dados_grafico) ?>;
        
        const labels = dadosGrafico.map(item => item.mes_formatado);
        const dados = dadosGrafico.map(item => parseInt(item.total_emprestimos));
        
        // Cores em gradiente para mostrar evolução temporal
        const cores = dados.map((_, index) => {
            const intensidade = (index + 1) / dados.length;
            return `rgba(${Math.floor(54 + (201 - 54) * intensidade)}, ${Math.floor(162 + (203 - 162) * intensidade)}, ${Math.floor(235 + (197 - 235) * intensidade)}, 0.8)`;
        });
        
        const bordas = dados.map((_, index) => {
            const intensidade = (index + 1) / dados.length;
            return `rgba(${Math.floor(54 + (201 - 54) * intensidade)}, ${Math.floor(162 + (203 - 162) * intensidade)}, ${Math.floor(235 + (197 - 235) * intensidade)}, 1)`;
        });
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Empréstimos',
                    data: dados,
                    backgroundColor: cores,
                    borderColor: bordas,
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: 'white',
                        bodyColor: 'white',
                        borderColor: 'rgba(255, 255, 255, 0.2)',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' empréstimos';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#666',
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#666',
                            font: {
                                size: 10,
                                weight: 'bold'
                            },
                            maxRotation: 45,
                            minRotation: 0
                        }
                    }
                },
                animation: {
                    duration: 1500,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }
    
    // Função para gerar relatório de livros mais emprestados
    async function gerarRelatorioLivros() {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Cabeçalho
            doc.setFontSize(20);
            doc.setTextColor(44, 62, 80);
            doc.text('ONG Biblioteca - Relatório de Livros Mais Emprestados', 105, 20, { align: 'center' });
            
            doc.setFontSize(12);
            doc.setTextColor(52, 73, 94);
            doc.text(`Gerado em: ${new Date().toLocaleDateString('pt-BR')} às ${new Date().toLocaleTimeString('pt-BR')}`, 105, 30, { align: 'center' });
            
            // Estatísticas
            const totalLivros = <?= count($livros_emprestados) ?>;
            const totalEmprestimos = <?= count($emprestimos_ativos) ?>;
            const totalEmprestimosHistoricos = <?= array_sum(array_column($livros_emprestados, 'total_emprestimos')) ?>;
            
            doc.setFontSize(14);
            doc.setTextColor(41, 128, 185);
            doc.text('Resumo Executivo', 20, 45);
            
            doc.setFontSize(10);
            doc.setTextColor(52, 73, 94);
            doc.text(`Total de Livros: ${totalLivros}`, 20, 55);
            doc.text(`Empréstimos Ativos: ${totalEmprestimos}`, 20, 62);
            doc.text(`Total de Empréstimos Históricos: ${totalEmprestimosHistoricos}`, 20, 69);
            
            // Dados dos livros
            const livros = <?= json_encode($livros_emprestados) ?>;
            const dados = livros.map((livro, index) => [
                index + 1,
                livro.Titulo,
                livro.Nome_Autor || 'N/A',
                livro.Nome_Genero || 'N/A',
                livro.Nome_Editora || 'N/A',
                livro.total_emprestimos,
                livro.estoque_atual,
                livro.Num_Prateleira || 'N/A'
            ]);
            
            doc.autoTable({
                startY: 80,
                head: [['Posição', 'Título', 'Autor', 'Gênero', 'Editora', 'Empréstimos', 'Estoque', 'Prateleira']],
                body: dados,
                theme: 'grid',
                headStyles: { fillColor: [41, 128, 185] },
                styles: { fontSize: 8 },
                columnStyles: {
                    0: { cellWidth: 15 },
                    1: { cellWidth: 40 },
                    2: { cellWidth: 30 },
                    3: { cellWidth: 20 },
                    4: { cellWidth: 25 },
                    5: { cellWidth: 20 },
                    6: { cellWidth: 15 },
                    7: { cellWidth: 15 }
                }
            });
            
            // Salvar
            const nomeArquivo = `relatorio_livros_${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(nomeArquivo);
            
            Swal.fire({
                icon: 'success',
                title: 'Relatório de Livros Gerado!',
                text: `Relatório salvo como "${nomeArquivo}"`,
                confirmButtonColor: '#ffbcfc'
            });
            
        } catch (error) {
            console.error('Erro ao gerar relatório de livros:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro ao gerar relatório',
                text: 'Não foi possível gerar o relatório de livros. Tente novamente.',
                confirmButtonColor: '#ffbcfc'
            });
        }
    }
    
    // Função para gerar relatório de empréstimos ativos
    async function gerarRelatorioEmprestimos() {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Cabeçalho
            doc.setFontSize(20);
            doc.setTextColor(44, 62, 80);
            doc.text('ONG Biblioteca - Relatório de Empréstimos Ativos', 105, 20, { align: 'center' });
            
            doc.setFontSize(12);
            doc.setTextColor(52, 73, 94);
            doc.text(`Gerado em: ${new Date().toLocaleDateString('pt-BR')} às ${new Date().toLocaleTimeString('pt-BR')}`, 105, 30, { align: 'center' });
            
            // Dados dos empréstimos
            const emprestimos = <?= json_encode($emprestimos_ativos) ?>;
            const dados = emprestimos.map(emprestimo => [
                emprestimo.Cod_Emprestimo,
                emprestimo.titulo_livro,
                emprestimo.Nome_Autor || 'N/A',
                emprestimo.Nome_Genero || 'N/A',
                emprestimo.nome_cliente,
                new Date(emprestimo.Data_Emprestimo).toLocaleDateString('pt-BR'),
                new Date(emprestimo.Data_Devolucao).toLocaleDateString('pt-BR'),
                emprestimo.Status_Emprestimo
            ]);
            
            doc.autoTable({
                startY: 45,
                head: [['ID', 'Livro', 'Autor', 'Gênero', 'Cliente', 'Data Empréstimo', 'Data Devolução', 'Status']],
                body: dados,
                theme: 'grid',
                headStyles: { fillColor: [41, 128, 185] },
                styles: { fontSize: 8 },
                columnStyles: {
                    0: { cellWidth: 15 },
                    1: { cellWidth: 35 },
                    2: { cellWidth: 25 },
                    3: { cellWidth: 20 },
                    4: { cellWidth: 25 },
                    5: { cellWidth: 20 },
                    6: { cellWidth: 20 },
                    7: { cellWidth: 15 }
                }
            });
            
            // Salvar
            const nomeArquivo = `relatorio_emprestimos_${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(nomeArquivo);
            
            Swal.fire({
                icon: 'success',
                title: 'Relatório de Empréstimos Gerado!',
                text: `Relatório salvo como "${nomeArquivo}"`,
                confirmButtonColor: '#ffbcfc'
            });
            
        } catch (error) {
            console.error('Erro ao gerar relatório de empréstimos:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro ao gerar relatório',
                text: 'Não foi possível gerar o relatório de empréstimos. Tente novamente.',
                confirmButtonColor: '#ffbcfc'
            });
        }
    }
    
    // Função para gerar relatório completo de livros
    async function gerarRelatorioCompletoLivros() {
        try {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            
            // Cabeçalho
            doc.setFontSize(20);
            doc.setTextColor(44, 62, 80);
            doc.text('ONG Biblioteca - Relatório Completo de Livros', 105, 20, { align: 'center' });
            
            doc.setFontSize(12);
            doc.setTextColor(52, 73, 94);
            doc.text(`Gerado em: ${new Date().toLocaleDateString('pt-BR')} às ${new Date().toLocaleTimeString('pt-BR')}`, 105, 30, { align: 'center' });
            
            // Estatísticas gerais
            const totalLivros = <?= count($livros_emprestados) ?>;
            const totalEmprestimos = <?= count($emprestimos_ativos) ?>;
            const totalEmprestimosHistoricos = <?= array_sum(array_column($livros_emprestados, 'total_emprestimos')) ?>;
            
            doc.setFontSize(14);
            doc.setTextColor(41, 128, 185);
            doc.text('Estatísticas Gerais', 20, 45);
            
            doc.setFontSize(10);
            doc.setTextColor(52, 73, 94);
            doc.text(`Total de Livros: ${totalLivros}`, 20, 55);
            doc.text(`Empréstimos Ativos: ${totalEmprestimos}`, 20, 62);
            doc.text(`Total de Empréstimos Históricos: ${totalEmprestimosHistoricos}`, 20, 69);
            
            // Ranking de livros
            doc.setFontSize(14);
            doc.setTextColor(41, 128, 185);
            doc.text('Ranking de Livros Mais Emprestados', 20, 85);
            
            const livros = <?= json_encode($livros_emprestados) ?>;
            const dadosLivros = livros.map((livro, index) => [
                index + 1,
                livro.Titulo,
                livro.Nome_Autor || 'N/A',
                livro.Nome_Genero || 'N/A',
                livro.total_emprestimos,
                livro.estoque_atual
            ]);
            
            doc.autoTable({
                startY: 95,
                head: [['Posição', 'Título', 'Autor', 'Gênero', 'Empréstimos', 'Estoque']],
                body: dadosLivros,
                theme: 'grid',
                headStyles: { fillColor: [41, 128, 185] },
                styles: { fontSize: 8 },
                columnStyles: {
                    0: { cellWidth: 20 },
                    1: { cellWidth: 50 },
                    2: { cellWidth: 35 },
                    3: { cellWidth: 25 },
                    4: { cellWidth: 20 },
                    5: { cellWidth: 15 }
                }
            });
            
            // Nova página para empréstimos ativos
            doc.addPage();
            doc.setFontSize(16);
            doc.setTextColor(41, 128, 185);
            doc.text('Empréstimos Ativos', 20, 20);
            
            const emprestimos = <?= json_encode($emprestimos_ativos) ?>;
            const dadosEmprestimos = emprestimos.map(emprestimo => [
                emprestimo.Cod_Emprestimo,
                emprestimo.titulo_livro,
                emprestimo.nome_cliente,
                new Date(emprestimo.Data_Emprestimo).toLocaleDateString('pt-BR'),
                new Date(emprestimo.Data_Devolucao).toLocaleDateString('pt-BR')
            ]);
            
            doc.autoTable({
                startY: 30,
                head: [['ID', 'Livro', 'Cliente', 'Data Empréstimo', 'Data Devolução']],
                body: dadosEmprestimos,
                theme: 'grid',
                headStyles: { fillColor: [41, 128, 185] },
                styles: { fontSize: 8 },
                columnStyles: {
                    0: { cellWidth: 20 },
                    1: { cellWidth: 50 },
                    2: { cellWidth: 40 },
                    3: { cellWidth: 25 },
                    4: { cellWidth: 25 }
                }
            });
            
            // Salvar
            const nomeArquivo = `relatorio_completo_livros_${new Date().toISOString().split('T')[0]}.pdf`;
            doc.save(nomeArquivo);
            
            Swal.fire({
                icon: 'success',
                title: 'Relatório Completo Gerado!',
                text: `Relatório salvo como "${nomeArquivo}"`,
                confirmButtonColor: '#ffbcfc'
            });
            
        } catch (error) {
            console.error('Erro ao gerar relatório completo:', error);
            Swal.fire({
                icon: 'error',
                title: 'Erro ao gerar relatório',
                text: 'Não foi possível gerar o relatório completo. Tente novamente.',
                confirmButtonColor: '#ffbcfc'
            });
        }
    }

    // Função para gerar relatório de livros mais emprestados
async function gerarRelatorioLivros() {
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Cabeçalho
        doc.setFontSize(20);
        doc.setTextColor(44, 62, 80);
        doc.text('ONG Biblioteca - Relatório de Livros Mais Emprestados', 105, 20, { align: 'center' });
        
        doc.setFontSize(12);
        doc.setTextColor(52, 73, 94);
        doc.text(`Gerado em: ${new Date().toLocaleDateString('pt-BR')} às ${new Date().toLocaleTimeString('pt-BR')}`, 105, 30, { align: 'center' });
        
        // Estatísticas
        const totalLivros = <?= count($livros_emprestados) ?>;
        const totalEmprestimos = <?= count($emprestimos_ativos) ?>;
        const totalEmprestimosHistoricos = <?= array_sum(array_column($livros_emprestados, 'total_emprestimos')) ?>;
        
        doc.setFontSize(14);
        doc.setTextColor(41, 128, 185);
        doc.text('Resumo Executivo', 20, 45);
        
        doc.setFontSize(10);
        doc.setTextColor(52, 73, 94);
        doc.text(`Total de Livros: ${totalLivros}`, 20, 55);
        doc.text(`Empréstimos Ativos: ${totalEmprestimos}`, 20, 62);
        doc.text(`Total de Empréstimos Históricos: ${totalEmprestimosHistoricos}`, 20, 69);
        
        // Dados dos livros
        const livros = <?= json_encode($livros_emprestados) ?>;
        const dados = livros.map((livro, index) => [
            index + 1,
            livro.Titulo,
            livro.Nome_Autor || 'N/A',
            livro.Nome_Genero || 'N/A',
            livro.Nome_Editora || 'N/A',
            livro.total_emprestimos,
            livro.estoque_atual,
            livro.Num_Prateleira || 'N/A'
        ]);
        
        doc.autoTable({
            startY: 80,
            head: [['Posição', 'Título', 'Autor', 'Gênero', 'Editora', 'Empréstimos', 'Estoque', 'Prateleira']],
            body: dados,
            theme: 'grid',
            headStyles: { fillColor: [41, 128, 185] },
            styles: { fontSize: 8 },
            columnStyles: {
                0: { cellWidth: 15 },
                1: { cellWidth: 40 },
                2: { cellWidth: 30 },
                3: { cellWidth: 20 },
                4: { cellWidth: 25 },
                5: { cellWidth: 20 },
                6: { cellWidth: 15 },
                7: { cellWidth: 15 }
            }
        });
        
        // Salvar
        const nomeArquivo = `relatorio_livros_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(nomeArquivo);
        
        Swal.fire({
            icon: 'success',
            title: 'Relatório de Livros Gerado!',
            text: `Relatório salvo como "${nomeArquivo}"`,
            confirmButtonColor: '#ffbcfc'
        });
        
    } catch (error) {
        console.error('Erro ao gerar relatório de livros:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro ao gerar relatório',
            text: 'Não foi possível gerar o relatório de livros. Tente novamente.',
            confirmButtonColor: '#ffbcfc'
        });
    }
}

// Função para gerar relatório de empréstimos ativos
async function gerarRelatorioEmprestimos() {
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Cabeçalho
        doc.setFontSize(20);
        doc.setTextColor(44, 62, 80);
        doc.text('ONG Biblioteca - Relatório de Empréstimos Ativos', 105, 20, { align: 'center' });
        
        doc.setFontSize(12);
        doc.setTextColor(52, 73, 94);
        doc.text(`Gerado em: ${new Date().toLocaleDateString('pt-BR')} às ${new Date().toLocaleTimeString('pt-BR')}`, 105, 30, { align: 'center' });
        
        // Dados dos empréstimos
        const emprestimos = <?= json_encode($emprestimos_ativos) ?>;
        const dados = emprestimos.map(emprestimo => [
            emprestimo.Cod_Emprestimo,
            emprestimo.titulo_livro,
            emprestimo.Nome_Autor || 'N/A',
            emprestimo.Nome_Genero || 'N/A',
            emprestimo.nome_cliente,
            new Date(emprestimo.Data_Emprestimo).toLocaleDateString('pt-BR'),
            new Date(emprestimo.Data_Devolucao).toLocaleDateString('pt-BR'),
            emprestimo.Status_Emprestimo
        ]);
        
        doc.autoTable({
            startY: 45,
            head: [['ID', 'Livro', 'Autor', 'Gênero', 'Cliente', 'Data Empréstimo', 'Data Devolução', 'Status']],
            body: dados,
            theme: 'grid',
            headStyles: { fillColor: [41, 128, 185] },
            styles: { fontSize: 8 },
            columnStyles: {
                0: { cellWidth: 15 },
                1: { cellWidth: 35 },
                2: { cellWidth: 25 },
                3: { cellWidth: 20 },
                4: { cellWidth: 25 },
                5: { cellWidth: 20 },
                6: { cellWidth: 20 },
                7: { cellWidth: 15 }
            }
        });
        
        // Salvar
        const nomeArquivo = `relatorio_emprestimos_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(nomeArquivo);
        
        Swal.fire({
            icon: 'success',
            title: 'Relatório de Empréstimos Gerado!',
            text: `Relatório salvo como "${nomeArquivo}"`,
            confirmButtonColor: '#ffbcfc'
        });
        
    } catch (error) {
        console.error('Erro ao gerar relatório de empréstimos:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro ao gerar relatório',
            text: 'Não foi possível gerar o relatório de empréstimos. Tente novamente.',
            confirmButtonColor: '#ffbcfc'
        });
    }
}

// Função para gerar relatório completo de livros
async function gerarRelatorioCompletoLivros() {
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Cabeçalho
        doc.setFontSize(20);
        doc.setTextColor(44, 62, 80);
        doc.text('ONG Biblioteca - Relatório Completo de Livros', 105, 20, { align: 'center' });
        
        doc.setFontSize(12);
        doc.setTextColor(52, 73, 94);
        doc.text(`Gerado em: ${new Date().toLocaleDateString('pt-BR')} às ${new Date().toLocaleTimeString('pt-BR')}`, 105, 30, { align: 'center' });
        
        // Estatísticas gerais
        const totalLivros = <?= count($livros_emprestados) ?>;
        const totalEmprestimos = <?= count($emprestimos_ativos) ?>;
        const totalEmprestimosHistoricos = <?= array_sum(array_column($livros_emprestados, 'total_emprestimos')) ?>;
        
        doc.setFontSize(14);
        doc.setTextColor(41, 128, 185);
        doc.text('Estatísticas Gerais', 20, 45);
        
        doc.setFontSize(10);
        doc.setTextColor(52, 73, 94);
        doc.text(`Total de Livros: ${totalLivros}`, 20, 55);
        doc.text(`Empréstimos Ativos: ${totalEmprestimos}`, 20, 62);
        doc.text(`Total de Empréstimos Históricos: ${totalEmprestimosHistoricos}`, 20, 69);
        
        // Ranking de livros
        doc.setFontSize(14);
        doc.setTextColor(41, 128, 185);
        doc.text('Ranking de Livros Mais Emprestados', 20, 85);
        
        const livros = <?= json_encode($livros_emprestados) ?>;
        const dadosLivros = livros.map((livro, index) => [
            index + 1,
            livro.Titulo,
            livro.Nome_Autor || 'N/A',
            livro.Nome_Genero || 'N/A',
            livro.total_emprestimos,
            livro.estoque_atual
        ]);
        
        doc.autoTable({
            startY: 95,
            head: [['Posição', 'Título', 'Autor', 'Gênero', 'Empréstimos', 'Estoque']],
            body: dadosLivros,
            theme: 'grid',
            headStyles: { fillColor: [41, 128, 185] },
            styles: { fontSize: 8 },
            columnStyles: {
                0: { cellWidth: 20 },
                1: { cellWidth: 50 },
                2: { cellWidth: 35 },
                3: { cellWidth: 25 },
                4: { cellWidth: 20 },
                5: { cellWidth: 15 }
            }
        });
        
        // Nova página para empréstimos ativos
        doc.addPage();
        doc.setFontSize(16);
        doc.setTextColor(41, 128, 185);
        doc.text('Empréstimos Ativos', 20, 20);
        
        const emprestimos = <?= json_encode($emprestimos_ativos) ?>;
        const dadosEmprestimos = emprestimos.map(emprestimo => [
            emprestimo.Cod_Emprestimo,
            emprestimo.titulo_livro,
            emprestimo.nome_cliente,
            new Date(emprestimo.Data_Emprestimo).toLocaleDateString('pt-BR'),
            new Date(emprestimo.Data_Devolucao).toLocaleDateString('pt-BR')
        ]);
        
        doc.autoTable({
            startY: 30,
            head: [['ID', 'Livro', 'Cliente', 'Data Empréstimo', 'Data Devolução']],
            body: dadosEmprestimos,
            theme: 'grid',
            headStyles: { fillColor: [41, 128, 185] },
            styles: { fontSize: 8 },
            columnStyles: {
                0: { cellWidth: 20 },
                1: { cellWidth: 50 },
                2: { cellWidth: 40 },
                3: { cellWidth: 25 },
                4: { cellWidth: 25 }
            }
        });
        
        // Salvar
        const nomeArquivo = `relatorio_completo_livros_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(nomeArquivo);
        
        Swal.fire({
            icon: 'success',
            title: 'Relatório Completo Gerado!',
            text: `Relatório salvo como "${nomeArquivo}"`,
            confirmButtonColor: '#ffbcfc'
        });
        
    } catch (error) {
        console.error('Erro ao gerar relatório completo:', error);
        Swal.fire({
            icon: 'error',
            title: 'Erro ao gerar relatório',
            text: 'Não foi possível gerar o relatório completo. Tente novamente.',
            confirmButtonColor: '#ffbcfc'
        });
    }
}
</script>


</body>
</html>
