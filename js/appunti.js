const sortSelect = document.getElementById("ajax-sort"); // "Ordina per"
const orderSelect = document.getElementById("ajax-order"); // "Crescente/Decrescente"
const searchInput = document.getElementById("ajax-search"); // Casella di ricerca
const approvalSelect = document.getElementById("ajax-approval"); // Per selezionare solo appunti approvati/in revisione/rifiutati

const container = document.getElementById("notes-container"); // Container dove inserire gli appunti
const idutente = container.dataset.idutente ?? ""; // Filtra per utente se specificato
const idcorso = container.dataset.idcorso ?? ""; // Filtra per corso se specificato
const defaultApprovalFilter = approvalSelect ? approvalSelect.value : "approved"; // Filtro di approvazione di default
const defaultMessage = container.dataset.defaultMessage || "Nessun appunto disponibile."; // Messaggio di default se non ci sono appunti
const showActions = approvalSelect; // Mostra i bottoni di azione se l'utente è un amministratore

searchInput.addEventListener("input", updateNotes); // Aggiorna al cambiamento del testo della casella di ricerca

// Aggiunge i listener ai pulsanti di azione
function attachActionListeners() {
	// Listener per il pulsante di approvazione
	container.querySelectorAll(".btn-action-approve").forEach((btn) => {
		btn.addEventListener("click", () => handleApprove(btn.dataset.id));
	});
	// Listener per il pulsante di rifiuto
	container.querySelectorAll(".btn-action-reject").forEach((btn) => {
		btn.addEventListener("click", () => handleReject(btn.dataset.id));
	});
	// Listener per il pulsante di eliminazione
	container.querySelectorAll(".btn-action-delete").forEach((btn) => {
		btn.addEventListener("click", () => handleDelete(btn));
	});
}

attachActionListeners();

// Aggiunge i bottoni di azione se l'utente è un amministratore
function renderActionButtons(el) {
	if (!showActions) return ""; // Non mostrare i bottoni di azione se non è un amministratore
	let buttons = "";
	if (el.stato === "in_revisione") {
		// Mostra i bottoni di approvazione/rifiuto solo se lo stato è "in_revisione"
		buttons += `
            <button type="button" class="btn btn-sm btn-outline-success btn-action-approve" data-id="${el.idappunto}" title="Approva" aria-label="Approva appunto">
                <em class="bi bi-check-lg" aria-hidden="true"></em>
                <span class="visually-hidden">Approva</span>
            </button>
            <button type="button" class="btn btn-sm btn-outline-warning btn-action-reject" data-id="${el.idappunto}" title="Rifiuta" aria-label="Rifiuta appunto">
                <em class="bi bi-x-lg" aria-hidden="true"></em>
                <span class="visually-hidden">Rifiuta</span>
            </button>`;
	}
	// Bottone di eliminazione
	buttons += `
        <button type="button" class="btn btn-sm btn-outline-danger btn-action-delete" data-id="${el.idappunto}" title="Elimina" aria-label="Elimina appunto">
            <em class="bi bi-trash" aria-hidden="true"></em>
            <span class="visually-hidden">Elimina</span>
        </button>`;
	return `<div class="d-flex gap-2 mt-3 justify-content-end">${buttons}</div>`;
}

// Aggiorna gli appunti
function updateNotes() {
	const searchValue = searchInput.value.trim(); // Testo della barra di ricerca
	const approvalValue = approvalSelect ? approvalSelect.value : defaultApprovalFilter;

	// Costruisci l'URL con i parametri di filtro
	let url = `appunti.php?action=filter&sort=${encodeURIComponent(sortSelect.value)}&order=${encodeURIComponent(orderSelect.value)}`;
	if (idutente !== "") url += `&idutente=${encodeURIComponent(idutente)}`;
	if (idcorso !== "") url += `&idcorso=${encodeURIComponent(idcorso)}`;
	if (searchValue !== "") url += `&search=${encodeURIComponent(searchValue)}`;
	url += `&approval=${encodeURIComponent(approvalValue)}`;

	// Effettua la richiesta AJAX (definita in base.js)
	handleButtonAction(null, url, null, (data) => {
		container.innerHTML = "";
		if (data.length === 0) {
			container.innerHTML = `<div class="alert alert-info text-center" role="alert">${defaultMessage}</div>`;
			return;
		}
		data.forEach((el) => {
			// Per ogni appunto ricevuto
			let statusBadge = "";
			if (showActions) {
				// Mappa degli stati agli stili e etichette
				const statusMap = {
					in_revisione: {
						label: "Da approvare",
						class: "bg-warning text-dark",
					},
					approvato: {
						label: "Approvato",
						class: "bg-success",
					},
					rifiutato: {
						label: "Rifiutato",
						class: "bg-danger",
					},
				};
				// Ottieni le informazioni sullo stato
				const statusInfo = statusMap[el.stato] || {
					label: el.stato,
					class: "bg-secondary",
				};

				// Crea il badge di stato
				statusBadge = `<span class="badge ${statusInfo.class}" title="Stato">${statusInfo.label}</span>`;
			}

			// Aggiungi l'appunto al container
			container.innerHTML += `
                <article class="card shadow-sm border-0 note-card" id="note-${el.idappunto}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h3 class=" h3 card-title mb-1">
                                    <a href="appunto.php?id=${el.idappunto}" class="text-decoration-none note-title-link ${showActions ? "" : "stretched-link"}">${el.titolo}</a>
                                </h3>
                                <p class="card-text text-muted small mb-2">di ${el.autore}</p>
                                <div class="d-flex gap-2 row-gap-2 align-items-center flex-wrap mt-2">
                                    ${statusBadge}
                                    <span class="badge bg-light text-dark border" title="Media recensioni">★ ${el.media_recensioni || "N/A"}</span>
                                    <span class="badge bg-light text-dark border" title="Visualizzazioni">${el.numero_visualizzazioni} Visualizzazioni</span>
                                    <span class="badge bg-light text-dark border" title="Data pubblicazione">${el.data_formattata}</span>
                                </div>
                            </div>
                        </div>
                        ${renderActionButtons(el)}
                    </div>
                </article>`;
		});
		// Aggiunge i listener ai nuovi bottoni
		attachActionListeners();
	});
}

// Approvazione appunto
function handleApprove(id) {
	// Effettua la richiesta di approvazione (definita in base.js)
	handleButtonAction(null, "appunti.php", `action=approve&idappunto=${id}`, (data) => {
		if (data.success) {
			updateNotes(); // Se l'approvazione ha successo, aggiorna gli appunti
		} else {
			showError("Errore durante l'approvazione"); // Mostra un errore in caso di fallimento
		}
	});
}

// Rifiuto appunto
function handleReject(id) {
	// Effettua la richiesta di rifiuto (definita in base.js)
	handleButtonAction(null, "appunti.php", `action=reject&idappunto=${id}`, (data) => {
		if (data.success) {
			updateNotes(); // Se il rifiuto ha successo, aggiorna gli appunti
		} else {
			showError("Errore durante il rifiuto"); // Mostra un errore in caso di fallimento
		}
	});
}

// Eliminazione appunto
function handleDelete(btn) {
	const id = btn.dataset.id; // ID dell'appunto da eliminare

	// Chiede conferma prima di eliminare
	if (!btn.dataset.confirm) {
		btn.dataset.confirm = "true";
		btn.innerHTML =
			'<em class="bi bi-check-lg" aria-hidden="true"></em><span class="visually-hidden">Conferma eliminazione</span>'; // Cambia l'icona del bottone per conferma
		btn.classList.replace("btn-outline-danger", "btn-danger");
		setTimeout(() => {
			// Resetta il bottone dopo 3 secondi se non viene confermato
			if (btn.dataset.confirm) resetDeleteButton(btn);
		}, 3000);
		return;
	}

	// Effettua la richiesta di eliminazione (definita in base.js)
	handleButtonAction(btn, "appunti.php", `action=delete&idappunto=${id}`, (data) => {
		if (data.success) {
			updateNotes(); // Se l'eliminazione ha successo, aggiorna gli appunti
		} else {
			showError("Errore durante l'eliminazione"); // Mostra un errore in caso di fallimento
			resetDeleteButton(btn); // Resetta il bottone elimina
		}
	});
}

// Resetta il bottone elimina allo stato iniziale
function resetDeleteButton(btn) {
	delete btn.dataset.confirm; // Rimuove l'attributo di conferma
	btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em> Elimina'; // Ripristina l'icona originale (cestino)
	btn.classList.replace("btn-danger", "btn-outline-danger"); // Ripristina lo stile originale
}

// Aggiorna gli appunti quando si torna alla pagina dal back/forward
window.addEventListener("pageshow", (event) => {
	if (event.persisted) {
		updateNotes(); // Ricarica gli appunti se la pagina è stata caricata dalla cache
	}
});

// Caricamento iniziale
updateNotes();
