"use strict";

const serverMessage = document.getElementById("server-message");
if (serverMessage) {
	const message = serverMessage.dataset.message;
	const type = serverMessage.dataset.type;
	if (type === "danger") {
		showError(message);
	}
}