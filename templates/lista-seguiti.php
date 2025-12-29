<?php
$idutente = $_SESSION['idutente'];
$seguiti = $dbh->getFollowedCoursesWithSSD($idutente);
foreach ($seguiti as $corsoseguito) {
?>
    <article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">

        <div class="card-body text-center">

            <!-- Nome corso -->
            <h2 class="card-title mb-3">
                <a href="corso.php?id=<?php echo (int)$corsoseguito['idcorso']; ?>" style="text-decoration: none;">
                    <?php echo htmlspecialchars($corsoseguito['nomeCorso']); ?>
                </a>
            </h2>

            <!-- SSD -->
            <p class="text-muted mb-3">
                SSD: <strong><?php echo htmlspecialchars($corsoseguito['nomeSSD']); ?></strong>
            </p>

            <!-- Bottone follow -->
            <button class="btn btn-primary follow-btn d-flex align-items-center justify-content-center mx-auto"
                style="gap: 8px; width: 180px;"
                data-idcorso="<?= (int)$corsoseguito['idcorso'] ?>">
                <img src="uploads/img/unfollow.svg" alt="Unfollow" style="width:20px;height:20px;">
                Smetti di seguire
            </button>


        </div>
    </article>
<?php
}
