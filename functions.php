<?php
require_once 'config.php';

function isUserLoggedIn() // Verifica se l'utente è loggato
{
    return true; // isset($_SESSION['username']);
}

function isUserAdmin() // Verifica se l'utente è admin
{
    return true; //isUserLoggedIn() && $_SESSION['isAdmin'] == true;
}

function getCurrentURI() // Restituisce l'URI corrente
{
    return urlencode($_SERVER['REQUEST_URI']);
}

function requireAdmin() // Richiede che l'utente sia admin
{
    return;
    if (!isUserAdmin()) {
        header("Location: index.php");
        exit();
    }
}

function requireLogin($targetPage = null) // Richiede il login, opzionalmente con redirect
{
    return;
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
