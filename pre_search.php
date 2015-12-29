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
//include('include/menu.php');
require_once( "func.php" );
require_once( "model/db.php" );

/* データベース接続 */
$db = new DB;
$conErr = $db->connect();
if ( !empty( $conErr ) ) { echo $conErr;  die(); } //接続不可時は終了

/* 施設分類の取得 */
$table = 'mm_rmcls';
$idNm = "code";
$valNm = "name";
$wh = '';
$rmcls = $db->listTB( $table, $idNm, $valNm,$wh );
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
<?php
include("search_entry.php");
?>

	<!--検索結果-->
    <div id="result">
<div class="col-xs-5">


        </div>
      	<div class="text-right  col-xs-7">
      		<a class="btn btn-lg btn-primary ml20"  href="top.php" role="button">ご利用登録・予約申込はこちら　>></a>

    	  </div>
    	</div>

		<p>
      		[凡例]
      		空：予約可      		
      		<span class="dgray" style="padding-left:5px;padding-right:5px">×</span>：予約不可　
      		<span class="dgray" style="padding-left:5px;padding-right:5px">休</span>：休館日　
      </p>
      <p class="text-right">
	    <input type='submit' class="btn btn-default mr48p prev"  href="#" role="button" value="<<前へ"></a>
      	<input type='submit' class="btn btn-default mr20 next"  href="#" role="button" value="次へ>>"></a>
      </p>
<?php
//TODO design-separatable
echo "<table id =\"rsv\" class=\"table table-bordered table-condensed\">";
echo "<tr class=\"head\">";
echo "<th colspan=\"2\" rowspan=\"3\" width=\"300\">施設名</th>";


$today = date( "Y/m/d" );
$rsv_sttdt = date( "Y/m/d", strtotime( "".$today." +14 day" ) );       //会議室申込開始日
$rsv_enddt = date( "Y/m/d", strtotime( "".$today." +365 day" ) );      //申込期限日

$span_stt="";
$span_end ="";
//対象日を取得
$date_array = get_date_array( $sttdt , $enddt ,  $yobi , $today, $rsv_enddt ) ;


/* 対象日の表示 */
include('date.php');

/* 施設分類 */
$table = 'mt_room';
$idNm = "rmcd";
$valNm = "rmnm";
$wh = '';

//新コード
//$room = $db->get_web_mroomr( $_POST['bldkb'], $bunrui);//施設区分、施設分類
$room = $db->get_web_mroomr( $bunrui );//施設区分

for ($i = 0; $i < ( count( $room ) ) ; $i++ ) {
	
	$rmcd = $room[ $i ][ 'rmcd' ];   			//施設コード
	$rmnm = mb_convert_encoding($room[ $i ][ 'rmnmw' ], "utf8", "SJIS");//施設名称
    $teiin = ltrim( $room[ $i ][ 'capacity' ], '0' );	//定員
    $weblink = $room[ $i ][ 'weblink' ];            //施設情報
	echo "<tr class=\"dgray\">";
    //施設情報
 	echo "<th rowspan=\"3\"><span class=\"f150\">".$rmnm."</span><br>[定員]".$teiin;
 	
    echo "<a href=\"".$weblink."\" target=\"_blank\" class=\"btn btn-primary btn-xs\" role=\"button\">施設情報</a></th>";

    //カレンダー
    //本当は一気に取りたい
    //朝
    echo "<th>朝</th>";
    $mor = $db->select_ksjkntai( $rmcd , 1 ,  str_replace( "/", "", $sttdt ) ,  str_replace( "/", "", $enddt ) );

	for ($k = 0; $k < count( $date_array ) ; $k++) {

		//$usedt = str_replace( "/", "", $sttdt ) + $k ;//仮
		$usedt = str_replace( "/", "", $date_array[$k]['yyyy'].$date_array[$k]['mm'].$date_array[$k]['dd'] );
		
		if( !array_key_exists( $usedt, $mor['data'] ) )
		{
			echo "<td  class=\"can\" id=".$rmcd.$usedt."1\">";
            echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."1\">";
            //各種定数化。
			echo "<div id=\"data-".$rmcd.$usedt."1\" data-usedt=\"".$usedt."\" data-yobi=".$k." data-timekb=\"1\" data-jkn1=\"9:00\" data-jkn2=\"12:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" />";
            echo "</td>";
		}elseif( array_key_exists( $usedt, $mor['data'] ) && ( $mor['data'][$usedt] == 0 ) ){
		//空室の場合
			
			echo "<td  class=\"can\">";
            echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."1\">";
            //各種定数化。
			echo "<div id=\"data-".$rmcd.$usedt."1\" data-usedt=\"".$usedt."\" data-yobi=".$k." data-timekb=\"1\" data-jkn1=\"9:00\" data-jkn2=\"12:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" />";
            echo "</td>";
        
		}else{
		//満室の場合
        
			echo "<td>×</td>";
        
		}

    }

    //昼
    echo "<tr class=\"dgray\" >";
    echo "<th>昼</th>";

    $noon = $db->select_ksjkntai( $rmcd , 2 ,  str_replace( "/", "", $sttdt ) ,  str_replace( "/", "", $enddt ) );

    for ( $k = 0; $k < count( $date_array ) ; $k++ ) {

        //$usedt = str_replace( "/", "", $sttdt ) + $k ;//仮
		$usedt = str_replace( "/", "", $date_array[$k]['yyyy'].$date_array[$k]['mm'].$date_array[$k]['dd'] );

        if( !array_key_exists( $usedt, $noon['data'] ) ){
		
        	echo "<td  class=\"can\">";
            echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."2\">";
            echo "<div id=\"data-".$rmcd.$usedt."2\" data-usedt=\"".$usedt."\" data-timekb=\"2\" data-jkn1=\"13:00\" data-jkn2=\"17:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" />";
            echo "</td>";

		}elseif ( array_key_exists( $usedt, $noon['data'] ) && ( $noon['data'][$usedt] == 0 ) ) {
            //echo "<td  class=\"can\"><a href=\"#\"><img src=\"icon/kara.jpg\"></a></td>";
			echo "<td  class=\"can\">";
            echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."2\">";
            echo "<div id=\"data-".$rmcd.$usedt."2\" data-usedt=\"".$usedt."\" data-timekb=\"2\" data-jkn1=\"13:00\" data-jkn2=\"17:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" />";
            echo "</td>";
        }else{
            echo "<td>×</td>";
        }

    }

    echo "</tr>";

    //夜
    echo "<tr class=\"dgray\"  style=\" border-bottom: 2px solid;\">";
    echo "<th>夜</th>";
    
	//$night = $db->select_ksjkntai( $rmcd , 3 , $sttdt , $enddt );
    $night = $db->select_ksjkntai( $rmcd , 3 ,  str_replace( "/", "", $sttdt ) ,  str_replace( "/", "", $enddt ) );

	for ( $k = 0; $k < count( $date_array ) ; $k++ ) {

        //$usedt = str_replace( "/", "", $sttdt ) + $k ;//仮
		$usedt = str_replace( "/", "", $date_array[$k]['yyyy'].$date_array[$k]['mm'].$date_array[$k]['dd'] );
        
		if( !array_key_exists( $usedt, $night['data'] ) ){
		
			echo "<td  class=\"can\">";
            echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."3\">";
            echo "<div id=\"data-".$rmcd.$usedt."3\" data-usedt=\"".$usedt."\" data-timekb=\"3\" data-jkn1=\"18:00\" data-jkn2=\"21:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" />";
            echo "</td>";	
        }elseif ( array_key_exists( $usedt, $night['data'] ) && ( $night['data'][$usedt] == 0 ) ) {
            //echo "<td  class=\"can\"><a href=\"#\"><img src=\"icon/kara.jpg\"></a></td>";
			echo "<td  class=\"can\">";
            echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."3\">";
            echo "<div id=\"data-".$rmcd.$usedt."3\" data-usedt=\"".$usedt."\" data-timekb=\"3\" data-jkn1=\"18:00\" data-jkn2=\"21:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" />";
            echo "</td>";		
        }else{
            echo "<td>×</td>";
        }

    }

    echo "</tr>";

    /* 3行ごとにテーブル仕切り（最終行は表示しない） */
    if ( ( ( $i % 3 ) == 2 ) && ( $i <  ( count( $room ) -1 ) ) ) {
        
        echo "</table>";
        echo "<p class=\"text-right\">";
		echo "<input type='submit' class=\"btn btn-default mr48p prev\" href=\"#\" role=\"button\" value=\"<<前へ\"></a>";
		echo "<input type='submit' class=\"btn btn-default mr20\ next\" href=\"#\" role=\"button\" value=\"次へ>>\"></a>";
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
<br><br><br>
</body>
</html>