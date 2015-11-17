<?php
@session_start();

$errmsg = "";
//header
$pageTitle =  "予約照会";
include('include/header.php');
?>
</head>
<body class="container">
<p class="bg-head text-right"><?php echo $_SESSION['centername']; ?></p>
<h1><span class="midashi">|</span><?php echo $pageTitle; ?><?php echo "<small>".$_SESSION['sysname']."</small>" ?></h1>
<?php

//メニュー
include('include/menu.php');
require_once('func.php');
/**
 * ReserveKeeperWeb予約システム
 *
 * PHP versions 4
 *
 * @category   公益財団法人神戸市産業振興財団／メールアドレス変更
 * @package    none
 * @author     y.kamijo <y.kamijo@gandg.co.jp>
 * @copyright  2015 G&G Co.ltd.
 * @license    G&G Co.ltd.
 * @version    0.1
**/

?>
    <div class="row mb20">
		<div class="col-xs-7">
      		<h4>お申し込みをされたご予約の一覧です</h4>
      		<h4 class="status2">＊＊ 必ずお読みください＊＊</h4>
			<div class="alert alert-info" role="alert">
			<p class="h5">※ご予約のお取り消しは、お問い合わせ窓口までお問合せください。</p>
			<br><p class="h5">※施設使用料は、前もって納付期限までにお支払いください。<br><!--納付期限をすぎてもご入金いただけない場合、お申し込みが失効されます。-->納付期限をすぎてもお支払いいただけない場合、お申し込みは取り消されます。</p>
			<br><p class="h5">※ご入金確認後、使用許可を行い、「使用許可書」を発行いたします。<br>お手数ですが、「使用許可書」を下記画面からダウンロード後、印刷のうえ、必ずご利用当日にご持参ください。
			<br><br>ダウンロードや印刷ができない場合は、下記、お問い合わせ窓口までお問い合わせください。<br>なお、安全性の為、ダウンロードできるのは一度のみとしております。ご注意ください。</p>		
			</div>
		</div>
		<div class="col-xs-5">
			<br>【状態の説明】<br>
          <span class="status1"></span><br>
          <span class="status3">仮予約：</span>予約申し込みを受け付けました。所定の使用料をお支払いください。ご入金確認後、「使用許可書」を発行いたします。<br>
          <span class="status1">予約：</span>使用が許可されました。「使用許可書」が発行されています。印刷して当日にお持ちください。<br>
          <span class="status2">予約不可：</span>時間差で他のお申し込みが受け付けられたため、予約できませんでした。悪しからずご了承ください。<br>
          <span class="status5">失効：</span>納付期限を超過したため、窓口で仮予約を取り消しました。<br>
          <span class="status5">取消：</span>お客様のお申し出により予約を取り消しました。<br>
          </p>
       </div>
      </div>
	<div class="alert alert-success" role="alert">
	<h4>＊＊ お問い合わせ窓口＊＊</h4><h3>TEL:078-360-3200 お問い合わせ時間：9:00～17:00</h3> ※予約状況を確認するにあたり、「お問い合わせ番号」をお知らせください。
	</div>
	<!--div class="row mb10 text-left">
	</div-->
    <!--div class="col-xs-5">
		<select  class="form-control" name="mokuteki">
		<option value="0">現在の予約</option>
		<option value="1">2015年度の予約</option>
		</select>
	</div-->
	<div class="row mb10 text-right">
	
	<a href="help.html#rsvlist"  class="btn alert-info" target="window_name"  onClick="disp('help.html#rsvlist')"><li class="glyphicon glyphicon-question-sign" aria-hidden="true">&nbsp;この画面の操作方法についてはこちら>></li></a> 
	</div>
    <table id ="rsv_input" class="table table-bordered table-condensed form-inline" >
      <thead>
      	<tr>
      		<th width="8%">状態</th>
      		<th width="10%">お問い合わせ<br>番号<br>/申込日</th>
          <th width="18%">使用日<br>/施設名</th>
          <th width="15%">使用時 間</th>
          <th width="3%">人数</th>
          <th width="22%">行事名<br>/確認事項</th>
       		<th width="10%">納付期限</th>
       		<th>請求書<br>/使用許可書</th>
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

$rsvlist = $db->select_rsvlist($_SESSION['wloginid']);//客コード

for ( $i = 0; $i < count( $rsvlist ); $i++ ) {

  
    echo "<tr>";
        
    //入金チェック（入金有無にかかわらず、予約は取り消しできる）
    if( $db->select_nyukin_status( $rsvlist[ $i ][ 'ukeno' ] ) ) {
      echo "<td class=\"status6\">予約</td>";     
      echo "<div id=\"data-".$rmcd.$usedt."1\" data-usedt=\"".$usedt."\" data-yobi=".$k." data-timekb=\"1\" data-jkn1=\"9:00\" data-jkn2=\"12:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" />";
    }else{
      echo "<td class=\"status3\">仮予約</td>";
    }
    
    //echo "<td>".substr( $rsvlist[$i]['usedt'], 0, 4 )."/".substr( $rsvlist[$i]['usedt'], 4, 2 )."/".substr( $rsvlist[$i]['usedt'], 6, 2 )."(".mb_convert_encoding($rsvlist[$i]['yobi'], "utf8", "SJIS").")<br>";//使用日
    $rsvdt = date( "Y/m/d", strtotime( substr( $rsvlist[$i]['usedt'], 0, 4 )."-".substr( $rsvlist[$i]['usedt'], 4, 2 )."-".substr( $rsvlist[$i]['usedt'], 6, 2 )) );      //申込終了日
    $paydt = date( "Y/m/d", strtotime( "".$rsvdt." +10 day" ) ); 
    echo "<td><span class=\"green\">".$rsvlist[$i]['ukeno']."-".$rsvlist[$i]['gyo']."</span><br>";
    echo substr( $rsvlist[$i]['udate'], 0, 4 )."/".substr( $rsvlist[$i]['udate'], 4, 2 )."/".substr( $rsvlist[$i]['udate'], 6, 2 )."</td>";
    /* hidden */
    echo "<input type='hidden' name='ukeno".$i."' id='ukeno".$i."'  value=\"".$rsvlist[$i]['ukeno']."\">";//受付№
    echo "<input type='hidden' name='gyo".$i."' id='gyo".$i."' value=\"".$rsvlist[$i]['gyo']."\">";//申し込み日
    /* 明細 */
    echo "<td>".$rsvdt."(".mb_convert_encoding($rsvlist[$i]['yobi'], "utf8", "SJIS").")<br>";//使用日
    echo mb_convert_encoding($rsvlist[$i]['rmnm'], "utf8", "SJIS")."<br>";//施設名
    echo "<td>使用時間：".format_jkn( $rsvlist[$i]['stjkn'] , ":" )."～".format_jkn( $rsvlist[$i]['edjkn'] , ":" )."<br/>";//使用時間
    echo "<td>".$rsvlist[$i]['ninzu']."人</td>";//人数
    echo "<td>「".mb_convert_encoding($rsvlist[$i]['kaigi'], "utf8", "SJIS")."」</td>";//行事内容
    echo "<td>".$paydt."</td>";
    echo "<td><a href=\"http://localhost/rk_ssc/sample/pay.pdf\" class=\"btn-icon\"><img src=\"icon_btn_pdf.png\" alt=\"請求書\">請求書ダウンロード</a></td>";
    echo "</tr>";

  }
?>
    </tbody>

    <tbody>
      <tr>
        <td class="status1">仮予約</td>
      	<td><span class="green">150804-05</span><br>2015/09/29(火)</td>
        <td>2015/10/20(月)<br>会議室９０２＋９０３</td>
  	    <td>使用時間：13:00～17:00<hr>催　　物：13:00～17:00</td>
  	    <td>10</td>
  			<td>「社労士情報交換会」<br><span class="small">・営利目的での使用にあてはまらない<br>・入場料・受講料を徴収しない</span></td>
      	<td>2015/09/30（水）</td>
  			<td><a href="http://localhost/rk_ssc/sample/pay.pdf" class="btn-icon"><img src="icon_btn_pdf.png" alt="">請求書ダウンロード</a></td>
    	</tr>
    	<tr>
      	<td class="status3">仮予約</td>
      	<td><span class="green">150921-05</span><br>2015/09/21(月)</td>
      	<td>2015/11/02(月)<br>会議室８０２＋８０３</td>
  			<td>使用時間：18:00～21:00<hr>催　　物：18:00～21:00</td>
      	<td>30</td>
  			<td>「中小企業のための就業規則講座」<br><span class="small">・営利目的での使用にあてはまらない<br>・入場料・受講料を徴収しない<br>・間仕切りを閉める</span></td>
      	<td>2015/09/30（水）</td>
      	<td><a href="http://localhost/rk_ssc/sample/pay.pdf" class="btn-icon"><img src="icon_btn_pdf.png" alt="">請求書ダウンロード</a></td>
    	</tr>
      <tr>
    		<td class="status1">予約</td>
    		<td><span class="green">140801-02</span><br>2014/08/01(金)</td>
    		<td>2015/11/30(火)<br>ハーバーホール</td>
			<td>使用時間：13:00～17:00<hr>準備・リハ：13:00～13:30<br>催　　物：13:30～16:30<br>撤　　去:16:30～17:00</td>
	        <td>350</td>
			<td>「ピアノコンクール」<br><span class="small">・営利目的での使用にあてはまらない<br>・入場料・受講料を徴収する<br>・グランドピアノを使用する</span></td>
			<td>入金済</td>
    		<td>(使用許可書発行済)<br>許可番号：00009765</td>
    	</tr>
    	<tr class="dgray">
    		<td class="status5">取消</td>
    		<td><span class="green">150403-01</span><br>2015/04/03(日)</td>
    		<td>2015/11/19(金)<br>会議室８０１</td>			
			<td>使用時間：&nbsp;9:00～12:00<hr>催　　物:&nbsp;9:00～12:00</td>
	        <td>30</td>
			<td>「マイナンバー対策セミナー」<br><span class="small">・営利目的での使用にあてはまらない<br>・入場料・受講料を徴収しない</span></td>
    		<td>2015/04/13（金）</td>
    		<td>(請求書発行済)</td>
    	</tr>
    	<tr>
    		<td class="status3">仮予約</td>
    		<td><span class="green">150925-03</span><br>2015/09/25(金)</td>
    		<td>2016/05/23(日)<br>会議室９０６</td>
			<td>使用時間：&nbsp;9:00～12:00<hr>催　　物:&nbsp;9:00～12:00</td>
	        <td>20</td>
			<td>「メンタルヘルス対策セミナー」<br><span class="small">・営利目的での使用にあてはまらない<br>・入場料・受講料を徴収しない</span></td>
    		<td>2015/10/04（日）</td>
    		<td>(請求書発行済)</td>
    	</tr>
    	<tr>
    		<td class="status1">予約</td>
    		<td><span class="green">150730-10</span><br>2015/07/30(金)</td>
    		<td>2015/10/20(日)<br>会議室９０２</td>
	        <td>使用時間：&nbsp;9:00～12:00<hr>催　　物:&nbsp;9:00～12:00</td>
			<td>20</td>
			<td>「メンタルヘルス対策セミナー」<br><span class="small">・営利目的での使用にあてはまらない<br>・入場料・受講料を徴収しない</span></td>
    		<td>入金済</td>
    		<td><a href="http://localhost/rk_ssc/sample/cer.pdf" class="btn-icon"><img src="icon_btn_pdf.png" alt="">使用許可書ダウンロード</a><br>許可番号：00009764</td>
    	</tr>
    	<tr class="dgray">
    		<td class="status2">予約不可</td>
    		<td><span class="green">150730-01</span><br>2015/07/30(金)</td>
    		<td>2015/10/20(日)<br>特別会議室</td>
			<td>使用時間：&nbsp;9:00～12:00<hr>催　　物:&nbsp;9:00～12:00</td>
			<td>15</td>
			<td>「はじめてのマイナンバー勉強会」<br><span class="small">・営利目的での使用にあてはまらない<br>・入場料・受講料を徴収しない</span></td>
    		<td></td>
   			<td></td>
    	</tr>
			<tr>
    		<td class="status1">予約<br>→変更済</td>
    		<td><span class="green">150730-01</span><br>2015/07/30(金)</td>
    		<td>2015/10/20(日)<br>会議室803</td>
			<td>使用時間：&nbsp;9:00～12:00<hr>催　　物:&nbsp;9:00～12:00</td>
	        <td>20</td>
			<td>「会議名称５６７８９０１２３４５６７８９０」<br><span class="small">・営利目的での使用にあてはまらない<br>・入場料・受講料を徴収しない</span></td>
			<td>入金済</td>
			<td>(使用許可書発行済)<br>許可番号：00009763</td>
		</tr>
    	<tr class="dgray">
    		<td class="status4">失効</td>
    		<td><span class="green">150212-05</span><br>2015/02/12(木)</td>
    		<td>2016/05/05(火)<br>会議室902</td>
			<td>使用時間：13:00～17:00<hr>催　　物:13:00～17:00</td>
	        <td>10</td>
			<td>「中小企業経営の秘訣」<br><span class="small">・営利目的での使用にあてはまる<br>・入場料・受講料を徴収する</span></td>
    		<td>2015/02/22（金）</td>
			<td>(請求書発行済)</td>
    	</tr>
      </tbody-->
   </table>
   <a class="btn btn-default btn-lg" href="top.php" role="button">トップページへ戻る</a>
<br><br>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="http://code.jquery.com/jquery.js"></script>
<script src="js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
	$('#rsv_input').dataTable( {
  // 大量データ利用時、「処理中」メッセージを表示するかを設定
  "bProcessing": true,
  // 初期表示の行数設定
  //"iDisplayLength": 20,
  // ページングボタンの制御
 	"bFilter":false,
 	"bLengthChange":false,
 	"bPaginate":false,
 	"bInfo":false,
  "order": [[ 1, "desc" ]],
  "columnDefs": [ {
      "targets": [ 0, 3, 4, 5, 6, 7 ],
      "orderable": false
    } ],
	"bLengthChange":false,
   "oLanguage" : {
           "sProcessing":   "処理中...",
           "sLengthMenu":   "_MENU_ 件表示",
           "sZeroRecords":  "データはありません。",
           "sInfo":         "_START_件～_END_件を表示（全_TOTAL_ 件中）",
           "sInfoEmpty":    " 0 件中 0 から 0 まで表示",
           "sInfoFiltered": "（全 _MAX_ 件より抽出）",
           "sInfoPostFix":  "",
           "sSearch":       "検索フィルター:",
           "sUrl":          "",
           "oPaginate": {
               "sFirst":    "先頭",
               "sPrevious": "前へ",
               "sNext":     "次へ",
               "sLast":     "最終"
           }
        }

	} );
} );
</script>
</body>
</html>