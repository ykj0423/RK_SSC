<?php @session_start(); 
/*if(empty($_SESSION[webrk][user][userid])){
	header("Location : top.php");	
}*/
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW,NOARCHIVE">
<title>空き状況</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<link href="css/bootstrap-glyphicons.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/jquery.datetimepicker.js"></script>
<script src="js/custom.js"></script>
<script src="js/search.room.js"></script>
</head>
<body class="container">
<?php
//メニュー
include('session_check.php');
include('include/menu.php');
require_once( "func.php" );
require_once( "model/db.php" );
//print_r($_POST);
/* データベース接続 */
$db = new DB;
$conErr = $db->connect();

if ( !empty( $conErr ) ) { echo $conErr;  die("ただいま、システムメンテナンス中です。<br>ご利用のお客様には大変ご迷惑をおかけいたしまして、 まことに申し訳ございません。<br>メンテナンス終了まで、いましばらくお待ちください。"); } //接続不可時は終了

/* 施設分類の取得 */
$rmcls = $db->select_rmcls( true );

if(!is_array($rmcls)){
  die("ただいま、システムメンテナンス中です。<br>ご利用のお客様には大変ご迷惑をおかけいたしまして、 まことに申し訳ございません。<br>メンテナンス終了まで、いましばらくお待ちください。");
}

/* 検索日付（自至） */
//検索開始日
$today = date( "Y/m/d" );
$rsv_sttdt = date( "Y/m/d", strtotime( "".$today." +14 day" ) );       //会議室申込開始日

//申込期限日
$included_days = 364;

if(date("L")){
  
  $target_day = date("Y")."/02/29";

  if(strtotime($today) <= strtotime($target_day)){
    $included_days = 365; 
  }

}

//申込期限日
$rsv_enddt = date( "Y/m/d", strtotime( "".$today." +".$included_days." day" ) );
$rsv_sttdt_hole = date( "Y/m/d", strtotime( "".$today." +3 month" ) ); //ホール申込開始日
$rsv_before = date( "Y/m/d", strtotime( "".$today." +14 day" ) );      //申込終了日

$cal_year = (int)substr( $rsv_sttdt, 0, 4 );
$cal_month = (int)substr( $rsv_sttdt, 5, 2 );

/*  default  */
//年月ボタン
$calbtn_year = $cal_year;
if($cal_month<10){
  $cal_month = "0"+$cal_month;
}
$calbtn_month = $cal_month;

//カレンダーの開始日、終了日
$sttdt = $rsv_sttdt;
$enddt = date( "Y/m/d", strtotime( "".$sttdt." +13 day" ) );//14日後

/*  検索ボタン押下  */
if( isset( $_POST['calbtn'] ) ){

  $calbtn = $_POST['calbtn'];
  $calbtn_year = (int)substr( $calbtn, 0, 4 );
  $calbtn_month =  (int)substr( $calbtn, 4, 2 );
  if($calbtn_month < 10)
  {
    $calbtn_month = "0".$calbtn_month;
  }
  
  $sttdt = $calbtn_year."/".$calbtn_month."/01";//月初

}else{ 
  
  if( !empty ( $_POST['search_ymd_stt'] ) ){
      $sttdt = $_POST['search_ymd_stt'] ; 
  }

}

//検索終了日
//if( !empty ( $_POST['serch_ymd_end'] ) ){
//    $enddt = $_POST['serch_ymd_end'] ; 
//}else{
    //初期値
    $enddt = date("Y/m/d", strtotime("".$sttdt." +13 day"));
//}

//検索曜日
if( isset( $_POST[ 'yobi' ] )  && ( count( $_POST[ 'yobi' ] ) > 0 ) ){
    //配列代入
    $yobi = &$_POST[ 'yobi' ];
}else{
    //デフォルトではcheck_on
    $yobi = array ( 0, 1, 2, 3, 4, 5, 6 );
}
?>
   <div class="row">
      	<div class="col-xs-6" style="padding:0">
        <h1><span class="midashi">|</span>空き状況</h1>
       </div>
      	<div class="col-xs-6  text-right">
          <span class="f120">現在の時間：　<span id="currentTime"></span></span>
       </div>
    </div>    
    <!--検索条件-->
<div class="row" id="srch">
    <form name="search_form" id="search_form" role="form" method="post" action="<?=$_SERVER["PHP_SELF"]?>">
    <div class="col-xs-8">
      <table id ="rsv_serach" class="table-bordered table-condensed srch" align="center" width="100%">
        <tbody>
          <tr>
            <th class="pt12">施設分類</th>
            <td>

<?php
/* 施設分類の表示 */
if( isset( $_POST[ 'bunrui' ] ) && (count( $_POST[ 'bunrui' ] ) > 0) ){

  //配列代入
  $bunrui = &$_POST[ 'bunrui' ];
   
}else{

  //デフォルトではcheck_on
  $bunrui = array();
  
  if(is_array($rmcls)){
    for ($i = 0; $i < ( count( $rmcls['data'] ) ) ; $i++ ) {
      array_push( $bunrui , $rmcls['data'][$i]['key'] );
    }
  }

}

if(is_array($rmcls)){

  for ($i = 0; $i < ( count( $rmcls['data'] ) ) ; $i++ ) {
      if( ( array_key_exists( $i, $rmcls['data']) ) && in_array ( $rmcls['data'][$i]['key'] , $bunrui )){
          echo "<label class=\"checkbox-inline\" for=\"bunrui". $i ."\"><input type=\"checkbox\" name=\"bunrui[]\" id=\"bunrui".$i."\" value=\"". $rmcls['data'][$i]['key'] ."\" checked>". $rmcls['data'][$i]['value'] . "</label>";
      } else {
          echo "<label class=\"checkbox-inline\" for=\"bunrui". $i ."\"><input type=\"checkbox\" name=\"bunrui[]\" id=\"bunrui".$i."\" value=\"". $rmcls['data'][$i]['key'] ."\">". $rmcls['data'][$i]['value'] . "</label>";
      }
  }

}
?>
            </td>
          </tr>
          <tr>
            <th class="pt12">曜日</th>
            <td>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi7" value = "0" <?php  echo ( in_array ( 0 , $yobi ) )? 'checked' : ''; ?>><div class="col-sun"> 日</div></label>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi1" value = "1" <?php  echo ( in_array ( 1 , $yobi ) )? 'checked' : ''; ?>> 月</label>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi2" value = "2" <?php  echo ( in_array ( 2 , $yobi ) )? 'checked' : ''; ?>> 火</label>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi3" value = "3" <?php  echo ( in_array ( 3 , $yobi ) )? 'checked' : ''; ?>> 水</label>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi4" value = "4" <?php  echo ( in_array ( 4 , $yobi ) )? 'checked' : ''; ?>> 木</label>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi5" value = "5" <?php  echo ( in_array ( 5 , $yobi ) )? 'checked' : ''; ?>> 金</label>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi6" value = "6" <?php  echo ( in_array ( 6 , $yobi ) )? 'checked' : ''; ?>><div class="col-sat"> 土</div></label>
              </div>
            </td>
          </tr>
          <tr>
            <th class="pt12">使用日</th>
            <td>
              <div class="form-inline">
                  <div class=" input-group date">
                    <input type="text" id="date_timepicker_start" name="search_ymd_stt" value="<?php echo $sttdt; ?>" style="width:100px">
                    <span id="sttbtn" class="input-group-addon"></span>
              </div>～
              <div class=" input-group date">
                <input type="text" id="date_timepicker_end" name="serch_ymd_end" value="<?php echo $enddt; ?>" style="width:100px">
                <span  id="endbtn" class="input-group-addon"></span>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="submit" class="btn btn-default " value="検索する >>">
            </td>
          </tr>
        </tbody>
      </table>
    </div><!-- col-xs-8 -->
    </form>
    <div class="col-xs-4">
      <a href="help.html#akijoukyou"  class="btn alert-info" target="window_name" onClick="disp('help.html#akijoukyou')"><li class="glyphicon glyphicon-question-sign" aria-hidden="true">&nbsp;この画面の操作方法についてはこちら>></li></a> <br>
    </div>
  </div><!-- row_end -->
  <hr>
	<!--検索結果-->
  <div id="result">
    <p class="f120">本日(<?php echo $today; ?>)現在、この予約システムにより、会議室、レセプションルームは、<b><?php echo($rsv_sttdt); ?>～<?php echo($rsv_enddt); ?></b>、
      <br>ハーバーホールは<b><?php echo($rsv_sttdt_hole); ?>～<?php echo($rsv_enddt); ?></b>のお申込みが可能です。<br>上記期間以前の使用をご希望の方は、受付までお問い合わせください。
    </p>
    <span class="status2">＊＊ この画面では、ご予約は確保されていません。ご希望の内容を送信後、受付結果をメールでお知らせいたします。＊＊</span>
    <br><br>
    <div class="row mb10">
      <div class="col-xs-7">
        <p>
          [凡例]<br>
          空：予約可
          <span class="selcol" style="padding-left:5px;padding-right:5px">○</span>：選択中
          <span class="dgray" style="padding-left:5px;padding-right:5px">×</span>：予約不可<br>
         朝：9:00～12:00　昼：13:00～17:00　夜:18:00～21:00
        </p>
      </div>
      <div class="text-right  col-xs-5">
        <form name="yoyaku_form" id="yoyaku_form" role="form"  action="input.php" method="post">
          <a class="btn btn-default btn-lg" id="release_select" role="button">選択解除</a>
          <input type='submit' class="btn btn-primary btn-lg ml20" role="button" name="submit_Click" id="submit_Click" value="使用申込へ進む&nbsp;>>">
        </form>
      </div>
    </div>
    <div class="row mb10 text-right clearfix">
    <span class="col-sm-5"></span>
      <input type='submit' class="btn btn-default mr48p prev" href="#" role="button" value="<<前へ"></a>
      <input type='submit' class="btn btn-default mr20 next" href="#" role="button" value="次へ>>"></a>
    </div>
<?php
echo "<table id =\"rsv\" class=\"table table-bordered table-condensed\">";
echo "<tr class=\"head\">";
echo "<th colspan=\"2\" rowspan=\"3\" width=\"300\">施設名</th>";

//対象日を取得
$date_array = get_date_array( $sttdt, $enddt,  $yobi, $today, $rsv_enddt );

/* 対象日の表示 */
include('date.php');

/* 施設分類 */
$table = 'mt_room';
$idNm = "rmcd";
$valNm = "rmnm";
$wh = '';
$jkn1 = "";
$jkn2 = "";

$room = $db->get_web_mroomr( $bunrui , true );//施設区分

for ($i = 0; $i < ( count( $room ) ) ; $i++ ) {

	$rmcd = $room[ $i ][ 'rmcd' ];//施設コード
	$rmnm = mb_convert_encoding($room[ $i ][ 'rmnmw' ], "utf8", "SJIS");//施設名称
	$teiin = ltrim( $room[ $i ][ 'capacity' ], '0' );	//定員
	$weblink = $room[ $i ][ 'weblink' ];  //施設情報
	$asatnk = $room[ $i ][ 'asatnk' ];
	$hirutnk = $room[ $i ][ 'hirutnk' ];
	$yorutnk = $room[ $i ][ 'yorutnk' ];
	$oyakokb = $room[ $i ][ 'oyakokb' ]; 
	$sumrmcd = $room[ $i ][ 'sumrmcd' ]; 

	echo "<tr class=\"dgray\">";
	//施設情報
 	echo "<th rowspan=\"3\"><span class=\"f150\">".$rmnm."</span><br>[定員]".$teiin;
 	echo "<a href=\"".$weblink."\" target=\"_blank\" class=\"btn btn-info btn-xs\" role=\"button\">施設情報を見る&nbsp;&gt;&gt;</a></th>";

	//予約カレンダー
	for ( $timekb = 1; $timekb <= 3 ; $timekb++ ) {

		switch ($timekb) {
			case 1:
				echo "<th>朝</th>";
				$jkn1 = "9:00";
				$jkn2 = "12:00";
				break;
			case 2:
				echo "</tr>";
				echo "<tr class=\"dgray\" >";
				echo "<th>昼</th>";
				$jkn1 = "13:00";
				$jkn2 = "17:00";
				break;
			case 3:
				echo "</tr>";
				echo "<tr class=\"dgray\" >";
				echo "<th>夜</th>";
				$jkn1 = "18:00";
				$jkn2 = "21:00";
				break;  
			default:
				break;
		}

		$rsv_cal = $db->select_ksjknksi( $rmcd, $timekb, str_replace( "/", "", $sttdt ), str_replace( "/", "", $enddt ) );

		for ($k = 0; $k < count( $date_array ) ; $k++) {

		    $usedt = str_replace( "/", "", $date_array[$k]['yyyy'].$date_array[$k]['mm'].$date_array[$k]['dd'] );

		    //空室の場合、選択可能とする
		    if( ( !array_key_exists( $usedt, $rsv_cal['data'] ) ) || ( array_key_exists( $usedt, $rsv_cal['data'] ) && ( $rsv_cal['data'][$usedt] == 0 ) ) ){

				echo "<td class=\"can\"><a id=\"a-".$rmcd.$usedt.$timekb."\">";
				echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt.$timekb."\"></a>";
				echo "<div id=\"data-".$rmcd.$usedt.$timekb."\" data-usedt=\"".$usedt."\" data-yobi=".$k." data-timekb=\"".$timekb."\" data-jkn1=\"".$jkn1."\" data-jkn2=\"".$jkn2."\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" data-tnk=\"".$asatnk."\" data-oyakokb=\"".$oyakokb."\" data-sumrmcd=\"".$sumrmcd."\"  data-teiin=\"".$teiin."\" />";
				echo "</td>";

			}else{
				//満室の場合
				echo "<td>×</td>";
			}

		}//for

	}

	/* 3行ごとにテーブル仕切り（最終行は表示しない） */
	if ( ( ( $i % 3 ) == 2 ) && ( $i <  ( count( $room ) -1 ) ) ) {
	  
	echo "</table>";
	echo "<p class=\"text-right\">";
	echo "<input type='submit' class=\"btn btn-default mr48p prev\" href=\"#\" role=\"button\" value=\"<<前へ\">";
	echo "<input type='submit' class=\"btn btn-default mr20\ next\" href=\"#\" role=\"button\" value=\"次へ>>\">";
	echo "</p>";
	echo "<table  class=\"table table-bordered table-condensed rsv\">";
	echo "<tr class=\"head\">";
	echo "<th colspan=\"2\" rowspan=\"3\" width=\"300\">施設名</th>";

	/* 対象日の表示 */
	include('date.php');

	}

}

?>
    	</table>
    </div>
      <div style="text-align:center">
    <img src="img/img_02.gif" width="864">
    <br><br><br><br>
  </div>
</body>
</html>