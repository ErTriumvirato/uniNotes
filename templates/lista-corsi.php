<?php
require_once 'config.php';
$corsi = $dbh->getCoursesWithSSD();
$idutente = $_SESSION["idutente"] ?? null;
$ssds = $templateParams["ssds"];
?>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <input type="text" id="search" class="form-control" placeholder="Cerca corso...">
    </div>
    <div class="col-12 col-md-4">
        <select id="ssd" class="form-select">
            <option value="">Tutti gli SSD</option>
            <?php foreach ($ssds as $ssd): ?>
                <option value="<?= htmlspecialchars($ssd['nome']) ?>"><?= htmlspecialchars($ssd['nome']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12 col-md-4">
        <select id="filterType" class="form-select">
            <option value="all">Tutti i corsi</option>
            <?php if ($idutente): ?>
                <option value="followed">Corsi seguiti</option>
                <option value="not_followed">Corsi non seguiti</option>
            <?php endif; ?>
        </select>
    </div>
</div>

<div id="courses-container" class="row g-4">
    <?php foreach ($corsi as $corso):
        $isFollowing = $idutente ? $dbh->isFollowingCourse($idutente, $corso['idcorso']) : false;
    ?>
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border-0 course-card">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">
                        <a href="corso.php?id=<?php echo htmlspecialchars((int)$corso['idcorso']); ?>" class="text-decoration-none text-dark stretched-link">
                            <?php echo htmlspecialchars($corso['nomeCorso']); ?>
                        </a>
                    </h5>
                    <p class="card-text text-muted mb-4">
                        SSD: <span class="badge bg-light text-dark border"><?php echo htmlspecialchars($corso['nomeSSD']); ?></span>
                    </p>
                    <div class="mt-auto">
                        <button type="button" class="btn btn-sm w-100 position-relative z-2 <?php echo htmlspecialchars($isFollowing ? 'btn-outline-danger' : 'btn-outline-primary'); ?>"
                            data-idcorso="<?= htmlspecialchars((int)$corso['idcorso']) ?>"
                            data-following="<?= htmlspecialchars($isFollowing ? 'true' : 'false') ?>">
                            <?php if ($isFollowing): ?>
                                Smetti di seguire
                            <?php else: ?>
                                Segui corso
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        attachFollowListeners();
        
    function attachFollowListeners() {
        document.querySelectorAll('button[data-idcorso]').forEach(btn => {
            const newBtn = btn.cloneNode(true);
            btn.parentNode.replaceChild(newBtn, btn);

            newBtn.addEventListener('click', function() {
                const idcorso = this.dataset.idcorso;
                const button = this;
                button.disabled = true;

                fetch('corsi.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: 'toggleFollow=' + encodeURIComponent(idcorso)
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.following) {
                            button.innerHTML = 'Smetti di seguire';
                            button.className = 'btn btn-sm w-100 position-relative z-2 btn-outline-danger';
                            button.dataset.following = 'true';
                        } else {
                            button.innerHTML = 'Segui corso';
                            button.className = 'btn btn-sm w-100 position-relative z-2 btn-outline-primary';
                            button.dataset.following = 'false';
                        }
                        button.disabled = false;
                    })
                    .catch(err => {
                        console.error(err);
                        alert('Errore durante l\'operazione. Riprova.');
                        button.disabled = false;
                    });
            });
        });
    }
        const searchInput = document.getElementById('search');
        const ssdSelect = document.getElementById('ssd');
        const filterTypeSelect = document.getElementById('filterType');
        const coursesContainer = document.getElementById('courses-container');

        function filterCourses() {
            const search = searchInput.value;
            const ssd = ssdSelect.value;
            const filterType = filterTypeSelect ? filterTypeSelect.value : 'all';

            fetch(`corsi.php?action=filter&search=${encodeURIComponent(search)}&ssd=${encodeURIComponent(ssd)}&filterType=${encodeURIComponent(filterType)}`)
                .then(response => response.json())
                .then(data => {
                    coursesContainer.innerHTML = '';
                    if (data.length === 0) {
                        coursesContainer.innerHTML = '<div class="col-12"><p class="text-center text-muted">Nessun corso trovato.</p></div>';
                        return;
                    }

                    data.forEach(corso => {
                        const col = document.createElement('div');
                        col.className = 'col-12 col-md-6 col-lg-4';

                        const isFollowing = corso.isFollowing;
                        const followText = isFollowing ? 'Smetti di seguire' : 'Segui corso';
                        const btnClass = isFollowing ? 'btn-outline-danger' : 'btn-outline-primary';

                        col.innerHTML = `
                <div class="card h-100 shadow-sm border-0 course-card">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="corso.php?id=${corso.idcorso}" class="text-decoration-none text-dark stretched-link">
                                ${corso.nomeCorso}
                            </a>
                        </h5>
                        <p class="card-text text-muted mb-4">
                            SSD: <span class="badge bg-light text-dark border">${corso.nomeSSD}</span>
                        </p>
                        <div class="mt-auto">
                            <button type="button" class="btn btn-sm w-100 position-relative z-2 ${btnClass}"
                                    data-idcorso="${corso.idcorso}"
                                    data-following="${isFollowing}">
                                ${followText}
                            </button>
                        </div>
                    </div>
                </div>
            `;
                        coursesContainer.appendChild(col);
                    });
                    attachFollowListeners();
                })
                .catch(error => console.error('Error:', error));
        }

        searchInput.addEventListener('input', filterCourses);
        ssdSelect.addEventListener('change', filterCourses);
        if (filterTypeSelect) {
            filterTypeSelect.addEventListener('change', filterCourses);
        }
    });
</script>
