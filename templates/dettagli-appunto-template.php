<?php
$note = $templateParams["appunto"]; // Recupero i dettagli dell'appunto

if (!empty($note)) {
    $dbh->incrementNoteViews($_GET['id']); // Se l'appunto esiste, incremento le visualizzazioni
?>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <article class="card shadow-sm border-0 mb-5">
                <div class="card-body p-4 p-md-5">
                    <header class="mb-4 border-bottom pb-3">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                            <h1 class="display-5 fw-bold mb-3"><?php echo htmlspecialchars($note['titolo']); ?></h1>
                        </div>
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">

                            <!-- L'amministratore può approvare, rifiutare o eliminare l'appunto -->
                            <?php if (isUserAdmin()): ?>
                                <?php
                                $statusMap = [
                                    'in_revisione' => ['label' => 'Da approvare', 'class' => 'bg-warning text-dark'],
                                    'approvato' => ['label' => 'Approvato', 'class' => 'bg-success'],
                                    'rifiutato' => ['label' => 'Rifiutato', 'class' => 'bg-danger']
                                ];
                                $statusInfo = $statusMap[$note['stato']] ?? ['label' => $note['stato'], 'class' => 'bg-secondary'];
                                ?>

                                <div class="d-flex gap-2 align-items-center">

                                    <!-- Badge di stato dell'appunto -->
                                    <span id="status-badge" class="badge <?php echo $statusInfo['class']; ?>"><?php echo $statusInfo['label']; ?></span>

                                    <!-- Pulsanti di approvazione, rifiuto e eliminazione -->
                                    <div class="d-flex gap-2" id="admin-actions">
                                        <?php if ($note['stato'] === 'in_revisione'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-success btn-approve" data-id="<?= $note['idappunto'] ?>" title="Approva">
                                                <em class="bi bi-check-lg" aria-hidden="true"></em>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-warning btn-reject" data-id="<?= $note['idappunto'] ?>" title="Rifiuta">
                                                <em class="bi bi-x-lg" aria-hidden="true"></em>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete-note" data-id="<?= $note['idappunto'] ?>" title="Elimina">
                                            <em class="bi bi-trash" aria-hidden="true"></em>
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Dettagli sull'appunto -->
                        <div class="d-flex flex-wrap gap-3 text-muted align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <span>Autore: <a href="profilo-utente.php?id=<?php echo $note['idutente']; ?>" class="fw-bold"><?php echo htmlspecialchars($note['autore']); ?></a></span>
                            </div>

                            <div class="vr d-none d-md-block "></div> <!-- Separatore verticale -->

                            <div class="d-flex align-items-center gap-2">
                                <span>Corso: <a href="corso.php?id=<?php echo $note['idcorso']; ?>" class="fw-bold"><?php echo htmlspecialchars($note['nome_corso']); ?></a></span>
                            </div>

                            <div class="vr d-none d-md-block"></div> <!-- Separatore verticale -->

                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge bg-light text-dark border p-2" title="Data pubblicazione">
                                    <?php echo date_format(date_create($note['data_pubblicazione']), 'd/m/Y'); ?>
                                </span>
                                <span class="badge bg-light text-dark border p-2" title="Visualizzazioni">
                                    <?php echo $note['numero_visualizzazioni']; ?> Visualizzazioni
                                </span>
                                <span id="avg-rating-badge" class="badge bg-light text-dark border p-2" title="Media recensioni">
                                    ★ <?php echo $note['media_recensioni'] ?: 'N/A'; ?> (<?php echo ($note['numero_recensioni'] ?? 0); ?>)
                                </span>
                            </div>
                        </div>
                    </header>

                    <!-- Contenuto dell'appunto -->
                    <div class="note-content mb-4 note-body-card rounded p-3">
                        <?php echo nl2br(htmlspecialchars($note['contenuto'])); ?>
                    </div>

                    <?php
                    // Se l'appunto è approvato o se l'utente non è l'autore, mostra la sezione per lasciare recensioni
                    $isAuthor = isUserLoggedIn() && $_SESSION['idutente'] == $note['idutente'];
                    if (($note['stato'] === 'approvato' || isUserAdmin()) && !$isAuthor):
                    ?>
                        <section aria-label="Sezione Recensioni" id="reviews-section" class="mb-0 pt-4 border-top <?php echo ($note['stato'] !== 'approvato') ? 'd-none' : ''; ?>">
                            <h2 class="visually-hidden">Recensioni</h2>
                            <?php
                            $userReview = (isUserLoggedIn() && !$isAuthor) ? $dbh->getReviewsByNote($note['idappunto'], $_SESSION['idutente']) : null;
                            $jsReviewConfig = [
                                'isLoggedIn' => isUserLoggedIn(),
                                'userReview' => $userReview ? [
                                    'idrecensione' => $userReview['idrecensione'],
                                    'valutazione' => $userReview['valutazione']
                                ] : null,
                                'idappunto' => $note['idappunto'],
                                'loginUrl' => 'login.php?redirect=' . urlencode(getCurrentURI())
                            ];
                            ?>

                            <!-- Sezione le recensioni, riempita con JavaScript -->
                            <div id="user-review-interaction" data-config='<?php echo htmlspecialchars(json_encode($jsReviewConfig)); ?>'></div>
                        </section>
                    <?php endif; ?>
                </div>
            </article>
        </div>
    </div>
<?php
} else { // Se l'appunto non esiste
    echo '<div class="alert alert-danger text-center" role="alert">Appunto non trovato.</div>';
}
?>