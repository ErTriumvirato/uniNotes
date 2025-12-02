<article class="card shadow-sm border-0 my-4" style="max-width: 600px; margin: auto;">
    <form action="#" method="POST">
    <?php if(isset($templateParams["errorelogin"])): ?>
    <p><?php echo $templateParams["errorelogin"]; ?></p>
    <?php endif; ?>
    <ul>
        <li>
            <label for="username">Username:</label><input type="text" id="username" name="username" />
        </li>
        <li>
            <label for="password">Password:</label><input type="password" id="password" name="password" />
        </li>
        <li>
            <input type="submit" name="submit" value="Invia" />
        </li>
    </ul>
</form>
</article>