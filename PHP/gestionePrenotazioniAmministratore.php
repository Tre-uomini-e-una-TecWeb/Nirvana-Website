<?php
session_start();
if($_SESSION["privilegi"]!="admin"){
    header("Location: 403.php");
    die();
}
function pulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value);
    $value=htmlentities($value);
    return $value;
}
//ini_set('display_errors', 1);
require_once "connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../HTML/PRENOTAZIONI/gestionePrenotazioniAmministratore.html");
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
    /*else{
        $clienti="<p>Non ci sono clienti iscritti</p>";
    }*/
    $clienti.="</select>";
    $query_result = $connessione->getPrenotazioni();
    if($query_result != null){
        $dataOggi = new DateTime(date("Y-m-d"));
        foreach($query_result as $prenotazione){
            $prenotazioni.="<tr>";
            $prenotazioni .= "<td scope=\"row\">".$prenotazione['Nome']."</td>";
            $prenotazioni .= "<td scope=\"row\">".$prenotazione['Cognome']."</td>";
            $eta = $dataOggi->diff(new DateTime($prenotazione['DataNascita']));
            $prenotazioni .= "<td scope=\"row\">".$eta->y."</td>";
            list($dataPrenotazione,$oraPrenotazione)=explode(" ",$prenotazione['DataOra']);
            $idPrenotazione = $prenotazione['Username'] . $dataPrenotazione . $oraPrenotazione;
            switch ($prenotazione['Stato']){
                case 'A':
                    $prenotazioni .= "<td scope=\"row\">".$dataPrenotazione."</td>";
                    $prenotazioni .= "<td scope=\"row\">".$oraPrenotazione."</td>";
                    break;
                case 'R':
                    $prenotazioni .= "<td scope=\"row\">".$dataPrenotazione."</td>";
                    $prenotazioni .= "<td scope=\"row\">".$oraPrenotazione."</td>";
                    break;
                default:
                $prenotazioni .= "<td scope=\"row\"><input placeholder=\"".$dataPrenotazione."\" class=\"textbox-n\" type=\"text\" onfocus=\"(this.type='date')\" name=\"".$idPrenotazione."[]\"></td>";
                $prenotazioni .= "<td scope=\"row\"><input placeholder=\"".$oraPrenotazione."\" class=\"textbox-n\" type=\"text\" onfocus=\"(this.type='time')\" name=\"".$idPrenotazione."[]\"></td>";
            }
            $prenotazioni .= "<td scope=\"row\">".$prenotazione['Trattamento']."</td>";
            $prenotazioni .= "<td scope=\"row\">";
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
        $prenotazioni .= "<tr><td scope=\"row\">Non ci sono prenotazioni da visualizzare.</td></tr>";
    }
} 
else {
    $clienti = "<p>Non è possbile caricare la lista dei clienti.</p>";
    $prenotazioni = "<tr><td scope=\"row\">Non è possbile caricare la lista delle prenotazioni.</td></tr>";
}

if(isset($_POST['submit'])){
    $cliente=pulisciInput($_POST['customers']);
    $data=pulisciInput($_POST['date']);
    $ora=pulisciInput($_POST['hour']);
    $trattamento=pulisciInput($_POST['service']);
    
    /*Inserisco i dati nel DB, se non ci sono errori*/
    if($connOk){
        $queryOk=$connessione->insertNewReservation($cliente,$data,$ora,$trattamento);
        if($queryOk){//prenotazione inserita
            $esitoInserimento="<div id=\"confermaInserimento\"><p>Inserimento avvenuto con successo!</p></div>";
        }
        else{//prenotazione non inserita: cliente ha una prenotazione per ora e data scelti!
            $esitoInserimento="<div id=\"erroreInserimento\"><p>Impossibile inserire la prenotazione, esiste giá una prenotazione per il cliente all'orario selezionato!</p></div>";
        }
    }
    else{
        $esitoInserimento="<div id=\"erroreInserimento\"><p>I nostri sistemi sono al momento non funzionanti, ci scusiamo per il disagio.</p></div>";
    }
    //aggiorno nuovamente le prenotazioni
    $prenotazioni = "";
    $query_result = $connessione->getPrenotazioni();
    if($query_result != null){
        $dataOggi = new DateTime(date("Y-m-d"));
        foreach($query_result as $prenotazione){
            $prenotazioni.="<tr>";
            $prenotazioni .= "<td scope=\"row\">".$prenotazione['Nome']."</td>";
            $prenotazioni .= "<td scope=\"row\">".$prenotazione['Cognome']."</td>";
            $eta = $dataOggi->diff(new DateTime($prenotazione['DataNascita']));
            $prenotazioni .= "<td scope=\"row\">".$eta->y."</td>";
            list($dataPrenotazione,$oraPrenotazione)=explode(" ",$prenotazione['DataOra']);
            $idPrenotazione = $prenotazione['Username'] . $dataPrenotazione . $oraPrenotazione;
            switch ($prenotazione['Stato']){
                case 'A':
                    $prenotazioni .= "<td scope=\"row\">".$dataPrenotazione."</td>";
                    $prenotazioni .= "<td scope=\"row\">".$oraPrenotazione."</td>";
                    break;
                case 'R':
                    $prenotazioni .= "<td scope=\"row\">".$dataPrenotazione."</td>";
                    $prenotazioni .= "<td scope=\"row\">".$oraPrenotazione."</td>";
                    break;
                default:
                $prenotazioni .= "<td scope=\"row\"><input placeholder=\"".$dataPrenotazione."\" class=\"textbox-n\" type=\"text\" onfocus=\"(this.type='date')\" name=\"".$idPrenotazione."[]\"></td>";
                $prenotazioni .= "<td scope=\"row\"><input placeholder=\"".$oraPrenotazione."\" class=\"textbox-n\" type=\"text\" onfocus=\"(this.type='time')\" name=\"".$idPrenotazione."[]\"></td>";
            }
            $prenotazioni .= "<td scope=\"row\">".$prenotazione['Trattamento']."</td>";
            $prenotazioni .= "<td scope=\"row\">";
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
        $prenotazioni .= "<tr><td scope=\"row\">Non ci sono prenotazioni da visualizzare.</td></tr>";
    }
    
}

if(isset($_POST['modificaPrenotazioni'])){
    /* Qua elaborazione dati */
    $prenotazioniVerificate = 0;
    $aggiornate = 0;
    $errori = false;
    while ($prenotazioniVerificate < $numPrenotazioniDaVerificare){
        $idPrenotazione = $prenotazioniDaVerificare[$prenotazioniVerificate];
        list($utente,$dataP,$oraP)=explode(" ",$idPrenotazione);
        $idPrenotazione = $utente . $dataP . $oraP;
        $prenotazioneModificata = $_POST[$idPrenotazione];
        $modifiche = "";
        foreach($prenotazioneModificata as $key => $value){
            $modifiche .= $value."?";
        }
        list($nuovaData, $nuovoOrario, $nuovoStato) = explode("?", $modifiche);
        $query_result = $connessione->modificaPrenotazione($nuovaData,$nuovoOrario,$nuovoStato,$utente, $dataP, $oraP);
        if($query_result){
            $aggiornate++;
        }
        $prenotazioniVerificate++;
    }
    if($aggiornate == $numPrenotazioniDaVerificare){
        $esitoModifica="<div id=\"confermaModifica\"><p>Prenotazioni aggiornate con successo: ".$aggiornate." su ".$numPrenotazioniDaVerificare.".</p></div>";
    }
    else{
        $esitoModifica="<div><p>Prenotazioni aggiornate con successo: ".$aggiornate." su ".$numPrenotazioniDaVerificare.".</p><p>É necessario scegliere uno stato per le prenotazioni da confermare.</p></div>";
    }
    
    //aggiorno nuovamente le prenotazioni
    $prenotazioni = "";
    $query_result = $connessione->getPrenotazioni();
    if($query_result != null){
        $dataOggi = new DateTime(date("Y-m-d"));
        foreach($query_result as $prenotazione){
            $prenotazioni.="<tr>";
            $prenotazioni .= "<td scope=\"row\">".$prenotazione['Nome']."</td>";
            $prenotazioni .= "<td scope=\"row\">".$prenotazione['Cognome']."</td>";
            $eta = $dataOggi->diff(new DateTime($prenotazione['DataNascita']));
            $prenotazioni .= "<td scope=\"row\">".$eta->y."</td>";
            list($dataPrenotazione,$oraPrenotazione)=explode(" ",$prenotazione['DataOra']);
            $idPrenotazione = $prenotazione['Username'] . $dataPrenotazione . $oraPrenotazione;
            switch ($prenotazione['Stato']){
                case 'A':
                    $prenotazioni .= "<td scope=\"row\">".$dataPrenotazione."</td>";
                    $prenotazioni .= "<td scope=\"row\">".$oraPrenotazione."</td>";
                    break;
                case 'R':
                    $prenotazioni .= "<td scope=\"row\">".$dataPrenotazione."</td>";
                    $prenotazioni .= "<td scope=\"row\">".$oraPrenotazione."</td>";
                    break;
                default:
                $prenotazioni .= "<td scope=\"row\"><input placeholder=\"".$dataPrenotazione."\" class=\"textbox-n\" type=\"text\" onfocus=\"(this.type='date')\" name=\"".$idPrenotazione."[]\"></td>";
                $prenotazioni .= "<td scope=\"row\"><input placeholder=\"".$oraPrenotazione."\" class=\"textbox-n\" type=\"text\" onfocus=\"(this.type='time')\" name=\"".$idPrenotazione."[]\"></td>";
            }
            $prenotazioni .= "<td scope=\"row\">".$prenotazione['Trattamento']."</td>";
            $prenotazioni .= "<td scope=\"row\">";
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
        $prenotazioni .= "<tr><td scope=\"row\">Non ci sono prenotazioni da visualizzare.</td></tr>";
    }
}
$pagina_HTML = str_replace("<elencoClienti />", $clienti, $pagina_HTML);
$pagina_HTML=str_replace("<esitoForm />", $esitoInserimento, $pagina_HTML);
$pagina_HTML=str_replace("<prenotazioniEsistenti />", $prenotazioni, $pagina_HTML);
$pagina_HTML=str_replace("<esitoModifiche />", $esitoModifica, $pagina_HTML);
echo $pagina_HTML;
?>