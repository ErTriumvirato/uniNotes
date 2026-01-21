let ssdModalBS = new bootstrap.Modal(document.getElementById("ssdModal"));

document.getElementById("btn-new-ssd").addEventListener("click", openSSDModal);
document.getElementById("btn-save-ssd").addEventListener("click", saveSSD);
document.getElementById("searchSSD").addEventListener("input", loadSSDs);

loadSSDs();

function createSSDRow(ssd) {
	return `
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
		</tr>`;
}

function loadSSDs() {
	const search = document.getElementById("searchSSD").value;
	const url = `gestione-ssd.php?action=get_ssds&search=${encodeURIComponent(search)}`;

	handleButtonAction(null, url, null, (data) => {
		if (!data.success) return;

		document.getElementById("ssdTableBody").innerHTML = data.data.map(createSSDRow).join("");

		document.querySelectorAll(".btn-edit-ssd").forEach((btn) => {
			btn.addEventListener("click", () => editSSD(btn.dataset.id));
		});

		document.querySelectorAll(".btn-delete-ssd").forEach((btn) => {
			btn.addEventListener("click", () => deleteSSD(btn.dataset.id, btn));
		});
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
	handleButtonAction(null, `gestione-ssd.php?action=get_ssd&id=${id}`, null, (data) => {
		if (!data.success) return;

		const ssd = data.data;
		document.getElementById("ssdId").value = ssd.idssd;
		document.getElementById("ssdName").value = ssd.nome;
		document.getElementById("ssdDescription").value = ssd.descrizione;
		document.getElementById("ssdModalTitle").innerText = "Modifica SSD";
		ssdModalBS.show();
	});
}

// Salva un nuovo SSD o le modifiche a uno esistente
function saveSSD() {
	const formData = new FormData(document.getElementById("ssdForm"));
	formData.append("action", "save_ssd");

	handleButtonAction(null, "gestione-ssd.php", new URLSearchParams(formData).toString(), (data) => {
		if (data.success) {
			ssdModalBS.hide();
			loadSSDs();
			showSuccess(data.message);
		} else {
			showError(data.message);
		}
	});
}

function resetDeleteSSDButton(btn) {
	delete btn.dataset.confirm;
	btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em><span class="visually-hidden">Elimina</span>';
	btn.classList.replace("btn-danger", "btn-outline-danger");
}

// Elimina un SSD
function deleteSSD(id, btn) {
	if (!btn.dataset.confirm) {
		btn.dataset.confirm = "true";
		btn.innerHTML =
			'<em class="bi bi-check-lg" aria-hidden="true"></em><span class="visually-hidden">Conferma eliminazione</span>';
		btn.classList.replace("btn-outline-danger", "btn-danger");
		setTimeout(() => {
			if (btn.dataset.confirm) resetDeleteSSDButton(btn);
		}, 3000);
		return;
	}

	handleButtonAction(btn, "gestione-ssd.php", `action=delete_ssd&id=${id}`, (data) => {
		if (data.success) {
			loadSSDs();
			loadCourses();
			showSuccess(data.message);
		} else {
			showError(data.message);
			resetDeleteSSDButton(btn);
		}
	});
}
