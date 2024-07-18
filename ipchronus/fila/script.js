document.addEventListener("DOMContentLoaded", function() {
    // Seleciona o container onde os cards serão inseridos
    var container = document.querySelector('.container');

    // Função para criar um card de fila
    function createQueueCard(queueId, queueName, calls, callers) {
        var card = document.createElement('div');
        card.className = 'card ' + (calls > 0 ? 'red' : '');

        var html = '<h2>Fila ' + queueId + ' - ' + queueName + '</h2>';
        if (calls > 0) {
            html += '<p>Número de Chamadas: ' + calls + '</p>';
            html += '<h3>Chamadores:</h3><ul>';
            callers.forEach(function(caller) {
                html += '<li>' + caller + '</li>';
            });
            html += '</ul>';
        } else {
            html += '<p>Não há chamadores na fila no momento.</p>';
        }

        card.innerHTML = html;
        return card;
    }

    // Função para carregar os dados das filas
    function loadQueueData() {
        // Requisição AJAX para obter os dados do PHP
        var xhr = new XMLHttpRequest();
        xhr.open('GET', '/ipchronus/fila/script.php', true);
        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 400) {
                var queueData = JSON.parse(xhr.responseText);

                // Limpa o conteúdo atual do container
                container.innerHTML = '';

                // Itera sobre os dados recebidos e cria os cards das filas
                ['9111', '001', '002', '003'].forEach(function(queueId) {
                    if (queueData[queueId]) {
                        var data = queueData[queueId];
                        var card = createQueueCard(queueId, data.queueName, data.calls, data.callers);
                        container.appendChild(card);
                    }
                });
            } else {
                console.error('Erro ao carregar dados das filas. Status:', xhr.status);
            }
        };
        xhr.onerror = function() {
            console.error('Erro de conexão ao tentar carregar dados das filas.');
        };
        xhr.send();
    }

    // Carrega os dados das filas inicialmente e define o intervalo de atualização
    loadQueueData();
    setInterval(loadQueueData, 2000); // Atualiza a cada 2 segundos
});
