<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>uniNotes</title>
    <link rel="icon" href="uploads/img/favicon.png" type="image/x-icon" />
</head>

<body>
    <header>
        <nav>
            <a href="index.php">
                <img src="uploads/img/logo.png" alt="Logo" />
            </a>

            <div id="navbarNav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="corsi.php">Corsi</a></li>
                <?php if(isUserLoggedIn()) { ?>
                    <li><a href="creazione-articoli.php">Carica appunti</a></li>
                <?php } ?>
                <?php if(isUserAdmin()) { ?>
                    <li><a href="area-admin.php">Area Admin</a></li>
                <?php } ?>
                </ul>
                <?php if((!isUserLoggedIn())) { ?>
                    <a href="login.php">Login</a>
                <?php } else { ?>
                    <a href="logout.php">Logout</a>
                <?php } ?>
            </div>
        </nav>
    </header>

    <main>
        <?php if (isset($templateParams["nome"])) require($templateParams["nome"]); ?>
    </main>

    <footer>
        <div>
            <p>Paolo Foschini, Alessandro Testa, Ciro Bassi</p>
        </div>
    </footer>

</body>

</html>
