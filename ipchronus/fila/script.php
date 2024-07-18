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
        'queueName' => 'Nome da Fila Aqui', // Substitua pelo nome correto da fila
        'calls' => $calls,
        'callers' => $callers
    );
}

// Monta um array com os dados de todas as filas desejadas
$queueData = array(
    '9111' => getQueueData('9111'),
    '001' => getQueueData('001'),
    '002' => getQueueData('002'),
    '003' => getQueueData('003')
);

// Retorna os dados como JSON
header('Content-Type: application/json');
echo json_encode($queueData);
?>
