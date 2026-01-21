<?php
require_once 'config.php';
$corsi = $dbh->getCoursesWithFilters(); // Prende i corsi
$idutente = $_SESSION["idutente"] ?? null;
$ssds = $templateParams["ssds"];
?>

<div class="row justify-content-center mb-3">
    <div class="col-12 text-center">
        <h1 class="display-5 fw-bold">Corsi</h1>
    </div>
</div>

<?php
$showFollowFilter = !empty($idutente);
require 'filtri-corsi.php';
?>


<!-- Lista corsi -->
<section id="courses-container" class="row g-4" aria-label="Lista corsi" aria-live="polite">
    <h2 class="visually-hidden">Lista corsi</h2>
    <?php foreach ($corsi as $corso):
        $isFollowing = $idutente ? $dbh->isFollowingCourse($idutente, $corso['idcorso']) : false;
    ?>
        <div class="col-12 col-md-6 col-lg-4">
            <article class="card h-100 shadow-sm border-0 course-card">
                <div class="card-body d-flex flex-column">
                    <h3 class="card-title h5">
                        <a href="corso.php?id=<?php echo htmlspecialchars((int)$corso['idcorso']); ?>" class="text-decoration-none text-dark stretched-link">
                            <?php echo htmlspecialchars($corso['nomeCorso']); ?>
                        </a>
                    </h3>
                    <p class="card-text text-muted mb-4">
                        SSD: <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($corso['nomeSSD']); ?></span>
                    </p>
                    <div class="mt-auto">
                        <button type="button" class="btn btn-sm w-100 position-relative z-2 btn-follow <?php echo htmlspecialchars($isFollowing ? 'btn-outline-danger' : 'btn-outline-primary'); ?>"
                            data-idcorso="<?= htmlspecialchars((int)$corso['idcorso']) ?>"
                            data-following="<?= htmlspecialchars($isFollowing ? 'true' : 'false') ?>">
                            <?php if ($isFollowing): ?>
                                Smetti di seguire
                            <?php else: ?>
                                Segui corso
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </article>
        </div>
    <?php endforeach; ?>
</section>