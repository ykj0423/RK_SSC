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
include('session_check.php');
include('include/menu.php');
require_once( "func.php" );
require_once( "model/db.php" );
//print_r($_POST);
/* データベース接続 */
$db = new DB;
$conErr = $db->connect();
if ( !empty( $conErr ) ) { echo $conErr;  die(); } //接続不可時は終了

/* 施設分類の取得 */
$rmcls = $db->select_rmcls();

/* 検索日付（自至） */
//検索開始日
               
$today = date( "Y/m/d" );
$rsv_sttdt = date( "Y/m/d", strtotime( "".$today." +15 day" ) );       //会議室申込開始日
$rsv_enddt = date( "Y/m/d", strtotime( "".$today." +365 day" ) );      //申込期限日
$rsv_sttdt_hole = date( "Y/m/d", strtotime( "".$today." +1 month" ) ); //ホール申込開始日
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
  //$calbtn_month = str_pad( (int)substr( $calbtn, 5, 2 ), 2, 0, STR_PAD_LEFT );
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
            <!--th>使用年月</th>
            <td>
              <div id="cal" class="btn-group" data-toggle="buttons"-->
                
              <?php
                
                /*for ($i = 0; $i < 13; $i++) {
                  
                  if($cal_month > 12){
                    $cal_month = 1;
                    $cal_year = $cal_year + 1;
                  }

                  if( ($cal_year == $calbtn_year) && ($cal_month == $calbtn_month)){
                    echo "<label class=\"btn btn-xs btn-cal active\">";                   
                    echo "<input name=\"calbtn\" value=\"".$cal_year.str_pad($cal_month, 2, 0, STR_PAD_LEFT)."\" type=\"radio\" checked>";
                  }else{
                    echo "<label class=\"btn btn-xs btn-cal\">";
                    echo "<input name=\"calbtn\" value=\"".$cal_year.str_pad($cal_month, 2, 0, STR_PAD_LEFT)."\" type=\"radio\">";
                  }
 
                  if($i == 0){
                    echo $cal_year."年<br>".$cal_month."月</label>";                   
                  }else if($cal_month == 1){
                    echo $cal_year."年<br>".$cal_month."月</label>";                  
                  }else{
                    echo "<br>".$cal_month."月</label>";
                  }

                  $cal_month++;  

                }
                */
              ?>
              <!--/div>
            </td-->
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
            <th class="pt12">日付</th>
            <td>
              <div class="form-inline">
                  <div class=" input-group date">
                    <input type="text" id="date_timepicker_start" name="search_ymd_stt" value="<?php echo $sttdt; ?>" style="width:100px">
                    <span id="sttbtn" class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
              </div>～
              <div class=" input-group date">
                <input type="text" id="date_timepicker_end" name="serch_ymd_end" value="<?php echo $enddt; ?>" style="width:100px">
                <span  id="endbtn" class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
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
	<!--検索結果-->
  <div id="result">
        <p class="f120">本日（<?php echo $today; ?>）現在、<b><?php echo($rsv_sttdt); ?>～<?php echo($rsv_enddt); ?></b>
            (※ﾊｰﾊﾞｰﾎｰﾙは<b><?php echo($rsv_sttdt_hole); ?>～<?php echo($rsv_enddt); ?></b>）のお申し込みが可能です。<br>
            <?php echo($rsv_before); ?>以前の使用をご希望の方は、下記窓口までお問合せください。
        </p>
        <span class="status2">＊＊ この画面では、ご予約は確保されていません。ご希望の内容を送信後、受付結果をメールでお知らせいたします。＊＊</span>
        <br>
		<div class="row mb10">
			<div class="col-xs-5">
			<p class="h4"><div class="h4 selcnt">現在の選択 ： &nbsp;件</div></p>
			</div>
			<div class="text-right  col-xs-7">
				<form name="yoyaku_form" id="yoyaku_form" role="form"  action="input.php" method="post">
 					<a class="btn btn-default btn-lg" id="release_select" role="button">選択解除</a>
					<input type='submit' class="btn btn-primary btn-lg ml20" role="button" name="submit_Click" id="submit_Click" value="予約申込へ進む&nbsp;>>">
				</form>
			</div>
		</div>
		<div class="col-xs-7">
        <p>
          [凡例]<br>
          空：予約可
          <span class="selcol" style="padding-left:5px;padding-right:5px">○</span>：選択中　
            <span class="dgray" style="padding-left:5px;padding-right:5px">×</span>：予約不可　
            <!--span class="dgray" style="padding-left:5px;padding-right:5px">空</span>：窓口にお申し付けください-->　<br>
          朝：9:00～12:00 昼：13:00～17:00　夜:18:00～21:00
        </p>
      </div>
      <p class="text-right">
	    <input type='submit' class="btn btn-default mr48p prev" href="#" role="button" value="<<前へ"></a>
      <input type='submit' class="btn btn-default mr20 next" href="#" role="button" value="次へ>>"></a>
      </p>
<?php
//========================================================================================
//TODO design-separatable
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

//新コード----------------------------------------------------------------------------------------------------
//$room = $db->get_web_mroomr( $_POST['bldkb'], $bunrui);//施設区分、施設分類
$room = $db->get_web_mroomr( $bunrui );//施設区分

for ($i = 0; $i < ( count( $room ) ) ; $i++ ) {
	
  $rmcd = $room[ $i ][ 'rmcd' ];   			//施設コード
	$rmnm = mb_convert_encoding($room[ $i ][ 'rmnmw' ], "utf8", "SJIS");//施設名称
  $teiin = ltrim( $room[ $i ][ 'capacity' ], '0' );	//定員
  $weblink = $room[ $i ][ 'weblink' ];   			//施設情報
  //$tnk = $room[ $i ][ 'tnk' ];
  $asatnk = $room[ $i ][ 'asatnk' ];
  $hirutnk = $room[ $i ][ 'hirutnk' ];
  $yorutnk = $room[ $i ][ 'yorutnk' ];
  $oyakokb = $room[ $i ][ 'oyakokb' ]; 
	$sumrmcd = $room[ $i ][ 'sumrmcd' ]; 

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

      $usedt = str_replace( "/", "", $date_array[$k]['yyyy'].$date_array[$k]['mm'].$date_array[$k]['dd'] );

      if( $db->select_kscal( $usedt , 1 )){
    		//$usedt = str_replace( "/", "", $sttdt ) + $k ;//仮
    		
        if( !array_key_exists( $usedt, $mor['data'] ) ){
     
          echo "<td class=\"can\" id=".$rmcd.$usedt."1\" ><a id=\"a-".$rmcd.$usedt."1\">";
          echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."1\"></a>";
          //各種定数化。
          echo "<div id=\"data-".$rmcd.$usedt."1\" data-usedt=\"".$usedt."\" data-yobi=".$k." data-timekb=\"1\" data-jkn1=\"9:00\" data-jkn2=\"12:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" data-tnk=\"".$asatnk."\" data-oyakokb=\"".$oyakokb."\" data-sumrmcd=\"".$sumrmcd."\" />";
          echo "</td>";
    		
        }elseif( array_key_exists( $usedt, $mor['data'] ) && ( $mor['data'][$usedt] == 0 ) ){

    		  //空室の場合
    			echo "<td class=\"can\"><a id=\"a-".$rmcd.$usedt."1\">";
          echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."1\"></a>";
          //各種定数化。
    			echo "<div id=\"data-".$rmcd.$usedt."1\" data-usedt=\"".$usedt."\" data-yobi=".$k." data-timekb=\"1\" data-jkn1=\"9:00\" data-jkn2=\"12:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" data-tnk=\"".$asatnk."\" data-oyakokb=\"".$oyakokb."\" data-sumrmcd=\"".$sumrmcd."\" />";
          echo "</td>";
            
    		}else{
    		  //満室の場合
    			echo "<td>×</td>";          
    		}
      
      }else{
        echo "<td>×</td>";  
      }
    
    }

    echo "</tr>";

    //昼
    echo "<tr class=\"dgray\" >";
    echo "<th>昼</th>";

    $noon = $db->select_ksjkntai( $rmcd , 2 ,  str_replace( "/", "", $sttdt ) ,  str_replace( "/", "", $enddt ) );

    for ( $k = 0; $k < count( $date_array ) ; $k++ ) {
        
        $usedt = str_replace( "/", "", $date_array[$k]['yyyy'].$date_array[$k]['mm'].$date_array[$k]['dd'] );

        if( $db->select_kscal( $usedt , 2 )){

          if( !array_key_exists( $usedt, $noon['data'] ) ){
  		
    	       echo "<td  class=\"can\"><a id=\"a-".$rmcd.$usedt."2\">";
             echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."2\"></a>";
             echo "<div id=\"data-".$rmcd.$usedt."2\" data-usedt=\"".$usedt."\" data-timekb=\"2\" data-jkn1=\"13:00\" data-jkn2=\"17:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" data-tnk=\"".$hirutnk."\" data-oyakokb=\"".$oyakokb."\" data-sumrmcd=\"".$sumrmcd."\" />";
             echo "</td>";

  		    }elseif ( array_key_exists( $usedt, $noon['data'] ) && ( $noon['data'][$usedt] == 0 ) ) {
              //echo "<td  class=\"can\"><a href=\"#\"><img src=\"icon/kara.jpg\"></a></td>";
              echo "<td  class=\"can\"><a id=\"a-".$rmcd.$usedt."2\">";
              echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."2\"></a>";
              echo "<div id=\"data-".$rmcd.$usedt."2\" data-usedt=\"".$usedt."\" data-timekb=\"2\" data-jkn1=\"13:00\" data-jkn2=\"17:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" data-tnk=\"".$hirutnk."\" data-oyakokb=\"".$oyakokb."\" data-sumrmcd=\"".$sumrmcd."\" />";
              echo "</td>";
          
          }else{
              echo "<td>×</td>";
          }

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

		  $usedt = str_replace( "/", "", $date_array[$k]['yyyy'].$date_array[$k]['mm'].$date_array[$k]['dd'] );
      
      if( $db->select_kscal( $usedt , 3 )){
        
          if($rmcd == 201 ){

                echo "<td>×</td>"; 
            
          }else	if( !array_key_exists( $usedt, $night['data'] ) ){
    		
            echo "<td  class=\"can\"><a id=\"a-".$rmcd.$usedt."3\">";
            echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."3\"></a>";
            echo "<div id=\"data-".$rmcd.$usedt."3\" data-usedt=\"".$usedt."\" data-timekb=\"3\" data-jkn1=\"18:00\" data-jkn2=\"21:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" data-tnk=\"".$yorutnk."\" data-oyakokb=\"".$oyakokb."\" data-sumrmcd=\"".$sumrmcd."\" />";
            echo "</td>";	
            
          }elseif ( array_key_exists( $usedt, $night['data'] ) && ( $night['data'][$usedt] == 0 ) ) {
                //echo "<td  class=\"can\"><a href=\"#\"><img src=\"icon/kara.jpg\"></a></td>";
            echo "<td  class=\"can\"><a id=\"a-".$rmcd.$usedt."3\">";
            echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."3\"></a>";
            echo "<div id=\"data-".$rmcd.$usedt."3\" data-usedt=\"".$usedt."\" data-timekb=\"3\" data-jkn1=\"18:00\" data-jkn2=\"21:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" data-tnk=\"".$yorutnk."\" data-oyakokb=\"".$oyakokb."\" data-sumrmcd=\"".$sumrmcd."\" />";
            echo "</td>";		
            
          }else{
            echo "<td>×</td>";
          }
      
      }else{
          echo "<td>×</td>";  
      }

    }

    echo "</tr>";

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
<br><br><br>
</body>
</html>