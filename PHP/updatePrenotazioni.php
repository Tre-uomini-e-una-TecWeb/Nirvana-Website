<?php
require_once "connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../HTML/PRENOTAZIONI/gestionePrenotazioniAmministratore");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
if($connOK){
}
else{
    echo "<p>Error</p>"
}
echo $pagina_HTML;
?>