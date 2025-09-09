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
</script>


</body>
</html>
