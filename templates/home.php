<?php
$userId = isUserLoggedIn() ? $_SESSION['idutente'] : null;
$latestArticles = $dbh->getHomeArticles($userId, 'data_pubblicazione', 6);
$mostViewedArticles = $dbh->getHomeArticles($userId, 'numero_visualizzazioni', 6);
$hasFollowedCourses = $userId ? $dbh->hasFollowedCourses($userId) : false;
?>

<div class="row mb-5">
    <div class="col-12 text-center">
        <h1 class="display-4 fw-bold">Benvenutə<?php if(isUserLoggedIn()) echo ' ' . $_SESSION['username']?>!</h1>
    </div>
</div>

<?php if ($userId && !$hasFollowedCourses): ?>
    <div class="alert alert-info mb-5" role="alert">
    <h4 class="alert-heading">Non segui ancora nessun corso!</h4>
    <p>Inizia a seguire dei corsi per poter visualizzare degli articoli qui.</p>
    <a href="corsi.php" class="btn btn-primary">Esplora i corsi</a>
</div>
<?php endif; ?>

<section class="mb-5">
    <h2 class="mb-4">Ultimi appunti usciti</h2>
    <?php if (empty($latestArticles)): ?>
        <p>Nessun appunto trovato.</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($latestArticles as $appunto): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card h-100 border-0 shadow-sm article-card">
                        <div class="card-body d-flex flex-column">
                            <h3 class="h5 card-title mb-3">
                                <a href="appunto.php?id=<?php echo $appunto['idappunto']; ?>" class="text-decoration-none stretched-link article-title-link">
                                    <?php echo htmlspecialchars($appunto['titolo']); ?>
                                </a>
                            </h3>
                            <div class="mt-auto">
                                <small class="text-muted mb-2 d-block"><?php echo htmlspecialchars($appunto['nome_corso']); ?></small>
                                <p class="mb-2 small">
                                    di <strong><?php echo htmlspecialchars($appunto['autore']); ?></strong>
                                    <br>
                                    <?php echo date('d/m/Y', strtotime($appunto['data_pubblicazione'])); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge border badge-vote">
                                        ★ <?php echo $appunto['media_recensioni'] ? htmlspecialchars($appunto['media_recensioni']) : '-'; ?>
                                    </span>
                                    <small><?php echo $appunto['numero_visualizzazioni']; ?> visualizzazioni</small>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section>
    <h2 class="mb-4">Appunti più visualizzati</h2>
    <?php if (empty($mostViewedArticles)): ?>
        <p>Nessun appunto visualizzato.</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($mostViewedArticles as $appunto): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card h-100 border-0 shadow-sm article-card">
                        <div class="card-body d-flex flex-column">
                            <h3 class="h5 card-title mb-3">
                                <a href="appunto.php?id=<?php echo $appunto['idappunto']; ?>" class="text-decoration-none stretched-link article-title-link">
                                    <?php echo htmlspecialchars($appunto['titolo']); ?>
                                </a>
                            </h3>
                            <div class="mt-auto">
                                <small class="text-muted mb-2 d-block"><?php echo htmlspecialchars($appunto['nome_corso']); ?></small>
                                <p class="mb-2 small">
                                    di <strong><?php echo htmlspecialchars($appunto['autore']); ?></strong>
                                    <br>
                                    <?php echo date('d/m/Y', strtotime($appunto['data_pubblicazione'])); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge border badge-vote">
                                        ★ <?php echo $appunto['media_recensioni'] ? htmlspecialchars($appunto['media_recensioni']) : '-'; ?>
                                    </span>
                                    <small><?php echo $appunto['numero_visualizzazioni']; ?> visualizzazioni</small>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
