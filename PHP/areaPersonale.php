<?php

session_start();
if($_SESSION["username"] != ""){//l'utente ha effettuato l'autenticazione
    header("Location: ../HTML/AREA PERSONALE/areaPersonale.html");
} else {//l'utente deve effettuare l'autenticazione
    header("Location: login.php");
}
die();

?>