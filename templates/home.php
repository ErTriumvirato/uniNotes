<?php
require_once 'db/database.php';
require_once 'config.php';

$articoli = $dbh->getArticlesByNumberOfViews();
foreach ($articoli as $articolo) :
?>

<article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">

    <a href="corso.php">
    <div class="card-body text-center">
        <h2 class="card-title mb-3"><?php echo $articolo['titolo'] ?></h2>

        <p class="text-muted mb-3">
            <?php echo "L'articolo è stato visualizzato " . $articolo['numero_visualizzazioni'] . " volte" ?>
        </p>

    </div>
    </a>
</article>
<?php
    endforeach;
?>
