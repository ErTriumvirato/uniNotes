<?php
$appunto = $templateParams["appunto"]; 

if (!empty($appunto)) {
    $dbh->incrementArticleViews($_GET['id']);
    $reviews = $dbh->getReviewsByArticle($_GET['id']);
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
                            <span>Autore: <a href="profilo.php?id=<?php echo $appunto['idutente']; ?>" class="text-decoration-none fw-bold"><?php echo htmlspecialchars($appunto['autore']); ?></a></span>
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
                        <?php echo (int)$appunto['numero_visualizzazioni']; ?> Visualizzazioni
                    </span>
                    <span id="avg-rating-badge" class="badge bg-light text-dark border p-2">
                        ★ <?php echo $appunto['media_recensioni'] ?: 'N/A'; ?>
                    </span>
                </footer>
            </div>
        </article>

        <!-- Reviews Section -->
        <section aria-labelledby="reviews-title">
            <h3 id="reviews-title" class="mb-4">Recensioni</h3>
            
            <?php 
            $isAuthor = isUserLoggedIn() && $_SESSION['idutente'] == $appunto['idutente'];
            if (isUserLoggedIn() && !$isAuthor && !$dbh->hasUserReviewed($appunto['idappunto'], $_SESSION['idutente'])): 
            ?>
                <section aria-label="Scrivi una recensione" id="review-form-card" class="card shadow-sm border-0 mb-4 form-card">
                    <div class="card-body p-4">
                        <h5 class="card-title mb-3">Lascia una recensione</h5>
                        <form id="review-form" data-idappunto="<?php echo htmlspecialchars($appunto['idappunto']); ?>" onsubmit="handleReviewFormSubmit(event)">
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
                            <button type="submit" class="btn btn-outline-primary">Invia Recensione</button>
                        </form>
                    </div>
                </section>
            <?php elseif (isUserLoggedIn() && !$isAuthor): ?>
                <div id="already-reviewed-card" class="card shadow-sm border-0 mb-4 bg-light">
                    <div class="card-body p-4 text-center">
                        <p class="mb-0 text-muted fst-italic">Hai già recensito questo appunto.</p>
                    </div>
                </div>
            <?php elseif (!isUserLoggedIn()): ?>
                <div class="alert alert-warning mb-4" role="alert">
                    Effettua il <a href="login.php?redirect=<?= getCurrentURI(); ?>" class="alert-link">login</a> per lasciare una recensione.
                </div>
            <?php endif; ?>

            <div id="reviews-list">

            <div id="reviews-list">
                <?php if (!empty($reviews)): ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($reviews as $review): ?>
                            <div class="card shadow-sm border-0" data-review-id="<?php echo $review['idrecensione']; ?>">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="card-title mb-0"><?php echo htmlspecialchars($review['username']); ?></h5>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="text-warning">
                                                <?php for($i=0; $i<$review['valutazione']; $i++) echo "★"; for($i=$review['valutazione']; $i<5; $i++) echo "☆";?>
                                            </div>
                                            <?php if (isUserLoggedIn() && $_SESSION['idutente'] == $review['idutente']): ?>
                                                <button class="btn btn-sm btn-outline-danger delete-review-btn" data-review-id="<?php echo $review['idrecensione']; ?>" aria-label="Elimina recensione" onclick="handleDeleteReview(this)" title="Elimina">
                                                    <i class="bi bi-trash" aria-hidden="true"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">Nessuna recensione presente.</p>
                <?php endif; ?>
            </div>
        </section>
    </div>
</div>
<?php
} else {
    echo '<div class="alert alert-danger text-center" role="alert">Appunto non trovato.</div>';
}
?>

<script>
    // Funzione per gestire il submit del form di recensione
    function handleReviewFormSubmit(e) {
        e.preventDefault();

        const form = e.target;
        const idappunto = form.dataset.idappunto;
        const valutazione = form.querySelector('#valutazione').value;
        const submitBtn = form.querySelector('button[type="submit"]');

        if (!valutazione) {
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Invio in corso...';

        fetch('appunto.php?id=' + idappunto, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'valutazione=' + encodeURIComponent(valutazione) + '&ajax=1'
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Nascondi il form e mostra il messaggio
                const formCard = document.getElementById('review-form-card');
                if (formCard) {
                    formCard.outerHTML = `
                        <div id="already-reviewed-card" class="card shadow-sm border-0 mb-4 bg-light">
                            <div class="card-body p-4 text-center">
                                <p class="mb-0 text-muted fst-italic">Hai già recensito questo appunto.</p>
                            </div>
                        </div>
                    `;
                }

                // Aggiorna la media delle recensioni
                const avgBadge = document.getElementById('avg-rating-badge');
                avgBadge.textContent = '★ ' + data.new_avg;

                // Aggiungi la nuova recensione alla lista
                const reviewsList = document.getElementById('reviews-list');
                const stars = '★'.repeat(data.review.valutazione) + '☆'.repeat(5 - data.review.valutazione);

                // Se non ci sono recensioni, rimuovi il messaggio "Nessuna recensione"
                const noReviewsMsg = reviewsList.querySelector('.text-muted');
                if (noReviewsMsg) {
                    reviewsList.innerHTML = '<div class="d-flex flex-column gap-3"></div>';
                }

                // Aggiungi la nuova recensione all'inizio della lista
                const reviewsContainer = reviewsList.querySelector('.d-flex.flex-column.gap-3');
                reviewsContainer.insertAdjacentHTML('afterbegin', `
                    <div class="card shadow-sm border-0" data-review-id="${data.review.idrecensione}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="card-title mb-0">${data.review.username}</h5>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="text-warning">
                                        ${stars}
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger delete-review-btn" data-review-id="${data.review.idrecensione}" aria-label="Elimina recensione" onclick="handleDeleteReview(this)" title="Elimina">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }
        })
        .catch(() => {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Invia Recensione';
            showError('Si è verificato un errore. Riprova.');
        });
    }

    // Gestione eliminazione recensioni
    function handleDeleteReview(btn) {
        const idrecensione = btn.dataset.reviewId;
        const reviewCard = btn.closest('.card[data-review-id]');

        if (!btn.dataset.confirm) {
            btn.dataset.confirm = 'true';
            btn.innerHTML = '<i class="bi bi-check-lg"></i>';
            btn.classList.remove('btn-outline-danger');
            btn.classList.add('btn-danger');
            setTimeout(() => {
                if (btn.dataset.confirm) {
                    delete btn.dataset.confirm;
                    btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i>';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-outline-danger');
                }
            }, 3000);
            return;
        }

        btn.disabled = true;
        btn.textContent = 'Eliminando...';

        fetch('appunto.php?id=<?php echo htmlspecialchars($appunto['idappunto']); ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'deleteReview=' + encodeURIComponent(idrecensione) + '&ajax=1'
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Rimuovi la card della recensione con animazione
                reviewCard.style.transition = 'opacity 0.3s';
                reviewCard.style.opacity = '0';
                setTimeout(() => {
                    reviewCard.remove();

                    // Controlla se non ci sono più recensioni
                    const reviewsList = document.getElementById('reviews-list');
                    const remainingReviews = reviewsList.querySelectorAll('.card[data-review-id]');
                    if (remainingReviews.length === 0) {
                        reviewsList.innerHTML = '<p class="text-muted">Nessuna recensione presente.</p>';
                    }

                    // Aggiorna la media delle recensioni
                    const avgBadge = document.getElementById('avg-rating-badge');
                    avgBadge.textContent = '★ ' + data.new_avg;

                    // Mostra il form per lasciare una nuova recensione
                    const alreadyReviewedCard = document.getElementById('already-reviewed-card');
                    if (alreadyReviewedCard) {
                        alreadyReviewedCard.outerHTML = `
                            <div id="review-form-card" class="card shadow-sm border-0 mb-4 form-card">
                                <div class="card-body p-4">
                                    <h5 class="card-title mb-3">Lascia una recensione</h5>
                                    <form id="review-form" data-idappunto="<?php echo htmlspecialchars($appunto['idappunto']); ?>" onsubmit="handleReviewFormSubmit(event)">
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
                                        <button type="submit" class="btn btn-outline-primary">Invia Recensione</button>
                                    </form>
                                </div>
                            </div>
                        `;
                    }
                }, 300);
            } else {
                showError(data.message || 'Errore durante l\'eliminazione della recensione.');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i>';
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i>';
            showError('Si è verificato un errore. Riprova.');
        });
    }
</script>
