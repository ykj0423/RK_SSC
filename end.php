<?php
@session_start();
//if(empty($_SESSION['webrk']['user']['userid'])){/
//	header("Location : top.php");	
//}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW,NOARCHIVE">
<title>予約申込 送信済み　 |  <?php echo $_SESSION['webrk']['sysname']; ?></title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<script src="js/custom.js"></script>
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.datetimepicker.js"></script>
<script src="js/custom.js"></script>
<script>
    jQuery(function () {
		
		//ログアウト時、トップに戻る時のローカルストレージクリア
		$(".logout").click(function(){
			
			var strlist = JSON.parse(localStorage.getItem("sentaku"));
			
			for ( var i = 0; i < strlist.length; i++ ){
					strlist.splice(i, 1);
			}
			
			localStorage.setItem('sentaku', JSON.stringify(strlist));
        });	
	});
 </script>
</head>
<body class="container">
<?php 
require_once( "func.php" );
require_once( "model/db.php" );
require_once("model/Reserve.php");
require_once("model/Seikyu.php");
/* データベース接続 */
$db = new DB;
$conErr = $db->connect();
if ( !empty( $conErr ) ) { echo $conErr;  die(); } //接続不可時は終了

$checkkyaku =1 ;
/* 同月9件チェック */
//本当は明細ごとに回さなきゃならない

$meisai_count = $_POST['meisai_count'];

$serverName = "WEBRK\SQLEXPRESS";
$connectionInfo = array( "Database"=>"RK_SSC_DB", "UID"=>"sa", "PWD"=>"Webrk_2015" );
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn === false ) {
     //die( print_r( sqlsrv_errors(), true));
}

/* --------------------*/
/* RK顧客データ          */
/* --------------------*/
$kyacd = 1;//テスト暫定
$sql = "SELECT * FROM mt_kyaku WHERE kyacd = ".$kyacd;
$stmt = sqlsrv_query( $conn, $sql );

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
	$dannm =  $row['dannm'];
	$dannm2 =  $row['dannm2'];
	$dannmk =  $row['dannmk'];
	$daihyo =  $row['daihyo'];
	$renraku =  $row['renraku'];
	$tel1 =  $row['tel1'];
	$tel2 =  $row['tel2'];
	$fax =  $row['fax'];
	$zipcd =  $row['zipcd'];
	$adr1 =  $row['adr1'];
	$adr2 =  $row['adr2'];
	$gyscd =  $row['gyscd'];
	$sihon =  $row['sihon'];
	$jygsu =  $row['jygsu'];
	$kyakb =  $row['kyakb'];
	$kounoukb = $row['kounoukb']; //後納区分
    $login = $row['wloginid'];    //ログイン
}


/* --------------------*/
/*  WEB受付№取得処理  */
/* --------------------*/
$sql = "SELECT MAX(ukeno) AS webukeno FROM dt_roomr";
$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
     //die( print_r( sqlsrv_errors(), true));
}

if( sqlsrv_fetch( $stmt ) === false) {
     //die( print_r( sqlsrv_errors(), true));
}

$webukeno = (int)sqlsrv_get_field( $stmt, 0) + 1 ;//nullの場合を考慮し、キャストする
//$financial_year = str_pad((int)get_financial_year() , 8, "0", STR_PAD_RIGHT);
//$webukeno =  $financial_year  +  $max_webukeno + 1;
/*$max_webukeno = (int)sqlsrv_get_field( $stmt, 0);//nullの場合を考慮し、キャストする
$financial_year = str_pad((int)get_financial_year() , 8, "0", STR_PAD_RIGHT);
$webukeno =  $financial_year  +  $max_webukeno + 1;
*/
/* --------------------*/
/*  RK受付№取得処理  */
/* --------------------*/
//echo ("RK受付№取得処理<br>");
$sql = "SELECT MAX(ukeno)  AS ukeno FROM dt_roomr";
$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
     //die( print_r( sqlsrv_errors(), true));
}

if( sqlsrv_fetch( $stmt ) === false) {
     //die( print_r( sqlsrv_errors(), true));
}

$max_ukeno = (int)sqlsrv_get_field( $stmt, 0);//nullの場合を考慮し、キャストする
$ukeno =  $max_ukeno + 1;

/*----------------------------------------------------*/
$login = "test";//$_SESSION['webrk']['user'];//暫定

//明細件数の取得
$meisai_count = $_POST['meisai_count'];

$ks_list = array();

for ($i = 0 ; $i < $meisai_count; $i++) {
	$ks_list[] = array('gyo' => $_POST[ 'gyo'.$i ], 'usedt' => $_POST[ 'usedt'.$i ], 'rmcd' => $_POST[ 'rmcd'.$i ], 'timekb' => $_POST[ 'timekb'.$i ] );
}

//空室時間貸、時間帯
$Reserve = new Reserve();
$ret = $Reserve->reserve( $ks_list,  $ukeno , $login );
if(!$ret){
	die("reserveエラー");
}

/*RK予約データ */
//define('UPDATE_NONE',0);
//define('YOYAKU_IPPAN',1);
//echo ("dt_roomr<br>");
$kaigi = mb_convert_encoding( $_POST[ 'kaigi'] , "SJIS","UTF-8");

$sql = "INSERT INTO dt_roomr (ukeno, ukedt, nen, krkb, krmemo, ukecd, ukehkb, kyacd, 
	dannm, dannm2, dannmk, daihyo, renraku, tel1, tel2, fax, zipcd, adr1, adr2, mail, gyscd, sihon, jygsu, kyakb, 
	kaigi, naiyo, kbiko, kupdkb, rsbkb, riyokb, login, 
	udate, utime)  
 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ? , ? , ? , ? , ?, ? , ? , ? , ? , ? , ? , ? , ? , ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?,?)"; 
// 受付番号 受付日付 年度 仮予約区分 仮受付メモ 受付者コード 受付方法区分 顧客コード 団体名 団体名２ 団体カナ名 代表者名 連絡者名 ＴＥＬ１ ＴＥＬ２
//ＦＡＸ 郵便番号 住所１ 住所２ メールアドレス 業種コード 資本金 従業員数 顧客区分 会議名称 内容 顧客備考 顧客更新区分
//予約種別区分 利用目的区分 コンピュータ名 更新日付 更新時間

$params = array($ukeno, date( 'Ymd' ), date( "Y" ), 1 , "", 1,  5,  $kyacd ,
				$dannm, $dannm2, $dannmk, $daihyo, $renraku, $tel1, $tel2, $fax, $zipcd, $adr1, $adr2, "", $gyscd, $sihon, $jygsu, $kyakb, 
				$kaigi, "", "", 1, 1, $_POST[ 'riyokb' ], $login, date( "Ymd" ) , date( "His" ));

//print_r($params);

$stmt = sqlsrv_query( $conn, $sql, $params);

if( $stmt === false ) {
    if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "dt_roomr<br>";
			echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
        }
    }
}

echo("meisai_start");

for ($i = 0; $i < $meisai_count; $i++) {
	
	$gyo = $i + 1;
	
	//$yobi =get_mb_wday($_POST[ 'usedt'.$i ]);
	//$yobi = get_mb_wday($yobi, "SJIS","UTF-8");
	//管理者留保
	$yobikbn = get_wday($_POST[ 'usedt'.$i ] );
	$weekday = array( "日", "月", "火", "水", "木", "金", "土" );//日本語曜日定義
	$yobi = mb_convert_encoding( $weekday[ $yobikbn ], "SJIS","UTF-8");
	
	$stjkn = str_replace(":","",$_POST[ 'stjkn'.$i ]);//使用開始時間
	$edjkn = str_replace(":","",$_POST[ 'edjkn'.$i ]);//使用終了時間

	$sql = "INSERT INTO dt_roomrmei(ukeno  ,gyo  ,rmcd  ,kyono  ,kyodt  ,usedt  ,yobi  ,yobikb  ,timekb  ,stjkn  ,edjkn  ,hbstjkn  ,hbedjkn  
	,ratekb  ,ratesb  ,zgrt  ,ninzu  ,rmtnk  ,rmentnk  ,rmtukin  ,rmenkin  ,rmkin  ,hzkin  ,rmnykin  ,hznykin  ,synykin  ,candt  ,cankb  ,hkktdt  ,hkdt  ,
	hkkin  ,kskbn  ,biko  ,tag1  ,tag2  ,tag3  ,login  ,udate  ,utime)
	VALUES  (? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,? ,?,? ,? ,? ,? ,? ,? ,? ,?)";
	
	//	 VALUES  (15000016,1,11,0,0,20150820,".$yobi.", 3, 1, 900,  1200, 900, 1200 ,  1,0, 100, 0,  4800 , 0 , 4800 ,0 , 4800 ,  0,  4800, 0 ,0 , 0,  0, 0,0,0,0,N'',0,0,0,'webtest',".date('Ymd') .", ". date('His'). ")";
	//受付番号 行番 施設コード 許可番号 許可日付 使用日付 使用日付曜日 使用曜日区分 時間帯区分 使用時間開始 使用時間終了 本番時間開始 本番時間終了
	//料金区分 料金種別 通常増減率 使用人数 施設単価 延長施設単価 施設通常使用金額 施設延長使用金額 施設使用合計金額 付属設備合計金額 
	//施設使用入金金額 付属設備入金金額 償還金入金金額 キャンセル日付
	//キャンセル区分 返還決定日付 返還日付 返還金額 状態データ更新フラグ 備考 付箋1 付箋2 付箋3 コンピュータ名 更新日付 更新時間 
//$_POST[ 'ninzu'.$i ]
	$params = array($ukeno, $gyo, $_POST[ 'rmcd'.$i ], 0 ,0 , $_POST[ 'usedt'.$i ], $yobi, $yobikbn, $_POST[ 'timekb'.$i ], $stjkn, $edjkn,$stjkn, $edjkn,
		1, 0, 0, 0, 0, 0, 0, 0, 
	 	0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 1, "", 0, 0, 0, $login, date( "Ymd" ) , date( "His" ));

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

}//end_for
echo("sei_list_before");
/* 請求データ作成 */
$sei_list = array();

for ($i = 0 ; $i < $meisai_count; $i++) {
	
	//略称を取得する必要がある
	$rmnm = mb_convert_encoding( $_POST[ 'rmnm'.$i ], "SJIS","UTF-8");
	
	$sei_list[] = array(
		'gyo' => $_POST[ 'gyo'.$i ], 'usedt' => $_POST[ 'usedt'.$i ], 'yobi' => $_POST[ 'yobi'.$i ], 'yobikb' => $_POST[ 'yobikb'.$i ],
		'rmcd' => $_POST[ 'rmcd'.$i ], 'rmnmr' => $rmnm, 'stjkn' => $_POST[ 'stjkn'.$i ], 'edjkn' => $_POST[ 'edjkn'.$i ], 
		'hbstjkn' => $_POST[ 'hbstjkn'.$i ], 'hbedjkn' => $_POST[ 'hbedjkn'.$i ], 'piano' => $_POST[ 'piano'.$i ],
		'rmkin'=> $_POST[ 'rmkin'.$i ], 'hzkin'=>$_POST[ 'hzkin'.$i ]);
	
}
echo("sei_list");
print_r($sei_list);
$Seikyu = new Seikyu();
$ret = $Seikyu->seikyu( $ukeno, $ukedt, $kyacd, $sei_list );
echo("sei_list_after");

if(!$ret){
	die("セイキュウエラー");
}

//クエリー結果の開放
sqlsrv_free_stmt($result);
//コネクションのクローズ
sqlsrv_close($conn);
?>
	
<!-- main -->
	<div class="row">
      	<div class="col-xs-6" style="padding:0">
        <h1><span class="midashi">|</span>予約申込 送信済み</h1>
       	</div>
      	<div class="col-xs-6  text-right">
          <span class="f120">現在の時間：　<span id="currentTime"></span></span>
       </div>
	</div>
	<h4 class="status2"> ご希望の内容は、システムへ送信されましたが、まだ受付できておりません。<br>お申し込みが成立したかどうかは、改めてメールでお知らせします。 </h4>
	
	<br>
	<li>受付は先着順となります。</li>
  	<li>お申し込みの受付状況は、<a href="rsvlist.php">予約照会画面</a>でもご覧いただけます。</li>
  	<br>
	<div class="alert alert-info" role="alert">
	お問い合わせ番号：  <span style="font-size:1.2em"><?php echo $webukeno ?></span></div>
	<p>・ハーバーホールのご使用については、必ずこちらの<a href="#">「ご利用案内」</a>をご確認ください。</p>
	<!--p>・展示場のご使用については、事前にこちらの<a href="#">「使用計画書」</a>をご提出ください。</p><br-->
	<a class="btn btn-default btn-lg" href="top.php" role="button">トップページに戻る</a>
	<a class="btn btn-primary btn-lg" href="search.php" role="button"><small>続けて申し込む場合・・・</small>空き状況へ >> </a>
	<a class="btn btn-primary btn-lg logout" href="login.php" role="button">ログアウト</a>

    <a href="http://localhost/rk_ssc/mailtpl/regist.txt"  target="_blank" onClick="disp('lhttp://localhost/rk_ssc/mailtpl/regist.txt')">
    	    <li class="glyphicon glyphicon-question-sign" aria-hidden="true">受付メールサンプル</li>
        </a> 
    </div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	</body>
</html>
<script type="text/javascript">
<!--
function disp(url){
	window.open(url, "window_name", "scrollbars=yes");
}
// -->