<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Ligações - IpChronus</title>
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
            width: 100%; /* Ajuste para ocupar toda a largura da tela */
            overflow: hidden; /* Remove a barra de rolagem do card */
        }
        .table-container {
            /* Remove a altura máxima e o overflow para evitar a barra de rolagem */
            overflow: auto;
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
        .form-container {
            background-color: #223548;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .form-container label {
            margin-bottom: 10px;
            display: block;
            font-size: 14px; /* Reduz o tamanho da fonte */
        }
        .form-container input[type="date"] {
            width: 100%;
            padding: 8px;
            border: none;
            border-radius: 4px;
            margin-bottom: 10px;
            display: inline-block;
            vertical-align: middle;
            font-size: 14px; /* Reduz o tamanho da fonte */
        }
        .form-container .btn-group {
            margin-top: 10px;
        }
        .form-container .btn-group .btn {
            margin-right: 10px;
            font-size: 14px; /* Reduz o tamanho da fonte */
        }
        .form-container button {
            padding: 10px 20px;
            background-color: #17a2b8;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px; /* Reduz o tamanho da fonte */
        }
        .form-container button:hover {
            background-color: #0e899b;
        }
        .card-header {
            font-size: 18px;
            font-weight: bold;
        }
        .text-danger {
            color: #dc3545 !important; /* Cor vermelha para "ABANDONADO" */
        }
    </style>
    <!-- Adicionando Bootstrap JS e dependências -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Carrega a logo -->
    <link rel="icon" href="logo.png" type="image/png">
    <!-- Adicionando o Script para preencher as datas -->
    <script>
        function setDateToYesterday() {
            let today = new Date();
            let yesterday = new Date(today);
            yesterday.setDate(today.getDate() - 1);

            let dd = String(yesterday.getDate()).padStart(2, '0');
            let mm = String(yesterday.getMonth() + 1).padStart(2, '0');
            let yyyy = yesterday.getFullYear();

            let dateString = yyyy + '-' + mm + '-' + dd;

            document.getElementById('start_date').value = dateString;
            document.getElementById('end_date').value = dateString; // Data de ontem
        }

        function setDateToToday() {
            let today = new Date();
            let dd = String(today.getDate()).padStart(2, '0');
            let mm = String(today.getMonth() + 1).padStart(2, '0');
            let yyyy = today.getFullYear();

            let dateString = yyyy + '-' + mm + '-' + dd;

            document.getElementById('start_date').value = dateString;
            document.getElementById('end_date').value = dateString; // Data de hoje
        }
    </script>
</head>
<body>
    <!-- Logo -->
    <img src="logo.png" alt="Logo" class="logo">

    <div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 3vh;">
        <div class="text-center">
            <h2 class="text-white mb-4">Relatório de Ligações</h2>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Formulário de filtro por data -->
            <div class="col-md-12">
                <div class="form-container">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="start_date">Data de Início:</label>
                            <input type="date" id="start_date" name="start_date" required>
                        </div>
                        <div class="form-group">
                            <label for="end_date">Data de Fim:</label>
                            <input type="date" id="end_date" name="end_date" required>
                        </div>
                        <div class="form-group">
                            <label>Filas:</label>
                            <?php
                            $queues = array(
                                '9111' => 'Suporte Direto',
                                '001' => 'Suporte',
                                '002' => 'Administrativo',
                                '003' => 'Comercial'
                            );
                            foreach ($queues as $queueId => $queueName) {
                                echo "<div class='form-check'>";
                                echo "<input class='form-check-input' type='checkbox' id='queue_$queueId' name='queues[]' value='$queueId'>";
                                echo "<label class='form-check-label' for='queue_$queueId'>$queueId - $queueName</label>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">Gerar Relatório</button>
                            <button type="button" class="btn btn-secondary" onclick="setDateToYesterday()">Ontem</button>
                            <button type="button" class="btn btn-secondary" onclick="setDateToToday()">Hoje</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resultados filtrados -->
        <?php
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
    </div>

    <div class="footer">
        ________________________________________________________________________________________________________________
        <p>IpChronus Tecnologia LTDA ®<br>
        CNPJ: 30.560.988/0001-04<br>
        Av Feliciano Sodré Nº300 - Sala 516 - Teresópolis RJ<br>
        (21) 2042-1828</p>
    </div>
</body>
</html>
