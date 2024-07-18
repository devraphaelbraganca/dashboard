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
        'calls' => $calls,
        'callers' => $callers
    );
}

// Obtém dados das filas (mantidas como estavam)
$queue9111 = '9111';
$data9111 = getQueueData($queue9111);
$calls9111 = $data9111['calls'];
$callers9111 = $data9111['callers'];

$queue001 = '001';
$data001 = getQueueData($queue001);
$calls001 = $data001['calls'];
$callers001 = $data001['callers'];

$queue002 = '002';
$data002 = getQueueData($queue002);
$calls002 = $data002['calls'];
$callers002 = $data002['callers'];

$queue003 = '003';
$data003 = getQueueData($queue003);
$calls003 = $data003['calls'];
$callers003 = $data003['callers'];

// Incluir o cabeçalho HTML
include('header.php');
?>

<!-- Corpo da página -->
<img src="logo.png" alt="Logo" class="logo">

<h1>Filas IpChronus Tecnologia</h1>

<div class="container">
    <!-- Cards das Filas (mantidos como estavam) -->
    <!-- Fila 9111 -->
    <div class="card <?php echo ($calls9111 > 0) ? 'red' : ''; ?>">
        <h2>Fila 911 - Suporte Direto</h2>
        <?php if ($calls9111 !== false): ?>
            <p>Número de Chamadas: <?php echo $calls9111; ?></p>
            <?php if (!empty($callers9111)): ?>
                <h3>Chamadores:</h3>
                <ul>
                    <?php foreach ($callers9111 as $caller): ?>
                        <li><?php echo $caller; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Não há chamadores na fila no momento.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Não foi possível obter informações da fila 9111.</p>
        <?php endif; ?>
    </div>

    <!-- Fila 001 -->
    <div class="card <?php echo ($calls001 > 0) ? 'red' : ''; ?>">
        <h2>Fila - Suporte</h2>
        <?php if ($calls001 !== false): ?>
            <p>Número de Chamadas: <?php echo $calls001; ?></p>
            <?php if (!empty($callers001)): ?>
                <h3>Chamadores:</h3>
                <ul>
                    <?php foreach ($callers001 as $caller): ?>
                        <li><?php echo $caller; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Não há chamadores na fila no momento.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Não foi possível obter informações da fila 001.</p>
        <?php endif; ?>
    </div>

    <!-- Fila 002 -->
    <div class="card <?php echo ($calls002 > 0) ? 'red' : ''; ?>">
        <h2>Fila - ADM</h2>
        <?php if ($calls002 !== false): ?>
            <p>Número de Chamadas: <?php echo $calls002; ?></p>
            <?php if (!empty($callers002)): ?>
                <h3>Chamadores:</h3>
                <ul>
                    <?php foreach ($callers002 as $caller): ?>
                        <li><?php echo $caller; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Não há chamadores na fila no momento.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Não foi possível obter informações da fila 002.</p>
        <?php endif; ?>
    </div>

    <!-- Fila 003 -->
    <div class="card <?php echo ($calls003 > 0) ? 'red' : ''; ?>">
        <h2>Fila - Comercial</h2>
        <?php if ($calls003 !== false): ?>
            <p>Número de Chamadas: <?php echo $calls003; ?></p>
            <?php if (!empty($callers003)): ?>
                <h3>Chamadores:</h3>
                <ul>
                    <?php foreach ($callers003 as $caller): ?>
                        <li><?php echo $caller; ?></li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <p>Não há chamadores na fila no momento.</p>
            <?php endif; ?>
        <?php else: ?>
            <p>Não foi possível obter informações da fila 003.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Incluir o rodapé HTML -->
<?php include('footer.php'); ?>
