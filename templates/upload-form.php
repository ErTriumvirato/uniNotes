<?php
$courses = $dbh->getCourses();
?>

<form action="gestione-articoli.php" method="post">
    <div class="mb-3">
        <label for="course" class="form-label">Corso:</label>
        <select class="form-select" id="course" name="course" required>
            <option value="" disabled selected>Seleziona un corso</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?php echo htmlspecialchars($course['idcorso']); ?>">
                    <?php echo htmlspecialchars($course['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="title" class="form-label">Titolo:</label>
        <input type="text" class="form-control" id="title" name="title" required>
    </div>
    <div class="mb-3">
        <label for="text" class="form-label">Testo:</label>
        <textarea class="form-control" id="text" name="text" rows="7" required></textarea>
    </div>
    <input type="submit" name="invia" class="btn btn-primary" value="Richiedi approvazione">
</form>