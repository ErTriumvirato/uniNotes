<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>uniNotes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" href="uploads/img/favicon.png" type="image/x-icon" />
</head>

<body class="d-flex flex-column min-vh-100">

    <header>
        <nav class="navbar navbar-expand-lg navbar-light shadow-sm fixed-top">
            <div class="container-fluid">

                <a class="navbar-brand" href="index.php">
                    <img src="uploads/img/logo.png" alt="uniNotes Logo" class="logo-img">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                    <div class="offcanvas-header">
                        <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav mx-auto mb-2 mb-lg-0 text-center">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="corsi.php">Corsi</a>
                            </li>
                            <?php if (isUserLoggedIn()) { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="creazione-articoli.php">Carica appunti</a>
                                </li>
                            <?php } ?>
                            <?php if (isUserAdmin()) { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="area-admin.php">Area Amministratore</a>
                                </li>
                            <?php } ?>
                        </ul>
                        <div class="d-flex justify-content-center justify-content-lg-end gap-2">
                            <?php if ((!isUserLoggedIn())) { ?>
                                <a href="login.php" class="btn btn-outline-primary btn-sm px-4">Accedi</a>
                            <?php } else { ?>
                                <a href="impostazioni.php" class="btn btn-outline-secondary btn-sm">Impostazioni</a>
                                <a href="logout.php" class="btn btn-danger btn-sm">Esci</a>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Contenuto princinpale -->
    <main class="container flex-grow-1 mt-5 pt-5">
        <div class="py-4">
            <?php if (isset($templateParams["nome"])) require($templateParams["nome"]); ?>
        </div>
    </main>

    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0 small"> uniNotes - Paolo Foschini, Alessandro Testa, Ciro Bassi</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>