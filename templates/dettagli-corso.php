<?php
$idCorso = (int)$_GET['id'];
$corso = $dbh->getCourseById($idCorso);
$idutente = $_SESSION["idutente"] ?? null;
$isFollowing = $idutente ? $dbh->isFollowingCourse($idutente, $idCorso) : false;
$appunti = $dbh->getApprovedArticlesByCourseWithFilters($idCorso, 'data_pubblicazione', 'DESC');
?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <section aria-labelledby="course-title" class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4 p-md-5">
                <div>
                    <h1 id="course-title" class="display-5 fw-bold mb-2"><?php echo htmlspecialchars($corso['nome']); ?></h1>
                    <span class="badge bg-secondary mb-3"><?php echo htmlspecialchars($corso['nomeSSD']); ?></span>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" id="followBtn" class="btn <?php echo $isFollowing ? 'btn-outline-danger' : 'btn-outline-primary'; ?>" data-idcorso="<?php echo htmlspecialchars($idCorso); ?>" onclick="handleFollowClick(this)">
                        <?php echo htmlspecialchars($isFollowing ? 'Smetti di seguire' : 'Segui corso'); ?>
                    </button>
                    <a href="creazione-appunti.php?idcorso=<?php echo htmlspecialchars($idCorso); ?>" class="btn btn-outline-secondary">Carica appunti</a>
                </div>
                <p class="lead mt-3"><?php echo nl2br(htmlspecialchars($corso['descrizione'])); ?></p>
            </div>
        </section>

        <?php
            $titoloFiltri = "Appunti disponibili";
            $ajaxUrl = "corso.php?id=" . $idCorso;
            $nomecorso = $corso['nome'];
            $messaggioVuoto = "Nessun appunto disponibile per questo corso.";
            include 'lista-appunti.php';
        ?>
    </div>
</div>

<script>
    function handleFollowClick(button) {
        handleButtonAction(button, "corso.php?id=" + encodeURIComponent(button.dataset.idcorso), "toggleFollow=" + encodeURIComponent(button.dataset.idcorso), (data, el) => {
            el.innerHTML = data.following ? "Smetti di seguire" : "Segui corso";
            el.classList.replace(data.following ? "btn-outline-primary" : "btn-outline-danger", data.following ? "btn-outline-danger" : "btn-outline-primary");
        });
    }
</script>
