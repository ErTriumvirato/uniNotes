"use strict";

document.addEventListener('DOMContentLoaded', function () {
    const serverMessage = document.getElementById('server-message');
    if (serverMessage) {
        const message = serverMessage.dataset.message;
        const type = serverMessage.dataset.type;
        if (type === 'success') {
            showSuccess(message);
        } else {
            showError(message);
        }
    }
});

function handleDeleteAccount(btn) {
    if (!btn.dataset.confirm) {
        btn.dataset.confirm = 'true';
        btn.textContent = 'Conferma eliminazione';
        btn.classList.remove('btn-outline-danger');
        btn.classList.add('btn-danger');
        setTimeout(() => {
            if (btn.dataset.confirm) {
                delete btn.dataset.confirm;
                btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i> Elimina account';
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-danger');
            }
        }, 3000);
        return;
    }
    document.getElementById('deleteAccountForm').submit();
}