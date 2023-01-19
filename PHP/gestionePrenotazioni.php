<?php
session_start();
if(array_key_exists("username", $_SESSION) && $_SESSION["username"] != ""){//l'utente ha effettuato l'autenticazione
    echo $_SESSION["privilegi"];
    if($_SESSION["privilegi"]=="admin"){
        header("Location: PRENOTAZIONI/gestionePrenotazioniAmministratore.php");
    }
    else{
        header("Location: PRENOTAZIONI/gestionePrenotazioniUtente.php");
    }
}
else{//l'utente deve effettuare l'autenticazione
    header("HTTP/1.1 401 Unauthenticated");
    header("Location: ../HTML/AMMINISTRAZIONE/401.html");
}
die();
?>