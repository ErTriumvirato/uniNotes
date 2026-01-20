<?php
require_once 'config.php';

requireAdmin();

$templateParams["titolo"] = "Gestione Appunti";
$templateParams["nome"] = "templates/appunti-da-gestire.php";
array_push($templateParams["script"], "js/appunti.js");

require_once 'templates/base.php';
