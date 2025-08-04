<?php
// processa_add.php

$conn = new mysqli("localhost", "root", "", "setembro_amarelo");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['name'] ?? '';
    $nascimento = $_POST['birthdate'] ?? '';
    $email = $_POST['email'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $aluno_estacio = $_POST['alunos_estacio'] ?? '';
    $comorbidade = $_POST['comorbidade'] ?? '';
    $genero = $_POST['gender'] ?? '';

    // Validação simples
    if ($nome && $nascimento && $email && $aluno_estacio && $genero) {
        $stmt = $conn->prepare("INSERT INTO inscricoes (nome, nascimento, email, bairro, aluno_estacio, comorbidade, genero) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nome, $nascimento, $email, $bairro, $aluno_estacio, $comorbidade, $genero);
        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: dashboard.php?msg=add_success");
            exit;
        } else {
            $stmt->close();
            $conn->close();
            echo "Erro ao salvar no banco: " . $conn->error;
            exit;
        }
    } else {
        echo "Preencha todos os campos obrigatórios!";
    }
} else {
    header("Location: add.php");
    exit;
}
