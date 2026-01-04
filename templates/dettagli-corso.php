<?php
$idCorso = (int)$_GET['id'];
$corso = $dbh->getCourseById($idCorso);
$idutente = $_SESSION["idutente"] ?? null;
$isFollowing = $idutente ? $dbh->isFollowingCourse($idutente, $idCorso) : false;
$articoli = $dbh->getApprovedArticlesByCourse($idCorso);
?>

<div>

    <header>
        <h1><?php echo htmlspecialchars($corso['nome']); ?></h1>
        <p>SSD: <?php echo htmlspecialchars($corso['nomeSSD']); ?></p>

        <div>
            <p><?php echo nl2br(htmlspecialchars($corso['descrizione'])); ?></p>
        </div>

        <button type="button" id="followBtn" data-idcorso="<?php echo $idCorso; ?>">
            <span>
                <img src="uploads/img/<?php echo $isFollowing ? 'unfollow.svg' : 'follow.svg'; ?>" alt="">
                <span><?php echo $isFollowing ? 'Smetti di seguire' : 'Segui'; ?></span>
            </span>
        </button>
    </header>

    <div>
        <div>
            <div>
                <label for="ajax-sort">Ordina per:</label>
                <select id="ajax-sort">
                    <option value="data_pubblicazione">Data di caricamento</option>
                    <option value="media_recensioni">Valutazione media</option>
                    <option value="numero_visualizzazioni">Numero di visualizzazioni</option>
                </select>
            </div>
            <div>
                <label for="ajax-order">Ordine:</label>
                <select id="ajax-order">
                    <option value="DESC">Decrescente</option>
                    <option value="ASC">Crescente</option>
                </select>
            </div>
        </div>
    </div>

    <div id="articles-container">
        <?php if (!empty($articoli)): foreach ($articoli as $articolo): ?>
                <article>
                    <a href="articolo.php?id=<?php echo $articolo['idarticolo']; ?>">
                        <div>
                            <h2>
                                <?php echo htmlspecialchars($articolo['titolo']); ?>
                            </h2>
                            <div>
                                <span><?php echo htmlspecialchars($articolo['autore']); ?></span>
                                <span>|</span>
                                <span>Media recensioni: <?php echo $articolo['media_recensioni'] ?: '0.0'; ?></span>
                                <span>|</span>
                                <span><?php echo (int)$articolo['numero_visualizzazioni']; ?></span>
                                <span>|</span>
                                <span><?php echo date('d/m/y', strtotime($articolo['data_pubblicazione'])); ?></span>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endforeach;
        else: ?>
            <p>Nessun appunto disponibile per questo corso.</p>
        <?php endif; ?>
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
                const icon = data.following ? 'unfollow.svg' : 'follow.svg';
                const text = data.following ? 'Smetti di seguire' : 'Segui';
                btn.innerHTML = `<span>
                                <img src="uploads/img/${icon}" alt="">
                                <span>${text}</span>
                             </span>`;
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
                    container.innerHTML = '<p>Nessun appunto trovato.</p>';
                } else {
                    data.forEach(art => {
                        container.insertAdjacentHTML('beforeend', `
                            <article>
                                <a href="articolo.php?id=${art.idarticolo}">
                                    <div>
                                        <h2>${art.titolo}</h2>
                                        <div>
                                            <span>${art.autore}</span>
                                            <span>Media recensioni: ${art.media_recensioni}</span>
                                            <span>|</span>
                                            <span>Visualizzazioni: ${art.views}</span>
                                            <span>|</span>
                                            <span>${art.data_formattata}</span>
                                        </div>
                                    </div>
                                </a>
                            </article>`);
                    });
                }
            });
    }

    sortSelect.addEventListener('change', updateArticles);
    orderSelect.addEventListener('change', updateArticles);
</script>