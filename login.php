<?php
require_once 'db/database.php';
require_once 'utils/functions.php';
require_once 'config.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Login
if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    if(isUserLoggedIn()) {
        //echo "<script>console.log('dsdsdsds');</script>";
    }
    //echo "<script>console.log('dsdsdsds');</script>";
    if (empty($username) || empty($password)) {
        $templateParams["error"] = "Inserisci username e password.";
    } else {
        $user = $dbh->getUserByUsername($username);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $templateParams["error"] = "Username o password errati.";
        }
    }
}

// Logout
if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
if (isUserLoggedIn()) {
    if(isUserLoggedIn()) {
        //echo "<script>console.log('dsdsdsds');</script>";
    }
    header("Location: dashboard.php");
    exit();
}
$templateParams["titolo"] = "uniNotes - Login";
$templateParams["nome"] = "templates/login-form.php";

require 'templates/base.php';

