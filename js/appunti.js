"use strict";

const sortSelect = document.getElementById("ajax-sort");
const orderSelect = document.getElementById("ajax-order");
const searchInput = document.getElementById("ajax-search");
const approvalSelect = document.getElementById("ajax-approval");
const container = document.getElementById("articles-container");
const nomeutente = container.dataset.nomeutente ?? "";
const nomecorso = container.dataset.nomecorso ?? "";
const defaultApprovalFilter = approvalSelect ? approvalSelect.value : "approved";
const defaultMessage = container.dataset.defaultMessage || "Nessun appunto disponibile.";
const showActions = approvalSelect;

let searchTimeout;
searchInput.addEventListener("input", () => {
	clearTimeout(searchTimeout);
	searchTimeout = setTimeout(updateArticles, 300);
});

function renderActionButtons(el) {
	if (!showActions) return "";
	let buttons = "";
	if (el.stato === "in_revisione") {
		buttons += `
            <button type="button" class="btn btn-sm btn-outline-success" onclick="handleApprove(${el.idappunto})" title="Approva" aria-label="Approva appunto">
                <i class="bi bi-check-lg" aria-hidden="true"></i>
                <span class="visually-hidden">Approva</span>
            </button>
            <button type="button" class="btn btn-sm btn-outline-warning" onclick="handleReject(${el.idappunto})" title="Rifiuta" aria-label="Rifiuta appunto">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
                <span class="visually-hidden">Rifiuta</span>
            </button>`;
	}
	buttons += `
        <button type="button" class="btn btn-sm btn-outline-danger" data-id="${el.idappunto}" onclick="handleDelete(this)" title="Elimina" aria-label="Elimina appunto">
            <i class="bi bi-trash" aria-hidden="true"></i>
            <span class="visually-hidden">Elimina</span>
        </button>`;
	return `<div class="d-flex gap-2 mt-3 justify-content-end">${buttons}</div>`;
}

// Aggiorna gli appunti
function updateArticles() {
	const searchValue = searchInput.value.trim();
	const approvalValue = approvalSelect ? approvalSelect.value : defaultApprovalFilter;

	let url = `appunti.php?action=filter&sort=${encodeURIComponent(sortSelect.value)}&order=${encodeURIComponent(orderSelect.value)}`;
	if (nomeutente !== "") url += `&nomeutente=${encodeURIComponent(nomeutente)}`;
	if (nomecorso !== "") url += `&nomecorso=${encodeURIComponent(nomecorso)}`;
	if (searchValue !== "") url += `&search=${encodeURIComponent(searchValue)}`;
	url += `&approval=${encodeURIComponent(approvalValue)}`;

	handleButtonAction(null, url, null, (data) => {
		container.innerHTML = "";
		if (data.length === 0) {
			container.innerHTML = `<div class="alert alert-info text-center" role="alert">${defaultMessage}</div>`;
			return;
		}
		data.forEach((el) => {
			let statusBadge = "";
			if (showActions) {
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
				const statusInfo = statusMap[el.stato] || {
					label: el.stato,
					class: "bg-secondary",
				};
				statusBadge = `<span class="badge ${statusInfo.class}" title="Stato">${statusInfo.label}</span>`;
			}

			container.insertAdjacentHTML(
				"beforeend",
				`
                <article class="card shadow-sm border-0 article-card" id="article-${el.idappunto}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h5 class="card-title mb-1">
                                    <a href="appunto.php?id=${el.idappunto}" class="text-decoration-none text-dark ${showActions ? "" : "stretched-link"}">${el.titolo}</a>
                                </h5>
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
                </article>`,
			);
		});
	});
}

// Handler per approvazione appunto
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
				updateArticles();
			} else {
				showError("Errore durante l'approvazione");
			}
		})
		.catch(() => showError("Errore di connessione"));
}

// Handler per rifiuto appunto
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
				updateArticles();
			} else {
				showError("Errore durante il rifiuto");
			}
		})
		.catch(() => showError("Errore di connessione"));
}

// Handler per eliminazione appunto
function handleDelete(btn) {
	const id = btn.dataset.id;
	const card = document.getElementById(`article-${id}`);

	if (!btn.dataset.confirm) {
		btn.dataset.confirm = "true";
		btn.innerHTML = '<i class="bi bi-check-lg" aria-hidden="true"></i><span class="visually-hidden">Conferma eliminazione</span>';
		btn.classList.remove("btn-outline-danger");
		btn.classList.add("btn-danger");
		setTimeout(() => {
			if (btn.dataset.confirm) {
				delete btn.dataset.confirm;
				btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i> Elimina';
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
				updateArticles();
			} else {
				showError("Errore durante l'eliminazione");
				btn.disabled = false;
				btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i> Elimina';
				delete btn.dataset.confirm;
				btn.classList.remove("btn-danger");
				btn.classList.add("btn-outline-danger");
			}
		})
		.catch(() => {
			showError("Errore di connessione");
			btn.disabled = false;
			btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i> Elimina';
			delete btn.dataset.confirm;
			btn.classList.remove("btn-danger");
			btn.classList.add("btn-outline-danger");
		});
}

// Aggiorna gli articoli quando si torna alla pagina dal back/forward
window.addEventListener("pageshow", (event) => {
	if (event.persisted) {
		updateArticles();
	}
});
