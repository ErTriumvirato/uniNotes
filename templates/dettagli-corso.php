<?php
$articoli = $dbh->getArticlesByCourse($_GET['id']);

if (!empty($articoli)) {
    foreach ($articoli as $articolo) {
?>

    <article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">

        <a href="articolo.php?id=<?php echo $articolo['idarticolo'] ?>">
            <div class="card-body text-center">
                <h2 class="card-title mb-3"><?php echo $articolo['titolo'] ?></h2>
                <p class="text-muted mb-3">
                    <?php echo date_format(date_create($articolo['data_pubblicazione']), 'd/m/Y H:i') ?>
                </p>
            </div>
        </a>
    </article>
    
<?php }
} else {
    echo "<p>Nessun articolo trovato per questo corso.</p>";
} 