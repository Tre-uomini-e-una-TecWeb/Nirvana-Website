<?php
session_start();
if(array_key_exists("username", $_SESSION)){//l'utente ha effettuato l'autenticazione
    header("Location: ../HTML/AREA PERSONALE/areaPersonale.html");
    die();
}
function pulisciInput($value){
    $value=trim($value);
    $value=strip_tags($value);
    $value=htmlentities($value);
    return $value;
}
require_once "connessione.php";
use DB\DBAccess;
$pagina_HTML=file_get_contents("../HTML/AREA PERSONALE/login.html");
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
                header("Location: ../HTML/AREA PERSONALE/areaPersonale.html");
                die();
            }
            else{
                $avvisoLogin = "<p id=\"loginKo\">Password non corretta: login non effettuato.</p>";
            }
        }
    }
    else{
        $avvisoLogin = "<p id=\"loginKo\">Utente non trovato: login non effettuato. Verificare di aver inserito lo <span lang=\"en\">username</span> correttamente.</p>";
    }
}
if(isset($_POST['registrazione'])){
    $nome = pulisciInput($_POST['name']);
    $cognome = pulisciInput($_POST['surname']);
    $dataNascita = pulisciInput($_POST['birth']);
    $email = pulisciInput($_POST['email']);
    $telefono = pulisciInput($_POST['phone']);
    $username = pulisciInput($_POST['username']);
    $utenteInDB = $connessione->checkUtente($username);
    if($utenteInDB != null){
        $esitoRegistrazione="<p id=\"registrazioneKo\">Username giá registrato da un altro cliente. Per favore scegline un altro.</p>";
        $utenteInDB=true;
    }
    $password = pulisciInput($_POST['password']);
    $pwd = password_hash($password,PASSWORD_DEFAULT);
    $privilegi = 0;
    if($utenteInDB == false){
        $query_result = $connessione->insertUtente($username,$nome,$cognome,$dataNascita,$email,$telefono,$pwd,$privilegi);
        if($query_result){
            $esitoRegistrazione = "<p id=\"registrazioneOk\">Registrazione andata a buon fine!</p>";
            $esitoRegistrazione .= "<p>Effettua il login per accedere alla tua area personale.</p>";
        }
        else{
            $esitoRegistrazione = "<p id=\"registrazioneKo\">Impossibile effettuare la registrazione. Controlla i dati inseriti e riprova.</p>";
        }
    }
}
$pagina_HTML=str_replace("<avvisoLogin />", $avvisoLogin, $pagina_HTML);
$pagina_HTML=str_replace("<esitoRegistrazione />", $esitoRegistrazione, $pagina_HTML);
echo $pagina_HTML;
?>