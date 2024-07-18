<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abandono nas Filas IpChronus</title>
    <!-- Adicionando Bootstrap CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0A2540;
            padding-top: 50px; /* Aumenta o padding no topo para acomodar o logo */
            margin: 0; /* Remove margens padrão do body */
            color: #fff; /* Cor do texto padrão */
        }
        .card {
            margin-bottom: 20px;
            width: 350px; /* Largura original do card */
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
        }
        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 150px; /* Tamanho da logo */
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
    <!-- Adicionando Bootstrap JS e dependências -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Carrega a logo -->
    <link rel="icon" href="caminho/para/sua/logo.png" type="image/png">
    <!-- Meta tag para refresh automático a cada 10 segundos -->
    <meta http-equiv="refresh" content="10">
</head>
<body>
    <!-- Logo -->
    <img src="logo.png" alt="Logo" class="logo">

    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center mb-4">
                <h2 class="text-white">Tempo Real Filas IpChronus</h2> <!-- Título visível na página -->
            </div>
        </div>
        <div class="row">
            <!-- Card para a fila 9111 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        Abandono fila Suporte Direto
                    </div>
                    <div class="card-body">
                        <?php exibirChamadasAbandonadas('/gravacoes/log/asterisk/queue_log', '9111'); ?>
                    </div>
                </div>
            </div>

            <!-- Card para a fila 001 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        Abandono Fila Suporte
                    </div>
                    <div class="card-body">
                        <?php exibirChamadasAbandonadas('/gravacoes/log/asterisk/queue_log', '001'); ?>
                    </div>
                </div>
            </div>

            <!-- Card para a fila 002 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        Abandono fila Administração
                    </div>
                    <div class="card-body">
                        <?php exibirChamadasAbandonadas('/gravacoes/log/asterisk/queue_log', '002'); ?>
                    </div>
                </div>
            </div>

            <!-- Card para a fila 003 -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        Abandono fila Comercial
                    </div>
                    <div class="card-body">
                        <?php exibirChamadasAbandonadas('/gravacoes/log/asterisk/queue_log', '003'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="footer">
        <hr>
        <p>IpChronus Tecnologia LTDA ®<br>
        CNPJ: 30.560.988/0001-04<br>
        Av Feliciano Sodré Nº300 - Sala 516 - Teresópolis RJ<br>
        (21) 2042-1828</p>
    </div>

</body>
</html>

<?php
function exibirChamadasAbandonadas($arquivo, $fila) {
    if (file_exists($arquivo)) {
        $conteudo = file_get_contents($arquivo);

        if ($conteudo !== false) {
            $abandonos = array();

            $linhas = explode("\n", $conteudo);

            // Obter data atual no formato usado no arquivo de log (segundos desde Unix epoch)
            $data_atual = strtotime('today');

            foreach ($linhas as $linha) {
                $campos = explode('|', $linha);
                
                // Verificar se há campos suficientes e se é a fila desejada e a chamada foi abandonada
                if (count($campos) > 1 && $campos[2] == $fila && $campos[4] == 'ABANDON') {
                    // Obter timestamp da linha e convertê-lo para o formato de data
                    $timestamp_abandono = (int)$campos[0];
                    $horario_abandono = date("d/m/Y H:i:s", $timestamp_abandono);
                    
                    // Buscar o uniqueid da chamada abandonada
                    $uniqueid = $campos[1];
                    $ramal_origem = '';

                    // Buscar o timestamp de entrada na fila (ENTERQUEUE) correspondente
                    $timestamp_entrada = null;

                    foreach ($linhas as $linha) {
                        $campos = explode('|', $linha);
                        if ($campos[1] == $uniqueid && $campos[4] == 'ENTERQUEUE') {
                            $timestamp_entrada = (int)$campos[0];
                            $ramal_origem = $campos[6]; // Captura o ramal de origem
                            break;
                        }
                    }

                    // Calcular o tempo de espera na fila (em segundos)
                    if ($timestamp_entrada !== null) {
                        $tempo_espera_segundos = $timestamp_abandono - $timestamp_entrada;
                        $tempo_espera_formatado = gmdate("H:i:s", $tempo_espera_segundos); // Formato HH:MM:SS
                    } else {
                        $tempo_espera_formatado = 'N/A'; // Caso não encontre o timestamp de entrada na fila
                    }

                    // Verificar se o timestamp do abandono pertence ao dia atual
                    if ($timestamp_abandono >= $data_atual) {
                        $abandonos[] = array(
                            'horario_abandono' => $horario_abandono,
                            'ramal' => $ramal_origem,
                            'tempo_espera' => $tempo_espera_formatado
                        );
                    }
                }
            }

            echo "<div class='table-container'>";
            echo "<table class='table'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th scope='col'>Horário de Abandono</th>";
            echo "<th scope='col'>Chamador</th>";
            echo "<th scope='col'>Espera</th>"; // Alterei o texto aqui
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($abandonos as $abandono) {
                echo "<tr>";
                echo "<td>{$abandono['horario_abandono']}</td>";
                echo "<td>{$abandono['ramal']}</td>";
                echo "<td>{$abandono['tempo_espera']}</td>";
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
            echo "</div>";
        } else {
            echo "<p class='card-text'>Não foi possível ler o arquivo.</p>";
        }
    } else {
        echo "<p class='card-text'>O arquivo não existe.</p>";
    }
}
?>
