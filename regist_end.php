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
        
    $_SESSION['next_page'] = $_POST['next_page'];
    header( 'location: hitudoku.php' );
    exit();

}

//利用者情報デシリアライズ
//$Kyaku = unserialize( $_SESSION['Kyaku'] );
//DB再接続
//$Kyaku->connectDb();
//顧客登録
//$Kyaku->add_kyaku();


$ini = parse_ini_file('config.ini');        
$serverName = $ini['SERVER_NAME'];
$connectionInfo = array( "Database"=>$ini['DBNAME'], "UID"=>$ini['UID'], "PWD"=>$ini['PWD'] );
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn === false ) {           
    die( print_r( sqlsrv_errors(), true));
}

/* 団体名 */
$dannm　= $_POST['dannm'];
$dannm2　= "";
$dannmk = $_POST['dannmk'];

/* 代表者名 */
$daihyo　= $_POST['daihyo'];

/* 連絡者名 */
$renraku　= $_POST['renraku'];

/* 電話番号 */
$tel1　= "";
$tel2　= $_POST['tel2'];

/* FAX番号 */
$fax　= $_POST['fax'];

/* URL */
$url　= "";

/* メールアドレス */
$mail　= $_POST['mail'];

/* 郵便番号 */
$zipcd = $_POST['zipcd'];

/* 住所 */
$ad1 = $_POST['ad1'];
$ad2 = "";

/* 業種コード */
$gyscd = $_POST['gyscd'];

/* 資本金 */
$shinon = $_POST['shinon'];

/* 業種コード */
$jygsu　= $_POST['jygsu']

/* 中小企業判断（１） */
$kyakb = 1;　//一般

if( judge_tyusyo ( $shinon　, $gyscd, $jygsu　) ){
    $kyakb = 2;　//中小企業
}

/* 中小企業判断（２） */
$sql = "select tyusyonm from mt_tyusyo where setkb = 1";

$stmt = sqlsrv_query( $conn, $sql );

if( $stmt === false) {
    return false;
}

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC ) ) {
    
    $tyusyonm = trim( $row[0] );

    if( strpos( $dannm , $tyusyonm ) !== false){
        //文字列が含まれている場合
        $kyakb = 2;　//中小企業
        break;
    }

}

$sql = "select tyusyonm from mt_tyusyo where setkb = 2 AND tyusyonm = '".$dannm."'";

$stmt = sqlsrv_query( $conn, $sql );

if( $stmt === false) {
    return false;
}

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC ) ) {
    
    if( trim( $row[0] ) == trim( $dannm ))｛

        //文字列が一致する場合
        $kyakb = 2;　//中小企業
        break;
    
    }

}

/* 後納ドメイン判断 */
$sql = "select domain,kyakb,kounoukb from mt_tyusyo";

$stmt = sqlsrv_query( $conn, $sql );

if( $stmt === false) {
    return false;
}

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC ) ) {

    if( strpos( $mail, $row[0] ) !== false　){    
  
        //文字列が含まれる場合
        $kyakb = $row[1];　//1:一般　2:中小企業 99:その他
        $kounoukb = $row[2];　//1:後納
        break;
    
    }

}

/* 顧客コード採番 */
$kyaku_cd = 100000;

$sql = "select max(kyacd) from mt_kyaku where kyacd >= ".$kyaku_cd;

$stmt = sqlsrv_query( $conn, $sql );

if( $stmt === false) {
    return false;
}

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC ) ) {
    $kyaku_cd = intval( $row[0] ) + 1;
}

/* WEBログインID */
$wloginid = (string)$kyaku_cd;

/* WEBパスワード */
$wpwd = (string)hash('adler32', $kyaku_cd );

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

echo convert_to_SJIS($str);

/* 顧客マスタ登録 */
$sql = "　INSERT INTO mt_kyaku(kyacd,dannm,dannm2,dannmk,daihyo,renraku,tel1,tel2,fax,url,mail,zipcd,adr1,adr2,
    gyscd,sihon,jygsu,kyakb,wuserkb,kounoukb,wloginid,wpwd,sndflg,biko,login,udate,utime,wlastlogindt,wsdate,wudate,wutime)";
$sql .= " VALUES　(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
  
$test = array( $kyacd, $dannm, $dannm2, $dannmk, $daihyo, $renraku, $tel1, $tel2, $fax, $url, $mail, $zipcd, $adr1, $adr2,
    $gyscd, $sihon, $jygsu, $kyakb, $wuserkb, $kounoukb, $wloginid, $wpwd, $sndflg, $biko, $login, $udate, $utime, $wlastlogindt, $wsdate, $wudate, $wutime);
print_r($test);

$params = array( 
    $kyacd, convert_to_SJIS( $dannm ), convert_to_SJIS( $dannm2 ), convert_to_SJIS( $dannmk ) , convert_to_SJIS( $daihyo ) , convert_to_SJIS( $renraku ), 
    $tel1, $tel2, $fax, $url, $mail, $zipcd, convert_to_SJIS( $adr1 ), convert_to_SJIS( $adr2 ),
    $gyscd, $sihon, $jygsu, $kyakb, $wuserkb, $kounoukb, $wloginid, $wpwd, $sndflg, $biko, $login, $udate, $utime, $wlastlogindt, $wsdate, $wudate, $wutime);

        
$stmt = sqlsrv_query( $this->conn, $sql, $params);

if( $stmt === false) {
    echo "false:".$sql."<br>";
    print_r($params);
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
          <h3>ログインID は　 <?php echo $Kyaku->get_wloginid(); ?>です。</h3>
          <h3>パスワード は　 <?php echo $Kyaku->get_wpwd(); ?>です。</h3>
      </div>
	<br>
	<span class="text-danger">※ご注意ください※</span><br>
    ◎ご登録いただいたメールアドレスあてにログインID、パスワードが記載された「利用者登録完了メール」が送付されます。<br>
    もし「利用者登録完了メール」が届かない場合は、メールアドレスの入力間違い、迷惑メールの拒否設定が考えられます。<br>
    　○メールアドレスの入力間違いの場合：<br>
    　　いったんログイン後、「利用者情報」画面でメールアドレスをご確認ください。<br>
    　　誤りがあれば正しいアドレスに変更し、再登録を行ってください。<br>
    　○迷惑メールの設定について：<br>　　設定をご確認のうえ、@kobe-ipc.or.jpドメインの受信許可をしてください。<br>
    　　設定方法はご契約会社により異なります。<br><br>
	◎今後、施設の使用をお申し込みの際は、予約受付やお支払に関するメールが送付されますので、メールアドレスをよくご確認ください。<br>
    ◎セキュリティを確保する為、初回ログイン後にパスワードの変更をおすすめします。<br>
    <a href="http://localhost/rk_ssc/mailtpl/member.txt"><small>受付メールサンプル</small></a><br>
    <!-- もし「利用者登録完了メール」が届かなかった場合、メールアドレスの記載ミス等が考えられますので、<br>
      下記までお問い合わせださい。<br><br>
      ＊＊ お問合せ窓口  TEL:078-360-3200 （お問い合わせ時間：9:00～17:00）＊＊-->
    <br>ご不明な点は下記窓口までお問い合わせださい。<br>
    <div class="alert alert-success" role="alert">
	<h4>＊＊ お問い合わせ窓口＊＊</h4><h3>TEL:078-360-3200 お問い合わせ時間：9:00～17:00</h3> ※登録状況を確認するにあたり、「ログインID」をお知らせください。
	</div>
    </div>    
	<div class="alert alert-info" role="alert" >
		<p class="lead">ご利用開始はこちらから>></p>
		<!--a class="btn btn-primary btn-lg" href="login.php" role="button">空き状況・予約申込　>></a-->
        <form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="submit" name="submit" id="submit" value="空き状況・予約申込　" class="btn btn-primary btn-lg">
            <input type="hidden" name="next_page" value="search.php" class="btn btn-primary btn-lg">
        </form>
		<!--a class="btn btn-primary btn-lg" href="login.html" role="button">予約照会　>></a-->
		<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <!--a class="btn btn-primary btn-lg" href="login.php" role="button">利用者情報変更　>></a-->
            <input type="submit" name="submit" id="submit" value="利用者情報変更　" class="btn btn-primary btn-lg">
            <input type="hidden" name="next_page" value="member_top.php" class="btn btn-primary btn-lg">
        </form>
	</div>
    </form>
        <a href="http://localhost/rk_ssc/mailtpl/member.txt"  target="_blank" onClick="disp('lhttp://localhost/rk_ssc/mailtpl/member.txt')">
            <li class="glyphicon glyphicon-question-sign" aria-hidden="true">受付メールサンプル</li>
        </a> 
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