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
<script>
    jQuery(function () {

		//$("#submit_Click").prop("disabled", false);
		//$('#submit_Click').attr('disabled', false);
		/* 予約件数の復元 */		
		var strlist = new Array(8);
		//strlist = JSON.parse( localStorage.getItem("sentaku") );//選択リスト
		//$(".selcnt").text("現在の選択 ： " + strlist.length + "件");
		$(".selcnt").text("現在の選択 ： 0 件");
        
        jQuery('#date_timepicker_start').datetimepicker({
            format: 'Y/m/d',
            lang: 'ja',
            //startDate: new Date(),
            //defaultDate: new Date(),
            onShow: function (ct) {
                this.setOptions({
                    maxDate: jQuery('#date_timepicker_end').val() ? jQuery('#date_timepicker_end').val() : false
                })
            },
            timepicker: false
        });
		//初期値は？
		jQuery('#date_timepicker_end').datetimepicker({
            format: 'Y/m/d',
            lang: 'ja',
            onShow: function (ct) {
                this.setOptions({
                    minDate: jQuery('#date_timepicker_start').val() ? jQuery('#date_timepicker_start').val() : false
                })
            },
            timepicker: false
        });

		//現在の選択件数の更新
		//$(".selcnt").text("現在の選択 ： " + strlist.length + "件");
		
		//前へボタン
		$('.prev').click(function() {
			
			//postされる値にパラメータをセット
			var stt = $('#date_timepicker_start').val();
			var sttDate = new Date( stt );
			var calc = -14;
			
			for (var i = 1; i < 8; i++) {
			
				if($("#yobi" + i).prop('checked')) {
				}else{
					calc--;
				}
			
			}
			
			var calcDate = new Date(sttDate.getTime() + calc*24*60*60*1000);//開始日
			var calc_yyyy = calcDate.getFullYear();
			var calc_mm = calcDate.getMonth()+1 ;
			var calc_dd = calcDate.getDate();
			
			if (calc_mm < 10) {
				calc_mm = '0' + calc_mm
			}

			if (calc_dd < 10) {
				calc_dd = '0' + calc_dd;
			}
			
			var sttdt = calc_yyyy + "/" + calc_mm + "/" + calc_dd;

			//var sttdt = calcDate.getFullYear() + "/" + (calcDate.getMonth()+1) + "/" + calcDate.getDate();
			//$('#date_timepicker_start').attr({'value': sttdt });
			
			//やり方ダサすぎる
			calc = 0;
			
			for (var j = 0; j < 8 ; j++) {
			
				for (var i = 1; i < 8; i++) {
			
					if($("#yobi" + i).prop('checked')) {
						
						calc++;
						
						if(　calc　>　12 ) {
							break;
						}
					
					}
				
				}
				
				if(　calc　>　12 ) {
					break;
				}
			
			}
			
			var calcDate = new Date( calcDate.getTime() + calc*24*60*60*1000 );//開始日 + calc
			
			var calc_yyyy = calcDate.getFullYear();
			var calc_mm = calcDate.getMonth()+1 ;
			var calc_dd = calcDate.getDate();
			
			if (calc_mm < 10) {
				calc_mm = '0' + calc_mm
			}

			if (calc_dd < 10) {
				calc_dd = '0' + calc_dd;
			}

			//var enddt = calcDate.getFullYear() + "/" + (calcDate.getMonth()+1) + "/" + calcDate.getDate();

			var enddt = calc_yyyy + "/" + calc_mm + "/" + calc_dd;

			$('#date_timepicker_start').attr({'value': sttdt });
			$('#date_timepicker_end').attr({'value': enddt });
			$( '#search_form' ).submit();

		});

		//次へボタン
		$('.next').click(function() {
			
			//postされる値にパラメータをセット
			var stt = $('#date_timepicker_end').val();			
			var sttDate = new Date( stt );
			var calc = 1;
			
			for (var i = 1; i < 8; i++) {
			
				if($("#yobi" + i).prop('checked')) {
					break;
				}else{
					calc++;
				}
			
			}

			//チェックしたら増えていく
			var calcDate = new Date( sttDate.getTime() + calc*24*60*60*1000 );//開始日
			var calc_yyyy = calcDate.getFullYear();
			var calc_mm = calcDate.getMonth()+1 ;
			var calc_dd = calcDate.getDate();
			
			if (calc_mm < 10) {
				calc_mm = '0' + calc_mm
			}

			if (calc_dd < 10) {
				calc_dd = '0' + calc_dd;
			}
			
			var sttdt = calc_yyyy + "/" + calc_mm + "/" + calc_dd;
			
			sttDate = new Date( sttdt );
			
			calc=0;
			
			for (var j = 0; j < 8 ; j++) {
			
				for (var i = 1; i < 8; i++) {
			
					if($("#yobi" + i).prop('checked')) {
						
						calc++;
						
						if(　calc　>　12 ) {
							break;
						}
					
					}
				
				}
				
				if(　calc　>　12 ) {
					break;
				}
			
			}

			var calcDate = new Date( sttDate.getTime() + calc*24*60*60*1000 );//開始日
			var calc_yyyy = calcDate.getFullYear();
			var calc_mm = calcDate.getMonth()+1 ;
			var calc_dd = calcDate.getDate();
			
			if (calc_mm < 10) {
				calc_mm = '0' + calc_mm
			}

			if (calc_dd < 10) {
				calc_dd = '0' + calc_dd;
			}

			var enddt = calc_yyyy + "/" + calc_mm + "/" + calc_dd;
			
			$('#date_timepicker_start').attr({'value': sttdt });			
			$('#date_timepicker_end').attr({'value': enddt });
			$( '#search_form' ).submit();

		});

		/* 選択状態格納リスト */
        var strlist = new Array();

		/* 予約状態の復元 */		
		var objData = JSON.parse(localStorage.getItem("sentaku"));//選択リスト
		if( objData != null){
			for ( var i=0; i < objData.length; i++ ){
				var  lnkstr = objData[i]['key'];
				var imgstr = lnkstr.replace('a-', 'img-');
				$("#" + imgstr).attr('src', 'icon/sentaku.png');
			}
		}
		/* 空室・選択クリック時 */
        $("a").click(function () {

			/* 予約状態の復元 */
			//var strlist = JSON.parse(localStorage.getItem("sentaku"));
			//var strlist = array();
			var lnkstr = $(this).attr("id");
            //var tdstr = lnkstr.replace('a-', '');
            var imgstr = lnkstr.replace('a-', 'img-');
            var datastr = lnkstr.replace('a-', 'data-');
            var usedt = $("#" + datastr).attr('data-usedt');	//使用日
			var yobi = $("#" + datastr).attr('data-yobi');		//使用日
            var rmcd = $("#" + datastr).attr('data-rmcd');		//施設コード
            var rmnm = $("#" + datastr).attr('data-rmnm');		//施設名
            var timekb = $("#" + datastr).attr('data-timekb');	//時間帯区分
            var jkn1 = $("#" + datastr).attr('data-jkn1');		//時間（自）
            var jkn2 = $("#" + datastr).attr('data-jkn2');		//時間（至）
            var src = $("#" + imgstr).attr('src');
			
			if (src == 'icon/kara.jpg') {	//空室選択時

                $("#" + imgstr).attr('src', 'icon/sentaku.png');
                //オブジェクトからJSONに直して格納する
                var data = {
                    key: lnkstr,
                    usedt: usedt,
					timekb: timekb,
                    rmcd: rmcd,
                    rmnm: rmnm,
					yobi: yobi,
                    jkn1: jkn1,
                    jkn2: jkn2,
					ninzu: 0,
                    value: 1
                }
                strlist.push(data);
                //選択中のtdの背景色変更
        //$("#"+ tdstr ).css('background-color', '#f4f984');
                //var list = JSON.parse(localStorage.getItem("sentaku"));//選択リスト
				//9件/月にすべきか
				for ( var i=0; i < strlist.length; i++ ){
					var wkey = strlist[i]['key'].slice(4, 10);			
					//alert(wkey);				
				}
				
				//if(strlist.length>9){
				//	alert("一度に9枠を超えるお申し込みは受け付けできません");
				//}else{
					localStorage.setItem('sentaku', JSON.stringify(strlist));
				//}
            } else {	//選択解除時
				$("#" + imgstr).attr('src', 'icon/kara.jpg');
                strlist.some(function (v, i) {
                    if (v.key == lnkstr) strlist.splice(i, 1); //key:lnkstrの要素を削除
                });
                localStorage.setItem('sentaku', JSON.stringify(strlist));
			}

			//現在の選択件数の更新
			$(".selcnt").text("現在の選択 ： " + strlist.length + "件");
		});

		//選択解除ボタン押下時
		$("#release_select").click(function(){
			
			var wklist = JSON.parse(localStorage.getItem("sentaku"));//ワークリスト
			
			for ( var i=0; i < wklist.length; i++ ){
				
				//画像差し替え
				var  wkey = wklist[i]['key'];			
				var imgstr = wkey.replace('a-', 'img-');			
				$("#" + imgstr).attr('src', 'icon/kara.jpg');
				
				//strlistから一つ一つ要素を削除してゆく
				strlist.some(function (v, i) {
					if (v.key == wkey) strlist.splice(i, 1); //key:lnkstrの要素を削除
				});
				//strlistの更新
				localStorage.setItem('sentaku', JSON.stringify(strlist));
				
			}
			//ローカルストレージstrlistクリア
			//localStorage.removeItem('sentaku', JSON.stringify(strlist));
			//現在の選択件数の更新
			$(".selcnt").text("現在の選択 ： " + strlist.length + "件");
        });

		//フォーム送信時
		$('#yoyaku_form').submit(function()
		{
			//$(this).attr('id');
			//if($(this).attr("id") == "submit_Click"){
				/*本当はボタンをfalseにしたほうがよい*/
				var objData = JSON.parse(localStorage.getItem("sentaku"));//選択リスト
				if( objData == null){
					alert("施設を選択してください");
					return false;
				}
				if( objData.length == 0){
					alert("施設を選択してください");
					return false;
				}
			//}
			return true;
		 });

		//ログアウト時　ローカルストレージクリア
		$(".logout").click(function(){			
			var wklist = JSON.parse(localStorage.getItem("sentaku"));
			
			for ( var i = 0; i < wklist.length; i++ ){
					wklist.splice(i, 1);
			}			
			localStorage.setItem('sentaku', JSON.stringify(wklist));
        });

 });
</script>
</head>
<body class="container">
<?php
//メニュー
include('include/menu.php');
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
<form name="search_form" id="search_form" role="form" method="post" action="<?=$_SERVER["PHP_SELF"]?>">
    <div class="row" id="srch">
    <p class="h4 ml10">検索条件</p>
        <table id ="rsv_serach" class="table-bordered table-condensed" align="center">
            <tbody>
                <tr>
                    <th class="bg-warning  pt12">施設分類</td>
                    <div class="form-group">
                    <td>
<?php
/* 施設分類の表示 */
if( isset( $_POST[ 'bunrui' ] ) && (count( $_POST[ 'bunrui' ] ) > 0) ){
	//配列代入
	$bunrui = &$_POST[ 'bunrui' ];
	
}else{

	//デフォルトではcheck_on
	$bunrui = array();

	for ($i = 0; $i < ( count( $rmcls['data'] ) ) ; $i++ ) {
		array_push( $bunrui , $rmcls['data'][$i]['key'] );
	}

}


for ($i = 0; $i < ( count( $rmcls['data'] ) ) ; $i++ ) {
	
	if( ( array_key_exists( $i, $rmcls['data']) ) && in_array ( $rmcls['data'][$i]['key'] , $bunrui )){

		echo "<label class=\"checkbox-inline\" for=\"bunrui". $i ."\"><input type=\"checkbox\" name=\"bunrui[]\" id=\"bunrui".$i."\" value=\"". $rmcls['data'][$i]['key'] ."\" checked>". $rmcls['data'][$i]['value'] . "</label>";

	} else {

		echo "<label class=\"checkbox-inline\" for=\"bunrui". $i ."\"><input type=\"checkbox\" name=\"bunrui[]\" id=\"bunrui".$i."\" value=\"". $rmcls['data'][$i]['key'] ."\">". $rmcls['data'][$i]['value'] . "</label>";

	}

}


?>
                    </td>       
                    </div><!--// form-group -->
                </tr>
                <tr>
					<th class="bg-warning  pt12">日付範囲</td>
                    <td colspan="3">
                        <div class="form-group"> 
<?php
/* 検索日付（自至） */
//検索開始日
if( !empty ( $_POST['search_ymd_stt'] ) ){
	$sttdt = $_POST['search_ymd_stt'] ; 
}else{
	//初期値
	$sttdt = date("Y/m/d"); 
}

//検索終了日
if( !empty ( $_POST['serch_ymd_end'] ) ){
	$enddt = $_POST['serch_ymd_end'] ; 
}else{
	//初期値
	$enddt = date("Y/m/d",strtotime("".$sttdt." +13 day"));
}

//検索曜日
if( isset( $_POST[ 'yobi' ] )  && ( count( $_POST[ 'yobi' ] ) > 0 ) ){
	//配列代入
	$yobi = &$_POST[ 'yobi' ];
}else{
	//デフォルトではcheck_on
	$yobi = array ( 0, 1, 2, 3, 4, 5, 6 );
}
?>
							<input type="text" id="date_timepicker_start" name="search_ymd_stt" value="<?php echo $sttdt; ?>" style="width:100px"> ～ <input type="text"  id="date_timepicker_end" name="serch_ymd_end" value="<?php echo $enddt; ?>" style="width:100px">
							<label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi7" value = "0" <?php  echo ( in_array ( 0 , $yobi ) )? 'checked' : ''; ?>><div class="col-sun"> 日</div></label>
							<label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi1" value = "1" <?php  echo ( in_array ( 1 , $yobi ) )? 'checked' : ''; ?>> 月</label>
							<label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi2" value = "2" <?php  echo ( in_array ( 2 , $yobi ) )? 'checked' : ''; ?>> 火</label>
							<label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi3" value = "3" <?php  echo ( in_array ( 3 , $yobi ) )? 'checked' : ''; ?>> 水</label>
							<label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi4" value = "4" <?php  echo ( in_array ( 4 , $yobi ) )? 'checked' : ''; ?>> 木</label>
							<label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi5" value = "5" <?php  echo ( in_array ( 5 , $yobi ) )? 'checked' : ''; ?>> 金</label>
							<label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi6" value = "6" <?php  echo ( in_array ( 6 , $yobi ) )? 'checked' : ''; ?>><div class="col-sat"> 土</div></label>
							<input type="submit" class="btn btn-default " value="検索する >>">
						</div><!--// form-group -->
					</td>
				</tr>
			</tbody>
		</table>    
        <hr>
    </div><!--// srch -->
</form>

	<!--検索結果-->
    <p class="h4 ml10">ご利用になりたい時間帯の[空]を押して、[○]にしてください。選択後、[予約申請へ進む]を押してください。</p>
    <div id="result">
		<div class="row mb10">
			<div class="col-xs-5">
			<p class="h4"><div class="h4 selcnt">現在の選択 ： &nbsp;件</div></p>
			</div>
			<div class="text-right  col-xs-7">
				<form name="yoyaku_form" id="yoyaku_form" role="form"  action="input.php" method="post">
					<a class="btn btn-default btn-lg" id="release_select" col-xs-1 role="button">選択解除</a>
					<input type='submit' class="btn btn-warning btn-lg" role="button" name="submit_Click" id="submit_Click" value="予約申請へ進む&nbsp;>>">
				</form>
			</div>
		</div>
		<p>
      		[凡例]
      		空：予約可　
      		<span class="selcol" style="padding-left:5px;padding-right:5px">○</span>：選択中　
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

//対象日を取得
$date_array = get_date_array( $sttdt , $enddt ,  $yobi ) ;

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

	echo "<tr class=\"dgray\">";
    //施設情報
    echo "<th rowspan=\"3\"><span class=\"f150\">".$rmnm."</span><br>[定員]".$teiin."<a href=\"#\"class=\"btn btn-primary btn-xs\" role=\"button\">施設情報</a></th>";

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
			echo "<td  class=\"can\" id=".$rmcd.$usedt."1\" ><a id=\"a-".$rmcd.$usedt."1\">";
            echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."1\"></a>";
            //各種定数化。
			echo "<div id=\"data-".$rmcd.$usedt."1\" data-usedt=\"".$usedt."\" data-yobi=".$k." data-timekb=\"1\" data-jkn1=\"9:00\" data-jkn2=\"12:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" />";
            echo "</td>";
		}else if( array_key_exists( $usedt, $mor['data'] ) && ( $mor['data'][$usedt] == 0 ) )
		{
		//空室の場合
			
			echo "<td  class=\"can\"><a id=\"a-".$rmcd.$usedt."1\">";
            echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."1\"></a>";
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

        if ( array_key_exists( $usedt, $noon['data'] ) && ( $noon['data'][$usedt] == 0 ) ) {
            //echo "<td  class=\"can\"><a href=\"#\"><img src=\"icon/kara.jpg\"></a></td>";
			echo "<td  class=\"can\"><a id=\"a-".$rmcd.$usedt."2\">";
            echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."2\"></a>";
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
        
		if ( array_key_exists( $usedt, $night['data'] ) && ( $night['data'][$usedt] == 0 ) ) {
            //echo "<td  class=\"can\"><a href=\"#\"><img src=\"icon/kara.jpg\"></a></td>";
			echo "<td  class=\"can\"><a id=\"a-".$rmcd.$usedt."3\">";
            echo "<img src=\"icon/kara.jpg\" alt=\"空\" class=\"mark\" id=\"img-".$rmcd.$usedt."3\"></a>";
            echo "<div id=\"data-".$rmcd.$usedt."3\" data-usedt=\"".$usedt."\" data-timekb=\"3\" data-jkn1=\"17:30\" data-jkn2=\"21:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" />";
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