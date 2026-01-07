    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="display-5 fw-bold">Gestione Utenti</h2>
                <p class="text-muted">Amministra gli utenti e i loro permessi</p>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <h3 class="card-title mb-0">Utenti</h3>
                    <button type="button" class="btn btn-primary" onclick="openUserModal()">
                        Nuovo Utente
                    </button>
                </div>

                <div class="row mb-4">
                    <div class="col-12 col-md-6">
                        <input type="text" id="searchUser" class="form-control" placeholder="Cerca utente per username...">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Username</th>
                                <th>Ruolo</th>
                                <th class="text-end">Azioni</th>
                            </tr>
                        </thead>
                        <tbody id="usersTableBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Utente -->
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="userModalTitle">Nuovo Utente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="userForm">
                        <input type="hidden" id="userId" name="id">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="ruolo" class="form-label">Ruolo</label>
                            <select class="form-select" id="ruolo" name="ruolo">
                                <option value="0">Utente Standard</option>
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

        document.addEventListener('DOMContentLoaded', function() {
            userModalBS = new bootstrap.Modal(document.getElementById('userModal'));
            loadUsers();
            document.getElementById('searchUser').addEventListener('input', debounce(loadUsers, 300));
        });

        function debounce(func, wait) {
            let timeout;
            return function() {
                const context = this,
                    args = arguments;
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(context, args), wait);
            };
        }

        function loadUsers() {
            const search = document.getElementById('searchUser').value;

            fetch(`gestione-utenti.php?action=get_users&search=${encodeURIComponent(search)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const tbody = document.getElementById('usersTableBody');
                        tbody.innerHTML = '';
                        data.data.forEach(user => {
                            const roleBadge = user.isAdmin == 1 ?
                                '<span class="badge bg-primary">Admin</span>' :
                                '<span class="badge bg-secondary">User</span>';

                            tbody.innerHTML += `
                        <tr>
                            <td><strong>${user.username}</strong></td>
                            <td>${roleBadge}</td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button type="button" class="btn btn-outline-secondary" onclick="editUser(${user.idutente})">Modifica</button>
                                    <button type="button" class="btn btn-outline-danger" onclick="deleteUser(${user.idutente})">Elimina</button>
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
                        // alert('Utente salvato!');
                    } else {
                        alert('Errore: ' + data.message);
                    }
                });
        }

        function deleteUser(id) {
            if (confirm('Sei sicuro di voler eliminare questo utente?')) {
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
                        } else {
                            alert('Errore: ' + data.message);
                        }
                    });
            }
        }
    </script>