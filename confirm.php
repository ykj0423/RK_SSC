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
		var td12 = $("<td></td>");
		var td13 = $("<td></td>");
		var td14 = $("<td></td>");
		var td15 = $("<td></td>");
		var td16 = $("<td></td>");
		var td17 = $("<td></td>");
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
		tr.append( td1 ).append( td2 ).append( td3 ).append( td4 ).append( td5 ).append( td6 ).append( td7 ).append( td8 ).append( td9 ).append( td10 ).append( td11 ).append( td12 ).append( td13 ).append( td14 ).append( td15 ).append( td16 ).append( td17 );
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
		var jjkn = objData[i]['jstjkn_h'] + "時" + objData[i]['jstjkn_m'] + "分～" + objData[i]['jedjkn_h'] + "時" + objData[i]['jedjkn_m'] + "分";
		var hjkn = objData[i]['hstjkn_h'] + "時" + objData[i]['hstjkn_m'] + "分～" + objData[i]['hedjkn_h'] + "時" + objData[i]['hedjkn_m'] + "分";
		var tjkn = objData[i]['tstjkn_h'] + "時" + objData[i]['tstjkn_m'] + "分～" + objData[i]['tedjkn_h'] + "時" + objData[i]['tedjkn_m'] + "分";
		td12.html( "<tr><th>準備・リハ時間</th><td>" + jjkn + "</td></tr>" + "<tr><th>催物時間</th><td>" + hjkn + "</td></tr>" + "<tr><th>撤去時間</th><td>" + tjkn + "</td></tr>" );
		td13.html();
		var str_option;
		if(objData[i]['commercially']==1){
			str_option = str_option + "営利目的で使用する。<br>"
		}
		if(objData[i]['fee']==1){
			str_option = str_option + "入場料・受講料を徴収する。<br>"
		}
		if(objData[i]['piano']==1){
			str_option = str_option + "グランドピアノを使用する。<br>"
		}
		if(objData[i]['partition']==0){
			str_option = str_option + "間仕切りをあける。<br>"
		}else{
			str_option = str_option + "間仕切りをしめる。<br>"
		}
		td14.html( str_option );

		td15.html( objData[i]['rmkin'] + "円");
		td16.html( objData[i]['hzkin'] + "円" );
	}

	$('#submit_prev').click(function(){
		
		$('#confirm_form').attr("action","input.php");
		return true;
	});

	$('#submit_next').click(function(){
		$('#confirm_form').attr("action","end.php");
		return true;
	});

	/* submit */
	$('#confirm_form').submit(function(){
		//バリデーションチェックの結果submitしない場合、return falseすることでsubmitを中止することができる。
		//件数を追加;
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
print_r($_POST);
//echo "<br>GET";
//print_r($_GET);

include("navi.php"); 
require_once( "func.php" );
require_once( "model/db.php" );
include("model/Kyaku.php"); 

$Kyaku = unserialize( $_SESSION['Kyaku'] );
$Kyaku->get_user_info( $_SESSION['wloginid'] );

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
        	<span class="f120">現在の時間：　<span id="currentTime"></span>
       	</div>
   </div>
	<h4>入力内容をご確認頂き、問題がなければ送信ボタンを押してください。</h4>
	<!--form name="confirm_form" id="confirm_form" role="form" action="end.php"　method="post"-->
	<form class="form-horizontal" name="confirm_form" id="confirm_form" role="form" method="post">
		<?php 
			echo "<input type='hidden' name='kaigi' id='kaigi' value=\"".$_POST[ 'kaigi' ]."\">";
			echo "<input type='hidden' name='riyokb' id='riyokb' value=\"".$_POST[ 'riyokb' ]."\">";
			echo "<input type='hidden' name='naiyo' id='riyokb' value=\"".$_POST[ 'naiyo' ]."\">";
			echo "<input type='hidden' name='sekinin' id='riyokb' value=\"".$_POST[ 'sekinin' ]."\">";
		?>
		<table id ="rsv_input" class="table table-bordered table-condensed  form-inline">
		    <tbody>
		        <tr><th colspan="5">お申込み内容</th></tr>
		        <tr>
    				<th colspan="2">利用者名</th>
    				<td colspan="6"><?php echo mb_convert_encoding($Kyaku->get_dannm(), "UTF-8", "SJIS"); ?></td>
    			</tr>
		        <tr>
			    	<th colspan="2">メールアドレス</th>
			    	<td  colspan="3"><?php echo $Kyaku->get_mail(); ?>
			        <p>こちらのアドレスに予約受付のメールをお送りいたします。</p>
			        </td>
		        </tr>
		        <tr>
			        <th colspan="2" width="20%">行事名称<span class="red">（必須)</span></th>
			        <td colspan="3" width="70%"><?php echo $_POST[ 'kaigi' ]; ?></td>
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
		      		<th colspan="2">内容</th>
		      		<td colspan="6"><?php echo $_POST[ 'naiyo' ]; ?></td>
		      	</tr>
			    <tr>
		      		<th  colspan="2">使用当日の管理責任者名</th>
		      		<td  colspan="6"><?php echo $_POST[ 'sekinin' ]; ?></td>
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
				<input type='submit' class="btn btn-default btn-lg" role="button" name="submit_prev" id="submit_prev" value='修正する'>
				<input type='submit' class="btn btn-warning btn-lg" role="button" name="submit_next" id="submit_next" value='送信する'>
	        </div>
	    </div>
	</form>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>