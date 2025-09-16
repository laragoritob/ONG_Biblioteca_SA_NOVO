<?php
    // Seeder de empréstimos: insere 73 registros nos últimos 6 meses
    // Acesse via navegador: http://localhost/ONG_Biblioteca_SA_NOVO/seed_emprestimos.php

    header('Content-Type: text/plain; charset=utf-8');

    require_once __DIR__ . '/conexao.php';

    function fetchIds(PDO $pdo, string $sql, string $column): array {
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $ids = [];
        foreach ($rows as $row) {
            if (isset($row[$column])) {
                $ids[] = (int)$row[$column];
            }
        }
        return $ids;
    }

    try {
        // Coleta clientes e livros ativos
        $clienteIds = fetchIds($pdo, "SELECT Cod_Cliente FROM cliente WHERE status = 'ativo' ORDER BY Cod_Cliente", 'Cod_Cliente');
        $livroIds   = fetchIds($pdo,   "SELECT Cod_Livro   FROM livro   WHERE status = 'ativo' ORDER BY Cod_Livro",   'Cod_Livro');

        if (count($clienteIds) === 0) {
            throw new RuntimeException('Nenhum cliente ativo encontrado.');
        }
        if (count($livroIds) === 0) {
            throw new RuntimeException('Nenhum livro ativo encontrado.');
        }

        // Embaralha para distribuir melhor
        shuffle($clienteIds);
        shuffle($livroIds);

        $totalRegistros = 73;

        $pdo->beginTransaction();

        $sql = "INSERT INTO emprestimo (
                    Cod_Cliente,
                    Cod_Livro,
                    Data_Emprestimo,
                    Data_Devolucao,
                    Data_Ultima_Renovacao,
                    Status_Emprestimo
                ) VALUES (?,?,?,?,NULL,?)";
        $stmt = $pdo->prepare($sql);

        $hoje = new DateTime('today');
        $inseridos = 0;

        for ($n = 0; $n < $totalRegistros; $n++) {
            $clienteId = $clienteIds[$n % count($clienteIds)];
            $livroId   = $livroIds[$n % count($livroIds)];

            // Espaça datas dentro de ~180 dias (6 meses)
            $diasAtras = (($n * 2) + ($n % 5)) % 180; // 0..179
            $dataEmprestimo = (clone $hoje)->modify("-{$diasAtras} days");
            $dataDevolucao  = (clone $dataEmprestimo)->modify('+7 days');

            // Alterna status (aprox 1/3 devolvido)
            $status = ($n % 3 === 0) ? 'Devolvido' : 'Pendente';

            $ok = $stmt->execute([
                $clienteId,
                $livroId,
                $dataEmprestimo->format('Y-m-d'),
                $dataDevolucao->format('Y-m-d'),
                $status
            ]);

            if (!$ok) {
                throw new RuntimeException('Falha ao inserir empréstimo na iteração #' . ($n + 1));
            }

            $inseridos++;
        }

        $pdo->commit();

        echo "Seeder de empréstimos concluído com sucesso.\n";
        echo "Registros inseridos: {$inseridos}\n";
        echo "Clientes utilizados: " . count($clienteIds) . "\n";
        echo "Livros utilizados: " . count($livroIds) . "\n";
    } catch (Throwable $e) {
        if ($pdo && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        http_response_code(500);
        echo "Erro no seeder: " . $e->getMessage();
    }
?>


