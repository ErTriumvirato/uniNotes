<?php
$courses = $dbh->getCourses();
$selectedCourseId = isset($_GET['idcorso']) ? (int)$_GET['idcorso'] : null;
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <section aria-label="Carica appunti" class="card shadow-sm border-0 rounded-3 form-card">
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
                        <input type="submit" name="invia" class="btn btn-outline-primary btn-lg" value="Richiedi approvazione">
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<?php if (!empty($templateParams["unapprovedArticles"])): ?>
    <section aria-labelledby="appunto-in-attesa" class="row justify-content-center mt-5">
        <div class="col-12 col-md-8 col-lg-6">
            <h3 id="appunto-in-attesa" class="text-center mb-4">In attesa di approvazione</h3>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($templateParams["unapprovedArticles"] as $article): ?>
                    <article class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?php echo htmlspecialchars($article['titolo']); ?></div>
                                    <div class="small text-muted"><?php echo htmlspecialchars($article['nome_corso']); ?></div>
                                    <?php if ($article['stato'] === 'rifiutato'): ?>
                                        <div class="text-danger small mt-1">
                                            <strong>Stato:</strong> Rifiutato
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <span class="text-muted small"><?php echo date('d/m/Y', strtotime($article['data_pubblicazione'])); ?></span>
                                    <?php if ($article['stato'] === 'rifiutato'): ?>
                                        <a href="modifica-appunti.php?id=<?php echo $article['idappunto']; ?>" class="btn btn-sm btn-outline-danger" title="Modifica">
                                            <i class="bi bi-pencil" aria-hidden="true"></i>
                                            <span class="visually-hidden">Modifica</span>
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-warning text-dark">In attesa</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>