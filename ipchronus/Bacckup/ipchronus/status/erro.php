<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erro de Autenticação</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0d1c2b; /* Cor de fundo mais escura */
            color: #fff; /* Cor do texto */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .erro-box {
            width: 300px;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1); /* Fundo com transparência */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Sombra */
            text-align: center;
        }
        .erro-box h2 {
            margin-top: 0;
        }
        .botao {
            margin-top: 10px;
            padding: 10px;
            border: none;
            border-radius: 5px;
            background-color: #f44336; /* Cor de fundo vermelha */
            color: #fff;
            cursor: pointer;
        }
        .botao:hover {
            background-color: #cc0000; /* Cor de fundo vermelha mais escura ao passar o mouse */
        }
    </style>
</head>
<body>
    <div class="erro-box">
        <h2>Erro de Autenticação</h2>
        <p>Credenciais incorretas. Tente novamente.</p>
        <button class="botao" onclick="window.location.href='index.php'">Voltar ao Login</button>
    </div>
</body>
</html>

