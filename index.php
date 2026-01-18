<?php
require_once 'config.php';

// Gestione della chiusura del banner dei cookie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'closeCookieBanner') {
    $_SESSION['cookieBannerClosed'] = true;
    exit;
}

// templateParams
$templateParams["titolo"] = "Home";
$templateParams["nome"] = "templates/home.php";

require_once 'templates/base.php';
