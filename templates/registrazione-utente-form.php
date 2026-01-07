<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6 col-xl-5">
        <div class="card shadow-sm border-0 rounded-3 form-card">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Registrati</h2>
                
                <?php if (isset($templateParams["error"])): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo htmlspecialchars($templateParams["error"]); ?>
                    </div>
                <?php endif; ?>

                <form action="registrazione-utente.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required />
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required />
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <input type="submit" name="registrazione" class="btn btn-primary btn-lg" value="Registrati" />
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p class="text-muted">Hai già un account? <a href="login.php" class="text-decoration-none">Accedi</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
