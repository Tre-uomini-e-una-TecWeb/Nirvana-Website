<?php
namespace DB;
class DBAccess{
    private const HOST_DB="127.0.0.1";
    private const DATABASE_NAME="Nirvana";
    private const USERNAME="";
    private const PASSWORD="";
    private $connection;
    public function openDBConnection(){
        mysqli_report(MSQLI_REPORT_ERROR);
        $this->connection=mysqli_connection(HOST_DB,USERNAME,PASSWORD,DATABASE_NAME);
        if(mysqli_connect_errno()){
            return false;
        }
        else{
            return true;
        }
    }
    public function getList(){
        $query="SELECT * FROM giocatori ORDER BY ID ASC";
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