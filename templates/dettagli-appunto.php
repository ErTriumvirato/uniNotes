<?php
$appunto = $dbh->getArticleById($_GET['id']);
$dbh->incrementArticleViews($_GET['id']);
$reviews = $dbh->getReviewsByArticle($_GET['id']);

if (!empty($appunto)) {
?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <!-- Article Content -->
        <article class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4 p-md-5">
                <header class="mb-4 border-bottom pb-3">
                    <h1 class="display-5 fw-bold mb-3"><?php echo htmlspecialchars($appunto['titolo']); ?></h1>
                    <div class="d-flex flex-wrap gap-3 text-muted align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <span>Autore: <strong><?php echo htmlspecialchars($appunto['autore']); ?></strong></span>
                        </div>
                        <div class="vr d-none d-md-block"></div>
                        <div class="d-flex align-items-center gap-2">
                            <span>Corso: <a href="corso.php?id=<?php echo $appunto['idcorso']; ?>" class="text-decoration-none fw-bold"><?php echo htmlspecialchars($appunto['nome_corso']); ?></a></span>
                        </div>
                        <div class="vr d-none d-md-block"></div>
                        <div>
                            <span><?php echo date_format(date_create($appunto['data_pubblicazione']), 'd/m/Y H:i'); ?></span>
                        </div>
                    </div>
                </header>

                <div class="article-content mb-4">
                    <?php echo nl2br(htmlspecialchars($appunto['contenuto'])); ?>
                </div>

                <footer class="d-flex gap-3 pt-3 border-top">
                    <span class="badge bg-light text-dark border p-2">
                        <?php echo htmlspecialchars($appunto['numero_visualizzazioni']); ?> Visualizzazioni
                    </span>
                    <span class="badge bg-light text-dark border p-2">
                        ★ <?php echo htmlspecialchars($appunto['media_recensioni'] ?: 'N/A'); ?> Media voti
                    </span>
                </footer>
            </div>
        </article>

        <!-- Reviews Section -->
        <section>
            <h3 class="mb-4">Recensioni</h3>
            
            <?php if (isUserLoggedIn() && !$dbh->hasUserReviewed($appunto['idappunto'], $_SESSION['idutente'])): ?>
                <div class="card shadow-sm border-0 mb-4 form-card">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">Lascia una recensione</h5>
                        <form action="appunto.php?id=<?php echo htmlspecialchars($appunto['idappunto']); ?>" method="POST">
                            <div class="mb-3">
                                <label for="valutazione" class="form-label">Valutazione</label>
                                <select name="valutazione" id="valutazione" class="form-select" required>
                                    <option value="" selected disabled>Seleziona un voto</option>
                                    <option value="5">5 - Eccellente</option>
                                    <option value="4">4 - Molto buono</option>
                                    <option value="3">3 - Buono</option>
                                    <option value="2">2 - Sufficiente</option>
                                    <option value="1">1 - Scarso</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Invia Recensione</button>
                        </form>
                    </div>
                </div>
            <?php elseif (isUserLoggedIn()): ?>
                <div class="alert alert-info mb-4" role="alert">
                    Hai già recensito questo appunto.
                </div>
            <?php else: ?>
                <div class="alert alert-warning mb-4" role="alert">
                    Effettua il <a href="login.php" class="alert-link">login</a> per lasciare una recensione.
                </div>
            <?php endif; ?>

            <?php if (!empty($reviews)): ?>
                <div class="d-flex flex-column gap-3">
                    <?php foreach ($reviews as $review): ?>
                        <div class="card shadow-sm border-0">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($review['username']); ?></h5>
                                    <div class="text-warning">
                                        <?php for($i=0; $i<$review['valutazione']; $i++) echo "★"; ?>
                                        <?php for($i=$review['valutazione']; $i<5; $i++) echo "☆"; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">Nessuna recensione presente.</p>
            <?php endif; ?>
        </section>
    </div>
</div>
<?php
} else {
    echo '<div class="alert alert-danger text-center" role="alert">Appunto non trovato.</div>';
}

