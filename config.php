<?php
session_start();
define("UPLOAD_DIR", "./upload/");
require_once("utils/functions.php");
require_once("db/database.php");
if(true) {
    $dbh = new DatabaseHelper("db", "user", "user_password", "uniNotes", 3306);
} else {
    $dbh = new DatabaseHelper("localhost", "root", "", "uniNotes", 3306);
}