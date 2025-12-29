<?php
require_once 'config.php';

if(!isUserLoggedIn()){
    header("Location: index.php");
    exit();
}

// templateParams
$templateParams["titolo"] = "uniNotes - Corsi Seguiti";
$templateParams["nome"] = "templates/lista-seguiti.php";

require_once 'templates/base.php';