<?php
// Statistiche utente
$statistiche = [
    "corsi_seguiti" => $dbh->getFollowedCoursesCount($templateParams['profiloUtente']['idutente']),
    "appunti_scritti" => $dbh->getNotesCountByAuthor($templateParams['profiloUtente']['idutente'], true),
    "media_recensioni" => $dbh->getAuthorAverageRating($templateParams['profiloUtente']['idutente'])
];
?>

<h1 class="mb-4 h2">Profilo di <?php echo htmlspecialchars($templateParams['profiloUtente']['username']); ?></h1>

<section aria-label="Statistiche utente" class="row g-2 g-md-4 mb-5">
    <h2 class="visually-hidden">Statistiche utente</h2>
    <div class="col-4">
        <div class="card h-100 border-0 shadow-sm text-center p-2 p-md-3">
            <div class="card-body p-1 p-md-3">
                <h3 class="h3 fw-bold text-primary mb-1 mb-md-2"><?php echo $statistiche["corsi_seguiti"]; ?></h3>
                <p class="mb-0 text-muted small">Corsi seguiti</p>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card h-100 border-0 shadow-sm text-center p-2 p-md-3">
            <div class="card-body p-1 p-md-3">
                <h3 class="h3 fw-bold text-primary mb-1 mb-md-2"><?php echo $statistiche["appunti_scritti"]; ?></h3>
                <p class="mb-0 text-muted small">Appunti scritti</p>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card h-100 border-0 shadow-sm text-center p-2 p-md-3">
            <div class="card-body p-1 p-md-3">
                <h3 class="h3 fw-bold text-primary mb-1 mb-md-2"><?php echo $statistiche["media_recensioni"]; ?></h3>
                <p class="mb-0 text-muted small">Media voto</p>
            </div>
        </div>
    </div>
</section>

<section aria-label="Lista appunti utente" class="card shadow-sm border-0 mb-5">
    <div class="card-body p-3 p-md-4">
        <?php
        $templateParams["titoloFiltri"] = "Appunti di " . htmlspecialchars($templateParams['profiloUtente']['username']);
        $templateParams["idutente"] = $templateParams['profiloUtente']['idutente'];
        include 'templates/appunti-template.php';
        ?>
    </div>
</section>