<?php
// Função para obter dados da fila (mantida como estava)
function getQueueData($queueNumber) {
    $command = "asterisk -rx 'queue show $queueNumber'";
    exec($command, $output, $return_var);

    if ($return_var !== 0) {
        return false;
    }

    $calls = 0;
    $callers = array();
    $readingCallers = false;

    foreach ($output as $line) {
        if (preg_match('/^\d+ has (\d+) calls/', $line, $matches)) {
            $calls = $matches[1];
        } elseif (strpos($line, 'Callers:') !== false) {
            $readingCallers = true;
        } elseif ($readingCallers && preg_match('/^\s*(\d+)\.\s+(\S+)\s+\(wait:\s+(\d+:\d+),\s+prio:\s+(\d+)\)/', $line, $matches)) {
            $callerInfo = $matches[2] . ' (' . $matches[3] . ')';
            $callers[] = $callerInfo;
        } elseif ($readingCallers && trim($line) === '') {
            break;
        }
    }

    return array(
        'calls' => $calls,
        'callers' => $callers
    );
}

// Obtém dados das filas (mantidas como estavam)
$queue9111 = '9111';
$data9111 = getQueueData($queue9111);
$calls9111 = $data9111['calls'];
$callers9111 = $data9111['callers'];

$queue001 = '001';
$data001 = getQueueData($queue001);
$calls001 = $data001['calls'];
$callers001 = $data001['callers'];

$queue002 = '002';
$data002 = getQueueData($queue002);
$calls002 = $data002['calls'];
$callers002 = $data002['callers'];

$queue003 = '003';
$data003 = getQueueData($queue003);
$calls003 = $data003['calls'];
$callers003 = $data003['callers'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="2"> <!-- Atualiza a cada 2 segundos -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor de Filas IpChronus</title>
       <link rel="icon" href="favicon.ico" type="favicon.ico"> <!-- Adiciona o favicon -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #0A2540; /* Fundo azul escuro */
            color: #fff; /* Texto branco */
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            min-height: 100vh; /* Garante que o corpo ocupe pelo menos a altura da tela */
            position: relative; /* Posição relativa para o posicionamento absoluto do logo */
        }
        .container {
            display: flex;
            flex-wrap: wrap; /* Permite que os cards quebrem para a próxima linha */
            justify-content: space-between; /* Espaçamento entre os cards */
            max-width: 1200px;
            margin: 20px auto;
        }
        .card {
            flex: 0 0 calc(30% - 20px); /* Tamanho dos cards com espaço entre eles */
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            color: #333; /* Texto preto nos cards */
            text-align: center; /* Centraliza o texto dentro dos cards */
        }
        .card.red {
            background-color: #FF6347; /* Vermelho */
        }
        h1 {
            color: #fff; /* Texto branco para o título */
            text-align: center;
            margin-bottom: 20px; /* Espaçamento abaixo do título */
        }
        h2 {
            color: #333;
            text-align: center;
            font-size: 1.5em; /* Tamanho maior para os títulos das filas */
            margin-bottom: 10px; /* Espaçamento abaixo dos títulos das filas */
        }
        ul {
            list-style-type: none;
            padding: 0;
        }
        li {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .logo {
            position: absolute;
            top: 20px;
            left: 20px;
            width: 150px; /* Largura da logo */
            height: auto; /* Altura ajustável conforme a largura */
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

<img src="logo.png" alt="Logo" class="logo">

<h1>Filas IpChronus Tecnologia</h1>

<div class="container">
    <!-- Cards das Filas (mantidos como estavam) -->
    <!-- Fila 9111 -->
    <div class="card <?php echo ($calls9111 > 0) ? 'red' : ''; ?>">
        <h2>Fila 911 - Suporte Direto</h2>
        <?php if ($calls9111 !== false): ?>
            <p>Número de Chamadas: <?php echo $calls9111; ?></p>
            <?php if (!empty($callers9111)): ?>
                <h3>Chamadores:</h3>
                <ul>
                    <?php foreach ($callers9111 as $caller): ?>
                        <li><?php echo $caller; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Não há chamadores na fila no momento.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Não foi possível obter informações da fila 9111.</p>
        <?php endif; ?>
    </div>

    <!-- Fila 001 -->
    <div class="card <?php echo ($calls001 > 0) ? 'red' : ''; ?>">
        <h2>Fila - Suporte</h2>
        <?php if ($calls001 !== false): ?>
            <p>Número de Chamadas: <?php echo $calls001; ?></p>
            <?php if (!empty($callers001)): ?>
                <h3>Chamadores:</h3>
                <ul>
                    <?php foreach ($callers001 as $caller): ?>
                        <li><?php echo $caller; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Não há chamadores na fila no momento.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Não foi possível obter informações da fila 001.</p>
        <?php endif; ?>
    </div>

    <!-- Fila 002 -->
    <div class="card <?php echo ($calls002 > 0) ? 'red' : ''; ?>">
        <h2>Fila - ADM</h2>
        <?php if ($calls002 !== false): ?>
            <p>Número de Chamadas: <?php echo $calls002; ?></p>
            <?php if (!empty($callers002)): ?>
                <h3>Chamadores:</h3>
                <ul>
                    <?php foreach ($callers002 as $caller): ?>
                        <li><?php echo $caller; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Não há chamadores na fila no momento.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Não foi possível obter informações da fila 002.</p>
        <?php endif; ?>
    </div>

    <!-- Fila 003 -->
    <div class="card <?php echo ($calls003 > 0) ? 'red' : ''; ?>">
        <h2>Fila - Comercial</h2>
        <?php if ($calls003 !== false): ?>
            <p>Número de Chamadas: <?php echo $calls003; ?></p>
            <?php if (!empty($callers003)): ?>
                <h3>Chamadores:</h3>
                <ul>
                    <?php foreach ($callers003 as $caller): ?>
                        <li><?php echo $caller; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Não há chamadores na fila no momento.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Não foi possível obter informações da fila 003.</p>
        <?php endif; ?>
    </div>
</div>

<hr class="divider">



</body>
<div class="footer">
________________________________________________________________________________________________________________
    <p>IpChronus Tecnologia LTDA ®<br>
    CNPJ: 30.560.988/0001-04<br>
    Av Feliciano Sodré Nº300 - Sala 516 - Teresópolis RJ<br>
    (21) 2042-1828</p>
</div>
</html>
