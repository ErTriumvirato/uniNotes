<form action="login.php" method="POST">
    <?php if (isset($templateParams["error"])): ?>
        <p><?php echo $templateParams["error"]; ?></p>
    <?php endif; ?>

    <ul>
        <li>
            <label for="username">Username:</label><input type="text" id="username" name="username" />
        </li>
        <li>
            <label for="password">Password:</label><input type="password" id="password" name="password" />
        </li>
        <li>
            <input type="submit" name="login" value="Invia" />
        </li>
    </ul>
    <p>Non hai un account? <a href="registrazione-utente.php">Registrati</a></p>
</form>