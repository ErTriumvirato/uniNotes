<?php
$articolo = $dbh->getArticleById($_GET['id']);
$dbh->incrementArticleViews($_GET['id']);
$reviews = $dbh->getReviewsByArticle($_GET['id']);

if (!empty($articolo)) {
?>
    <div class="container mt-5">
        <article class="card shadow-sm border-0 mb-5">
            <div class="card-body">
                <h1 class="card-title display-4 mb-3"><?php echo htmlspecialchars($articolo['titolo']); ?></h1>
                
                <div class="d-flex justify-content-between align-items-center text-muted mb-4">
                    <div>
                        <span class="me-3"><i class="bi bi-person"></i> Autore: <strong><?php echo htmlspecialchars($articolo['autore']); ?></strong></span>
                        <span><i class="bi bi-calendar"></i> <?php echo date_format(date_create($articolo['data_pubblicazione']), 'd/m/Y H:i'); ?></span>
                    </div>
                    <div>
                        <span class="badge bg-primary rounded-pill me-2">
                            <i class="bi bi-eye"></i> <?php echo $articolo['numero_visualizzazioni']; ?> Visualizzazioni
                        </span>
                        <span class="badge bg-warning text-dark rounded-pill">
                            <i class="bi bi-star-fill"></i> <?php echo $articolo['media_recensioni'] ?: 'N/A'; ?>
                        </span>
                    </div>
                </div>

                <div class="card-text lead" style="white-space: pre-wrap;">
                    <?php echo nl2br(htmlspecialchars($articolo['contenuto'])); ?>
                </div>
            </div>
        </article>

        <section class="mb-5">
            <h3 class="mb-4">Recensioni</h3>
            
            <?php if (isUserLoggedIn() && !$dbh->hasUserReviewed($articolo['idarticolo'], $_SESSION['idutente'])): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Lascia una recensione</h5>
                        <form action="articolo.php?id=<?php echo $articolo['idarticolo']; ?>" method="POST">
                            <div class="mb-3">
                                <label for="valutazione" class="form-label">Valutazione (1-5)</label>
                                <select class="form-select" name="valutazione" id="valutazione" required>
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
                <div class="alert alert-info">Hai già recensito questo articolo.</div>
            <?php else: ?>
                <div class="alert alert-warning">Effettua il <a href="login.php">login</a> per lasciare una recensione.</div>
            <?php endif; ?>

            <?php if (!empty($reviews)): ?>
                <div class="list-group">
                    <?php foreach ($reviews as $review): ?>
                        <div class="list-group-item list-group-item-action flex-column align-items-start">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?php echo htmlspecialchars($review['username']); ?></h5>
                                <small class="text-warning">
                                    <?php for($i=0; $i<$review['valutazione']; $i++) echo "★"; ?>
                                    <?php for($i=$review['valutazione']; $i<5; $i++) echo "☆"; ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-muted">Nessuna recensione presente.</p>
            <?php endif; ?>
        </section>
    </div>
<?php
} else {
    echo "<div class='container mt-5'><div class='alert alert-danger'>Articolo non trovato.</div></div>";
}

