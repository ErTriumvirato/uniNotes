<?php
require_once 'config.php';

if (isUserLoggedIn()) {
    header("Location: index.php");
    exit();
}

// Gestione della registrazione
if (isset($_POST['registrazione'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verifica se l'utente esiste già
    $existingUser = $dbh->getUserByUsername($username);
    $existingEmail = $dbh->getUserByEmail($email);

    if ($existingUser) {
        $templateParams["error"] = "Username già in uso";
    } elseif ($existingEmail) {
        $templateParams["error"] = "Email già in uso";
    } else {
        // Crea nuovo utente (ruolo 0 = utente standard)
        $dbh->createUser($username, $email, $password, 0);

        // Login automatico dopo registrazione
        $user = $dbh->getUserByUsername($username);
        $_SESSION['idutente'] = $user['idutente'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['isAdmin'] = $user['isAdmin'];

        header("Location: index.php");
        exit();
    }
}

$templateParams["titolo"] = "Registrazione";
$templateParams["nome"] = "templates/registrazione-utente-form.php";

require 'templates/base.php';
