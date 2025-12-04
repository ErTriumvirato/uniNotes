<?php
require_once 'db/database.php';
require_once 'config.php';

$corsi = $dbh->getCoursesWithSSD();
foreach ($corsi as $corso) :
?>

<article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">
    <img src="uploads/img/follow.svg" class="card-img-top" alt="Immagine corso">
    <a href="corso.php">
    <div class="card-body text-center">
        <h2 class="card-title mb-3"><?php echo $corso['nomeCorso'] ?></h2>
        
        <p class="text-muted mb-3">
            <?php echo $corso['descrizioneCorso'] ?>
        </p>

    </div>
    </a>
</article>
<?php
    endforeach;