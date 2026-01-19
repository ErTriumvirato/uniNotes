<?php
$courses = $dbh->getCourses();
$appunto = $templateParams["appunto"];
?>

<div class="row justify-content-center">
    <div class="col-12 col-md-8 col-lg-6">
        <section aria-label="Modifica appunto" class="card shadow-sm border-0 rounded-3 form-card">
            <div class="card-body p-4">
                <h2 class="text-center mb-4">Modifica appunti</h2>

                <?php if ($appunto['stato'] === 'rifiutato'): ?>
                    <div class="alert alert-danger" role="alert">
                        <strong>Stato:</strong> Rifiutato
                    </div>
                <?php endif; ?>

                <form action="modifica-appunti.php" method="post">
                    <input type="hidden" name="idappunto" value="<?php echo $appunto['idappunto']; ?>">

                    <div class="mb-3">
                        <label for="course" class="form-label">Corso</label>
                        <select id="course" name="course" class="form-select" required>
                            <?php foreach ($courses as $course): ?>
                                <option value="<?php echo htmlspecialchars($course['idcorso']); ?>" <?php echo ($appunto['idcorso'] == $course['idcorso']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($course['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label">Titolo</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($appunto['titolo']); ?>" required>
                    </div>

                    <div class="mb-3">
                        <label for="text" class="form-label">Testo</label>
                        <textarea class="form-control" id="text" name="text" rows="7" required><?php echo htmlspecialchars($appunto['contenuto']); ?></textarea>
                    </div>

                    <div class="d-grid gap-2 mt-4">
                        <input type="submit" name="salva" class="btn btn-primary btn-lg" value="Salva modifiche e richiedi approvazione">
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
