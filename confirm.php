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
    var total = 0;
	for ( var i = 0; i < objData.length; i++ ){

		var tr = $("<tr></tr>");
		var td1 = $("<td></td>");
		var td2 = $("<td></td>");
		var td3 = $("<td></td>");
		var td4 = $("<td class=\"text-right\"></td>");
		var td5 = $("<td></td>");
		var td6 = $("<td></td>");
		var td7 = $("<td></td>");
		var td8 = $("<div></div>");
		var td9 = $("<div></div>");
		var td10 = $("<div></div>");
		var td11 = $("<div></div>");
		var td12 = $("<div></div>");
		var td13 = $("<div></div>");
		var td14 = $("<div></div>");
		var td15 = $("<div></div>");
		var td16 = $("<div></div>");
		var td17 = $("<div></div>");
		var td18 = $("<div></div>");
		var td19 = $("<div></div>");
		var td20 = $("<div></div>");
		var td21 = $("<div></div>");
		var td22 = $("<div></div>");
		var td23 = $("<div></div>");
		var td24 = $("<div></div>");
		//var td25 = $("<div></div>");
		/* 日付のフォーマット もう少しスマートな方法がないか検討*/
		/* 前の画面とまったく同じ処理を書いているので整理*/
		var usedt = objData[i]['usedt'];
		var useyyyy = usedt.substring(0, 4);
		var usemm = objData[i]['usedt'].substring(4, 6);
		var usedd = usedt.substring(6, 8);
	
		var d = new Date(useyyyy + "/" + usemm + "/" +  usedd);
		var w = ["日","月","火","水","木","金","土"];
		var yobikb = d.getDay();
		var yobi = w[yobikb];
		var gyo = i + 1;

		$("#list").append(tr);
		tr.append( td1 ).append( td2 ).append( td3 ).append( td4 ).append( td5 ).append( td6 ).append( td7 ).append( td8 ).append( td9 ).append( td10 ).append( td11 ).append( td12 ).append( td13 ).append( td14 ).append( td15 ).append( td16 ).append( td17 ).append( td18 ).append( td19 ).append( td20 ).append( td21 ).append( td22 ).append( td23 ).append( td24 );//.append( td25 );
		td1.html( useyyyy + "/" + usemm + "/" +  usedd + " ("+ yobi + ")<br>" + objData[i]['rmnm'] );
		td2.html( objData[i]['jkn1']  + "～" + objData[i]['jkn2']  );

		var jjkn_dsp = objData[i]['jstjkn_h'] + "：" + objData[i]['jstjkn_m'] + "～" + objData[i]['jedjkn_h'] + "：" + objData[i]['jedjkn_m'];
		//var jjkn = parseInt( objData[i]['jstjkn_h'] ) * 100 +  parseInt( objData[i]['jstjkn_m'];
		td3.html( jjkn_dsp );
		var jjkn;
		jjkn = 0;
		var hjkn_dsp = objData[i]['hstjkn_h'] + "：" + objData[i]['hstjkn_m'] + "～" + objData[i]['hedjkn_h'] + "：" + objData[i]['hedjkn_m'];
		//var jjkn = parseInt( objData[i]['jstjkn_h'] ) * 100 +  parseInt( objData[i]['jstjkn_m'];
		td3.html( jjkn_dsp + "<br>" + hjkn_dsp );
		var jjkn;
		jjkn = 0;
		var hstjkn;
		var hedjkn;
		//hstjkn = parseInt( objData[i]['jstjkn_h'] ) * 100 +  parseInt( objData[i]['jstjkn_m'];
		hstjkn = 900;
		hedjkn = 1200;

		/*if(!(objData[i]['jstjkn_h']){
		}else{
			if(isNaN(objData[i]['jstjkn_h'])
			{

			}else{
			 jjkn1 = parseInt( objData[i]['jstjkn_h'] );
			}
			jjkn1=0;
		}*/
		
		td4.html( objData[i]['ninzu']+"人" );
		var str_option='';
		if(objData[i]['commercially']==1){
			str_option = str_option + "・営利目的での利用（販売やPR活動も含む）：あてはまる<br>";
		}
		if(objData[i]['fee']==1){
			str_option = str_option + "・入場料・受講料等の徴収：する<br>";
		}
		if(objData[i]['piano']==1){
			str_option = str_option + "・グランドピアノの使用：する<br>";
			objData[i]['hzkin'] = 13000;
		}
		if(objData[i]['partition']==0){
			str_option = str_option + "・間仕切り：あける<br>";
		}else{
			str_option = str_option + "・間仕切り：しめる<br>";
		}
		td5.html( str_option );
		
		var rmkin;
		var hzkin;
		var comlkb;

		rmkin = parseInt( objData[i]['rmkin'] );
		hzkin = parseInt( objData[i]['hzkin'] );
		comlkb = 0;
		
		if((objData[i]['commercially']==1)&&(objData[i]['fee']==1)){

			rmkin = rmkin * 1.5;
			comlkb = 1
			objData[i]['rmkin'] = rmkin;

		}
		
		total = parseInt(total) + parseInt(rmkin) + parseInt(objData[i]['hzkin']);

		td6.html( "\\" + rmkin.toLocaleString() );
		td7.html( "\\" + hzkin.toLocaleString() );
		td8.html( "<input type='hidden' name='rmcd" + i + "' id='rmcd" + i + "' value='" + objData[i]['rmcd'] + "'>" );
		td9.html( "<input type='hidden' name='gyo" + i + "' id='gyo" + i + "' value='" + gyo + "'>" );	//行番
		td10.html( "<input type='hidden' name='usedt" + i + "' id='usedt" + i + "' value=" + useyyyy + usemm + usedd + ">" ); //使用日付
		td11.html( "<input type='hidden' name='timekb" + i + "' id='timekb" + i  + "' value='" + objData[i]['timekb'] + "'>" ); //時間帯
		//var stjkn = 900;
		//var edjkn = 1200;
		var stjkn = objData[i]['jkn1'].replace( ':','' );
		var edjkn = objData[i]['jkn2'].replace( ':','' );
		td12.html( "<input type='hidden' name='stjkn" + i + "' id='stjkn" + i + "' value='" + stjkn +"'><input type='text' name='edjkn" + i + "' id='edjkn" + i + "' value='"+ edjkn + "'>" );
		td13.html( "<input type='hidden' name='ninzu" + i + "' id='ninzu" + i + "' value='" + objData[i]['ninzu'] +"'");
		td14.html( "<input type='hidden' name='rmkin" + i + "' id='rmkin" + i + "' value='" + parseInt( objData[i]['rmkin'] ) + "'>");
		td15.html( "<input type='hidden' name='hzkin" + i + "' id='hzkin" + i + "' value='" + parseInt( objData[i]['hzkin'] ) + "'>");
		td16.html( "<input type='hidden' name='piano" + i + "' id='piano" + i + "' value='" + objData[i]['piano'] + "'>");
		td17.html( "<input type='hidden' name='partition" + i + "' id='partition" + i + "' value='" + objData[i]['partition'] + "'>");
		td18.html( "<input type='hidden' name='yobi" + i + "' id='yobi" + i + "' value='" + yobi + "'>");//曜日
		td19.html( "<input type='hidden' name='yobikb" + i + "' id='yobikb" + i + "' value='" + yobikb + "'>");//曜日区分
		td20.html( "<input type='hidden' name='rmnm" + i + "' id='rmnm" + i + "' value='" + objData[i]['rmnm'] + "'>");//施設名
		td21.html( "<input type='hidden' name='hbstjkn" + i + "' id='hbstjkn" + i + "' value='" + hstjkn + "'>");
		td22.html( "<input type='hidden' name='hbedjkn" + i + "' id='hbedjkn" + i + "' value='" + hedjkn + "'>");
		td23.html( "<input type='hidden' name='comlkb" + i + "' id='comlkb" + i + "' value='" + comlkb + "'>");
		td23.html( "<input type='hidden' name='biko" + i + "' id='biko" + i + "' value=''>");
	
//（空室マーク）usedt:使用日、rmcd:施設コード、timekb:時間帯
//（請求データ）usedt:使用日、yobi:曜日、yobikb:曜日区分、rmcd:施設コード、rmnm:施設名、
//stjkn：開始時間、edjkn：終了時間、hstjkn本番開始時間、hedjkn:本番終了時間、piano：ピアノ区分、rmkin：施設使用料金額、hzkin：付属設備使用料金額

//alert("mei");

	}
	
	$("#total").html("\\" + total.toLocaleString());

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
//print_r($_POST);
//echo "<br>GET";
//print_r($_GET);

//include("navi.php"); 
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
   <h4>入力内容をご確認いただき、問題がなければ「送信する」ボタンを押してください。</h4>
  <span class="status2">＊＊ この画面では、ご予約は確保されていません。ご希望の内容を送信後、受付結果をメールでお知らせいたします。＊＊</span><br><br>
	<!--form name="confirm_form" id="confirm_form" role="form" action="end.php"　method="post"-->
	<form class="form-horizontal" name="confirm_form" id="confirm_form" role="form" method="post">
		<?php 
			
			echo "<input type='hidden' name='kaigi' id='kaigi' value=\"".$_POST[ 'kaigi' ]."\">";
			echo "<input type='hidden' name='riyokb' id='riyokb' value=\"".$_POST[ 'riyokb' ]."\">";
			
			$str_naiyo = "<input type='hidden' name='naiyo' id='riyokb' value=\"";

			if(isset($_POST[ 'naiyo' ])){
				$str_naiyo .= $_POST[ 'naiyo' ];	
			}
			
			$str_naiyo .= "\">";
			//echo $str;

			//echo "<input type='hidden' name='naiyo' id='riyokb' value=\"".$_POST[ 'naiyo' ]."\">";
			echo "<input type='hidden' name='sekinin' id='riyokb' value=\"".$_POST[ 'sekinin' ]."\">";
		?>

		<table id ="rsv_input" class="table table-bordered table-condensed  form-inline">
		    <tbody>
		        <tr><th colspan="8">お申込み内容</th></tr>
		        <tr>
    				<th colspan="2">利用者名</th>
    				<td colspan="6"><?php echo mb_convert_encoding($Kyaku->get_dannm(), "UTF-8", "SJIS"); ?></td>
    			</tr>
		        <tr>
			    	<th colspan="2">メールアドレス</th>
			    	<td  colspan="6"><?php echo $Kyaku->get_mail(); ?>
			        <p>こちらのアドレスに予約受付のメールをお送りいたします。</p>
			        </td>
		        </tr>
		        <tr>
			        <th colspan="2" width="20%">行事名称</th>
			        <td colspan="6" width="70%"><?php echo $_POST[ 'kaigi' ]; ?></td>
		        </tr>
		        <tr>
			        <th colspan="2">利用目的</th>
			        <td colspan="6">
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
		         	<?php if( isset( $_POST[ 'naiyo' ] )  && ( !empty( $_POST[ 'naiyo' ] ) ) ){ ?>
		         	<tr>
					<th colspan="2">内容</th>
		      		<td colspan="6"><?php echo $_POST[ 'naiyo' ]; ?></td>
		      		</tr>
		         	<?php } ?>
			    <tr>
		      		<th  colspan="2">使用当日の管理責任者名</th>
		      		<td  colspan="6"><?php echo $_POST[ 'sekinin' ]; ?></td>
		      	</tr>			   
		        <tr><th colspan="8">お申し込み施設</th></tr>
		    	<tr>
		    	<th width="12%">使用日/施設名</th>
		    	<th width="12%">使用時間</th>
		    	<th width="22%">時間内訳</th>
		      	<th width="8%">利用人数</th>
		      	<th>その他確認事項</th>
		    	<th>使用料</th>
		    	<th>設備料</th>
		    	</tr>
	        </tbody>
	        <tbody id="list">
	        </tbody>
	        <tbody>
	          	<tr>
        		<td class="text-right  f120" colspan="6">使用料合計</th>
         		<td colspan="2" class="text-right f120"><div id="total">\0</div></th>
      			</tr>
	    	</tbody>
	        </table>
	        <span class="red">ホールのご利用時の人件費は上記使用料に含まれません。別途ご請求させていただきます。<br>詳細は事前打合せで決定いたします。使用日の1か月前までに必ず事前打合せをお願いいたします。</span><br><br>
			 <div class="form-group">
			 	<div class="row mb20">
					<input type='submit' class="btn btn-default btn-lg" role="button" name="submit_prev" id="submit_prev" value='修正する'>
					<input type='submit' class="btn btn-primary btn-lg" role="button" name="submit_next" id="submit_next" value='送信する'>
				</div>
	        </div>
	    </div>
	</form>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>