<div>
    <h2>Impostazioni Profilo</h2>
    
    <?php if(isset($templateParams["messaggio"])): ?>
        <p><?php echo $templateParams["messaggio"]; ?></p>
    <?php endif; ?>

    <form action="impostazioni.php" method="POST">
        <ul>
            <li>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($templateParams["currentUser"]["username"]); ?>" required />
            </li>
            <li>
                <label for="password">Nuova Password (lascia vuoto per non cambiare):</label>
                <input type="password" id="password" name="password" />
            </li>
            <li>
                <input type="submit" name="submit" value="Salva Modifiche" />
            </li>
        </ul>
    </form>
</div>