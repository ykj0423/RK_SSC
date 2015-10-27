<?php @session_start();
//if(empty($_SESSION['webrk']['user']['userid'])){
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
<title>予約申込み[確認]　 |</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<script>
jQuery(function () {
    //HTMLを初期化  
    $("table.rsv_input tbody.list").html("");
    var objData = JSON.parse(localStorage.getItem("sentaku"));
    
	for ( var i = 0; i < objData.length; i++ ){

		var tr = $("<tr></tr>");
		var td1 = $("<td></td>");
		var td2 = $("<td></td>");
		var td3 = $("<td></td>");
		var td4 = $("<td></td>");
		var td5 = $("<td></td>");
		var td6 = $("<div></div>");
		var td7 = $("<div></div>");
		var td8 = $("<div></div>");
		var td9 = $("<div></div>");
		var td10 = $("<div></div>");
		var td11 = $("<div></div>");
		
		/* 日付のフォーマット もう少しスマートな方法がないか検討*/
		/* 前の画面とまったく同じ処理を書いているので整理*/
		var usedt = objData[i]['usedt'];
		var useyyyy = usedt.substring(0, 4);
		var usemm = objData[i]['usedt'].substring(4, 6);
		var usedd = usedt.substring(6, 8);
			
		var d = new Date(useyyyy + "/" + usemm + "/" +  usedd);
		var w = ["（日）","（月）","（火）","（水）","（木）","（金）","（土）"];
		var yobi = w[d.getDay()];
		var gyo = i + 1;

		$("#list").append(tr);
		tr.append( td1 ).append( td2 ).append( td3 ).append( td4 ).append( td5 ).append( td6 ).append( td7 ).append( td8 ).append( td9 ).append( td10 ).append( td11 );
		td1.html( gyo );
		td2.html( useyyyy + "/" + usemm + "/" +  usedd  + yobi );
		td3.html( objData[i]['jkn1']  + "～" + objData[i]['jkn2']  );
		td4.html( objData[i]['ninzu']+"人" );
		td5.html( objData[i]['rmnm'] );
		td6.html( "<input type='hidden' name='rmcd" + i + "' id='rmcd" + i + "' value='" + objData[i]['rmcd'] + "'>" );
		td7.html( "<input type='hidden' name='gyo" + i + "' id='gyo" + i + "' value='" + gyo + "'>" );	//行番
		td8.html( "<input type='hidden' name='usedt" + i + "' id='usedt" + i + "' value=" + useyyyy + usemm + usedd + ">" ); //使用日付
		td9.html( "<input type='hidden' name='timekb" + i + "' id='timekb" + i  + "' value='" + objData[i]['timekb'] + "'>" ); //時間帯
		td10.html( "<input type='hidden' name='stjkn" + i + "' id='stjkn" + i + "' value='" + objData[i]['jkn1'] + "' style='width:70px'><input type='hidden' class='form-control' name='edjkn" + i + "' id='edjkn" + i + "' value='"+ objData[i]['jkn2'] + "'' style='width:70px'>" );
		td11.html( "<input type='hidden' name='ninzu" + i + "' id='ninzu" + i + "' value='" + objData[i]['ninzu'] + "' style='width:50px'>" );
		
		//test
		//td5.html(objData[i]['data-rmcd']);
		//var form = $('confirm_form');
		//使用日(hidden)
		//form.append($('<input>',{type:'hidden',id:'usedt'.i,name:'usedt'.i,value:objData[i]['usedt']}));
		//施設コード(hidden)
		//form.append($('<input>',{type:'hidden',id:'rmcd'.i,name:'rmcd'.i,value:objData[i]['rmcd']}));
		
	} 

	/* submit */
	$('#confirm_form').submit(function(){
			//バリデーションチェックの結果submitしない場合、return falseすることでsubmitを中止することができる。
			//return false;
			$('#confirm_form').append($('<input>',{type:'hidden',name:'meisai_count',value:objData.length}));
			return true;
	});
	//formを作成
	/*$(".btn_Click").click(function(){
		//attrで発生したイベントのidを取得する
		var anc = $(this).attr("id");
		//form用のHTMLを作成する
		var form = $('<form></form>',
					{id:'btnid',action:'end.php',method:'POST'}).hide();
		//bodyのオブジェクトを取得
		var body = $('body');
		//bodyに作成したformを追加する
		body.append(form);
		//追加したformにinputを追加する
		//form.append($('<input>',{type:'hidden',name:'btnid',value:anc}));
		form.append($('<input>',{type:'hidden',name:'test',value:'xyz'}));
		//作成したformでsubmitする
		form.submit();
		return false;
	});*/
	
});
</script>
</head>
<body class="container">
<?php 

//echo "<br>POST";
//print_r($_POST);
//echo "<br>GET";
//print_r($_GET);

include("navi.php"); 
require_once( "func.php" );
require_once( "model/db.php" );

/* データベース接続 */
$db = new DB;
$conErr = $db->connect();
if ( !empty( $conErr ) ) { echo $conErr;  die(); } //接続不可時は終了

?>

   <div class="row">
      	<div class="col-xs-6" style="padding:0">
        <h1><span class="midashi">|</span>予約申込み[確認]</h1>
       </div>

      	<div class="col-xs-6  text-right">
          <span class="f120">現在の時間：　<span id="currentTime"></span></span>
       </div>
   </div>
<!------------------->
<!-- main -->
    	<h4>入力内容をご確認頂き、問題がなければ送信ボタンを押してください。</h4>
<!--form name="confirm_form" id="confirm_form" role="form" action="end.php"　method="post"-->
<form class="form-horizontal" name="confirm_form" id="confirm_form" role="form" method="post" action="end.php">
    <table id ="rsv_input" class="table table-bordered table-condensed  form-inline">
    	    <tbody>
                <tr><th colspan="5">お申込み内容</th></tr>
    	        <tr>
    		        <th colspan="2" width="20%">行事名<span class="red">（必須)</span></th>
    		        <td colspan="3" width="70%"><?php echo $_POST[ 'kaigi' ]; ?></td>
					<?php 
						echo "<input type='hidden' name='kaigi' id='kaigi' value=\"".$_POST[ 'kaigi' ]."\">";
						echo "<input type='hidden' name='riyokb' id='riyokb' value=\"".$_POST[ 'riyokb' ]."\">";
						echo "<input type='hidden' name='riyokb' id='riyokb' value=\"".$_POST[ 'ninzu' ]."\">";
					?>
    	        </tr>
    	        <tr>
    		        <th colspan="2">利用目的<span class="red">（必須)</span></th>
    		        <td colspan="3">
					<?php 
					//利用目的はマスタから読み込む
					if ( !empty( $_POST['riyokb'] ) ) {
						
						$riyo = $db->get_mm_riyo( $_POST['riyokb'] );

						for ( $i = 0; $i < ( count( $riyo ) ) ; $i++ ) {
							echo mb_convert_encoding( $riyo[ $i ][ 'name' ], "utf8", "SJIS" );
							break;						
						}
					
					}
					?></td>
    	        </tr>
    	        <!--tr>
    		        <th  colspan="2">利用人数<span class="red">（必須)</span></th>
    		        <td  colspan="2"><?php //echo $_POST['ninzu']; ?></td>
    	        </tr-->
    	        <tr>
    		        <th  colspan="2">団体名</th>
    		        <td  colspan="3"></td>
    	        </tr>
    	        <tr>
    		        <th  colspan="2">メールアドレス</th>
    		        <td  colspan="3">
    		            <p>こちらのアドレスに予約受付のメールをお送りいたします。</p>
    		        </td>
    	        </tr>
    	        <tr><th colspan="5">お申込み施設</th></tr>
    	        <tr>
    		        <th width="10%">No.</th>
    		        <th width="20%">ご利用日</th>
    		        <th width="20%">ご利用時間</th>
					<th width="20%">人数</th>
    		        <th>施設名</th>
    	        </tr>
            </tbody>
            <tbody id="list">
            </tbody>
            <tbody>
    	    <!--tr><th colspan="4">上記のうち、ひとつでも予約できなかった場合</th></tr>
    	    <tr>
    		    <td  colspan="4">全ての申込をキャンセルする</td>
    	    </tr-->
            </tbody>
    	</table>
		 <div class="form-group">
			<a class="btn btn-default btn-lg" href="input.php" role="button"><<　修正する</a>
			<input type='submit' class="btn btn-warning btn-lg" role="button" name="submit_Click" id="submit_Click" value='送信する&nbsp;>>'>
        </div>
    </div>
</form>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>