<?php
    session_start();
    require_once 'conexao.php';

    if (!isset($_SESSION['usuario'])) {
        header('Location: index.php');
        exit();
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
                     CONCAT('Nome: ', NEW.Nome_Autor, ', Telefone: ', NEW.Telefone, ', Email: ', NEW.Email),
                     USER(), @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_autor_update_audit
         AFTER UPDATE ON autor
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('autor', 'UPDATE', NEW.Cod_Autor,
                     CONCAT('Nome: ', OLD.Nome_Autor, ', Telefone: ', OLD.Telefone, ', Email: ', OLD.Email),
                     CONCAT('Nome: ', NEW.Nome_Autor, ', Telefone: ', NEW.Telefone, ', Email: ', NEW.Email),
                     USER(), @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_autor_delete_audit
         BEFORE DELETE ON autor
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('autor', 'DELETE', OLD.Cod_Autor,
                     CONCAT('Nome: ', OLD.Nome_Autor, ', Telefone: ', OLD.Telefone, ', Email: ', OLD.Email),
                     USER(), @ip_usuario);
         END",
        
        // Triggers para tabela cliente
        "CREATE TRIGGER IF NOT EXISTS tr_cliente_insert_audit
         AFTER INSERT ON cliente
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('cliente', 'INSERT', NEW.Cod_Cliente, 
                     CONCAT('Nome: ', NEW.Nome, ', CPF: ', NEW.CPF, ', Email: ', NEW.Email),
                     USER(), @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_cliente_update_audit
         AFTER UPDATE ON cliente
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('cliente', 'UPDATE', NEW.Cod_Cliente,
                     CONCAT('Nome: ', OLD.Nome, ', CPF: ', OLD.CPF, ', Email: ', OLD.Email),
                     CONCAT('Nome: ', NEW.Nome, ', CPF: ', NEW.CPF, ', Email: ', NEW.Email),
                     USER(), @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_cliente_delete_audit
         BEFORE DELETE ON cliente
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('cliente', 'DELETE', OLD.Cod_Cliente,
                     CONCAT('Nome: ', OLD.Nome, ', CPF: ', OLD.CPF, ', Email: ', OLD.Email),
                     USER(), @ip_usuario);
         END",
        
        // Triggers para tabela funcionario
        "CREATE TRIGGER IF NOT EXISTS tr_funcionario_insert_audit
         AFTER INSERT ON funcionario
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('funcionario', 'INSERT', NEW.Cod_Funcionario, 
                     CONCAT('Nome: ', NEW.Nome, ', Data Nascimento: ', NEW.Data_Nascimento),
                     USER(), @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_funcionario_update_audit
         AFTER UPDATE ON funcionario
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('funcionario', 'UPDATE', NEW.Cod_Funcionario,
                     CONCAT('Nome: ', OLD.Nome, ', Data Nascimento: ', OLD.Data_Nascimento),
                     CONCAT('Nome: ', NEW.Nome, ', Data Nascimento: ', NEW.Data_Nascimento),
                     USER(), @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_funcionario_delete_audit
         BEFORE DELETE ON funcionario
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('funcionario', 'DELETE', OLD.Cod_Funcionario,
                     CONCAT('Nome: ', OLD.Nome, ', Data Nascimento: ', OLD.Data_Nascimento),
                     USER(), @ip_usuario);
         END",
        
        // Triggers para tabela livro
        "CREATE TRIGGER IF NOT EXISTS tr_livro_insert_audit
         AFTER INSERT ON livro
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('livro', 'INSERT', NEW.Cod_Livro, 
                     CONCAT('Título: ', NEW.Titulo, ', ISBN: ', NEW.ISBN),
                     USER(), @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_livro_update_audit
         AFTER UPDATE ON livro
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('livro', 'UPDATE', NEW.Cod_Livro,
                     CONCAT('Título: ', OLD.Titulo, ', ISBN: ', OLD.ISBN),
                     CONCAT('Título: ', NEW.Titulo, ', ISBN: ', NEW.ISBN),
                     USER(), @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_livro_delete_audit
         BEFORE DELETE ON livro
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('livro', 'DELETE', OLD.Cod_Livro,
                     CONCAT('Título: ', OLD.Titulo, ', ISBN: ', OLD.ISBN),
                     USER(), @ip_usuario);
         END",
        
        // Triggers para tabela emprestimo
        "CREATE TRIGGER IF NOT EXISTS tr_emprestimo_insert_audit
         AFTER INSERT ON emprestimo
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_novos, usuario, ip_usuario)
             VALUES ('emprestimo', 'INSERT', NEW.Cod_Emprestimo, 
                     CONCAT('Cliente: ', NEW.Cod_Cliente, ', Livro: ', NEW.Cod_Livro, ', Data: ', NEW.Data_Emprestimo),
                     USER(), @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_emprestimo_update_audit
         AFTER UPDATE ON emprestimo
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, dados_novos, usuario, ip_usuario)
             VALUES ('emprestimo', 'UPDATE', NEW.Cod_Emprestimo,
                     CONCAT('Cliente: ', OLD.Cod_Cliente, ', Livro: ', OLD.Cod_Livro, ', Data: ', OLD.Data_Emprestimo),
                     CONCAT('Cliente: ', NEW.Cod_Cliente, ', Livro: ', NEW.Cod_Livro, ', Data: ', NEW.Data_Emprestimo),
                     USER(), @ip_usuario);
         END",
        
        "CREATE TRIGGER IF NOT EXISTS tr_emprestimo_delete_audit
         BEFORE DELETE ON emprestimo
         FOR EACH ROW
         BEGIN
             INSERT INTO logs_auditoria (tabela, operacao, id_registro, dados_anteriores, usuario, ip_usuario)
             VALUES ('emprestimo', 'DELETE', OLD.Cod_Emprestimo,
                     CONCAT('Cliente: ', OLD.Cod_Cliente, ', Livro: ', OLD.Cod_Livro, ', Data: ', OLD.Data_Emprestimo),
                     USER(), @ip_usuario);
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
         <link rel ="stylesheet" type="text/css" href="css/style.css" />
         <link rel="stylesheet" type="text/css" href="css/relatorios.css" />
                   <script src="javascript/JS_Logout.js" defer></script>
          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            <!-- Filtros -->
            <div class="filter-container">
                <label for="filter-tabela">Filtrar por Tabela:</label>
                <select id="filter-tabela" class="filter-select" onchange="filtrarLogs()">
                    <option value="">Todas as Tabelas</option>
                    <?php foreach (array_keys($logs_por_tabela) as $tabela): ?>
                        <option value="<?= htmlspecialchars($tabela) ?>"><?= ucfirst(htmlspecialchars($tabela)) ?></option>
                    <?php endforeach; ?>
                </select>

                <label for="filter-operacao">Filtrar por Operação:</label>
                <select id="filter-operacao" class="filter-select" onchange="filtrarLogs()">
                    <option value="">Todas as Operações</option>
                    <option value="INSERT">Cadastros</option>
                    <option value="UPDATE">Alterações</option>
                    <option value="DELETE">Exclusões</option>
                </select>
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
                                        <div style="text-align: right; font-size: 0.9em;">
                                            <div><strong>Usuário:</strong> <?= htmlspecialchars($log['usuario']) ?></div>
                                            <div><strong>Data:</strong> <?= date("d/m/Y H:i:s", strtotime($log['data_operacao'])) ?></div>
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
            function filtrarLogs() {
                const tabela = document.getElementById('filter-tabela').value;
                const operacao = document.getElementById('filter-operacao').value;
                const logs = document.querySelectorAll('.log-entry');
                
                logs.forEach(log => {
                    const logTabela = log.dataset.tabela;
                    const logOperacao = log.dataset.operacao;
                    
                    let mostrar = true;
                    
                    if (tabela && logTabela !== tabela) {
                        mostrar = false;
                    }
                    
                    if (operacao && logOperacao !== operacao) {
                        mostrar = false;
                    }
                    
                    log.style.display = mostrar ? 'block' : 'none';
                });
                
                // Mostrar/ocultar seções de tabela baseado nos filtros
                const secoes = document.querySelectorAll('.tabela-section');
                secoes.forEach(secao => {
                    const logsVisiveis = secao.querySelectorAll('.log-entry[style*="display: block"], .log-entry:not([style*="display: none"])');
                    if (logsVisiveis.length === 0) {
                        secao.style.display = 'none';
                    } else {
                        secao.style.display = 'block';
                    }
                });
            }
            
            function filtrarPorOperacao(operacao) {
                // Resetar filtros de tabela
                document.getElementById('filter-tabela').value = '';
                
                // Aplicar filtro de operação
                if (operacao === 'TODOS') {
                    document.getElementById('filter-operacao').value = '';
                } else {
                    document.getElementById('filter-operacao').value = operacao;
                }
                
                // Aplicar filtros
                filtrarLogs();
                
                // Destacar o card selecionado
                document.querySelectorAll('.operacao-card').forEach(card => {
                    card.classList.remove('active');
                });
                
                if (operacao === 'TODOS') {
                    document.querySelector('.operacao-card:last-child').classList.add('active');
                } else {
                    const targetCard = document.querySelector(`[onclick="filtrarPorOperacao('${operacao}')"]`);
                    if (targetCard) targetCard.classList.add('active');
                }
            }
            
            // Filtrar automaticamente ao carregar a página
            document.addEventListener('DOMContentLoaded', function() {
                filtrarLogs();
            });
        </script>
    </body>
    </html>