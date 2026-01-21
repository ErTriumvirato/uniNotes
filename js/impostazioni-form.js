"use strict";

const serverMessage = document.getElementById("server-message");
if (serverMessage) {
	const message = serverMessage.dataset.message;
	const type = serverMessage.dataset.type;
	if (type === "success") {
		showSuccess(message);
	} else {
		showError(message);
	}
}

const btnDeleteAccount = document.getElementById("btn-delete-account");
if (btnDeleteAccount) {
	btnDeleteAccount.addEventListener("click", () => handleDeleteAccount(btnDeleteAccount));
}

function handleDeleteAccount(btn) {
	if (!btn.dataset.confirm) {
		btn.dataset.confirm = "true";
		btn.textContent = "Conferma eliminazione";
		btn.classList.remove("btn-outline-danger");
		btn.classList.add("btn-danger");
		setTimeout(() => {
			if (btn.dataset.confirm) {
				delete btn.dataset.confirm;
				btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em> Elimina account';
				btn.classList.remove("btn-danger");
				btn.classList.add("btn-outline-danger");
			}
		}, 3000);
		return;
	}
	document.getElementById("deleteAccountForm").submit();
}
