<?php
require_once 'config.php';

requireLogin();

$currentUser = $dbh->getUserById($_SESSION['idutente']); // Dati dell'utente corrente

if (isset($_POST['submit'])) { // Gestione dell'aggiornamento del profilo
    $newUsername = trim($_POST['username']);
    $newEmail = trim($_POST['email']);
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (!empty($newPassword) && $newPassword !== $confirmPassword) { // Verifica che le password coincidano
        $templateParams["messaggio"] = "Le password non coincidono!";
    }

    // Controllo se il nome utente è già in uso
    if (!isset($templateParams["messaggio"]) && $newUsername !== $currentUser['username']) {
        $existingUser = $dbh->getUsersWithFilters(username: $newUsername);
        if ($existingUser) {
            $templateParams["messaggio"] = "Username già in uso!";
        }
    }

    // Se non ci sono errori
    if (!isset($templateParams["messaggio"])) {
        // Se il campo password è vuoto, non aggiorno la password
        $passwordToUpdate = !empty($newPassword) ? $newPassword : null;

        // Aggiorno i dati dell'utente
        $result = $dbh->updateUser($currentUser['idutente'], $newUsername, $newEmail, $currentUser['isAdmin'], $passwordToUpdate);

        if ($result) {
            $templateParams["messaggio"] = "Profilo aggiornato con successo!";
            // Aggiorno i dati nella sessione
            $_SESSION['username'] = $newUsername;
            $currentUser['username'] = $newUsername;
            $currentUser['email'] = $newEmail;
        } else {
            $templateParams["messaggio"] = "Errore durante l'aggiornamento del profilo.";
        }
    }
}

// Gestione dell'eliminazione dell'account
if (isset($_POST['delete_account'])) {
    $canDelete = true;
    if ($currentUser['isAdmin']) { // Controlla se è l'ultimo admin
        if ($dbh->getAdminCount() <= 1) {
            $canDelete = false;
            $templateParams["messaggio"] = "Sei l'ultimo amministratore!";
        }
    }

    // Se può, elimina l'account
    if ($canDelete) {
        if ($dbh->deleteUser($currentUser['idutente'])) { // Elimina l'utente dal database
            session_destroy();
            header("Location: index.php");
            exit();
        } else {
            $templateParams["messaggio"] = "Errore durante l'eliminazione dell'account.";
        }
    }
}

$templateParams["currentUser"] = $currentUser;
$templateParams["titolo"] = "Impostazioni";
$templateParams["nome"] = "templates/impostazioni-template.php";
array_push($templateParams["script"], "js/impostazioni.js");

require_once 'templates/base.php';
