<?php
@session_start();
/*if(empty($_SESSION['webrk']['user']['userid'])){
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
<title>予約申込み[入力]　 | <?php echo $_SESSION['webrk']['sysname']; ?></title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/custom.js"></script>
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<?php 
include("include/menu.php");
include("model/Kyaku.php"); 
require_once( "func.php" );
require_once( "model/db.php" );

/* データベース接続 */
$db = new DB;
$conErr = $db->connect();
if ( !empty( $conErr ) ) { echo $conErr;  die(); } //接続不可時は終了

//別途validationを実装
class input_validation
{

	//ルールクラスを作成し、インポートする
	//エラー用スタック
	public $errors = array();
 
	function run()
	{
		$ret = true;
		if ( !isset( $_GET['kaigi']) ){
			 array_push($errors, "行事名を入力してください。");
			//文字長チェック
			$ret =  false;
			echo "行事名を入力してください。"."<br />";//仮
		}
		//利用目的
        if ( !isset( $_GET['riyokb'] ) ){
            array_push($errors, "利用目的を入力してください。");
            //数字チェック、範囲チェック
			$ret =  false;
			echo "利用目的を入力してください。"."<br />";//仮
        } 
        //利用人数
        if ( !isset( $_GET['ninzu'] ) ){
            array_push($errors, "人数を入力してください。");
            //数字チェック、範囲チェック
			$ret =  false;
			echo "人数を入力してください。"."<br />";//仮
        } 
		//return true;
		//return $ret;
	}
	
	//エラー取得
	function error()
	{
		return $errors;
	}

}     

$val = new input_validation();

//顧客クラスデシリアライズ
$Kyaku = unserialize( $_SESSION['Kyaku'] );

//echo $Kyaku->get_wloginid();
$Kyaku->get_user_info( $_SESSION['wloginid'] );
//このほうが良い
//$val->add_field('username', 'Your username', 'required');
//if ( $_POST['submit_Click']  == 1 ) {

    //サーバ側入力チェック
    //if ($val->run(array('username' => 'something')))
    /*if ( $val->run() )
    {	
		//成功時、	確認画面へ
		alert("val-run");
		//header('location: confirm.php ');
	}
    else
    {
        // 失敗
        if( ($errors = $val->error() ) != null) {
            foreach( $errors as $error ) {
                //エラーメッセージの表示
                echo "message: ".mb_convert_encoding( $error[ 'message'] ,  "UTF-8" )."<br />";
			    //die();
            }
        }
    }*/
//}
?>
<script>
    jQuery(function () {
        //HTMLを初期化  
        $("table.rsv_input tbody.list").html("");
        
		var objData = JSON.parse(localStorage.getItem("sentaku"));
        if (objData==null){
			return false;
		}
		
		for ( var i = 0; i < objData.length; i++ ){
            
			var tr = $("<tr></tr>");
            var td1 = $("<td></td>");
            var td2 = $("<td></td>");
            var td3 = $("<td></td>");
            var td4 = $("<td></td>");
            var td5 = $("<td></td>");
			var td6 = $("<td></td>");
			var td7 = $("<div></div>");
			var td8 = $("<div></div>");
			var td9 = $("<div></div>");
			var td10 = $("<div></div>");
			var td11 = $("<div></div>");
			
			/* 日付のフォーマット もう少しスマートな方法がないか検討*/
			var usedt = objData[i]['usedt'];
			var useyyyy = usedt.substring(0, 4);
			var usemm = objData[i]['usedt'].substring(4, 6);
			var usedd = usedt.substring(6, 8);
			
			var d = new Date(useyyyy + "/" + usemm + "/" +  usedd);
			var w = ["（日）","（月）","（火）","（水）","（木）","（金）","（土）"];
			var yobi = w[d.getDay()];
			var gyo = i + 1;

			
			/* 命名を配列っぽくしてもいいかもしれない */
			/* 後で変更 jkn1 jkn2 →　stjkn　edjkn */
			$("#list").append(tr);
			tr.append( td1 ).append( td2 ).append( td3 ).append( td4 ).append( td5 ).append( td6 ).append( td7 ).append( td8 ).append( td9 ).append( td10 );
			//td1.html( gyo );
			td1.html( "<a class=\"btn btn-default btnclass\" id='btn-" + objData[i]['rmcd'] + objData[i]['usedt'] + objData[i]['timekb'] + "' name='" + i + "' href=\"#\" role=\"button\">削除</a>" );
			td2.html( useyyyy + "/" + usemm + "/" +  usedd  + yobi + "<br>" + objData[i]['rmnm'] );
			td3.html( objData[i]['jkn1'] + "～" + objData[i]['jkn2'] );
			//td4.html( "時間内訳" );
			td4.html( "<table class=\"table table-condensed  form-inline nest mb0\"><tr><th>催物時間</th><td>"
               + "<input type=\"text\" class=\"form-control\" value=\"\" style=\"width:30px\" maxlength=\"2\" >時"
               + "<input type=\"text\" class=\"form-control\" value=\"\" style=\"width:30px\"  maxlength=\"2\" >分～"
               + "<input type=\"text\" class=\"form-control\" value=\"\" style=\"width:30px\" maxlength=\"2\" >時"
               + "<input type=\"text\" class=\"form-control\" value=\"\" style=\"width:30px\"  maxlength=\"2\" >分"
               + "</td></tr></table>");
			td5.html( "<input type='text' class='form-control' name='ninzu" + i + "' id='ninzu" + i + "' value='' style='width:50px'>人<span class='text-danger'>（必須)</span>" );
			//td6.html( "営利目的での利用" + "入場料・受講料等の徴収" );			
			td6.html( "<table class=\"table table-condensed  form-inline nest2 mb0\"><tr><th>営利目的での利用<br>（販売やPR活動も含む）</th><td>"
	            + "<label class=\"\"><input type=\"radio\" name=\"j0[1]\" value=\"2\">あてはまる</label>"
	            + "<label class=\"\"><input type=\"radio\" name=\"j0[1]\" value=\"2\" >あてはまらない</label>"
	            + "</td></tr><tr><th>入場料・受講料等の徴収</th><td>"
	            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"j1[1]\" value=\"2\">する</label>"
	            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"j1[1]\" value=\"2\">しない</label>"
	            + "</td></tr></table>");
			td7.html( "<input type='hidden' name='rmcd" + i + "' id='rmcd" + i + "' value='" + objData[i]['rmcd'] + "'>" );
			td8.html( "<input type='hidden' name='gyo" + i + "' id='gyo" + i + "' value='" + gyo + "'>" );	//行番
			td9.html( "<input type='hidden' name='usedt" + i + "' id='usedt" + i + "' value=" + useyyyy + usemm + usedd + ">" ); //使用日付
			td10.html( "<input type='hidden' name='timekb" + i + "' id='timekb" + i  + "' value='" + objData[i]['timekb'] + "'>" ); //時間帯
		}

		//フォーム送信時
		$('#input_form').submit(function(){

			// バリデーションチェックや、データの加工を行う。
			if($('#kaigi').val()=='' ){
				alert("行事名を入力してください。");
				return false;
			}
			if($('#riyokb').val()=='' ){
				alert("利用目的を入力してください。");
				return false;
			}
			
			for ( var i = 0; i < objData.length; i++ ){
					if($('#ninzu' + i ).length){
						//後でちゃんと書き直す
						if(  $('#ninzu' + i ).val()=='' ){
							alert("人数を入力してください。");
							return false;
						}
						if(  $('#ninzu' + i ).val()==0){
							alert("人数は０以上で入力してください。");
							return false;
						}
						objData[i]['ninzu'] = $('#ninzu' + i ).val();//入力された人数を格納
					}
			}
			localStorage.removeItem('sentaku');
			//alert('removeitem');
			localStorage.setItem('sentaku', JSON.stringify(objData));
			//alert('setItem');
			
			
			//objData
			//バリデーションチェックの結果submitしない場合、return falseすることでsubmitを中止することができる。
			//return false;
			return true;
		});

		//申し込みをやめる処理
		$(".btnclass").click(function(){
			if (!confirm('この施設のお申込みを取りやめます。よろしいですか？')) {
				return false;
			}
			//name属性 からrowNo取得し、該当DOMを消去。
			var rowNo = $(this).attr("name"); 
			
			//var lnkstr = $(this).attr("id"); 
			var btnkey = $(this).attr("id").replace('btn-','a-');
			//alert(btnkey);
			var strlist = JSON.parse(localStorage.getItem("sentaku"));//選択リスト
			//alert(btnkey);
			//console.log( btnkey );
			//var removed;
			
                //strlist.some(function (v, i) {
                //    if (v.key == lnkstr) strlist.splice(i, 1); //key:lnkstrの要素を削除
                //});
                //localStorage.setItem('sentaku', JSON.stringify(strlist));
			//該当の予約をstrlistから除く
			for ( var i = 0; i < strlist.length; i++ ){
				if( btnkey ==  strlist[i]['key']){
					//alert(btnkey);
					strlist.splice(i, 1);
				}
			}
			//localStorage.removeItem('sentaku');
			$( '#list tr' ).eq( rowNo ).remove();
			localStorage.setItem('sentaku', JSON.stringify(strlist));

		});
		
		
		//ログアウト時ローカルストレージクリア
		$(".logout").click(function(){			
			var wklist = JSON.parse(localStorage.getItem("sentaku"));
			
			for ( var i = 0; i < wklist.length; i++ ){
					wklist.splice(i, 1);
			}			
			localStorage.setItem('sentaku', JSON.stringify(wklist));
        });

		//$('#ninzu').change(function() {
        //    isChange = true;
        //    console.log("Hello world");
        //});

        //formを作成
            //サーバ側入力チェック

		/*$('#submit_Click').click(function() {
			//var val = $('#my-form [name=my-text]').val();
			//console.log(val);  //
			$('#inpit_form').submit();
		});*/

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
		    form.append($('<input>',{type:'hidden',name:'btnid',value:anc}));
		    //作成したformでsubmitする
		    form.submit();
		    return false;
	    });*/
		

		//var val = $('#my-form [name=my-text]').val();
		//console.log(val);  //
		
		/*
		jQuery.post( url [,object] [function] [type] )ver1.0〜
		・url：読む込むデータのurl
		・object：サーバに送るデータを設定。値の型はobjectオブジェクト。
		・function：通信が「成功」したら実行される処理を設定。以下の引数を受け取る
		　・第1引数：取得したデータ
		　・第2引数：状態（success、error、notmodified、timeout、parsererror）
		　・第3引数：jqXHRオブジェクト
		・type：予期されるデータの形式（省略してもxml,json,script,html位は判断してくれます）
		*/
		
    });   
 </script>
</head>

<body class="container">
<!-- title -->
   <div class="row">
      	<div class="col-xs-6" style="padding:0">
        <h1><span class="midashi">|</span>予約申込み[入力]</h1>
       </div>
      	<div class="col-xs-6  text-right">
          <span class="f120">現在の時間：　<span id="currentTime"></span></span>
       </div>
   </div>
<!-- title -->
<!-- main -->
	<h4>必要事項をご記入のうえ、「確認画面へ」ボタンを押してください。</h4>
  <div class="row mb20">
    	<p class="col-xs-8">・受付は先着順となっております。<br>
    ・お申し込み後、ご登録いただいたメールアドレスに宛に、受付可否のメールを送信いたします。<br>
    ・受付状況は予約照会画面でもご覧いただけます。<br>
    ・「削除」ボタン：施設のお申し込みを取りやめる場合、押下してください。該当行のみ取り消されます。 <br>
（※いったん「削除」を押すと元に戻せません。ご注意ください。）
    <br><br>
		※マイク・スクリーン・プロジェクター・アンプ・レーザーポインターは、各会議室にご用意しております。<br>使用当日に9階のサービスステーションにお申し付けください。<br><br>
    <!-- ※お申込自体を取りやめたい行がある場合、「削除」ボタンを押してください。-->

<br>

    </p>
    
    <div class="col-xs-4">
    <a href="help.html#input"  class="btn alert-info" target="window_name"  onClick="disp('help.html#input')"><li class="glyphicon glyphicon-question-sign" aria-hidden="true">&nbsp;この画面の操作方法についてはこちら>></li></a> 
<a href="loginqa.html#input"   class="btn alert-danger" target="window_name"  onClick="disp('loginqa.html#input')"><li class="glyphicon glyphicon-exclamation-sign" aria-hidden="true">&nbsp;予約申込にあたってのご注意はこちら>></li></a> 
    </div>
  </div>

<span class="status2">＊＊ この画面では、ご予約は確保されていません。ご希望の内容を送信後、受付結果をメールでお知らせいたします。＊＊</span><br><br>
<form name="input_form" id="input_form" role="form"  action="confirm.php" method="post">
  <table id ="rsv_input" class="table table-bordered table-condensed  form-inline">
      	<tr><th colspan="7">お申込み内容</th></tr>
      	<tr>
			<th  colspan="2">利用者名</th>
      		<td  colspan="5">
			<?php echo mb_convert_encoding($Kyaku->get_dannm(), "UTF-8", "SJIS"); ?>
			</td>
      	</tr>
      	<tr>
      		<th  colspan="2">メールアドレス</th>
      		<td  colspan="5">
			<?php echo $Kyaku->get_mail(); ?>
      		 <br>※お申し込み受け付け時、こちらのアドレスに請求書のメールが送付されますのでご注意ください。
      		</td>
      	</tr>
      	<tr>
      		<th colspan="2" width="20%">行事名<span class="text-danger">（必須)</span></th>
      		<td colspan="5" width="70%"><input type="text"  class="form-control" name="kaigi" id="kaigi" value="" style="width:70%">
      			<br>こちらの内容が案内板に表示されます。<span class="note">（例：人材育成セミナー、ピアノ発表会、幹部会議…)</span></td>
      	</tr>
      	<tr>
		<th colspan="2">利用目的<span class="text-danger">（必須)</span></th>
      		<td colspan="5">
      		<select  class="form-control" name="riyokb"  id="riyokb">
			<option value="">(選択してください)</option>
		<?php 
		//利用目的をマスタから読み込む
		$riyo = $db->get_mm_riyo();

		for ($i = 0; $i < ( count( $riyo ) ) ; $i++ ) {
		
			$riyocd = $riyo[ $i ][ 'code' ];   			//施設コード
			$riyonm = mb_convert_encoding($riyo[ $i ][ 'name' ], "utf8", "SJIS");//施設名称
			echo "<option value=\"".$riyocd."\">".$riyonm."</option>";
		}
		
		?>
      		</select>
      		</td>
      	</tr>
      	
        </tbody>
        <tbody id="">
          	<tr><th colspan="7">お申込み施設</th></tr>
      	    <tr>
      		    <th width="5%">No.</th>
      		    <th width="10%">利用日/施設名</th>
      		    <th width="8%">利用時間</th>
				<th width="25%">時間内訳<span class="text-danger">（必須)</span></th>
      		    <th width="8%">利用人数</th>
      		    <th >その他確認事項<span class="text-danger">（必須)</span></th>
      	    </tr>        
		<!-- お申し込み明細エリア-->
		<tbody id="list">
        </tbody>
        <tbody>
			 <!--tr><th colspan="7">ご注意！</th></tr>
      	    <tr>
      		    <td colspan="7" class="text-center">
                    <div class="form-control noboder">
 						※複数お申し込みの場合は、使用可能な施設だけ予約されます。
                    </div>
                </td>
      	    </tr-->
        </tbody>
	</table>
	<a class="btn btn-default btn-lg" href="search.php" role="button"><<　戻る</a>
	<!-- a class="btn btn-warning btn-lg" role="button" name="" id="" value=1>確認画面へ　>></a-->
	<input type='submit' class="btn btn-warning btn-lg" role="button" name="submit_Click" id="submit_Click" value="確認画面へ&nbsp;>>">
</form>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>