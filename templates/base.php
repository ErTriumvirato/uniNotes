<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>uniNotes - <?php echo $templateParams["titolo"] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="icon" href="uploads/img/favicon.png" type="image/x-icon" />
</head>

<body class="d-flex flex-column min-vh-100">

    <header>
        <!-- Barra di navigazione -->
        <nav class="navbar navbar-expand-lg navbar-light shadow-sm fixed-top" aria-label="Barra di navigazione">
            <div class="container-fluid">
                <!-- Logo -->
                <a class="navbar-brand" href="index.php">
                    <img src="uploads/img/logo.png" alt="Logo uniNotes" class="logo-img" />
                </a>

                <!-- Pulsante menu per dispositivi mobile -->
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#navbarOffcanvas" aria-controls="navbarOffcanvas" aria-label="Apri menu di navigazione">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Menu di navigazione -->
                <div class="offcanvas offcanvas-end flex-lg-grow-1" tabindex="-1" id="navbarOffcanvas" aria-labelledby="navbarOffcanvasLabel">
                    <div class="offcanvas-header d-lg-none">
                        <h5 class="offcanvas-title" id="navbarOffcanvasLabel">Menu</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Chiudi"></button>
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
                                    <a class="nav-link" href="creazione-appunto.php">Carica</a>
                                </li>
                            <?php } ?>
                            <?php if (isUserAdmin()) { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="menu-gestione.php">Area amministrazione</a>
                                </li>
                            <?php } ?>
                        </ul>

                        <!-- Menu utente -->
                        <?php if (!isUserLoggedIn()) { ?>
                            <div class="d-flex justify-content-center">
                                <a href="login.php" class="btn btn-primary">Accedi</a>
                            </div>
                        <?php } else { ?>
                            <div class="dropdown user-menu">
                                <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bi bi-person-circle me-1"></i>
                                    <?php echo htmlspecialchars($_SESSION['username']); ?>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="profilo-utente.php">Profilo</a></li>
                                    <li><a class="dropdown-item" href="impostazioni.php">Impostazioni</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="logout.php">Esci</a></li>
                                </ul>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Banner errori -->
    <div id="error-banner"
        class="alert alert-danger alert-dismissible position-fixed top-0 start-50 translate-middle-x mt-5 pt-4 w-auto error-banner banner-layer is-hidden" role="alert" aria-live="assertive">
        <span id="error-message"></span>
        <button type="button" class="btn-close" id="btn-close-error" aria-label="Chiudi"></button>
    </div>


    <!-- Banner cookie -->
    <?php if (!isset($_SESSION['cookieBannerClosed']) || $_SESSION['cookieBannerClosed'] !== true): ?>
        <div id="cookie-banner" class="alert alert-light border shadow-sm position-fixed bottom-0 start-0 end-0 m-0 rounded-0 banner-layer" role="dialog" aria-live="polite" aria-label="Informativa cookie">
            <div class="container d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 py-3">
                <div>
                    <strong>Informativa cookie</strong>
                    <p class="mb-0 small">Usiamo solo cookie di sessione per il funzionamento del sito. Nessun cookie di profilazione.</p>
                </div>
                <button id="cookie-accept" type="button" class="btn btn-primary btn-sm px-4">Ok</button>
            </div>
        </div>
    <?php endif; ?>

    <main class="container flex-grow-1 mt-5 pt-5" tabindex="-1">
        <!-- Pulsante indietro solamente per alcune pagine -->
        <?php
        $backButtonPages = [
            "templates/dettagli-corso-template.php",
            "templates/gestione-corsi-template.php",
            "templates/gestione-ssd-template.php",
            "templates/gestione-utenti-template.php",
            "templates/appunti-da-approvare.php",
            "templates/gestione-appunti-template.php",
            "templates/menu-appunti.php",
            "templates/dettagli-appunto-template.php"
        ];

        if (isset($templateParams["nome"]) && in_array($templateParams["nome"], $backButtonPages)):
        ?>
            <button class="btn btn-outline-secondary mb-3" id="btn-back">← Indietro</button>
        <?php
        endif;
        ?>

        <!-- Contenuto principale della pagina -->
        <div class="py-4">
            <!-- Include il template specifico della pagina -->
            <?php
            if (isset($dbh) && $dbh->errorConnection) {
                // Non caricare il template se c'è un errore di connessione
            } elseif (isset($templateParams["nome"])) {
                require($templateParams["nome"]);
            }
            ?>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-auto">
        <div class="container text-center">
            <p class="mb-0 small"> uniNotes - Paolo Foschini, Alessandro Testa, Ciro Bassi</p>
        </div>
    </footer>
</body>

<!-- Script Bootstrap -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

<!-- Rendering di formule matematiche -->
<script type="text/javascript" id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-svg.js"></script>

<?php
// Inclusione di script .js passati come parametri
if (isset($templateParams["script"])) {
    foreach ($templateParams["script"] as $script) {
        echo '<script src="' . $script . '"></script>';
    }
}
?>

<?php if (isset($dbh) && $dbh->errorConnection): ?>
    <script>
        showError("<?php echo $dbh->errorConnection; ?>");
    </script>
<?php endif; ?>

</html>