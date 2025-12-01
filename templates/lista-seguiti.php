<?php
require_once 'db/database.php';
require_once 'config.php';
?>

<?php
$userId = 2; // esempio, da prendere successivamente al login
//VERIFICARE SE SI è LOGGATI
$seguiti = $dbh->getFollowedCoursesWithSSD($userId);
foreach ($seguiti as $corsoseguito) :
?>
    <article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">

        <a href="corso.php">
            <div class="card-body text-center">
                <h2 class="card-title mb-3"><?php echo $corsoseguito['nomeCorso'] ?></h2>

                <p class="text-muted mb-3">
                    <?php echo $corsoseguito['descrizioneCorso'] ?>
                </p>

            </div>
        </a>
    </article>
<?php
endforeach;
?>