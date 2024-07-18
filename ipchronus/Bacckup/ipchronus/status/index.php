<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado dos Ramais</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #0A2540; /* Cor de fundo mais escura */
            color: #fff; /* Cor do texto */
        }
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.1); /* Fundo com transparência */
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2); /* Sombra */
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around; /* Alinha os quadrados ao centro */
        }
        .quadrado {
            width: 150px;
            height: 150px;
            border: 2px solid #ccc; /* Borda dos quadrados */
            border-radius: 10px;
            margin: 10px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Espaço entre os textos dentro do quadrado */
            align-items: center;
            background-color: #f9f9f9; /* Cor de fundo dos quadrados */
            transition: all 0.3s ease; /* Transição suave */
        }
        .quadrado:hover {
            transform: scale(1.05); /* Efeito de escala ao passar o mouse */
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.3); /* Sombra mais forte ao passar o mouse */
        }
        .estado {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
        }
        .numero {
            font-size: 16px;
            color: #fff; /* Cor do número da ligação */
        }
        .titulo {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .logo {
            position: absolute;
            top: 10px;
            left: 10px;
            width: 300px; /* Tamanho maior da logo */
            height: auto;
            opacity: 0.7; /* Opacidade reduzida */
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
    <script>
        // Função para atualizar a página a cada 10 segundos
        setTimeout(function() {
            location.reload();
        }, 5000); // 10 segundos (10000 milissegundos)
    </script>
</head>
<body>
    <img class="logo" src="logo.png" alt="Logo IpChronus"> <!-- Logo na mesma pasta -->

    <h1 class="titulo">Painel IpChronus Tecnologia</h1>

    <div class="container">
        <?php
        // Array de ramais específicos com nomes
        $ramais = array(
            1001 => 'Leo',
            1004 => 'Alexandre',
            1005 => 'Camila',
            1007 => 'Bancada',
            1008 => 'Raphael'
        );

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
            preg_match('/SIP\/\d+-.*!.*!.*!.*!.*!.*!SIP\/.*\/(.*)!.*!.*!.*!.*!.*!/', $linha, $matches);
            if (isset($matches[1])) {
                // Extrair apenas os 11 primeiros dígitos e formatar como (XX) XXXXXXXXX
                $numero_ligacao = substr($matches[1], 0, 11);
                return '(' . substr($numero_ligacao, 0, 2) . ') ' . substr($numero_ligacao, 2);
            }
            return '';
        }

        // Array para armazenar o estado e cor de cada ramal
        $ramais_info = array();

        foreach ($ramais as $ramal => $nome) {
            // Comando para verificar os detalhes do ramal
            $command = "sip show peer $ramal";

            // Executar o comando Asterisk e obter a saída
            $output = executeAsteriskCommand($command);

            // Variáveis para armazenar o estado e cor do quadrado
            $estado_ramal = 'Offline';
            $cor_quadrado = 'gray'; // Cinza para offline
            $numero_ligacao_formatado = '';

            // Verificar se o ramal está online
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
                    if (strpos($linha, "SIP/$ramal-") !== false) {
                        // Ramal encontrado em ligação, extrair o número da ligação
                        $numero_ligacao_formatado = extractFormattedNumber($linha);
                        $estado_ramal = 'Em Ligação';
                        $cor_quadrado = 'red'; // Vermelho para em ligação
                        $ramal_em_ligacao = true;
                        break;
                    }
                }
            }

            // Armazenar informações do ramal no array
            $ramais_info[] = array(
                'ramal' => $ramal,
                'nome' => $nome,
                'estado_ramal' => $estado_ramal,
                'cor_quadrado' => $cor_quadrado,
                'numero_ligacao_formatado' => $numero_ligacao_formatado
            );
        }
        ?>

        <?php foreach ($ramais_info as $info): ?>
            <div class="quadrado" style="background-color: <?php echo $info['cor_quadrado']; ?>;">
                <p><?php echo $info['ramal']; ?> - <?php echo $info['nome']; ?></p>
                <p class="estado"><?php echo $info['estado_ramal']; ?></p>
                <?php if ($info['estado_ramal'] === 'Em Ligação'): ?>
                    <p class="numero">Número: <?php echo $info['numero_ligacao_formatado']; ?></p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
<div class="footer">
________________________________________________________________________________________________________________
    <p>IpChronus Tecnologia LTDA ®<br>
    CNPJ: 30.560.988/0001-04<br>
    Av Feliciano Sodré Nº300 - Sala 516 - Teresópolis RJ<br>
    (21) 2042-1828</p>
</div>
</html>

