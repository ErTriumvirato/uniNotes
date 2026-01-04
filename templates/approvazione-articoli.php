<?php
$articles = $dbh->getArticlesToApprove();
?>

<div class="container">
    <?php if (empty($articles)): ?>
        <p class="text-center mt-5">Nessun articolo da approvare.</p>
    <?php else: ?>
        <?php foreach ($articles as $article): ?>
            <article class="card shadow-sm border-0 my-4" id="article-<?= $article['idarticolo'] ?>" style="max-width: 800px; margin: auto;">
                <div class="card-body">
                    <h2 class="card-title">
                        <?= htmlspecialchars($article['titolo']) ?>
                    </h2>
                    <p class="card-text">
                        Corso: <?= htmlspecialchars($article['nome_corso']) ?>
                    </p>
                    <p class="card-text">
                        Autore: <?= htmlspecialchars($article['autore']) ?>
                    </p>
                    
                    <div class="collapse my-3" id="collapseContent-<?= $article['idarticolo'] ?>">
                        <div class="card card-body bg-light">
                            <?= nl2br(htmlspecialchars($article['contenuto'])) ?>
                        </div>
                    </div>

                    <div class="mt-3">
                        <button class="btn btn-outline-primary me-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContent-<?= $article['idarticolo'] ?>" aria-expanded="false" aria-controls="collapseContent-<?= $article['idarticolo'] ?>">
                            Leggi
                        </button>
                        <button class="btn btn-success me-2" onclick="handleArticle(<?= $article['idarticolo'] ?>, 'approve')">Approva</button>
                        <button class="btn btn-danger" onclick="handleArticle(<?= $article['idarticolo'] ?>, 'reject')">Rifiuta</button>
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
            if (articleElement) {
                articleElement.remove();
            }
            if (document.querySelectorAll('article').length === 0) {
                document.querySelector('.container').innerHTML = '<p class="text-center mt-5">Nessun articolo da approvare.</p>';
            }
        } else {
            alert('Errore durante l\'operazione.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Errore di comunicazione con il server.');
    });
}
</script>