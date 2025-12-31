<?php
$articles = $dbh->getArticlesToApprove();
?>

<form action="approvazione_appunti.php" method="post">
    <?php foreach ($articles as $article): ?>
        <article class="card shadow-sm border-0 my-4" style="max-width: 800px; margin: auto;">
            <div class="card-body">
                <h2 class="card-title">
                    <?= htmlspecialchars($article['titolo']) ?>
                </h2>
                <p class="card-text">
                    <?= htmlspecialchars($article['contenuto']) ?>
                </p>
                <div class="mt-3">
                    <input type="hidden" name="article_ids[]" value="<?= $article['idarticolo'] ?>">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="decision[<?= $article['idarticolo'] ?>]" value="approve" required>
                        <label class="form-check-label text-success"> Approva </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="decision[<?= $article['idarticolo'] ?>]" value="reject" required />
                        <label class="form-check-label text-danger"> Rifiuta </label>
                    </div>
                </div>
            </div>
        </article>
    <?php endforeach; ?>

    <div class="text-center">
        <button type="submit" class="btn btn-primary">
            Conferma operazioni
        </button>
    </div>
</form>