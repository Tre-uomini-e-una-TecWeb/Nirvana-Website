<?php
session_start();
if(!array_key_exists("username", $_SESSION) || $_SESSION["username"] == ""){//l'utente deve effettuare l'autenticazione
    header("Location: ../AMMINISTRAZIONE/401.php");
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
$pagina_HTML=file_get_contents("../../HTML/AREA_PERSONALE/areaPersonale.html"); 
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$nome="";
$cognome="";
$dataNascita="";
$mail="";
$telefono="";
$modDati = "";
$modPasswd = "";
if($connOk){
    $query_result=$connessione->checkUtente($_SESSION["username"]);
    if($query_result != null){
        foreach($query_result as $infoUtente){
            $nome = $infoUtente['Nome'];
            $cognome = $infoUtente['Cognome'];
            $dataNascita = $infoUtente['DataNascita'];
            $mail = $infoUtente['Email'];
            $telefono = $infoUtente['Telefono'];
        }
    }
} 
else {
}

if(isset($_POST['modificaDati'])){
    $modNome = "";
    $modCognome = "";
    $modDataNascita = "";
    $modEmail = "";
    $modTelefono = "";
    //controllo se effettivamente é stato modificato un dato
    if($_POST['newPhone']!="" || $_POST['newEmail']!="" || $_POST['newName']!="" || $_POST['newSurname']!="" || $_POST['newBirth']!=""){
        $canUpdate = true;
        if($_POST['newName']!=""){
            $modNome = pulisciInput($_POST['newName']);
            if (preg_match("/\d/",$modNome)){
                $modDati.='<p class=\'errore\'>Nome non valido: non possono esserci numeri!</p>';
                $canUpdate = false;
            }
        }
        if($_POST['newSurname']!=""){
            $modCognome = pulisciInput($_POST['newSurname']);
            if (preg_match("/\d/",$modCognome)){
                $modDati.='<p class=\'errore\'>Cognome non valido: non possono esserci numeri!</p>';
                $canUpdate = false;
            }
        }
        if($_POST['newBirth']!=""){
            $modDataNascita = pulisciInput($_POST['newBirth']);
            if (!preg_match("/\d{4}-\d{1,2}-\d{1,2}/",$modDataNascita)){
                $modDati.='<p class=\'errore\'>Data di nascita non valida: formato non valido!</p>';
                $canUpdate = false;
            }
        }
        if($_POST['newEmail']!=""){
            $modEmail = pulisciInput($_POST['newEmail']);
            if (!filter_var($modEmail, FILTER_VALIDATE_EMAIL)) {
                $modDati .= "<p class='\errore'\><span lang='en'>Email</span> non valida: formato non corretto!</p>";
                $canUpdate = false;
            }
        }
        if($_POST['newPhone']!=""){
            $modTelefono = pulisciInput($_POST['newPhone']);
            if (preg_match("/\D/",$modTelefono)){
                $modDati.='<p class=\'errore\'>Numero di telefono non valido: possono esserci solo numeri!</p>';
                $canUpdate = false;
            }
        }
        if($canUpdate){
            $isOk=true;
            if($modNome!=""){
                $isOk = $connessione->updateNameUtente($_SESSION["username"], $modNome);
                if($isOk){
                    $modDati .= "<p class='conferma'>Nome aggiornato con successo!</p>";
                }
                else{
                    $modDati .= "<p class='errore'>Non é stato possibile aggiornare il nome.</p>";
                }
            }
            if($modCognome!=""){
                $isOk = $connessione->updateSurnameUtente($_SESSION["username"], $modCognome);
                if($isOk){
                    $modDati .= "<p class='conferma'>Cognome aggiornato con successo!</p>";
                }
                else{
                    $modDati .= "<p class='errore'>Non é stato possibile aggiornare il cognome.</p>";
                }
            }
            if($modDataNascita!=""){
                $isOk = $connessione->updateBirthUtente($_SESSION["username"], $modDataNascita);
                if($isOk){
                    $modDati .= "<p class='conferma'>Data di nascita aggiornata con successo!</p>";
                }
                else{
                    $modDati .= "<p class='errore'>Non é stato possibile aggiornare la data di nascita.</p>";
                }
            }
            if($modEmail!=""){
                $isOk = $connessione->updateEmailUtente($_SESSION["username"], $modEmail);
                if($isOk){
                    $modDati .= "<p class='conferma'><span lang='en'>Email</span> aggiornata con successo!</p>";
                }
                else{
                    $modDati .= "<p class='errore'>Non é stato possibile aggiornare l'email.</p>";
                }
            }  
            if($modTelefono!=""){
                $isOk = $connessione->updateTelUtente($_SESSION["username"], $modTelefono);
                if($isOk){
                    $modDati .= "<p class='conferma'>Numero di telefono aggiornato con successo!</p>";
                }
                else{
                    $modDati .= "<p class='errore'>Non é stato possibile aggiornare il numero di telefono.</p>";
                }
            }
            //mostro i nuovi dati
            $query_result=$connessione->checkUtente($_SESSION["username"]);
            if($query_result != null){
                foreach($query_result as $infoUtente){
                    $nome = $infoUtente['Nome'];
                    $cognome = $infoUtente['Cognome'];
                    $dataNascita = $infoUtente['DataNascita'];
                    $mail = $infoUtente['Email'];
                    $telefono = $infoUtente['Telefono'];
                }
            }
        }
    }
    else{
        $modDati = "<p class='errore'>Non é stato modificato alcun dato.</p>";
    }
}

if(isset($_POST['modificaPasswd'])){
    $newPasswd=pulisciInput($_POST['password']);
    $newPasswdConfirm=pulisciInput($_POST['confirmPassword']);
    $query_result = $connessione->checkUtente($_SESSION["username"]);
    $oldPw = "";
    if($query_result != null){
        foreach ($query_result as $utente) {
            $oldPw = $utente['Password'];
        }
    }
    if(strcmp($newPasswd,$newPasswdConfirm)==0){
        $password = password_hash($newPasswd,PASSWORD_DEFAULT);
        if(!password_verify($newPasswd,$oldPw)){
            $isUpdated = $connessione->updatePasswdUtente($_SESSION["username"], $password);
            if($isUpdated){
                $modPasswd = "<p class='conferma'>Password aggiornata con successo!</p>";
            }
            else{
                $modPasswd = "<p class='errore'>Non é stato possibile aggiornare la password</p>";
            }
        }
        else{
            $modPasswd = "<p class='errore'>La password é identica a quella giá in uso.</p>";
        }
    }
    else{
        $modPasswd = "<p class='errore'>Le password non coincidono.</p>";
    }
}

$pagina_HTML = str_replace("<usernameUtente />", $_SESSION["username"], $pagina_HTML);
$pagina_HTML = str_replace("<nomeUtente />", $nome, $pagina_HTML);
$pagina_HTML = str_replace("<cognomeUtente />", $cognome, $pagina_HTML);
$pagina_HTML = str_replace("<dataNascitaUtente />", $dataNascita, $pagina_HTML);
$pagina_HTML = str_replace("<mailUtente />", $mail, $pagina_HTML);
$pagina_HTML = str_replace("<telefonoUtente />", $telefono, $pagina_HTML);
$pagina_HTML = str_replace("<esitoModificaDati />", $modDati, $pagina_HTML);
$pagina_HTML = str_replace("<esitoCambioPassword />", $modPasswd, $pagina_HTML);
echo $pagina_HTML;
?>