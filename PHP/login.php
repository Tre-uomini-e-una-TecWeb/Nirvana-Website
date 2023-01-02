<?php
function pulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value);
    $value=htmlentities($value);
    return $value;
}
//ini_set('display_errors', 1);
require_once "connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../HTML/AREA PERSONALE/login.html");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$clienti="";
$esitoInserimento="";
$prenotazioni = "";
$prenotazioniDaVerificare = [];
$numPrenotazioniDaVerificare = 0;
$esitoModifica = "";
if($connOk){
}

if(isset($_POST['submit'])){
    
}
echo $pagina_HTML;
?>