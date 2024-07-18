<?php
function getQueueData($queueNumber) {
    // Comando para obter informações da fila específica
    $command = "asterisk -rx 'queue show $queueNumber'";
    
    // Executa o comando e captura a saída
    $output = shell_exec($command);
    
    // Retorna a resposta como array de linhas
    return explode("\n", $output);
}

// Teste simples para verificar se a função está funcionando corretamente
$queueNumber = '001'; // Número da fila desejada
$responseLines = getQueueData($queueNumber);

echo "<pre>";
print_r($responseLines);
echo "</pre>";
?>

