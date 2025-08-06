<?php
if (!empty($_POST['hpfield'])) {
    die("Bot detectado. Ação bloqueada.");
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    $telefone = $_POST['phone'] ?? '';
    $indicacao = $_POST['indicacao'] ?? '';
    $nome_indicador = $_POST['nome_indicador'] ?? '';
    $matricula_indicador = $_POST['matricula_indicador'] ?? '';

    // Validação simples
    if ($nome && $email && $nascimento) {
        $stmt = $conn->prepare("INSERT INTO inscricoes 
            (nome, nascimento, email, bairro, aluno_estacio, comorbidade, genero, telefone, indicacao, nome_indicador, matricula_indicador) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param(
            "sssssssssss",
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
            header("Location: confirmacao.html");
            exit;
        } else {
            header("Location: erro.html");
            exit;
        }

        $stmt->close();
    } else {
        echo "Dados inválidos!";
    }
}

$conn->close();
?>
