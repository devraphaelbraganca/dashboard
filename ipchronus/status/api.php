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
        $numero_ligacao = substr($matches[1], 0, 11);
        return '(' . substr($numero_ligacao, 0, 2) . ') ' . substr($numero_ligacao, 2);
    }
    return '';
}

// Executar o comando Asterisk e obter a saída
$output = executeAsteriskCommand('sip show peer 1001');
$estado_ramal = isRamalOnline($output) ? 'Online' : 'Offline';
$numero_ligacao_formatado = '';

if ($estado_ramal === 'Online') {
    $output_channels = executeAsteriskCommand('core show channels concise');
    $linhas = explode("\n", $output_channels);
    
    foreach ($linhas as $linha) {
        if (strpos($linha, 'SIP/1001-') !== false) {
            $numero_ligacao_formatado = extractFormattedNumber($linha);
            $estado_ramal = 'Em Ligação';
            break;
        }
    }
}

$response = [
    'estado_ramal' => $estado_ramal,
    'numero_ligacao' => $numero_ligacao_formatado,
];

header('Content-Type: application/json');
echo json_encode($response);
?>
