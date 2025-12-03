<?php
require_once 'db/database.php';
require_once 'utils/functions.php';

if(isset($_POST["username"]) && isset($_POST["password"])){
    $login_result = $dbh->checkLogin($_POST["username"], $_POST["password"]);
    if(count($login_result)==0){
        $templateParams["errorelogin"] = "Errore! Controllare username o password!";
    }
    else{
        registerLoggedUser($login_result[0]);
    }
}

if(isUserLoggedIn()){
    $templateParams["titolo"] = "uniNotes - Home";
    $templateParams["nome"] = "index.php";
}
else{
    $templateParams["titolo"] = "uniNotes - Login";
    $templateParams["nome"] = "templates/login-form.php";
}
require 'templates/users-base.php';
?>
