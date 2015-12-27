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
<title>予約申込 送信済み　 |  <?php //echo $_SESSION['webrk']['sysname']; ?></title>
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
include('session_check.php');
require_once( "func.php" );
require_once( "model/db.php" );
require_once("model/Reserve.php");
//include("model/Seikyu.php");
//include("model/Seikyu.php");
//require_once("model/Seikyu.php");

/* データベース接続 */
$db = new DB;
$conErr = $db->connect();
if ( !empty( $conErr ) ) { echo $conErr;  die(); } //接続不可時は終了

$checkkyaku =1 ;
//本当は明細ごとに回さなきゃならない

$meisai_count = $_POST['meisai_count'];

$ini = parse_ini_file('config.ini');        
$serverName = $ini['SERVER_NAME'];
$connectionInfo = array( "Database"=>$ini['DBNAME'], "UID"=>$ini['UID'], "PWD"=>$ini['PWD'] );
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn === false ) {
    // die( print_r( sqlsrv_errors(), true));
}

/* --------------------*/
/* RK顧客データ          */
/* --------------------*/
$kyacd = $_SESSION['kyacd'];
$sql = "SELECT * FROM mt_kyaku WHERE kyacd = ".$kyacd;
$stmt = sqlsrv_query( $conn, $sql );

while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

	$dannm =  $row['dannm'];
	$dannm2 =  $row['dannm2'];
	$dannmk = NULL;//     $row['dannmk'];
	$daihyo =  $row['daihyo'];
	$renraku =  $row['renraku'];
	$tel1 =  $row['tel1'];
	$tel2 =  $row['tel2'];
	$fax =  $row['fax'];
	$url =  $row['url'];
	$mail =  $row['mail'];	 
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
//$sql = "SELECT MAX(ukeno) AS webukeno FROM dt_roomr";
//$stmt = sqlsrv_query( $conn, $sql);
//if( $stmt === false ) {
     //die( print_r( sqlsrv_errors(), true));
//}

//if( sqlsrv_fetch( $stmt ) === false) {
     //die( print_r( sqlsrv_errors(), true));
//}

//$webukeno = (int)sqlsrv_get_field( $stmt, 0) + 1 ;//nullの場合を考慮し、キャストする

/* --------------------*/
/*  RK受付№取得処理  */
/* --------------------*/
//echo ("RK受付№取得処理<br>");
//$sql = "SELECT MAX(ukeno)  AS ukeno FROM dt_roomr";
$nen1 = date("y");
$nen2 = date("y") +1;

$sql = "SELECT MAX(ukeno)  AS ukeno FROM dt_roomr where ukeno >= ".$nen1."000000 and ukeno <".$nen2."000000";
$stmt = sqlsrv_query( $conn, $sql);
if( $stmt === false ) {
     die( print_r( sqlsrv_errors(), true));
}
//echo "ukeno== ".$sql;
if( sqlsrv_fetch( $stmt ) === false) {
     //die( print_r( sqlsrv_errors(), true));
}

$max_ukeno = (int)sqlsrv_get_field( $stmt, 0);//nullの場合を考慮し、キャストする
$ukeno =  $max_ukeno + 1;

/*----------------------------------------------------*/
$login = $_SESSION['wloginid'];

//明細件数の取得
$meisai_count = $_POST['meisai_count'];

$ks_list = array();

for ($i = 0 ; $i < $meisai_count; $i++) {
	if( isset( $_POST[ 'rmcd'.$i ] )&& ( !empty( $_POST[ 'rmcd'.$i ] ) ) ){
		$ks_list[] = array('gyo' => $_POST[ 'gyo'.$i ], 'usedt' => $_POST[ 'usedt'.$i ], 'rmcd' => $_POST[ 'rmcd'.$i ], 'timekb' => $_POST[ 'timekb'.$i ] );
	}
}

//空室時間貸、時間帯
$revflg = true;

$notice = "";

$Reserve = new Reserve();
$revflg = $Reserve->reserve( $ks_list,  $ukeno , $login );
if(!$revflg){
	$notice = "時間差ですでにほかの予約が受け付けられました。お手数ですが、ほかの施設をご検討ください。";
}

if($revflg){
	/*RK予約データ */

	//受付日
	$ukedt = date( 'Ymd' );
	//年
	$nen = date( "Y" );

	//仮予約区分
	$krkb = 1;

	//後納であれば予約
	if( $kounoukb ==1){//後納であればセットしない
		$krkb = 0;
	}

	if( $kyakb == 99 ){//内部であればセットしない
		$krkb = 0;
	}

	//仮予約メモ
	$krmemo = "";

	//受付者コード
	$ukecd　= 9999;//WEB予約

	//受付方法
	$ukehkb= 98;//WEB予約

	//ホール区分
	$holekb =0;//暫定

	//会議名称
	$kaigi = "";

	if(isset($_POST[ 'kaigi'])){
		$kaigi = mb_convert_encoding( $_POST[ 'kaigi'] , "SJIS","UTF-8");
	}

	//内容
	$naiyo = "";

	if(isset($_POST[ 'naiyo'])){
		$naiyo = mb_convert_encoding( $_POST[ 'naiyo'] , "SJIS","UTF-8");
	}

	//顧客備考
	$kbiko="";

	//当日利用責任者
	$sekinin = "";

	if(isset($_POST['sekinin'])){
		 $sekinin = mb_convert_encoding( $_POST[ 'sekinin'] , "SJIS","UTF-8");
	}

	//顧客更新区分
	$kupdkb = 1;//1:更新する

	//予約種別区分
	$rsbkb = 1;//1:一般、2:業務予約、3:使用不可

	//利用目的区分
	$riyokb = 0;

	if(isset($_POST['riyokb'])){
		$riyokb = $_POST['riyokb'];
	}

	//納付期限
	$nen = substr( $ukedt, 0, 4 );
	$m = substr( $ukedt, 4, 2 );
	if( $m < 10 ) { $m = '0'.$m; }
	$d = substr( $ukedt, 6, 4 );
	if( $d < 10 ) { $d = '0'.$d; }

	$date_ukedt = strtotime( $nen.'-'.$m.'-'.$d );

	$paylmtdt = date('Ymd', strtotime(' +9 days', $date_ukedt));

	if( $kounoukb ==1){//後納であればセットしない
		$paylmtdt = 0;
	}

	if( $kyakb == 99 ){//内部であればセットしない
		$paylmtdt = 0;
	}

	//失効区分
	$expkb = 0;

	//失効予告日
	$expnocdt = 0;

	//失効日
	$expdt = 0;

	//施設使用合計金額
	$trmkin = 0;//暫定

	//付属使用合計金額
	$thzkin = 0;//暫定

	//総合計使用料
	$tkin = 0;//暫定

	//WEB予約区分
	$wrkkb = 1;

	//管理項目
	$wloginid = $login;
	$udate = date( "Ymd" );
	$wudate = date( "Ymd" );
	$utime = date( "His" );
	$wutime = date( "His" );

	$ukecd = 9999;

	/*$sql = "INSERT INTO dt_roomr (ukeno, ukedt, nen, krkb, krmemo,ukecd,ukehkb,kyacd,
		dannm,dannm2,dannmk,daihyo,renraku,tel1,tel2,fax,url,mail,zipcd,adr1,adr2,gyscd,sihon,jygsu,kyakb,
		kounoukb,holekb,kaigi,naiyo,kbiko,sekinin,kupdkb,rsbkb,riyokb,paylmtdt,expkb,expnocdt,expdt,
		trmkin,thzkin,tkin,login,udate,utime,wrkkb,wloginid,wudate,wutime)";
	$sql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

	$params = array($ukeno, $ukedt, $nen, $krkb, $krmemo, $ukecd, $ukehkb, $kyacd,
		$dannm, $dannm2, $dannmk, $daihyo, $renraku, $tel1, $tel2, $fax, $url, $mail, $zipcd, $adr1, $adr2, $gyscd, $sihon, $jygsu, $kyakb,
		$kounoukb, $holekb, $kaigi,$naiyo, $kbiko, $sekinin, $kupdkb, $rsbkb, $riyokb, $paylmtdt, $expkb, $expnocdt, $expdt,
		$trmkin, $thzkin, $tkin, $login, $udate, $utime, $wrkkb, $wloginid, $wudate, $wutime);


	$stmt = sqlsrv_query( $conn, $sql, $params);

	if( $stmt === false ) {
	    if( ($errors = sqlsrv_errors() ) != null) {
	        foreach( $errors as $error ) {
	            echo "dt_roomr<br>".$sql;
	            print_r($params);
	            echo "<br>";
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
	            echo "code: ".$error[ 'code']."<br />";
	            echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
	            die();
	        }
	    }
	}*/

	//echo("meisai_start");

	$gyo = 0;
	$trmkin=0;
	$thzkin=0;
	$tkin = $tkin + $trmkin + $thzkin;
	$holekb = 0;//ホール区分

	for ($i = 0; $i < $meisai_count; $i++) {
		
		if( isset( $_POST[ 'rmcd'.$i ] )&& ( !empty( $_POST[ 'rmcd'.$i ] ) ) ){
		
			$gyo++;

			//施設コード
			$rmcd = $_POST[ 'rmcd'.$i ];
			
			//ホールであればホール区分設定
			if($rmcd==301){
				$holekb = 1;
			}

			//許可番号
			$kyono = 0;

			//許可日
			$kyodt = 0;

			//申請書発行日
			$shindt = 0;

			//許可申請書発行日
			$shindt = 0;
			
			//許可書ダウンロードURL
			$kyourl = "";

			//許可書ファイル名
			$kyofile = "";

			//許可書ダウンロード不可	
			$kyofbd = 0;

			//使用日付
			$usedt = $_POST[ 'usedt'.$i ];

			//曜日区分
			$yobikb = get_wday( $usedt );
			
			//曜日
			$weekday = array( "日", "月", "火", "水", "木", "金", "土" );//日本語曜日定義
			$yobi = mb_convert_encoding( $weekday[ $yobikb ], "SJIS","UTF-8");
			
			//時間帯区分
			$timekb = $_POST[ 'timekb'.$i ];

			//使用開始時間
			$stjkn = str_replace(":","",$_POST[ 'stjkn'.$i ]);
			
			//使用終了時間
			$edjkn = str_replace(":","",$_POST[ 'edjkn'.$i ]);

			//準備開始時間
			$jstjkn_h = 0;
			$jstjkn_m = 0;
			
			if(isset($_POST[ 'jnstjkn_h'.$i ] )){
				$jstjkn_h =  $_POST[ 'jnstjkn_h'.$i ];
			}
			
			if(isset($_POST[ 'jnstjkn_m'.$i ] )){
				$jstjkn_m =  $_POST[ 'jnstjkn_m'.$i ];
			}

			$jnstjkn = format_db_jkn( $jstjkn_h ,  $jstjkn_m );

			//準備終了時間
			$jnedjkn_h = 0;
			$jnedjkn_m = 0;
			
			if(isset($_POST[ 'jnedjkn_h'.$i ] )){
				$jnedjkn_h =  $_POST[ 'jnedjkn_h'.$i ];
			}
			
			if(isset($_POST[ 'jedjkn_m'.$i ] )){
				$jnedjkn_m =  $_POST[ 'jedjkn_m'.$i ];
			}

			$jnedjkn = format_db_jkn( $jnedjkn_h ,  $jnedjkn_m );

			//本番開始時間
			//$hbstjkn_h = 0;
			//$hbstjkn_m = 0;

			/*if(isset($_POST[ 'hbstjkn_h'.$i ] )){
				$hbstjkn_h =  $_POST[ 'hbstjkn_h'.$i ];
			}
			
			if(isset($_POST[ 'hstjkn_m'.$i ] )){
				$hbstjkn_m =  $_POST[ 'hbstjkn_m'.$i ];
			}

			$hbstjkn = format_db_jkn( $hbstjkn_h ,  $hbstjkn_m );*/

			//本番終了時間
			/*$hbedjkn_h = 0;
			$hbedjkn_m = 0;
			
			if(isset($_POST[ 'hbedjkn_h'.$i ] )){
				$hedjkn_h =  $_POST[ 'hbedjkn_h'.$i ];
			}
			
			if(isset($_POST[ 'hedjkn_m'.$i ] )){
				$hbedjkn_m =  $_POST[ 'hbedjkn_m'.$i ];
			}

			$hbedjkn = format_db_jkn( $hbedjkn_h ,  $hbedjkn_m );*/
			//ホール以外
			$hbstjkn = str_replace(":","",$_POST[ 'stjkn'.$i ]);
			$hbedjkn = str_replace(":","",$_POST[ 'edjkn'.$i ]);


			//撤去開始時間
			$tkstjkn_h = 0;
			$tkstjkn_m = 0;
			
			if(isset($_POST[ 'tkstjkn_h'.$i ] )){
				$tkstjkn_h =  $_POST[ 'tkstjkn_h'.$i ];
			}
			
			if(isset($_POST[ 'tkstjkn_m'.$i ] )){
				$tkstjkn_m =  $_POST[ 'tkstjkn_m'.$i ];
			}

			$tkstjkn = format_db_jkn( $tkstjkn_h ,  $tkstjkn_m );

			//撤去終了時間
			$tkedjkn_h = 0;
			$tkedjkn_m = 0;
			
			if(isset($_POST[ 'tkedjkn_h'.$i ] )){
				$tkedjkn_h =  $_POST[ 'tkedjkn_h'.$i ];
			}
			
			if(isset($_POST[ 'tkedjkn_m'.$i ] )){
				$tkedjkn_m =  $_POST[ 'tkedjkn_m'.$i ];
			}

			$tkedjkn = format_db_jkn( $tkstjkn_h ,  $tkstjkn_m );

			//営利目的区分
			$comlkb = 0;

			if(isset($_POST[ 'comlkb'.$i ] )){

				if( $_POST[ 'comlkb'.$i ] == 1 ){
					$comlkb = 1;	
				}	

			}
			
			//入場料・受講料区分
			$feekb = 0;

			if(isset($_POST[ 'feekb'.$i ] )){

				if( $_POST[ 'feekb'.$i ] == 1 ){
					$feekb = 1;	
				}	

			}
			
			//料金区分・増減率
			$ratekb = 1;//一般
			$zgrt = 100;
			
			//（注意）本番開始、本番終了時間がセットされていなければ、準備撤去（料金区分:2）とみなす
			if( $hstjkn = 0 && $hedjkn = 0 ){
			
				$ratekb = 2;
				$zgrt　= 50;
			
			}else{

				//営利目的の場合は、料金区分：3
				if( ( $comlkb == 1 ) && ( $feekb == 1 ) ){
					
					$ratekb = 3;
					$zgrt　= 150;
				
				}

			}

			//料金種別
			$ratesb = 0;//未使用

			//使用人数
			$ninzu = 0;

			if( isset ( $_POST[ 'ninzu'.$i ] ) ){
				$ninzu = $_POST[ 'ninzu'.$i ];
			}

			//施設単価
			$rmtnk = $_POST[ 'rmtnk'.$i ] ;
			$rmentnk = $_POST[ 'rmentnk'.$i ] ;
			$rmtukin = $_POST[ 'rmtukin'.$i ] ;
			$rmenkin = $_POST[ 'rmenkin'.$i ] ;
			$rmkin = $_POST[ 'rmkin'.$i ] ;
			$hzkin = $_POST[ 'hzkin'.$i ] ;
			$trmkin = $rmkin;
			$thzkin = $hzkin;
//echo " tkin=".$tkin."<br>";
			$tkin = $tkin + $trmkin + $thzkin;

//echo " trmkin=".$trmkin."<br>";
//echo " thzkin=".$thzkin."<br>";
//echo "tkin=".$tkin."<br>";


			/*$sql = "SELECT tnk, entnk FROM mt_rmtnk WHERE rmcd = ".$rmcd." AND kyakb = ".$kyacd." AND ratesb = 0 AND stjkn = ".$stjkn." AND edjkn = ".$edjkn;
		        
		    $stmt = sqlsrv_query( $conn, $sql );
		    
		    if( $stmt === false) {
		    	echo "mt_rmtnk";
		    	echo $sql;
		    	die( print_r( sqlsrv_errors(), true) );
			}

		 	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

		        $rmtnk = $row['tnk'];		//通常単価
		        $rmentnk = $row['entnk'];	//延長単価

		    } */

		    //通常金額
		    //$rmtukin = $rmtnk;

		    //延長金額
		    //$rmenkin = $rmentnk;

		    //施設使用合計金額
		    //$rmkin = intval( $rmtukin ) + intval( $rmenkin );
		    //$rmkin =  $rmtukin  + $rmenkin;

			//ピアノ区分
			$pianokb = $_POST[ 'piano'.$i ];
			//if(isset($_POST[ 'pianokb'.$i ])){
			//	$pianokb = $_POST[ 'pianokb'.$i ];
			//}

		    //付属設備合計金額
		    /*$hzkin = 0;

		    if( $pianokb == 1 ){
		    	
		    	if( $kyakb == 1 ){
		    		
		    		$hzkin = 13000;

		    	}else if( $kyakb == 2 ){

					$hzkin = 6500;
		    	
		    	}
			
			}*/

			//入金額・償還金
			$rmnykin = 0;
			$hznykin = 0;
			$synykin = 0;

			//キャンセル日付
			$candt = 0;
			
			//キャンセル区分
			$cankb = 0;

			//返還決定日付
			$hkktdt = 0;

			//返還日付
			$hkdt = 0;

			//返還金額
			$hkkin = 0;
			
			//状態データ更新フラグ
			$kskbn = 0;
			
			//間仕切り
			$partkb = 0;

			if( isset($_POST['partkb'.$i])){
				$partkb = $_POST['partkb'.$i];
			}

			//備考
			if( isset($_POST['biko'.$i])){
				$biko = $_POST[ 'biko'.$i ];
			}

			if( isset($_POST['partkb'.$i])){

				if( $partkb == 1 ){
					$biko .= "P閉める";
				}

				if( $partkb == 0 ){
					$biko .= "P開ける"; 
				}

			}
			
			$biko = mb_convert_encoding($biko, "SJIS","UTF-8");

			//付箋1,2,3
			$tag1 = 0;
			$tag2 = 0;
			$tag3 = 0;

			//WEB予約状態区分
			$wrsvkb = 1;
			
			if( $kounoukb ==1){
			
				$wrsvkb = 2;//予約

			}else{

				$wrsvkb = 3;//仮予約

			}
			if($kyakb==99){//内部
				$wrsvkb = 2;//予約	
			}

			//予約変更日
			$rsvchgdt = 0;
			
			//管理項目	
			$wloginid = $login;
			$udate = date( "Ymd" );
			$wudate = date( "Ymd" );
			$utime = date( "His" );
			$wutime = date( "His" );
			$pgnm = "";

			$sql = "INSERT INTO dt_roomrmei
				(ukeno, gyo, rmcd, kyono, kyodt, shindt, kyourl, kyofile, kyofbd, usedt, yobi, yobikb,
				timekb,stjkn,edjkn,jnstjkn,jnedjkn,hbstjkn,hbedjkn,tkstjkn,tkedjkn,
				ratekb,ratesb,zgrt,ninzu,rmtnk,rmentnk,rmtukin,rmenkin,rmkin,hzkin,rmnykin,hznykin,synykin,
				candt,cankb,hkktdt,hkdt,hkkin,kskbn,biko,tag1,tag2,tag3,
				wrsvkb,rsvchgdt,comlkb,feekb,pianokb,partkb,login,udate,utime,ukedt,wloginid,wudate,wutime,pgnm)";
		    
		    $sql .= "VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

		    $params = array($ukeno, $gyo, $rmcd, $kyono, $kyodt, $shindt, $kyourl, $kyofile, $kyofbd, $usedt, $yobi, $yobikb,
				$timekb, $stjkn, $edjkn, $jnstjkn, $jnedjkn, $hbstjkn, $hbedjkn, $tkstjkn, $tkedjkn,
				$ratekb, $ratesb, $zgrt, $ninzu, $rmtnk, $rmentnk, $rmtukin, $rmenkin, $rmkin, $hzkin, $rmnykin, $hznykin, $synykin,
				$candt, $cankb, $hkktdt, $hkdt, $hkkin, $kskbn, $biko, $tag1, $tag2, $tag3,
				$wrsvkb, $rsvchgdt, $comlkb, $feekb, $pianokb, $partkb, $login, $udate, $utime, $ukedt, $wloginid, $wudate, $wutime, $pgnm );
		 
			$stmt = sqlsrv_query( $conn, $sql, $params);

			if( $stmt === false ) {
				if( ($errors = sqlsrv_errors() ) != null) {
					/*foreach( $errors as $error ) {
						echo $sql."<br />";
						print_r($params);
						echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
						echo "code: ".$error[ 'code']."<br />";
						echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
						print_r($params);
					}*/
				}
			}

			/* 付属設備 */
			$hzgyo = 0;

			if($pianokb ==1 ){

				//付属設備行番
				$hzgyo++;

				//付属設備コード
				$hzcd = 1006;

				//付属設備名称
				$hznmr = mb_convert_encoding("ｸﾞﾗﾝﾄﾞﾋﾟｱﾉ", "SJIS", "UTF-8");

				//単位区分
				$tanikb = 3;

				//数量
				$stsu = 1;

				//単価
				$sttnk = 6500;
				
				//金額
				$stkin = intval( $sttnk ) * intval( $stsu );

				$sql = "INSERT INTO dt_huzor(ukeno, gyo, hzgyo, hzcd, hznmr, tanikb, stsu, sttnk, stkin, login, udate, utime, wudate, wutime)";
				$sql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

				$params = array($ukeno, $gyo, $hzgyo, $hzcd, $hznmr, $tanikb, $stsu, $sttnk, $stkin, $login, date( "Ymd" ) , date( "His" ), date( "Ymd" ) , date( "His" ));

				$stmt = sqlsrv_query( $conn, $sql, $params);

				if( $stmt === false ) {
					if( ($errors = sqlsrv_errors() ) != null) {
						foreach( $errors as $error ) {
							echo $sql;
							print_r($params);
							echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
							echo "code: ".$error[ 'code']."<br />";
							echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
							print_r($params);
						}
					}
				}

			}



			//管理者留保


		}
	}//end_for

	$sql = "INSERT INTO dt_roomr (ukeno, ukedt, nen, krkb, krmemo,ukecd,ukehkb,kyacd,
		dannm,dannm2,dannmk,daihyo,renraku,tel1,tel2,fax,url,mail,zipcd,adr1,adr2,gyscd,sihon,jygsu,kyakb,
		kounoukb,holekb,kaigi,naiyo,kbiko,sekinin,kupdkb,rsbkb,riyokb,paylmtdt,expkb,expnocdt,expdt,
		trmkin,thzkin,tkin,login,udate,utime,wrkkb,wloginid,wudate,wutime)";
	$sql .= " VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

	$params = array($ukeno, $ukedt, $nen, $krkb, $krmemo, $ukecd, $ukehkb, $kyacd,
		$dannm, $dannm2, $dannmk, $daihyo, $renraku, $tel1, $tel2, $fax, $url, $mail, $zipcd, $adr1, $adr2, $gyscd, $sihon, $jygsu, $kyakb,
		$kounoukb, $holekb, $kaigi,$naiyo, $kbiko, $sekinin, $kupdkb, $rsbkb, $riyokb, $paylmtdt, $expkb, $expnocdt, $expdt,
		$trmkin, $thzkin, $tkin, $login, $udate, $utime, $wrkkb, $wloginid, $wudate, $wutime);


	$stmt = sqlsrv_query( $conn, $sql, $params);

	if( $stmt === false ) {
	    if( ($errors = sqlsrv_errors() ) != null) {
	        foreach( $errors as $error ) {
	            echo "dt_roomr<br>".$sql;
	            print_r($params);
	            echo "<br>";
				echo "SQLSTATE: ".$error[ 'SQLSTATE']."<br />";
	            echo "code: ".$error[ 'code']."<br />";
	            echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
	            die();
	        }
	    }
	}

}


/* 請求データ作成 */
include("model/Seikyu.php");

if($revflg){//echo "test1";

	$Seikyu = new Seikyu();
	//echo "test1";
	//$ukeno = 700;
	$ukedt = date('Ymd');//20151214;
	//$kyacd = 1; 

	$list = array();

	for ($i = 0 ; $i < $meisai_count; $i++) {

		if( isset( $_POST[ 'rmcd'.$i ] )&& ( !empty( $_POST[ 'rmcd'.$i ] ) ) ){
			
			/* 施設略称 */
			$rmnmr = "";
			$sql = "SELECT rmnmr FROM mt_room WHERE rmcd = ".$_POST[ 'rmcd'.$i ];

			$stmt = sqlsrv_query( $conn, $sql);

			while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
		    	$rmnmr = $row['rmnmr'];                                   
		    }
		
			//施設単価
			$tnk = 0;
			$kin = 0;

			$sql = "SELECT tnk FROM mt_rmtnk WHERE rmcd = ".$_POST[ 'rmcd'.$i ]." AND kyakb = ".$kyacd." AND ratesb = 0 AND stjkn = ".$_POST[ 'stjkn'.$i ]." AND edjkn = ".$_POST[ 'edjkn'.$i ];
		        
		    $stmt = sqlsrv_query( $conn, $sql );
		    
		    if( $stmt === false) {
		    	//echo "mt_rmtnk";
		    	//echo $sql;
		    	//die( print_r( sqlsrv_errors(), true) );
			}

		 	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

		        $tnk = $row['tnk'];	//通常単価
		        $kin = $row['tnk'];	//金額

		    }  

			$list[] = array(
				'gyo' => $_POST[ 'gyo'.$i ], 'usedt' => $_POST[ 'usedt'.$i ], 'yobi' => '月', 'yobikb' => $_POST[ 'yobikb'.$i ],
				'rmcd' => $_POST[ 'rmcd'.$i ], 'rmnmr' => $rmnmr, 'stjkn' => $_POST[ 'stjkn'.$i ], 'edjkn' => $_POST[ 'edjkn'.$i ], 
				'hbstjkn' => $_POST[ 'hbstjkn'.$i ], 'hbedjkn' => $_POST[ 'hbedjkn'.$i ], 
				'pianokb' => $_POST[ 'piano'.$i ],'tnk' => $tnk, 
				'rmkin'=> $_POST[ 'rmkin'.$i ], 'hzkin'=>$_POST[ 'hzkin'.$i ]);
			

		}
	
	}
	//echo "_POST";
	//print_r($_POST);
	//echo "請求";
	//print_r($list);
	$Seikyu->seikyu( $ukeno, $ukedt, $kyacd, $list );

}

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
<?php	
	if(empty($notice)){
		$notice = "ご希望の内容は、システムへ送信されましたが、まだ受付できておりません。<br>お申し込みが成立したかどうかは、改めてメールでお知らせします。";
	}
?>
	<h4 class="status2"><?php echo $notice; ?></h4>
	
	<br>
	<li>受付は先着順となります。</li>
  	<li>お申し込みの受付状況は、<a href="rsvlist.php">予約照会画面</a>でもご覧いただけます。</li>
  	<br>
<?php if( $revflg ){ ?>
	<div class="alert alert-info" role="alert">
	お問い合わせ番号：  <span style="font-size:1.2em"><?php echo $ukeno ?></span></div>
<?php if( $holekb == 1 ){ ?>	
	<p>・ハーバーホールのご使用については、必ずこちらの<a href="#">「ご利用案内」</a>をご確認ください。</p>
<?php } ?>
<?php } ?>	
	<!--p>・展示場のご使用については、事前にこちらの<a href="#">「使用計画書」</a>をご提出ください。</p><br-->
	<a class="btn btn-default btn-lg" href="top.php" role="button">トップページに戻る</a>
	<a class="btn btn-primary btn-lg" href="search.php" role="button"><small>続けて申し込む場合・・・</small>空き状況へ >> </a>
	<a class="btn btn-primary btn-lg logout" href="login.php" role="button">ログアウト</a>
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