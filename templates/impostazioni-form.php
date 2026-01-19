<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <section aria-label="Impostazioni profilo" class="card shadow-sm border-0 rounded-3 form-card">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Impostazioni profilo</h2>

                <?php if (isset($templateParams["messaggio"])): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            <?php if (strpos($templateParams["messaggio"], 'successo') === false): ?>
                                showError("<?php echo addslashes($templateParams['messaggio']); ?>");
                            <?php else: ?>
                                showSuccess("<?php echo addslashes($templateParams['messaggio']); ?>");
                            <?php endif; ?>
                        });
                    </script>
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
                        <label for="confirm_password" class="form-label">Ripeti password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" />
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <input type="submit" name="submit" class="btn btn-outline-primary" value="Salva Modifiche" />
                    </div>
                </form>

                <hr class="my-4">

                <form action="impostazioni.php" method="POST" id="deleteAccountForm">
                    <input type="hidden" name="delete_account" value="1" />
                    <div class="d-grid gap-2">
                        <button type="button" onclick="handleDeleteAccount(this)" class="btn btn-outline-danger">
                            <i class="bi bi-trash" aria-hidden="true"></i> Elimina account
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<script>
    function handleDeleteAccount(btn) {
        if (!btn.dataset.confirm) {
            btn.dataset.confirm = 'true';
            btn.textContent = 'Conferma eliminazione';
            btn.classList.remove('btn-outline-danger');
            btn.classList.add('btn-danger');
            setTimeout(() => {
                if (btn.dataset.confirm) {
                    delete btn.dataset.confirm;
                    btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i> Elimina account';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-outline-danger');
                }
            }, 3000);
            return;
        }
        document.getElementById('deleteAccountForm').submit();
    }
</script>
