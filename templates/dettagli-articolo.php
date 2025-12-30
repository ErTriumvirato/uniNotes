<?php
$articolo = $dbh->getArticleById($_GET['id']);
$dbh->incrementArticleViews($_GET['id']);

if (!empty($articolo)) {
?>
    
    <h1><?php echo $articolo['titolo']; ?></h1>
    <p>Autore: <?php echo $articolo['autore']; ?></p>
    <p>Media recensioni: <?php echo $articolo['media_recensioni'] ?></p>
    <p><?php echo $articolo['contenuto']; ?></p>
    <p>Visualizzazioni: <?php echo $articolo['numero_visualizzazioni']; ?></p>
    <p>Data di pubblicazione: <?php echo date_format(date_create($articolo['data_pubblicazione']), 'd/m/Y H:i'); ?></p>
    
<?php
} else {
    echo "<p>Articolo non trovato.</p>";
}
