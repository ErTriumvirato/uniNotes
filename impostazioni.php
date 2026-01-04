<?php
require_once 'config.php';

if(!isUserLoggedIn()) {
    header("Location: index.php");
    exit();
}

$currentUser = $dbh->getUserById($_SESSION['idutente']);

if(isset($_POST['submit'])) {
    $newUsername = trim($_POST['username']);
    $newPassword = $_POST['password'];
    
    // Controllo se username è cambiato e se è già in uso
    if($newUsername !== $currentUser['username']) {
        $existingUser = $dbh->getUserByUsername($newUsername);
        if($existingUser) {
            $templateParams["messaggio"] = "Errore: Username già in uso!";
        }
    }
    
    if(!isset($templateParams["messaggio"])) {
        // Se la password è vuota, passo null per non aggiornarla
        $passwordToUpdate = !empty($newPassword) ? $newPassword : null;
        
        // Mantengo il ruolo attuale
        $result = $dbh->updateUser($currentUser['idutente'], $newUsername, $currentUser['isAdmin'], $passwordToUpdate);
        
        if($result) {
            $templateParams["messaggio"] = "Profilo aggiornato con successo!";
            // Aggiorno sessione e dati utente corrente
            $_SESSION['username'] = $newUsername;
            $currentUser['username'] = $newUsername;
        } else {
            $templateParams["messaggio"] = "Errore durante l'aggiornamento del profilo.";
        }
    }
}

$templateParams["currentUser"] = $currentUser;
$templateParams["titolo"] = "uniNotes - Impostazioni";
$templateParams["nome"] = "templates/impostazioni-form.php";
require_once 'templates/base.php';