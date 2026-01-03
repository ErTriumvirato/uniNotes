<?php
require_once 'config.php';

if(!isUserLoggedIn()) {
    header("Location: index.php");
    exit();
}

$templateParams["titolo"] = "uniNotes - Impostazioni";
$templateParams["nome"] = "templates/settings-form.php";
require_once 'templates/base.php';