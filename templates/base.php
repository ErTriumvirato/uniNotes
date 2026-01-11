<!DOCTYPE html>
<html lang="it">

<?php
// Impedisce la memorizzazione nella cache delle pagine (per non dover aggiornare manualmente dopo aver cliccato il tasto "indietro")
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>

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
                <div class="d-flex align-items-center gap-3">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <a class="navbar-brand" href="index.php">
                        <img src="uploads/img/logo.png" alt="Logo uniNotes" class="logo-img">
                    </a>
                </div>

                <div class="offcanvas offcanvas-start flex-grow-1" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
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
                                    <a class="nav-link" href="creazione-appunti.php">Carica appunti</a>
                                </li>
                            <?php } ?>
                            <?php if (isUserAdmin()) { ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="area-admin.php">Area Amministrazione</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>

                <div class="d-flex justify-content-end align-items-center gap-2">
                    <?php if (!isUserLoggedIn()) { ?>
                        <a href="login.php" class="btn btn-primary btn-sm px-4">Accedi</a>
                    <?php } else { ?>
                        <nav class="user-menu" aria-label="Menu utente">
                            <button type="button" class="nav-link user-menu-btn" onclick="toggleUserMenu()" aria-expanded="false" aria-haspopup="true" aria-controls="user-dropdown">
                                <?php echo htmlspecialchars($_SESSION['username']); ?> ▾
                            </button>
                            <ul id="user-dropdown" class="user-dropdown" role="menu" aria-label="Opzioni utente">
                                <li role="none"><a href="profilo.php" class="user-dropdown-item" role="menuitem">Profilo</a></li>
                                <li role="none"><a href="impostazioni.php" class="user-dropdown-item" role="menuitem">Impostazioni</a></li>
                                <li role="none"><a href="logout.php" class="user-dropdown-item" role="menuitem">Esci</a></li>
                            </ul>
                        </nav>
                    <?php } ?>
                </div>
            </div>
        </nav>
    </header>

    <!-- Banner errori -->
    <div id="error-banner" class="alert alert-danger alert-dismissible fade position-fixed top-0 start-50 translate-middle-x mt-5 pt-4" role="alert" style="display: none; max-width: 90%; margin-top: 80px !important; z-index: 2000;">
        <span id="error-message"></span>
        <button type="button" class="btn-close" onclick="hideError()" aria-label="Chiudi"></button>
    </div>

    <!-- Contenuto principale -->
    <main class="container flex-grow-1 mt-5 pt-5">
        <?php
        if (($templateParams["nome"] === "templates/dettagli-corso.php")
            || ($templateParams["nome"] === "templates/gestione-corsi.php")
            || ($templateParams["nome"] === "templates/gestione-utenti.php")
            || ($templateParams["nome"] === "templates/appunti-da-approvare.php")
            || ($templateParams["nome"] === "templates/appunti-da-gestire.php")
            || ($templateParams["nome"] === "templates/menu-appunti.php")
            || ($templateParams["nome"] === "templates/dettagli-appunto.php")
        ) { ?>
            <button class="btn btn-secondary mb-3" onclick="goBack()">← Indietro</button>
        <?php
        } ?>
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
    <script>
        let bannerTimeout;

        function showBanner(message, type) {
            const banner = document.getElementById('error-banner');
            const msgSpan = document.getElementById('error-message');
            msgSpan.textContent = message;
            
            banner.classList.remove('alert-danger', 'alert-success');
            banner.classList.add('alert-' + type);
            
            banner.style.display = 'block';
            setTimeout(() => banner.classList.add('show'), 10);
            
            if (bannerTimeout) clearTimeout(bannerTimeout);
            bannerTimeout = setTimeout(hideError, 5000);
        }

        function showError(message) {
            showBanner(message, 'danger');
        }

        function showSuccess(message) {
            showBanner(message, 'success');
        }

        function hideError() {
            const banner = document.getElementById('error-banner');
            banner.classList.remove('show');
            setTimeout(() => { banner.style.display = 'none'; }, 150);
        }

        function goBack() {
            const referrer = document.referrer;
            const currentDomain = window.location.origin;
            const currentUrl = window.location.href;

            if (referrer &&
                referrer.startsWith(currentDomain) &&
                currentUrl !== currentDomain &&
                currentUrl !== currentDomain + '/' &&
                currentUrl !== currentDomain + '/index.php' &&
                currentUrl !== referrer) {

                history.back();
            } else if (!referrer.startsWith(currentDomain)) {
                window.location.href = '/';
            }
        }

        function toggleUserMenu() {
            const dropdown = document.getElementById('user-dropdown');
            const btn = document.querySelector('.user-menu-btn');
            const isOpen = dropdown.classList.toggle('open');
            btn.setAttribute('aria-expanded', isOpen);
        }

        document.addEventListener('click', function(e) {
            const userMenu = document.querySelector('.user-menu');
            const dropdown = document.getElementById('user-dropdown');
            if (userMenu && dropdown && !userMenu.contains(e.target)) {
                dropdown.classList.remove('open');
                document.querySelector('.user-menu-btn')?.setAttribute('aria-expanded', 'false');
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const dropdown = document.getElementById('user-dropdown');
                if (dropdown?.classList.contains('open')) {
                    dropdown.classList.remove('open');
                    document.querySelector('.user-menu-btn')?.setAttribute('aria-expanded', 'false');
                    document.querySelector('.user-menu-btn')?.focus();
                }
            }
        });
    </script>
</body>

</html>