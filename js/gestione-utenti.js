"use strict";

let userModalBS = null;
let searchTimeout = null;

document.addEventListener("DOMContentLoaded", function () {
	userModalBS = new bootstrap.Modal(document.getElementById("userModal"));

	document.getElementById("btn-new-user").addEventListener("click", openUserModal);
	document.getElementById("btn-save-user").addEventListener("click", saveUser);
	document.getElementById("searchUser").addEventListener("input", debouncedLoadUsers);
	document.getElementById("filterRole").addEventListener("change", loadUsers);

	document.getElementById("usersTableBody").addEventListener("click", (e) => {
		const btn = e.target.closest("button");
		if (!btn) return;

		if (btn.classList.contains("btn-edit-user")) {
			editUser(btn.dataset.id);
		} else if (btn.classList.contains("btn-delete-user")) {
			deleteUser(btn.dataset.id, btn);
		}
	});

	loadUsers();
});

function debouncedLoadUsers() {
	clearTimeout(searchTimeout);
	searchTimeout = setTimeout(loadUsers, 300);
}

function loadUsers() {
	const search = document.getElementById("searchUser").value;
	const role = document.getElementById("filterRole").value;

	fetch(`gestione-utenti.php?action=get_users&search=${encodeURIComponent(search)}&role=${encodeURIComponent(role)}`)
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				const tbody = document.getElementById("usersTableBody");
				tbody.innerHTML = "";
				data.data.forEach((user) => {
					const roleBadge = user.isAdmin == 1 ? "Amministratore" : "Utente";

					tbody.innerHTML += `
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
                        </tr>
                    `;
				});
			}
		});
}

// Apre il modal per creare un nuovo utente
function openUserModal() {
	document.getElementById("userForm").reset();
	document.getElementById("userId").value = "";
	document.getElementById("userModalTitle").innerText = "Nuovo Utente";
	userModalBS.show();
}

// Apre il modal per modificare un utente esistente
function editUser(id) {
	fetch(`gestione-utenti.php?action=get_user&id=${id}`)
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				const user = data.data;
				document.getElementById("userId").value = user.idutente;
				document.getElementById("username").value = user.username;
				document.getElementById("email").value = user.email;
				document.getElementById("ruolo").value = user.isAdmin;
				document.getElementById("password").value = "";
				document.getElementById("userModalTitle").innerText = "Modifica Utente";
				userModalBS.show();
			}
		});
}

// Salva un nuovo utente o le modifiche a un utente esistente
function saveUser() {
	const formData = new FormData(document.getElementById("userForm"));
	formData.append("action", "save_user");

	fetch("gestione-utenti.php", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				userModalBS.hide();
				loadUsers();
				showSuccess(data.message);
			} else {
				showError(data.message);
			}
		});
}

// Elimina un utente
function deleteUser(id, btn) {
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
	formData.append("action", "delete_user");
	formData.append("id", id);

	fetch("gestione-utenti.php", {
		method: "POST",
		body: formData,
	})
		.then((response) => response.json())
		.then((data) => {
			if (data.success) {
				loadUsers();
				showSuccess(data.message);
			} else {
				showError(data.message);
			}
		});
}
