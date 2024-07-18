<?php
// Função para executar comando no Asterisk e retornar a saída
function executeAsteriskCommand($command) {
    return shell_exec('asterisk -rx "' . $command . '"');
}

// Função para verificar se o ramal está online
function isRamalOnline($output) {
    return strpos($output, 'Status') !== false && strpos($output, 'OK (') !== false;
}

// Função para extrair e formatar o número da ligação
function extractFormattedNumber($linha) {
    preg_match('/SIP\/1001-.*!.*!.*!.*!.*!.*!SIP\/.*\/(.*)!.*!.*!.*!.*!.*!/', $linha, $matches);
    if (isset($matches[1])) {
        // Extrair apenas os 11 primeiros dígitos e formatar como (XX) XXXXXXXXX
        $numero_ligacao = substr($matches[1], 0, 11);
        return '(' . substr($numero_ligacao, 0, 2) . ') ' . substr($numero_ligacao, 2);
    }
    return '';
}

// Comando para verificar os detalhes do ramal 1001
$command = 'sip show peer 1001';

// Executar o comando Asterisk e obter a saída
$output = executeAsteriskCommand($command);

// Verificar se o ramal está online, offline ou em ligação
if (isRamalOnline($output)) {
    $estado_ramal = 'Online';
    $cor_quadrado = 'green'; // Verde para online

    // Comando para verificar os canais ativos
    $command_channels = 'core show channels concise';

    // Executar o comando Asterisk e obter a saída
    $output_channels = executeAsteriskCommand($command_channels);

    // Verificar se o ramal está em ligação
    $ramal_em_ligacao = false;
    $linhas = explode("\n", $output_channels);
    foreach ($linhas as $linha) {
        if (strpos($linha, 'SIP/1001-') !== false) {
            // Ramal encontrado em ligação, extrair o número da ligação
            $numero_ligacao_formatado = extractFormattedNumber($linha);
            $ramal_em_ligacao = true;
            break;
        }
    }

    if ($ramal_em_ligacao) {
        $estado_ramal = 'Em Ligação';
        $cor_quadrado = 'red'; // Vermelho para em ligação
    }
} else {
    $estado_ramal = 'Offline';
    $cor_quadrado = 'gray'; // Cinza para offline
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado do Ramal</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 50px;
        }
        .quadrado {
            width: 100px;
            height: 100px;
            background-color: <?php echo $cor_quadrado; ?>;
            border: 2px solid black;
            border-radius: 10px;
            display: inline-block;
        }
        .estado {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
    <script>
        // Função para atualizar a página a cada 10 segundos
        setTimeout(function() {
            location.reload();
        }, 10000); // 10 segundos (10000 milissegundos)
    </script>
</head>
<body>
    <h1>Estado do Ramal 1001</h1>
    <div class="quadrado"></div>
    <p class="estado">Estado: <?php echo $estado_ramal; ?></p>
    <?php if ($estado_ramal === 'Em Ligação'): ?>
        <p>Número da Ligação: <?php echo $numero_ligacao_formatado; ?></p>
    <?php endif; ?>
</body>
</html>

