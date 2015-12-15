<?php
require_once("ModelBase.php");
class Seikyu extends ModelBase {
    
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
            echo "db-err";
        }else{
            echo "suc";
        }
               
    }

    //受付№、受付日、顧客コード、明細リスト
    function seikyu( $ukeno, $ukedt, $kyacd, $list ) {

        /* transaction flg */
        $tran = true;

        /* Begin the transaction. */
        if ( sqlsrv_begin_transaction( $this->conn ) === false ) {
             die( print_r( sqlsrv_errors(), true ));
        }

        //請求書発行日                                                
        $seidt = 0;//ZERO
        //請求書ダウンロードURL
        $seiurl = "";
        //請求書ファイル名
        $seiurl = ""; //空文字
        //請求書自動発行処理 
        $seideal = 0; //ZERO
        //請求書ダウンロード不可  0:可 1:不可 
        $seifbd = 0;  //ZERO
        //年度 
        $nen = substr( $ukedt, 0, 4 );
        $m = substr( $ukedt, 4, 2 );
        if( $m < 10 ) { $m = '0'.$m; }
        $d = substr( $ukedt, 6, 4 );
        if( $d < 10 ) { $d = '0'.$d; }
        //請求金額 
        $seikin = 0;
        //納付期限 
        $date_ukedt = strtotime( $nen.'-'.$m.'-'.$d );
        $paylmtdt = date('Ymd', strtotime(' +9 days', $date_ukedt)); 

        /* 顧客データ */
        $sql = "SELECT * FROM mt_kyaku WHERE kyacd = ".$kyacd;
        $stmt = sqlsrv_query( $conn, $sql );

        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

            $dannm =  $row['dannm'];    //団体名  
            $dannm2 =  $row['dannm2'];
            $daihyo =  $row['daihyo'];  //代表者名
            $renraku =  $row['renraku'];//連絡者名 
            $kyakb =  $row['kyakb'];    //1:一般 2:中小企業 99:その他(ct_kyaku)
            $login =  $row['login'];    //ログイン

        }
        
        /* 請求書明細 */
        $gyo_num = 0;

        foreach ($list as $gyo => $rec) {

            $gyo_num++;
            $usedt = $rec['usedt'];     //使用日
            $yobi = $rec['yobi'];       //使用曜日
            $yobikb = $rec['yobikb'];   //使用曜日区分
            $hzkb = 0;                  //付属設備区分
            $rmcd = $rec['rmcd'];       //施設コード
            $rmnmr = $rec['rmnmr'];     //施設名称
            $hzcd = 0;                  //付属設備コード
            $hznmr = ''；                //付属設備名称
            $stjkn = $rec['stjkn'];     //使用開始時間
            $edjkn = $rec['edjkn'];     //使用終了時間
            $hbstjkn = $rec['hbstjkn']; //本番開始時間
            $hbedjkn = $rec['hbedjkn']; //本番終了時間
            
            $zgr = 0; 
            
            /*$zgr = 100; 
            
            $comlkb = $rec['comlkb'];//営利目的区分
 
            if( $comlkb == 1 ){
                $zgr = $zgr * 1.5;
            }
 
            $jnbkb = $rec['jnbkb'];//準備撤去区分
            
            if( $jnbkb == 1 ){
                $zgr = $zgr * 0.5;
            }
            */
            
            $tnk = 0;//$rec['tnk'];
            $kin = $rec['rmkin']
            $seikin = $seikin + $kin;

            //insert
            $sql = "INSERT INTO dt_wbseikyu_m ( seino, ukeno, gyo, usedt, yobi, yobikb, hzkb, rmcd, rmnmr, hzcd, hznmr, stjkn, edjkn, hbstjkn, hbedjkn, zgrt, tnk, kin,
                login, udate, utime)";
            $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

            $params = array( $ukeno, $ukeno, $gyo_num, $usedt, $yobi, $yobikb, $hzkb, $rmcd, $rmnmr, $hzcd, $hznmr,
                $stjkn, $edjkn, $hbstjkn, $hbedjkn, $zgrt, $tnk, $kin, $login, parent::getUdate(), parent::getUtime() );

            $stmt = sqlsrv_query( $this->conn, $sql, $params );
        
            if( $stmt === false) {
                $tran = false;
                break;//exit for
            }

            //グランドピアノ区分ありの場合、もう一明細作成
            if( $rec['pianokb'] ==1 ){
                
                $gyo_num++;

                $usedt = $rec['usedt'];     //使用日
                $yobi = $rec['yobi'];       //使用曜日
                $yobikb = $rec['yobikb'];   //使用曜日区分
                $hzkb = 1;                  //付属設備区分
                $rmcd = 0;                  //施設コード
                $rmnmr = '';                //施設名称
                $hzcd = 1006;               //付属設備コード
                $hznmr ='ｸﾞﾗﾝﾄﾞﾋﾟｱﾉ';         //付属設備名称
                $stjkn = 0;                 //使用開始時間
                $edjkn = 0;                 //使用終了時間
                $hbstjkn = 0;               //本番開始時間
                $hbedjkn = 0;               //本番終了時間            
                $zgr = 0;                   //増減率
                $tnk = 0;                   //単価
                $kin = $rec['rmkin'];       //金額
                $seikin = $seikin + $kin;   //合計金額
            
                //insert
                $sql = "INSERT INTO dt_wbseikyu_m ( seino, ukeno, gyo, usedt, yobi, yobikb, hzkb, rmcd, rmnmr, hzcd, hznmr, stjkn, edjkn, hbstjkn, hbedjkn, zgrt, tnk, kin,
                    login, udate, utime)";
                $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $params = array( $ukeno, $ukeno, $gyo_num, $usedt, $yobi, $yobikb, $hzkb, $rmcd, $rmnmr, $hzcd, $hznmr,
                    $stjkn, $edjkn, $hbstjkn, $hbedjkn, $zgrt, $tnk, $kin, $login, parent::getUdate(), parent::getUtime() );

                $stmt = sqlsrv_query( $this->conn, $sql, $params );
            
                if( $stmt === false) {
                    $tran = false;
                    break;//exit for
                }

            }

        }//exit foreach

        /* 請求書ヘッダー */
        //insert
        $sql = "INSERT INTO dt_wbseikyu ( seino, ukeno, seidt, seiurl, seifile, seideal, seifbd, ukedt, nen,"; 
        $sql = $sql." kyacd, dannm, dannm2, daihyo, renraku, seikin, paylmtdt, login, udate, utime)";
        $sql = $sql." ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";

        $params = array( $ukeno, $ukeno, 0, $seiurl, '', 0, 0, $ukedt, $nen, 
            $kyacd, $dannm, $dannm2, $daihyo, $renraku, $seikin, $paylmtdt, $login, parent::getUdate(), parent::getUtime() );

        $stmt = sqlsrv_query( $this->conn, $sql, $params );
    
        if( $stmt === false) {
            $tran = false;
            break;//exit for
        }


        /* If both queries were successful, commit the transaction. */
        /* Otherwise, rollback the transaction. */
        if( $tran ) {
             sqlsrv_commit( $this->conn );
             return true;
             echo "Transaction committed.<br />";
        } else {
             sqlsrv_rollback( $this->conn );
             return false;
             echo "Transaction rolled back.<br />";
        }
    
    }
    
}
?>
