const serverMessage = document.getElementById("server-message"); // Messaggio dal server (di successo o errore)

if (serverMessage) {
	const message = serverMessage.dataset.message;
	const type = serverMessage.dataset.type;
	if (type === "success") {
		showSuccess(message); // Banner di successo
	} else {
		showError(message); // Banner di errore
	}
}

// Aggiunge il listener al pulsante di eliminazione account
const btnDeleteAccount = document.getElementById("btn-delete-account");
btnDeleteAccount.addEventListener("click", () => handleDeleteAccount(btnDeleteAccount));

// Eliminazione account con conferma
function handleDeleteAccount(btn) {
	if (!btn.dataset.confirm) {
		// Se non è ancora stato confermato (è richiesta la conferma)
		btn.dataset.confirm = "true"; // Imposta il flag di conferma
		btn.textContent = "Conferma eliminazione"; // Cambia il testo del pulsante
		btn.classList.remove("btn-outline-danger"); // Rimuove lo stile precedente
		btn.classList.add("btn-danger"); // Cambia lo stile del pulsante
		setTimeout(() => {
			// Dopo 3 secondi, resetta il pulsante se non è stato cliccato di nuovo
			if (btn.dataset.confirm) {
				delete btn.dataset.confirm; // Rimuove il flag di conferma
				btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em> Elimina account'; // Ripristina il testo originale
				btn.classList.remove("btn-danger"); // Rimuove lo stile di conferma
				btn.classList.add("btn-outline-danger"); // Ripristina lo stile originale
			}
		}, 3000);
		return;
	}
	document.getElementById("deleteAccountForm").submit(); // Invia il modulo di eliminazione account
}
