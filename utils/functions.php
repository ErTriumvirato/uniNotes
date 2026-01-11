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
    // if not logged in
    if (!isUserLoggedIn()) {
        header('Location: login.php?redirect=' . urlencode($targetPage ?? getCurrentURI()));
        exit;
    }
    
    // if logged in
    return true;
}
