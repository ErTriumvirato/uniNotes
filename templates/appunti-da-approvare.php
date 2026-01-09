<?php
$articles = $dbh->getArticlesToApprove();
?>

<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="display-5 fw-bold">Approvazione appunti</h2>
            <p class="text-muted">Revisiona e approva gli articoli inviati dagli utenti</p>
        </div>
    </div>

    <div id="approvazione-container" class="d-flex flex-column gap-4 mb-5">
        <?php if (empty($articles)): ?>
            <div class="alert alert-info" role="alert">
                Nessun articolo da approvare al momento.
            </div>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <div class="card shadow-sm border-0" id="article-<?= $article['idappunto'] ?>">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-3">
                            <div>
                                <h3 class="card-title h4 mb-1"><?= htmlspecialchars($article['titolo']) ?></h3>
                                <div class="text-muted small">
                                    <span class="me-3">Corso: <strong><?= htmlspecialchars($article['nome_corso']) ?></strong></span>
                                    <span>Autore: <strong><?= htmlspecialchars($article['autore']) ?></strong></span>
                                </div>
                            </div>
                            <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContent-<?= $article['idappunto'] ?>" aria-expanded="false" aria-controls="collapseContent-<?= $article['idappunto'] ?>">
                                Leggi Contenuto
                            </button>
                        </div>

                        <div class="collapse mb-4" id="collapseContent-<?= $article['idappunto'] ?>">
                            <div class="card card-body bg-light border-0">
                                <?= nl2br(htmlspecialchars($article['contenuto'])) ?>
                            </div>
                        </div>

                        <div class="d-flex gap-2 justify-content-end">
                            <button type="button" class="btn btn-outline-danger" onclick="handleArticle(<?= $article['idappunto'] ?>, 'reject')">Rifiuta</button>
                            <button type="button" class="btn btn-primary" onclick="handleArticle(<?= $article['idappunto'] ?>, 'approve')">Approva</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    function handleArticle(id, action) {
        let reason = null;
        if (action === 'reject') {
            reason = prompt("Inserisci il motivo del rifiuto:");
            if (reason === null) return; // Annullato dall'utente
            if (reason.trim() === "") {
                showError("È necessario specificare un motivo per il rifiuto.");
                return;
            }
        }

        const formData = new FormData();
        formData.append('action', action);
        formData.append('idappunto', id);
        if (reason) {
            formData.append('reason', reason);
        }

        fetch('approvazione-appunti.php', {
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

                    const container = document.getElementById('approvazione-container');
                    if (container.children.length === 0) {
                        container.innerHTML = '<div class="alert alert-info" role="alert">Nessun appunto da approvare al momento.</div>';
                    }
                } else {
                    showError('Errore durante l\'operazione: ' + (data.message || 'Sconosciuto'));
                }
            })
            .catch(() => {
                showError('Errore di comunicazione con il server.');
            });
    }
</script>