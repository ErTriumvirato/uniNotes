<?php
require_once 'config.php';

if(!isUserAdmin()){
    header("Location: index.php");
    exit();
}

$templateParams["titolo"] = "uniNotes - Approvazione Appunti";
$templateParams["nome"] = "templates/approvazione.php";
require_once 'templates/base.php';