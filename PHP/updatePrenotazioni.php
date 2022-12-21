<?php
function pulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value);
    $value=htmlentities($value);
    return $value;
}
require_once "connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../HTML/PRENOTAZIONI/gestionePrenotazioniAmministratore.html");
$esitoInserimento="";

if(isset($_POST['submit'])){
    $messaggiPerForm="";
    $cliente=pulisciInput($_POST['customers']);
    $data=pulisciInput($_POST['date']);
    $ora=pulisciInput($_POST['hour']);
    $trattamento=pulisciInput($_POST['service']);
    
    /*Inserisco i dati nel DB, se non ci sono errori*/
    if($messaggiPerForm == ""){
        $connessione=new DBAccess;
        $connOk=$connessione->openDBConnection();
        if($connOk){
            $queryOk=$connessione->insertNewReservation($cliente,$data,$ora,$trattamento);
            if($queryOk){
                $messaggiPerForm="<div id=\"greetings\"><p>Inserimento avvenuto con successo!</p></div>";
            }
            else{
                $messaggiPerForm="<div id=\"messageErrors\"><p>Problemi nell\'inserimento dei dati, controlla se hai usato caratteri speciali</p></div>";
            }
        }
        else{
            $messaggiPerForm="<div id=\"messageErrors\"><p>I nostri sistemi sono al momento non funzionanti, ci scusiamo per il disagio</p></div>";
        }
    }
    else{
        $messaggiPerForm="<div id=\"messageErrors\"><ul>".$messaggiPerForm."</ul></div>";
    }
}
$pagina_HTML=str_replace("<esitoForm />", $messaggiPerForm, $pagina_HTML);
echo $pagina_HTML;
?>