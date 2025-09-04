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
         <title> ONG Bilbioteca - Bibliotecário </title>
         <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/relatorios.css" />
        <script src="javascript/JS_Logout.js" defer></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
         
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

            <!-- Logs agrupados por tabela (apenas as permitidas para bibliotecario) -->
            <?php 
            // Filtrar apenas as tabelas que o bibliotecario pode ver
            $tabelas_permitidas_bibliotecario = ['cliente', 'livro', 'emprestimo', 'doador'];
            $logs_filtrados = array_intersect_key($logs_por_tabela, array_flip($tabelas_permitidas_bibliotecario));
            ?>
            
            <?php foreach ($logs_filtrados as $tabela => $logs_tabela): ?>
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
        <?php endif; ?>
        
        <!-- Botões de ação -->
        <a href="#" class="btn-voltar" onclick="voltarAoTopo()"> Voltar para o topo ↑ </a>
        <a href="#" class="btn-pdf" onclick="baixarPDF()"> 📄 Baixar PDF </a>
    </div>

    <ul class="nav-bar">
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

    // Função para baixar a página como PDF
    function baixarPDF() {
        // Usar html2pdf.js para gerar o PDF
        const element = document.body;
        const opt = {
            margin: 1,
            filename: 'relatorio_bibliotecario.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        // Verificar se html2pdf está disponível
        if (typeof html2pdf !== 'undefined') {
            html2pdf().set(opt).from(element).save();
        } else {
            // Fallback: abrir em nova janela para impressão
            window.print();
        }
    }

    // ===== FUNÇÕES DE TOGGLE PARA CATEGORIAS =====
    
    // Função para expandir/colapsar todas as tabelas
    function expandirTodas() {
        document.querySelectorAll('.tabela-section').forEach(secao => {
            secao.style.display = 'block';
            secao.classList.remove('colapsada');
            secao.querySelector('.toggle-icon').textContent = '▼';
            secao.querySelector('.tabela-content').style.display = 'block';
        });
        
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
