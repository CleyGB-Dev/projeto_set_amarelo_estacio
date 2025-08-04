<?php
if (!empty($_POST['hpfield'])) {
    die("Bot detectado. Ação bloqueada.");
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Conexão com o banco de dados
$conn = new mysqli("localhost", "root", "", "setembro_amarelo");

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Verifica se os dados chegaram via POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar os dados do formulário com segurança
    $nome = $_POST['name'] ?? '';
    $nascimento = $_POST['birthdate'] ?? '';
    $email = $_POST['email'] ?? '';
    $bairro = $_POST['bairro'] ?? '';
    $aluno_estacio = $_POST['alunos_estacio'] ?? '';
    $comorbidade = $_POST['comorbidade'] ?? '';
    $genero = $_POST['gender'] ?? '';
    $telefone = $_POST['phone'] ?? '';
    $indicacao = $_POST['indicacao'] ?? '';

    // Validação básica (opcional)
    if ($nome && $email && $nascimento) {
        // Preparar e executar a inserção
        $stmt = $conn->prepare("INSERT INTO inscricoes (nome, nascimento, email, bairro, aluno_estacio, comorbidade, genero, telefone, indicacao) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssss", $nome, $nascimento, $email, $bairro, $aluno_estacio, $comorbidade, $genero, $telefone, $indicacao);

        // Depois de salvar no banco:
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
