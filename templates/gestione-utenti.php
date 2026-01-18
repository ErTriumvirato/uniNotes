    <div class="container">
        <header class="row mb-4">
            <div class="col-12">
                <h2 class="display-5 fw-bold">Gestione utenti</h2>
            </div>
        </header>

        <section aria-labelledby="titolo-gestione-utenti" class="card shadow-sm border-0 mb-5">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <button type="button" class="btn btn-outline-primary" onclick="openUserModal()">
                        Nuovo utente
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-sm btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#userFiltersCollapse" aria-expanded="false" aria-controls="userFiltersCollapse">
                        <i class="bi bi-filter"></i> Filtri
                    </button>
                </div>

                <div class="row g-2 align-items-end collapse d-md-flex mb-4" id="userFiltersCollapse">
                    <div class="col-12 col-md-6">
                        <label for="searchUser" class="form-label small text-muted">Cerca</label>
                        <input type="text" id="searchUser" class="form-control" placeholder="Cerca utente per username..." oninput="debouncedLoadUsers()">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="filterRole" class="form-label small text-muted">Ruolo</label>
                        <select id="filterRole" class="form-select" onchange="loadUsers()">
                            <option value="all">Tutti</option>
                            <option value="admin">Amministratori</option>
                            <option value="user">Utenti</option>
                        </select>
                    </div>
                </div>

                <div>
                    <table class="table table-hover align-middle table-sm">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Username</th>
                                <th scope="col" class="col-ruolo">Ruolo</th>
                                <th scope="col" class="text-end">Azioni</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody" aria-live="polite">
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal Utente -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Nuovo utente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" id="userId" name="id">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="ruolo" class="form-label">Ruolo</label>
                            <select class="form-select" id="ruolo" name="ruolo">
                                <option value="0">Utente</option>
                                <option value="1">Amministratore</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Lascia vuoto per non modificare">
                            <div class="form-text">Obbligatoria per nuovi utenti.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="button" class="btn btn-primary" onclick="saveUser()">Salva</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let userModalBS = null;
        let searchTimeout = null;

        document.addEventListener('DOMContentLoaded', function() {
            userModalBS = new bootstrap.Modal(document.getElementById('userModal'));
            loadUsers();
        });

        function debouncedLoadUsers() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(loadUsers, 300);
        }

        function loadUsers() {
            const search = document.getElementById('searchUser').value;
            const role = document.getElementById('filterRole').value;

            fetch(`gestione-utenti.php?action=get_users&search=${encodeURIComponent(search)}&role=${encodeURIComponent(role)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.getElementById('usersTableBody');
                        tbody.innerHTML = '';
                        data.data.forEach(user => {
                            const roleBadge = user.isAdmin == 1 ?
                                'Amministratore' :
                                'Utente';

                            tbody.innerHTML += `
                        <tr>
                            <td class="text-break">${user.username}</td>
                            <td class="col-ruolo">${roleBadge}</td>
                            <td class="text-end col-azioni">
                                <div class="d-flex gap-1 flex-column flex-md-row justify-content-end align-items-end">
                                    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="editUser(${user.idutente})" title="Modifica">
                                        <i class="bi bi-pencil" aria-hidden="true"></i>
                                        <span class="visually-hidden">Modifica</span>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteUser(${user.idutente}, this)" title="Elimina">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
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

        function openUserModal() {
            document.getElementById('userForm').reset();
            document.getElementById('userId').value = '';
            document.getElementById('userModalTitle').innerText = 'Nuovo Utente';
            userModalBS.show();
        }

        function editUser(id) {
            fetch(`gestione-utenti.php?action=get_user&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const user = data.data;
                        document.getElementById('userId').value = user.idutente;
                        document.getElementById('username').value = user.username;
                        document.getElementById('email').value = user.email;
                        document.getElementById('ruolo').value = user.isAdmin;
                        document.getElementById('password').value = '';
                        document.getElementById('userModalTitle').innerText = 'Modifica Utente';
                        userModalBS.show();
                    }
                });
        }

        function saveUser() {
            const formData = new FormData(document.getElementById('userForm'));
            formData.append('action', 'save_user');

            fetch('gestione-utenti.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        userModalBS.hide();
                        loadUsers();
                        showSuccess(data.message);
                    } else {
                        showError('Errore: ' + data.message);
                    }
                });
        }

        function deleteUser(id, btn) {
            if (!btn.dataset.confirm) {
                btn.dataset.confirm = 'true';
                btn.innerHTML = '<i class="bi bi-check-lg" aria-hidden="true"></i><span class="visually-hidden">Conferma eliminazione</span>';
                btn.classList.remove('btn-outline-danger');
                btn.classList.add('btn-danger');
                setTimeout(() => {
                    if (btn.dataset.confirm) {
                        delete btn.dataset.confirm;
                        btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i><span class="visually-hidden">Elimina</span>';
                        btn.classList.remove('btn-danger');
                        btn.classList.add('btn-outline-danger');
                    }
                }, 3000);
                return;
            }

            const formData = new FormData();
            formData.append('action', 'delete_user');
            formData.append('id', id);

            fetch('gestione-utenti.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        loadUsers();
                        showSuccess(data.message);
                    } else {
                        showError('Errore: ' + data.message);
                    }
                });
        }
    </script>