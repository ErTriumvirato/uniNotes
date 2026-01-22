<?php
$ssds = $ssds ?? []; // Array di SSD
$showFollowFilter = $showFollowFilter ?? false; // Mostra il filtro seguiti/non seguiti
$searchId = $searchId ?? 'search'; // ID dell'input di ricerca
$ssdId = $ssdId ?? 'ssd'; // ID della select SSD
$filterTypeId = $filterTypeId ?? 'filterType'; // ID della select tipo filtro
$extraButtons = $extraButtons ?? ''; // Pulsanti extra da mostrare accanto ai filtri
?>

<section aria-label="Filtri corsi" class="mb-4">
    <h2 class="visually-hidden">Filtri corsi</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-3">
            <?= $extraButtons ?>
        </div>
        <button class="btn btn-sm btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
            <em class="bi bi-filter"></em> Filtri
        </button>
    </div>

    <div class="row g-2 align-items-end collapse d-md-flex" id="filtersCollapse">
        <div class="col-12 col-md-<?php echo $showFollowFilter ? '4' : '6'; ?>">
            <label for="<?php echo $searchId; ?>" class="form-label small fw-bold">Cerca corso</label>
            <input type="search" id="<?php echo $searchId; ?>" name="course_search" class="form-control" placeholder='es. "Ingegneria" o "Economia"' />
        </div>
        <div class="col-12 col-md-<?php echo $showFollowFilter ? '4' : '6'; ?>">
            <label for="<?php echo $ssdId; ?>" class="form-label small text-muted">SSD</label>
            <select id="<?php echo $ssdId; ?>" class="form-select">
                <option value="">Tutti gli SSD</option>
                <?php foreach ($ssds as $ssd):
                    $nome = is_array($ssd) ? ($ssd['nome'] ?? $ssd) : $ssd;
                ?>
                    <option value="<?= htmlspecialchars($nome) ?>"><?= htmlspecialchars($nome) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Filtro seguiti/non seguiti solo se l'utente è loggato -->
        <?php if ($showFollowFilter): ?>
            <div class="col-12 col-md-4">
                <label for="<?php echo $filterTypeId; ?>" class="form-label small text-muted">Stato</label>
                <select id="<?php echo $filterTypeId; ?>" class="form-select">
                    <option value="all">Tutti i corsi</option>
                    <option value="followed">Corsi seguiti</option>
                    <option value="not_followed">Corsi non seguiti</option>
                </select>
            </div>
        <?php endif; ?>
    </div>
</section>