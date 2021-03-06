<?php
require_once("ModelBase.php");
class Yoyaku extends ModelBase {

    // プロパティの宣言
    //var $data;
    //var $count;
    var $conn;
    /*--------------------
    // コンストラクタ
    ---------------------*/
    function __construct() {

        parent::__construct();

        /*if( $this->conn === false ) {
             //TODO
            
            echo "db-err";
        }else{
            echo "suc";
        }*/

    }

    //受付№、受付日、顧客コード、明細リスト
    function yoyaku( $ukeno, $ukedt, $kyacd, $list ) {

        /* テーブル */
        $headerTB = "dt_wbseikyu";
        $detailTB = "dt_wbseikyu_m";

        /* transaction flg */
        $tran = true;

        /* Begin the transaction. */
        if ( sqlsrv_begin_transaction( $this->conn ) === false ) {
             die( print_r( sqlsrv_errors(), true ));
        }
        //parent::begin_transaction( $this->conn );

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

        $stmt = sqlsrv_query( $this->conn, $sql );

        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

            $dannm =  $row['dannm'];    //団体名
            $dannm = mb_convert_encoding($row['dannm'], "SJIS", "utf8");//施設名称
            $dannm2 =  $row['dannm2'];
            $daihyo = mb_convert_encoding($row['daihyo'], "SJIS", "utf8");//代表者名
            $renraku = mb_convert_encoding($row['renraku'], "SJIS", "utf8");//連絡者名
            $kyakb =  $row['kyakb'];    //1:一般 2:中小企業 99:その他(ct_kyaku)
            $kounoukb = $row['kounoukb']; //後納区分
            $login = $row['wloginid'];    //ログイン
            echo $row['dannm'].", ".$row['dannm2']."<br />";
        }
 
        /* 請求書明細 */
        $gyo_num = 0;
        
        foreach ($list as $gyo => $rec) {
            
            $gyo_num++;
            $usedt = $rec['usedt'];     //使用日
            $yobi = mb_convert_encoding($rec['yobi'], "SJIS", "utf8");//施設名称
            $yobikb = $rec['yobikb'];  //使用曜日区分
            $hzkb = 0;                  //付属設備区分
            $rmcd = $rec['rmcd'];       //施設コード
            $rmnmr = mb_convert_encoding($rec['rmnmr'], "SJIS", "utf8");//施設名称
            $hzcd = 0;                  //付属設備コード
            $hznmr = "";//付属設備名称
            $stjkn = $rec['stjkn'];//使用開始時間
            $edjkn = $rec['edjkn'];//使用終了時間
            $hbstjkn = $rec['hbstjkn'];//本番開始時間
            $hbedjkn = $rec['hbedjkn'];//本番終了時間

            $zgrt = 0;

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
            $kin = $rec['rmkin'];
            //$seikin = intval($seikin) + intval($kin);
            //insert

            /*後納の場合、料金通知テーブルに書き込む*/
            if( $kounoukb == 1 ){
                $sql = "INSERT INTO dt_wbtuchi_m ( tuchino, ukeno, gyo, usedt, yobi, yobikb, hzkb, rmcd, rmnmr, hzcd, hznmr, stjkn, edjkn, hbstjkn, hbedjkn, zgrt, tnk, kin, login, udate, utime)";
            }else{
                $sql = "INSERT INTO dt_wbseikyu_m ( seino, ukeno, gyo, usedt, yobi, yobikb, hzkb, rmcd, rmnmr, hzcd, hznmr, stjkn, edjkn, hbstjkn, hbedjkn, zgrt, tnk, kin, login, udate, utime)";
            }

            $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

            $params = array( $ukeno, $ukeno, $gyo_num, $usedt, $yobi, $yobikb, $hzkb, $rmcd, $rmnmr, $hzcd, $hznmr,
                $stjkn, $edjkn, $hbstjkn, $hbedjkn, $zgrt, $tnk, $kin, $login, parent::getUdate(), parent::getUtime() );

            $stmt = sqlsrv_query( $this->conn, $sql, $params );

            if( $stmt === false) {
                $tran = false;
                break;//exit for
            }

            //付属備品（グランドピアノ
            if( $rec['piano'] ==1 ){

                $gyo_num++;
                $usedt = $rec['usedt'];//使用日
                $yobi = "";//施設名称
                $yobikb = 0;//使用曜日区分
                $hzkb = 1;//付属設備区分
                $rmcd = 0;//施設コード
                $rmnmr = "";//施設名称
                $hzcd = 1006; //付属設備コード
                $hznmr = mb_convert_encoding("ｸﾞﾗﾝﾄﾞﾋﾟｱﾉ", "SJIS", "utf8");//付属設備名称
                $stjkn = 0;//使用開始時間
                $edjkn = 0;//使用終了時間
                $hbstjkn = 0;//本番開始時間
                $hbedjkn = 0;//本番終了時間
                $tnk = 0;//単価;
                $kin = $rec['hzkin'];       //設備金額

                /*後納の場合、料金通知テーブルに書き込む*/
                if( $kounoukb == 1 ){
                    $sql = "INSERT INTO dt_wbtuchi_m ( tuchino, ukeno, gyo, usedt, yobi, yobikb, hzkb, rmcd, rmnmr, hzcd, hznmr, stjkn, edjkn, hbstjkn, hbedjkn, zgrt, tnk, kin, login, udate, utime)";
                }else{
                    $sql = "INSERT INTO dt_wbseikyu_m ( seino, ukeno, gyo, usedt, yobi, yobikb, hzkb, rmcd, rmnmr, hzcd, hznmr, stjkn, edjkn, hbstjkn, hbedjkn, zgrt, tnk, kin, login, udate, utime)";
                }

                $sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

                $params = array( $ukeno, $ukeno, $gyo_num, $usedt, $yobi, $yobikb, $hzkb, $rmcd, $rmnmr, $hzcd, $hznmr,
                $stjkn, $edjkn, $hbstjkn, $hbedjkn, $zgrt, $tnk, $kin, $login, parent::getUdate(), parent::getUtime() ); 

                $stmt = sqlsrv_query( $this->conn, $sql, $params );

                if( $stmt === false) {
                    $tran = false;
                    break;//exit for
                }

            }

        }
        
        /* 請求書ヘッダー */
        //insert
        /*後納の場合、料金通知テーブルに書き込む*/

        if( $kounoukb == 1 ){
            $sql = "INSERT INTO  dt_wbtuchi ( tuchino, ukeno, tuchidt, tuchiurl, tuchifile, tuchideal, tuchifbd, ukedt, nen,";
            $sql = $sql." kyacd, dannm, dannm2, daihyo, renraku, tuchikin, paylmtdt, login, udate, utime)";
        }else{
            $sql = "INSERT INTO  dt_wbseikyu ( seino, ukeno, seidt, seiurl, seifile, seideal, seifbd, ukedt, nen,";
            $sql = $sql." kyacd, dannm, dannm2, daihyo, renraku, seikin, paylmtdt, login, udate, utime)";
        }
        
        $sql = $sql." VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? )";

        $params = array( $ukeno, $ukeno, 0, $seiurl, '', 0, 0, $ukedt, $nen,$kyacd, $dannm, $dannm2, $daihyo, $renraku, $seikin, $paylmtdt, $login, parent::getUdate(), parent::getUtime() );

       $stmt = sqlsrv_query( $this->conn, $sql, $params );

        if( $stmt === false) {
            $tran = false;
            //break;//exit for
        }

        /* If both queries were successful, commit the transaction. */
        /* Otherwise, rollback the transaction. */
        //parent::begin_transaction( $this->conn , $tran );

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
