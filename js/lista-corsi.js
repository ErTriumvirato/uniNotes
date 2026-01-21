"use strict";

// Gestisce aggiornamento pulsante segui/smetti di seguire
function handleFollowClick(button) {
	handleButtonAction(button, "corsi.php", "toggleFollow=" + encodeURIComponent(button.dataset.idcorso), (data, el) => {
		el.innerHTML = data.following ? "Smetti di seguire" : "Segui corso";
		el.classList.replace(
			data.following ? "btn-outline-primary" : "btn-outline-danger",
			data.following ? "btn-outline-danger" : "btn-outline-primary",
		);
	});
}

const searchInput = document.getElementById("search");
const ssdSelect = document.getElementById("ssd");
const filterTypeSelect = document.getElementById("filterType");
const coursesContainer = document.getElementById("courses-container");

// Carica i corsi
filterCourses();

// Crea la card di un corso
function createCourseCard(corso) {
	const btnClass = corso.isFollowing ? "btn-outline-danger" : "btn-outline-primary";
	const followText = corso.isFollowing ? "Smetti di seguire" : "Segui corso";

	return `
		<div class="col-12 col-md-6 col-lg-4">
			<div class="card h-100 shadow-sm border-0 course-card">
				<div class="card-body d-flex flex-column">
					<h3 class="card-title h5">
						<a href="corso.php?id=${corso.idcorso}" class="text-decoration-none course-title-link stretched-link">
							${corso.nomeCorso}
						</a>
					</h3>
					<p class="card-text text-muted mb-4">
						SSD: <span class="badge bg-light text-dark border">${corso.nomeSSD}</span>
					</p>
					<button type="button" class="btn btn-sm w-100 mt-auto position-relative z-2 btn-follow ${btnClass}"
							data-idcorso="${corso.idcorso}" data-following="${corso.isFollowing}">
						${followText}
					</button>
				</div>
			</div>
		</div>`;
}

// Filtri corsi
function filterCourses() {
	const url = `corsi.php?action=filter&search=${encodeURIComponent(searchInput.value)}&ssd=${encodeURIComponent(ssdSelect.value)}&filterType=${encodeURIComponent(filterTypeSelect?.value || "all")}`;

	// Richiesta AJAX per ottenere i corsi filtrati
	handleButtonAction(null, url, null, (data) => {
		if (data.length === 0) {
			coursesContainer.innerHTML = '<div class="col-12"><p class="text-center text-muted">Nessun corso trovato.</p></div>';
			return;
		}

		coursesContainer.innerHTML = data
				.map(createCourseCard) // Crea la card per ogni corso
				.join(""); // Unisce tutte le card in un'unica stringa (con separatore vuoto)

		// Aggiunge gli event listener ai pulsanti segui/smetti di seguire
		coursesContainer.querySelectorAll(".btn-follow").forEach((btn) => {
			btn.addEventListener("click", () => handleFollowClick(btn));
		});
	});
}
