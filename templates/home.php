<?php
require_once 'db/database.php';
require_once 'config.php';

$articoli = $dbh->getArticlesByReviews();

foreach ($articoli as $articolo) :
    $recensioni = $dbh->getReviewByArticle($articolo['idarticolo']);
    $media = null;
    if (!empty($recensioni)) {
        $somma = 0;
        foreach ($recensioni as $recensione) {
            $somma += $recensione['valutazione'];
        }
        $media = round($somma / count($recensioni), 1);
    }
?>

<article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">
    <a href="corso.php">
        <div class="card-body text-center">
            <h2 class="card-title mb-3"><?php echo htmlspecialchars($articolo['titolo']); ?></h2>
                <p class="text-muted mb-3">
                <?php 
                    if ($media !== null) {
                        echo "L'articolo è valutato " . $media . " stelle";
                    } else {
                        echo "Non sono presenti recensioni";
                    }
                ?>
            </p>

        </div>
    </a>
</article>

<?php
    endforeach;
?>
