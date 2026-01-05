<?php
$courses = $dbh->getCourses();
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <div class="card shadow-sm border-0 rounded-3 form-card">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Carica Appunti</h2>

                <form action="gestione-articoli.php" method="post">
                    <div class="mb-3">
                        <label for="course" class="form-label">Corso</label>
                        <select id="course" name="course" class="form-select" required>
                            <option value="" disabled selected>Seleziona un corso</option>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course['idcorso']); ?>">
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