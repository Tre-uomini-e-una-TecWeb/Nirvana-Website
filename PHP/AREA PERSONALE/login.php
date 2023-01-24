<?php
session_start();
if(array_key_exists("username", $_SESSION) && $_SESSION["username"] != ""){//l'utente ha effettuato l'autenticazione
    header("Location: ../../HTML/AREA PERSONALE/areaPersonale.html");
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
$pagina_HTML=file_get_contents("../../HTML/AREA PERSONALE/login.html");
$connessione=new DBAccess();
$connOk=$connessione->openDBConnection();
$esitoRegistrazione = "";
$avvisoLogin = "";

if(isset($_POST['accesso'])){
    $username = pulisciInput($_POST['Username']);
    $password = pulisciInput($_POST['Password']);
    $pwd = password_hash($password,PASSWORD_DEFAULT);
    $query_result = $connessione->checkUtente($username);
    if($query_result != null){
        foreach($query_result as $utente){
            if(password_verify($password,$utente['Password'])){
                $_SESSION["username"] = $username;
                if($utente['Privilegi']==0){
                    $_SESSION["privilegi"] = "cliente";
                }
                else{
                    $_SESSION["privilegi"] = "admin";
                }
                header("Location: ../../PHP/AREA PERSONALE/areaPersonale.php");
                die();
            }
            else{
                $avvisoLogin = "<p id=\"loginKo\">Credenziali errate: login non effettuato.</p>";
            }
        }
    }
    else{
        $avvisoLogin = "<p id=\"loginKo\">Credenziali errate: login non effettuato.</p>";
    }
}
if(isset($_POST['registrazione'])){
    $errRegistrazione = "";
    $canInsert = true;
    $nome = pulisciInput($_POST['name']);
    if (preg_match("/\d/",$nome)){
        $errRegistrazione.='<li>Nome non valido: non possono esserci numeri!</li>';
        $canInsert = false;
    }
    $cognome = pulisciInput($_POST['surname']);
    if (preg_match("/\d/",$cognome)){
        $errRegistrazione.='<li>Cognome non valido: non possono esserci numeri!</li>';
        $canInsert = false;
    }
    $dataNascita = pulisciInput($_POST['birth']);
    if (!preg_match("/\d{4}-\d{1,2}-\d{1,2}/",$dataNascita)){
        $errRegistrazione.='<li>Data di nascita non valida: formato non valido!</li>';
        $canInsert = false;
    }
    $email = pulisciInput($_POST['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errRegistrazione .= "<li>Email non valida: formato non corretto!</li>";
        $canInsert = false;
    }
    $telefono = pulisciInput($_POST['phone']);
    if (preg_match("/\D/",$telefono)){
        $errRegistrazione.='<li>Numero di telefono non valido: possono esserci solo numeri!</li>';
        $canInsert = false;
    }
    $username = pulisciInput($_POST['username']);
    $utenteInDB = $connessione->checkUtente($username);
    if($utenteInDB != null){
        $utenteInDB=true;
    }
    $password = pulisciInput($_POST['password']);
    $pwd = password_hash($password,PASSWORD_DEFAULT);
    $privilegi = 0;
    if($canInsert && $utenteInDB == false){
        $query_result = $connessione->insertUtente($username,$nome,$cognome,$dataNascita,$email,$telefono,$pwd,$privilegi);
        if($query_result){
            $esitoRegistrazione = "<p id=\"registrazioneOk\">Registrazione andata a buon fine!</p>";
            $esitoRegistrazione .= "<p>Effettua il login per accedere alla tua area personale.</p>";
        }
        else{
            $esitoRegistrazione = "<p id=\"registrazioneKo\">Impossibile effettuare la registrazione. Controlla i dati inseriti e riprova.</p>";
        }
    }
    else{
        if(!$canInsert){
            $esitoRegistrazione = "<p id=\"registrazioneKo\">Registrazione non andata a buon fine per questi motivi:".$errRegistrazione."</p>";
        }
        else{
            $esitoRegistrazione = "<p id=\"registrazioneKo\">É giá presente un utente con i dati inseriti. Riprovare.</p>";
        }
    }
}
$pagina_HTML=str_replace("<avvisoLogin />", $avvisoLogin, $pagina_HTML);
$pagina_HTML=str_replace("<esitoRegistrazione />", $esitoRegistrazione, $pagina_HTML);
echo $pagina_HTML;
?>