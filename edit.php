<?php
// edit.php

$conn = new mysqli("localhost", "root", "", "setembro_amarelo");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    header("Location: dashboard.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM inscricoes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$participante = $result->fetch_assoc();
$stmt->close();

if (!$participante) {
    echo "Participante não encontrado.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8" />
<title>Editar Participante</title>
<style>
    /* Mesma estilização do add.php */
    body {
        font-family: 'Poppins', sans-serif;
        background: #fff6a3;
        padding: 30px 20px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        min-height: 100vh;
        margin: 0;
    }
    .form-container {
        background-color: #fff;
        border-radius: 12px;
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        padding: 30px 40px;
        max-width: 500px;
        width: 100%;
    }
    h2 {
        color: #1f1f1f;
        text-align: center;
        margin-bottom: 25px;
        font-weight: 600;
    }
    form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    label {
        font-weight: 500;
        color: #333;
    }
    input[type="text"],
    input[type="email"],
    input[type="date"],
    select {
        padding: 10px 12px;
        border: 1px solid #ccc;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.3s ease;
        width: 100%;
        box-sizing: border-box;
    }
    input[type="text"]:focus,
    input[type="email"]:focus,
    input[type="date"]:focus,
    select:focus {
        outline: none;
        border-color: #6366f1;
        box-shadow: 0 0 5px rgba(99, 102, 241, 0.5);
    }
    button {
        background-color: #000;
        color: #fff;
        font-weight: 600;
        font-size: 1.1rem;
        padding: 12px;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    button:hover {
        background-color: #555;
    }
    a.back-link {
        display: inline-block;
        margin-top: 15px;
        color: #000;
        text-decoration: none;
        font-weight: 500;
    }
    a.back-link:hover {
        text-decoration: underline;
    }
    @media (max-width: 600px) {
        .form-container {
            padding: 20px;
            margin: 10px;
        }
    }
</style>
</head>
<body>

<div class="form-container">
    <h2>Editar Participante</h2>
    <form action="processa_edit.php" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($participante['id']) ?>">

        <label for="name">Nome:</label>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($participante['nome']) ?>" required>

        <label for="birthdate">Data de Nascimento:</label>
        <input type="date" id="birthdate" name="birthdate" value="<?= htmlspecialchars($participante['nascimento']) ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($participante['email']) ?>" required>

        <label for="bairro">Bairro:</label>
        <input type="text" id="bairro" name="bairro" value="<?= htmlspecialchars($participante['bairro']) ?>">

        <label for="alunos_estacio">Aluno Estácio:</label>
        <select id="alunos_estacio" name="alunos_estacio" required>
            <option value="">Selecione</option>
            <option value="Sim" <?= ($participante['aluno_estacio'] === 'Sim') ? 'selected' : '' ?>>Sim</option>
            <option value="Não" <?= ($participante['aluno_estacio'] === 'Não') ? 'selected' : '' ?>>Não</option>
        </select>

        <label for="comorbidade">Comorbidade:</label>
        <input type="text" id="comorbidade" name="comorbidade" value="<?= htmlspecialchars($participante['comorbidade']) ?>">

        <label for="gender">Gênero:</label>
        <select id="gender" name="gender" required>
            <option value="">Selecione</option>
            <option value="Masculino" <?= ($participante['genero'] === 'Masculino') ? 'selected' : '' ?>>Masculino</option>
            <option value="Feminino" <?= ($participante['genero'] === 'Feminino') ? 'selected' : '' ?>>Feminino</option>
            <option value="Outro" <?= ($participante['genero'] === 'Outro') ? 'selected' : '' ?>>Outro</option>
        </select>

        <button type="submit">Atualizar</button>
    </form>
    <a href="dashboard.php" class="back-link">&larr; Voltar ao Dashboard</a>
</div>

</body>
</html>
