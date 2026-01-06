<?php
$userId = isUserLoggedIn() ? $_SESSION['idutente'] : null;
$latestArticles = $dbh->getHomeArticles($userId, 'data_pubblicazione', 6);
$mostViewedArticles = $dbh->getHomeArticles($userId, 'numero_visualizzazioni', 6);
?>

<section class="mb-5">
    <h2 class="mb-4">Ultimi Articoli Usciti</h2>
    <?php if (empty($latestArticles)): ?>
        <p>Nessun articolo trovato.</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($latestArticles as $articolo): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card h-100 border-0 shadow-sm article-card">
                        <div class="card-body d-flex flex-column">
                            <h3 class="h5 card-title mb-3">
                                <a href="articolo.php?id=<?php echo $articolo['idarticolo']; ?>" class="text-decoration-none stretched-link article-title-link">
                                    <?php echo htmlspecialchars($articolo['titolo']); ?>
                                </a>
                            </h3>
                            <div class="mt-auto">
                                <p class="mb-2 small">
                                    di <strong><?php echo htmlspecialchars($articolo['autore']); ?></strong>
                                    <br>
                                    <?php echo date('d/m/Y', strtotime($articolo['data_pubblicazione'])); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge border badge-vote">
                                        ★ <?php echo $articolo['media_recensioni'] ? htmlspecialchars($articolo['media_recensioni']) : '-'; ?>
                                    </span>
                                    <small><?php echo $articolo['numero_visualizzazioni']; ?> views</small>
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
    <h2 class="mb-4">Articoli Più Visualizzati</h2>
    <?php if (empty($mostViewedArticles)): ?>
        <p>Nessun articolo visualizzato.</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($mostViewedArticles as $articolo): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card h-100 border-0 shadow-sm article-card">
                        <div class="card-body d-flex flex-column">
                            <h3 class="h5 card-title mb-3">
                                <a href="articolo.php?id=<?php echo $articolo['idarticolo']; ?>" class="text-decoration-none stretched-link article-title-link">
                                    <?php echo htmlspecialchars($articolo['titolo']); ?>
                                </a>
                            </h3>
                            <div class="mt-auto">
                                <p class="mb-2 small">
                                    di <strong><?php echo htmlspecialchars($articolo['autore']); ?></strong>
                                    <br>
                                    <?php echo date('d/m/Y', strtotime($articolo['data_pubblicazione'])); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge border badge-vote">
                                        ★ <?php echo $articolo['media_recensioni'] ? htmlspecialchars($articolo['media_recensioni']) : '-'; ?>
                                    </span>
                                    <small><?php echo $articolo['numero_visualizzazioni']; ?> views</small>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>