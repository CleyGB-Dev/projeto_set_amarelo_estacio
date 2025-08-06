<?php
// processa_edit.php

$conn = new mysqli("localhost", "root", "", "setembro_amarelo");
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id'] ?? 0);
    $nome = $_POST['name'] ?? '';
    $nascimento = $_POST['birthdate'] ?? '';
    $email = $_POST['email'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $aluno_estacio = $_POST['alunos_estacio'] ?? '';
    $comorbidade = $_POST['comorbidade'] ?? '';
    $genero = $_POST['gender'] ?? '';
    $telefone = $_POST['telefone'] ?? '';  // corrigido para 'telefone'
    $indicacao = $_POST['indicacao'] ?? '';
    $nome_indicador = $_POST['nome_indicador'] ?? '';
    $matricula_indicador = $_POST['matricula_indicador'] ?? '';

    if ($id > 0 && $nome && $nascimento && $email && $aluno_estacio && $genero) {
        $stmt = $conn->prepare("UPDATE inscricoes 
            SET nome=?, nascimento=?, email=?, bairro=?, aluno_estacio=?, comorbidade=?, genero=?, telefone=?, indicacao=?, nome_indicador=?, matricula_indicador=? 
            WHERE id=?");

        $stmt->bind_param("sssssssssssi", 
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
            $matricula_indicador, 
            $id
        );

        if ($stmt->execute()) {
            $stmt->close();
            $conn->close();
            header("Location: dashboard.php?msg=edit_success");
            exit;
        } else {
            echo "Erro ao atualizar: " . $conn->error;
        }
    } else {
        echo "Preencha todos os campos obrigatórios!";
    }
} else {
    header("Location: dashboard.php");
    exit;
}
?>
