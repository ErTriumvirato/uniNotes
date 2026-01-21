const serverMessage = document.getElementById("server-message"); // Seleziona l'elemento che contiene il messaggio del server

if (serverMessage) {
	const message = serverMessage.dataset.message; // Estrae il messaggio
	const type = serverMessage.dataset.type; // Estrae il tipo di messaggio ("danger", "success")
	if (type === "danger") {
		showError(message); // Mostra il banner di errore
	}
}
