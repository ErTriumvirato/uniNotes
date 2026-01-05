<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 rounded-3 form-card">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Impostazioni Profilo</h2>
                
                <?php if(isset($templateParams["messaggio"])): ?>
                    <div class="alert alert-info" role="alert">
                        <?php echo $templateParams["messaggio"]; ?>
                    </div>
                <?php endif; ?>

                <form action="impostazioni.php" method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($templateParams["currentUser"]["username"]); ?>" required />
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">Nuova Password <small class="text-muted">(lascia vuoto per non cambiare)</small></label>
                        <input type="password" class="form-control" id="password" name="password" />
                    </div>

                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Ripeti Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" />
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <input type="submit" name="submit" class="btn btn-primary btn-lg" value="Salva Modifiche" />
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>