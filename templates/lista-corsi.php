<?php
require_once 'config.php';
$corsi = $dbh->getCoursesWithSSD();
$idutente = $_SESSION["idutente"] ?? null;
$ssds = $templateParams["ssds"];
?>

<div class="container mb-4">
    <div class="row g-3">
        <div class="col-md-4">
            <input type="text" id="search" class="form-control" placeholder="Cerca corso...">
        </div>
        <div class="col-md-4">
            <select id="ssd" class="form-select">
                <option value="">Tutti gli SSD</option>
                <?php foreach ($ssds as $ssd): ?>
                    <option value="<?= htmlspecialchars($ssd['nome']) ?>"><?= htmlspecialchars($ssd['nome']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <select id="filterType" class="form-select">
                <option value="all">Tutti i corsi</option>
                <?php if ($idutente): ?>
                    <option value="followed">Corsi seguiti</option>
                    <option value="not_followed">Corsi non seguiti</option>
                <?php endif; ?>
            </select>
        </div>
    </div>
</div>

<div id="courses-container">
<?php foreach ($corsi as $corso):
    $isFollowing = $idutente ? $dbh->isFollowingCourse($idutente, $corso['idcorso']) : false;
?>
    <article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">
        <div class="card-body text-center">
            <h2 class="card-title mb-3">
                <a href="corso.php?id=<?php echo (int)$corso['idcorso']; ?>" style="text-decoration: none;">
                    <?php echo htmlspecialchars($corso['nomeCorso']); ?>
                </a>
            </h2>
            <p class="text-muted mb-3">
                SSD: <strong><?php echo htmlspecialchars($corso['nomeSSD']); ?></strong>
            </p>
            <button class="btn btn-primary follow-btn d-flex align-items-center justify-content-center mx-auto" 
                    style="gap: 8px; width: 180px;"
                    data-idcorso="<?= (int)$corso['idcorso'] ?>"
                    data-following="<?= $isFollowing ? 'true' : 'false' ?>">
                <?php if ($isFollowing): ?>
                    <img src="uploads/img/unfollow.svg" alt="Unfollow" style="width:20px;height:20px;">
                    Smetti di seguire
                <?php else: ?>
                    <img src="uploads/img/follow.svg" alt="Follow" style="width:20px;height:20px;">
                    Segui corso
                <?php endif; ?>
            </button>
        </div>
    </article>
<?php endforeach; ?>
</div>

<script>
function attachFollowListeners() {
    document.querySelectorAll('.follow-btn').forEach(btn => {
        const newBtn = btn.cloneNode(true);
        btn.parentNode.replaceChild(newBtn, btn);
        
        newBtn.addEventListener('click', function() {
            const idcorso = this.dataset.idcorso;
            const button = this;
            button.disabled = true;
            
            fetch('corsi.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'toggleFollow=' + encodeURIComponent(idcorso)
            })
            .then(res => res.json())
            .then(data => {
                if (data.following) {
                    button.innerHTML = '<img src="uploads/img/unfollow.svg" alt="Unfollow" style="width:20px;height:20px;"> Smetti di seguire';
                    button.dataset.following = 'true';
                } else {
                    button.innerHTML = '<img src="uploads/img/follow.svg" alt="Follow" style="width:20px;height:20px;"> Segui corso';
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

attachFollowListeners();

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
            coursesContainer.innerHTML = '<p class="text-center mt-5">Nessun corso trovato.</p>';
            return;
        }

        data.forEach(corso => {
            const article = document.createElement('article');
            article.className = 'card shadow-sm border-0 my-4';
            article.style.maxWidth = '600px';
            article.style.margin = 'auto';

            const isFollowing = corso.isFollowing;
            const followText = isFollowing ? 'Smetti di seguire' : 'Segui corso';
            const followIcon = isFollowing ? 'uploads/img/unfollow.svg' : 'uploads/img/follow.svg';

            article.innerHTML = `
                <div class="card-body text-center">
                    <h2 class="card-title mb-3">
                        <a href="corso.php?id=${corso.idcorso}" style="text-decoration: none;">
                            ${corso.nomeCorso}
                        </a>
                    </h2>
                    <p class="text-muted mb-3">
                        SSD: <strong>${corso.nomeSSD}</strong>
                    </p>
                    <button class="btn btn-primary follow-btn d-flex align-items-center justify-content-center mx-auto" 
                            style="gap: 8px; width: 180px;"
                            data-idcorso="${corso.idcorso}"
                            data-following="${isFollowing}">
                        <img src="${followIcon}" alt="${isFollowing ? 'Unfollow' : 'Follow'}" style="width:20px;height:20px;">
                        ${followText}
                    </button>
                </div>
            `;
            coursesContainer.appendChild(article);
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
</script>
