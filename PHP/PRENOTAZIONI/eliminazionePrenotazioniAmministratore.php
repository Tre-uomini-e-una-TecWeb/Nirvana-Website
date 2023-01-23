<?php
session_start();
if($_SESSION["privilegi"]!="admin"){
    header("HTTP/1.1 403 Unauthorized");
    header("Location: ../../HTML/AMMINISTRAZIONE/403.html");
    die();
}
function pulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value);
    $value=htmlentities($value);
    return $value;
}
ini_set('display_errors', 1);
require_once "../connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../../HTML/PRENOTAZIONI/eliminazionePrenotazioniAmministratore.html");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$prenotazioni = "";
$prenotazioni .= "<select id=\"prenotazioni\" name=\"prenotazioneDaEliminare\">";
if($connOk){
    $query_result = $connessione->getPrenotazioni();
    if($query_result != null){
        $dataOggi = new DateTime(date("Y-m-d"));
        $prenotazioni .= "<option value=\"\" disabled selected>Selezionare la prenotazione da eliminare</option>";
        foreach($query_result as $prenotazione){
            $eta = $dataOggi->diff(new DateTime($prenotazione['DataNascita']));
            $prenotazioni .= "<option value=\"".$prenotazione['Username'] ." ". $prenotazione['DataOra']."\">";
            $prenotazioni .= "Nome: ".$prenotazione['Nome']." ";
            $prenotazioni .= "Cognome: ".$prenotazione['Cognome']." ";
            $prenotazioni .= "Etá: ".$eta->y." ";
            $prenotazioni .= "Data e ora: ".$prenotazione['DataOra']." ";
            $prenotazioni .= "Trattamento: ".$prenotazione['Trattamento']." ";
            $prenotazioni .= "Data e ora: ".$prenotazione['DataOra']." ";
            $prenotazioni .= "Stato prenotazione: ";
            switch ($prenotazione['Stato']){
                case 'A':
                    $prenotazioni .= "Accettata";
                    break;
                case 'R':
                    $prenotazioni .= "Rifiutata";
                    break;
                default:
                    $prenotazioni .= "In attesa";
            }
            $prenotazioni .= "</option>";
            //list($dataPrenotazione,$oraPrenotazione)=explode(" ",$prenotazione['DataOra']);
        }
    }
    else{
        $prenotazioni .= "<option value=\"\" disabled selected>Non esistono prenotazioni che possono essere eliminate</option>";
    }
}
else{
    $prenotazioni .= "<option value=\"\" disabled selected>Non é al momento possibile visualizzare le prenotazioni</option>";
}
$prenotazioni .= "</select>";

$pagina_HTML=str_replace("<listaPrenotazioni />", $prenotazioni, $pagina_HTML);
//$pagina_HTML=str_replace("<esitoForm />", $esitoInserimento, $pagina_HTML);
//$pagina_HTML=str_replace("<prenotazioniEsistenti />", $prenotazioni, $pagina_HTML);
//$pagina_HTML=str_replace("<esitoModifiche />", $esitoModifica, $pagina_HTML);
echo $pagina_HTML;
?>