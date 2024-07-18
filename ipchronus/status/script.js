
function updateStatus() {
    fetch('index.php')
        .then(response => response.json())
        .then(data => {
            const estadoDiv = document.querySelector('.quadrado');
            const estadoText = document.querySelector('.estado');
            const numeroLigacaoText = document.querySelector('.numero-ligacao');

            // Atualizar estado
            switch (data.estado_ramal) {
                case 'Online':
                    estadoDiv.style.backgroundColor = 'green';
                    break;
                case 'Em Ligação':
                    estadoDiv.style.backgroundColor = 'red';
                    numeroLigacaoText.textContent = `Número da Ligação: ${data.numero_ligacao}`;
                    break;
                default:
                    estadoDiv.style.backgroundColor = 'gray';
                    break;
            }
            estadoText.textContent = `Estado: ${data.estado_ramal}`;
        })
        .catch(error => console.error('Erro:', error));
}

// Atualiza a cada 10 segundos
setInterval(updateStatus, 10000);
updateStatus(); // Chama na inicialização
