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
?>


<!DOCTYPE html>
<html lang="pt-br">
    <head> 
         <meta charset="UTF-8">
         <title> ONG Bilbioteca - Gestor </title>
         <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/relatorios.css" />
        <script src="javascript/JS_Logout.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body> 
        <header> 
            <h1> Bem-Vindo, <?php echo $_SESSION['usuario']?>! </h1>
            <form action="logout.php" method="POST">
                <button type="submit" class="logout">🚶🏻‍♂️ Logout</a>
            </form>
        </header>

        <!-- Seção de Relatórios em Destaque -->
    <div class="relatorios-section">
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
                <p><strong>Triggers criados:</strong> ✅ autor, cliente, funcionario, livro, emprestimo</p>
                <p><strong>Próximos passos:</strong> Realize algumas operações (cadastros, alterações, exclusões) e elas aparecerão aqui automaticamente.</p>
            </div>
        <?php else: ?>
            <!-- Logs agrupados por tabela -->
            <?php foreach ($logs_por_tabela as $tabela => $logs_tabela): ?>
                <div class="tabela-section">
                    <h2 class="tabela-title">📋 <?= ucfirst(htmlspecialchars($tabela)) ?></h2>
                    <div class="tabela-content">
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
        <?php endif; ?>
    </div>

        <ul class="nav-bar">
            <li><a href="#" class="dropbtn"> Início </a></li>
            <li class="dropdown">
                <a href="javascript:void(0)" class="dropbtn"> Funcionários </a>
                <div class="dropdown-content">
                    <a href="subtelas/cadastro_funcionario.php"> Registrar Funcionário </a>
                    <a href="subtelas/telconsultar_funcionario.php"> Consultar Funcionários </a>
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

        document.querySelectorAll('.log-entry').forEach(entry => {
            if (entry.offsetParent !== null) {  // Verifica se o elemento está visível
                const operacao = entry.dataset.operacao;
                if (operacao === "INSERT") qtdInsert++;
                if (operacao === "UPDATE") qtdUpdate++;
                if (operacao === "DELETE") qtdDelete++;
            }
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
</script>
    </body>
    </html>
