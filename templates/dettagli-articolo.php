<?php
$articolo = $dbh->getArticleById($_GET['id']);
$dbh->incrementArticleViews($_GET['id']);
$reviews = $dbh->getReviewsByArticle($_GET['id']);

if (!empty($articolo)) {
?>
    <div>
        <article>
            <div>
                <h1><?php echo htmlspecialchars($articolo['titolo']); ?></h1>
                <div>
                    <div>
                        <span>Autore: <strong><?php echo htmlspecialchars($articolo['autore']); ?></strong></span>
                        <span><?php echo date_format(date_create($articolo['data_pubblicazione']), 'd/m/Y H:i'); ?></span>
                    </div>
                    <div>
                        <span>
                            <?php echo $articolo['numero_visualizzazioni']; ?> Visualizzazioni
                        </span>
                        <span>
                            <?php echo $articolo['media_recensioni'] ?: 'N/A'; ?>
                        </span>
                    </div>
                </div>

                <div>
                    <?php echo nl2br(htmlspecialchars($articolo['contenuto'])); ?>
                </div>
            </div>
        </article>

        <section>
            <h3>Recensioni</h3>
            
            <?php if (isUserLoggedIn() && !$dbh->hasUserReviewed($articolo['idarticolo'], $_SESSION['idutente'])): ?>
                <div>
                    <div>
                        <h5>Lascia una recensione</h5>
                        <form action="articolo.php?id=<?php echo $articolo['idarticolo']; ?>" method="POST">
                            <div>
                                <label for="valutazione">Valutazione (1-5)</label>
                                <select name="valutazione" id="valutazione" required>
                                    <option value="" selected disabled>Seleziona un voto</option>
                                    <option value="5">5 - Eccellente</option>
                                    <option value="4">4 - Molto buono</option>
                                    <option value="3">3 - Buono</option>
                                    <option value="2">2 - Sufficiente</option>
                                    <option value="1">1 - Scarso</option>
                                </select>
                            </div>
                            <button type="submit">Invia Recensione</button>
                        </form>
                    </div>
                </div>
            <?php elseif (isUserLoggedIn()): ?>
                <div>Hai già recensito questo articolo.</div>
            <?php else: ?>
                <div>Effettua il <a href="login.php">login</a> per lasciare una recensione.</div>
            <?php endif; ?>

            <?php if (!empty($reviews)): ?>
                <div>
                    <?php foreach ($reviews as $review): ?>
                        <div>
                            <div>
                                <h5><?php echo htmlspecialchars($review['username']); ?></h5>
                                <small>
                                    <?php for($i=0; $i<$review['valutazione']; $i++) echo "★"; ?>
                                    <?php for($i=$review['valutazione']; $i<5; $i++) echo "☆"; ?>
                                </small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p>Nessuna recensione presente.</p>
            <?php endif; ?>
        </section>
    </div>
<?php
} else {
    echo "<div><div>Articolo non trovato.</div></div>";
}

