<?php
require_once("ModelBase.php");
class Reserve extends ModelBase {
    
    // プロパティの宣言
    //var $data;
    //var $count;
    //var $conn;
    /*--------------------
    // コンストラクタ
    ---------------------*/
    function __construct() {
        
        parent::__construct();

        if( $this->conn === false ) {
             //TODO
            //echo "db-err";
        }else{
            //echo "suc";
        }
               
    }
    
    function reserve( $list,  $ukeno, $login ) {
        
        /* transaction flg */
        $tran = true;

        /* Begin the transaction. */
        if ( sqlsrv_begin_transaction( $this->conn ) === false ) {
             die( print_r( sqlsrv_errors(), true ));
        }

        foreach ($list as $gyo => $rec) {

            $usedt = $rec['usedt'];
            $rmcd = $rec['rmcd'];
            $timekb = $rec['timekb'];

                $stt = 0;
                $end = 0;
        
            if( $timekb == 1 ){
                $stt = 9;
                $end = 11;
            }else if( $timekb == 2 ) {
                $stt = 13;
                $end = 16;    
            }else if( $timekb == 3 ) {
                $stt = 18;
                $end = 20;
            }else if( $timekb == 4 ) {
                $stt = 9;
                $end = 16;
            }else if( $timekb == 5 ) {
                $stt = 13;
                $end = 20;
            }else if( $timekb == 6 ) {
                $stt = 9;
                $end = 20;
            }
            /* 1 */
            /* 空室チェック （予約マーク）*/
            //満室：空室マークが０以外のレコードが存在、利用状況０以外のレコードが存在
            for ( $jkn = $stt; $jkn <= $end;  $jkn++) { // 3時間分回す
            
                $sql = "SELECT * FROM ks_jknksi WHERE usedt = ".$usedt." AND jikan = ".$jkn." AND rmcd = ".$rmcd." AND rsignkb <> 0 ";//OR rjyokb <> 0";            
                $stmt = sqlsrv_query( $this->conn, $sql );
                $has_rows = sqlsrv_has_rows ( $stmt );
                
                if ( $has_rows ){
                    //echo $sql;
                    $tran =  false;
                }
        
            }//exit for

            /* 2 */
            /* 空室チェック （時間帯）*/
            //満室：受付№が０以外のレコードが存在
            $sql = "SELECT * FROM ks_jkntai WHERE usedt = ".$usedt." AND rmcd = ".$rmcd." AND timekb = ".$timekb." AND ukeno <> 0";
            $stmt = sqlsrv_query( $this->conn, $sql );
            $has_rows = sqlsrv_has_rows ( $stmt );
            
            if ( $has_rows ){
                //echo $sql;
                $tran =  false;
            }

            
            /* 空室チェック （予約マーク） 更新　*/
            $rsignkb=3;//仮予約
            
            if($_SESSION['kyakb']==99){
                
                $rsignkb=5;//内部手続き済み

            }else if($_SESSION['kounoukb']==1){

                $rsignkb=4;//未収

            }


            //レコードが存在しなければ挿入、存在すれば更新        
            for ( $jkn = $stt; $jkn <= $end;  $jkn++) { // 3時間分回す

                $sql = "SELECT * FROM ks_jknksi WHERE usedt = ".$usedt." AND jikan = ".$jkn." AND rmcd = ".$rmcd;
                
                $stmt = sqlsrv_query( $this->conn, $sql );
                
                if( $stmt === false) {
                    die( print_r( sqlsrv_errors(), true) );
                }
                //echo $sql;
                $has_rows = sqlsrv_has_rows ( $stmt );
                
                if ( $has_rows ){
                    
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                    
                        if( $row['rsignkb'] != 0 ){

                            sqlsrv_rollback( $this->conn );
                            //echo "Transaction rolled back.<br />";
                            //die( "unexpected error" ); //想定外、先取りされているなど
                            return false;

                        }

                        /*if( $row['rjyokb'] != 0 ){
                            
                            sqlsrv_rollback( $this->conn );
                            echo "Transaction rolled back.<br />";
                            //die( "unexpected error" ); //想定外、先取りされているなど
                            return false;

                        }*/
                    
                    }
                    
                    //update
                    $sql = "UPDATE ks_jknksi SET rsignkb=(?), rjyokb=(?),login=(?), udate=(?), utime=(?)";
                    $sql = $sql." WHERE usedt=(?) AND rmcd=(?) AND jikan=(?)";
                    $params = array( $rsignkb, 2, $login, parent::getUdate(), parent::getUtime(), $usedt, $rmcd, $jkn ); //2:予約済



                }else{

                    //insert
                    $sql = "INSERT INTO ks_jknksi ( usedt, jikan, rmcd, rsignkb, rjyokb ,login, udate, utime)";
                    $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                    $params = array( $usedt, $jkn, $rmcd, $rsignkb, 2, $login, parent::getUdate(), parent::getUtime() );//2:予約済

                }

                $stmt = sqlsrv_query( $this->conn, $sql, $params );
            
                if( $stmt === false) {
                    $tran = false;
                    echo $sql;
                    break;//exit for
                }

                if( $rmcd == 1001 || $rmcd == 1002){
                        
                }
                
                if( $rmcd == 301){
                    

                }
            
            }//for
    
            /* 空室チェック （時間帯）*/
            //満室：受付№が０以外のレコードが存在
            for ( $jkn = $stt; $jkn <= $end;  $jkn++) { // 3時間分回す
            
                $sql = "SELECT * FROM ks_jkntai WHERE usedt = ".$usedt." AND rmcd = ".$rmcd." AND jikan = ".$jkn." AND timekb = ".$timekb ;

                $stmt = sqlsrv_query( $this->conn, $sql );
                
                if( $stmt === false) {
                    die( print_r( sqlsrv_errors(), true) );
                }
            
                $has_rows = sqlsrv_has_rows ( $stmt );

                if ( $has_rows ){
                
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                
                        if( $row['ukeno'] != 0 ){
                            die( "unexpected error" ); //想定外、先取りされているなど
                        }
                
                    }
                    //update
                    $sql = "UPDATE ks_jkntai SET ukeno=(?), gyo=(?),login=(?), udate=(?), utime=(?)";
                    $sql = $sql." WHERE usedt=(?) AND rmcd=(?) AND jikan=(?) AND timekb=(?)";
                    $params = array( $ukeno, $gyo, $login, parent::getUdate(), parent::getUtime() , $usedt, $rmcd, $jkn, $timekb );

                }else{
            
                    //insert
                    $sql = "INSERT INTO ks_jkntai ( usedt, jikan, rmcd, timekb, ukeno, gyo, login, udate, utime)";
                    $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $params = array( $usedt, $jkn, $rmcd, $timekb, $ukeno, $gyo, $login, parent::getUdate(), parent::getUtime() );

                }//if
            
                $stmt = sqlsrv_query( $this->conn, $sql, $params );
            
                if( $stmt === false) {
                    $tran = false;
                    echo $sql;
                    print_r($params);
                    break;//exit for
                }

            }//for




        }//exit foreach

        /* If both queries were successful, commit the transaction. */
        /* Otherwise, rollback the transaction. */
        if( $tran ) {
             sqlsrv_commit( $this->conn );
             return true;
             //echo "Transaction committed.<br />";
        } else {
             sqlsrv_rollback( $this->conn );
             return false;
             //echo "Transaction rolled back.<br />";
        }
    
    }
    
}
?>
