<?php
function pulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value);
    $value=htmlentities($value);
    return $value;
}
require_once "../connessione.php";
use DB\DBAccess;
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$pagina_HTML=file_get_contents("../../HTML/CONSULENZE/consulenze.html");
$esitoMessaggio="";
if(isset($_POST['submit'])){
    if($connOk){
        $errMessaggio="";
        $canInsert=true;
        $name=pulisciInput($_POST['name']);
        if (preg_match("/\d/",$name)){
            $errMessaggio.='<p class=\'erroreConsulenza\'>Nome non valido: non possono esserci numeri!</p>';
            $canInsert = false;
        }
        if ((strlen($name)==0)){
            $errMessaggio.='<p class=\'erroreConsulenza\'>Nome non puó essere vuoto!</p>';
            $canInsert = false;
        }
        $email=pulisciInput($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errMessaggio .= "<p class=\"erroreConsulenza\">Email non valida: formato non corretto!</p>";
            $canInsert = false;
        }
        $message=pulisciInput($_POST['message']);
        if(strlen($message)<50){
            $errMessaggio .= "<p class=\"erroreConsulenza\">Messaggio non valido: sono stati inseriti meno di 50 caratteri!</p>";
            $canInsert = false;
        }
        if($canInsert){
            $query_result=$connessione->insertNewMessage($name,$email,$message);
            if($query_result){
                $esitoMessaggio = "<p class=\"confermaConsulenza\">Messaggio inviato con successo!</p>";
            }
            else{
                $esitoMessaggio = "<p class=\"erroreConsulenza\">Impossibile inviare il messaggio. Controlla i dati inseriti e riprova.</p>";
            }
        }
        else{
            $esitoMessaggio=$errMessaggio;
        }
    }
    else{
        header("Location: ../AMMINISTRAZIONE/500.php");
        die();
    }
}
$pagina_HTML=str_replace("<esitoMessaggio />",$esitoMessaggio,$pagina_HTML);
echo $pagina_HTML;
?>