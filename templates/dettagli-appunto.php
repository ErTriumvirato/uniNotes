<?php
$appunto = $templateParams["appunto"]; 

if (!empty($appunto)) {
    $dbh->incrementArticleViews($_GET['id']);
?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <!-- Article Content -->
        <article class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4 p-md-5">
                <header class="mb-4 border-bottom pb-3">
                    <h1 class="display-5 fw-bold mb-3"><?php echo htmlspecialchars($appunto['titolo']); ?></h1>
                    <?php if (isUserAdmin()): ?>
                        <?php
                        $statusMap = [
                            'in_revisione' => ['label' => 'Da approvare', 'class' => 'bg-warning text-dark'],
                            'approvato' => ['label' => 'Approvato', 'class' => 'bg-success'],
                            'rifiutato' => ['label' => 'Rifiutato', 'class' => 'bg-danger']
                        ];
                        $statusInfo = $statusMap[$appunto['stato']] ?? ['label' => $appunto['stato'], 'class' => 'bg-secondary'];
                        ?>
                        <div class="mb-3">
                            <span id="status-badge" class="badge <?php echo $statusInfo['class']; ?>"><?php echo $statusInfo['label']; ?></span>
                        </div>
                    <?php endif; ?>
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

                <!-- Reviews Section inside Article -->
                <?php if ($appunto['stato'] === 'approvato' || isUserAdmin()): ?>
                <section aria-label="Sezione Recensioni" id="reviews-section" class="mb-4 pt-4 border-top" <?php echo ($appunto['stato'] !== 'approvato') ? 'style="display:none;"' : ''; ?>>
                    <?php 
                    $isAuthor = isUserLoggedIn() && $_SESSION['idutente'] == $appunto['idutente'];
                    $userReview = (isUserLoggedIn() && !$isAuthor) ? $dbh->getUserReview($appunto['idappunto'], $_SESSION['idutente']) : null;
                    
                    if (isUserLoggedIn() && !$isAuthor && !$userReview && ($appunto['stato'] === 'approvato' || isUserAdmin())): 
                    ?>
                        <div id="review-form-container">
                            <h5 class="mb-3">Lascia una recensione</h5>
                            <form id="review-form" data-idappunto="<?php echo htmlspecialchars($appunto['idappunto']); ?>" onsubmit="handleReviewFormSubmit(event)">
                                <div class="row g-2 align-items-end">
                                    <div class="col-8 col-sm-6 col-md-4">
                                        <label for="valutazione" class="form-label visually-hidden">Valutazione</label>
                                        <select name="valutazione" id="valutazione" class="form-select text-center" required>
                                            <option value="" selected disabled>Vota...</option>
                                            <option value="5">5 ★★★★★</option>
                                            <option value="4">4 ★★★★</option>
                                            <option value="3">3 ★★★</option>
                                            <option value="2">2 ★★</option>
                                            <option value="1">1 ★</option>
                                        </select>
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary">Invia</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    <?php elseif (isUserLoggedIn() && !$isAuthor && $userReview): ?>
                        <div id="already-reviewed-container" class="d-flex justify-content-between align-items-center bg-light p-3 rounded" data-review-id="<?php echo $userReview['idrecensione']; ?>">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold">La tua recensione:</span>
                                <span class="text-warning">
                                    <?php for($i=0; $i<$userReview['valutazione']; $i++) echo "★"; for($i=$userReview['valutazione']; $i<5; $i++) echo "☆";?>
                                </span>
                            </div>
                            <button class="btn btn-sm btn-outline-danger delete-review-btn" data-review-id="<?php echo $userReview['idrecensione']; ?>" aria-label="Elimina recensione" onclick="handleDeleteReview(this)" title="Elimina">
                                <i class="bi bi-trash" aria-hidden="true"></i>
                            </button>
                        </div>
                    <?php elseif (!isUserLoggedIn()): ?>
                        <p class="mb-0 text-muted">
                            <a href="login.php?redirect=<?= getCurrentURI(); ?>">Accedi</a> per lasciare una recensione.
                        </p>
                    <?php endif; ?>
                </section>
                <?php endif; ?>

                <footer class="d-flex flex-wrap gap-3 pt-3 border-top align-items-center justify-content-between">
                    <div class="d-flex gap-3">
                        <span class="badge bg-light text-dark border p-2">
                            <?php echo (int)$appunto['numero_visualizzazioni']; ?> Visualizzazioni
                        </span>
                        <span id="avg-rating-badge" class="badge bg-light text-dark border p-2">
                            ★ <?php echo $appunto['media_recensioni'] ?: 'N/A'; ?>
                        </span>
                    </div>
                    <?php if (isUserAdmin()): ?>
                    <div class="d-flex gap-2" id="admin-actions">
                        <?php if ($appunto['stato'] === 'in_revisione'): ?>
                        <button type="button" class="btn btn-sm btn-outline-success" onclick="handleApprove(<?= $appunto['idappunto'] ?>)" title="Approva">
                            <i class="bi bi-check-lg" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-warning" onclick="handleReject(<?= $appunto['idappunto'] ?>)" title="Rifiuta">
                            <i class="bi bi-x-lg" aria-hidden="true"></i>
                        </button>
                        <?php endif; ?>
                        <button type="button" class="btn btn-sm btn-outline-danger" data-id="<?= $appunto['idappunto'] ?>" onclick="handleDeleteArticle(this)" title="Elimina">
                            <i class="bi bi-trash" aria-hidden="true"></i>
                        </button>
                    </div>
                    <?php endif; ?>
                </footer>
            </div>
        </article>

        <!-- Reviews Section Removed from here -->
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
                const container = document.getElementById('review-form-container');
                if (container) {
                    const stars = '★'.repeat(data.review.valutazione) + '☆'.repeat(5 - data.review.valutazione);
                    container.outerHTML = `
                        <div id="already-reviewed-container" class="d-flex justify-content-between align-items-center bg-light p-3 rounded" data-review-id="${data.review.idrecensione}">
                            <div class="d-flex align-items-center gap-2">
                                <span class="fw-bold">La tua recensione:</span>
                                <span class="text-warning">${stars}</span>
                            </div>
                            <button class="btn btn-sm btn-outline-danger delete-review-btn" data-review-id="${data.review.idrecensione}" aria-label="Elimina recensione" onclick="handleDeleteReview(this)" title="Elimina">
                                <i class="bi bi-trash" aria-hidden="true"></i>
                            </button>
                        </div>
                    `;
                }

                // Aggiorna la media delle recensioni
                const avgBadge = document.getElementById('avg-rating-badge');
                if (avgBadge) avgBadge.textContent = '★ ' + data.new_avg;
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
        // In the new layout, the container is the element itself or slightly different
        const reviewContainer = document.getElementById('already-reviewed-container');

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
                setTimeout(() => {
                    if (reviewContainer) {
                        reviewContainer.outerHTML = `
                            <div id="review-form-container">
                                <h5 class="mb-3">Lascia una recensione</h5>
                                <form id="review-form" data-idappunto="<?php echo htmlspecialchars($appunto['idappunto']); ?>" onsubmit="handleReviewFormSubmit(event)">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-8 col-sm-6 col-md-4">
                                            <label for="valutazione" class="form-label visually-hidden">Valutazione</label>
                                            <select name="valutazione" id="valutazione" class="form-select text-center" required>
                                                <option value="" selected disabled>Vota...</option>
                                                <option value="5">5 ★★★★★</option>
                                                <option value="4">4 ★★★★</option>
                                                <option value="3">3 ★★★</option>
                                                <option value="2">2 ★★</option>
                                                <option value="1">1 ★</option>
                                            </select>
                                        </div>
                                        <div class="col-auto">
                                            <button type="submit" class="btn btn-primary">Invia</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        `;
                    }

                    // Aggiorna la media delle recensioni
                    const avgBadge = document.getElementById('avg-rating-badge');
                    if (avgBadge) avgBadge.textContent = '★ ' + data.new_avg;
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

    <?php if (isUserAdmin()): ?>
    // Handler per approvazione articolo
    function handleApprove(id) {
        const formData = new FormData();
        formData.append('action', 'approve');
        formData.append('idappunto', id);

        fetch('appunti.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Aggiorna il badge
                    const badge = document.getElementById('status-badge');
                    if (badge) {
                        badge.className = 'badge bg-success';
                        badge.textContent = 'Approvato';
                    }
                    // Rimuovi i pulsanti approva/rifiuta
                    const adminActions = document.getElementById('admin-actions');
                    if (adminActions) {
                        const approveBtn = adminActions.querySelector('button[onclick^="handleApprove"]');
                        const rejectBtn = adminActions.querySelector('button[onclick^="handleReject"]');
                        if (approveBtn) approveBtn.remove();
                        if (rejectBtn) rejectBtn.remove();
                    }
                    
                    // Mostra la sezione recensioni
                    const reviewsSection = document.getElementById('reviews-section');
                    if (reviewsSection) {
                        reviewsSection.style.display = 'block';
                    }

                    showSuccess('Appunto approvato con successo');
                } else {
                    showError('Errore durante l\'approvazione');
                }
            })
            .catch(() => showError('Errore di connessione'));
    }

    // Handler per rifiuto articolo
    function handleReject(id) {
        const formData = new FormData();
        formData.append('action', 'reject');
        formData.append('idappunto', id);

        fetch('appunti.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    // Aggiorna il badge
                    const badge = document.getElementById('status-badge');
                    if (badge) {
                        badge.className = 'badge bg-danger';
                        badge.textContent = 'Rifiutato';
                    }
                    // Rimuovi i pulsanti approva/rifiuta
                    const adminActions = document.getElementById('admin-actions');
                    if (adminActions) {
                        const approveBtn = adminActions.querySelector('button[onclick^="handleApprove"]');
                        const rejectBtn = adminActions.querySelector('button[onclick^="handleReject"]');
                        if (approveBtn) approveBtn.remove();
                        if (rejectBtn) rejectBtn.remove();
                    }
                    showSuccess('Appunto rifiutato con successo');
                } else {
                    showError('Errore durante il rifiuto');
                }
            })
            .catch(() => showError('Errore di connessione'));
    }

    // Handler per eliminazione articolo
    function handleDeleteArticle(btn) {
        const id = btn.dataset.id;

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

        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('idappunto', id);

        fetch('appunti.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    window.location.href = 'gestione-appunti.php';
                } else {
                    showError('Errore durante l\'eliminazione');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i>';
                    delete btn.dataset.confirm;
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-outline-danger');
                }
            })
            .catch(() => {
                showError('Errore di connessione');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i>';
                delete btn.dataset.confirm;
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-danger');
            });
    }
    <?php endif; ?>
</script>
