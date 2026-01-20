<?php
$appunto = $templateParams["appunto"];

if (!empty($appunto)) {
    $dbh->incrementArticleViews($_GET['id']);
?>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <article class="card shadow-sm border-0 mb-5">
                <div class="card-body p-4 p-md-5">
                    <header class="mb-4 border-bottom pb-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                            <h1 class="display-5 fw-bold mb-3"><?php echo htmlspecialchars($appunto['titolo']); ?></h1>
                        </div>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                            <?php if (isUserAdmin()): ?>
                                <?php
                                $statusMap = [
                                    'in_revisione' => ['label' => 'Da approvare', 'class' => 'bg-warning text-dark'],
                                    'approvato' => ['label' => 'Approvato', 'class' => 'bg-success'],
                                    'rifiutato' => ['label' => 'Rifiutato', 'class' => 'bg-danger']
                                ];
                                $statusInfo = $statusMap[$appunto['stato']] ?? ['label' => $appunto['stato'], 'class' => 'bg-secondary'];
                                ?>

                                <div class="d-flex gap-2 align-items-center">
                                    <span id="status-badge" class="badge <?php echo $statusInfo['class']; ?>"><?php echo $statusInfo['label']; ?></span>

                                    <div class="d-flex gap-2" id="admin-actions">
                                        <?php if ($appunto['stato'] === 'in_revisione'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-success btn-approve" data-id="<?= $appunto['idappunto'] ?>" title="Approva">
                                                <i class="bi bi-check-lg" aria-hidden="true"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-warning btn-reject" data-id="<?= $appunto['idappunto'] ?>" title="Rifiuta">
                                                <i class="bi bi-x-lg" aria-hidden="true"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-article" data-id="<?= $appunto['idappunto'] ?>" title="Elimina">
                                            <i class="bi bi-trash" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
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
                            <div class="vr d-none d-md-block"></div>
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-dark border p-2">
                                    <?php echo (int)$appunto['numero_visualizzazioni']; ?> Visualizzazioni
                                </span>
                                <span id="avg-rating-badge" class="badge bg-light text-dark border p-2">
                                    ★ <?php echo $appunto['media_recensioni'] ?: 'N/A'; ?> (<?php echo (int)($appunto['numero_recensioni'] ?? 0); ?>)
                                </span>
                            </div>
                        </div>
                    </header>

                    <div class="article-content mb-4">
                        <?php echo nl2br(htmlspecialchars($appunto['contenuto'])); ?>
                    </div>

                    <?php
                    $isAuthor = isUserLoggedIn() && $_SESSION['idutente'] == $appunto['idutente'];
                    if (($appunto['stato'] === 'approvato' || isUserAdmin()) && !$isAuthor):
                    ?>
                        <section aria-label="Sezione Recensioni" id="reviews-section" class="mb-0 pt-4 border-top <?php echo ($appunto['stato'] !== 'approvato') ? 'd-none' : ''; ?>">
                            <h2 class="visually-hidden">Recensioni</h2>
                            <?php
                            $userReview = (isUserLoggedIn() && !$isAuthor) ? $dbh->getUserReview($appunto['idappunto'], $_SESSION['idutente']) : null;

                            // Mostra il form di recensione solo se l'utente è loggato, non è l'autore e non ha già recensito
                            if (isUserLoggedIn() && !$isAuthor && !$userReview && ($appunto['stato'] === 'approvato' || isUserAdmin())):
                            ?>
                                <div id="review-form-container">
                                    <h5 class="mb-3">Lascia una recensione</h5>
                                    <form id="review-form" data-idappunto="<?php echo htmlspecialchars($appunto['idappunto']); ?>">
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
                            <?php elseif (isUserLoggedIn() && !$isAuthor && $userReview): // Utente ha già recensito 
                            ?>
                                <div id="already-reviewed-container" class="d-flex justify-content-between align-items-center bg-light p-3 rounded" data-review-id="<?php echo $userReview['idrecensione']; ?>" data-idappunto="<?php echo $appunto['idappunto']; ?>">
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="fw-bold">La tua recensione:</span>
                                        <span class="text-warning" role="img" aria-label="Valutazione: <?php echo $userReview['valutazione']; ?> su 5 stelle">
                                            <span aria-hidden="true">
                                                <?php for ($i = 0; $i < $userReview['valutazione']; $i++) echo "★";
                                                for ($i = $userReview['valutazione']; $i < 5; $i++) echo "☆"; ?>
                                            </span>
                                        </span>
                                    </div>
                                    <button class="btn btn-sm btn-outline-danger delete-review-btn" data-review-id="<?php echo $userReview['idrecensione']; ?>" aria-label="Elimina recensione" title="Elimina">
                                        <i class="bi bi-trash" aria-hidden="true"></i>
                                    </button>
                                </div>
                            <?php elseif (!isUserLoggedIn()): // Utente non loggato
                            ?>
                                <p class="mb-0 text-muted">
                                    <a href="login.php?redirect=<?= getCurrentURI(); ?>">Accedi</a> per lasciare una recensione.
                                </p>
                            <?php endif; ?>
                        </section>
                    <?php endif; ?>
                </div>
            </article>
        </div>
    </div>
<?php
} else {
    echo '<div class="alert alert-danger text-center" role="alert">Appunto non trovato.</div>';
}
?>