<?php

/**
 * Template per i filtri dei corsi
 * 
 * Variabili attese:
 * $ssds - (opzionale) array degli SSD per il popolamento via PHP
 * $showFollowFilter - (bool) mostra il filtro seguiti/non seguiti (default false)
 * $searchId - ID dell'input di ricerca (default 'search')
 * $ssdId - ID della select SSD (default 'ssd')
 * $filterTypeId - ID della select tipo filtro (default 'filterType')
 * $searchCallback - funzione JS al oninput (default 'filterCourses()')
 * $ssdCallback - funzione JS al onchange (default 'filterCourses()')
 * $filterTypeCallback - funzione JS al onchange (default 'filterCourses()')
 */

$ssds = $ssds ?? [];
$showFollowFilter = $showFollowFilter ?? false;
$searchId = $searchId ?? 'search';
$ssdId = $ssdId ?? 'ssd';
$filterTypeId = $filterTypeId ?? 'filterType';
$searchCallback = $searchCallback ?? 'filterCourses()';
$ssdCallback = $ssdCallback ?? 'filterCourses()';
$filterTypeCallback = $filterTypeCallback ?? 'filterCourses()';
?>

<section aria-label="Filtri corsi" class="mb-4">
    <h2 class="visually-hidden">Filtri corsi</h2>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-sm btn-outline-secondary d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
            <i class="bi bi-filter"></i> Filtri
        </button>
    </div>

    <div class="row g-2 align-items-end collapse d-md-flex" id="filtersCollapse">
        <div class="col-12 col-md-<?php echo $showFollowFilter ? '4' : '6'; ?>">
            <label for="<?php echo $searchId; ?>" class="form-label small text-muted">Cerca</label>
            <input type="text" id="<?php echo $searchId; ?>" class="form-control" placeholder="Cerca corso..." oninput="<?php echo $searchCallback; ?>">
        </div>
        <div class="col-12 col-md-<?php echo $showFollowFilter ? '4' : '6'; ?>">
            <label for="<?php echo $ssdId; ?>" class="form-label small text-muted">SSD</label>
            <select id="<?php echo $ssdId; ?>" class="form-select" onchange="<?php echo $ssdCallback; ?>">
                <option value="">Tutti gli SSD</option>
                <?php foreach ($ssds as $ssd):
                    $nome = is_array($ssd) ? ($ssd['nome'] ?? $ssd) : $ssd;
                ?>
                    <option value="<?= htmlspecialchars($nome) ?>"><?= htmlspecialchars($nome) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php if ($showFollowFilter): ?>
            <div class="col-12 col-md-4">
                <label for="<?php echo $filterTypeId; ?>" class="form-label small text-muted">Stato</label>
                <select id="<?php echo $filterTypeId; ?>" class="form-select" onchange="<?php echo $filterTypeCallback; ?>">
                    <option value="all">Tutti i corsi</option>
                    <option value="followed">Corsi seguiti</option>
                    <option value="not_followed">Corsi non seguiti</option>
                </select>
            </div>
        <?php endif; ?>
    </div>
</section>
