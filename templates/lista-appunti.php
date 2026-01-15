<?php
// Parametri richiesti:
// $titoloFiltri - titolo della sezione (es. "Appunti disponibili", "Appunti di Mario")
// $nomeutente - (opzionale) username dell'autore per filtrare
// $nomecorso - (opzionale) nome del corso per filtrare
// $messaggioVuoto - (opzionale) messaggio quando non ci sono appunti
global $dbh;

$nomeutente = $nomeutente ?? null;
$nomecorso = $nomecorso ?? null;
$messaggioVuoto = $messaggioVuoto ?? "Nessun appunto disponibile.";

$appunti = $dbh->getApprovedArticlesWithFilters($nomeutente, $nomecorso);
?>

<section aria-label="Filtri appunti" class="row g-3 mb-4 align-items-end">
    <div class="col-12 col-md-6">
        <h3 class="mb-0"><?= $titoloFiltri ?></h3>
    </div>
    <div class="col-6 col-md-3">
        <label for="ajax-sort" class="form-label small text-muted">Ordina per</label>
        <select id="ajax-sort" class="form-select form-select-sm" onchange="updateArticles()">
            <option value="data_pubblicazione">Data di caricamento</option>
            <option value="media_recensioni">Valutazione media</option>
            <option value="numero_visualizzazioni">Numero di visualizzazioni</option>
        </select>
    </div>
    <div class="col-6 col-md-3">
        <label for="ajax-order" class="form-label small text-muted">Ordine</label>
        <select id="ajax-order" class="form-select form-select-sm" onchange="updateArticles()">
            <option value="DESC">Decrescente</option>
            <option value="ASC">Crescente</option>
        </select>
    </div>
</section>

<section aria-label="Lista appunti" id="articles-container" class="d-flex flex-column gap-3">
    <?php if (!empty($appunti)): foreach ($appunti as $appunto): ?>
            <article class="card shadow-sm border-0 article-card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-8">
                            <h5 class="card-title mb-1">
                                <a href="appunto.php?id=<?= htmlspecialchars($appunto['idappunto']) ?>" class="text-decoration-none text-dark stretched-link">
                                    <?= htmlspecialchars($appunto['titolo']) ?>
                                </a>
                            </h5>
                            <p class="card-text text-muted small mb-2">di <?= htmlspecialchars($appunto['autore']) ?></p>
                        </div>
                        <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                            <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                                <span class="badge bg-light text-dark border" title="Media recensioni">★ <?= htmlspecialchars($appunto['media_recensioni'] ?: 'N/A') ?></span>
                                <span class="badge bg-light text-dark border" title="Visualizzazioni"><?= (int)$appunto['numero_visualizzazioni'] ?> Visualizzazioni</span>
                                <span class="badge bg-light text-dark border" title="Data pubblicazione"><?= date('d/m/y', strtotime($appunto['data_pubblicazione'])) ?></span>
                            </div>
                        </div>
                    </div>
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
    const container = document.getElementById('articles-container');
    const nomeutente = <?= json_encode($nomeutente) ?>;
    const nomecorso = <?= json_encode($nomecorso) ?>;
    const messaggioVuoto = <?= json_encode($messaggioVuoto) ?>;

    function updateArticles() {
        let url = `appunti.php?action=filter&sort=${encodeURIComponent(sortSelect.value)}&order=${encodeURIComponent(orderSelect.value)}`;
        if (nomeutente) url += `&nomeutente=${encodeURIComponent(nomeutente)}`;
        if (nomecorso) url += `&nomecorso=${encodeURIComponent(nomecorso)}`;

        handleButtonAction(null, url, null, (data) => {
            container.innerHTML = '';
            if (data.length === 0) {
                container.innerHTML = `<div class="alert alert-info text-center" role="alert">${messaggioVuoto}</div>`;
                return;
            }
            data.forEach(art => {
                container.insertAdjacentHTML('beforeend', `
                    <article class="card shadow-sm border-0 article-card">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12 col-md-8">
                                    <h5 class="card-title mb-1">
                                        <a href="appunto.php?id=${art.idappunto}" class="text-decoration-none text-dark stretched-link">${art.titolo}</a>
                                    </h5>
                                    <p class="card-text text-muted small mb-2">di ${art.autore}</p>
                                </div>
                                <div class="col-12 col-md-4 text-md-end mt-2 mt-md-0">
                                    <div class="d-flex gap-2 justify-content-md-end flex-wrap">
                                        <span class="badge bg-light text-dark border" title="Media recensioni">★ ${art.media_recensioni || 'N/A'}</span>
                                        <span class="badge bg-light text-dark border" title="Visualizzazioni">${art.numero_visualizzazioni} Visualizzazioni</span>
                                        <span class="badge bg-light text-dark border" title="Data pubblicazione">${art.data_formattata}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>`);
            });
        });
    }
</script>