<?php
require_once 'config.php';

function isUserLoggedIn() {
    return isset($_SESSION['username']);
}

function isUserAdmin() {
    return isUserLoggedIn() && $_SESSION['isAdmin'] == true;
}

function getCurrentURI() {
    return urlencode($_SERVER['REQUEST_URI']);
}

function requireLogin($targetPage = null) {
    global $dbh;
    // if not logged in
    if (!isUserLoggedIn()) {
        header('Location: login.php?redirect=' . urlencode($targetPage ?? getCurrentURI()));
        exit;
    }

    // Verify user still exists in DB (handling stale sessions)
    if (isset($_SESSION['idutente'])) {
        $user = $dbh->getUserById($_SESSION['idutente']);
        if (!$user) {
            session_unset();
            session_destroy();
            header('Location: login.php?error=session_expired');
            exit;
        }
    }
    
    // if logged in
    return true;
}
