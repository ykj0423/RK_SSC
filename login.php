<?php
@session_start();

$errmsg = "";
//header
$pageTitle =  "ログイン";
include('include/header.php');
include('model/Kyaku.php');
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

if( isset( $_POST['submit'] ) ){

    if (empty($_POST['wloginid'])){
        $errmsg = "ログインIDを入力してください。"; 
    }else if (empty($_POST['wpwd'])){
        $errmsg = "パスワードを入力してください。";  
    }
    
    //ログインIDとパスワードのチェック
    $Kyaku = new Kyaku();

    $login = $Kyaku->login( $_POST['wloginid'], $_POST['wpwd'] );

    if(!$login){
        $errmsg = "ログインIDもしくはパスワードが違います。";
    }

    if ( !$errmsg ) {
        $_SESSION['wloginid'] = $_POST['wloginid'];
        header( 'location: hitudoku.php' );
        exit();
    }

}

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
                        <input type="text" class="form-control" id="wloginid" name="wloginid">
                    </div>
                </div>
                <div class="form-group text-center">
                    <label class="col-xs-5 control-label" for="pass">パスワード</label>
                    <div class="col-xs-3">
                        <input type="password" class="form-control" id="wpwd" name="wpwd">
                    </div>
                </div>
                 <div class="form-group">
                    <div class="col-xs-offset-5 col-xs-1">
                        <!-- //TODO -->
                        <a href="top.php"><input type="submit" value="戻る" class="btn btn-default btn-lg"></a>
                    </div>
                    <div class="col-xs-1">
                        <input type="submit" name="submit" id="submit" value="ログイン" class="btn btn-primary btn-lg">
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