<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONG Biblioteca - Sala Arco-íris</title>
    <link rel="stylesheet" type="text/css" href="subtelas_css/recuperarsenha.css">
    <script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@3/dist/email.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <button class="btn-voltar" onclick="window.history.back()">← Voltar</button>
        <h1>ESQUECI MINHA SENHA</h1>
    </header>

    <div id="email-form" class="email-form">
        <form class="formulario" id="form_login" action="#" method="post">
            <div class="input-group">
                <span class="icon">👤</span>
                <input type="email" name="email" id="email" placeholder="Digite seu e-mail" required>
            </div>

            <div class="botao">
                <button type="submit" class="btn"> Enviar </button>
            </div>
        </form>
    </div>

    <div id="verification-container" class="verification-container">
        <h2 class="verification-title">Digite o código de verificação</h2>
        <p>Enviamos um código de 5 dígitos para seu e-mail</p>
        <div class="verification-inputs">
            <input type="text" class="verification-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
            <input type="text" class="verification-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
            <input type="text" class="verification-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
            <input type="text" class="verification-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
            <input type="text" class="verification-input" maxlength="1" pattern="[0-9]" inputmode="numeric">
        </div>
        <button class="btn-verify" onclick="verifyCode()">Verificar</button>
    </div>

    <div id="password-reset-container" class="password-reset-container">
        <h2 class="password-reset-title">Redefinir Senha</h2>
        <form id="password-reset-form" onsubmit="return resetPassword(event)">
            <div class="user-type-group">
                <label for="user-type">Usuário:</label>
                <input type="text" id="user-type" class="user-type-input" required>
            </div>
            <div class="password-input-group">
                <label for="new-password">Nova Senha:</label>
                <input type="password" id="new-password" class="password-input" required minlength="6">
            </div>
            <div class="password-input-group">
                <label for="confirm-password">Confirmar Nova Senha:</label>
                <input type="password" id="confirm-password" class="password-input" required minlength="6">
            </div>
            <div class="show-password-container">
                <input type="checkbox" id="show-password" onchange="togglePasswords()">
                <label for="show-password">Mostrar senha</label>
            </div>
            <button type="submit" class="btn-reset">Redefinir</button>
        </form>
    </div>

    <script>
        let generatedCode = '';
        const validUserTypes = ['gerente', 'repositor', 'bibliotecário', 'gestor', 'recreador'];
        
        (function(){
            emailjs.init('7aFFN1oE9Gtwzi-A6');
        })();

        function togglePasswords() {
            const showPassword = document.getElementById('show-password').checked;
            const newPassword = document.getElementById('new-password');
            const confirmPassword = document.getElementById('confirm-password');
            
            newPassword.type = showPassword ? 'text' : 'password';
            confirmPassword.type = showPassword ? 'text' : 'password';
        }

        function enviarEmail(event) {
            event.preventDefault();

            const email = document.getElementById('email').value;

            if (!email) {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Por favor, preencha o campo de e-mail.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#7076c9'
                });
                return;
            }

            generatedCode = Math.floor(10000 + Math.random() * 90000).toString();

            const templateParams = {
                to_email: email,
                passcode: generatedCode
            };

            emailjs.send('service_i4zyues', 'template_i9qd04m', templateParams)
                .then((response) => {
                    Swal.fire({
                        title: 'Sucesso!',
                        text: 'Código de verificação enviado!',
                        icon: 'success',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#7076c9'
                    }).then(() => {
                        document.getElementById('email-form').classList.add('hidden');
                        document.getElementById('verification-container').style.display = 'block';
                        setupVerificationInputs();
                    });
                }, (error) => {
                    Swal.fire({
                        title: 'Erro!',
                        text: 'E-mail não encontrado.',
                        icon: 'error',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#7076c9'
                    });
                });
        }

        function setupVerificationInputs() {
            const inputs = document.querySelectorAll('.verification-input');
            
            inputs.forEach((input, index) => {
                input.addEventListener('keyup', (e) => {
                    if (e.key >= '0' && e.key <= '9') {
                        input.value = e.key;
                        if (index < inputs.length - 1) {
                            inputs[index + 1].focus();
                        }
                    } else if (e.key === 'Backspace') {
                        input.value = '';
                        if (index > 0) {
                            inputs[index - 1].focus();
                        }
                    }
                });

                input.addEventListener('input', (e) => {
                    if (e.target.value.length > 1) {
                        e.target.value = e.target.value.slice(0, 1);
                    }
                });
            });
        }

        function verifyCode() {
            const inputs = document.querySelectorAll('.verification-input');
            const enteredCode = Array.from(inputs).map(input => input.value).join('');

            if (enteredCode === generatedCode) {
                Swal.fire({
                    title: 'Sucesso!',
                    text: 'Código verificado com sucesso!',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#7076c9'
                }).then(() => {
                    document.getElementById('verification-container').style.display = 'none';
                    document.getElementById('password-reset-container').style.display = 'block';
                });
            } else {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Código incorreto. Tente novamente.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#7076c9'
                });
            }
        }

        function resetPassword(event) {
            event.preventDefault();
            
            const userType = document.getElementById('user-type').value.toLowerCase().trim();
            const newPassword = document.getElementById('new-password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            if (!userType) {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Por favor, digite seu tipo de usuário.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#7076c9'
                });
                return false;
            }

            if (!validUserTypes.includes(userType)) {
                Swal.fire({
                    title: 'Erro!',
                    text: 'Nome de usuário inválido.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#7076c9'
                });
                return false;
            }

            if (newPassword !== confirmPassword) {
                Swal.fire({
                    title: 'Erro!',
                    text: 'As senhas não coincidem.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#7076c9'
                });
                return false;
            }

            if (newPassword.length < 6) {
                Swal.fire({
                    title: 'Erro!',
                    text: 'A senha deve ter pelo menos 6 caracteres.',
                    icon: 'error',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#7076c9'
                });
                return false;
            }

            // Aqui você pode adicionar a lógica para atualizar a senha no banco de dados
            Swal.fire({
                title: 'Sucesso!',
                text: 'Senha atualizada com sucesso!',
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#7076c9'
            }).then(() => {
                window.location.href = '../login.html';
            });

            return false;
        }

        document.getElementById('form_login').addEventListener('submit', enviarEmail);
    </script>
</body>
</html>