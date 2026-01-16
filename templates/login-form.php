<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <section aria-label="Modulo di accesso" class="card shadow-sm border-0 rounded-3 form-card">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Accedi</h2>
                
                <?php if (isset($templateParams["error"])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($templateParams["error"]); ?>
                    </div>
                <?php endif; ?>

                <form action="login.php<?php echo isset($_GET['redirect']) ? '?redirect=' . urlencode($_GET['redirect']) : ''; ?>" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required />
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required />
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <input type="submit" name="login" class="btn btn-outline-primary btn-lg" value="Accedi" />
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p class="text-muted">Non hai un account? <a href="registrazione-utente.php" class="text-decoration-none">Registrati</a></p>
                </div>
            </div>
        </section>
    </div>
</div>
