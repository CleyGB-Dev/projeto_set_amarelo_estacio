<?php
session_start();

// Protege a página, redireciona para login se não estiver logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Conexão com o banco
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

// Buscar todos os participantes ordenados por id (mais antigos primeiro)
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
            background:rgb(238, 231, 137);
            color: #333;
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            position: relative;
        }

        /* Botão logout no topo direito */
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
        }

        th, td {
            padding: 12px 10px;
            border: 1px solid #ddd;
            text-align: left;
            word-break: break-word;
        }

        th {
            background-color: #d4af37;
            color: #000;
            font-size: 1rem;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Botões dentro da tabela - menor, cores diferentes */
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
            background-color: #6366f1; /* azul */
        }

        .actions a.btn-edit:hover {
            background-color: #4f46e5;
        }

        .actions a.btn-delete {
            background-color: #ef4444; /* vermelho */
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

            th {
                background-color: #facc00;
                color: #000;
            }

            td {
                padding: 8px 5px;
                position: relative;
                padding-left: 50%;
                text-align: left;
                white-space: normal;
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

            td:nth-of-type(1)::before { content: "Nome"; }
            td:nth-of-type(2)::before { content: "Nascimento"; }
            td:nth-of-type(3)::before { content: "Email"; }
            td:nth-of-type(4)::before { content: "Bairro"; }
            td:nth-of-type(5)::before { content: "Aluno Estácio"; }
            td:nth-of-type(6)::before { content: "Comorbidade"; }
            td:nth-of-type(7)::before { content: "Gênero"; }
            td:nth-of-type(8)::before { content: "Ações"; }

            .actions a {
                margin-top: 5px;
                margin-right: 0;
            }
        }

        @media print {
            body {
                background: none;
                color: #000;
            }

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
        <a href="gera_pdf.php" class="button" target="_blank">📄 Gerar PDF</a>
        <a href="add.php" class="button">➕ Adicionar Participante</a>
        <a href="#" class="button" onclick="window.print()">🖨️ Imprimir Lista</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>Nascimento</th>
                <th>Email</th>
                <th>Bairro</th>
                <th>Aluno Estácio</th>
                <th>Comorbidade</th>
                <th>Gênero</th>
                <th class="actions">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['nome']) ?></td>
                    <td><?= htmlspecialchars($row['nascimento']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['bairro']) ?></td>
                    <td><?= htmlspecialchars($row['aluno_estacio']) ?></td>
                    <td><?= htmlspecialchars($row['comorbidade']) ?></td>
                    <td><?= htmlspecialchars($row['genero']) ?></td>
                    <td class="actions">
                        <a href="edit.php?id=<?= $row['id'] ?>" class="btn-edit" title="Editar participante">Editar</a>
                        <a href="dashboard.php?delete_id=<?= $row['id'] ?>" class="btn-delete" title="Excluir participante" onclick="return confirm('Excluir participante?');">Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

</body>
</html>

<?php $conn->close(); ?>
