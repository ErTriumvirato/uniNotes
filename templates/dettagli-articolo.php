<?php
$articolo = $dbh->getArticleById($_GET['id']);
$dbh->incrementArticleViews($_GET['id']);
?>

<h1><?php echo $articolo['titolo']; ?></h1>
<p><?php echo $articolo['contenuto']; ?></p>
<p>Visualizzazioni: <?php echo $articolo['numero_visualizzazioni']; ?></p>
<p>Data di pubblicazione: <?php echo date_format(date_create($articolo['data_pubblicazione']), 'd/m/Y H:i'); ?></p>