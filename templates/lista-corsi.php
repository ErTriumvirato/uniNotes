<?php
    require_once 'db/database.php';
    require_once 'config.php';
    
    $lista = $dbh->getCoursesWithSSD();
    foreach($lista as $corso){
        echo $corso['nomeCorso'] . " (" . $corso['nomeSSD'] . ")" . "<br>" . $corso['descrizioneCorso'] . "<br>";
    }
?>
    