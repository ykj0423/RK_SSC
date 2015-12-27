<?php
@session_start();

$errmsg = "";
//header
$pageTitle =  "予約照会";
include('include/header.php');
?>
</head>
<body class="container">
<?php include('include/menu.php');
?>
<h1><span class="midashi">|</span><?php echo $pageTitle; ?><?php echo "<small>".$_SESSION['sysname']."</small>" ?></h1>
<?php

//メニュー

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
<!--      <h4>お申込みされたご予約の一覧です</h4>-->
      <h4 class="status2">＊＊ 必ずお読みください＊＊</h4>
      <div class="alert alert-info" role="alert">
        <p class="h5">※使用許可のお取消は、受付までお問い合わせください。<br>&nbsp;&nbsp;&nbsp;&nbsp;また、<a href="torikesi.pdf" target="blank"><img src="icon_btn_pdf.png" alt=""><u>「神戸市産業振興センター使用許可取消申出書」</u></a>を受付までご提出ください。</p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <br>
        <p class="h5">※使用料は、納付期限までにお支払いください。<br>&nbsp;&nbsp;&nbsp;&nbsp;納付期限を過ぎてもご入金いただけない場合、お申込みは自動的に失効いたします。</p><br>
        <p class="h5">※ご入金確認後、使用許可を行い「神戸市産業振興センター使用許可書」を発行いたします。<br>&nbsp;&nbsp;&nbsp;&nbsp;下記画面よりダウンロードの上、印刷し、必ずご使用当日にご持参ください。
          <br><br>ダウンロードや印刷ができない場合は、受付までお問い合わせください。<br>なお、安全性確保の為、ダウンロードできるのは一度のみとしておりますのでご注意ください。
        </p>    
      </div>
    </div>
    <div class="col-xs-5"><br>【状態の説明】<br>
      <span class="status1"></span><br>
      <span class="status3">仮予約：</span>使用申込を受け付けました。所定の使用料をお支払いください。ご入金確認後、「神戸市産業振興センター使用許可書」を発行いたします。<br><br>
      <span class="status1">予約：</span>「神戸市産業振興センター使用許可書」が発行されています。印刷して当日お持ちください。<br><br>
      <span class="status2">予約不可：</span>他のお申込みが受け付けられたため、あるいは既存の予約を含め、7日を超える連続使用となるため予約できませんでした。悪しからずご了承ください。<br><br>
      <span class="status5">失効：</span>納付期限を超過したため、センターで仮予約を取り消しました。<br><br>
      <span class="status5">取消：</span>お客様のお申し出により予約を取り消しました。<br>
    </div>
  </div>
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
require_once("model/db.php");

$db = new DB;
$conErr = $db->connect();
if (!empty($conErr)) { echo $conErr; die();}

$rsvlist = $db->select_rsvlist($_SESSION['kyacd']);//客コード
$cnt = count( $rsvlist );
echo $cnt;
$cnt=$cnt+1;
echo $cnt;
//$cnt = count( $rsvlist ) +1;
//print_r($rsvlist);
for ( $i = 0; $i < count( $rsvlist ); $i++ ) {
  
    echo "<tr>";
    //入金チェック（入金有無にかかわらず、予約は取り消しできる）
    //if( $db->select_nyukin_status( $rsvlist[ $i ][ 'ukeno' ] ) ) {
    if( $rsvlist[ $i ][ 'wrsvkb' ] ==2 ){
      echo "<td class=\"status6\">予約</td>";     
    //  echo "<div id=\"data-".$rmcd.$usedt."1\" data-usedt=\"".$usedt."\" data-yobi=".$k." data-timekb=\"1\" data-jkn1=\"9:00\" data-jkn2=\"12:00\" data-rmcd=\"".$rmcd."\" data-rmnm=\"".$rmnm."\" />";
    }else{
      echo "<td class=\"status3\">仮予約</td>";
    }
    
    $rsvdt = date( "Y/m/d", strtotime( substr( $rsvlist[$i]['usedt'], 0, 4 )."-".substr( $rsvlist[$i]['usedt'], 4, 2 )."-".substr( $rsvlist[$i]['usedt'], 6, 2 )) );      //申込終了日
    echo "<td><span class=\"green\">".$rsvlist[$i]['ukeno']."</span><br>";
    //echo "<td><span class=\"green\">".$rsvlist[$i]['ukeno']."-".$rsvlist[$i]['gyo']."</span><br>";
    echo format_ymd( $rsvlist[$i]['ukedt'] )."</td>";
    /* hidden */
    echo "<input type='hidden' name='ukeno".$i."' id='ukeno".$i."'  value=\"".$rsvlist[$i]['ukeno']."\">";//受付№
    echo "<input type='hidden' name='gyo".$i."' id='gyo".$i."' value=\"".$rsvlist[$i]['gyo']."\">";//申し込み日
    /* 明細 */
    echo "<td>".$rsvdt."(".mb_convert_encoding($rsvlist[$i]['yobi'], "utf8", "SJIS").")<br>";//使用日
    echo mb_convert_encoding($rsvlist[$i]['rmnm'], "utf8", "SJIS")."<br>";//施設名
    echo "<td>使用時間：".format_jkn( $rsvlist[$i]['stjkn'] , ":" )."～".format_jkn( $rsvlist[$i]['edjkn'] , ":" )."<br/>";//使用時間
    echo "<td>".$rsvlist[$i]['ninzu']."人</td>";//人数
    echo "<td>「".mb_convert_encoding($rsvlist[$i]['kaigi'], "utf8", "SJIS")."」</td>";//行事内容
    
    //納付期限
    if( ($rsvlist[$i]['kounoukb'] == 0) && ($rsvlist[$i]['paylmtdt'] > 0) ){    
      echo "<td>".$rsvlist[$i]['paylmtdt']."</td>";    
    }else{    
      echo "<td></td>";
    }
    
 
    //ドキュメント
    echo "<td>";

    //許可書
    if(!empty($rsvlist[$i]['kyono'])){

      if( $rsvlist[$i]['kyofbd'] == 0) {
          echo "<a href=\"".$rsvlist[$i]['kyourl'].$rsvlist[$i]['kyofile']."\" class=\"btn-icon\"><img src=\"icon_btn_pdf.png\" alt=\"許可書\">使用許可書ダウンロード</a>";
      }else{
        echo "許可番号：".$rsvlist[$i]['kyono'];
      }

    }

    if(!empty($rsvlist[$i]['seino'])){

      $doc_name ="";
      
      //料金後納の場合
      if($rsvlist[$i]['kounoukb'] == 1){
        $doc_name ="料金のお知らせ";
      }else{
        $doc_name ="請求書";
      }

      if( $rsvlist[$i]['seifbd'] == 0) {
      //echo $rsvlist[$i]['seiurl'];
      //echo "<br>";
//echo $rsvlist[$i]['seifile'];

        echo "<a href=\"".$rsvlist[$i]['seiurl'].$rsvlist[$i]['seifile']."\" class=\"btn-icon\"><img src=\"icon_btn_pdf.png\" alt=\"".$doc_name."\">".$doc_name."ダウンロード</a>";
        //echo "<a href=\"".$rsvlist[$i]['seiurl'].$rsvlist[$i]['seifile']."\" class=\"btn-icon\"><img src=\"icon_btn_pdf.png\" alt=\"".$doc_name."\">".$doc_name."ダウンロード</a>";
      }else{
        echo $doc_name."番号：".$rsvlist[$i]['seino'];
      }

    }
  
    echo "</td>";
    echo "</tr>";
}
?>
    </tbody>    
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