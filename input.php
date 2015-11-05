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
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="js/bootstrap.min.js"></script>
<script src="js/custom.js"></script>
<script src="js/input.reserve.js"></script>
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
        } 
        //利用人数
        if ( !isset( $_GET['ninzu'] ) ){
            array_push($errors, "人数を入力してください。");
            //数字チェック、範囲チェック
			$ret =  false;
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
$Kyaku->get_user_info( $_SESSION['wloginid'] );

?>
</head>
<body class="container">
   	<div class="row">
    <div class="col-xs-6" style="padding:0">
    	<h1><span class="midashi">|</span>予約申込み[入力]</h1>
    </div>
    <div class="col-xs-6  text-right">
    	<span class="f120">現在の時間：　<span id="currentTime"></span></span>
	</div>
	</div>
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
    <br>
	</p>
	<div class="col-xs-4">
		<a href="help.html#input" class="btn alert-info" target="window_name" onClick="disp('help.html#input')"><li class="glyphicon glyphicon-question-sign" aria-hidden="true">&nbsp;この画面の操作方法についてはこちら>></li></a> 
		<a href="loginqa.html#input" class="btn alert-danger" target="window_name" onClick="disp('loginqa.html#input')"><li class="glyphicon glyphicon-exclamation-sign" aria-hidden="true">&nbsp;予約申込にあたってのご注意はこちら>></li></a> 
	</div>
</div>
<span class="status2">＊＊ この画面では、ご予約は確保されていません。ご希望の内容を送信後、受付結果をメールでお知らせいたします。＊＊</span><br><br>
<form name="input_form" id="input_form" role="form"  action="confirm.php" method="post">
	<table id ="rsv_input" class="table table-bordered table-condensed  form-inline">
  	<tr><th colspan="7">お申込み内容</th></tr>
  	<tr>
		<th colspan="2">利用者名</th>
  		<td colspan="5">
		<?php echo mb_convert_encoding($Kyaku->get_dannm(), "UTF-8", "SJIS"); ?>
		</td>
  	</tr>
  	<tr>
  		<th colspan="2">メールアドレス</th>
  		<td colspan="5">
		<?php echo $Kyaku->get_mail(); ?>
  		<br>※お申し込み受け付け時、こちらのアドレスに請求書のメールが送付されますのでご注意ください。
  		</td>
  	</tr>
  	<tr>
  		<th colspan="2" width="20%">行事名<span class="text-danger">（必須)</span></th>
  		<td colspan="5" width="70%">
  			<input type="text"  class="form-control" name="kaigi" id="kaigi" value="<?php echo $_POST[ 'kaigi' ]; ?>" style="width:70%">
  			<br>こちらの内容が案内板に表示されます。<span class="note">（例：人材育成セミナー、ピアノ発表会、幹部会議…)</span>
  		</td>
  	</tr>
  	<tr>
	<th colspan="2">利用目的<span class="text-danger">（必須)</span></th>
  		<td colspan="5">
  		<select  class="form-control" name="riyokb" id="riyokb">
		<option value="">(選択してください)</option>
	<?php 
	//利用目的をマスタから読み込む
	$riyo = $db->get_mm_riyo();

	for ($i = 0; $i < ( count( $riyo ) ) ; $i++ ) {
	
		$riyocd = $riyo[ $i ][ 'code' ];   			//施設コード
		$riyonm = mb_convert_encoding($riyo[ $i ][ 'name' ], "utf8", "SJIS");//施設名称

		if ( $_POST[ 'riyokb' ] == $riyocd ){
			echo "<option value=\"".$riyocd."\" selected>".$riyonm."</option>";
		}else{
			echo "<option value=\"".$riyocd."\">".$riyonm."</option>";
		}
	
	}
	
	?>
  		</select>
  		</td>
  	</tr>
	<tr>
		<th colspan="2" width="20%">内容</th>
		<td colspan="5" width="70%">
			<input type="text" maxlength="10" class="form-control" name="naiyo" id="naiyo" value="<?php echo $_POST[ 'naiyo' ]; ?>" style="width:20%"><br>利用目的が「その他」の場合、具体的なご利用内容をご入力ください。<span class="note">（例：詩吟、カラオケ…)</span>
		</td>
	</tr>
  	<tr>
  		<th colspan="2">利用当日の管理責任者名<span class="text-danger">（必須)</span></th>
  		<td colspan="5">
  			<input type="text" class="form-control" name="sekinin"  id="sekinin" value="<?php echo $_POST[ 'sekinin' ]; ?>" style="width:70%">
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
			<th width="8%">利用人数<br><span class='text-danger'>（必須)</span></th>
			<th >その他確認事項<span class="text-danger">（必須)</span></th>
		</tr>       
		<!-- お申し込み明細エリア-->
		<tbody id="list">
        </tbody>
	</table>
	<a class="btn btn-default btn-lg" href="search.php" role="button"><<　戻る</a>
	<input type='submit' class="btn btn-primary btn-lg" role="button" name="submit_Click" id="submit_Click" value="確認画面へ&nbsp;>>">
</form>
</body>
</html>