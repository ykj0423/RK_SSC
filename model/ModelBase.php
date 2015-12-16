<?php
class ModelBase
{
    protected $conn;

    public function __construct()
    {
        $this->connectDb();
        $this->udate = date( "Ymd" );
        $this->utime = date( "His" );
    }

    /* Begin the transaction. */
    public function begin_transaction( $conn ){
         echo "begin";       
        if ( sqlsrv_begin_transaction( $conn ) === false ) {
            //die( print_r( sqlsrv_errors(), true ));
            print_r( sqlsrv_errors(), true );
            return false;
        }

        return true;
    }
    
    public function end_transaction( $conn, $tran ){

        if( $tran ) {
            echo "commit";
             sqlsrv_commit( $conn );
             return true;
             echo "Transaction committed.<br />";
        } else {
            echo "rollbk";
             sqlsrv_rollback( $conn );
             return false;
             echo "Transaction rolled back.<br />";
        }

    }

    public function getUdate()
    {
        return $this->udate;
    }

    public function getUtime()
    {
        return $this->utime;
    }

    public function connectDb()
    {
        
        $this->conn = false;

        $ini = parse_ini_file('config.ini');
        
        $serverName = $ini['SERVER_NAME'];
        $connectionInfo = array( "Database"=>$ini['DBNAME'], "UID"=>$ini['UID'], "PWD"=>$ini['PWD'] );

        $conn = sqlsrv_connect( $serverName, $connectionInfo);

        if( $conn === false ) {
             //TODO
            $this->conn = false;
            die( print_r( sqlsrv_errors(), true));
        }

        $this->conn = $conn;

    }

    public function convertToZero( $str )
    {
        if( !isset( $str ) || empty( $str ) ){
            return 0;
        }

    }

     public function convertToSJIS( $str )
    {
        if( isset( $str ) && !empty( $str ) ){
            return mb_convert_encoding($str, "SJIS", "auto");
        }else{
            return '';
        }
    }

}
?>