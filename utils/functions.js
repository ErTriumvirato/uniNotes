function handleButtonAction(button, path, bodyText, onSuccess) {
	if (button) button.disabled = true;

	const options = bodyText
		? { method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: bodyText }
		: {};

	fetch(path, options)
		.then((res) => res.json())
		.then((data) => {
			if (data.error === "login_required") {
				window.location.href = "login.php?redirect=" + encodeURIComponent(path);
				return;
			}
			onSuccess(data, button);
		})
		.catch((err) => {
			console.error(err);
			showError("Errore durante l'operazione. Riprova.");
		})
		.finally(() => {
			if (button) button.disabled = false;
		});
}
