<?php
$courses = $dbh->getCoursesWithFilters();

// Ottieni l'ID del corso selezionato della GET, se presente (se l'utente è stato reindirizzato dalla pagina del corso)
$selectedCourseId = isset($_GET['idcorso']) ? $_GET['idcorso'] : null;
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <section aria-label="Carica appunti" class="card shadow-sm border-0 rounded-3 form-card">
            <div class="card-body p-4">
                <h1 class="text-center mb-4 h2">Carica appunti</h1>

                <!-- Form di caricamento appunti -->
                <form action="creazione-appunto.php" method="post">
                    <div class="mb-3">
                        <label for="course" class="form-label">Corso</label>
                        <select id="course" name="course" class="form-select" required>
                            <option value="" disabled <?php echo !$selectedCourseId ? 'selected' : ''; ?>>Seleziona un corso</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course['idcorso']); ?>" <?php echo ($selectedCourseId === $course['idcorso']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($course['nomeCorso']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Titolo</label>
                        <input type="text" class="form-control" id="title" name="title" required />
                    </div>

                    <div class="mb-3">
                        <label for="text" class="form-label">Testo</label>
                        <textarea class="form-control" id="text" name="text" rows="7" required></textarea>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <input type="submit" name="invia" class="btn btn-outline-primary btn-lg" value="Richiedi approvazione" />
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>

<!-- Articoli in attesa di approvazione -->
<?php if (!empty($templateParams["unapprovedNotes"])): ?>
    <section aria-labelledby="appunto-in-attesa" class="row justify-content-center mt-5">
        <div class="col-12 col-md-8 col-lg-6">
            <h2 id="appunto-in-attesa" class="text-center mb-4 h3">In attesa di approvazione</h2>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($templateParams["unapprovedNotes"] as $note): ?>
                    <article class="card shadow-sm border-0 rounded-3">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold"><?php echo htmlspecialchars($note['titolo']); ?></div>
                                    <div class="small text-muted"><?php echo htmlspecialchars($note['nome_corso']); ?></div>
                                    <?php if ($note['stato'] === 'rifiutato'): ?>
                                        <div class="text-danger small mt-1">
                                            <strong>Stato:</strong> Rifiutato
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="d-flex flex-column align-items-end gap-2">
                                    <span class="text-muted small"><?php echo date('d/m/Y', strtotime($note['data_pubblicazione'])); ?></span>
                                    <?php if ($note['stato'] === 'rifiutato'): ?>
                                        <a href="modifica-appunto.php?id=<?php echo $note['idappunto']; ?>" class="btn btn-sm btn-outline-danger" title="Modifica">
                                            <em class="bi bi-pencil" aria-hidden="true"></em>
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