<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <section aria-label="Impostazioni profilo" class="card shadow-sm border-0 rounded-3 form-card">
            <div class="card-body p-4">
                <h1 class="text-center mb-4 h2">Impostazioni profilo</h1>

                <?php if (isset($templateParams["messaggio"])): ?>
                    <div id="server-message"
                        data-message="<?php echo htmlspecialchars($templateParams['messaggio']); ?>"
                        data-type="<?php echo (strpos($templateParams["messaggio"], 'successo') !== false) ? 'success' : 'danger'; ?>">
                    </div>
                <?php endif; ?>

                <form action="impostazioni.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($templateParams["currentUser"]["username"]); ?>" required />
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($templateParams["currentUser"]["email"]); ?>" required />
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nuova password <small class="text-muted">(lascia vuoto per non cambiare)</small></label>
                        <input type="password" class="form-control" id="password" name="password" />
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Ripeti password <small class="text-muted">(lascia vuoto per non cambiare)</small></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" />
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <input type="submit" name="submit" class="btn btn-outline-primary" value="Salva Modifiche" />
                    </div>
                </form>

                <hr class="my-4" />

                <form action="impostazioni.php" method="POST" id="deleteAccountForm">
                    <input type="hidden" name="delete_account" value="1" />
                    <div class="d-grid gap-2">
                        <button type="button" id="btn-delete-account" class="btn btn-outline-danger">
                            <em class="bi bi-trash" aria-hidden="true"></em> Elimina account
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>