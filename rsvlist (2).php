<?php
@session_start();
if(empty($_SESSION['webrk']['user']['userid'])){
	header("Location : top.php");	
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW,NOARCHIVE">
<title>予約照会 | <?php echo $_SESSION['webrk']['sysname']; ?></title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<link href="css/jquery.dataTables.css" rel="stylesheet">
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<link href="css/bootstrap-responsive.min.css" rel="stylesheet">
<link href="css/DT_bootstrap.css" rel="stylesheet">
<script src="js/custom.js"></script>
</head>
<body class="container">
<?php
require_once( "func.php" );
include("navi.php");
?>
   <div class="row">
      	<div class="col-xs-6" style="padding:0">
        <h1><span class="midashi">|</span>予約照会</h1>
       </div>

      	<div class="col-xs-6  text-right">
          <span class="f120">現在の時間：　<span id="currentTime"></span></span>
       </div>
   </div>
<!------------------->


    <!-- main -->
   <div class="row">
     <div class="col-xs-9" style="padding:0">

    	<h4>本システムからお申込頂いたご予約の一覧です</h4>
      <p class="h5">予約の取消をしたい場合、[取消]をチェックしてページ下部の取消ボタンを押してください</p>
    	<ul>
    	<li><span class="red">予約の変更は、窓口・お電話にて承っております。</span></li>
    	<li><span class="red">お支払手続き済の予約のキャンセルをされたい場合は、窓口までお越しください。</span></li>
    	<li>失効予定日までに窓口まで手続きにお越しください。失効予定日をすぎてもお手続きが終わっていない場合、<br>施設側で予約を取消させていただきます。</li>
    	</ul>
	</div>
	<div class="col-xs-3  text-left bg-danger">
		<p>【状態の説明】<br>
		<li><b class="status1">予約照合中：</b>予約申込を受付ました</li>
		<li><b class="status2">予約不可：</b>予約申込の受付ができませんでした</li>
		<li><b class="status3">予約：</b>予約申込の受付が完了しました（窓口で利用料をお支払いください）</li>
		<li><b class="status5">予約取消：</b>予約申込を取り消しました</li>
		<li><b class="status6">予約完了：</b>予約申込が完了しました（利用料をお支払いいただきました）</li>
		</p>
	</div>
	</div>
    <br>
	<form name="rsvlist_form" id="rsvlist_form" role="form"  action="delconf.php" method="post">
    <table id ="rsv_input" class="table table-bordered table-condensed  form-inline" >
    <thead>
     	<tr>
      		<th width="1%">取消</th>
      		<th width="7%">状態</th>
      		<th width="12%">WEB受付No.</th>
      		<th width="12%">申込日</th>
      		<th width="30%">利用日時</th>
      		<th width="20%">申込施設</th>
       		<!-- th width="10%" >取消期限</th-->
      	</tr>
      </thead>
      <tbody>
<?php 
define("KARIYOHAKU", "仮予約");
define("YOHAKU", "予約完了");
define("TORIKESHI", "予約取消");

require_once("model/db.php");
$db = new DB;
$conErr = $db->connect();
if (!empty($conErr)) { echo $conErr; die();}

$rsvlist = $db->select_rsvlist(1);//客コード

for ( $i = 0; $i < count( $rsvlist ); $i++ ) {

	if ( !empty( $rsvlist[$i]['candt'] ) )  {
	
		echo "<tr class=\"dgray\">";
		echo "<td>&nbsp;</td>";
		echo "<td>予約取消</td>";
	
	} else {
	
		echo "<tr>";
				
		//入金チェック（入金有無にかかわらず、予約は取り消しできる）
		if( $db->select_nyukin_status( $rsvlist[ $i ][ 'ukeno' ] ) ) {

			echo "<td><input type=\"checkbox\" name=\"del[]\" value=\"".$i."\"></td>";
			echo "<td class=\"status6\">予約完了</td>";			
			
			echo "<div id=\"data-".$rmcd.$usedt."1\" data-usedt=\"".$usedt."\" data-yobi=".$k." data-timekb=\"1\" data-jkn1=\"9:00\" data-jkn2=\"12:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" />";

			

		} else {
		
			echo "<td><input type=\"checkbox\" name=\"del[]\" value=\"".$i."\"></td>";
			echo "<td class=\"status3\">予約</td>";
		
		}
		
	}
		echo "<td>".$rsvlist[$i]['ukeno']."-".$rsvlist[$i]['gyo']."</td>";
		/* hidden */
		echo "<input type='hidden' name='ukeno".$i."' id='ukeno".$i."'  value=\"".$rsvlist[$i]['ukeno']."\">";//受付№
		echo "<input type='hidden' name='gyo".$i."' id='gyo".$i."' value=\"".$rsvlist[$i]['gyo']."\">";//申し込み日
		/* 明細 */
		echo "<td>".substr( $rsvlist[$i]['udate'], 0, 4 )."/".substr( $rsvlist[$i]['udate'], 4, 2 )."/".substr( $rsvlist[$i]['udate'], 6, 2 );
		echo "<td>".substr( $rsvlist[$i]['usedt'], 0, 4 )."/".substr( $rsvlist[$i]['usedt'], 4, 2 )."/".substr( $rsvlist[$i]['usedt'], 6, 2 )."(".mb_convert_encoding($rsvlist[$i]['yobi'], "utf8", "SJIS").")<br>";//使用日
		echo format_jkn( $rsvlist[$i]['stjkn'] , ":" )."～".format_jkn( $rsvlist[$i]['edjkn'] , ":" )."<br/>";//使用時間
		echo "「".mb_convert_encoding($rsvlist[$i]['kaigi'], "utf8", "SJIS")."」</td>";//行事内容
		echo "<td>".mb_convert_encoding($rsvlist[$i]['rmnm'], "utf8", "SJIS")."<br>";//施設名
		echo $rsvlist[$i]['ninzu']."人</td>";//人数
		//echo "<td>&nbsp;</td>";
		echo "</tr>";

	}
?>
		</tbody>
    </table>
	<a class="btn btn-default btn-lg" href="top.php" role="button">トップページへ戻る</a>
	<input type='submit' class="btn btn-warning btn-lg" role="button" name="submit_Click" id="submit_Click" value="予約取消確認へ&nbsp;>>">
</form>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="http://code.jquery.com/jquery.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
	$('#rsv_input').dataTable( {

	"bFilter":false,
	"bPaginate":false,
	"bInfo":false,
	"bLengthChange":false,
		"oLanguage": {
			"sLengthMenu": "_MENU_ records per page"
		}
	} );
} );
</script>

</body>
</html>