    <div class="container">
        <header class="row mb-4">
            <div class="col-12">
                <h2 class="display-5 fw-bold">Gestione utenti</h2>
            </div>
        </header>

        <section aria-labelledby="titolo-gestione-utenti" class="card shadow-sm border-0 mb-5">
            <div class="card-body p-3 p-md-4">
                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
                    <button type="button" class="btn btn-outline-primary" id="btn-new-user">
                        Nuovo utente
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <button class="btn btn-sm btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#userFiltersCollapse" aria-expanded="false" aria-controls="userFiltersCollapse">
                        <em class="bi bi-filter"></em> Filtri
                    </button>
                </div>

                <div class="row g-2 align-items-end collapse d-md-flex mb-4" id="userFiltersCollapse">
                    <div class="col-12 col-md-6">
                        <label for="searchUser" class="form-label small text-muted">Cerca</label>
                        <input type="text" id="searchUser" class="form-control" placeholder="Cerca utente per username...">
                    </div>
                    <div class="col-12 col-md-6">
                        <label for="filterRole" class="form-label small text-muted">Ruolo</label>
                        <select id="filterRole" class="form-select">
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
                    <button type="button" class="btn btn-primary" id="btn-save-user">Salva</button>
                </div>
            </div>
        </div>
    </div>