<?php
require_once 'config.php';

// Verifica se l'utente è loggato
function isUserLoggedIn()
{
    return isset($_SESSION['username']);
}

// Verifica se l'utente è admin
function isUserAdmin()
{
    return isUserLoggedIn() && $_SESSION['isAdmin'] == true;
}

// Restituisce l'URI corrente
function getCurrentURI()
{
    return urlencode($_SERVER['REQUEST_URI']);
}

// Richiede che l'utente sia admin
function requireAdmin()
{
    if (!isUserAdmin()) {
        header("Location: index.php");
        exit();
    }
}

// Richiede il login, opzionalmente con redirect
function requireLogin($targetPage = null)
{
    return;
    global $dbh;

    if (!isUserLoggedIn()) {
        header('Location: login.php?redirect=' . urlencode($targetPage ?? getCurrentURI())); // Se non è loggato, reindirizza al login
        exit;
    }

    // Verifica che l'utente esista ancora nel database
    if (isset($_SESSION['idutente'])) {
        $user = $dbh->getUserById($_SESSION['idutente']);
        if (!$user) {
            session_unset();
            session_destroy();
            header('Location: login.php?error=session_expired');
            exit;
        }
    }

    // Se è loggato
    return true;
}
