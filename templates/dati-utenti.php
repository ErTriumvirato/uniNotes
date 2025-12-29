<?php
    $numeroUtenti = $dbh->getUsersNumber();
    $numeroCorsi = $dbh->getCoursesNumber();
    $numeroArticoli = $dbh->getArticlesNumber();
?>
<article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">
    <div class="card-body text-center">
        <p class="text-muted mb-3">
            <?php 
                echo 'Numero totale utenti: ' . $numeroUtenti . '<br>';
            ?>
        </p>
    </div>
</article>
<article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">
    <div class="card-body text-center">
        <p class="text-muted mb-3">
            <?php 
                echo 'Numero totale corsi: ' . $numeroCorsi . '<br>';
            ?>
        </p>
    </div>
</article>
<article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">
    <div class="card-body text-center">
        <p class="text-muted mb-3">
            <?php 
                echo 'Numero totale articoli: ' . $numeroArticoli . '<br>';
            ?>
        </p>
    </div>
</article>