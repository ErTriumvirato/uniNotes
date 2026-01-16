<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="display-5 fw-bold mb-0">Gestione appunti</h1>
        </header>

        <?php
        $titoloFiltri = "";
        $showApprovalFilter = true;
        $messaggioVuoto = "Nessun appunto disponibile per questo corso.";
        include 'lista-appunti.php';
        ?>
    </div>
</div>

<script>
    function escapeHtml(text) {
        if (!text) return '';
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
            btn.innerHTML = '<i class="bi bi-check-lg"></i>';
            btn.classList.remove('btn-outline-danger');
            btn.classList.add('btn-danger');
            setTimeout(() => {
                if (btn.dataset.confirm) {
                    delete btn.dataset.confirm;
                    btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i><span class="visually-hidden">Elimina</span>';
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
                if (data.success) {
                    card.style.opacity = '0';
                    setTimeout(() => {
                        card.remove();
                        if (container.children.length === 0) {
                            container.innerHTML = '<div class="alert alert-info text-center">Nessun appunto trovato.</div>';
                        }
                    }, 500);
                } else {
                    showError('Errore durante l\'eliminazione');
                    btn.disabled = false;
                    btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i><span class="visually-hidden">Elimina</span>';
                    delete btn.dataset.confirm;
                    btn.classList.remove('btn-danger');
                    btn.classList.add('btn-outline-danger');
                }
            })
            .catch(() => {
                showError('Errore di connessione');
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-trash" aria-hidden="true"></i><span class="visually-hidden">Elimina</span>';
                delete btn.dataset.confirm;
                btn.classList.remove('btn-danger');
                btn.classList.add('btn-outline-danger');
            });
    }

    let timeout;

    function debouncedUpdateArticles() {
        clearTimeout(timeout);
        timeout = setTimeout(updateArticles, 300);
    }
</script>