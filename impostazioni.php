<?php
require_once 'config.php';

requireLogin();

$currentUser = $dbh->getUserById($_SESSION['idutente']);

if(isset($_POST['submit'])) {
    $newUsername = trim($_POST['username']);
    $newEmail = trim($_POST['email']);
    $newPassword = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (!empty($newPassword) && $newPassword !== $confirmPassword) {
        $templateParams["messaggio"] = "Le password non coincidono!";
    }
    
    // Controllo se username è cambiato e se è già in uso
    if(!isset($templateParams["messaggio"]) && $newUsername !== $currentUser['username']) {
        $existingUser = $dbh->getUserByUsername($newUsername);
        if($existingUser) {
            $templateParams["messaggio"] = "Username già in uso!";
        }
    }
    
    if(!isset($templateParams["messaggio"])) {
        // Se la password è vuota, passo null per non aggiornarla
        $passwordToUpdate = !empty($newPassword) ? $newPassword : null;
        
        // Mantengo il ruolo attuale
        $result = $dbh->updateUser($currentUser['idutente'], $newUsername, $newEmail, $currentUser['isAdmin'], $passwordToUpdate);
        
        if($result) {
            $templateParams["messaggio"] = "Profilo aggiornato con successo!";
            // Aggiorno sessione e dati utente corrente
            $_SESSION['username'] = $newUsername;
            $currentUser['username'] = $newUsername;
            $currentUser['email'] = $newEmail;
        } else {
            $templateParams["messaggio"] = "Errore durante l'aggiornamento del profilo.";
        }
    }
}

if(isset($_POST['delete_account'])) {
    $canDelete = true;
    if ($currentUser['isAdmin']) {
        if ($dbh->getAdminCount() <= 1) {
            $canDelete = false;
            $templateParams["messaggio"] = "Sei l'ultimo amministratore!";
        }
    }

    if ($canDelete) {
        if($dbh->deleteUser($currentUser['idutente'])) {
            session_destroy();
            header("Location: index.php");
            exit();
        } else {
            $templateParams["messaggio"] = "Errore durante l'eliminazione dell'account.";
        }
    }
}

$templateParams["currentUser"] = $currentUser;
$templateParams["titolo"] = "uniNotes - Impostazioni";
$templateParams["nome"] = "templates/impostazioni-form.php";
require_once 'templates/base.php';