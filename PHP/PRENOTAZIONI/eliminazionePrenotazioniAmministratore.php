<?php
session_start();
if($_SESSION["privilegi"]!="admin"){
    header("Location: ../AMMINISTRAZIONE/403.php");
    die();
}
function pulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value);
    $value=htmlentities($value);
    return $value;
}
require_once "../connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../../HTML/PRENOTAZIONI/eliminazionePrenotazioniAmministratore.html");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$prenotazioni = "";
$esitoDelete = "";
$prenotazioni .= "<select id=\"reservation\" name=\"prenotazioneDaEliminare\">";
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

if(isset($_POST['deleteP'])){
    if(isset($_POST['prenotazioneDaEliminare'])){
        list($username,$dataPrenotazione,$oraPrenotazione)=explode(" ",$_POST['prenotazioneDaEliminare']);
        $isDeleted = $connessione->deletePrenotazioni($username, $dataPrenotazione, $oraPrenotazione);
        if($isDeleted){
            $esitoDelete = "<p>Prenotazione eliminata con successo!</p>";
            //aggiorno le prenotazioni
            $prenotazioni = "<select id=\"reservation\" name=\"prenotazioneDaEliminare\">";
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
                }
            }
            else{
                $prenotazioni .= "<option value=\"\" disabled selected>Non esistono prenotazioni che possono essere eliminate</option>";
            }
        }
        else{
            $esitoDelete = "<p>Impossibile eliminare la prenotazione: prenotazione non esistente o non valida. Riprovare.</p>";
        }
    }
    else{
        $esitoDelete = "<p>Impossibile eliminare la prenotazione: non é stata selezionata nessuna prenotazione.</p>";
    }
}

$pagina_HTML=str_replace("<listaPrenotazioni />", $prenotazioni, $pagina_HTML);
$pagina_HTML=str_replace("<esitoDelete />", $esitoDelete, $pagina_HTML);
echo $pagina_HTML;
?>