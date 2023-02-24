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
function caricaMessaggi($connection,$validConnection){
    $msg="";
    if($validConnection){
        $queryResult=$connection->getMessaggi();
        if($queryResult != null){
            foreach($queryResult as $messaggio){
                $msg.="<tr>";
                $msg.="<td>".$messaggio['Nome']."<td data-title='Email:'><a href=\"mailto:".$messaggio['Email']."\">".$messaggio['Email']."</a></td><td>".$messaggio['Messaggio']."</td>"."<td data-title='Eliminare:'><input type='checkbox' id='myCheckbox[]' name='".$messaggio['Id']."' value='".$messaggio['Id']."' ><label class=\"hide print-invisible\" for='myCheckbox[]'>Seleziona la prenotazione ".$messaggio['Id']."</label></td>";
                $msg.="</tr>";
            }
        }
        else{
            $msg="<td colspan=4>Nessun messaggio presente.</td>";
        }
    }
    else{
        $msg="<td colspan=4>Non é stato possibile recuperare i messaggi.</td>";
    }
    return $msg;
}
require_once "../connessione.php";
use DB\DBAccess;
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$pagina_HTML=file_get_contents("../../HTML/CONSULENZE/gestioneConsulenzeAmministratore.html");
$messaggi="";
$messaggi=caricaMessaggi($connessione,$connOk);
if(isset($_POST['submit'])){
}
$pagina_HTML=str_replace("<listaConsulenze />",$messaggi,$pagina_HTML);
echo $pagina_HTML;
?>