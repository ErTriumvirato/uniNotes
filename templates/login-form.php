
<form action="login.php" method="POST">
    <!-- Errore login -->
    <?php if (isset($templateParams["error"])): ?>
        <p><?php echo $templateParams["error"]; ?></p>
    <?php endif; ?>

    <!-- Form login -->
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
</form>