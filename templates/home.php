<?php
$articoli = $dbh->getApprovedArticles();

foreach ($articoli as $articolo) { ?>

<article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">
    <a href="articolo.php?id=<?php echo $articolo['idarticolo']; ?>"
       class="text-decoration-none text-dark">

        <div class="card-body text-center">
            <h2 class="card-title mb-2">
                <?php echo htmlspecialchars($articolo['titolo']); ?>
            </h2>
            <p class="text-muted mb-1">
                Pubblicato il
                <?php
                    echo date(
                        'd/m/Y',
                        strtotime($articolo['data_pubblicazione'])
                    );
                ?>
            </p>
            <p class="mb-1">
                <?php
                    if ($articolo['media_recensioni'] !== null) {
                        echo "⭐ " . $articolo['media_recensioni'] . " / 5.0";
                    } else {
                        echo "⭐ Nessuna recensione";
                    }
                ?>
            </p>
            <p class="text-muted mb-0">
                <?php echo (int)$articolo['numero_visualizzazioni']; ?> visualizzazioni
            </p>

        </div>
    </a>
</article>

<?php } ?>
