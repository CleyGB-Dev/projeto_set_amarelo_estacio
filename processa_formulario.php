<?php
// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "setembro_amarelo");

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Coletar os dados do formulário
$nome = $_POST['name'];
$nascimento = $_POST['birthdate'];
$email = $_POST['email'];
$bairro = $_POST['bairro'];
$aluno_estacio = $_POST['alunos_estacio'];
$comorbidade = $_POST['comorbidade'];
$genero = $_POST['gender'];

// Preparar e executar a inserção
$stmt = $conn->prepare("INSERT INTO inscricoes (nome, nascimento, email, bairro, aluno_estacio, comorbidade, genero) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $nome, $nascimento, $email, $bairro, $aluno_estacio, $comorbidade, $genero);

if ($stmt->execute()) {
    echo "Inscrição feita com sucesso!";
} else {
    echo "Erro: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
