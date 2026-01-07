<?php
$idCorso = (int)$_GET['id'];
$corso = $dbh->getCourseById($idCorso);
$idutente = $_SESSION["idutente"] ?? null;
$isFollowing = $idutente ? $dbh->isFollowingCourse($idutente, $idCorso) : false;
$articoli = $dbh->getApprovedArticlesByCourse($idCorso);
?>

<a href="corsi.php"><img src="uploads/img/back.png" alt="Torna alla pagina precedente" class="back-img"/></a>

<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h1 class="display-5 fw-bold mb-2"><?php echo htmlspecialchars($corso['nome']); ?></h1>
                        <span class="badge bg-secondary mb-3"><?php echo htmlspecialchars($corso['nomeSSD']); ?></span>
                    </div>
                    <button type="button" id="followBtn" class="btn <?php echo htmlspecialchars($isFollowing ? 'btn-outline-danger' : 'btn-primary'); ?> btn-lg" data-idcorso="<?php echo htmlspecialchars($idCorso); ?>">
                        <?php echo htmlspecialchars($isFollowing ? 'Smetti di seguire' : 'Segui corso'); ?>
                    </button>
                    <a href="creazione-articoli.php?idcorso=<?php echo htmlspecialchars($idCorso); ?>" class="btn btn-outline-secondary btn-lg">Crea articolo</a>
                </div>
                <p class="lead mt-3"><?php echo nl2br(htmlspecialchars($corso['descrizione'])); ?></p>
            </div>
        </div>

        <div class="row g-3 mb-4 align-items-end">
            <div class="col-12 col-md-6">
                <h3 class="mb-0">Appunti disponibili</h3>
            </div>
            <div class="col-6 col-md-3">
                <label for="ajax-sort" class="form-label small text-muted">Ordina per</label>
                <select id="ajax-sort" class="form-select form-select-sm">
                    <option value="data_pubblicazione">Data di caricamento</option>
                    <option value="media_recensioni">Valutazione media</option>
                    <option value="numero_visualizzazioni">Numero di visualizzazioni</option>
                </select>
            </div>
            <div class="col-6 col-md-3">
                <label for="ajax-order" class="form-label small text-muted">Ordine</label>
                <select id="ajax-order" class="form-select form-select-sm">
                    <option value="DESC">Decrescente</option>
                    <option value="ASC">Crescente</option>
                </select>
            </div>
        </div>

        <!-- Articles List -->
        <div id="articles-container" class="d-flex flex-column gap-3">
            <?php if (!empty($articoli)): foreach ($articoli as $articolo): ?>
                    <div class="card shadow-sm border-0 article-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-8">
                                    <h5 class="card-title mb-1">
                                        <a href="articolo.php?id=<?php echo htmlspecialchars($articolo['idarticolo']); ?>" class="text-decoration-none text-dark stretched-link">
                                            <?php echo htmlspecialchars($articolo['titolo']); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted small mb-2">
                                        di <?php echo htmlspecialchars($articolo['autore']); ?>
                                    </p>
                                </div>
                                <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                                    <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                                        <span class="badge bg-light text-dark border" title="Media recensioni">
                                            ★ <?php echo htmlspecialchars($articolo['media_recensioni'] ?: '0.0'); ?>
                                        </span>
                                        <span class="badge bg-light text-dark border" title="Visualizzazioni">
                                            👁 <?php echo htmlspecialchars((int)$articolo['numero_visualizzazioni']); ?>
                                        </span>
                                        <span class="badge bg-light text-dark border" title="Data pubblicazione">
                                            📅 <?php echo htmlspecialchars(date('d/m/y', strtotime($articolo['data_pubblicazione']))); ?>
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
    </div>
</div>

<script>
    document.getElementById('followBtn')?.addEventListener('click', function() {
        <?php if (!$idutente): ?>
            window.location.href = 'login.php';
            return;
        <?php endif; ?>

        const idcorso = this.dataset.idcorso;
        const btn = this;
        btn.disabled = true;

        fetch('corso.php?id=' + idcorso, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'toggleFollow=' + encodeURIComponent(idcorso)
            })
            .then(res => res.json())
            .then(data => {
                if (data.following) {
                    btn.textContent = 'Smetti di seguire';
                    btn.classList.remove('btn-primary');
                    btn.classList.add('btn-outline-danger');
                } else {
                    btn.textContent = 'Segui corso';
                    btn.classList.remove('btn-outline-danger');
                    btn.classList.add('btn-primary');
                }
                btn.disabled = false;
            });
    });

    const sortSelect = document.getElementById('ajax-sort');
    const orderSelect = document.getElementById('ajax-order');
    const container = document.getElementById('articles-container');

    function updateArticles() {
        const url = `corso.php?id=<?php echo $idCorso; ?>&action=filter&sort=${sortSelect.value}&order=${orderSelect.value}`;

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
                                                <a href="articolo.php?id=${art.idarticolo}" class="text-decoration-none text-dark stretched-link">
                                                    ${art.titolo}
                                                </a>
                                            </h5>
                                            <p class="card-text text-muted small mb-2">
                                                di ${art.autore}
                                            </p>
                                        </div>
                                        <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                                            <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                                                <span class="badge bg-light text-dark border" title="Media recensioni">
                                                    ★ ${art.media_recensioni || '0.0'}
                                                </span>
                                                <span class="badge bg-light text-dark border" title="Visualizzazioni">
                                                    👁 ${art.views}
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

    sortSelect.addEventListener('change', updateArticles);
    orderSelect.addEventListener('change', updateArticles);
</script>