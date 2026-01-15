<?php
$stats = [
    "followed_courses" => $dbh->getFollowedCoursesCount($idProfile),
    "articles_written" => $dbh->getArticlesCountByAuthor($idProfile, true),
    "avg_rating" => $dbh->getAuthorAverageRating($idProfile)
];
$appunti = $dbh->getApprovedArticlesByUserIdWithFilters($templateParams['userProfile']['idutente'], 'data_pubblicazione', 'DESC');
?>

<h2 class="mb-4">Profilo di <?php echo htmlspecialchars($templateParams['userProfile']['username']); ?></h2>

<section aria-label="Statistiche utente" class="row g-2 g-md-4 mb-5">
    <div class="col-4">
        <div class="card h-100 border-0 shadow-sm text-center p-2 p-md-3">
            <div class="card-body p-1 p-md-3">
                <h3 class="h2 fw-bold text-primary mb-1 mb-md-2"><?php echo $stats["followed_courses"]; ?></h3>
                <p class="mb-0 text-muted small">Corsi Seguiti</p>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card h-100 border-0 shadow-sm text-center p-2 p-md-3">
            <div class="card-body p-1 p-md-3">
                <h3 class="h2 fw-bold text-primary mb-1 mb-md-2"><?php echo $stats["articles_written"]; ?></h3>
                <p class="mb-0 text-muted small">Articoli Scritti</p>
            </div>
        </div>
    </div>
    <div class="col-4">
        <div class="card h-100 border-0 shadow-sm text-center p-2 p-md-3">
            <div class="card-body p-1 p-md-3">
                <h3 class="h2 fw-bold text-primary mb-1 mb-md-2"><?php echo $stats["avg_rating"]; ?></h3>
                <p class="mb-0 text-muted small">Media Voto</p>
            </div>
        </div>
    </div>
</section>

<?php
$titoloFiltri = "Appunti di " . htmlspecialchars($templateParams['userProfile']['username']);
$ajaxUrl = "profilo.php?id=" . $templateParams['userProfile']['idutente'];
$nomeutente = $templateParams['userProfile']['username'];
include 'lista-appunti.php';
?>
