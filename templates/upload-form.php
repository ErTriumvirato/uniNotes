<?php
$courses = $dbh->getCourses();
$selectedCourseId = isset($_GET['idcorso']) ? (int)$_GET['idcorso'] : null;
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 rounded-3 form-card">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Carica appunti</h2>

                <form action="creazione-appunti.php" method="post">
                    <div class="mb-3">
                        <label for="course" class="form-label">Corso</label>
                        <select id="course" name="course" class="form-select" required>
                            <option value="" disabled <?php echo !$selectedCourseId ? 'selected' : ''; ?>>Seleziona un corso</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course['idcorso']); ?>" <?php echo ($selectedCourseId === $course['idcorso']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($course['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Titolo</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label for="text" class="form-label">Testo</label>
                        <textarea class="form-control" id="text" name="text" rows="7" required></textarea>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <input type="submit" name="invia" class="btn btn-primary btn-lg" value="Richiedi approvazione">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($templateParams["unapprovedArticles"])): ?>
<div class="row justify-content-center mt-5">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-4">
                <h3 class="text-center mb-4">In attesa di approvazione</h3>
                <ul class="list-group list-group-flush">
                    <?php foreach ($templateParams["unapprovedArticles"] as $article): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                            <div>
                                <div class="fw-bold"><?php echo htmlspecialchars($article['titolo']); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($article['nome_corso']); ?></div>
                            </div>
                            <span class="text-muted small"><?php echo date('d/m/Y', strtotime($article['data_pubblicazione'])); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
