<?php
require_once 'config.php';

if(!isUserAdmin()){
    header("Location: index.php");
    exit();
}

$templateParams["titolo"] = "uniNotes - Gestione";
$templateParams["nome"] = "templates/management.php";

require_once 'templates/base.php';
