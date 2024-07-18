document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;

    // Simulação de credenciais
    const validUsername = 'admin';
    const validPassword = 'ipchronus@123';

    if (username === validUsername && password === validPassword) {
        // Redirecionar para o dashboard
        window.location.href = "../dashboard/dashboard.html"
    } else {
        document.getElementById('error').innerText = 'Usuário ou senha inválidos!';
    }
});
