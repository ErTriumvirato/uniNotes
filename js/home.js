const recentContainer = document.getElementById("recent-notes-container");
const mostViewedContainer = document.getElementById("most-viewed-notes-container");
const bannerContainer = document.getElementById("no-courses-banner-container");

// Genera l'HTML del banner "non segui corsi"
function renderNoCoursesBanner() {
	return `
		<div class="alert alert-info mb-5" role="alert">
			<h2 class="alert-heading h4">Non segui ancora nessun corso!</h2>
			<p>Inizia a seguire dei corsi per poter visualizzare degli appunti qui.</p>
			<a href="corsi.php" class="btn btn-outline-primary">Esplora i corsi</a>
		</div>`;
}

// Genera l'HTML di una singola card appunto
function renderNoteCard(note) {
	return `
        <div class="col-12 col-md-6 col-lg-4">
            <article class="card h-100 border-0 shadow-sm note-card">
                <div class="card-body d-flex flex-column">
                    <h3 class="h5 card-title mb-3">
                        <a href="appunto.php?id=${note.idappunto}" class="text-decoration-none stretched-link note-title-link" target="_self">
                            ${note.titolo}
                        </a>
                    </h3>
                    <div class="mt-auto">
                        <small class="text-muted mb-2 d-block">${note.nome_corso}</small>
                        <p class="mb-2 small">
                            di <strong>${note.autore}</strong>
                        </p>
                        <div class="d-flex flex-wrap gap-2 align-items-center">
                            <span class="badge bg-light text-dark border p-2" title="Data pubblicazione">
                                ${note.data_formattata}
                            </span>
                            <span class="badge bg-light text-dark border p-2" title="Visualizzazioni">
                                ${note.numero_visualizzazioni} Visualizzazioni
                            </span>
                            <span class="badge bg-light text-dark border p-2" title="Media recensioni">
                                ★ ${note.media_recensioni}
                            </span>
                        </div>
                    </div>
                </div>
            </article>
        </div>`;
}

// Genera l'HTML per una lista di appunti
function renderNotes(container, notes) {
	container.innerHTML = "";
	if (notes.length === 0) {
		container.innerHTML = '<p class="col-12">Nessun appunto trovato.</p>';
		return;
	}
	notes.forEach((note) => {
		container.innerHTML += renderNoteCard(note);
	});
}

// Aggiorna gli appunti della home via AJAX
function refreshHomeNotes() {
	// Se i container non esistono, non fare nulla
	if (!recentContainer && !mostViewedContainer) {
		return;
	}

	// Esegue la richiesta AJAX (definita in base.js)
	handleButtonAction(null, "index.php", "action=getHomeNotes", (data) => {
		if (data.success) {
			// Gestisce il banner "non segui corsi"
			if (bannerContainer) {
				if (data.isLoggedIn && !data.seguendoCorsi) {
					bannerContainer.innerHTML = renderNoCoursesBanner();
				} else {
					bannerContainer.innerHTML = "";
				}
			}

			// Aggiorna i container degli appunti
			if (recentContainer) {
				renderNotes(recentContainer, data.recentNotes);
			}
			if (mostViewedContainer) {
				renderNotes(mostViewedContainer, data.mostViewedNotes);
			}
		}
	});
}

// Aggiorna gli appunti quando si torna alla pagina dal back/forward
window.addEventListener("pageshow", (event) => {
	if (event.persisted) {
		refreshHomeNotes();
	}
});

// Caricamento iniziale
refreshHomeNotes();
