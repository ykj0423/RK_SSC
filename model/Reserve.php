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
             //die( print_r( sqlsrv_errors(), true ));
        }

        /* 空室チェック （予約マーク）*/
        // ks_jknksi, ks_jkntai //
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
                $tran =  false;
            }
            
            /* 3 */
            /* 空室チェック （予約マーク） 更新　*/
            $rsignkb = 3;//仮予約
            
            if( $_SESSION['kyakb'] == 99 ){
                
                $rsignkb = 5;//内部手続き済み

            }else if( $_SESSION['kounoukb'] == 1 ){

                $rsignkb = 4;//未収

            }

            //レコードが存在しなければ挿入、存在すれば更新        
            for ( $jkn = $stt; $jkn <= $end;  $jkn++) { // 3時間分回す

                $sql = "SELECT * FROM ks_jknksi WHERE usedt = ".$usedt." AND jikan = ".$jkn." AND rmcd = ".$rmcd;
                
                $stmt = sqlsrv_query( $this->conn, $sql );
                
                if( $stmt === false) {
                    return false;
                }
                
                $has_rows = sqlsrv_has_rows ( $stmt );
                
                if ( $has_rows ){
                    
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                    
                        if( $row['rsignkb'] != 0 ){

                            //sqlsrv_rollback( $this->conn );
                            //echo $sql;
                            return false;
                        }                  
                    
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

                }//has_rows

                $stmt = sqlsrv_query( $this->conn, $sql, $params );
            
                if( $stmt === false) {
                    $tran = false;
                    //echo $sql;
                    //break;//exit for
                }
                /*----------------*/
                if( $rmcd == 802 || $rmcd == 803 || $rmcd == 902 || $rmcd == 903 || $rmcd == 905 || $rmcd == 905 || $rmcd == 1001 || $rmcd == 1002){
                    
                    $oyarmcd = 0;
                    $mngrmcd = 0;   
                    
                    if( $rmcd == 802 || $rmcd == 803 ){
                        $oyarmcd = 823;
                    }
                    if( $rmcd == 902 || $rmcd == 903 ){
                        $oyarmcd = 923;
                    }
                    if( $rmcd == 904 || $rmcd == 905 ){
                        $oyarmcd = 945;
                    }
                    if( $rmcd == 1001 || $rmcd == 1002 ){
                        $oyarmcd = 1012;

                        if( $rmcd == 1001 ){
                            $mngrmcd = 1002;    
                        }
                        
                        if( $rmcd == 1002 ){
                            $mngrmcd = 1001;    
                        }
                    
                    }

                    //親施設
                    if( $oyarmcd != 0 ){

                        $sql = "SELECT * FROM ks_jknksi WHERE usedt = ".$usedt." AND jikan = ".$jkn." AND rmcd = ".$oyarmcd;
                    
                        $stmt = sqlsrv_query( $this->conn, $sql );
                        
                        if( $stmt === false) {
                            $tran = false;
                            //die( print_r( sqlsrv_errors(), true) );
                        }
                        $has_rows = sqlsrv_has_rows ( $stmt );
                        
                        if ( $has_rows ){
                            
                            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                            
                                if( $row['rsignkb'] != 0 ){

                                    //sqlsrv_rollback( $this->conn );
                                    //return false;

                                }

                              
                            }
                            
                            //update
                            $sql = "UPDATE ks_jknksi SET rsignkb=(?), rjyokb=(?),login=(?), udate=(?), utime=(?)";
                            $sql = $sql." WHERE usedt=(?) AND rmcd=(?) AND jikan=(?)";
                            $params = array( 9, 2, $login, parent::getUdate(), parent::getUtime(), $usedt, $oyarmcd, $jkn ); //2:予約済

                        }else{

                            //insert
                            $sql = "INSERT INTO ks_jknksi ( usedt, jikan, rmcd, rsignkb, rjyokb ,login, udate, utime)";
                            $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                            $params = array( $usedt, $jkn, $oyarmcd, 9, 2, $login, parent::getUdate(), parent::getUtime() );//2:予約済

                        }

                        $stmt = sqlsrv_query( $this->conn, $sql, $params );
            
                        if( $stmt === false) {
                            $tran = false;
                            //echo $sql;
                            //break;//exit for
                        }

                    }//oya

                    if( $mngrmcd != 0 ){

                        $sql = "SELECT * FROM ks_jknksi WHERE usedt = ".$usedt." AND jikan = ".$jkn." AND rmcd = ".$mngrmcd;
                  
                        $stmt = sqlsrv_query( $this->conn, $sql );
                        
                        if( $stmt === false) {
                            return false;
                            //echo $sql;
                            //die( print_r( sqlsrv_errors(), true) );
                        }

                        $has_rows = sqlsrv_has_rows ( $stmt );
                        
                        if ( $has_rows ){
                            
                            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                            
                                if( $row['rsignkb'] != 0 ){
                                    //sqlsrv_rollback( $this->conn );
                                    //echo $sql;
                                    return false;
                                }
                              
                            }
                            
                            //update
                            $sql = "UPDATE ks_jknksi SET rsignkb=(?), rjyokb=(?),login=(?), udate=(?), utime=(?)";
                            $sql = $sql." WHERE usedt=(?) AND rmcd=(?) AND jikan=(?)";
                            $params = array( 7, 2, $login, parent::getUdate(), parent::getUtime(), $usedt, $mngrmcd, $jkn ); //2:予約済

                        }else{

                            //insert
                            $sql = "INSERT INTO ks_jknksi ( usedt, jikan, rmcd, rsignkb, rjyokb ,login, udate, utime)";
                            $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                            $params = array( $usedt, $jkn, $mngrmcd, 7, 2, $login, parent::getUdate(), parent::getUtime() );//2:予約済

                        }

                        $stmt = sqlsrv_query( $this->conn, $sql, $params );
            
                        if( $stmt === false) {
                            $tran = false;
                        }

                    }//mngrmcd
                    
                }//$rmcd == 802 || $rmcd == 803 || $rmcd == 902 || $rmcd == 903 || $rmcd == 905 || $rmcd == 905 || $rmcd == 1001 || $rmcd == 1002
                
                if( $rmcd == 823 || $rmcd == 923 || $rmcd == 945 ){

                    if( $rmcd == 823 ){
                         
                        $child1 = 802;   
                        $child2 = 803;                        

                    }
                    
                    if( $rmcd == 923 ){
                    
                        $child1 = 902;   
                        $child2 = 903;                       
                            
                    }

                    if( $rmcd == 945 ){

                        $child1 = 904;   
                        $child2 = 905;                        
                            
                    }

                    for ($count = $child1; $count <= $child2 ; $count++) {
                    
                        $sql = "SELECT * FROM ks_jknksi WHERE usedt = ".$usedt." AND jikan = ".$jkn." AND rmcd = ".$count;
                    
                        $stmt = sqlsrv_query( $this->conn, $sql );
                        
                        if( $stmt === false) {
                            $tran = false;
                            //die( print_r( sqlsrv_errors(), true) );
                        }
                        
                        $has_rows = sqlsrv_has_rows ( $stmt );
                        
                        if ( $has_rows ){
                            
                            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                            
                                if( $row['rsignkb'] != 0 ){

                                    //sqlsrv_rollback( $this->conn );
                                    //echo $sql;
                                    return false;

                                }

                              
                            }
                            
                            //update
                            $sql = "UPDATE ks_jknksi SET rsignkb=(?), rjyokb=(?),login=(?), udate=(?), utime=(?)";
                            $sql = $sql." WHERE usedt=(?) AND rmcd=(?) AND jikan=(?)";
                            $params = array( 8, 2, $login, parent::getUdate(), parent::getUtime(), $usedt, $count, $jkn ); //2:予約済

                        }else{

                            //insert
                            $sql = "INSERT INTO ks_jknksi ( usedt, jikan, rmcd, rsignkb, rjyokb ,login, udate, utime)";
                            $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                            $params = array( $usedt, $jkn, $count, 8, 2, $login, parent::getUdate(), parent::getUtime() );//2:予約済

                        }


                        $stmt = sqlsrv_query( $this->conn, $sql, $params );
            
                        if( $stmt === false) {
                            $tran = false;
                            //break;//exit for
                        }
                    
                    }
                
                }//$rmcd == 823 || $rmcd == 923 || $rmcd == 945 )
            
            }//for/* 3 */
           
            /*---------------------------------------*/
            //hall
            /*---------------------------------------*/
            if( $rmcd == 301 ){
                
                $mng_rec = array();
                
                if( $timekb == 1 ){
                    $mng_rec = array(12, 13, 14, 15, 16);
                }else if( $timekb == 2 ) {
                    $mng_rec = array(9, 10, 11, 12, 17, 18, 19, 20);
                }else if( $timekb == 3 ) {
                    $mng_rec = array(12, 13, 14, 15, 16);
                }else if( $timekb == 4 ) {
                    $mng_rec = array(17, 18, 19, 20);
                }else if( $timekb == 5 ) {
                    $mng_rec = array(9, 10, 11, 12);
                }else if( $timekb == 6 ) {
                }
                
                for ($cnt = 0 ; $cnt < count($mng_rec); $cnt++) {
                //for ( $jkn = $stt; $jkn <= $end;  $jkn++) { // 3時間分回す

                    $sql = "SELECT * FROM ks_jknksi WHERE usedt = ".$usedt." AND jikan = ".$mng_rec[$cnt]." AND rmcd = ".$rmcd;
                    
                    $stmt = sqlsrv_query( $this->conn, $sql );
                    
                    if( $stmt === false) {
                        return false;
                        //echo $sql;
                        //die( print_r( sqlsrv_errors(), true) );
                    }

                    $has_rows = sqlsrv_has_rows ( $stmt );
                    
                    if ( $has_rows ){
                        
                        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                        
                            //if( $row['rsignkb'] != 0 ){

                            //    sqlsrv_rollback( $this->conn );
                            //    return false;

                            //}

                        
                        }
                        
                        //update
                        $sql = "UPDATE ks_jknksi SET rsignkb=(?), rjyokb=(?),login=(?), udate=(?), utime=(?)";
                        $sql = $sql." WHERE usedt=(?) AND rmcd=(?) AND jikan=(?)";
                        $params = array( 7, 2, $login, parent::getUdate(), parent::getUtime(), $usedt, 301, $mng_rec[$cnt]); //7:使用不可

                    }else{

                        //insert
                        $sql = "INSERT INTO ks_jknksi ( usedt, jikan, rmcd, rsignkb, rjyokb ,login, udate, utime)";
                        $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                        $params = array( $usedt, $mng_rec[$cnt], 301, 7, 2, $login, parent::getUdate(), parent::getUtime() );//7:使用不可

                    }

                    $stmt = sqlsrv_query( $this->conn, $sql, $params );
                
                    if( $stmt === false) {
                        $tran = false;
                        //echo $sql;
                        //break;//exit for
                    }

                }//for
            
            }//hall end

            /*---------------------------------------*/
            /* 空室チェック （時間帯）*/
            //満室：受付№が０以外のレコードが存在
            /*---------------------------------------*/
            for ( $jkn = $stt; $jkn <= $end;  $jkn++) { // 3時間分回す
            
                $sql = "SELECT * FROM ks_jkntai WHERE usedt = ".$usedt." AND rmcd = ".$rmcd." AND jikan = ".$jkn." AND timekb = ".$timekb ;

                $stmt = sqlsrv_query( $this->conn, $sql );
                
                if( $stmt === false) {
                    return false;
                    //die( print_r( sqlsrv_errors(), true) );
                }
            
                $has_rows = sqlsrv_has_rows ( $stmt );

                if ( $has_rows ){
                
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                
                        if( $row['ukeno'] != 0 ){
                            $tran = false;
                            //die( "unexpected error" ); //想定外、先取りされているなど
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
                    //echo $sql;
                    //print_r($params);
                    break;//exit for
                }

                /*----------------*/                              
                if( $rmcd == 802 || $rmcd == 803 || $rmcd == 902 || $rmcd == 903 || $rmcd == 905 || $rmcd == 905 || $rmcd == 1001 || $rmcd == 1002){
                    
                    $oyarmcd = 0;    
                    $mngrmcd = 0 ;
                    
                    if( $rmcd == 802 || $rmcd == 803 ){
                        $oyarmcd = 823;
                    }
                    if( $rmcd == 902 || $rmcd == 903 ){
                        $oyarmcd = 923;
                    }
                    if( $rmcd == 904 || $rmcd == 905 ){
                        $oyarmcd = 945;
                    }
                    
                    if( $rmcd == 1001 || $rmcd == 1002 ){
                        
                        $oyarmcd = 1012;

                        if( $rmcd == 1001 ){
                            $mngrmcd = 1002;    
                        }
                        
                        if( $rmcd == 1002 ){
                            $mngrmcd = 1001;    
                        }
                    
                    }

                   
                    if( $oyarmcd != 0 ){

                        $sql = "SELECT * FROM ks_jkntai WHERE usedt = ".$usedt." AND rmcd = ".$oyarmcd." AND jikan = ".$jkn." AND timekb = ".$timekb ;

                        $stmt = sqlsrv_query( $this->conn, $sql );
                        
                        if( $stmt === false) {
                            return false;    
                            //echo $sql;
                            //die( print_r( sqlsrv_errors(), true) );
                        }
                    
                        $has_rows = sqlsrv_has_rows ( $stmt );

                        if ( $has_rows ){
                        
                            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                        
                                if( $row['ukeno'] != 0 ){
                                    //echo $sql;                                    
                                    //die( "unexpected error" ); //想定外、先取りされているなど
                                }else{
                                                                        //update
                                    $sql = "UPDATE ks_jkntai SET ukeno=(?), gyo=(?),login=(?), udate=(?), utime=(?)";
                                    $sql = $sql." WHERE usedt=(?) AND rmcd=(?) AND jikan=(?) AND timekb=(?)";
                                    $params = array( $ukeno, $gyo, $login, parent::getUdate(), parent::getUtime() , $usedt, $oyarmcd, $jkn, $timekb );

                                }
                        
                            }

                        }else{
                    
                            //insert
                            $sql = "INSERT INTO ks_jkntai ( usedt, jikan, rmcd, timekb, ukeno, gyo, login, udate, utime)";
                            $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $params = array( $usedt, $jkn, $oyarmcd, $timekb, $ukeno, $gyo, $login, parent::getUdate(), parent::getUtime() );

                        }//if

                        $stmt = sqlsrv_query( $this->conn, $sql, $params );
            
                        if( $stmt === false) {
                            $tran = false;
                            //echo $sql;
                            //break;//exit for
                        }
                    

                    }
                    //1001,1002
                    if( $mngrmcd != 0 ){

                        $sql = "SELECT * FROM ks_jkntai WHERE usedt = ".$usedt." AND rmcd = ".$mngrmcd." AND jikan = ".$jkn." AND timekb = ".$timekb ;

                        $stmt = sqlsrv_query( $this->conn, $sql );
                        
                        if( $stmt === false) {
                            return false; 
                            //die( print_r( sqlsrv_errors(), true) );
                        }
                    
                        $has_rows = sqlsrv_has_rows ( $stmt );

                        if ( $has_rows ){
                        
                            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                        
                                if( $row['ukeno'] != 0 ){
                                    $tran = false;
                                    //die( "unexpected error" ); //想定外、先取りされているなど
                                }
                        
                            }
                            //update
                            $sql = "UPDATE ks_jkntai SET ukeno=(?), gyo=(?),login=(?), udate=(?), utime=(?)";
                            $sql = $sql." WHERE usedt=(?) AND rmcd=(?) AND jikan=(?) AND timekb=(?)";
                            $params = array( $ukeno, $gyo, $login, parent::getUdate(), parent::getUtime() , $usedt, $mngrmcd, $jkn, $timekb );

                        }else{
                    
                            //insert
                            $sql = "INSERT INTO ks_jkntai ( usedt, jikan, rmcd, timekb, ukeno, gyo, login, udate, utime)";
                            $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $params = array( $usedt, $jkn, $mngrmcd, $timekb, $ukeno, $gyo, $login, parent::getUdate(), parent::getUtime() );

                        }//if

                        $stmt = sqlsrv_query( $this->conn, $sql, $params );
            
                        if( $stmt === false) {
                            $tran = false;
                            //echo $sql;
                            //break;//exit for
                        }
                    

                    }

                }
                /*----------------*/
                
                 if( $rmcd == 823 || $rmcd == 923 || $rmcd == 945 ){

                    if( $rmcd == 823 ){
                         
                        $child1 = 802;   
                        $child2 = 803;                        

                    }
                    
                    if( $rmcd == 923 ){
                    
                        $child1 = 902;   
                        $child2 = 903;                       
                            
                    }

                    if( $rmcd == 945 ){

                        $child1 = 904;   
                        $child2 = 905;                        
                            
                    }

                    for ($count = $child1; $count <= $child2 ; $count++) {


                        $sql = "SELECT * FROM ks_jkntai WHERE usedt = ".$usedt." AND rmcd = ".$count." AND jikan = ".$jkn." AND timekb = ".$timekb ;

                        $stmt = sqlsrv_query( $this->conn, $sql );
                        
                        if( $stmt === false) {
                            $tran = false;
                            //return false;
                        }
                    
                        $has_rows = sqlsrv_has_rows ( $stmt );

                        if ( $has_rows ){
                        
                            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                        
                                if( $row['ukeno'] != 0 ){
                                    return false;
                                    //die( "unexpected error" ); //想定外、先取りされているなど
                                }
                        
                            }
                            //update
                            $sql = "UPDATE ks_jkntai SET ukeno=(?), gyo=(?),login=(?), udate=(?), utime=(?)";
                            $sql = $sql." WHERE usedt=(?) AND rmcd=(?) AND jikan=(?) AND timekb=(?)";
                            $params = array( $ukeno, $gyo, $login, parent::getUdate(), parent::getUtime() , $usedt, $count, $jkn, $timekb );

                        }else{
                    
                            //insert
                            $sql = "INSERT INTO ks_jkntai ( usedt, jikan, rmcd, timekb, ukeno, gyo, login, udate, utime)";
                            $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                            $params = array( $usedt, $jkn, $count, $timekb, $ukeno, $gyo, $login, parent::getUdate(), parent::getUtime() );

                        }//if


                    }

                }


                /*----------------*/

            }//for




        }//exit foreach

        /* If both queries were successful, commit the transaction. */
        /* Otherwise, rollback the transaction. */
        /*if( $tran ) {
             sqlsrv_commit( $this->conn );
             return true;
             //echo "Transaction committed.<br />";
        } else {
             sqlsrv_rollback( $this->conn );
             return false;
             //echo "Transaction rolled back.<br />";
        }*/
    
    }
    
}
?>
