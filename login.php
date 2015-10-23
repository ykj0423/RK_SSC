<?php
@session_start();

$errmsg = "";
//header
$pageTitle =  "ログイン";
include('include/header.php');

/**
 * ReserveKeeperWeb予約システム
 *
 * PHP versions 4
 *
 * @category   公益財団法人神戸市産業振興財団／ログイン
 * @package    none
 * @author     y.kamijo <y.kamijo@gandg.co.jp>
 * @copyright  2015 G&G Co.ltd.
 * @license    G&G Co.ltd.
 * @version    0.1
**/

//TODO DBにログインしてパスワードチェック
//require_once("model/Kyaku.php");
//$obj = new Kyaku();
//$conErr = $obj->connect();
//if (!empty($conErr)) { echo $conErr; die();}

if (!empty($_POST['loginid'])){

    $errmsg = "ログインIDを入力してください。"; 

}else if (!empty($_POST['pass'])){
    $errmsg = "パスワードを入力してください。"; 
}

if (!$errmsg) {
    header('location: '.$_SESSION['next_page']);
    exit();
}
//$obj->close();

//エラーメッセージ
include('include/err.php');
?>
    <div class="text-center">
        <p>発行された「ログインID」と「パスワード」をご入力いただき、「ログイン」ボタンを押してください。</p>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-horizontal">
                <div class="form-group text-center">
                    <label class="col-xs-5 control-label" for="loginid">ログインID</label>
                    <div class="col-xs-3">
                        <input type="text" class="form-control" id="loginid" name="loginid">
                    </div>
                </div>
                <div class="form-group text-center">
                    <label class="col-xs-5 control-label" for="pass">パスワード</label>
                    <div class="col-xs-3">
                        <input type="password" class="form-control" id="pass" name="pass">
                    </div>
                </div>
                 <div class="form-group">
                    <div class="col-xs-offset-5 col-xs-1">
                        <a href="top.html"><input type="submit" value="戻る" class="btn btn-default btn-lg"></a>
                    </div>
                    <div class="col-xs-1">
                        <a href="hitudoku.html"><input type="submit" value="ログイン" class="btn btn-primary btn-lg"></a>
                    </div>
                </div>
            </div>
        </form>
        <a href="loginqa.html#login"  target="window_name"  onClick="disp('loginqa.html#login')"><li class="glyphicon glyphicon-question-sign" aria-hidden="true">ログインでお困りの方はこちら</li></a> 
    </div>
<script type="text/javascript">
<!--
function disp(url){
	window.open(url, "window_name", "scrollbars=yes");
}
// -->
</body>
</html>