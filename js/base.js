"use strict";

// Funzione generica per mostrare un banner
function showBanner(message, type) {
	const banner = document.getElementById("error-banner");
	const msgSpan = document.getElementById("error-message");
	msgSpan.textContent = message;

	banner.classList.remove("alert-danger", "alert-success");
	banner.classList.add("alert-" + type);

	banner.classList.remove("is-hidden");
	banner.classList.add("show");
}

// Mostra banner di errore
function showError(message) {
	showBanner(message, "danger");
}

// Mostra banner di successo
function showSuccess(message) {
	showBanner(message, "success");
}

// Nasconde il banner di errore
function hideError() {
	const banner = document.getElementById("error-banner");
	banner.classList.remove("show");
	banner.classList.add("is-hidden");
}

// Funzione per tornare alla pagina precedente
function goBack() {
	const referrer = document.referrer;
	const currentDomain = window.location.origin;
	const currentUrl = window.location.href;

	if (
		referrer &&
		referrer.startsWith(currentDomain) &&
		currentUrl !== currentDomain &&
		currentUrl !== currentDomain + "/" &&
		currentUrl !== currentDomain + "/index.php" &&
		currentUrl !== referrer
	) {
		history.back();
	} else if (!referrer.startsWith(currentDomain)) {
		window.location.href = "/";
	}
}

// Funzione per gestire il menu utente
function toggleUserMenu() {
	const dropdown = document.getElementById("user-dropdown");
	const btn = document.querySelector(".user-menu-btn");
	const isOpen = dropdown.classList.toggle("open");
	btn.setAttribute("aria-expanded", isOpen);
}

function closeCookieBanner() {
	fetch("index.php", {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded",
		},
		body: "action=closeCookieBanner",
	}).then(() => {
		document.getElementById("cookie-banner").classList.add("is-hidden");
	});
}

// Gestione delle azioni dei bottoni
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

document.addEventListener("DOMContentLoaded", () => {
	const userMenuBtn = document.querySelector(".user-menu-btn");
	if (userMenuBtn) userMenuBtn.addEventListener("click", toggleUserMenu);

	const errorCloseBtn = document.getElementById("btn-close-error");
	if (errorCloseBtn) errorCloseBtn.addEventListener("click", hideError);

	const cookieAcceptBtn = document.getElementById("cookie-accept");
	if (cookieAcceptBtn) cookieAcceptBtn.addEventListener("click", closeCookieBanner);

	const backBtn = document.getElementById("btn-back");
	if (backBtn) backBtn.addEventListener("click", goBack);
});
