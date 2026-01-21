"use strict";

// Helper functions (Templates)
function getReviewFormHTML(idappunto) {
	return `
        <div id="review-form-container">
            <h3 class="mb-3 h5">Lascia una recensione</h3>
            <form id="review-form" data-idappunto="${idappunto}">
                <div class="row g-2 align-items-end">
                    <div class="col-8 col-sm-6 col-md-4">
                        <label for="valutazione" class="form-label visually-hidden">Valutazione</label>
                        <select name="valutazione" id="valutazione" class="form-select text-center" required>
                            <option value="" selected disabled>Vota...</option>
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

function getLoginPromptHTML(loginUrl) {
	return `
        <p class="mb-0 text-muted">
            <a href="${loginUrl}">Accedi</a> per lasciare una recensione.
        </p>
    `;
}

// Funzione per gestire il submit del form di recensione
function handleReviewFormSubmit(e) {
	e.preventDefault();

	const form = e.target;
	const idappunto = form.dataset.idappunto;
	const valutazioneSelect = form.querySelector("#valutazione");
	const valutazione = valutazioneSelect ? valutazioneSelect.value : null;
	const submitBtn = form.querySelector('button[type="submit"]');

	if (!valutazione) {
		return;
	}

	handleButtonAction(submitBtn, `appunto.php?id=${idappunto}`, `valutazione=${encodeURIComponent(valutazione)}&ajax=1`, (data) => {
		if (data.success) {
			const container = document.getElementById("review-form-container");
			if (container) container.outerHTML = getReviewDisplayHTML(data.review, idappunto);

			const avgBadge = document.getElementById("avg-rating-badge");
			if (avgBadge) avgBadge.textContent = "★ " + data.new_avg + " (" + data.new_count + ")";
		}
	});
}

// Gestione eliminazione recensioni
function handleDeleteReview(btn) {
	const idrecensione = btn.dataset.reviewId;
	const reviewCard = document.getElementById("already-reviewed-container");

	if (!btn.dataset.confirm) {
		btn.dataset.confirm = "true";
		btn.innerHTML = '<em class="bi bi-check-lg"></em>';
		btn.classList.remove("btn-outline-danger");
		btn.classList.add("btn-danger");
		setTimeout(() => {
			if (btn && btn.dataset.confirm) {
				delete btn.dataset.confirm;
				btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em>';
				btn.classList.remove("btn-danger");
				btn.classList.add("btn-outline-danger");
			}
		}, 3000);
		return;
	}

	if (!reviewCard || !reviewCard.dataset.idappunto) {
		console.error("ID Appunto mancante nel container");
		return;
	}

	const idappunto = reviewCard.dataset.idappunto;

	handleButtonAction(btn, `appunto.php?id=${idappunto}`, `deleteReview=${encodeURIComponent(idrecensione)}&ajax=1`, (data) => {
		if (data.success) {
			setTimeout(() => {
				reviewCard.outerHTML = getReviewFormHTML(idappunto);

				const avgBadge = document.getElementById("avg-rating-badge");
				if (avgBadge) avgBadge.textContent = "★ " + data.new_avg + " (" + data.new_count + ")";

				const reviewsList = document.getElementById("reviews-list");
				if (reviewsList) reviewsList.innerHTML = '<div class="d-flex flex-column gap-3"></div>';
			}, 300);
		} else {
			showError(data.message || "Errore durante l'eliminazione della recensione.");
			btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em>';
		}
	});
}

// Handler per approvazione articolo
function handleApprove(id) {
	handleButtonAction(null, "appunti.php", `action=approve&idappunto=${id}`, (data) => {
		if (data.success) {
			const badge = document.getElementById("status-badge");
			if (badge) {
				badge.className = "badge bg-success";
				badge.textContent = "Approvato";
			}

			const adminActions = document.getElementById("admin-actions");
			if (adminActions) {
				adminActions.querySelector(".btn-approve")?.remove();
				adminActions.querySelector(".btn-reject")?.remove();
			}

			const reviewsSection = document.getElementById("reviews-section");
			if (reviewsSection) {
				reviewsSection.classList.remove("d-none");
				reviewsSection.style.display = "block";
			}

			showSuccess("Appunto approvato con successo");
		} else {
			showError("Errore durante l'approvazione");
		}
	});
}

// Handler per rifiuto articolo
function handleReject(id) {
	handleButtonAction(null, "appunti.php", `action=reject&idappunto=${id}`, (data) => {
		if (data.success) {
			const badge = document.getElementById("status-badge");
			if (badge) {
				badge.className = "badge bg-danger";
				badge.textContent = "Rifiutato";
			}

			const adminActions = document.getElementById("admin-actions");
			if (adminActions) {
				adminActions.querySelector(".btn-approve")?.remove();
				adminActions.querySelector(".btn-reject")?.remove();
			}

			showSuccess("Appunto rifiutato con successo");
		} else {
			showError("Errore durante il rifiuto");
		}
	});
}

// Resetta il bottone elimina allo stato iniziale
function resetDeleteNoteButton(btn) {
	delete btn.dataset.confirm;
	btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em>';
	btn.classList.replace("btn-danger", "btn-outline-danger");
}

// Handler per eliminazione articolo
function handleDeleteNote(btn) {
	const id = btn.dataset.id;

	if (!btn.dataset.confirm) {
		btn.dataset.confirm = "true";
		btn.innerHTML = '<em class="bi bi-check-lg"></em>';
		btn.classList.replace("btn-outline-danger", "btn-danger");
		setTimeout(() => {
			if (btn?.dataset.confirm) resetDeleteNoteButton(btn);
		}, 3000);
		return;
	}

	handleButtonAction(btn, "appunti.php", `action=delete&idappunto=${id}`, (data) => {
		if (data.success) {
			window.location.href = "gestione-appunti.php";
		} else {
			showError("Errore durante l'eliminazione");
			resetDeleteNoteButton(btn);
		}
	});
}

// Initialize User Review Section
	const container = document.getElementById("user-review-interaction");
	if (container && container.dataset.config) {
		try {
			const config = JSON.parse(container.dataset.config);

			if (!config.isLoggedIn) {
				container.innerHTML = getLoginPromptHTML(config.loginUrl);
			} else if (config.userReview) {
				container.innerHTML = getReviewDisplayHTML(config.userReview, config.idappunto);
			} else {
				container.innerHTML = getReviewFormHTML(config.idappunto);
			}

			// Event Delegation for dynamic elements
			container.addEventListener("submit", (e) => {
				if (e.target && e.target.id === "review-form") {
					handleReviewFormSubmit(e);
				}
			});

			container.addEventListener("click", (e) => {
				const btn = e.target.closest(".delete-review-btn");
				if (btn) {
					handleDeleteReview(btn);
				}
			});

		} catch (e) {
			console.error("Error parsing review config", e);
		}
	}

	// Listener diretti only for static elements (Note Admin Actions)
	document.querySelectorAll(".btn-approve").forEach((btn) => {
		btn.addEventListener("click", () => {
			const id = btn.dataset.id;
			if (id) handleApprove(id);
		});
	});

	document.querySelectorAll(".btn-reject").forEach((btn) => {
		btn.addEventListener("click", () => {
			const id = btn.dataset.id;
			if (id) handleReject(id);
		});
	});

	document.querySelectorAll(".btn-delete-note").forEach((btn) => {
		btn.addEventListener("click", () => handleDeleteNote(btn));
	});
