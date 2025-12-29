<?php
require_once 'config.php';
$corsi = $dbh->getCoursesWithSSD();
$idutente = $_SESSION["idutente"] ?? null;

foreach ($corsi as $corso):
    // Verifica se il corso è già seguito
    $isFollowing = $idutente ? $dbh->isFollowingCourse($idutente, $corso['idcorso']) : false;
?>
    <article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">
        <div class="card-body text-center">
            <!-- Nome corso -->
            <h2 class="card-title mb-3">
                <a href="corso.php?id=<?php echo (int)$corso['idcorso']; ?>" style="text-decoration: none;">
                    <?php echo htmlspecialchars($corso['nomeCorso']); ?>
                </a>
            </h2>
            <!-- SSD -->
            <p class="text-muted mb-3">
                SSD: <strong><?php echo htmlspecialchars($corso['nomeSSD']); ?></strong>
            </p>
            <!-- Bottone follow -->
            <button class="btn btn-primary follow-btn d-flex align-items-center justify-content-center mx-auto" 
                    style="gap: 8px; width: 180px;"
                    data-idcorso="<?= (int)$corso['idcorso'] ?>"
                    data-following="<?= $isFollowing ? 'true' : 'false' ?>">
                <?php if ($isFollowing): ?>
                    <img src="uploads/img/unfollow.svg" alt="Unfollow" style="width:20px;height:20px;">
                    Smetti di seguire
                <?php else: ?>
                    <img src="uploads/img/follow.svg" alt="Follow" style="width:20px;height:20px;">
                    Segui corso
                <?php endif; ?>
            </button>
        </div>
    </article>
<?php endforeach; ?>

<script>
document.querySelectorAll('.follow-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const idcorso = this.dataset.idcorso;
        const button = this;
        button.disabled = true;
        
        fetch('corsi.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: 'toggleFollow=' + encodeURIComponent(idcorso)
        })
        .then(res => res.json())
        .then(data => {
            // Aggiorna il bottone in base allo stato
            if (data.following) {
                button.innerHTML = '<img src="uploads/img/unfollow.svg" alt="Unfollow" style="width:20px;height:20px;"> Smetti di seguire';
                button.dataset.following = 'true';
            } else {
                button.innerHTML = '<img src="uploads/img/follow.svg" alt="Follow" style="width:20px;height:20px;"> Segui corso';
                button.dataset.following = 'false';
            }
            button.disabled = false;
        })
        .catch(err => {
            console.error(err);
            alert('Errore durante l\'operazione. Riprova.');
            button.disabled = false;
        });
    });
});
</script>