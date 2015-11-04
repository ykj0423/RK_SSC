<?php
require_once("ModelBase.php");
class Room extends ModelBase {
	
    // プロパティの宣言
    var $data;
    var $count;

    /*--------------------
    // ログイン処理
    ---------------------*/
    function __construct() {
        
        $data_cnt = 0;       
        $data = array();

        parent::__construct();

        if( $this->conn === false ) {
             //TODO
            echo "db-err";
        }else{
            //echo "suc";
        }
               
    }

    function get_room_count( $wloginid ) 
    {
        return count( $this->data );
    }

    function get_rooms() 
	{
	 
		return false:
        //if( $this->conn === false ) {
		//	 $this->connectDb();
		//}

		/* --------------------*/
		/*  施設情報取得処理  */
		/* --------------------*/
		/* $sql = "SELECT * FROM mt_room order by rmcd";

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
        
        $this->data_cnt = $cnt;
		return $this->data;*//*TODO 見直し*/

	 }

     function get_room_info($rmcd) 
    {
     
        return false:
        //if( $this->conn === false ) {
        //     $this->connectDb();
        //}

        /* --------------------*/
        /*  施設情報取得処理  */
        /* --------------------*/
        /* $sql = "SELECT * FROM mt_room where rmcd = ".$rmcd;

        $stmt = sqlsrv_query( $this->conn, $sql );
        
        if( $stmt === false) {
            print_r( sqlsrv_errors(), true);
        }

        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

            $this->data[$cnt]['infodate'] = $row['infodate'];
            $this->data[$cnt]['contents'] = $row['contents'];
            
        }

        return $this->data;*//*TODO 見直し*/
     }

    /* --------------------*/
    /*  施設単価情報取得処理  */
    /* --------------------*/
     function get_room_tnk( $rmcd, $kyakb, $ratesb, $stjkn, $edjkn, $extension ,$commercially, $fee, $non_performance ) 
    {
     
        //顧客区分  kyakb 1:一般 99:その他(ct_kyaku)
        //料金種別  ratesb 料金種別M参照(mm_ratesb)//神戸市産振差までは未使用
        //使用開始時間    stjkn           
        //使用終了時間    edjkn           
        //現通常単価     tnk         
        //現延長単価     entnk           
        //$extension 延長フラグ
        //$commercially 営利目的フラグ,
        //$eiri_flg 営利目的フラグ,
        //$fee 入場料を払う,
        //$non_performance 練習、準備、撤去にあたる

        //if( $this->conn === false ) {
             $this->connectDb();
        //}

        $sql = "SELECT tnk, entnk FROM mt_rmtnk WHERE rmcd = ".$rmcd." AND ratesb".$ratesb;

        $stmt = sqlsrv_query( $this->conn, $sql );
        
        if( $stmt === false) {
            print_r( sqlsrv_errors(), true);
        }

        $tnk = 0;//単価
        
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

            if($extension) {
                $tnk = $row['entnk'];
            }else{
                $tnk = $$row['tnk'];
            }

        }

        if( $commercially && $fee ){
            $tnk = $tnk * 1.5;//TODO define    
        }

        if($non_performance){
            $tnk = $tnk * 0.5;//TODO define
        }
        
        return $tnk;
     }

}
?>
