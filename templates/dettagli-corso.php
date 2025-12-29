<?php
$articoli = $dbh->getArticlesById($_GET['id']);

if (!empty($articoli)) {
    $articolo = $articoli[0];
?>

    <article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">

        <a href="corso.php">
        <div class="card-body text-center">
            <h2 class="card-title mb-3"><?php echo $articolo['titolo'] ?></h2>
            <p class="text-muted mb-3">
                <?php echo $articolo['data_pubblicazione'] ?>
            </p>
        </div>
        </a>
    </article>
    
<?php }
