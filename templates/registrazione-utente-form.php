<form action="registrazione-utente.php" method="POST">
    <?php if (isset($templateParams["error"])): ?>
        <p><?php echo $templateParams["error"]; ?></p>
    <?php endif; ?>

    <ul>
        <li>
            <label for="username">Username:</label><input type="text" id="username" name="username" required />
        </li>
        <li>
            <label for="password">Password:</label><input type="password" id="password" name="password" required />
        </li>
        <li>
            <input type="submit" name="registrazione" value="Registrati" />
        </li>
    </ul>
    <p>Hai già un account? <a href="login.php">Accedi</a></p>
</form>