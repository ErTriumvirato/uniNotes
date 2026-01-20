"use strict";

document.addEventListener("DOMContentLoaded", () => {
	// Event Delegation
	document.body.addEventListener("submit", (e) => {
		if (e.target.id === "review-form") {
			handleReviewFormSubmit(e);
		}
	});

	document.body.addEventListener("click", (e) => {
		const target = e.target;

		// Delete Review Button
		const deleteReviewBtn = target.closest(".delete-review-btn");
		if (deleteReviewBtn) {
			handleDeleteReview(deleteReviewBtn);
			return;
		}

		// Approve Button
		const approveBtn = target.closest(".btn-approve");
		if (approveBtn) {
			const id = approveBtn.dataset.id;
			if (id) handleApprove(id);
			return;
		}

		// Reject Button
		const rejectBtn = target.closest(".btn-reject");
		if (rejectBtn) {
			const id = rejectBtn.dataset.id;
			if (id) handleReject(id);
			return;
		}

		// Delete Note Button
		const deleteNoteBtn = target.closest(".btn-delete-note");
		if (deleteNoteBtn) {
			handleDeleteNote(deleteNoteBtn);
			return;
		}
	});
});

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

	submitBtn.disabled = true;

	fetch("appunto.php?id=" + idappunto, {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded",
		},
		body: "valutazione=" + encodeURIComponent(valutazione) + "&ajax=1",
	})
		.then((res) => res.json())
		.then((data) => {
			if (data.success) {
				// Nascondi il form e mostra il messaggio
				const container = document.getElementById("review-form-container");
				if (container) {
					const stars = "★".repeat(data.review.valutazione) + "☆".repeat(5 - data.review.valutazione);
					container.outerHTML = `
                        <div id="already-reviewed-container" class="d-flex justify-content-between align-items-center bg-light p-3 rounded" data-review-id="${data.review.idrecensione}" data-idappunto="${idappunto}">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold">La tua recensione:</span>
                                <span class="text-warning">${stars}</span>
                            </div>
                            <button class="btn btn-sm btn-outline-danger delete-review-btn" data-review-id="${data.review.idrecensione}" aria-label="Elimina recensione" title="Elimina">
                                <em class="bi bi-trash" aria-hidden="true"></em>
                            </button>
                        </div>
                    `;
				}

				// Aggiorna la media delle recensioni
				const avgBadge = document.getElementById("avg-rating-badge");
				if (avgBadge) avgBadge.textContent = "★ " + data.new_avg + " (" + data.new_count + ")";
			}
		})
		.catch(() => {
			submitBtn.disabled = false;
			submitBtn.textContent = "Invia";
			if (typeof showError === "function") showError("Si è verificato un errore. Riprova.");
			else alert("Si è verificato un errore. Riprova.");
		});
}

// Gestione eliminazione recensioni
function handleDeleteReview(btn) {
	const idrecensione = btn.dataset.reviewId;
	const reviewCard = btn.closest("#already-reviewed-container");

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
	btn.disabled = true;

	fetch("appunto.php?id=" + idappunto, {
		method: "POST",
		headers: {
			"Content-Type": "application/x-www-form-urlencoded",
		},
		body: "deleteReview=" + encodeURIComponent(idrecensione) + "&ajax=1",
	})
		.then((res) => res.json())
		.then((data) => {
			if (data.success) {
				setTimeout(() => {
					reviewCard.outerHTML = `
                        <div id="review-form-container">
                            <h3 class="mb-3 h5">Lascia una recensione</h3>
                            <form id="review-form" data-idappunto="${idappunto}">
                                <div class="row g-2 align-items-end">
                                    <div class="col-8 col-sm-6 col-md-4">
                                        <label for="valutazione" class="form-label visually-hidden">Valutazione</label>
                                        <select name="valutazione" id="valutazione" class="form-select text-center" required>
                                            <option value="" selected disabled>Vota...</option>
                                            <option value="5">5 ★★★★★</option>
                                            <option value="4">4 ★★★★</option>
                                            <option value="3">3 ★★★</option>
                                            <option value="2">2 ★★</option>
                                            <option value="1">1 ★</option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary">Invia</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    `;

					// Aggiorna la media delle recensioni
					const avgBadge = document.getElementById("avg-rating-badge");
					if (avgBadge) avgBadge.textContent = "★ " + data.new_avg + " (" + data.new_count + ")";

					// Puliamo la lista recensioni visuale in ogni caso
					const reviewsList = document.getElementById("reviews-list");
					if (reviewsList) {
						reviewsList.innerHTML = '<div class="d-flex flex-column gap-3"></div>';
					}
				}, 300);
			} else {
				if (typeof showError === "function") showError(data.message || "Errore durante l'eliminazione della recensione.");
				else alert(data.message || "Errore durante l'eliminazione della recensione.");
				btn.disabled = false;
				btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em>';
			}
		})
		.catch(() => {
			btn.disabled = false;
			btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em>';
			if (typeof showError === "function") showError("Si è verificato un errore. Riprova.");
			else alert("Si è verificato un errore. Riprova.");
		});
}

// Handler per approvazione articolo
function handleApprove(id) {
	const formData = new FormData();
	formData.append("action", "approve");
	formData.append("idappunto", id);

	fetch("appunti.php", {
		method: "POST",
		body: formData,
	})
		.then((res) => res.json())
		.then((data) => {
			if (data.success) {
				// Aggiorna il badge
				const badge = document.getElementById("status-badge");
				if (badge) {
					badge.className = "badge bg-success";
					badge.textContent = "Approvato";
				}
				// Rimuovi i pulsanti approva/rifiuta
				const adminActions = document.getElementById("admin-actions");
				if (adminActions) {
					const approveBtn = adminActions.querySelector(".btn-approve");
					const rejectBtn = adminActions.querySelector(".btn-reject");
					if (approveBtn) approveBtn.remove();
					if (rejectBtn) rejectBtn.remove();
				}

				// Mostra la sezione recensioni
				const reviewsSection = document.getElementById("reviews-section");
				if (reviewsSection) {
					reviewsSection.classList.remove("d-none");
					reviewsSection.style.display = "block";
				}

				if (typeof showSuccess === "function") showSuccess("Appunto approvato con successo");
			} else {
				if (typeof showError === "function") showError("Errore durante l'approvazione");
			}
		})
		.catch(() => {
			if (typeof showError === "function") showError("Errore di connessione");
		});
}

// Handler per rifiuto articolo
function handleReject(id) {
	const formData = new FormData();
	formData.append("action", "reject");
	formData.append("idappunto", id);

	fetch("appunti.php", {
		method: "POST",
		body: formData,
	})
		.then((res) => res.json())
		.then((data) => {
			if (data.success) {
				// Aggiorna il badge
				const badge = document.getElementById("status-badge");
				if (badge) {
					badge.className = "badge bg-danger";
					badge.textContent = "Rifiutato";
				}
				// Rimuovi i pulsanti approva/rifiuta
				const adminActions = document.getElementById("admin-actions");
				if (adminActions) {
					const approveBtn = adminActions.querySelector(".btn-approve");
					const rejectBtn = adminActions.querySelector(".btn-reject");
					if (approveBtn) approveBtn.remove();
					if (rejectBtn) rejectBtn.remove();
				}
				if (typeof showSuccess === "function") showSuccess("Appunto rifiutato con successo");
			} else {
				if (typeof showError === "function") showError("Errore durante il rifiuto");
			}
		})
		.catch(() => {
			if (typeof showError === "function") showError("Errore di connessione");
		});
}

// Handler per eliminazione articolo
function handleDeleteNote(btn) {
	const id = btn.dataset.id;

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

	btn.disabled = true;

	const formData = new FormData();
	formData.append("action", "delete");
	formData.append("idappunto", id);

	fetch("appunti.php", {
		method: "POST",
		body: formData,
	})
		.then((res) => res.json())
		.then((data) => {
			if (data.success) {
				window.location.href = "gestione-appunti.php";
			} else {
				if (typeof showError === "function") showError("Errore durante l'eliminazione");
				btn.disabled = false;
				btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em>';
				delete btn.dataset.confirm;
				btn.classList.remove("btn-danger");
				btn.classList.add("btn-outline-danger");
			}
		})
		.catch(() => {
			if (typeof showError === "function") showError("Errore di connessione");
			btn.disabled = false;
			btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em>';
			delete btn.dataset.confirm;
			btn.classList.remove("btn-danger");
			btn.classList.add("btn-outline-danger");
		});
}
