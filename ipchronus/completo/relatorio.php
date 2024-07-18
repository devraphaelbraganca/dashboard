<?php
// relatorio.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $selectedQueues = $_POST['queues'];

    echo "<h3 class='text-white mb-4'>Resultados de " . date("d/m/Y", strtotime($startDate)) . " a " . date("d/m/Y", strtotime($endDate)) . "</h3>";
    echo "<div class='row'>";

    foreach ($selectedQueues as $queue) {
        exibirChamadas('/gravacoes/log/asterisk/queue_log', $queue, $startDate, $endDate, $queues[$queue]);
    }

    echo "</div>";
}

function exibirChamadas($arquivo, $fila, $start_date, $end_date, $nome_fila) {
    if (file_exists($arquivo)) {
        $conteudo = file_get_contents($arquivo);

        if ($conteudo !== false) {
            $chamadas = array();
            $linhas = explode("\n", $conteudo);

            foreach ($linhas as $linha) {
                $campos = explode('|', $linha);
                if (count($campos) > 1 && $campos[2] === $fila) {
                    $uniqueid = $campos[1];
                    $timestamp = (int)$campos[0];
                    $data = date("Y-m-d", $timestamp);

                    if ($data >= $start_date && $data <= $end_date) {
                        if ($campos[4] == 'ENTERQUEUE') {
                            $ramal_origem = $campos[6];
                            $chamadas[$uniqueid]['origem'] = $ramal_origem;
                            $chamadas[$uniqueid]['entrada'] = $timestamp;
                        } elseif ($campos[4] == 'CONNECT') {
                            $ramal_atendido = $campos[3];
                            $chamadas[$uniqueid]['atendido'] = $ramal_atendido;
                            $chamadas[$uniqueid]['conectado'] = $timestamp;
                        } elseif ($campos[4] == 'ABANDON') {
                            $chamadas[$uniqueid]['atendido'] = 'ABANDONADO';
                            $chamadas[$uniqueid]['abandonado'] = true;
                        }
                    }
                }
            }

            if (!empty($chamadas)) {
                echo "<div class='col-md-12'>";
                echo "<div class='card'>";
                echo "<div class='card-header bg-success text-white'>";
                echo "Chamadas na fila $nome_fila";
                echo "</div>";
                echo "<div class='card-body'>";
                echo "<div class='table-container'>";
                echo "<table class='table'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th scope='col'>Horário</th>";
                echo "<th scope='col'>Quem Ligou</th>";
                echo "<th scope='col'>Quem Atendeu</th>";
                echo "<th scope='col'>Tempo de Ligação</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($chamadas as $chamada) {
                    $atendido = isset($chamada['atendido']) ? $chamada['atendido'] : 'N/A';
                    $class = isset($chamada['abandonado']) ? 'text-danger' : '';
                    $atendido_texto = isset($chamada['abandonado']) ? 'ABANDONADO' : ($atendido == 'N/A' ? 'Não Atendido' : $atendido);

                    $tempo_ligacao = isset($chamada['conectado']) ? $chamada['conectado'] - $chamada['entrada'] : 0;
                    $tempo_ligacao_formatado = gmdate("H:i:s", $tempo_ligacao);

                    echo "<tr>";
                    echo "<td>" . date("d/m/Y H:i:s", $chamada['entrada']) . "</td>";
                    echo "<td>" . (isset($chamada['origem']) ? $chamada['origem'] : 'N/A') . "</td>";
                    echo "<td class='$class'>" . $atendido_texto . "</td>";
                    echo "<td>" . $tempo_ligacao_formatado . "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='col-md-12'>";
                echo "<div class='card'>";
                echo "<div class='card-header bg-success text-white'>";
                echo "Chamadas na fila $nome_fila";
                echo "</div>";
                echo "<div class='card-body'>";
                echo "<p class='card-text'>Nenhum registro encontrado para este período.</p>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "<p class='card-text'>Não foi possível ler o arquivo.</p>";
        }
    } else {
        echo "<p class='card-text'>O arquivo não existe.</p>";
    }
}
?>
