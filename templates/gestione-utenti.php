<div>
    <h2>Gestione Utenti</h2>

    <div>
        <div>
            <input type="text" id="searchUser" placeholder="Cerca utente per username...">
        </div>
    </div>

    <div>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Ruolo</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
                <!-- Populated by JS -->
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Utente -->
<div id="userModal" tabindex="-1">
    <div>
        <div>
            <div>
                <h5 id="userModalTitle">Nuovo Utente</h5>
                <button type="button">Chiudi</button>
            </div>
            <div>
                <form id="userForm">
                    <input type="hidden" id="userId" name="id">
                    <div>
                        <label for="username">Username</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    <div>
                        <label for="ruolo">Ruolo</label>
                        <select id="ruolo" name="ruolo">
                            <option value="0">Utente Standard</option>
                            <option value="1">Amministratore</option>
                        </select>
                    </div>
                    <div>
                        <label for="password">Password</label>
                        <input type="password" id="password" name="password" placeholder="Lascia vuoto per non modificare">
                        <div>Obbligatoria per nuovi utenti.</div>
                    </div>
                </form>
            </div>
            <div>
                <button type="button">Annulla</button>
                <button type="button" onclick="saveUser()">Salva</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadUsers();
    document.getElementById('searchUser').addEventListener('input', debounce(loadUsers, 300));
});

// const userModal = new bootstrap.Modal(document.getElementById('userModal'));

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
                        ? '<span>Admin</span>' 
                        : '<span>User</span>';
                        
                    tbody.innerHTML += `
                        <tr>
                            <td>${user.username}</td>
                            <td>${roleBadge}</td>
                            <td>
                                <button type="button" onclick="editUser(${user.idutente})">Modifica</button>
                                <button type="button" onclick="deleteUser(${user.idutente})">Elimina</button>
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
    // userModal.show();
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
                document.getElementById('password').value = '';
                document.getElementById('userModalTitle').innerText = 'Modifica Utente';
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
            // userModal.hide();
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
