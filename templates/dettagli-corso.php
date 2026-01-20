<?php
$idCorso = (int)$_GET['id'];
$corso = $dbh->getCourseById($idCorso);
$idutente = $_SESSION["idutente"] ?? null;
$isFollowing = $idutente ? $dbh->isFollowingCourse($idutente, $idCorso) : false;
//$appunti = $dbh->getNotesWithFilters(idcorso: $idCorso, sort: 'data_pubblicazione', order: 'DESC');
?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <section aria-labelledby="titolo-corso" class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4 p-md-5">
                <div>
                    <h1 id="titolo-corso" class="display-5 fw-bold mb-2"><?php echo htmlspecialchars($corso['nome']); ?></h1>
                    <span class="badge bg-secondary mb-3"><?php echo htmlspecialchars($corso['nomeSSD']); ?></span>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" id="followBtn" class="btn <?php echo $isFollowing ? 'btn-outline-danger' : 'btn-outline-primary'; ?>" data-idcorso="<?php echo htmlspecialchars($idCorso); ?>">
                        <?php echo htmlspecialchars($isFollowing ? 'Smetti di seguire' : 'Segui corso'); ?>
                    </button>
                    <a href="creazione-appunti.php?idcorso=<?php echo htmlspecialchars($idCorso); ?>" class="btn btn-outline-secondary">Carica appunti</a>
                </div>
                <p class="lead mt-3"><?php echo nl2br(htmlspecialchars($corso['descrizione'])); ?></p>
            </div>
        </section>

        <?php
        $templateParams["titoloFiltri"] = "Appunti disponibili";
        $templateParams["ajaxUrl"] = "corso.php?id=" . $idCorso;
        $templateParams["idcorso"] = $idCorso;
        $templateParams["defaultMessage"] = "Nessun appunto disponibile per questo corso.";
        include 'templates/appunti-template.php';
        ?>
    </div>
</div>