<?php
namespace DB;
class DBAccess{
    private const HOST_DB="127.0.0.1";
    private const DATABASE_NAME="Nirvana";
    private const USERNAME="root";
    private const PASSWORD="";
    private $connection;
    public function openDBConnection(){
        mysqli_report(MYSQLI_REPORT_ERROR);
        $this->connection=mysqli_connect(self::HOST_DB,self::USERNAME,self::PASSWORD,self::DATABASE_NAME);
        if(mysqli_connect_errno()){
            return false;
        }
        else{
            return true;
        }
    }
    public function getUtenti(){
        $query="SELECT * FROM Utenti WHERE Privilegi=false ORDER BY Username ASC";
        $query_result=mysqli_query($this->connection,$query) or die("Errore in openDBConnection: ".mysqli_error($this->connection));
        if(mysqli_num_rows($query_result)==0){
            return null;
        }
        else{
            $result=array();
            while($row=mysqli_fetch_assoc($query_result)){
                array_push($result,$row);
            }
            $query_result->free();
            return $result;
        }
    }

    public function checkUtente($user){
        $user = mysqli_real_escape_string($this->connection, $user);
        $query="SELECT * FROM Utenti WHERE Privilegi=false AND Username='".$user."' ORDER BY Username ASC";
        $query_result=mysqli_query($this->connection,$query) or die("Errore in openDBConnection: ".mysqli_error($this->connection));
        if(mysqli_num_rows($query_result)==0){
            return null;
        }
        else{
            $result=array();
            while($row=mysqli_fetch_assoc($query_result)){
                array_push($result,$row);
            }
            $query_result->free();
            return $result;
        }
    }

    public function insertNewReservation($cliente,$data,$ora,$trattamento){
        list($year, $month, $day) = explode("-", $data);
        list($hour, $min) = explode(":", $ora);
        $dataOraStringa=$year."-".$month."-".$day." ";
        $dataOraStringa.=$hour.":".$min;
        //$dataora = date_create_from_format("Y-m-d H:i", $dataOraStringa);
        $query="INSERT INTO Prenotazioni (Utente, DataOra, Trattamento, Stato) VALUES ('$cliente','$dataOraStringa','$trattamento','A')";
        $query_result=mysqli_query($this->connection,$query);
        if($query_result){
            return true;
        }
        else{
            return false;
        }
    }

    public function modificaPrenotazione($nD,$nO,$nS,$user,$vD,$vO){
        if($nS!=""){
            if($nD=="" || $nS=="R"){//non é stata modificata la data della prenotazione
                $nD = $vD;
            }
            if($nO==""||$nS=="R"){//non é stata modificata l'ora della prenotazione
                $nO = $vO;
            }
            list($year, $month, $day) = explode("-", $nD);
            list($hour, $min) = explode(":", $nO);
            $daInserire=$year."-".$month."-".$day." ";
            $daInserire.=$hour.":".$min;
            list($year, $month, $day) = explode("-", $vD);
            list($hour, $min) = explode(":", $vO);
            $giaEsistente=$year."-".$month."-".$day." ";
            $giaEsistente.=$hour.":".$min;
            if($daInserire != $giaEsistente){
                $query="UPDATE `Prenotazioni` SET `DataOra` = '".$daInserire."', `Stato` = '".$nS."' WHERE `Prenotazioni`.`Utente` = '".$user."' AND `Prenotazioni`.`DataOra` = '".$giaEsistente."'";
            }
            else{
                $query="UPDATE `Prenotazioni` SET `Stato` = '".$nS."' WHERE `Prenotazioni`.`Utente` = '".$user."' AND `Prenotazioni`.`DataOra` = '".$giaEsistente."'";
            }
            $query_result=mysqli_query($this->connection,$query);
            if($query_result){
                return true;
            }
            else{
                return false;
            }
        }
        else{//stato non modificato, non é possibile fare l'update
            return false;
        }
        
    }

    public function getPrenotazioni(){
        $dataOggi = date("Y-m-d");
        $query="SELECT * FROM Prenotazioni JOIN Utenti ON Prenotazioni.Utente=Utenti.Username WHERE DataOra>'$dataOggi' ORDER BY DataOra ASC";
        $query_result=mysqli_query($this->connection,$query) or die("Errore in openDBConnection: ".mysqli_error($this->connection));
        if(mysqli_num_rows($query_result)==0){
            return null;
        }
        else{
            $result=array();
            while($row=mysqli_fetch_assoc($query_result)){
                array_push($result,$row);
            }
            $query_result->free();
            return $result;
        }
    }

    public function getPrenotazioniDaConfermare(){
        $dataOggi = date("Y-m-d");
        $query="SELECT * FROM Prenotazioni JOIN Utenti ON Prenotazioni.Utente=Utenti.Username WHERE DataOra>'$dataOggi' AND Stato='P' ORDER BY DataOra ASC";
        $query_result=mysqli_query($this->connection,$query) or die("Errore in openDBConnection: ".mysqli_error($this->connection));
        if(mysqli_num_rows($query_result)==0){
            return null;
        }
        else{
            $result=array();
            while($row=mysqli_fetch_assoc($query_result)){
                array_push($result,$row);
            }
            $query_result->free();
            return $result;
        }
    }

    public function insertUtente($user,$name,$surname,$birth,$email,$tel,$passwd,$isAdmin){
        $query="INSERT INTO `Utenti` (`Username`, `Nome`, `Cognome`, `DataNascita`, `Email`, `Telefono`, `Password`, `Privilegi`) VALUES ('".$user."', '".$name."', '".$surname."', '".$birth."', '".$email."', '".$tel."', '".$passwd."', '".$isAdmin."')";
        $query_result=mysqli_query($this->connection,$query);
        if($query_result){
            return true;
        }
        else{
            return false;
        }
    }

    public function closeConnection(){
        mysqli_close($this->connection);
    }
}
?>