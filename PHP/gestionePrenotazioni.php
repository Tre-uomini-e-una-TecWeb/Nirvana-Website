<?php
session_start();
if(array_key_exists("username", $_SESSION) && $_SESSION["username"] != ""){
    echo $_SESSION["privilegi"];
    if($_SESSION["privilegi"]=="admin"){
        header("Location: PRENOTAZIONI/gestionePrenotazioniAmministratore.php");
    }
    else{
        header("Location: PRENOTAZIONI/gestionePrenotazioniUtente.php");
    }
}
else{
    header("Location: AMMINISTRAZIONE/401.php");
}
die();
?>