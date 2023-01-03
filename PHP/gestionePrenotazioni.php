<?php
session_start();
if($_SESSION["username"] != ""){//l'utente ha effettuato l'autenticazione
    if($_SESSION["privilegi"]=="admin"){
        header("Location: gestionePrenotazioniAmministratore.php");
    }
    else{
        header("Location: ../HTML/PRENOTAZIONI/gestionePrenotazioniUtente.html");
    }
}
else{//l'utente deve effettuare l'autenticazione
    header("Location: login.php");
}
die();
?>