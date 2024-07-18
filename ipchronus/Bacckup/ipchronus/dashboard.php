<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: index.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - IpChronus</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0A2540;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            position: relative; /* Adicionado para o posicionamento da logo */
        }
        .button-container {
            display: flex;
            gap: 20px;
            margin-top: 20px; /* Ajuste conforme necessário */
        }
        .button-container button {
            padding: 15px 30px;
            background-color: #fff;
            color: #0A2540;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
        }
        .button-container button:hover {
            background-color: #ddd;
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
    <h1>Bem-vindo ao Painel IpChronus</h1>
    <div class="button-container">
        <button onclick="window.location.href='fila'">Acesso ao Relatório de Filas</button>
        <button onclick="window.location.href='status'">Acesso Tempo Real</button>
        <button onclick="window.location.href='abandono'">Abandonos em tempo real</button> <!-- Novo botão -->
        <button onclick="window.location.href='abandono/data'">Abandonos</button> <!-- Novo botão -->
        <button onclick="window.location.href='completo'">Relatório completo de Filas</button> <!-- Novo botão -->
    </div>
</body>
<div class="footer">
________________________________________________________________________________________________________________
    <p>IpChronus Tecnologia LTDA ®<br>
    CNPJ: 30.560.988/0001-04<br>
    Av Feliciano Sodré Nº300 - Sala 516 - Teresópolis RJ<br>
    (21) 2042-1828</p>
</div>
</html>
