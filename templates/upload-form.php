<?php
$courses = $dbh->getCourses();
?>

<form action="gestione-articoli.php" method="post">
    <div>
        <label for="course">Corso:</label>
        <select id="course" name="course" required>
            <option value="" disabled selected>Seleziona un corso</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?php echo htmlspecialchars($course['idcorso']); ?>">
                    <?php echo htmlspecialchars($course['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label for="title">Titolo:</label>
        <input type="text" id="title" name="title" required>
    </div>
    <div>
        <label for="text">Testo:</label>
        <textarea id="text" name="text" rows="7" required></textarea>
    </div>
    <input type="submit" name="invia" value="Richiedi approvazione">
</form>