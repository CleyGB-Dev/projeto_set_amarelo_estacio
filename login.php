<?php
session_start();

if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conn = new mysqli("localhost", "root", "", "setembro_amarelo");
    if ($conn->connect_error) {
        die("Erro: " . $conn->connect_error);
    }

    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id, $hashed_password);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Login OK
            $_SESSION['user_id'] = $id;
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Usuário ou senha inválidos.";
        }
    } else {
        $error = "Usuário ou senha inválidos.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <title>Login - Gestão Participantes</title>
    
    <section id="titulo_page">
    <h1>Sistema de Gestão de Participantes</h1><br>
</selection>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background:rgb(238, 231, 137);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        #titulo_page {
            text-align: center;
            margin-bottom: 0px;
            color:rgb(82, 82, 82);
        }

        .login-box {
            background: white;
            padding: 30px 40px;
            border-radius: 8px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            width: 400px;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #d4af37;
        }
        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 1rem;
        }
        button {
            width: 100%;
            padding: 10px 0;
            background: #000;
            color: white;
            border: none;
            border-radius: 6px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            transition: background 0.3s ease;
        }
        button:hover {
            background: #444;
        }
        .error {
            color: #ef4444;
            margin-bottom: 15px;
            text-align: center;
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="login-box">
    <h2>Login</h2>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" action="login.php">
        <input type="text" name="username" placeholder="Usuário" required autofocus>
        <input type="password" name="password" placeholder="Senha" required>
        <button type="submit">Entrar</button>
    </form>
</div>

</body>
</html>
