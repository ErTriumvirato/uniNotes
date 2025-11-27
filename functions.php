<?php

function isUserLoggedIn(){
    return !empty($_SESSION['idautore']);
}

function registerLoggedUser($user){
    $_SESSION["idautore"] = $user["idautore"];
    $_SESSION["username"] = $user["username"];
    $_SESSION["nome"] = $user["nome"];
}

?>