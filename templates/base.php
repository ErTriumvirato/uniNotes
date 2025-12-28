<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
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
            <a class="navbar-brand d-flex align-items-center" href="gestione.php">
                <img src="uploads/img/logo.png" alt="Logo" />
            </a>

            <div class="d-flex d-lg-none ms-auto align-items-center">
                <a href="#" class="btn btn-primary me-2">Login</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-lg-auto text-center">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="corsi.php">Corsi</a></li>
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 2) { ?>
                        <li class="nav-item"><a class="nav-link" href="seguiti.php">Seguiti</a></li>
                    <?php } elseif (isset($_SESSION['role']) && $_SESSION['role'] == 1) { ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="gestione.php">Gestione</a></li>
                    <?php } ?>
                    <li class="nav-item"><a class="nav-link" href="contatti.php">Contatti</a></li>
                </ul>
                
                <?php
                    if((!isUserLoggedIn()))
                        echo '<a href="login.php" class="btn btn-primary d-none d-lg-block">Login</a>';
                    else{
                        echo '<a href="logout.php" class="btn btn-primary d-none d-lg-block">Logout</a>';
                    }
                ?>
            </div>
        </nav>
    </header>

    <main>
        <?php
        if (isset($templateParams["nome"])) {
            require($templateParams["nome"]);
        }
        ?>
    </main>

    <footer class="bg-dark text-light pt-4 pb-3 mt-5">
        <div class="container text-center">
            <p class="mb-1">Paolo Foschini, Alessandro Testa, Ciro Bassi</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>