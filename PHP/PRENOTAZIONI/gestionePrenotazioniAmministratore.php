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
//ini_set('display_errors', 1);
require_once "../connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../../HTML/PRENOTAZIONI/gestionePrenotazioniAmministratore.html");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$clienti="";
$esitoInserimento="";
$prenotazioni = "";
$prenotazioniDaVerificare = [];
$numPrenotazioniDaVerificare = 0;
$esitoModifica = "";
if($connOk){
    $query_result = $connessione->getUtenti();
    $clienti="<select id=\"customers\" name=\"customers\" required aria-required=\"true\">";
    $clienti.="<option value=\"\" selected>Selezionare un cliente</option>";
    if($query_result != null){
        foreach($query_result as $cliente){
            $clienti.="<option value=\"".$cliente['Username']."\">".$cliente['Nome']." ".$cliente['Cognome']." ".$cliente['DataNascita']."</option>";
        }
    }
    $clienti.="</select>";
    $query_result = $connessione->getPrenotazioni();
    if($query_result != null){
        $dataOggi = new DateTime(date("Y-m-d"));
        foreach($query_result as $prenotazione){
            $prenotazioni.="<tr>";
            list($dataPrenotazione,$oraPrenotazione)=explode(" ",$prenotazione['DataOra']);
            $idPrenotazione = $prenotazione['Username'] . $dataPrenotazione . $oraPrenotazione;
            $prenotazioni .= "<td data-title='' class='header'>".$prenotazione['Nome']." ".$prenotazione['Cognome']."</td>";
            $eta = $dataOggi->diff(new DateTime($prenotazione['DataNascita']));
            $prenotazioni .= "<td data-title='Età: '>".$eta->y."</td>";
            switch ($prenotazione['Stato']){
                case 'A':
                    $prenotazioni .= "<td data-title='Data: '>".$dataPrenotazione."</td>";
                    $prenotazioni .= "<td data-title='Orario: '>".$oraPrenotazione."</td>";
                    break;
                case 'R':
                    $prenotazioni .= "<td data-title='Data: '>".$dataPrenotazione."</td>";
                    $prenotazioni .= "<td data-title='Orario: '>".$oraPrenotazione."</td>";
                    break;
                default:
                $prenotazioni .= "<td data-title='Data: ' class='header'><input placeholder=\"".$dataPrenotazione."\" class=\"textbox-n\" id=\"".$idPrenotazione."data\" type=\"text\" onfocus=\"makeDate('".$idPrenotazione."data')\" onblur=\"returnText('".$idPrenotazione."data')\" name=\"".$idPrenotazione."[]\"></td>";
                $prenotazioni .= "<td data-title='Orario: '><input placeholder=\"".$oraPrenotazione."\" class=\"textbox-n\" id=\"".$idPrenotazione."ora\" type=\"text\" onfocus=\"makeTime('".$idPrenotazione."ora')\" onblur=\"returnText('".$idPrenotazione."ora')\" name=\"".$idPrenotazione."[]\"></td>";
            }
            $prenotazioni .= "<td data-title='Richiesta: '>".$prenotazione['Trattamento']."</td>";
            $prenotazioni .= "<td data-title='Stato: '>";
            switch ($prenotazione['Stato']){
                case 'A':
                    $prenotazioni .= "Accettata";
                    break;
                case 'R':
                    $prenotazioni .= "Rifiutata";
                    break;
                default:
                    $prenotazioni .= "<select id=\"stato\" name=\"".$idPrenotazione."[]\">";
                    $prenotazioni .= "<option value=\"\" disabled selected>Da confermare</option>";
                    $prenotazioni .= "<option value=\"A\">Accetta prenotazione</option>";
                    $prenotazioni .= "<option value=\"R\">Rifiuta prenotazione</option>";
                    $prenotazioni .= "</select>";
                    $idPrenotazione = $prenotazione['Username'] ." ". $dataPrenotazione ." ". $oraPrenotazione;
                    $prenotazioniDaVerificare[$numPrenotazioniDaVerificare] = $idPrenotazione;
                    $numPrenotazioniDaVerificare++;
            }
            $prenotazioni .= "</td>";
            $prenotazioni .= "</tr>";
        }
    }
    else{
        $prenotazioni .= "<tr><td>Non ci sono prenotazioni da visualizzare.</td></tr>";
    }
} 
else {
    $clienti = "<p>Non è possibile caricare la lista dei clienti.</p>";
    $prenotazioni = "<tr><td>Non è possibile caricare la lista delle prenotazioni.</td></tr>";
}

if(isset($_POST['submit'])){
    $errPrenotazione = "";
    $canMakeRes = true;
    $cliente=pulisciInput($_POST['customers']);
    $data=pulisciInput($_POST['date']);
    if (!preg_match("/\d{4}-\d{1,2}-\d{1,2}/",$data)){
        $errPrenotazione.='<p class=\'errore\'>Data per la prenotazione non valida: formato non valido!</p>';
        $canMakeRes = false;
    }
    $ora=pulisciInput($_POST['hour']);
    if (!preg_match("/\d{2}:\d{2}/",$ora)){
        $errPrenotazione.='<p class=\'errore\'>Ora per la prenotazione non valida: formato non valido!</p>';
        $canMakeRes = false;
    }
    if($canMakeRes && ($ora<"09:00" || $ora >"19:00")){
        $errPrenotazione.='<p class=\'errore\'>Orario non valido: il centro é chiuso nell\'orario richiesto!</p>';
        $canMakeRes = false;
    }
    $trattamento=pulisciInput($_POST['service']);
    

    /*Inserisco i dati nel DB, se non ci sono errori*/
    if($canMakeRes){
        $queryOk=$connessione->insertNewReservation($cliente,$data,$ora,$trattamento);
        if($queryOk){//prenotazione inserita
            $esitoInserimento="<div class=\"conferma\"><p>Inserimento avvenuto con successo!</p></div>";
        }
        else{//prenotazione non inserita: cliente ha una prenotazione per ora e data scelti!
            $esitoInserimento="<div class=\"errore\"><p>Impossibile inserire la prenotazione, esiste giá una prenotazione per il cliente all'orario selezionato!</p></div>";
        }
    }
    else{
        $esitoInserimento=$errPrenotazione;
    }
    //aggiorno nuovamente le prenotazioni
    $prenotazioni = "";
    $query_result = $connessione->getPrenotazioni();
    if($query_result != null){
        $dataOggi = new DateTime(date("Y-m-d"));
        foreach($query_result as $prenotazione){
            $prenotazioni.="<tr>";
            list($dataPrenotazione,$oraPrenotazione)=explode(" ",$prenotazione['DataOra']);
            $idPrenotazione = $prenotazione['Username'] . $dataPrenotazione . $oraPrenotazione;
            $prenotazioni .= "<td data-title='' class='header'>".$prenotazione['Nome']." ".$prenotazione['Cognome']."</td>";
            $eta = $dataOggi->diff(new DateTime($prenotazione['DataNascita']));
            $prenotazioni .= "<td data-title='Età: '>".$eta->y."</td>";
            switch ($prenotazione['Stato']){
                case 'A':
                    $prenotazioni .= "<td data-title='Data: '>".$dataPrenotazione."</td>";
                    $prenotazioni .= "<td data-title='Orario: '>".$oraPrenotazione."</td>";
                    break;
                case 'R':
                    $prenotazioni .= "<td data-title='Data: '>".$dataPrenotazione."</td>";
                    $prenotazioni .= "<td data-title='Orario: '>".$oraPrenotazione."</td>";
                    break;
                default:
                $prenotazioni .= "<td td data-title='Data: ' class='header'><input placeholder=\"".$dataPrenotazione."\" class=\"textbox-n\" id=\"".$idPrenotazione."data\" type=\"text\" onfocus=\"makeDate('".$idPrenotazione."data')\" onblur=\"returnText('".$idPrenotazione."data')\" name=\"".$idPrenotazione."[]\"></td>";
                $prenotazioni .= "<td data-title='Orario: '><input placeholder=\"".$oraPrenotazione."\" class=\"textbox-n\" id=\"".$idPrenotazione."ora\" type=\"text\" onfocus=\"makeTime('".$idPrenotazione."ora')\" onblur=\"returnText('".$idPrenotazione."ora')\" name=\"".$idPrenotazione."[]\"></td>";
            }
            $prenotazioni .= "<td data-title='Richiesta: '>".$prenotazione['Trattamento']."</td>";
            $prenotazioni .= "<td data-title='Stato: '>";
            
            switch ($prenotazione['Stato']){
                case 'A':
                    $prenotazioni .= "Accettata";
                    break;
                case 'R':
                    $prenotazioni .= "Rifiutata";
                    break;
                default:
                    $prenotazioni .= "<select id=\"stato\" name=\"".$idPrenotazione."[]\">";
                    $prenotazioni .= "<option value=\"\" disabled selected>Da confermare</option>";
                    $prenotazioni .= "<option value=\"A\">Accetta prenotazione</option>";
                    $prenotazioni .= "<option value=\"R\">Rifiuta prenotazione</option>";
                    $prenotazioni .= "</select>";
                    $idPrenotazione = $prenotazione['Username'] ." ". $dataPrenotazione ." ". $oraPrenotazione;
                    $prenotazioniDaVerificare[$numPrenotazioniDaVerificare] = $idPrenotazione;
                    $numPrenotazioniDaVerificare++;
            }
            $prenotazioni .= "</td>";
            $prenotazioni .= "</tr>";
        }
    }
    else{
        $prenotazioni .= "<tr><td>Non ci sono prenotazioni da visualizzare.</td></tr>";
    }
    
}

if(isset($_POST['modificaPrenotazioni'])){
    /* Qua elaborazione dati */
    $prenotazioniVerificate = 0;
    $aggiornate = 0;
    $errModficaPren = "";
    while ($prenotazioniVerificate < $numPrenotazioniDaVerificare){
        $toSkip = false;
        $idPrenotazione = $prenotazioniDaVerificare[$prenotazioniVerificate];
        list($utente,$dataP,$oraP)=explode(" ",$idPrenotazione);
        $idPrenotazione = $utente . $dataP . $oraP;
        $prenotazioneModificata = $_POST[$idPrenotazione];
        $modifiche = "";
        foreach($prenotazioneModificata as $key => $value){
            $modifiche .= $value."?";
        }
        list($nuovaData, $nuovoOrario, $nuovoStato) = explode("?", $modifiche);
        if ($nuovoStato == '') {
            $errModficaPren .= '<p>Stato non valido: é necessario accettare o rifiutare la prenotazione!</p>';
            $toSkip = true;
        }
        if($nuovaData==""){
            $nuovaData = $dataP;
        }
        if (!$toSkip && !preg_match("/\d{4}-\d{1,2}-\d{1,2}/",$nuovaData)){
            $errModficaPren.='<p>Data per la prenotazione non valida: formato non valido!</p>';
            $toSkip = true;
        }
        if($nuovoOrario==""){
            $nuovoOrario = $oraP;
        }
        if (!$toSkip && !preg_match("/\d{2}:\d{2}/",$nuovoOrario)){
            $errModficaPren.='<p>Ora per la prenotazione non valida: formato non valido!</p>';
            $toSkip = true;
        }
        if(!$toSkip && ($nuovoOrario<"09:00" || $nuovoOrario >"19:00")){
            $errModficaPren.='<p>Orario non valido: il centro é chiuso nell\'orario inserito!</p>';
            $toSkip = true;
        }
        if(!$toSkip){
            $query_result = $connessione->modificaPrenotazione($nuovaData,$nuovoOrario,$nuovoStato,$utente, $dataP, $oraP);
            if($query_result){
                $aggiornate++;
            }
        }
        $prenotazioniVerificate++;
    }
    if($aggiornate == $numPrenotazioniDaVerificare){
        $esitoModifica="<div id=\"conferma\"><p>Prenotazioni aggiornate con successo: ".$aggiornate." su ".$numPrenotazioniDaVerificare.".</p></div>";
    }
    else{
        $esitoModifica="<div id=\"confermaModifica\"><p>Prenotazioni aggiornate con successo: ".$aggiornate." su ".$numPrenotazioniDaVerificare."</p>";
        if($errModficaPren){
            $esitoModifica .= $errModficaPren;
        }
        $esitoModifica .="</div>";
    }
    
    //aggiorno nuovamente le prenotazioni
    $prenotazioni = "";
    $query_result = $connessione->getPrenotazioni();
    if($query_result != null){
        $dataOggi = new DateTime(date("Y-m-d"));
        foreach($query_result as $prenotazione){
            $prenotazioni.="<tr>";
            list($dataPrenotazione,$oraPrenotazione)=explode(" ",$prenotazione['DataOra']);
            $idPrenotazione = $prenotazione['Username'] . $dataPrenotazione . $oraPrenotazione;
            $prenotazioni .= "<td data-title='' class='header'>".$prenotazione['Nome']." ".$prenotazione['Cognome']."</td>";
            $eta = $dataOggi->diff(new DateTime($prenotazione['DataNascita']));
            $prenotazioni .= "<td data-title='Età: '>".$eta->y."</td>";
            switch ($prenotazione['Stato']){
                case 'A':
                    $prenotazioni .= "<td data-title='Data: '>".$dataPrenotazione."</td>";
                    $prenotazioni .= "<td data-title='Orario: '>".$oraPrenotazione."</td>";
                    break;
                case 'R':
                    $prenotazioni .= "<td data-title='Data: '>".$dataPrenotazione."</td>";
                    $prenotazioni .= "<td data-title='Orario: '>".$oraPrenotazione."</td>";
                    break;
                default:
                $prenotazioni .= "<td data-title='Data: ' class='header'><input placeholder=\"".$dataPrenotazione."\" class=\"textbox-n\" id=\"".$idPrenotazione."data\" type=\"text\" onfocus=\"makeDate('".$idPrenotazione."data')\" onblur=\"returnText('".$idPrenotazione."data')\" name=\"".$idPrenotazione."[]\"></td>";
                $prenotazioni .= "<td data-title='Orario: '><input placeholder=\"".$oraPrenotazione."\" class=\"textbox-n\" id=\"".$idPrenotazione."ora\" type=\"text\" onfocus=\"makeTime('".$idPrenotazione."ora')\" onblur=\"returnText('".$idPrenotazione."ora')\" name=\"".$idPrenotazione."[]\"></td>";
            }
            $prenotazioni .= "<td data-title='Richiesta: '>".$prenotazione['Trattamento']."</td>";
            $prenotazioni .= "<td data-title='Stato: '>";
            switch ($prenotazione['Stato']){
                case 'A':
                    $prenotazioni .= "Accettata";
                    break;
                case 'R':
                    $prenotazioni .= "Rifiutata";
                    break;
                default:
                    $prenotazioni .= "<select id=\"stato\" name=\"".$idPrenotazione."[]\">";
                    $prenotazioni .= "<option value=\"\" disabled selected>Da confermare</option>";
                    $prenotazioni .= "<option value=\"A\">Accetta prenotazione</option>";
                    $prenotazioni .= "<option value=\"R\">Rifiuta prenotazione</option>";
                    $prenotazioni .= "</select>";
                    $idPrenotazione = $prenotazione['Username'] ." ". $dataPrenotazione ." ". $oraPrenotazione;
                    $prenotazioniDaVerificare[$numPrenotazioniDaVerificare] = $idPrenotazione;
                    $numPrenotazioniDaVerificare++;
            }
            $prenotazioni .= "</td>";
            $prenotazioni .= "</tr>";
        }
    }
    else{
        $prenotazioni .= "<tr><td>Non ci sono prenotazioni da visualizzare.</td></tr>";
    }
}
$pagina_HTML=str_replace("<elencoClienti />", $clienti, $pagina_HTML);
$pagina_HTML=str_replace("<esitoForm />", $esitoInserimento, $pagina_HTML);
$pagina_HTML=str_replace("<prenotazioniEsistenti />", $prenotazioni, $pagina_HTML);
$pagina_HTML=str_replace("<esitoModifiche />", $esitoModifica, $pagina_HTML);
echo $pagina_HTML;
?>