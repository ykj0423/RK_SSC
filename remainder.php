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

</head>
<?php
    
    if( empty( $_POST['mail'] ) ){
        $errmsg = "メールアドレスを入力してください。";
    }else if( empty( $_POST['remail'] )) {
        $errmsg = "確認のため、もう一度メールアドレスを入力してください。";
    }else if( $_POST['mail'] != $_POST['remail'] ){
        $errmsg = "メールアドレスが一致しません。";
    }

    //顧客を検索する

    //不一致

    //一致：sndflgのupdate
$sql = "update mt_kyaku set mail = '".$mail_adress."' where wloginid ='".$wloginid."'";

        $stmt = sqlsrv_query( $this->conn, $sql );
        
        if( $stmt === false) {
            //TODO
            //echo $sql;
            //echo "stmterr";
            return false;
        }

        return true;
//update
                    $sql = "UPDATE ks_jkntai SET ukeno=(?), gyo=(?),login=(?), udate=(?), utime=(?)";
                    $sql = $sql." WHERE usedt=(?) AND rmcd=(?) AND jikan=(?) AND timekb=(?)";
                    $params = array( $ukeno, $gyo, $login, parent::getUdate(), parent::getUtime() , $usedt, $rmcd, $jkn, $timekb );

               
           
                $stmt = sqlsrv_query( $this->conn, $sql, $params );
            
                if( $stmt === false) {
                    $tran = false;
                    //echo $sql;
                    //print_r($params);
                    break;//exit for
                }




?>
<body class="container">
<p class="bg-head text-right">神戸市産業振興センター</p>


<h3 id="idh"><span class="midashi">|</span>ログインIDの確認はこちら</h3>
<p>ご登録内容と照会して、ログインIDを記載したメールをお送りいたします。<br>ご利用登録されているメールアドレスを入力して、「送信する」を押してください。</p> 
   
<div class="row">
<label class="col-sm-4">ご利用登録されているメールアドレス<span class="red note">(必須)</span></label>
<input class="col-sm-7" type="text" name="email" value=""><br><br>
<label class="col-sm-4">メールアドレス<span class="red note">(再入力)</span></label>
<input class="col-sm-7" type="text" name="email" value="">
</div>
<p class="text-danger ">
　※迷惑メールの拒否設定をされている場合は、@kobe-ipc.or.jpドメインの受信許可をお願いいたします。
</p>
<a class="btn btn-default" href="javascript:history.back();" role="button">&lt;&lt;&nbsp;戻る</a>
<input type="submit" class="btn btn-primary" id="submit" name="submit" role="button" value="送信する&nbsp;&gt;&gt;"> 
<!--a href="help.hhtml#loginqa" target="window_name"  onClick="disp('help.hhtml#loginqa')">
<!--li class="glyphicon glyphicon-question-sign" aria-hidden="true">メールアドレスが不明な場合はこちら　>></li></a--> 
<br>
<br><br><br><br>
<hr>



<h3 id="idh"><span class="midashi">|</span>ログインIDの確認はこちら</h3>
<p>ご登録内容と照会して、ログインIDを記載したメールをお送りいたします。<br>ご利用登録されているメールアドレスを入力して、「送信する」を押してください。</p>
<form id="wloginidt_form" role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div class="row">
<label class="col-sm-4">ご利用登録されているメールアドレス</label><br>
<span class="red note">(必須)</span><input type="text" name="mail" style="width:60%" value=""><br>
<span class="red note">(再入力)</span><input type="text" name="remail" style="width:60%" value="">
</div>
<p class="text-danger ">
　※迷惑メールの拒否設定をされている場合は、@kobe-ipc.or.jpドメインの受信許可をお願いいたします。
</p>
<a class="btn btn-default " href="login.php" role="button">&lt;&lt;&nbsp;戻る</a>
<!--a href="help.html#loginqa" target="window_name" onClick="disp('help.hhtml#loginqa')">
<li class="glyphicon glyphicon-question-sign" aria-hidden="true">メールアドレスが不明な場合はこちら　&gt;&gt;</li>
</a--> 
<input type="submit" class="btn btn-primary" id="submit" name="submit" role="button" value="送信する&nbsp;&gt;&gt;"> 
</form>


<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>