<?php
    require_once 'db/database.php';
    require_once 'config.php';
?>

<h1 class="text-center fw-bold my-5 position-relative">
    Corsi Seguiti
</h1>

<?php
    $userId = 2; // esempio, da prendere successivamente al login
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

<h1 class="text-center fw-bold my-5 position-relative">
    Altri Corsi
</h1>

<?php
    $nonSeguiti = $dbh->getCoursesWithSSD();
    foreach ($nonSeguiti as $corso) :
        if (in_array($corso, $seguiti)) {
            continue;
        }
?>
<article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">

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
?>
