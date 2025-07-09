document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('form_login');

  // Função para mostrar popup de erro
  function showError(message) {
    // Remove popup anterior se existir
    const existingPopup = document.querySelector('.error-popup');
    if (existingPopup) {
      existingPopup.remove();
    }

    // Cria o popup
    const popup = document.createElement('div');
    popup.className = 'error-popup';
    popup.style.cssText = `
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #fff;
      padding: 15px 25px;
      border-radius: 8px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      display: flex;
      align-items: center;
      gap: 10px;
      z-index: 1000;
      animation: slideIn 0.3s ease-out;
      border-left: 4px solid #ff4444;
    `;

    // Ícone de erro
    const icon = document.createElement('span');
    icon.innerHTML = '❌';
    icon.style.fontSize = '20px';

    // Mensagem de erro
    const messageDiv = document.createElement('div');
    messageDiv.textContent = message;
    messageDiv.style.color = '#333';
    messageDiv.style.fontSize = '14px';

    // Adiciona elementos ao popup
    popup.appendChild(icon);
    popup.appendChild(messageDiv);

    // Adiciona o popup ao body
    document.body.appendChild(popup);

    // Adiciona animação de saída
    setTimeout(() => {
      popup.style.animation = 'slideOut 0.3s ease-in forwards';
      setTimeout(() => {
        popup.remove();
      }, 300);
    }, 3000);
  }

  // Adiciona estilos de animação
  const style = document.createElement('style');
  style.textContent = `
    @keyframes slideIn {
      from {
        transform: translateX(100%);
        opacity: 0;
      }
      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
    @keyframes slideOut {
      from {
        transform: translateX(0);
        opacity: 1;
      }
      to {
        transform: translateX(100%);
        opacity: 0;
      }
    }
  `;
  document.head.appendChild(style);

  form.addEventListener('submit', function (e) {
    e.preventDefault();

    const formData = new FormData(form);
    const usuario = formData.get('usuario');
    const senha = formData.get('senha');

    if (usuario === 'gerente' && senha === 'gerente') {
      window.location.href = 'gerente.html';
    } else if (usuario === 'bibliotecario' && senha === 'bibliotecario') {
      window.location.href = 'bibliotecario.html';
    } else if (usuario === 'recreador' && senha === 'recreador') {
      window.location.href = 'recreador.html';
    } else if (usuario === 'repositor' && senha === 'repositor') {
      window.location.href = 'repositor.html';
    } else if (usuario === 'gestor' && senha === 'gestor') {
      window.location.href = 'gestor.html';
    } else {
      showError('Usuário ou senha incorretos. Por favor, tente novamente.');
      form.reset();
    }
  });
});
