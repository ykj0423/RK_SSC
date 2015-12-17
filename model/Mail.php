<?php
require_once("ModelBase.php");
class Mail extends ModelBase {

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
    function mail( $ukeno, $ukedt, $kyacd, $list ) {
//echo "seikyu_start";

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
//echo " seikyu_1 ";
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
//echo " seikyu_2 ";
        /* 顧客データ */
        $sql = "SELECT * FROM mt_kyaku WHERE kyacd = ".$kyacd;
//echo $sql."<br>";
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
//echo "foreach";      
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
            /*パターン区分*/
            if( $kounoukb == 1 ){
                $ptnkb=31;
            }else{
                $ptnkb=30;
            }

/*受付番号 ukeno  int8 予約明細   
メールパターン ptnkb   int2 名称マスタ（mm_mlptn）    
行番 gyo  int2 予約明細   
施設コード rmcd  int4
●施設分類区分 rmclkb   int2 施設分類マスタ参照（mm_rmcls）    
使用日付 usedt  int8 メールには　yyyy + "年" + mm + "月" + dd  + "日" 　形式で出力　（ゼロサプレス）    
使用日付曜日 yobi varchar4 メールには　"(" + yobi漢字 + ")"形式で出力  
●施設名称（Web用） rmnmw    varchar20
使用時間開始 stjkn    int4 メールには　hh + ":" + mm 形式で出力（ゼロサプレス）  
使用時間終了 edjkn    int4 メールには　hh + ":" + mm 形式で出力（ゼロサプレス）  
●準備リハ時間開始 jnstjkn    int4 メールには　hh + ":" + mm 形式で出力（ゼロサプレス）  
●準備リハ時間終了 jnedjkn    int4 メールには　hh + ":" + mm 形式で出力（ゼロサプレス）  
本番時間開始 hbstjkn  int4 メールには　hh + ":" + mm　形式で出力（ゼロサプレス）  
本番時間終了 hbedjkn  int4 メールには　hh + ":" + mm　形式で出力（ゼロサプレス）  
●撤去時間開始 tkstjkn  int4 メールには　hh + ":" + mm　形式で出力（ゼロサプレス）  
●撤去時間終了 tkedjkn  int4 メールには　hh + ":" + mm　形式で出力（ゼロサプレス）  
使用人数 ninzu  int5 メールには　ninzu + "人"  形式で出力（ゼロサプレス）   
●営利目的区分名称 comlkb int1 「営利目的で利用：する」「営利目的で利用：しない」  
●入場料受講料区分名称 feekb    int1 「入場料・受講料等の徴収：する」「入場料・受講料等の徴収：しない」  
●グランドピアノ使用区分名称 pianokb   int1 「グランドピアノの使用：する」「グランドピアノの使用：しない」    
●間仕切りフラグ名称 partkb    int1 「パーティション：開ける」「パーティション：閉める」 
●備考 biko varchar80 利用者入力内容（パーティションの開閉、立看板の有無等）
*/
            //insert
$sql = "INSERT INTO dbo.dt_mail_m (ukeno, ptnkb, gyo, rmcd, rmclkb, usedt, yobi, rmnmw, stjkn, edjkn, jnstjkn, jnedjkn, hbstjkn, hbedjkn, tkstjkn, tkedjkn, ninzu, comlkb, feekb, pianokb, partkb, biko, ucomputer, uosuser, usysuser, udate, utime, mcomputer, mosuser, msysuser, mdate, ukedt, wloginid, wudate, wutime, pgnm)";
$sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

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
if( $stmt === false ) {
    if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
            print_r($params);
        }
    }
}
                if( $stmt === false) {
                    $tran = false;
                    break;//exit for
                }

            }

        }

//echo "請求書ヘッダー";      
        
        /* ヘッダー */
/*受付番号 ukeno  int 8       予約      
メールパターン ptnkb   int 2       実行ＰＧによりセット      
受付日付 ukedt  int 8       予約      
顧客コード kyacd     int 6       予約      
メールアドレス mail    varchar 255     顧客マスタ       
団体名 dannm   varchar 50      予約      
代表者名 daihyo     varchar 40      予約      
連絡者名 renraku    varchar 40      予約      
管理責任者 sekinin   varchar 40      予約      
会議名称 kaigi  varchar 40      予約      
利用目的 riyokbnm   varchar 40      予約＋名称（mm_riyo）      
請求書ダウンロードＵＲＬ seikdl     varchar 255     請求データ       
請求書ファイル名 seifile    varchar 20      請求データ       
使用許可証ダウンロードＵＲＬ siyodl   varchar 255     予約明細        
許可書ファイル名 kyofile    varchar 20      予約明細        
納付期限 paylmtdt   int 8       予約      
ホール打合せ日 apptdt  int 8       画面      
失効予告日 expnocdt  int 8       yyyymmdd        
メール送信処理日 sddae  int 8       西暦表示(yyyymmdd)      
メール送信処理時間 sdtime    int 6       時分(hhmm)        
メール送信処理結果 sdret     int ?       0:未処理 1:正常 2:エラー        
更新コンピュータ名 ucomputer     varchar 20      
更新ユーザー（Windows） uosuser     varchar 20      
更新ユーザー（ｼｽﾃﾑのﾛｸﾞｲﾝﾕｰｻﾞｰ） usysuser    varchar 20      
更新日 udate   int 8       西暦表示(yyyymmdd)      
更新時間 utime  int 6       時分(hhmm)        
登録コンピュータ名 mcomputer     varchar 20      
登録ユーザー（Windows） mosuser     varchar 20      
登録ユーザー（ｼｽﾃﾑのﾛｸﾞｲﾝﾕｰｻﾞｰ） msysuser    varchar 20      
登録日 mdate   int 8   
受付日付 wukedt     int 8   
ログインユーザー名 wloginid  userid  userid  userid  int 6       顧客コード       
WEB更新日付 wudate  int 8       西暦表示(yyyymmdd)      
WEB更新時間 wutime  int 4       時分(hhmm)        
最終更新プログラム名 pgnm     varchar 20      更新プログラム名をセット        
連動方向    int 1       1:WtoL 2:LtoW 3:else 9:stop 詳細未定        
更新区分    int 1       1:Create 2:Update 3:Reference 4:Delete 詳細未定     
連動日     int 8       西暦表示(yyyymmdd) 詳細未定     
連動時間    int 4       時分(hhmm) 詳細未定       
処理フラグ   int 1       0:（未処理）  1:処理済　 9:エラー 詳細未定      
*/
        //insert
$sql = "INSERT INTO dt_mail ( ukeno, ptnkb, ukedt, kyacd, mail, dannm, daihyo, renraku, sekinin, kaigi, riyokbnm, seikdl, seifile, siyodl, kyofile, paylmtdt, apptdt, expnocdt, sddae, sdtime, sdret, ucomputer, uosuser, usysuser, udate, utime, mcomputer, mosuser, msysuser, mdate, wukedt, wloginid, wudate, wutime, pgnm )";
$sql = $sql." VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
     


//echo $sql;
        $params = array( $ukeno, $ukeno, 0, $seiurl, '', 0, 0, $ukedt, $nen,$kyacd, $dannm, $dannm2, $daihyo, $renraku, $seikin, $paylmtdt, $login, parent::getUdate(), parent::getUtime() );

        $stmt = sqlsrv_query( $this->conn, $sql, $params );

        if( $stmt === false) {
            $tran = false;
            //break;//exit for
        }
if( $stmt === false ) {
    if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
            print_r($params);
        }
    }
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
