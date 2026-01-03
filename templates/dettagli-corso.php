<?php
$idCorso = (int)$_GET['id'];
$corso = $dbh->getCourseById($idCorso);
$idutente = $_SESSION["idutente"] ?? null;
$isFollowing = $idutente ? $dbh->isFollowingCourse($idutente, $idCorso) : false;
$articoli = $dbh->getApprovedArticlesByCourse($idCorso);
?>

<div class="container mt-4 px-3 pb-5">

    <header class="course-header text-center mb-5 p-4 rounded shadow-sm">
        <h1 class="h4 fw-bold text-primary mb-1"><?php echo htmlspecialchars($corso['nome']); ?></h1>
        <p class="text-secondary x-small fw-bold mb-3">SSD: <?php echo htmlspecialchars($corso['nomeSSD']); ?></p>

        <div class="mb-4">
            <p class="course-desc-text">
                <?php echo nl2br(htmlspecialchars($corso['descrizione'])); ?>
            </p>
        </div>

        <button class="btn btn-primary follow-btn-mini shadow-sm" data-idcorso="<?php echo $idCorso; ?>">
            <span class="d-flex align-items-center justify-content-center gap-2">
                <img src="uploads/img/<?php echo $isFollowing ? 'unfollow.svg' : 'follow.svg'; ?>"
                    alt="" style="width:14px; height:14px;">
                <span class="btn-text"><?php echo $isFollowing ? 'Smetti di seguire' : 'Segui'; ?></span>
            </span>
        </button>
    </header>

    <div class="mx-auto p-3 mb-5 rounded shadow-sm" style="max-width: 500px; background-color: #2c2c2c;">
        <div class="row g-2">
            <div class="col-6">
                <label for="ajax-sort" class="form-label filter-label-text mb-1">Ordina per:</label>
                <select id="ajax-sort" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="data_pubblicazione">Data di caricamento</option>
                    <option value="media_recensioni">Valutazione media</option>
                    <option value="numero_visualizzazioni">Numero di visualizzazioni</option>
                </select>
            </div>
            <div class="col-6">
                <label for="ajax-order" class="form-label filter-label-text mb-1">Ordine:</label>
                <select id="ajax-order" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="DESC">Decrescente</option>
                    <option value="ASC">Crescente</option>
                </select>
            </div>
        </div>
    </div>

    <div id="articles-container" class="d-flex flex-column gap-3">
        <?php if (!empty($articoli)): foreach ($articoli as $articolo): ?>
                <article class="card article-card-mini border-0 shadow-sm mx-auto">
                    <a href="articolo.php?id=<?php echo $articolo['idarticolo']; ?>" class="text-decoration-none text-dark">
                        <div class="card-body p-3 text-center">
                            <h2 class="h6 fw-bold mb-2 text-truncate px-2">
                                <?php echo htmlspecialchars($articolo['titolo']); ?>
                            </h2>
                            <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                <span class="badge badge-author x-small px-2"><?php echo htmlspecialchars($articolo['autore']); ?></span>
                                <span class="text-warning x-small fw-bold">★ <?php echo $articolo['media_recensioni'] ?: '0.0'; ?></span>
                                <span class="text-muted x-small">|</span>
                                <span class="text-secondary x-small">👁️ <?php echo (int)$articolo['numero_visualizzazioni']; ?></span>
                                <span class="text-muted x-small">|</span>
                                <span class="text-muted x-small"><?php echo date('d/m/y', strtotime($articolo['data_pubblicazione'])); ?></span>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endforeach;
        else: ?>
            <p class="text-center text-muted small mt-4">Nessun appunto disponibile per questo corso.</p>
        <?php endif; ?>
    </div>
</div>

<script>
    document.querySelector('.follow-btn-mini')?.addEventListener('click', function() {
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
                const icon = data.following ? 'unfollow.svg' : 'follow.svg';
                const text = data.following ? 'Smetti di seguire' : 'Segui';
                btn.innerHTML = `<span class="d-flex align-items-center justify-content-center gap-2">
                                <img src="uploads/img/${icon}" alt="" style="width:14px; height:14px;">
                                <span class="btn-text">${text}</span>
                             </span>`;
                btn.disabled = false;
            });
    });

    const sortSelect = document.getElementById('ajax-sort');
    const orderSelect = document.getElementById('ajax-order');
    const container = document.getElementById('articles-container');

    function updateArticles() {
        container.style.opacity = '0.5';
        const url = `corso.php?id=<?php echo $idCorso; ?>&action=filter&sort=${sortSelect.value}&order=${orderSelect.value}`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                if (data.length === 0) {
                    container.innerHTML = '<p class="text-center text-muted small mt-4">Nessun appunto trovato.</p>';
                } else {
                    data.forEach(art => {
                        container.insertAdjacentHTML('beforeend', `
                            <article class="card article-card-mini border-0 shadow-sm mx-auto">
                                <a href="articolo.php?id=${art.idarticolo}" class="text-decoration-none text-dark">
                                    <div class="card-body p-3 text-center">
                                        <h2 class="h6 fw-bold mb-2 text-truncate px-2">${art.titolo}</h2>
                                        <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                                            <span class="badge badge-author x-small px-2">${art.autore}</span>
                                            <span class="text-warning x-small fw-bold">★ ${art.media_recensioni}</span>
                                            <span class="text-muted x-small">|</span>
                                            <span class="text-secondary x-small">👁️ ${art.views}</span>
                                            <span class="text-muted x-small">|</span>
                                            <span class="text-muted x-small">${art.data_formattata}</span>
                                        </div>
                                    </div>
                                </a>
                            </article>`);
                    });
                }
                container.style.opacity = '1';
            });
    }

    sortSelect.addEventListener('change', updateArticles);
    orderSelect.addEventListener('change', updateArticles);
</script>