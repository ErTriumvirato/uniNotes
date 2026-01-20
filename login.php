<?php
require_once 'config.php';

if (isUserLoggedIn()) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['login'])) { // Gestione del login
    $nomeUtente = $_POST['username'];
    $password = $_POST['password'];
    $utente = $dbh->getUsersWithFilters(username: $nomeUtente); // Recupera i dati utente dal database

    if ($utente && password_verify($password, $utente['password'])) { // Verifica la password
        $_SESSION['idutente'] = $utente['idutente'];
        $_SESSION['username'] = $utente['username'];
        $_SESSION['isAdmin'] = $utente['isAdmin'];

        // Gestione del redirect dopo il login
        $redirect = $_GET['redirect'] ?? null;
        if (isValidRedirect($redirect)) {
            header('Location: ' . $redirect);
            exit();
        }
        header("Location: index.php");
        exit();
    } else {
        $templateParams["error"] = "Username o password errati"; // Messaggio di errore in caso di credenziali errate
    }
}

function isValidRedirect($url) // Funzione per validare l'URL di redirect
{
    if (!isset($url) || empty($url)) {
        return false;
    }
    return true;
}

$templateParams["titolo"] = "Login";
$templateParams["nome"] = "templates/login-form.php";
array_push($templateParams["script"], "js/login-form.js");

require 'templates/base.php';
