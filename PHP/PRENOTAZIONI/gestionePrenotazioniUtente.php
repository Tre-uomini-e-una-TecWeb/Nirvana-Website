<?php

session_start();
if($_SESSION["privilegi"] != "cliente"){
    header("Location: ../AMMINISTRAZIONE/403.php");
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
        $actual_date=date("Y-m-d");
        if (!preg_match("/\d{4}-\d{1,2}-\d{1,2}/",$data)){
            $errPrenotazione.='<p class=\'errore\'>Data per la prenotazione non valida: formato non valido!</p>';
            $canAskRes = false;
        } elseif($actual_date>$data){
            $errPrenotazione.='<p class=\'errore\'>Data per la prenotazione non valida (inserita data passata)!</p>';
            $canAskRes = false;
        }
        $ora = pulisciInput($_POST['hour']);
        $actual_time=date("H:i");
        if (!preg_match("/\d{2}:\d{2}/",$ora)){
            $errPrenotazione.='<p class=\'errore\'>Ora per la prenotazione non valida: formato non valido!</p>';
            $canAskRes = false;
        } elseif($actual_date==$data && $actual_time>$ora){
            $errPrenotazione.='<p class=\'errore\'>Data-ora per la prenotazione non valida (inserita data-ora passata)!</p>';
            $canAskRes = false;
        }

        if($canAskRes && ($ora<"09:00" || $ora >"19:00")){
            $errPrenotazione.='<p class=\'errore\'>Orario non valido: il centro é chiuso nell\'orario richiesto!</p>';
            $canAskRes = false;
        }
        $servizio = pulisciInput($_POST['service']);
    
        if($canAskRes){
            $queryOk=$connessione->insertNewReservationUser($cliente,$data,$ora,$servizio);
            if($queryOk){
                
                $esitoInserimento="<div class='conferma'><p>Richiesta di prenotazione avvenuta con successo!</p></div>";
            } else {
                
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
                        .    "<td data-title='Data:'>".$dataPrenotazione."</td>"
                          .  "<td data-title='Ora:'>".$oraPrenotazione."</td>"
                          .  "<td data-title='Stato richiesta:'>".$prenotazione['Stato']."</td>"
                          . "</tr>";
        }
    }
    else{
        $prenotazioni .= "<tr><td colspan='4'>Non ci sono prenotazioni da visualizzare.</td></tr>";
    }
} else {
    header("Location: ../AMMINISTRAZIONE/500.php");
    die();
}

$pagina_HTML=str_replace("<esitoForm />", $esitoInserimento, $pagina_HTML);
$pagina_HTML=str_replace("<prenotazioniEsistenti />", $prenotazioni, $pagina_HTML);
echo $pagina_HTML;

?>