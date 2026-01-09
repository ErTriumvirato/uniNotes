<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="display-5 fw-bold mb-0">Revisione appunti</h1>
        </div>

        <div class="card shadow-sm border-0 mb-4 form-card">
            <div class="card-body p-4">
                <form class="row g-3">
                    <div class="col-12 col-md-6">
                        <label for="search" class="form-label small text-muted">Cerca</label>
                        <input type="text" class="form-control" id="search" placeholder="Titolo, autore, corso...">
                    </div>
                    <div class="col-6 col-md-3">
                        <label for="ajax-sort" class="form-label small text-muted">Ordina per</label>
                        <select id="ajax-sort" class="form-select">
                            <option value="data_pubblicazione">Data</option>
                            <option value="media_recensioni">Media Voti</option>
                            <option value="numero_visualizzazioni">Visualizzazioni</option>
                        </select>
                    </div>
                    <div class="col-6 col-md-3">
                        <label for="ajax-order" class="form-label small text-muted">Ordine</label>
                        <select id="ajax-order" class="form-select">
                            <option value="DESC">Decrescente</option>
                            <option value="ASC">Crescente</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <div id="articles-container" class="d-flex flex-column gap-3">
            <?php if (!empty($templateParams["appunti"])): foreach ($templateParams["appunti"] as $appunto): ?>
                <div class="card shadow-sm border-0 article-card" id="article-<?php echo $appunto['idappunto']; ?>">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6">
                                <h5 class="card-title mb-1">
                                    <a href="appunto.php?id=<?php echo htmlspecialchars($appunto['idappunto']); ?>" class="text-decoration-none text-dark article-title-link">
                                        <?php echo htmlspecialchars($appunto['titolo']); ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted small mb-2">
                                    di <?php echo htmlspecialchars($appunto['autore']); ?> • <?php echo htmlspecialchars($appunto['nome_corso']); ?>
                                </p>
                            </div>
                            <div class="col-12 col-md-6 text-md-end mt-2 mt-md-0">
                                <div class="d-flex gap-2 justify-content-md-end align-items-center flex-wrap">
                                    <span class="badge bg-light text-dark border">
                                        ★ <?php echo $appunto['media_recensioni'] ?: '0.0'; ?>
                                    </span>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo (int)$appunto['numero_visualizzazioni']; ?> Vis.
                                    </span>
                                    <span class="badge bg-light text-dark border">
                                        📅 <?php echo date('d/m/y', strtotime($appunto['data_pubblicazione'])); ?>
                                    </span>
                                    <button class="btn btn-sm btn-outline-danger ms-2" onclick="handleDelete(this)" data-id="<?php echo $appunto['idappunto']; ?>" title="Elimina appunto">
                                         Elimina
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; else: ?>
                <div class="alert alert-info text-center">Nessun appunto trovato.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    const searchInput = document.getElementById('search');
    const sortSelect = document.getElementById('ajax-sort');
    const orderSelect = document.getElementById('ajax-order');
    const container = document.getElementById('articles-container');

    function updateArticles() {
        const query = searchInput.value;
        const sort = sortSelect.value;
        const order = orderSelect.value;

        fetch(`gestione-appunti.php?action=filter&search=${encodeURIComponent(query)}&orderBy=${sort}&order=${order}`)
            .then(res => res.json())
            .then(data => {
                container.innerHTML = '';
                if (data.length === 0) {
                    container.innerHTML = '<div class="alert alert-info text-center">Nessun appunto trovato.</div>';
                } else {
                    data.forEach(art => {
                        const html = `
                <div class="card shadow-sm border-0 article-card" id="article-${art.idappunto}">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-12 col-md-6">
                                <h5 class="card-title mb-1">
                                    <a href="appunto.php?id=${art.idappunto}" class="text-decoration-none text-dark article-title-link">
                                        ${escapeHtml(art.titolo)}
                                    </a>
                                </h5>
                                <p class="card-text text-muted small mb-2">
                                    di ${escapeHtml(art.autore)} • ${escapeHtml(art.nome_corso)}
                                </p>
                            </div>
                            <div class="col-12 col-md-6 text-md-end mt-2 mt-md-0">
                                <div class="d-flex gap-2 justify-content-md-end align-items-center flex-wrap">
                                    <span class="badge bg-light text-dark border">
                                        ★ ${art.media_recensioni || '0.0'}
                                    </span>
                                    <span class="badge bg-light text-dark border">
                                        ${art.numero_visualizzazioni} Vis.
                                    </span>
                                    <span class="badge bg-light text-dark border">
                                        📅 ${art.data_formattata}
                                    </span>
                                    <button class="btn btn-sm btn-outline-danger ms-2" onclick="handleDelete(this)" data-id="${art.idappunto}" title="Elimina appunto">
                                         Elimina
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>`;
                        container.insertAdjacentHTML('beforeend', html);
                    });
                }
            })
            .catch(() => {
                container.innerHTML = '<div class="alert alert-danger text-center">Errore di connessione.</div>';
            });
    }

    function escapeHtml(text) {
        if(!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    function handleDelete(btn) {
        const id = btn.dataset.id;
        const card = document.getElementById(`article-${id}`);

        if (!btn.dataset.confirm) {
            btn.dataset.confirm = 'true';
            btn.textContent = 'Conferma?';
            btn.classList.remove('btn-outline-danger');
            btn.classList.add('btn-danger');
            setTimeout(() => {
                if (btn.dataset.confirm) {
                    delete btn.dataset.confirm;
                    btn.textContent = 'Elimina';
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-outline-danger');
                }
            }, 3000);
            return;
        }

        btn.disabled = true;
        btn.textContent = '...';

        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('idappunto', id);
        formData.append('ajax', '1');

        fetch('gestione-appunti.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                card.style.opacity = '0';
                setTimeout(() => {
                    card.remove();
                    if(container.children.length === 0) {
                        container.innerHTML = '<div class="alert alert-info text-center">Nessun appunto trovato.</div>';
                    }
                }, 500);
            } else {
                alert('Errore durante l\'eliminazione');
                btn.disabled = false;
                btn.textContent = 'Elimina';
                delete btn.dataset.confirm;
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-danger');
            }
        })
        .catch(() => {
            alert('Errore di connessione');
            btn.disabled = false;
            btn.textContent = 'Elimina';
            delete btn.dataset.confirm;
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-outline-danger');
        });
    }

    let timeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(timeout);
        timeout = setTimeout(updateArticles, 300);
    });
    
    sortSelect.addEventListener('change', updateArticles);
    orderSelect.addEventListener('change', updateArticles);
</script>