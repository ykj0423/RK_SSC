<?php
@session_start();

$errmsg = "";
//header
$pageTitle = "新規利用者登録[完了]";
include('include/header.php');
include('model/Kyaku.php');
require_once( "func.php" );

/**
 * ReserveKeeperWeb予約システム
 *
 * PHP versions 4
 *
 * @category   公益財団法人神戸市産業振興財団／新規利用者登録[完了]
 * @package    none
 * @author     y.kamijo <y.kamijo@gandg.co.jp>
 * @copyright  2015 G&G Co.ltd.
 * @license    G&G Co.ltd.
 * @version    0.1
**/
if( isset( $_POST['submit'] ) && !empty( $_POST['submit'] ) ){
        
    //$_SESSION['next_page'] = $_POST['next_page'];
    //header( 'location: hitudoku.php' );
    //exit();

}

//利用者情報デシリアライズ
//$Kyaku = unserialize( $_SESSION['Kyaku'] );
//DB再接続
//$Kyaku->connectDb();
//顧客登録
//$Kyaku->add_kyaku();

//echo "test";
$ini = parse_ini_file('config.ini');        
$serverName = $ini['SERVER_NAME'];
$connectionInfo = array( "Database"=>$ini['DBNAME'], "UID"=>$ini['UID'], "PWD"=>$ini['PWD'] );
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn === false ) {           
    die( print_r( sqlsrv_errors(), true));
}

$check = true;
//echo "test";
if( empty( $_POST['dannm'] ) ){
  echo "<span class=\"text-danger\">※利用者名を入力してください。</span>";
  $check = false;
}

if( empty( $_POST['daihyo'] ) ){
  echo "<span class=\"text-danger\">※代表者名を入力してください。</span>";
  $check = false;
}


if( empty( $_POST['renraku'] ) ){
  echo "<span class=\"text-danger\">※連絡者名を入力してください。</span>";
  $check = false;
}


if( empty( $_POST['tel2'] ) ){
  echo "<span class=\"text-danger\">※連絡者TELを入力してください。</span>";
  $check = false;
}

if( empty( $_POST['mail'] ) ){
  echo "<span class=\"text-danger\">※メールアドレスを入力してください。</span>";
  $check = false;
}

if( empty( $_POST['zipcd'] ) ){
  echo "<span class=\"text-danger\">※郵便番号を入力してください。</span>";
  $check = false;
}

if( empty( $_POST['adr1'] ) && empty( $_POST['adr2'] ) ){
  echo "<span class=\"text-danger\">※住所を入力してください。</span>";
  $check = false;
}

if( !$check ){ 

?>
  <br><br><div class="form-group">
      <div class="row mb20">
          <a class="btn btn-default btn-lg" href="javascript:history.back();"><<修正する</a>
        
      </div>
  </div>
<?php
  die();
}

/* 団体名 */
$dannm = $_POST['dannm'];
$dannm2 = "";
$dannmk = NULL;//$_POST['dannmk'];

/* 代表者名 */
$daihyo = $_POST['daihyo'];

/* 連絡者名 */
$renraku = $_POST['renraku'];

/* 電話番号 */
$tel1 = NULL;;
$tel2 = $_POST['tel2'];
$tel2 = mb_convert_kana($_POST['tel2'], "n", "SJIS");

/* FAX番号 */
$fax = $_POST['fax'];
$fax = mb_convert_kana($_POST['fax'], "n", "SJIS");

/* URL */
$url = "";

/* メールアドレス */
$mail = $_POST['mail'];
$mail = mb_convert_kana($_POST['mail'], "a", "SJIS");

/* 郵便番号 */
$zipcd = $_POST['zipcd'];
$zipcd = mb_convert_kana($_POST['zipcd'], "n", "SJIS");

/* 住所 */
$adr1 = $_POST['adr1'];
$adr2 = $_POST['adr2'];;

/* 業種コード */
$gyscd = $_POST['gyscd'];

/* 資本金 */
$sihon = $_POST['sihon'];
$sihon = mb_convert_kana($_POST['sihon'], "n", "SJIS");

/* 従業員数 */
$jygsu= $_POST['jygsu'];
$jygsu = mb_convert_kana($_POST['jygsu'], "n", "SJIS");

//echo "中小企業判断（１）";
/* 中小企業判断（１） */
$kyakb = 1;//一般

if( judge_tyusyo ( $sihon, $gyscd, $jygsu) ){
    $kyakb = 2;//中小企業
}
//echo "中小企業判断（２）";
/* 中小企業判断（２） */
$sql = "select tyusyonm from mt_tyusyo where setkb = 1";

$stmt = sqlsrv_query( $conn, $sql );

if( $stmt === false) {
    //echo $sql;
    return false;
}else{

    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC ) ) {
        
        $tyusyonm = mb_convert_encoding( $row[0] , "utf8", "SJIS" );
        
        if( strpos( $dannm , $tyusyonm ) !== false){
            //文字列が含まれている場合
            $kyakb = 2;//中小企業
            //echo "中小企業".$sql;
            break;
        }

    }

}


$sql = "select tyusyonm from mt_tyusyo where setkb = 2 AND tyusyonm = '".mb_convert_encoding($dannm,"SJIS","utf8")."'";
//echo "中小企業".$sql;
//echo "<br>";
$stmt = sqlsrv_query( $conn, $sql );

if( $stmt === false) {
    //echo $sql;
    //print_r( sqlsrv_errors());
    //return false;
}else{

    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC ) ) {

        $tyusyonm = mb_convert_encoding( $row[0] , "utf8", "SJIS" );
        if( $tyusyonm == trim( $dannm )){
            //文字列が一致する場合
            $kyakb = 2;//中小企業
            //echo "中小企業".$sql;
            break;
        }
    }

}

//echo "後納ドメイン判断";
/* 後納ドメイン判断 */
$kounoukb = 0;

$sql = "select domain,kyakb,kounoukb from mt_domain";

$stmt = sqlsrv_query( $conn, $sql );

if( $stmt === false) {
    return false;
}

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC ) ) {

    if( strpos( $mail, $row[0] ) !== false){    
  
        //文字列が含まれる場合
        $kyakb = $row[1];//1:一般2:中小企業 99:その他
        $kounoukb = $row[2];//1:後納
        break;
    
    }

}

//echo "顧客コード採番";
/* 顧客コード採番 */
$kyacd = 100000;

$sql = "select max(kyacd) from mt_kyaku where kyacd >= ".$kyacd;
//echo "test".$sql."  ";
$stmt = sqlsrv_query( $conn, $sql );

if( $stmt === false) {
    return false;
}

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC ) ) {

    if(!is_null($row[0])){
        $kyacd = intval( $row[0] ) +1; 
    }
 
 }

//echo " kyacd =".$kyacd."  ";
//$adr2="";

/* WEBログインID */
$wloginid = (string)$kyacd;

/* WEBパスワード */
$wpwd = (string)hash('adler32', $kyacd );

/* WEB利用者区分 */
$wuserkb = 1;

/* 登録完了メール送信フラグ */
$sndflg = 0;

/* 備考 */
$biko = "";

/* コンピュータ名 */
$login = $wloginid;

/* WEB最終ログイン日付 */
$wlastlogindt = 0;

/* WEB利用開始日 */
$wsdate = date('Ymd');

/* 更新日付 */
$udate = date('Ymd');
$wudate = date('Ymd');

/* WEB更新時間 */
$utime = date('Hs');
$wutime = date('Hs');

//echo convert_to_SJIS($str);

/* 顧客マスタ登録 */
$sql = "INSERT INTO mt_kyaku(kyacd,dannm,dannm2,dannmk,daihyo,renraku,tel1,tel2,fax,url,mail,zipcd,adr1,adr2,
    gyscd,sihon,jygsu,kyakb,wuserkb,kounoukb,wloginid,wpwd,sndflg,biko,login,udate,utime,wlastlogindt,wsdate,wudate,wutime)";
$sql .= " VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  
$test = array( $kyacd, $dannm, $dannm2, $dannmk, $daihyo, $renraku, $tel1, $tel2, $fax, $url, $mail, $zipcd, $adr1, $adr2,
    $gyscd, $sihon, $jygsu, $kyakb, $wuserkb, $kounoukb, $wloginid, $wpwd, $sndflg, $biko, $login, $udate, $utime, $wlastlogindt, $wsdate, $wudate, $wutime);

$params = array( 
    $kyacd, convert_to_SJIS( $dannm ), convert_to_SJIS( $dannm2 ), convert_to_SJIS( $dannmk ) , convert_to_SJIS( $daihyo ) , convert_to_SJIS( $renraku ), 
    $tel1, $tel2, $fax, $url, $mail, $zipcd, convert_to_SJIS( $adr1 ), convert_to_SJIS( $adr2 ),
    $gyscd, $sihon, $jygsu, $kyakb, $wuserkb, $kounoukb, $wloginid, $wpwd, $sndflg, $biko, $login, $udate, $utime, $wlastlogindt, $wsdate, $wudate, $wutime);
        
$stmt = sqlsrv_query( $conn, $sql, $params);

//echo "顧客マスタ登録"."<br>";
//echo $sql."<br>";
//print_r($params);

if( $stmt === false) {
    //echo "false:".$sql."<br>";
    //print_r($params);
    print_r( sqlsrv_errors()) ;
    return false;
}

//エラーメッセージ
include('include/err.php');


?>
<p class="bg-head text-right">神戸市産業振興センター</p>
    <h1><span class="midashi">|</span>利用者登録[完了]</h1>

    <div class="f120 mb20">
    ご登録が完了いたしました。<br><br>
    下記がお客様のログインIDとパスワードになります。ログインの際、必要となります。 <br>
    <span class="status2">※この画面を印刷するなどしてログインIDとパスワードを保管してください。</span>
    <br><div class="alert alert-info" role="alert" style="width:50%">
          <h3>ログインID は  <?php echo $wloginid; ?>です。</h3>
          <h3>パスワード は  <?php echo $wpwd; ?>です。</h3>
      </div>
    <br>
    <span class="text-danger">※ご注意ください※</span><br>
    ◎ご登録いただいたメールアドレスあてにログインID、パスワードが記載された「利用者登録完了メール」が送付されます。<br>
    もし「利用者登録完了メール」が届かない場合は、メールアドレスの入力間違い、迷惑メールの拒否設定が考えられます。<br>
     ○メールアドレスの入力間違いの場合：<br>
      いったんログイン後、「利用者情報変更」画面でメールアドレスをご確認ください。<br>
      誤りがあれば正しいアドレスに変更し、再登録を行ってください。<br>
     ○迷惑メールの設定について：<br>  設定をご確認のうえ、@kobe-ipc.or.jpドメインの受信許可をしてください。<br>
      設定方法はご契約会社により異なります。<br><br>
    ◎今後、施設の使用をお申し込みの際は、予約受付やお支払に関するメールが送付されますので、メールアドレスをよくご確認ください。<br>
    ◎セキュリティを確保する為、初回ログイン後にパスワードの変更をおすすめします。<br>
    <br>ご不明な点は下記窓口までお問い合わせださい。<br>
    <div style="text-align:center">
    <img src="img/img_02.gif" width="864">
    </div>
    <br><br><br>
    <div class="alert alert-info" role="alert" >
        <p class="lead">ご利用開始はこちらから&gt;&gt;</p>
        <?php $_SESSION['next_page'] = "search.php" ;?>
          <a class="btn btn-warning btn-lg mb20" href="login.php" role="button">ログイン&gt;&gt;</a>
    </div>
    </form>
    </div>
 </div>
</body>
</html>
</html>
<script type="text/javascript">
<!--
function disp(url){
    window.open(url, "window_name", "scrollbars=yes");
}
// -->