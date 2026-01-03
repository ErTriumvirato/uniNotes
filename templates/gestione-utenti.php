<div class="container py-5">
    <h2 class="mb-4">Gestione Utenti</h2>

    <div class="row mb-3 g-2">
        <div class="col-md-12">
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
                <!-- Populated by JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Utente -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalTitle">Nuovo Utente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    document.getElementById('searchUser').addEventListener('input', debounce(loadUsers, 300));
});

const userModal = new bootstrap.Modal(document.getElementById('userModal'));

function debounce(func, wait) {
    let timeout;
    return function() {
        const context = this, args = arguments;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

function loadUsers() {
    const search = document.getElementById('searchUser').value;
    
    fetch(`gestione-utenti.php?action=get_users&search=${encodeURIComponent(search)}`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const tbody = document.getElementById('usersTableBody');
                tbody.innerHTML = '';
                data.data.forEach(user => {
                    const roleBadge = user.isAdmin == 1 
                        ? '<span class="badge bg-danger">Admin</span>' 
                        : '<span class="badge bg-primary">User</span>';
                        
                    tbody.innerHTML += `
                        <tr>
                            <td>${user.username}</td>
                            <td>${roleBadge}</td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editUser(${user.idutente})">Modifica</button>
                                <button class="btn btn-sm btn-outline-danger" onclick="deleteUser(${user.idutente})">Elimina</button>
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
    userModal.show();
}

function editUser(id) {
    fetch(`gestione-utenti.php?action=get_user&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                const user = data.data;
                document.getElementById('userId').value = user.idutente;
                document.getElementById('username').value = user.username;
                document.getElementById('ruolo').value = user.isAdmin;
                document.getElementById('password').value = ''; // Don't show password hash
                document.getElementById('userModalTitle').innerText = 'Modifica Utente';
                userModal.show();
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
        if(data.success) {
            userModal.hide();
            loadUsers();
            alert('Utente salvato!');
        } else {
            alert('Errore: ' + data.message);
        }
    });
}

function deleteUser(id) {
    if(confirm('Sei sicuro di voler eliminare questo utente?')) {
        const formData = new FormData();
        formData.append('action', 'delete_user');
        formData.append('id', id);
        
        fetch('gestione-utenti.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                loadUsers();
            } else {
                alert('Errore: ' + data.message);
            }
        });
    }
}
</script>
