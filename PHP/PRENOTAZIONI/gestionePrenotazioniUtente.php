<?php

session_start();
if($_SESSION["privilegi"] != "cliente"){
    header("HTTP/1.1 403 Unauthorized");
    header("Location: ../../HTML/AMMINISTRAZIONE/403.html");
    die();
}
require_once "../connessione.php";
use DB\DBAccess;

$pagina_HTML = file_get_contents("../../HTML/PRENOTAZIONI/gestionePrenotazioniUtente.html");

$connessione = new DBAccess();
$connOk = $connessione->openDBConnection();

$esitoInserimento="";
$prenotazioni = "";               

function pulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value);
    $value=htmlentities($value);
    return $value;
}

if($connOk){
    if(isset($_POST['submit'])){
        $errPrenotazione = "";
        $canAskRes = true;
        $cliente = $_SESSION["username"];
        $data = pulisciInput($_POST['date']);
        if (!preg_match("/\d{4}-\d{1,2}-\d{1,2}/",$data)){
            $errPrenotazione.='<p class=\'errore\'>Data per la prenotazione non valida: formato non valido!</p>';
            $canAskRes = false;
        }
        $ora = pulisciInput($_POST['hour']);
        if (!preg_match("/\d{2}:\d{2}/",$ora)){
            $errPrenotazione.='<p class=\'errore\'>Ora per la prenotazione non valida: formato non valido!</p>';
            $canAskRes = false;
        }
        if($canMakeRes && ($ora<"09:00" || $ora >"19:00")){
            $errPrenotazione.='<p class=\'errore\'>Orario non valido: il centro é chiuso nell\'orario richiesto!</p>';
            $canAskRes = false;
        }
        $servizio = pulisciInput($_POST['service']);
    
        if($canAskRes){
            $queryOk=$connessione->insertNewReservationUser($cliente,$data,$ora,$servizio);
            if($queryOk){
                // Prenotazione inserita!
                $esitoInserimento="<div class='conferma'><p>Richiesta di prenotazione avvenuta con successo!</p></div>";
            } else {
                // Prenotazione non inserita: cliente ha una prenotazione per ora e data scelti!
                $esitoInserimento="<div class='errore'><p>Una prenotazione è già presente per l'orario selezionato!</p></div>";
            }
        } else {
            $esitoInserimento=$errPrenotazione;
        }
    }


    $query_result = $connessione->getPrenotazioniUtente($_SESSION["username"]);
    if($query_result != null){
        foreach($query_result as $prenotazione){
            $prenotazioni .= "<tr>";
            list($dataPrenotazione,$oraPrenotazione)=explode(" ",$prenotazione['DataOra']);
            $prenotazioni .= "<td data-title='' class='header'>".$prenotazione['Trattamento']."</td>"
                        .    "<td data-title='Data: '>".$dataPrenotazione."</td>"
                          .  "<td data-title='Ora: '>".$oraPrenotazione."</td>"
                          .  "<td data-title='Stato richiesta: '>".$prenotazione['Stato']."</td>"
                          . "</tr>";
        }
    }
    else{
        $prenotazioni .= "<tr><td>Non ci sono prenotazioni da visualizzare.</td></tr>";
    }
} else {
    $prenotazioni="<div class='errore'><p>I nostri sistemi sono al momento non funzionanti, ci scusiamo per il disagio.</p></div>";
}

$pagina_HTML=str_replace("<esitoForm />", $esitoInserimento, $pagina_HTML);
$pagina_HTML=str_replace("<prenotazioniEsistenti />", $prenotazioni, $pagina_HTML);
echo $pagina_HTML;

?>