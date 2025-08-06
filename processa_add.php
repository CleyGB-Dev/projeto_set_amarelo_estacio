<?php
// processa_add.php

$conn = new mysqli("localhost", "root", "", "setembro_amarelo");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['name'] ?? '';
    $nascimento = $_POST['birthdate'] ?? '';
    $email = $_POST['email'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $aluno_estacio = $_POST['alunos_estacio'] ?? '';
    $comorbidade = $_POST['comorbidade'] ?? '';
    $genero = $_POST['gender'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $indicacao = $_POST['indicacao'] ?? '';
    $nome_indicador = $_POST['nome_indicador'] ?? '';
    $matricula_indicador = $_POST['matricula_indicador'] ?? '';

    // Validação simples
    if ($nome && $nascimento && $email && $aluno_estacio && $genero) {
        $stmt = $conn->prepare("INSERT INTO inscricoes 
            (nome, nascimento, email, bairro, aluno_estacio, comorbidade, genero, telefone, indicacao, nome_indicador, matricula_indicador)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        if (!$stmt) {
            die("Falha na preparação da consulta: " . $conn->error);
        }

        $stmt->bind_param("sssssssssss", 
            $nome, 
            $nascimento, 
            $email, 
            $bairro, 
            $aluno_estacio, 
            $comorbidade, 
            $genero, 
            $telefone, 
            $indicacao, 
            $nome_indicador, 
            $matricula_indicador
        );

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: dashboard.php?msg=add_success");
            exit;
        } else {
            echo "Erro ao salvar no banco: " . $stmt->error;
            $stmt->close();
            $conn->close();
            exit;
        }
    } else {
        echo "Preencha todos os campos obrigatórios!";
    }
} else {
    header("Location: add.php");
    exit;
}
?>
