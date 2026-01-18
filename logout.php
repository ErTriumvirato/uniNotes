<?php
require_once 'config.php';

if (isset($_SESSION['cookieBannerClosed']) && $_SESSION['cookieBannerClosed'] === true) { // Preserva lo stato del banner dei cookie
    $cookieBannerClosed = true;
}

// Distrugge la sessione utente
session_unset();
session_destroy();

if (isset($cookieBannerClosed) && $cookieBannerClosed === true) { // Ripristina lo stato del banner dei cookie
    session_start();
    $_SESSION['cookieBannerClosed'] = true;
}

header("Location: login.php");
exit();
