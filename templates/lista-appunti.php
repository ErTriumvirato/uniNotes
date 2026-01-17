<?php
// Parametri richiesti:
// $titoloFiltri - titolo della sezione (es. "Appunti disponibili", "Appunti di Mario")
// $nomeutente - (opzionale) username dell'autore per filtrare
// $nomecorso - (opzionale) nome del corso per filtrare
// $search - (opzionale) testo di ricerca iniziale
// $approvalFilter - (opzionale) filtro approvazione: 'approved', 'pending', 'all', 'refused' (default: 'approved', 'all' per admin)
// $messaggioVuoto - (opzionale) messaggio quando non ci sono appunti
// Nota: filtro approvazione e azioni sono visibili solo agli admin
global $dbh;

$isAdmin = isUserAdmin();
$nomeutente = $nomeutente ?? null;
$nomecorso = $nomecorso ?? null;
$search = $search ?? '';
$approvalFilter = $isAdmin ? ($approvalFilter ?? 'all') : 'approved';
$showApprovalFilter = $isAdmin;
$showActions = $isAdmin;
$messaggioVuoto = $messaggioVuoto ?? "Nessun appunto disponibile.";

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

<section aria-label="Lista appunti" id="articles-container" class="d-flex flex-column gap-3">
    <?php if (!empty($appunti)): foreach ($appunti as $appunto): ?>
            <article class="card shadow-sm border-0 article-card" id="article-<?= $appunto['idappunto'] ?>">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-8">
                            <h5 class="card-title mb-1">
                                <a href="appunto.php?id=<?= htmlspecialchars($appunto['idappunto']) ?>" class="text-decoration-none text-dark <?= $showActions ? '' : 'stretched-link' ?>">
                                    <?= htmlspecialchars($appunto['titolo']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-2">di <?= htmlspecialchars($appunto['autore']) ?></p>
                        </div>
                        <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                            <div class="d-flex gap-2 justify-content-md-end flex-wrap">
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
                                <button type="button" class="btn btn-sm btn-outline-success" onclick="handleApprove(<?= $appunto['idappunto'] ?>)" title="Approva">
                                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-warning" onclick="handleReject(<?= $appunto['idappunto'] ?>)" title="Rifiuta">
                                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                                </button>
                            <?php endif; ?>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-id="<?= $appunto['idappunto'] ?>" onclick="handleDelete(this)" title="Elimina">
                                <i class="bi bi-trash" aria-hidden="true"></i>
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

<script>
    const sortSelect = document.getElementById('ajax-sort');
    const orderSelect = document.getElementById('ajax-order');
    const searchInput = document.getElementById('ajax-search');
    const approvalSelect = document.getElementById('ajax-approval');
    const container = document.getElementById('articles-container');
    const nomeutente = <?= json_encode($nomeutente) ?>;
    const nomecorso = <?= json_encode($nomecorso) ?>;
    const defaultApprovalFilter = <?= json_encode($approvalFilter) ?>;
    const messaggioVuoto = <?= json_encode($messaggioVuoto) ?>;
    const showActions = <?= json_encode($showActions) ?>;

    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(updateArticles, 300);
    });

    function renderActionButtons(art) {
        if (!showActions) return '';
        let buttons = '';
        if (art.stato === 'in_revisione') {
            buttons += `
                <button type="button" class="btn btn-sm btn-outline-success" onclick="handleApprove(${art.idappunto})" title="Approva">
                    <i class="bi bi-check-lg" aria-hidden="true"></i>
                </button>
                <button type="button" class="btn btn-sm btn-outline-warning" onclick="handleReject(${art.idappunto})" title="Rifiuta">
                    <i class="bi bi-x-lg" aria-hidden="true"></i>
                </button>`;
        }
        buttons += `
            <button type="button" class="btn btn-sm btn-outline-danger" data-id="${art.idappunto}" onclick="handleDelete(this)" title="Elimina">
                <i class="bi bi-trash" aria-hidden="true"></i>
            </button>`;
        return `<div class="d-flex gap-2 mt-3 justify-content-end">${buttons}</div>`;
    }

    function updateArticles() {
        const searchValue = searchInput.value.trim();
        const approvalValue = approvalSelect ? approvalSelect.value : defaultApprovalFilter;

        let url = `appunti.php?action=filter&sort=${encodeURIComponent(sortSelect.value)}&order=${encodeURIComponent(orderSelect.value)}`;
        if (nomeutente) url += `&nomeutente=${encodeURIComponent(nomeutente)}`;
        if (nomecorso) url += `&nomecorso=${encodeURIComponent(nomecorso)}`;
        if (searchValue) url += `&search=${encodeURIComponent(searchValue)}`;
        url += `&approval=${encodeURIComponent(approvalValue)}`;

        handleButtonAction(null, url, null, (data) => {
            container.innerHTML = '';
            if (data.length === 0) {
                container.innerHTML = `<div class="alert alert-info text-center" role="alert">${messaggioVuoto}</div>`;
                return;
            }
            data.forEach(art => {
                let statusBadge = '';
                if (showActions) {
                    const statusMap = {
                        'in_revisione': { label: 'Da approvare', class: 'bg-warning text-dark' },
                        'approvato': { label: 'Approvato', class: 'bg-success' },
                        'rifiutato': { label: 'Rifiutato', class: 'bg-danger' }
                    };
                    const statusInfo = statusMap[art.stato] || { label: art.stato, class: 'bg-secondary' };
                    statusBadge = `<span class="badge ${statusInfo.class}" title="Stato">${statusInfo.label}</span>`;
                }

                container.insertAdjacentHTML('beforeend', `
                    <article class="card shadow-sm border-0 article-card" id="article-${art.idappunto}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-8">
                                    <h5 class="card-title mb-1">
                                        <a href="appunto.php?id=${art.idappunto}" class="text-decoration-none text-dark ${showActions ? '' : 'stretched-link'}">${art.titolo}</a>
                                    </h5>
                                    <p class="card-text text-muted small mb-2">di ${art.autore}</p>
                                </div>
                                <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                                    <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                                        ${statusBadge}
                                        <span class="badge bg-light text-dark border" title="Media recensioni">★ ${art.media_recensioni || 'N/A'}</span>
                                        <span class="badge bg-light text-dark border" title="Visualizzazioni">${art.numero_visualizzazioni} Visualizzazioni</span>
                                        <span class="badge bg-light text-dark border" title="Data pubblicazione">${art.data_formattata}</span>
                                    </div>
                                </div>
                            </div>
                            ${renderActionButtons(art)}
                        </div>
                    </article>`);
            });
        });
    }

    // Handler per approvazione
    function handleApprove(id) {
        const formData = new FormData();
        formData.append('action', 'approve');
        formData.append('idappunto', id);

        fetch('appunti.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateArticles();
                } else {
                    showError('Errore durante l\'approvazione');
                }
            })
            .catch(() => showError('Errore di connessione'));
    }

    // Handler per rifiuto
    function handleReject(id) {
        const formData = new FormData();
        formData.append('action', 'reject');
        formData.append('idappunto', id);

        fetch('appunti.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateArticles();
                } else {
                    showError('Errore durante il rifiuto');
                }
            })
            .catch(() => showError('Errore di connessione'));
    }

    // Handler per eliminazione
    function handleDelete(btn) {
        const id = btn.dataset.id;
        const card = document.getElementById(`article-${id}`);

        if (!btn.dataset.confirm) {
            btn.dataset.confirm = 'true';
            btn.innerHTML = '<i class="bi bi-check-lg"></i>';
            btn.classList.remove('btn-outline-danger');
            btn.classList.add('btn-danger');
            setTimeout(() => {
                if (btn.dataset.confirm) {
                    delete btn.dataset.confirm;
                    btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i> Elimina';
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

        fetch('appunti.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    updateArticles();
                } else {
                    showError('Errore durante l\'eliminazione');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i> Elimina';
                    delete btn.dataset.confirm;
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-outline-danger');
                }
            })
            .catch(() => {
                showError('Errore di connessione');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i> Elimina';
                delete btn.dataset.confirm;
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-danger');
            });
    }

    // Reset dei selettori quando si torna indietro con il browser (bfcache)
    window.addEventListener('pageshow', (event) => {
        if (event.persisted) {
            // Resetta i selettori ai valori di default per sincronizzarli con il contenuto PHP
            if (approvalSelect) approvalSelect.value = defaultApprovalFilter;
            sortSelect.value = 'data_pubblicazione';
            orderSelect.value = 'DESC';
            searchInput.value = <?= json_encode($search) ?>;
        }
    });
</script>