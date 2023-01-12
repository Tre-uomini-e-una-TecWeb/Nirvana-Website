<?php
session_start();
if(array_key_exists("username", $_SESSION) && $_SESSION["username"] != ""){//l'utente ha effettuato l'autenticazione
    echo $_SESSION["privilegi"];
    if($_SESSION["privilegi"]=="admin"){
        header("Location: gestionePrenotazioniAmministratore.php");
    }
    else{
        header("Location: gestionePrenotazioniUtente.php");
    }
}
else{//l'utente deve effettuare l'autenticazione
    header("Location: login.php");
}
die();
?>