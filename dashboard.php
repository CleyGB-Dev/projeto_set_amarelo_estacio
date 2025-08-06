<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "setembro_amarelo");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Deletar participante
if (isset($_GET['delete_id'])) {
    $id = intval($_GET['delete_id']);
    $stmt = $conn->prepare("DELETE FROM inscricoes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: dashboard.php");
    exit;
}

$result = $conn->query("SELECT * FROM inscricoes ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Dashboard - Participantes</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            padding: 20px;
            background: rgb(238, 231, 137);
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        .logout-btn {
            position: absolute;
            top: 0;
            right: 0;
            background: #ef4444;
            color: white;
            padding: 8px 15px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background: #b91c1c;
        }

        .btn-container {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 25px;
            flex-wrap: wrap;
        }

        a.button {
            text-decoration: none;
            padding: 10px 20px;
            background: #000;
            color: #fff;
            border-radius: 6px;
            font-weight: 600;
            transition: background 0.3s;
        }

        a.button:hover {
            background: #444;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: auto;
            font-size: 0.95rem;
            box-shadow: 0 4px 10px rgba(255, 255, 255, 0.1);
            background-color: white;
            table-layout: fixed; /* largura fixa para colunas */
        }

        th, td {
            padding: 10px 8px;
            border: 1px solid #ddd;
            text-align: left;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap; /* evita quebra de linha */
        }

        th {
            background-color: #d4af37;
            color: #000;
            font-size: 1rem;
        }

        /* Define larguras para algumas colunas específicas */
        th:nth-child(1), td:nth-child(1) { width: 40px; }    /* # */
        th:nth-child(2), td:nth-child(2) { width: 180px; }   /* Nome */
        th:nth-child(3), td:nth-child(3) { width: 100px; }   /* Nascimento */
        th:nth-child(4), td:nth-child(4) { width: 250px; }   /* Email */
        th:nth-child(5), td:nth-child(5) { width: 120px; }   /* Telefone */
        th:nth-child(6), td:nth-child(6) { width: 120px; }   /* Bairro */
        th:nth-child(7), td:nth-child(7) { width: 120px; }    /* Aluno Estácio */
        th:nth-child(8), td:nth-child(8) { width: 80px; }    /* Indicação */
        th:nth-child(9), td:nth-child(9) { width: 150px; }   /* Nome Indicador */
        th:nth-child(10), td:nth-child(10) { width: 120px; } /* Matrícula Indicador */
        th:nth-child(11), td:nth-child(11) { width: 140px; } /* Comorbidade */
        th:nth-child(12), td:nth-child(12) { width: 80px; }  /* Gênero */
        th:nth-child(13), td:nth-child(13) { width: 150px; } /* Ações */

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .actions a {
            padding: 6px 12px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.85rem;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.3s ease;
            color: #fff;
            margin-right: 6px;
            user-select: none;
        }

        .actions a.btn-edit {
            background-color: #6366f1;
        }

        .actions a.btn-edit:hover {
            background-color: #4f46e5;
        }

        .actions a.btn-delete {
            background-color: #ef4444;
        }

        .actions a.btn-delete:hover {
            background-color: #b91c1c;
        }

        @media (max-width: 768px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            tr {
                margin-bottom: 15px;
                border: 1px solid #ccc;
                padding: 10px;
            }

            td {
                padding: 8px 5px;
                position: relative;
                padding-left: 50%;
                text-align: left;
                white-space: normal; /* permite quebra de linha no mobile */
                word-break: break-word;
                overflow: visible; /* mostrar texto completo */
            }

            td::before {
                position: absolute;
                top: 8px;
                left: 10px;
                width: 45%;
                padding-right: 10px;
                white-space: nowrap;
                font-weight: 700;
                color: #555;
            }

            td:nth-of-type(1)::before { content: "#"; }
            td:nth-of-type(2)::before { content: "Nome"; }
            td:nth-of-type(3)::before { content: "Nascimento"; }
            td:nth-of-type(4)::before { content: "Email"; }
            td:nth-of-type(5)::before { content: "Telefone"; }
            td:nth-of-type(6)::before { content: "Bairro"; }
            td:nth-of-type(7)::before { content: "Aluno Estácio"; }
            td:nth-of-type(8)::before { content: "Indicação"; }
            td:nth-of-type(9)::before { content: "Nome Indicador"; }
            td:nth-of-type(10)::before { content: "Matrícula Indicador"; }
            td:nth-of-type(11)::before { content: "Comorbidade"; }
            td:nth-of-type(12)::before { content: "Gênero"; }
            td:nth-of-type(13)::before { content: "Ações"; }

            .actions a {
                margin-top: 5px;
                margin-right: 0;
            }
        }

        @media print {
            .btn-container, .actions, .logout-btn {
                display: none !important;
            }

            table {
                font-size: 11pt;
                page-break-inside: avoid;
            }

            table th {
                background-color: #eee !important;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>
<body>

<h1>
    Dashboard de Participantes
    <a href="logout.php" class="logout-btn" title="Sair do sistema">Sair</a>
</h1>

<div class="btn-container">
    <a href="gera_pdf.php" class="button" target="_blank">🖨️ Imprimir Lista</a>
    <a href="add.php" class="button">➕ Adicionar Participante</a>
</div>

<table>
    <thead>
        <tr>
            <th>#</th> <!-- Número sequencial -->
            <th>Nome</th>
            <th>Nascimento</th>
            <th>Email</th>
            <th>Telefone</th>
            <th>Bairro</th>
            <th>Aluno Estácio</th>
            <th>Indicação</th>
            <th>Nome Indicador</th>
            <th>Matrícula Indicador</th>
            <th>Comorbidade</th>
            <th>Gênero</th>
            <th class="actions">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php $contador = 1; ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $contador ?></td>
                <td><?= htmlspecialchars($row['nome']) ?></td>
                <td><?= htmlspecialchars($row['nascimento']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['telefone']) ?></td>
                <td><?= htmlspecialchars($row['bairro']) ?></td>
                <td><?= htmlspecialchars($row['aluno_estacio']) ?></td>
                <td><?= htmlspecialchars($row['indicacao']) ?></td>
                <td><?= htmlspecialchars($row['nome_indicador']) ?></td>
                <td><?= htmlspecialchars($row['matricula_indicador']) ?></td>
                <td><?= htmlspecialchars($row['comorbidade']) ?></td>
                <td><?= htmlspecialchars($row['genero']) ?></td>
                <td class="actions">
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit" title="Editar participante">Editar</a>
                    <a href="dashboard.php?delete_id=<?= $row['id'] ?>" class="btn-delete" title="Excluir participante" onclick="return confirm('Excluir participante?');">Excluir</a>
                </td>
            </tr>
            <?php $contador++; ?>
        <?php endwhile; ?>
    </tbody>
</table>

<?php $conn->close(); ?>

</body>
</html>
