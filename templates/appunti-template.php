<?php
global $dbh;

// TODO: mettere i parametri di ingresso in templateParams
$isAdmin = isUserAdmin();
$nomeutente = $nomeutente ?? '';
$nomecorso = $nomecorso ?? '';
$search = $search ?? ''; // Testo di ricerca iniziale
$showApprovalFilter = $isAdmin; // Mostra il filtro di approvazione solo per admin
$approvalFilter = $showApprovalFilter ? ($approvalFilter ?? 'all') : 'approved'; // Filtro approvazione: 'approved', 'pending', 'all', 'refused' (default: 'approved', 'all' per admin)
$showActions = $isAdmin; // Mostra i tasti solo per admin
$defaultMessage = $defaultMessage ?? "Nessun appunto disponibile.";
$appunti = $dbh->getArticlesWithFilters($nomeutente, $nomecorso, 'data_pubblicazione', 'DESC', $search, $approvalFilter);
?>

<section aria-label="Filtri appunti" class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="mb-0"><?= $titoloFiltri ?></h3>
        <button class="btn btn-sm btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
            <i class="bi bi-filter"></i> Filtri
        </button>
    </div>

    <div class="row g-2 align-items-end collapse d-md-flex" id="filtersCollapse">
        <div class="col-12 col-md-4">
            <label for="ajax-search" class="form-label small text-muted">Cerca</label>
            <input type="text" id="ajax-search" class="form-control form-control-sm" placeholder="Cerca appunti..." value="<?= htmlspecialchars($search) ?>">
        </div>
        <?php if ($showApprovalFilter): ?>
            <div class="col-6 col-md-2">
                <label for="ajax-approval" class="form-label small text-muted">Stato</label>
                <select id="ajax-approval" class="form-select form-select-sm" onchange="updateArticles()" autocomplete="off">
                    <option value="all" <?= $approvalFilter === 'all' ? 'selected' : '' ?>>Tutti</option>
                    <option value="approved" <?= $approvalFilter === 'approved' ? 'selected' : '' ?>>Approvati</option>
                    <option value="pending" <?= $approvalFilter === 'pending' ? 'selected' : '' ?>>In attesa</option>
                    <option value="refused" <?= $approvalFilter === 'refused' ? 'selected' : '' ?>>Rifiutati</option>
                </select>
            </div>
        <?php endif; ?>
        <div class="col-6 col-md-3">
            <label for="ajax-sort" class="form-label small text-muted">Ordina per</label>
            <select id="ajax-sort" class="form-select form-select-sm" onchange="updateArticles()" autocomplete="off">
                <option value="data_pubblicazione">Data di caricamento</option>
                <option value="media_recensioni">Valutazione media</option>
                <option value="numero_visualizzazioni">Numero di visualizzazioni</option>
            </select>
        </div>
        <div class="col-6 col-md-3">
            <label for="ajax-order" class="form-label small text-muted">Ordine</label>
            <select id="ajax-order" class="form-select form-select-sm" onchange="updateArticles()" autocomplete="off">
                <option value="DESC">Decrescente</option>
                <option value="ASC">Crescente</option>
            </select>
        </div>
    </div>
</section>

<section aria-label="Lista appunti" id="articles-container" data-nomeutente="<?= htmlspecialchars($nomeutente) ?>" data-nomecorso="<?= htmlspecialchars($nomecorso) ?>" data-defaultMessage="<?= htmlspecialchars($defaultMessage) ?>" data-showActions=<?= $showActions ? "true" : "false" ?> class="d-flex flex-column gap-3" aria-live="polite">
    <h2 class="visually-hidden">Lista appunti</h2>
    <?php if (!empty($appunti)): foreach ($appunti as $appunto): ?>
            <article class="card shadow-sm border-0 article-card" id="article-<?= $appunto['idappunto'] ?>">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h5 class="card-title mb-1">
                                <a href="appunto.php?id=<?= htmlspecialchars($appunto['idappunto']) ?>" class="text-decoration-none text-dark <?= $showActions ? '' : 'stretched-link' ?>">
                                    <?= htmlspecialchars($appunto['titolo']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-2">di <?= htmlspecialchars($appunto['autore']) ?></p>
                            <div class="d-flex gap-2 row-gap-2 align-items-center flex-wrap mt-2">
                                <?php if ($isAdmin): ?>
                                    <?php
                                    $statusMap = [
                                        'in_revisione' => ['label' => 'Da approvare', 'class' => 'bg-warning text-dark'],
                                        'approvato' => ['label' => 'Approvato', 'class' => 'bg-success'],
                                        'rifiutato' => ['label' => 'Rifiutato', 'class' => 'bg-danger']
                                    ];
                                    $statusInfo = $statusMap[$appunto['stato']] ?? ['label' => $appunto['stato'], 'class' => 'bg-secondary'];
                                    ?>
                                    <span class="badge <?php echo $statusInfo['class']; ?>" title="Stato"><?php echo $statusInfo['label']; ?></span>
                                <?php endif; ?>
                                <span class="badge bg-light text-dark border" title="Media recensioni">★ <?= htmlspecialchars($appunto['media_recensioni'] ?: 'N/A') ?></span>
                                <span class="badge bg-light text-dark border" title="Visualizzazioni"><?= (int)$appunto['numero_visualizzazioni'] ?> Visualizzazioni</span>
                                <span class="badge bg-light text-dark border" title="Data pubblicazione"><?= date('d/m/y', strtotime($appunto['data_pubblicazione'])) ?></span>
                            </div>
                        </div>
                    </div>
                    <?php if ($showActions): ?>
                        <div class="d-flex gap-2 mt-3 justify-content-end">
                            <?php if ($appunto['stato'] === 'in_revisione'): ?>
                                <button type="button" class="btn btn-sm btn-outline-success btn-action-approve" data-id="<?= $appunto['idappunto'] ?>" title="Approva" aria-label="Approva appunto">
                                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                                    <span class="visually-hidden">Approva</span>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning btn-action-reject" data-id="<?= $appunto['idappunto'] ?>" title="Rifiuta" aria-label="Rifiuta appunto">
                                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                                    <span class="visually-hidden">Rifiuta</span>
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-action-delete" data-id="<?= $appunto['idappunto'] ?>" title="Elimina" aria-label="Elimina appunto">
                                <i class="bi bi-trash" aria-hidden="true"></i>
                                <span class="visually-hidden">Elimina</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach;
    else: ?>
        <div class="alert alert-info text-center" role="alert"><?= $messaggioVuoto ?></div>
    <?php endif; ?>
</section>