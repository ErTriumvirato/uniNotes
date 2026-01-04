<?php
$articles = $dbh->getArticlesToApprove();
?>

<div id="approvazione-container">
    <?php if (empty($articles)): ?>
        <p>Nessun articolo da approvare.</p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <article id="article-<?= $article['idarticolo'] ?>">
                <div>
                    <h2><?= htmlspecialchars($article['titolo']) ?></h2>
                    <p>Corso: <?= htmlspecialchars($article['nome_corso']) ?></p>
                    <p>Autore: <?= htmlspecialchars($article['autore']) ?></p>

                    <div id="collapseContent-<?= $article['idarticolo'] ?>">
                        <div>
                            <?= nl2br(htmlspecialchars($article['contenuto'])) ?>
                        </div>
                    </div>
                    <div>
                        <button type="button">Leggi</button> // <-- NON FA NULLA
                        <button type="button" onclick="handleArticle(<?= $article['idarticolo'] ?>, 'approve')">Approva</button>
                        <button type="button" onclick="handleArticle(<?= $article['idarticolo'] ?>, 'reject')">Rifiuta</button>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<script>
function handleArticle(id, action) {
    const formData = new FormData();
    formData.append('action', action);
    formData.append('idarticolo', id);

    fetch('gestione-articoli.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const articleElement = document.getElementById('article-' + id);
            if (articleElement)
                articleElement.remove();
            if (document.querySelectorAll('article').length === 0)
                document.getElementById('approvazione-container').innerHTML = '<p>Nessun articolo da approvare.</p>';
        } else
            alert('Errore durante l\'operazione.');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Errore di comunicazione con il server.');
    });
}
</script>