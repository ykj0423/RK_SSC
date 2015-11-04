<?php
require_once("ModelBase.php");
class Info extends ModelBase {
	
    // プロパティの宣言
    var $data;
    var $count;

    /*--------------------
    // ログイン処理
    ---------------------*/
    function __construct() {
        
        $count = 0;       
        $data = array();

        parent::__construct();

        if( $this->conn === false ) {
             //TODO
            echo "db-err";
        }else{
            //echo "suc";
        }
               
    }

    function get_info_count( $wloginid ) 
    {
        return count( $this->data );
    }

    function get_info( $wloginid ) 
	{
	 
		//if( $this->conn === false ) {
			 $this->connectDb();
		//}

		/* --------------------*/
		/*  お知らせ情報取得処理  */
		/* --------------------*/
		$sql = "SELECT * FROM wdt_info order by infodate desc";

		$stmt = sqlsrv_query( $this->conn, $sql );
		
		if( $stmt === false) {
			print_r( sqlsrv_errors(), true);
		}

        $cnt = 0;
		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

			$this->data[$cnt]['infodate'] = $row['infodate'];
            $this->data[$cnt]['contents'] = $row['contents'];
            $cnt++;

		}
		return $this->data;
	 }

}

?>
