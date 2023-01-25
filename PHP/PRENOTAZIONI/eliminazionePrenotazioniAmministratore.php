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

function showReservations($connessione, $prenotazioni){
    $query_result = $connessione->getPrenotazioni();
    if($query_result != null){
        $dataOggi = new DateTime(date("Y-m-d"));
        $i=0;
        foreach($query_result as $prenotazione){
            $i++;
            $prenotazioni.="<tr>";
            list($dataPrenotazione,$oraPrenotazione)=explode(" ",$prenotazione['DataOra']);
            $idPrenotazione = $prenotazione['Username'].",". $dataPrenotazione.",".$oraPrenotazione;
            $prenotazioni .= "<td data-title='' class='header'>".$prenotazione['Nome']." ".$prenotazione['Cognome']."</td>";
            $eta = $dataOggi->diff(new DateTime($prenotazione['DataNascita']));
            $prenotazioni .= "<td data-title='Età: '>".$eta->y."</td>";
            $prenotazioni .= "<td data-title='Data: '>".$dataPrenotazione."</td>";
            $prenotazioni .= "<td data-title='Orario: '>".$oraPrenotazione."</td>";
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
                    $prenotazioni .= "In attesa";
            }
            $prenotazioni .= "</td>";
            $prenotazioni .= "<td> <input type='checkbox' id='myCheckbox[]' name='".$i."' value='".$idPrenotazione."'> </td>";
            $prenotazioni .= "</tr>";
        }
    }
    else{
        $prenotazioni = "<tr><td colspan='7'>Non ci sono prenotazioni da visualizzare.</td></tr>";
    }
    return $prenotazioni;
}


ini_set('display_errors', 1);
require_once "../connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../../HTML/PRENOTAZIONI/eliminazionePrenotazioniAmministratore.html");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$prenotazioni = "";
$esitoDelete = "";
if($connOk){
    $prenotazioni=showReservations($connessione, $prenotazioni);
}
else{
    $prenotazioni .= "<tr><td colspan='7'>Non é al momento possibile visualizzare le prenotazioni</td></tr>";
}

if(isset($_POST['deleteP'])){
    if (isset($_POST['myCheckbox']) && $_POST['myCheckbox'] == 'Yes'){
        $toDelete = $_POST['myCheckbox'];
        if(empty($toDelete))
        {
          $esitoDelete = "<p class='errore'>Non sono state selezionate prenotazioni da eliminare!</p>";
        }
        else
        {
          $i = count($toDelete);
          $deleted=0;
          for($j = 0; $j < $i; $j++)
          {
            list($username,$dataPrenotazione,$oraPrenotazione)=explode(",",$toDelete[$j]);
            $isDeleted = $connessione->deletePrenotazioni($username, $dataPrenotazione, $oraPrenotazione);
            if($isDeleted){
                $deleted++;
            }
          }
          $prenotazioni=showReservations($connessione, $prenotazioni);
          if ($i == $deleted) {
            $esitoDelete = "<p class='conferma'>Le prenotazioni sono state eliminate correttamente!</p>";
          } else {
            $esitoDelete = "<p class='errore'>Alcune prenotazioni non sono state eliminate!</p>";
          }

        }
    } else {
        $b="";
        foreach ($_POST as $key => $value) {
            
            $b .= $key."=".$value.";";
        }
        $esitoDelete = "<p class='errore'>Non è stata selezionata alcuna prenotazione! ".$b." </p>";
    }
}

$pagina_HTML=str_replace("<listaPrenotazioni />", $prenotazioni, $pagina_HTML);
$pagina_HTML=str_replace("<esitoDelete />", $esitoDelete, $pagina_HTML);
echo $pagina_HTML;
?>