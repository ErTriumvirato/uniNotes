"use strict";

let ssdModalBS = null;
let searchTimeout = null;

document.addEventListener("DOMContentLoaded", function () {
	ssdModalBS = new bootstrap.Modal(document.getElementById("ssdModal"));

	document.getElementById("btn-new-ssd").addEventListener("click", openSSDModal);
	document.getElementById("btn-save-ssd").addEventListener("click", saveSSD);
	document.getElementById("searchSSD").addEventListener("input", debouncedLoadSSDs);

	document.getElementById("ssdTableBody").addEventListener("click", (e) => {
		const btn = e.target.closest("button");
		if (!btn) return;

		if (btn.classList.contains("btn-edit-ssd")) {
			editSSD(btn.dataset.id);
		} else if (btn.classList.contains("btn-delete-ssd")) {
			deleteSSD(btn.dataset.id, btn);
		}
	});

	loadSSDs();
});

function debouncedLoadSSDs() {
	clearTimeout(searchTimeout);
	searchTimeout = setTimeout(loadSSDs, 300);
}

function loadSSDs() {
	const search = document.getElementById("searchSSD").value;

	fetch(`gestione-ssd.php?action=get_ssds&search=${encodeURIComponent(search)}`)
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				// Riempie Tabella
				const tbody = document.getElementById("ssdTableBody");
				tbody.innerHTML = "";

				data.data.forEach((ssd) => {
					tbody.innerHTML += `
                                <tr>
                                    <td class="text-nowrap">${ssd.nome}</td>
                                    <td class="text-break">${ssd.descrizione}</td>
                                    <td class="text-end col-actions-compact">
                                        <div class="d-flex gap-1 flex-column flex-md-row justify-content-end align-items-end">
                                            <button type="button" class="btn btn-sm btn-outline-secondary btn-edit-ssd" data-id="${ssd.idssd}" title="Modifica">
                                                <em class="bi bi-pencil" aria-hidden="true"></em>
                                                <span class="visually-hidden">Modifica</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger btn-delete-ssd" data-id="${ssd.idssd}" title="Elimina">
                                                <em class="bi bi-trash" aria-hidden="true"></em>
                                                <span class="visually-hidden">Elimina</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            `;
				});
			}
		});
}

// Apre il modal per creare un nuovo SSD
function openSSDModal() {
	document.getElementById("ssdForm").reset();
	document.getElementById("ssdId").value = "";
	document.getElementById("ssdModalTitle").innerText = "Nuovo SSD";
	ssdModalBS.show();
}

// Apre il modal per modificare un SSD esistente
function editSSD(id) {
	fetch(`gestione-ssd.php?action=get_ssd&id=${id}`)
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				const ssd = data.data;
				document.getElementById("ssdId").value = ssd.idssd;
				document.getElementById("ssdName").value = ssd.nome;
				document.getElementById("ssdDescription").value = ssd.descrizione;
				document.getElementById("ssdModalTitle").innerText = "Modifica SSD";
				ssdModalBS.show();
			}
		});
}

// Salva un nuovo SSD o le modifiche a uno esistente
function saveSSD() {
	const formData = new FormData(document.getElementById("ssdForm"));
	formData.append("action", "save_ssd");

	fetch("gestione-ssd.php", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				ssdModalBS.hide();
				loadSSDs();
				showSuccess(data.message);
			} else {
				showError(data.message);
			}
		});
}

// Elimina un SSD
function deleteSSD(id, btn) {
	if (!btn.dataset.confirm) {
		btn.dataset.confirm = "true";
		btn.innerHTML =
			'<em class="bi bi-check-lg" aria-hidden="true"></em><span class="visually-hidden">Conferma eliminazione</span>';
		btn.classList.remove("btn-outline-danger");
		btn.classList.add("btn-danger");
		setTimeout(() => {
			if (btn.dataset.confirm) {
				delete btn.dataset.confirm;
				btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em><span class="visually-hidden">Elimina</span>';
				btn.classList.remove("btn-danger");
				btn.classList.add("btn-outline-danger");
			}
		}, 3000);
		return;
	}

	const formData = new FormData();
	formData.append("action", "delete_ssd");
	formData.append("id", id);

	fetch("gestione-ssd.php", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				loadSSDs();
				loadCourses();
				showSuccess(data.message);
			} else {
				showError(data.message);
			}
		});
}
