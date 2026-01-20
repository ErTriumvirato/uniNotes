<?php
$userId = isUserLoggedIn() ? $_SESSION['idutente'] : null;
$lastNotes = $dbh->getHomeNotes($userId, 'data_pubblicazione', 6);
$mostViewedNotes = $dbh->getHomeNotes($userId, 'numero_visualizzazioni', 6);
$seguendoCorsi = $userId ? $dbh->getFollowedCoursesCount($userId) : false;
?>

<header class="row mb-3">
    <div class="col-12 text-center">
        <h1 class="display-4 fw-bold">Benvenutə<?php if (isUserLoggedIn()) echo ' ' . $_SESSION['username'] ?>!</h1>
    </div>
</header>

<?php if ($userId && !$seguendoCorsi): ?>
    <div class="alert alert-info mb-5" role="alert">
        <h2 class="alert-heading h4">Non segui ancora nessun corso!</h2>
        <p>Inizia a seguire dei corsi per poter visualizzare degli appunti qui.</p>
        <a href="corsi.php" class="btn btn-outline-primary">Esplora i corsi</a>
    </div>
<?php endif; ?>

<section class="mb-5">
    <h2 class="mb-4">Ultimi appunti usciti</h2>
    <?php if (empty($lastNotes)): ?>
        <p>Nessun appunto trovato.</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($lastNotes as $note): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card h-100 border-0 shadow-sm note-card">
                        <div class="card-body d-flex flex-column">
                            <h3 class="h5 card-title mb-3">
                                <a href="appunto.php?id=<?php echo $note['idappunto']; ?>" class="text-decoration-none stretched-link note-title-link">
                                    <?php echo htmlspecialchars($note['titolo']); ?>
                                </a>
                            </h3>
                            <div class="mt-auto">
                                <small class="text-muted mb-2 d-block"><?php echo htmlspecialchars($note['nome_corso']); ?></small>
                                <p class="mb-2 small">
                                    di <strong><?php echo htmlspecialchars($note['autore']); ?></strong>
                                    <br />
                                    <?php echo date('d/m/Y', strtotime($note['data_pubblicazione'])); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge border badge-vote" role="img" aria-label="Valutazione: <?php echo $note['media_recensioni'] ? htmlspecialchars($note['media_recensioni']) : 'Non disponibile'; ?> su 5">
                                        <span aria-hidden="true">★ <?php echo $note['media_recensioni'] ? htmlspecialchars($note['media_recensioni']) : 'N/A'; ?></span>
                                    </span>
                                    <small><?php echo $note['numero_visualizzazioni']; ?> visualizzazioni</small>
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
    <?php if (empty($mostViewedNotes)): ?>
        <p>Nessun appunto visualizzato.</p>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($mostViewedNotes as $note): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <article class="card h-100 border-0 shadow-sm note-card">
                        <div class="card-body d-flex flex-column">
                            <h3 class="h5 card-title mb-3">
                                <a href="appunto.php?id=<?php echo $note['idappunto']; ?>" class="text-decoration-none stretched-link note-title-link">
                                    <?php echo htmlspecialchars($note['titolo']); ?>
                                </a>
                            </h3>
                            <div class="mt-auto">
                                <small class="text-muted mb-2 d-block"><?php echo htmlspecialchars($note['nome_corso']); ?></small>
                                <p class="mb-2 small">
                                    di <strong><?php echo htmlspecialchars($note['autore']); ?></strong>
                                    <br />
                                    <?php echo date('d/m/Y', strtotime($note['data_pubblicazione'])); ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge border badge-vote" role="img" aria-label="Valutazione: <?php echo $note['media_recensioni'] ? htmlspecialchars($note['media_recensioni']) : 'Non disponibile'; ?> su 5">
                                        <span aria-hidden="true">★ <?php echo $note['media_recensioni'] ? htmlspecialchars($note['media_recensioni']) : 'N/A'; ?></span>
                                    </span>
                                    <small><?php echo $note['numero_visualizzazioni']; ?> visualizzazioni</small>
                                </div>
                            </div>
                        </div>
                    </article>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>