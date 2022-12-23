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
    public function insertNewReservation($cliente,$data,$ora,$trattamento){
        list($year, $month, $day) = explode("-", $data);
        list($hour, $min) = explode(":", $ora);
        $dataOraStringa=$year."-".$month."-".$day." ";
        $dataOraStringa.=$hour.":".$min;
        $dataora = date_create_from_format("Y-m-d H:i", $dataOraStringa);
        $query="INSERT INTO 'Prenotazioni' ('Utente', 'DataOra', 'Trattamento', 'Stato') VALUES (\'$cliente\',\'$dataora\',\'$trattamento\',\'A\')";
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
    public function closeConnection(){
        mysqli_close($this->connection);
    }
}
?>