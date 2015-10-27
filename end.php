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
<title>予約申込み[完了]　 |  <?php echo $_SESSION['webrk']['sysname']; ?></title>
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

/* データベース接続 */
$db = new DB;
$conErr = $db->connect();
if ( !empty( $conErr ) ) { echo $conErr;  die(); } //接続不可時は終了

$checkkyaku =1 ;
/* 同月9件チェック */
//本当は明細ごとに回さなきゃならない

$meisai_count = $_POST['meisai_count'];

for ( $i = 0; $i < $meisai_count; $i++ ) {
	
	$_POST[ 'usedt'.$i ];
	$checkdt = $_POST[ 'usedt'.$i ];
	$checky = substr($checkdt, 0, 4);
	$checkm = substr($checkdt, 4, 2);

	if( $db->check_monthly_count($checkkyaku,$checky,$checkm) > 9){
		echo $checky."年".$checkm."月に関してはお申し込みの上限を超えています。";
		die();
	}else{
		echo $checky."年".$checkm."月に関してはお申し込みの上限を超えていません。";
	}
	
}

?>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<?php
$serverName = "WEBRK\SQLEXPRESS";
$connectionInfo = array( "Database"=>"RK_SSC_DB", "UID"=>"sa", "PWD"=>"Webrk_2015" );
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn === false ) {
     //die( print_r( sqlsrv_errors(), true));
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

$max_webukeno = (int)sqlsrv_get_field( $stmt, 0);//nullの場合を考慮し、キャストする
$financial_year = str_pad((int)get_financial_year() , 8, "0", STR_PAD_RIGHT);
$webukeno =  $financial_year  +  $max_webukeno + 1;

?>
	

<?php
$login = "test";//$_SESSION['webrk']['user'];
//明細件数の取得
$meisai_count = $_POST['meisai_count'];

/* WEB空室状況（時間帯）更新処理 */
for ( $i = 0; $i < $meisai_count; $i++ ) {
	/*
		（暫定コード）9:00～12:00	13:00～17:00	17:30～21:00	9:00～17:00	13:00～21:00	9:00～21:00
	*/
	$gyo = $i + 1;

	if( $_POST[ 'timekb'.$i ]  == 1 ){
		$stt = 9;
		$k = 11;
	}else if( $_POST[ 'timekb'.$i ]  == 2 ) {
		$stt = 13;
		$k = 16;	
	}else if( $_POST[ 'timekb'.$i ]  == 3 ) {
		$stt = 17;
		$k = 20;
	}
	
	$sql = "SELECT * FROM ks_jkntai WHERE usedt = ".$_POST[ 'usedt'.$i ]." AND rmcd = ".$_POST[ 'rmcd'.$i ] ." AND timekb = ".$_POST[ 'timekb'.$i ];
	$stmt = sqlsrv_query( $conn, $sql );
	$row_count = sqlsrv_has_rows ( $stmt );
	
	if ($row_count === false){
		
		/* 更新処理 */
		for ( $j = $stt; $j <= $k;  $j++) {// 3時間分回す
			
				$sql = "INSERT INTO ks_jkntai (usedt , jikan , rmcd , timekb , ukeno , gyo , login , udate , utime)  VALUES  (?,? ,?,?,?,?,? ,?,?)";

				$params = array( $_POST[ 'usedt'.$i ], $j , $_POST[ 'rmcd'.$i ], $_POST[ 'timekb'.$i ], $webukeno, $gyo, $login, date( "Ymd" ), date( "His" ) );

				$stmt = sqlsrv_query( $conn, $sql, $params);
		
				if( $stmt === false ) {
				
					if( ( $errors = sqlsrv_errors() )  != null) {

						/*foreach( $errors as $error ) {
								echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
								echo "code: ".$error[ 'code']."<br />";
								echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
								print_r ($params);
								echo "<br>";
								//die();
						}*/
						
					}
				
				}

			}
		
	}else{
	
		//echo ("既に予約が埋まっていた場合の処理<br>");
		/* 既に予約が埋まっていた場合の処理 */
		//チェックはks_を見に行く
		//kがうまっていらたweb_ksを元に戻す。
		echo $_POST[ 'usedt'.$i ]."日の予約は申し込めません";
	
	}

}

//echo "RK空室状況（時間貸し、時間帯）更新処理"."<br />";
/* RK空室状況（時間帯）更新処理 */
for ($i = 0; $i < $meisai_count; $i++) {
	
	$gyo = $i + 1;
	
	/*
		（暫定コード）9:00～12:00	13:00～17:00	17:30～21:00	9:00～17:00	13:00～21:00	9:00～21:00
	*/
	if( $_POST[ 'timekb'.$i ]  == 1 ){
		$stt = 9;
		$k = 11;
	}else if( $_POST[ 'timekb'.$i ]  == 2 ) {
		$stt = 13;
		$k = 16;	
	}else if( $_POST[ 'timekb'.$i ]  == 3 ) {
		$stt = 17;
		$k = 20;
	}
	
	//時間貸
	for ($j = $stt; $j <= $k;  $j++) {// 3時間分回す

		$sql = "SELECT * FROM ks_jknksi WHERE usedt = ".$_POST[ 'usedt'.$i ]." AND rmcd = ".$_POST[ 'rmcd'.$i ] ." AND jikan = ".$j." AND rjyokb <> 0";
		$stmt = sqlsrv_query( $conn, $sql );
		$row_count = sqlsrv_has_rows( $stmt );

		if ($row_count === false)
		{
			define('MISHU',4);
			define('YOYAKU',2);
			
			$sql = "INSERT INTO ks_jknksi (usedt , jikan , rmcd , rsignkb, rjyokb , login , udate , utime)  VALUES  (?,?,?,?,?,?,?,?)";
			//使用年月日 時間 施設コード 予約記号区分 予約状態区分 コンピュータ名 更新日付 更新時間
		
			$params = array( $_POST[ 'usedt'.$i ], $j , $_POST[ 'rmcd'.$i ], 4, 2, $login, date( "Ymd" ), date( "His" ) );
			$stmt = sqlsrv_query( $conn, $sql, $params);
			
			if( $stmt === false ) {
				if( ($errors = sqlsrv_errors() ) != null) {
					/*foreach( $errors as $error ) {
						echo "ks_jknksi<br />";
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
						print_r($params);
					}*/
				}
			}
		
		
		
		
		}else{
			//echo "既に埋まっていた場合の処理";		
		}
		
	}
	
			//時間帯
			$sql = "SELECT * FROM ks_jkntai WHERE usedt = ".$_POST[ 'usedt'.$i ]." AND rmcd = ".$_POST[ 'rmcd'.$i ] ." AND timekb = ".$_POST[ 'timekb'.$i ];
			$stmt = sqlsrv_query( $conn, $sql );
			$row_count = sqlsrv_has_rows( $stmt );

			if ($row_count === false){
			
				for ($j = $stt; $j <= $k;  $j++) {// 3時間分回す
				
					$sql = "INSERT INTO ks_jkntai (usedt , jikan , rmcd , timekb , ukeno , gyo , login , udate , utime)  VALUES  (?,? ,?,?,?,?,? ,?,?)";
					
					//いったんWEB受付ナンバーで更新する
					$params = array( $_POST[ 'usedt'.$i ], $j , $_POST[ 'rmcd'.$i ], $_POST[ 'timekb'.$i ], $webukeno, $gyo, $login, date( "Ymd" ), date( "His" ) );
					//print_r($params);
					//echo "<br>";
					
					$stmt = sqlsrv_query( $conn, $sql, $params);
					
					if( $stmt === false ) {
						/*if( ($errors = sqlsrv_errors() ) != null) {
							foreach( $errors as $error ) {
								echo "ks_jkntai<br />";
								echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
								echo "code: ".$error[ 'code']."<br />";
								echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
								die();
							}
						}*/
					}
				}
	
			}else{
				//echo ("既に予約が埋まっていた場合の処理<br>");
				/* 既に予約が埋まっていた場合の処理 */
			}
	
	//}else{
	//	echo ("既に予約が埋まっていた場合の処理<br>");
	//	/* 既に予約が埋まっていた場合の処理 */
	//}
	
}


/* WEB予約データ */
/*$sql = "INSERT INTO web_droomr (Webukeno , ukeno , ukedt , nen , krkb , krmemo , ukecd , nyutncd , ukehkb , kyacd , dannm , dannm2 , dannmk , daihyo , renraku , tel1, tel2 , fax , zipcd , adr1,adr2 , gyscd , sihon , jygsu , kyakb , kaigi , kaigir , naiyo , kbiko , kupdkb , rsbkb , riyokb , login , udate,utime)  VALUES (? , ? , ? , ? , ? , ? , ?, ? , ? , ? , ? , ? , ? , ? , ?, ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ?, ?,?)"; 

$params = array(1,15000016, 20150820, 2015, 0       , "",       1,          1,          1,         10,     "だんたい","だんたい２","カナ","代表者名","連絡者名","0120222221","0120222222","0120222223","655-0023","兵庫県神戸市垂水区清水通","●×ビル5-202",1,0,0,1,"会議名称","会議名称","会議内容","",0,1,2,'webtest',date("Ymd") , date("His"));

$stmt = sqlsrv_query( $conn, $sql, $params);

if( $stmt === false ) {
    if( ($errors = sqlsrv_errors() ) != null) {
        foreach( $errors as $error ) {
            echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
             echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
			die();
        }
    }
}*/


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

//RK顧客データ
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
	//$adr1 =  mb_convert_encoding( $row['adr1'], "SJIS","UTF-8");
	//$adr2 =  mb_convert_encoding( $row['adr2'], "SJIS","UTF-8");
	$adr1 =  $row['adr1'];
	$adr2 =  $row['adr2'];
	$gyscd =  $row['gyscd'];
	$sihon =  $row['sihon'];
	$jygsu =  $row['jygsu'];
	$kyakb =  $row['kyakb'];
}

/*RK予約データ */
//define('UPDATE_NONE',0);
//define('YOYAKU_IPPAN',1);
//echo ("dt_roomr<br>");
$kaigi = mb_convert_encoding( $_POST[ 'kaigi'] , "SJIS","UTF-8");

$sql = "INSERT INTO dt_roomr (ukeno , ukedt , nen , krkb , krmemo , ukecd , nyutncd , ukehkb , kyacd , dannm , dannm2 , dannmk , daihyo , renraku ,
 tel1, tel2 , fax , zipcd , adr1,adr2 , gyscd , sihon , jygsu , kyakb , kaigi , kaigir , naiyo , kbiko , kupdkb , rsbkb , riyokb , login , udate,utime)  
 VALUES (? , ? , ? , ? , ? , ? , ?, ? , ? , ? , ? , ? , ? , ? , ?, ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ? , ?, ?,?)"; 
// 受付番号 受付日付 年度 仮予約区分 仮受付メモ 受付者コード 受付方法区分 顧客コード 団体名 団体名２ 団体カナ名 代表者名 連絡者名 ＴＥＬ１ ＴＥＬ２
//ＦＡＸ 郵便番号 住所１ 住所２ メールアドレス 業種コード 資本金 従業員数 顧客区分 会議名称 内容 顧客備考 顧客更新区分
//予約種別区分 利用目的区分 コンピュータ名 更新日付 更新時間
$params = array($ukeno, date( 'Ymd' ), date( "Y" ), 0 , "", 1,  1,  1,  10,
						$dannm, $dannm2, $dannmk, $daihyo, $renraku, $tel1, $tel2, $fax, $zipcd, $adr1, $adr2, $gyscd, $sihon,
						$jygsu, $kyakb, $kaigi, $kaigi, "", "", 2, 1, $_POST[ 'riyokb' ], $login, date( "Ymd" ) , date("His" ));
//print_r($params);
$stmt = sqlsrv_query( $conn, $sql, $params);

if( $stmt === false ) {
    if( ($errors = sqlsrv_errors() ) != null) {
        /*foreach( $errors as $error ) {
            echo "dt_roomr<br>";
			echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
            echo "code: ".$error[ 'code']."<br />";
            echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
        }*/
    }
}

for ($i = 0; $i < $meisai_count; $i++) {
	
	$gyo = $i + 1;
	
	//$yobi =get_mb_wday($_POST[ 'usedt'.$i ]);
	//$yobi = get_mb_wday($yobi, "SJIS","UTF-8");
	$yobikbn = get_wday($_POST[ 'usedt'.$i ] );
	$weekday = array( "日", "月", "火", "水", "木", "金", "土" );//日本語曜日定義
	$yobi = mb_convert_encoding( $weekday[ $yobikbn ], "SJIS","UTF-8");
	
	$stjkn = str_replace(":","",$_POST[ 'stjkn'.$i ]);//使用開始時間
	$edjkn = str_replace(":","",$_POST[ 'edjkn'.$i ]);//使用終了時間

	$sql = "INSERT INTO dt_roomrmei(ukeno  ,gyo  ,rmcd  ,kyono  ,kyodt  ,usedt  ,yobi  ,yobikb  ,timekb  ,stjkn  ,edjkn  ,hbstjkn  ,hbedjkn  
	,ratekb  ,ratesb  ,zgrt  ,ninzu  ,rmtnk  ,rmentnk  ,rmtukin  ,rmenkin  ,rmkin  ,hzkin  ,rmnykin  ,hznykin  ,synykin  ,candt  ,cankb  ,hkktdt  ,hkdt  ,hkkin  ,kskbn  ,biko  ,tag1  ,tag2  ,tag3  ,login  ,udate  ,utime)
	VALUES  (?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?  ,?)";
	
	//	 VALUES  (15000016,1,11,0,0,20150820,".$yobi.", 3, 1, 900,  1200, 900, 1200 ,  1,0, 100, 0,  4800 , 0 , 4800 ,0 , 4800 ,  0,  4800, 0 ,0 , 0,  0, 0,0,0,0,N'',0,0,0,'webtest',".date('Ymd') .", ". date('His'). ")";
	//受付番号 行番 施設コード 許可番号 許可日付 使用日付 使用日付曜日 使用曜日区分 時間帯区分 使用時間開始 使用時間終了 本番時間開始 本番時間終了
	//料金区分 料金種別 通常増減率 使用人数 施設単価 延長施設単価 施設通常使用金額 施設延長使用金額 施設使用合計金額 付属設備合計金額 
	//施設使用入金金額 付属設備入金金額 償還金入金金額 キャンセル日付
	//キャンセル区分 返還決定日付 返還日付 返還金額 状態データ更新フラグ 備考 付箋1 付箋2 付箋3 コンピュータ名 更新日付 更新時間 

	$params = array($ukeno, $gyo, $_POST[ 'rmcd'.$i ], 0 ,0 , $_POST[ 'usedt'.$i ], $yobi, $yobikbn, $_POST[ 'timekb'.$i ], $stjkn, $edjkn, $stjkn, $edjkn,
	0, 0, 0, $_POST[ 'ninzu'.$i ], 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, "", 0, 0, 0, $login, date( "Ymd" ) , date( "His" ));

	$stmt = sqlsrv_query( $conn, $sql, $params);

	if( $stmt === false ) {
		if( ($errors = sqlsrv_errors() ) != null) {
			/*foreach( $errors as $error ) {
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
				echo "code: ".$error[ 'code']."<br />";
				echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
				print_r($params);
			}*/
		}
	}

}//end_for

//クエリー結果の開放
sqlsrv_free_stmt($result);
//コネクションのクローズ
sqlsrv_close($conn);
?>
	
<!-- main -->
	<div class="row">
      	<div class="col-xs-6" style="padding:0">
        <h1><span class="midashi">|</span>予約申込み[完了]</h1>
       </div>

      	<div class="col-xs-6  text-right">
          <span class="f120">現在の時間：　<span id="currentTime"></span></span>
       </div>
	</div>
<h4>ご予約のお申込を受け付けました。</h4>
<p class="red">※ご注意※　施設利用料のお支払が完了するまで、予約は確定していません。</p>
<p>
	以下の内容でお申込みを受け付けましたが、先着順となりますのでご希望通りに受理できない可能性がございます。<br>
	ご予約結果については、<a href="rsvlist.php">予約照会画面</a>にてご確認下さい。<br>
	なお、下記のアドレス宛にご予約結果のメールを送信しておりますので、ご確認をお願いします。
</p>
<br>
<div class="alert alert-warning" role="alert">WEB受付番号：  <span style="font-size:1.2em"><?php  echo "4-".$webukeno ?></span></div>
<a class="btn btn-default btn-lg logout" href="top.php" role="button">トップページに戻る</a>
<a class="btn btn-default btn-lg logout" href="login.php" role="button">ログアウト</a>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>