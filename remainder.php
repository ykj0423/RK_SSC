<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW,NOARCHIVE">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ログインID/パスワードの確認と再発行 | 神戸市産業振興センター　予約システム</title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!--script src="js/help.js"></script-->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<link href="css/bootstrap-glyphicons.css" rel="stylesheet">
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</head>
<?php
$errmsg = "";
$msg = "";

$ini = parse_ini_file('config.ini');        
$serverName = $ini['SERVER_NAME'];
$connectionInfo = array( "Database"=>$ini['DBNAME'], "UID"=>$ini['UID'], "PWD"=>$ini['PWD'] );
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( isset( $_POST['submit'] ) && ( !empty($_POST['submit'] ) ) ){

	/* 入力チェック */
	if( empty( $_POST['mail'] ) ){
        $errmsg = "メールアドレスを入力してください。";
    }else if( empty( $_POST['remail'] )) {
        $errmsg = "確認のため、もう一度メールアドレスを入力してください。";
    }else if( $_POST['mail'] != $_POST['remail'] ){
        $errmsg = "メールアドレスが一致しません。";
    }

    if(empty($errmsg)){

    	//顧客を検索する
    	$ex = false;

    	$sql = " select kyacd from mt_kyaku where mail = '".$_POST['mail'] ."'";

    	$stmt = sqlsrv_query( $conn, $sql );
        
        if( $stmt === false) {
            return false;
        }

		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
			$kyacd = $row['kyacd'];
			$ex = true;
    	}

    	if(!$ex){
    	
    		$errmsg = "ご指定のメールアドレスは登録されていません。再度メールアドレスをご確認ください。";
    	
    	}else{
    		//一致：いったんたてたsndflgを倒す
    		$sql = "update mt_kyaku set sndflg = 0 where kyacd =".$kyacd;

    		$stmt = sqlsrv_query( $conn, $sql );
        
	        if( $stmt === false) {
	            return false;
	        }

	        $msg = "ご指定のメールアドレスに利用情報のメールが送信されますので、しばらくお待ちください。";

    	}
	
	}else{
	//	echo $errmsg;
	}

}

?>
<body class="container">
<p class="bg-head text-right">神戸市産業振興センター</p>
<h3 id="idh"><span class="midashi">|</span>ログインID、パスワードの確認</h3>
<p>ご登録内容と照会して、ログインIDを記載したメールをお送りいたします。<br>ご利用登録されているメールアドレスを入力して、「送信する」を押してください。</p> 
<form id="id_form" role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">  
<div class="row">
<label class="col-sm-4">ご利用登録されているメールアドレス<span class="red note">(必須)</span></label>
<input class="col-sm-7" type="text" name="mail" value="<?php echo $_POST['mail']; ?>"><br><br>
<label class="col-sm-4">メールアドレス<span class="red note">(再入力)</span></label>
<input class="col-sm-7" type="text" name="remail"  value="<?php echo $_POST['remail']; ?>">
</div>
<p class="text-danger ">
　※迷惑メールの拒否設定をされている場合は、@kobe-ipc.or.jpドメインの受信許可をお願いいたします。
</p>
<a class="btn btn-default" href="login.php" role="button">&lt;&lt;&nbsp;戻る</a>
<input type="submit" class="btn btn-primary" id="submit" name="submit" role="button" value="送信する&nbsp;&gt;&gt;"> 
<!--a href="help.hhtml#loginqa" target="window_name"  onClick="disp('help.hhtml#loginqa')">
<!--li class="glyphicon glyphicon-question-sign" aria-hidden="true">メールアドレスが不明な場合はこちら　>></li></a-->
</form>
<span class="red note"><?php echo $errmsg; ?></span><br>
<?php echo $msg; ?>
</body>
</html>