// Funzione generica per mostrare un banner
function showBanner(message, type) {
	const banner = document.getElementById("error-banner"); // Banner usato sia per errori che per successi
	const msgSpan = document.getElementById("error-message"); // Span per il messaggio
	msgSpan.textContent = message; // Imposta il messaggio

	// Rimuove le classi di tipo precedenti e aggiunge quella nuova
	banner.classList.remove("alert-danger", "alert-success");
	banner.classList.add("alert-" + type);

	// Mostra il banner
	banner.classList.remove("is-hidden");
	banner.classList.add("show");
}

// Nasconde il banner di errore
function hideBanner() {
	const banner = document.getElementById("error-banner"); // Banner usato sia per errori che per successi
	banner.classList.remove("show"); // Rimuove la classe show
	banner.classList.add("is-hidden"); // Aggiunge la classe is-hidden
}

// Mostra banner di errore
function showError(message) {
	showBanner(message, "danger");
}

// Mostra banner di successo
function showSuccess(message) {
	showBanner(message, "success");
}

// Funzione per tornare alla pagina precedente
function goBack() {
	const referrer = document.referrer; // Ottiene il referrer (pagina precedente)
	const currentDomain = window.location.origin; // Ottiene il dominio corrente
	const currentUrl = window.location.href; // Ottiene l'URL corrente

	if (
		// Controlla se il referrer è dello stesso dominio e non è una delle pagine principali
		referrer &&
		referrer.startsWith(currentDomain) &&
		currentUrl !== currentDomain &&
		currentUrl !== currentDomain + "/" &&
		currentUrl !== currentDomain + "/index.php" &&
		currentUrl !== referrer
	) {
		history.back(); // Torna alla pagina precedente
	} else if (!referrer.startsWith(currentDomain)) {
		window.location.href = "/"; // Se il referrer è esterno, va alla home
	}
}

// Funzione per gestire il menu utente
function toggleUserMenu() {
	const dropdown = document.getElementById("user-dropdown");
	const btn = document.querySelector(".user-menu-btn");
	const isOpen = dropdown.classList.toggle("open");
	btn.setAttribute("aria-expanded", isOpen);
}

// Gestione chiusura banner dei cookie
function closeCookieBanner() {
	// Invia richiesta per chiudere il banner dei cookie
	handleButtonAction(null, "index.php", "action=closeCookieBanner", () => {
		document.getElementById("cookie-banner").classList.add("is-hidden");
	});
}

// Gestione di qualsiasi richiesta AJAX legata a un bottone
function handleButtonAction(button = null, path = "", bodyText = null, onSuccess = null) {
	const options = bodyText
		? { method: "POST", headers: { "Content-Type": "application/x-www-form-urlencoded" }, body: bodyText }
		: {};

	// Esegue la richiesta AJAX
	fetch(path, options)
		.then((res) => res.json()) // Converte la risposta in JSON
		.then((data) => {
			// Gestisce la risposta
			if (data.error === "login_required") {
				// Se è richiesto il login
				window.location.href = "login.php?redirect=" + encodeURIComponent(path); // Reindirizza alla pagina di login
				return;
			}
			onSuccess(data, button); // Esegue la funzione di successo passata come parametro
		})
		.catch((e) => {
			showError("Errore durante l'operazione: " + e.message); // Mostra errore generico in caso di fallimento
		});
}

// Aggiunge gli event listener ai bottoni
const userMenuBtn = document.querySelector(".user-menu-btn");
if (userMenuBtn) userMenuBtn.addEventListener("click", toggleUserMenu);

const errorCloseBtn = document.getElementById("btn-close-error");
if (errorCloseBtn) errorCloseBtn.addEventListener("click", hideBanner);

const cookieAcceptBtn = document.getElementById("cookie-accept");
if (cookieAcceptBtn) cookieAcceptBtn.addEventListener("click", closeCookieBanner);

const backBtn = document.getElementById("btn-back");
if (backBtn) backBtn.addEventListener("click", goBack);
