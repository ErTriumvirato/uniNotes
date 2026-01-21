// Genera l'HTML per il form di recensione
function getReviewFormHTML(idappunto) {
	return `
        <div id="review-form-container">
            <h3 class="mb-3 h5">Lascia una recensione</h3>
            <form id="review-form" data-idappunto="${idappunto}">
                <div class="row g-2 align-items-end">
                    <div class="col-8 col-sm-6 col-md-4">
                        <label for="valutazione" class="form-label visually-hidden">Valutazione</label>
                        <select name="valutazione" id="valutazione" class="form-select text-center" required>
                            <option value="" selected disabled>Vota</option>
                            <option value="5">5 - Eccellente</option>
                            <option value="4">4 - Molto Buono</option>
                            <option value="3">3 - Buono</option>
                            <option value="2">2 - Sufficiente</option>
                            <option value="1">1 - Insufficiente</option>
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Invia</button>
                    </div>
                </div>
            </form>
        </div>
    `;
}

// Genera l'HTML per la visualizzazione della recensione lasciata in precedenza dall'utente
function getReviewDisplayHTML(review, idappunto) {
	const stars = "★".repeat(review.valutazione) + "☆".repeat(5 - review.valutazione);
	return `
        <div id="already-reviewed-container" class="d-flex justify-content-between align-items-center bg-light p-3 rounded" data-review-id="${review.idrecensione}" data-idappunto="${idappunto}">
            <div class="d-flex align-items-center gap-2">
                <span class="fw-bold">La tua recensione:</span>
                <span class="text-warning">${stars}</span>
            </div>
            <button class="btn btn-sm btn-outline-danger delete-review-btn" data-review-id="${review.idrecensione}" aria-label="Elimina recensione" title="Elimina">
                <em class="bi bi-trash" aria-hidden="true"></em>
            </button>
        </div>
    `;
}

// Genera l'HTML per il prompt di login (se l'utente non è loggato)
function getLoginPromptHTML(loginUrl) {
	return `
        <p class="mb-0 text-muted">
            <a href="${loginUrl}">Accedi</a> per lasciare una recensione.
        </p>
    `;
}

// Funzione per gestire l'aggiunta di una recensione
function handleReviewFormSubmit(e) {
	e.preventDefault(); // Impedisce il comportamento di submit predefinito (non ricarica la pagina)

	const form = e.target; // Ottiene il form
	const idappunto = form.dataset.idappunto; // ID dell'appunto dal dataset del form
	const valutazioneSelect = form.querySelector("#valutazione"); // Select della valutazione
	const valutazione = valutazioneSelect.value; // Valutazione selezionata
	const submitBtn = form.querySelector('button[type="submit"]'); // Bottone di submit

	// Invia la richiesta AJAX per aggiungere la recensione (definita in base.js)
	handleButtonAction(
		submitBtn,
		`appunto.php?id=${idappunto}`,
		`valutazione=${encodeURIComponent(valutazione)}&ajax=1`,
		(data) => {
			if (data.success) {
				// Se la recensione è stata aggiunta con successo
				const container = document.getElementById("review-form-container"); // Contenitore del form
				container.outerHTML = getReviewDisplayHTML(data.review, idappunto); // Sostituisce il form con la visualizzazione della recensione

				const avgBadge = document.getElementById("avg-rating-badge"); // Badge della valutazione media
				avgBadge.textContent = "★ " + data.new_avg + " (" + data.new_count + ")"; // Aggiorna la valutazione media

				const deleteBtn = document.querySelector(".delete-review-btn");
				if (deleteBtn) {
					deleteBtn.addEventListener("click", () => handleDeleteReview(deleteBtn));
				}
			}
		},
	);
}

// Gestione eliminazione recensioni
function handleDeleteReview(btn) {
	const idrecensione = btn.dataset.reviewId; // ID della recensione da eliminare
	const reviewCard = document.getElementById("already-reviewed-container"); // Contenitore della recensione

	if (!btn.dataset.confirm) {
		// Se non è stato confermato
		btn.dataset.confirm = "true"; // Imposta il flag di conferma
		btn.innerHTML = '<em class="bi bi-check-lg"></em>'; // Cambia l'icona del bottone
		btn.classList.remove("btn-outline-danger"); // Rimuove la classe di stile originale
		btn.classList.add("btn-danger"); // Aggiunge la classe di stile di conferma
		setTimeout(() => {
			// Resetta il bottone dopo 3 secondi se non confermato
			if (btn.dataset.confirm) {
				// Se il flag di conferma è ancora presente
				delete btn.dataset.confirm; // Rimuove il flag di conferma
				btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em>'; // Ripristina l'icona originale
				btn.classList.remove("btn-danger"); // Rimuove la classe di stile di conferma
				btn.classList.add("btn-outline-danger"); // Ripristina la classe di stile originale
			}
		}, 3000);
		return;
	}

	const idappunto = reviewCard.dataset.idappunto; // ID dell'appunto dal dataset del contenitore della recensione

	// Invia la richiesta AJAX per eliminare la recensione (definita in base.js)
	handleButtonAction(btn, `appunto.php?id=${idappunto}`, `deleteReview=${encodeURIComponent(idrecensione)}&ajax=1`, (data) => {
		if (data.success) {
			setTimeout(() => {
				reviewCard.outerHTML = getReviewFormHTML(idappunto); // Sostituisce la recensione con il form di recensione

				const avgBadge = document.getElementById("avg-rating-badge");
				if (avgBadge) avgBadge.textContent = "★ " + data.new_avg + " (" + data.new_count + ")";

				const reviewsList = document.getElementById("reviews-list");
				if (reviewsList) reviewsList.innerHTML = '<div class="d-flex flex-column gap-3"></div>';

				const newForm = document.getElementById("review-form");
				if (newForm) {
					newForm.addEventListener("submit", handleReviewFormSubmit);
				}
			}, 300);
		} else {
			// Se c'è stato un errore durante l'eliminazione
			showError(data.message || "Errore durante l'eliminazione della recensione."); // Mostra un messaggio di errore
			btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em>'; // Ripristina l'icona originale
		}
	});
}

// Approvazione articolo (per admin)
function handleApprove(id) {
	// Invia la richiesta AJAX per approvare l'appunto (definita in base.js)
	handleButtonAction(null, "appunti.php", `action=approve&idappunto=${id}`, (data) => {
		if (data.success) {
			// Se l'approvazione è andata a buon fine
			const badge = document.getElementById("status-badge");
			badge.className = "badge bg-success"; // Cambia la classe del badge di stato
			badge.textContent = "Approvato"; // Aggiorna il testo del badge di stato

			// Rimuove i bottoni di approvazione/rifiuto
			const adminActions = document.getElementById("admin-actions"); // Contenitore delle azioni admin
			adminActions.querySelector(".btn-approve")?.remove();
			adminActions.querySelector(".btn-reject")?.remove();

			// Mostra la sezione delle recensioni
			const reviewsSection = document.getElementById("reviews-section");
			reviewsSection.classList.remove("d-none");
			reviewsSection.style.display = "block";

			showSuccess("Appunto approvato con successo"); // Mostra un messaggio di successo
		} else {
			showError("Errore durante l'approvazione"); // Mostra un messaggio di errore
		}
	});
}

// Rifiuto articolo
function handleReject(id) {
	// Invia la richiesta AJAX per rifiutare l'appunto (definita in base.js)
	handleButtonAction(null, "appunti.php", `action=reject&idappunto=${id}`, (data) => {
		if (data.success) {
			// Se il rifiuto è andato a buon fine
			// Aggiorna il badge di stato
			const badge = document.getElementById("status-badge");
			badge.className = "badge bg-danger";
			badge.textContent = "Rifiutato";

			// Rimuove i bottoni di approvazione/rifiuto
			const adminActions = document.getElementById("admin-actions");
			adminActions.querySelector(".btn-approve")?.remove();
			adminActions.querySelector(".btn-reject")?.remove();

			showSuccess("Appunto rifiutato con successo"); // Mostra un messaggio di successo
		} else {
			showError("Errore durante il rifiuto"); // Mostra un messaggio di errore
		}
	});
}

// Resetta il bottone elimina allo stato iniziale (cestino)
function resetDeleteNoteButton(btn) {
	delete btn.dataset.confirm; // Rimuove il flag di conferma
	btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em>'; // Ripristina l'icona originale (cestino)
	btn.classList.replace("btn-danger", "btn-outline-danger"); // Ripristina la classe di stile originale
}

// Eliminazione articolo
function handleDeleteNote(btn) {
	const id = btn.dataset.id; // ID dell'appunto da eliminare

	if (!btn.dataset.confirm) {
		// Se non è stato confermato
		btn.dataset.confirm = "true"; // Imposta il flag di conferma
		btn.innerHTML = '<em class="bi bi-check-lg"></em>'; // Cambia l'icona del bottone (check)
		btn.classList.replace("btn-outline-danger", "btn-danger"); // Cambia la classe di stile per indicare conferma
		setTimeout(() => {
			// Resetta il bottone dopo 3 secondi se non confermato
			if (btn?.dataset.confirm) resetDeleteNoteButton(btn);
		}, 3000);
		return;
	}

	// Invia la richiesta AJAX per eliminare l'appunto (definita in base.js)
	handleButtonAction(btn, "appunti.php", `action=delete&idappunto=${id}`, (data) => {
		if (data.success) {
			window.location.href = "gestione-appunti.php";
		} else {
			showError("Errore durante l'eliminazione");
			resetDeleteNoteButton(btn);
		}
	});
}

// Inizializzazione del modulo di recensioni
const container = document.getElementById("user-review-interaction"); // Contenitore del modulo di recensioni
if (container.dataset.config) {
	try {
		const config = JSON.parse(container.dataset.config); // Configurazione passata dal server (dettagli-appunto.php)

		if (!config.isLoggedIn) {
			// Se l'utente non è loggato
			container.innerHTML = getLoginPromptHTML(config.loginUrl); // Mostra il prompt di login
		} else if (config.userReview) {
			// Se l'utente ha già lasciato una recensione
			container.innerHTML = getReviewDisplayHTML(config.userReview, config.idappunto); // Mostra la recensione lasciata
			const deleteBtn = container.querySelector(".delete-review-btn"); // Bottone di eliminazione recensione
			deleteBtn.addEventListener("click", () => handleDeleteReview(deleteBtn)); // Aggiunge il listener per l'eliminazione della recensione
		} else {
			// Se l'utente non ha ancora lasciato una recensione
			container.innerHTML = getReviewFormHTML(config.idappunto); // Mostra il form di recensione
			const form = container.querySelector("#review-form"); // Form di recensione
			form.addEventListener("submit", handleReviewFormSubmit); // Aggiunge il listener per il submit del form
		}
	} catch (e) {
		showError("Errore nel caricamento del modulo di recensione."); // Mostra un messaggio di errore
	}
}

// Aggiunge i listener per i bottoni di approvazione
document.querySelectorAll(".btn-approve").forEach((btn) => {
	btn.addEventListener("click", () => {
		const id = btn.dataset.id;
		handleApprove(id);
	});
});

// Aggiunge i listener per i bottoni di rifiuto
document.querySelectorAll(".btn-reject").forEach((btn) => {
	btn.addEventListener("click", () => {
		const id = btn.dataset.id;
		handleReject(id);
	});
});

// Aggiunge i listener per i bottoni di eliminazione appunto
document.querySelectorAll(".btn-delete-note").forEach((btn) => {
	btn.addEventListener("click", () => handleDeleteNote(btn));
});
