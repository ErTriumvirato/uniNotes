<?php
$stats = [
    "followed_courses" => $dbh->getFollowedCoursesCount($idProfile),
    "articles_written" => $dbh->getArticlesCountByAuthor($idProfile, true),
    "avg_rating" => $dbh->getAuthorAverageRating($idProfile)
];
$appunti = $dbh->getApprovedArticlesByUserIdWithFilters($templateParams['userProfile']['idutente'], 'data_pubblicazione', 'DESC');
?>

<h2 class="mb-4"><?php echo htmlspecialchars($templateParams['userProfile']['username']); ?></h2>

<div class="row g-4 mb-5">
    <div class="col-12 col-md-4">
        <div class="card h-100 border-0 shadow-sm text-center p-3">
            <div class="card-body">
                <h3 class="h1 fw-bold text-primary"><?php echo $stats["followed_courses"]; ?></h3>
                <p class="mb-0 text-muted">Corsi Seguiti</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100 border-0 shadow-sm text-center p-3">
            <div class="card-body">
                <h3 class="h1 fw-bold text-primary"><?php echo $stats["articles_written"]; ?></h3>
                <p class="mb-0 text-muted">Articoli Scritti</p>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card h-100 border-0 shadow-sm text-center p-3">
            <div class="card-body">
                <h3 class="h1 fw-bold text-primary"><?php echo $stats["avg_rating"]; ?></h3>
                <p class="mb-0 text-muted">Media Recensioni</p>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4 align-items-end">
    <div class="col-12 col-md-6">
        <h3 class="mb-0">Appunti scritti da <?= $templateParams['userProfile']['username'] ?></h3>
    </div>
    <div class="col-6 col-md-3">
        <label for="ajax-sort" class="form-label small text-muted">Ordina per</label>
        <select id="ajax-sort" class="form-select form-select-sm" onchange="updateArticles()">
            <option value="data_pubblicazione">Data di caricamento</option>
            <option value="media_recensioni">Valutazione media</option>
            <option value="numero_visualizzazioni">Numero di visualizzazioni</option>
        </select>
    </div>
    <div class="col-6 col-md-3">
        <label for="ajax-order" class="form-label small text-muted">Ordine</label>
        <select id="ajax-order" class="form-select form-select-sm" onchange="updateArticles()">
            <option value="DESC">Decrescente</option>
            <option value="ASC">Crescente</option>
        </select>
    </div>
</div>

<!-- Articles List -->
<div id="articles-container" class="d-flex flex-column gap-3">
    <?php if (!empty($appunti)): foreach ($appunti as $appunto): ?>
            <div class="card shadow-sm border-0 article-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-8">
                            <h5 class="card-title mb-1">
                                <a href="appunto.php?id=<?php echo htmlspecialchars($appunto['idappunto']); ?>" class="text-decoration-none text-dark stretched-link">
                                    <?php echo htmlspecialchars($appunto['titolo']); ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-2">
                                di <?php echo htmlspecialchars($templateParams['userProfile']['username']); ?>
                            </p>
                        </div>
                        <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                            <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                                <span class="badge bg-light text-dark border" title="Media recensioni">
                                    ★ <?php echo htmlspecialchars($appunto['media_recensioni'] ?: '0.0'); ?>
                                </span>
                                <span class="badge bg-light text-dark border" title="Visualizzazioni">
                                    <?php echo htmlspecialchars((int)$appunto['numero_visualizzazioni']); ?> Visualizzazioni
                                </span>
                                <span class="badge bg-light text-dark border" title="Data pubblicazione">
                                    📅 <?php echo htmlspecialchars(date('d/m/y', strtotime($appunto['data_pubblicazione']))); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach;
    else: ?>
        <div class="alert alert-info text-center" role="alert">
            Nessun appunto disponibile per questo corso.
        </div>
    <?php endif; ?>
</div>
<script>
    const sortSelect = document.getElementById('ajax-sort');
    const orderSelect = document.getElementById('ajax-order');
    const container = document.getElementById('articles-container');

    function updateArticles() {
        const url = `profilo.php?id=<?php echo $templateParams['userProfile']['idutente']; ?>&action=filter&sort=${sortSelect.value}&order=${orderSelect.value}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                if (data.length === 0) {
                    container.innerHTML = '<div class="alert alert-info text-center" role="alert">Nessun appunto disponibile per questo corso.</div>';
                } else {
                    data.forEach(art => {
                        container.insertAdjacentHTML('beforeend', `
                            <div class="card shadow-sm border-0 article-card">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-12 col-md-8">
                                            <h5 class="card-title mb-1">
                                                <a href="appunto.php?id=${art.idappunto}" class="text-decoration-none text-dark stretched-link">
                                                    ${art.titolo}
                                                </a>
                                            </h5>
                                            <p class="card-text text-muted small mb-2">
                                                di <?php echo htmlspecialchars($templateParams['userProfile']['username']); ?>
                                            </p>
                                        </div>
                                        <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                                            <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                                                <span class="badge bg-light text-dark border" title="Media recensioni">
                                                    ★ ${art.media_recensioni || '0.0'}
                                                </span>
                                                <span class="badge bg-light text-dark border" title="Visualizzazioni">
                                                    ${art.views} Visualizzazioni
                                                </span>
                                                <span class="badge bg-light text-dark border" title="Data pubblicazione">
                                                    📅 ${art.data_formattata}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`);
                    });
                }
            });
    }

</script>
