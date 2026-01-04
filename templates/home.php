<?php
$articoli = $dbh->getApprovedArticles();

foreach ($articoli as $articolo) { ?>

<article>
    <a href="articolo.php?id=<?php echo $articolo['idarticolo']; ?>">

        <div>
            <h2>
                <?php echo htmlspecialchars($articolo['titolo']); ?>
            </h2>
            <p>
                Pubblicato il
                <?php
                    echo date(
                        'd/m/Y',
                        strtotime($articolo['data_pubblicazione'])
                    );
                ?>
            </p>
            <p>
                <?php
                    if ($articolo['media_recensioni'] !== null) {
                        echo "⭐ " . $articolo['media_recensioni'] . " / 5.0";
                    } else {
                        echo "⭐ Nessuna recensione";
                    }
                ?>
            </p>
            <p>
                <?php echo (int)$articolo['numero_visualizzazioni']; ?> visualizzazioni
            </p>

        </div>
    </a>
</article>

<?php } ?>
