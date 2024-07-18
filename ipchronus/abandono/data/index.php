<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ligações Abandonadas por Data - IpChronus</title>
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
    </style>
    <!-- Adicionando Bootstrap JS e dependências -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- Carrega a logo -->
    <link rel="icon" href="logo.png" type="image/png">
</head>
<body>
    <!-- Logo -->
    <img src="logo.png" alt="Logo" class="logo">

    <div class="container-fluid d-flex justify-content-center align-items-center" style="min-height: 3vh;">
        <div class="text-center">
            <h2 class="text-white mb-4">Ligações Abandonadas na Fila</h2> <!-- Título visível na página -->
        </div>
    </div>

    <div class="container">
        <div class="row">
            <!-- Formulário de filtro por data -->
            <div class="col-md-6 offset-md-3">
                <div class="form-container">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="data">Filtrar por Data:</label>
                            <input type="date" id="data" name="data" value="<?php echo date('Y-m-d'); ?>" required>
                        </div>
                        <div class="btn-group">
                            <a href="?data=<?php echo date('Y-m-d', strtotime('-1 day')); ?>" class="btn btn-secondary">Ontem</a>
                            <a href="?data=<?php echo date('Y-m-d', strtotime('-2 days')); ?>" class="btn btn-secondary">Anteontem</a>
                            <button type="submit" class="btn btn-primary">Filtrar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Resultados filtrados -->
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" || isset($_GET['data'])) {
            $data_filtro = isset($_POST['data']) ? $_POST['data'] : $_GET['data'];
            echo "<h3 class='text-white mb-4'>Resultados para a data: " . date("d/m/Y", strtotime($data_filtro)) . "</h3>";
            echo "<div class='row'>";
            
            // Filtrar para cada fila utilizando a função exibirChamadasAbandonadas com a data de filtro
            exibirChamadasAbandonadas('/gravacoes/log/asterisk/queue_log', '9111', $data_filtro, 'Suporte Direto');
            exibirChamadasAbandonadas('/gravacoes/log/asterisk/queue_log', '001', $data_filtro, 'Suporte');
            exibirChamadasAbandonadas('/gravacoes/log/asterisk/queue_log', '002', $data_filtro, 'ADM');
            exibirChamadasAbandonadas('/gravacoes/log/asterisk/queue_log', '003', $data_filtro, 'Comercial');
            
            echo "</div>";
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

<?php
function exibirChamadasAbandonadas($arquivo, $fila, $data_filtro, $nome_fila) {
    if (file_exists($arquivo)) {
        $conteudo = file_get_contents($arquivo);

        if ($conteudo !== false) {
            $abandonos = array();

            $linhas = explode("\n", $conteudo);

            foreach ($linhas as $linha) {
                $campos = explode('|', $linha);
                if (count($campos) > 1 && $campos[2] == $fila && $campos[4] == 'ABANDON') {
                    $uniqueid = $campos[1];
                    $ramal_origem = '';
                    $timestamp_abandono = (int)$campos[0];

                    foreach ($linhas as $linha) {
                        $campos = explode('|', $linha);
                        if ($campos[1] == $uniqueid && $campos[4] == 'ENTERQUEUE') {
                            $ramal_origem = $campos[6];
                            $timestamp_entrada = (int)$campos[0];
                            break;
                        }
                    }

                    // Verifica se o timestamp de entrada não está nulo ou zero e se está dentro da data filtro
                    if (!empty($timestamp_entrada) && $timestamp_entrada != 0 && date("Y-m-d", $timestamp_entrada) == $data_filtro) {
                        // Calcula o tempo de espera na fila (em segundos)
                        $tempo_espera_segundos = $timestamp_abandono - $timestamp_entrada;
                        $tempo_espera_formatado = gmdate("H:i:s", $tempo_espera_segundos); // Formato HH:MM:SS

                        $horario_abandono = date("d/m/Y H:i:s", $timestamp_abandono);
                        $abandonos[] = array(
                            'horario' => $horario_abandono,
                            'ramal' => $ramal_origem,
                            'tempo_espera' => $tempo_espera_formatado
                        );
                    }
                }
            }

            if (!empty($abandonos)) {
                echo "<div class='col-md-4'>";
                echo "<div class='card'>";
                echo "<div class='card-header bg-danger text-white'>";
                echo "Abandono fila $nome_fila";
                echo "</div>";
                echo "<div class='card-body'>";
                echo "<div class='table-container'>";
                echo "<table class='table'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th scope='col'>Horário</th>";
                echo "<th scope='col'>Chamador</th>";
                echo "<th scope='col'>Espera</th>"; // Alterado de "Tempo de Espera" para "Espera"
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach ($abandonos as $abandono) {
                    echo "<tr>";
                    echo "<td>{$abandono['horario']}</td>";
                    echo "<td>{$abandono['ramal']}</td>";
                    echo "<td>{$abandono['tempo_espera']}</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
                echo "</div>";
            } else {
                echo "<div class='col-md-4'>";
                echo "<div class='card'>";
                echo "<div class='card-header bg-danger text-white'>";
                echo "Abandono fila $nome_fila";
                echo "</div>";
                echo "<div class='card-body'>";
                echo "<p class='card-text'>Nenhum registro encontrado para esta data.</p>";
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
