<?php

function getQueueData($queueNumber) {
    // Configurações de acesso ao AMI Asterisk
    $host = '127.0.0.1'; // IP do servidor Asterisk
    $port = '5038'; // Porta do AMI
    $username = 'admin'; // Nome de usuário configurado no manager.conf
    $password = 'amp111'; // Senha configurada no manager.conf

    // Comando para obter informações da fila específica
    $command = "Action: Command\r\n";
    $command .= "Command: queue show $queueNumber\r\n\r\n";

    // Tenta estabelecer a conexão com o Asterisk via AMI
    $socket = @fsockopen($host, $port, $errno, $errstr, 10);
    if (!$socket) {
        return "Falha ao conectar ao Asterisk - $errstr ($errno)";
    }

    // Autenticação no AMI
    $login = "Action: Login\r\n";
    $login .= "Username: $username\r\n";
    $login .= "Secret: $password\r\n";
    $login .= "Events: off\r\n";
    $login .= "\r\n";
    fwrite($socket, $login);

    // Função para ler a resposta do socket
    function read_response($socket) {
        $response = '';
        while (!feof($socket)) {
            $line = fgets($socket);
            $response .= $line;
            if (strpos($line, "--END COMMAND--") !== false) {
                break;
            }
        }
        return $response;
    }

    // Verifica se a conexão foi bem sucedida
    $response = read_response($socket);
    if (strpos($response, 'Success') === false) {
        return "Falha ao autenticar no Asterisk - $response";
    }

    // Envia o comando para listar a fila específica
    fwrite($socket, $command);

    // Lê a resposta do comando
    $response = read_response($socket);

    // Fecha o socket
    fclose($socket);

    // Retorna a resposta como array de linhas
    return explode("\r\n", $response);
}
?>

