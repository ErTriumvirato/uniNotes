<?php
require_once 'config.php';

if(!isUserAdmin()){
    header("Location: index.php");
    exit();
}

$templateParams["titolo"] = "uniNotes - Dashboard";
$templateParams["nome"] = "templates/dati-utenti.php";
require_once 'templates/base.php';
