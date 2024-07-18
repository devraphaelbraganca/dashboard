<?php
session_start();

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Verifica as credenciais
    if ($username == 'admin' && $password == 'ipchronus@123') {
        $_SESSION['loggedin'] = true;
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Usuário ou senha inválidos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login - IpChronus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0A2540;
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            position: relative; /* Adicionado para o posicionamento da logo */
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            color: #333;
        }
        .login-container h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
        }
        .login-container label {
            margin-bottom: 10px;
        }
        .login-container input {
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .login-container button {
            padding: 10px;
            background-color: #0A2540;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .login-container .error {
            color: red;
            text-align: center;
            margin-bottom: 20px;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 150px; /* Ajuste a largura conforme necessário */
            height: auto;
        }
        .footer {
            background-color: #0A2540;
            color: #ccc; /* Texto cinza para o rodapé */
            padding: 15px;
            text-align: center;
            margin-top: auto; /* Coloca o rodapé no final da página */
            font-size: 14px; /* Tamanho da fonte reduzido */
        }
        .footer p {
            margin: 0;
        }
        .footer hr {
            border: 0;
            height: 1px;
            background-color: #ccc; /* Linha separadora cinza */
            margin: 5px 0;
        }
        
    </style>
</head>
<body>
    <img src="logo.png" alt="Logo" class="logo"> <!-- Adiciona a logo -->
    <div class="login-container">
        <h1>Login</h1>
        <?php if (isset($error)): ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="">
            <label for="username">Usuário:</label>
            <input type="text" id="username" name="username" required>
            
            <label for="password">Senha:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
