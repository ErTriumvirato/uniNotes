<?php
require_once 'config.php';
$corsi = $dbh->getCoursesWithSSD();
$idutente = $_SESSION["idutente"] ?? null;
$ssds = $templateParams["ssds"];
?>

<?php
$showFollowFilter = !empty($idutente);
require 'filtri-corsi.php';
?>

<section id="courses-container" class="row g-4" aria-label="Lista corsi" aria-live="polite">
    <?php foreach ($corsi as $corso):
        $isFollowing = $idutente ? $dbh->isFollowingCourse($idutente, $corso['idcorso']) : false;
    ?>
        <div class="col-12 col-md-6 col-lg-4">
            <article class="card h-100 shadow-sm border-0 course-card">
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
                            data-following="<?= htmlspecialchars($isFollowing ? 'true' : 'false') ?>"
                            onclick="handleFollowClick(this)">
                            <?php if ($isFollowing): ?>
                                Smetti di seguire
                            <?php else: ?>
                                Segui corso
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </article>
        </div>
    <?php endforeach; ?>
</section>

<script>
    function handleFollowClick(button) {
        handleButtonAction(button, "corsi.php", "toggleFollow=" + encodeURIComponent(button.dataset.idcorso), (data, el) => {
            el.innerHTML = data.following ? "Smetti di seguire" : "Segui corso";
            el.classList.replace(data.following ? "btn-outline-primary" : "btn-outline-danger", data.following ? "btn-outline-danger" : "btn-outline-primary");
        });
    }

    const searchInput = document.getElementById('search');
    const ssdSelect = document.getElementById('ssd');
    const filterTypeSelect = document.getElementById('filterType');
    const coursesContainer = document.getElementById('courses-container');

    function filterCourses() {
        const url = `corsi.php?action=filter&search=${encodeURIComponent(searchInput.value)}&ssd=${encodeURIComponent(ssdSelect.value)}&filterType=${encodeURIComponent(filterTypeSelect?.value || 'all')}`;

        handleButtonAction(null, url, null, (data) => {
            coursesContainer.innerHTML = '';
            if (data.length === 0) {
                coursesContainer.innerHTML = '<div class="col-12"><p class="text-center text-muted">Nessun corso trovato.</p></div>';
                return;
            }

            data.forEach(corso => {
                const col = document.createElement('div');
                col.className = 'col-12 col-md-6 col-lg-4';
                const btnClass = corso.isFollowing ? 'btn-outline-danger' : 'btn-outline-primary';
                const followText = corso.isFollowing ? 'Smetti di seguire' : 'Segui corso';

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
                                        data-following="${corso.isFollowing}"
                                        onclick="handleFollowClick(this)">
                                    ${followText}
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                coursesContainer.appendChild(col);
            });
        });
    }
</script>