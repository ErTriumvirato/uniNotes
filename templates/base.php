<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>uniNotes</title>
    <link rel="icon" href="uploads/img/favicon.png" type="image/x-icon" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="css/style.css" />
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg custom-navbar px-3">
            <a class="navbar-brand d-flex align-items-center" href="index.php">
                <img src="uploads/img/logo.png" alt="Logo" />
            </a>

            <div class="d-flex d-lg-none ms-auto align-items-center">
                <a href="#" class="btn btn-primary me-2">Login</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <?php if(isUserAdmin()) { ?>
                <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-lg-auto text-center">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="gestione.php">Gestione</a></li>
                    <li class="nav-item"><a class="nav-link" href="approvazione_appunti.php">Approvazione Appunti</a></li>
                </ul>
            <?php } else if(isUserLoggedIn()){?>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-lg-auto text-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="corsi.php">Corsi</a></li>
                    <li class="nav-item"><a class="nav-link" href="creazione_appunti.php">Carica appunti</a></li>
                    <li class="nav-item"><a class="nav-link" href="contatti.php">Contatti</a></li>
                </ul>
            <?php } else {?>
                <ul class="navbar-nav mx-lg-auto text-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="corsi.php">Corsi</a></li>
                    <li class="nav-item"><a class="nav-link" href="contatti.php">Contatti</a></li>
                </ul>
            <?php } ?>
                <! -- Bottone di login/logout -->
                <?php if((!isUserLoggedIn())) { ?>
                    <a href="login.php" class="btn btn-primary d-none d-lg-block">Login</a>
                <?php } else { ?>
                    <a href="logout.php" class="btn btn-primary d-none d-lg-block">Logout</a>
                <?php } ?>
            </div>
        </nav>
    </header>

    <main>
        <!-- Contenuto della pagina -->
        <?php if (isset($templateParams["nome"])) require($templateParams["nome"]); ?>
    </main>

    <footer class="bg-dark text-light pt-4 pb-3 mt-5">
        <div class="container text-center">
            <p class="mb-1">Paolo Foschini, Alessandro Testa, Ciro Bassi</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>