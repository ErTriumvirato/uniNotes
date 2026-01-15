<?php
$articles = $dbh->getArticlesToApprove();
?>

<div class="container">
    <header class="row mb-4">
        <div class="col-12">
            <h2 class="display-5 fw-bold">Approvazione appunti</h2>
            <p class="text-muted">Revisiona e approva gli articoli inviati dagli utenti</p>
        </div>
    </header>

    <!-- Filtri di ordinamento -->
    <section aria-label="Filtri" class="d-flex justify-content-end mb-4">
        <div class="col-12 col-md-4 col-lg-3">
            <label for="sortOrder" class="visually-hidden">Ordina per</label>
            <select class="form-select" id="sortOrder" aria-label="Ordina appunti">
                <option value="data_pubblicazione-DESC" selected>Data: Più recenti</option>
                <option value="data_pubblicazione-ASC">Data: Meno recenti</option>
                <option value="titolo-ASC">Titolo: A-Z</option>
                <option value="titolo-DESC">Titolo: Z-A</option>
                <option value="autore-ASC">Autore: A-Z</option>
                <option value="autore-DESC">Autore: Z-A</option>
            </select>
        </div>
    </section>

    <section aria-label="Lista appunti da approvare" id="approvazione-container" class="d-flex flex-column gap-4 mb-5">
        <?php if (empty($articles)): ?>
            <div class="alert alert-info" role="alert">
                Nessun articolo da approvare al momento.
            </div>
        <?php else: ?>
            <?php foreach ($articles as $article): ?>
                <article class="card shadow-sm border-0" id="article-<?= $article['idappunto'] ?>">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <h3 class="card-title h4 mb-1"><?= htmlspecialchars($article['titolo']) ?></h3>
                            <div class="text-muted small">
                                <span class="me-3">Corso: <strong><?= htmlspecialchars($article['nome_corso']) ?></strong></span>
                                <span>Autore: <strong><?= htmlspecialchars($article['autore']) ?></strong></span>
                            </div>
                        </div>

                        <div class="collapse mb-4" id="collapseContent-<?= $article['idappunto'] ?>">
                            <div class="card card-body bg-light border-0">
                                <?= nl2br(htmlspecialchars($article['contenuto'])) ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContent-<?= $article['idappunto'] ?>" aria-expanded="false" aria-controls="collapseContent-<?= $article['idappunto'] ?>">
                                Leggi Contenuto
                            </button>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary" onclick="handleArticle(<?= $article['idappunto'] ?>, 'approve')">Approva</button>
                                <button type="button" class="btn btn-outline-danger" onclick="handleArticle(<?= $article['idappunto'] ?>, 'reject')">Rifiuta</button>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </section>
</div>

<script>
    document.getElementById('sortOrder').addEventListener('change', function() {
        const [sort, order] = this.value.split('-');
        fetchArticles(sort, order);
    });

    function fetchArticles(sort, order) {
        fetch(`approvazione-appunti.php?action=filter&sort=${sort}&order=${order}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    renderArticles(data.articles);
                } else {
                    showError('Impossibile caricare gli appunti.');
                }
            })
            .catch(() => showError('Errore di comunicazione con il server.'));
    }

    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function nl2br(str) {
        if (!str) return '';
        return escapeHtml(str).replace(/\n/g, '<br>');
    }

    function renderArticles(articles) {
        const container = document.getElementById('approvazione-container');
        container.innerHTML = '';

        if (articles.length === 0) {
            container.innerHTML = '<div class="alert alert-info" role="alert">Nessun appunto da approvare al momento.</div>';
            return;
        }

        articles.forEach(article => {
            const articleHtml = `
            <article class="card shadow-sm border-0" id="article-${article.idappunto}">
                <div class="card-body p-4">
                    <div class="mb-3">
                        <h3 class="card-title h4 mb-1">${escapeHtml(article.titolo)}</h3>
                        <div class="text-muted small">
                            <span class="me-3">Corso: <strong>${escapeHtml(article.nome_corso)}</strong></span>
                            <span>Autore: <strong>${escapeHtml(article.autore)}</strong></span>
                        </div>
                    </div>

                    <div class="collapse mb-4" id="collapseContent-${article.idappunto}">
                         <div class="card card-body bg-light border-0">
                            ${nl2br(article.contenuto)}
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <button class="btn btn-outline-secondary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#collapseContent-${article.idappunto}" aria-expanded="false" aria-controls="collapseContent-${article.idappunto}">
                            Leggi Contenuto
                        </button>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-danger" onclick="handleArticle(${article.idappunto}, 'reject')">Rifiuta</button>
                            <button type="button" class="btn btn-primary" onclick="handleArticle(${article.idappunto}, 'approve')">Approva</button>
                        </div>
                    </div>
                </div>
            </article>`;
            container.insertAdjacentHTML('beforeend', articleHtml);
        });
    }

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