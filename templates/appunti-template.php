<?php
$isAdmin = isUserAdmin();
$idutente = $templateParams["idutente"] ?? '';
$idcorso = $templateParams["idcorso"] ?? '';
$search = $templateParams["search"] ?? ''; // Testo di ricerca iniziale
$showApprovalFilter = $isAdmin; // Mostra il filtro di approvazione solo per admin
$approvalFilter = $showApprovalFilter ? ($templateParams["approvalFilter"] ?? 'all') : 'approved'; // Filtro approvazione: 'approved', 'pending', 'all', 'refused' (default: 'approved', 'all' per admin)
$showActions = $isAdmin; // Mostra i tasti solo per admin
$defaultMessage = $templateParams["defaultMessage"] ?? "Nessun appunto disponibile.";
?>

<section aria-label="Filtri appunti" class="mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0 h3"><?= $templateParams["titoloFiltri"] ?></h2>
        <button class="btn btn-sm btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
            <em class="bi bi-filter"></em> Filtri
        </button>
    </div>

    <div class="row g-2 align-items-end collapse d-md-flex" id="filtersCollapse">
        <div class="col-12 col-md-4">
            <label for="ajax-search" class="form-label small text-muted">Cerca</label>
            <input type="text" id="ajax-search" class="form-control form-control-sm" placeholder="Cerca appunti..." value="<?= htmlspecialchars($search) ?>" />
        </div>
        <?php if ($showApprovalFilter): ?>
            <div class="col-6 col-md-2">
                <label for="ajax-approval" class="form-label small text-muted">Stato</label>
                <select id="ajax-approval" class="form-select form-select-sm" onchange="updateNotes()" autocomplete="off">
                    <option value="all" <?= $approvalFilter === 'all' ? 'selected' : '' ?>>Tutti</option>
                    <option value="approved" <?= $approvalFilter === 'approved' ? 'selected' : '' ?>>Approvati</option>
                    <option value="pending" <?= $approvalFilter === 'pending' ? 'selected' : '' ?>>In attesa</option>
                    <option value="refused" <?= $approvalFilter === 'refused' ? 'selected' : '' ?>>Rifiutati</option>
                </select>
            </div>
        <?php endif; ?>
        <div class="col-6 col-md-3">
            <label for="ajax-sort" class="form-label small text-muted">Ordina per</label>
            <select id="ajax-sort" class="form-select form-select-sm" onchange="updateNotes()" autocomplete="off">
                <option value="data_pubblicazione">Data di caricamento</option>
                <option value="media_recensioni">Valutazione media</option>
                <option value="numero_visualizzazioni">Numero di visualizzazioni</option>
            </select>
        </div>
        <div class="col-6 col-md-3">
            <label for="ajax-order" class="form-label small text-muted">Ordine</label>
            <select id="ajax-order" class="form-select form-select-sm" onchange="updateNotes()" autocomplete="off">
                <option value="DESC">Decrescente</option>
                <option value="ASC">Crescente</option>
            </select>
        </div>
    </div>
</section>

<section aria-label="Lista appunti" id="notes-container" data-idutente="<?= htmlspecialchars($idutente) ?>" data-idcorso="<?= htmlspecialchars($idcorso) ?>" data-defaultMessage="<?= htmlspecialchars($defaultMessage) ?>" data-showActions=<?= $showActions ? "true" : "false" ?> class="d-flex flex-column gap-3" aria-live="polite">
    <h2 class="visually-hidden">Lista appunti</h2>
    <!-- Caricamento contenuto con AJAX -->
    <div class="text-center">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Caricamento...</span>
        </div>
    </div>
</section>