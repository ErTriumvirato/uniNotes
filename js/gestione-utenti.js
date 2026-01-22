let userModalBS = new bootstrap.Modal(document.getElementById("userModal")); // Inizializza il modal di Bootstrap

// Rimuove il focus dall'elemento attivo quando il modal inizia a chiudersi per evitare errori di accessibilità
document.getElementById("userModal").addEventListener("hide.bs.modal", () => {
	document.activeElement?.blur();
});

document.getElementById("btn-new-user").addEventListener("click", openUserModal); // Aggiunge al pulsante il listener di apertura del modal
document.getElementById("btn-save-user").addEventListener("click", saveUser); // Aggiunge al pulsante di salvataggio il listener per salvare l'utente
document.getElementById("searchUser").addEventListener("input", loadUsers); // Aggiunge il listener di ricerca utenti
document.getElementById("filterRole").addEventListener("change", loadUsers); // Aggiunge il listener di filtro per ruolo

// Carica la lista degli utenti all'avvio
loadUsers();

// Creazione di una riga della tabella degli user
function createUserRow(user) {
	const roleBadge = user.isAdmin == 1 ? "Amministratore" : "Utente";
	return `
		<tr>
			<td class="text-break">${user.username}</td>
			<td class="col-ruolo">${roleBadge}</td>
			<td class="text-end col-azioni">
				<div class="d-flex gap-1 flex-column flex-md-row justify-content-end align-items-end">
					<button type="button" class="btn btn-sm btn-outline-secondary btn-edit-user" data-id="${user.idutente}" title="Modifica">
						<em class="bi bi-pencil" aria-hidden="true"></em>
						<span class="visually-hidden">Modifica</span>
					</button>
					<button type="button" class="btn btn-sm btn-outline-danger btn-delete-user" data-id="${user.idutente}" title="Elimina">
						<em class="bi bi-trash" aria-hidden="true"></em>
						<span class="visually-hidden">Elimina</span>
					</button>
				</div>
			</td>
		</tr>`;
}

// Carica la lista degli utenti con filtri di ricerca e ruolo
function loadUsers() {
	const search = document.getElementById("searchUser").value;
	const role = document.getElementById("filterRole").value;
	const url = `gestione-utenti.php?action=get_users&search=${encodeURIComponent(search)}&role=${encodeURIComponent(role)}`;

	// Invia la richiesta AJAX per ottenere la lista degli utenti (definita in base.js)
	handleButtonAction(null, url, null, (data) => {
		if (!data.success) return;

		// Popola la tabella con le righe create
		document.getElementById("usersTableBody").innerHTML = data.data
			.map(createUserRow) // Crea una riga per ogni utente
			.join(""); // Unisce tutte le righe in una singola stringa

		// Aggiunge i listener ai pulsanti di modifica ed eliminazione
		document.querySelectorAll(".btn-edit-user").forEach((btn) => {
			btn.addEventListener("click", () => editUser(btn.dataset.id));
		});

		document.querySelectorAll(".btn-delete-user").forEach((btn) => {
			btn.addEventListener("click", () => deleteUser(btn.dataset.id, btn));
		});
	});
}

// Apre il modal per creare un nuovo utente
function openUserModal() {
	document.getElementById("userForm").reset();
	document.getElementById("userId").value = "";
	document.getElementById("password").required = true;
	document.getElementById("userModalTitle").innerText = "Nuovo Utente";
	userModalBS.show();
}

// Apre il modal per modificare un utente esistente
function editUser(id) {
	// Invia la richiesta AJAX per ottenere i dati dell'utente (definita in base.js)
	handleButtonAction(null, `gestione-utenti.php?action=get_user&id=${id}`, null, (data) => {
		if (!data.success) return;

		const user = data.data;
		document.getElementById("userId").value = user.idutente;
		document.getElementById("username").value = user.username;
		document.getElementById("email").value = user.email;
		document.getElementById("ruolo").value = user.isAdmin;
		document.getElementById("password").value = "";
		delete document.getElementById("password").required;
		document.getElementById("userModalTitle").innerText = "Modifica Utente";
		userModalBS.show();
	});
}

// Salva un nuovo utente o le modifiche a un utente esistente
function saveUser() {
	const formData = new FormData(document.getElementById("userForm"));
	formData.append("action", "save_user");

	// Invia la richiesta AJAX per salvare l'utente (definita in base.js)
	handleButtonAction(null, "gestione-utenti.php", new URLSearchParams(formData).toString(), (data) => {
		if (data.success) {
			userModalBS.hide();
			loadUsers();
			showSuccess(data.message);
		} else {
			showError(data.message);
		}
	});
}

// Resetta il pulsante di eliminazione utente
function resetDeleteUserButton(btn) {
	delete btn.dataset.confirm;
	btn.innerHTML = '<em class="bi bi-trash" aria-hidden="true"></em><span class="visually-hidden">Elimina</span>';
	btn.classList.replace("btn-danger", "btn-outline-danger");
}

// Elimina un utente
function deleteUser(id, btn) {
	if (!btn.dataset.confirm) {
		btn.dataset.confirm = "true";
		btn.innerHTML =
			'<em class="bi bi-check-lg" aria-hidden="true"></em><span class="visually-hidden">Conferma eliminazione</span>';
		btn.classList.replace("btn-outline-danger", "btn-danger");
		setTimeout(() => {
			if (btn.dataset.confirm) resetDeleteUserButton(btn);
		}, 3000);
		return;
	}

	// Invia la richiesta AJAX per eliminare l'utente (definita in base.js)
	handleButtonAction(btn, "gestione-utenti.php", `action=delete_user&id=${id}`, (data) => {
		if (data.success) {
			loadUsers();
			showSuccess(data.message);
		} else {
			showError(data.message);
			resetDeleteUserButton(btn);
		}
	});
}
