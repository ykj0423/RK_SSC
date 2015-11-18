<?php
@session_start();

$errmsg = "";
//header
$pageTitle = "新規利用者登録[完了]";
include('include/header.php');
include('model/Kyaku.php');
/**
 * ReserveKeeperWeb予約システム
 *
 * PHP versions 4
 *
 * @category   公益財団法人神戸市産業振興財団／新規利用者登録[完了]
 * @package    none
 * @author     y.kamijo <y.kamijo@gandg.co.jp>
 * @copyright  2015 G&G Co.ltd.
 * @license    G&G Co.ltd.
 * @version    0.1
**/
if( isset( $_POST['submit'] ) && !empty( $_POST['submit'] ) ){
        
    $_SESSION['next_page'] = $_POST['next_page'];
    header( 'location: hitudoku.php' );
    exit();

}

//利用者情報デシリアライズ
$Kyaku = unserialize( $_SESSION['Kyaku'] );
//DB再接続
$Kyaku->connectDb();
//顧客登録
$Kyaku->add_kyaku();

//エラーメッセージ
include('include/err.php');
?>
<p class="bg-head text-right">神戸市産業振興センター</p>
    <h1><span class="midashi">|</span>利用者登録[完了]</h1>

    <div class="f120 mb20">
    ご登録が完了いたしました。<br><br>
    下記がお客様のログインIDとパスワードになります。ログインの際、必要となります。 <br>
	<span class="status2">※この画面を印刷するなどしてログインIDとパスワードを保管してください。</span>
    <br><div class="alert alert-info" role="alert" style="width:50%">
          <h3>ログインID は　 <?php echo $Kyaku->get_wloginid(); ?>です。</h3>
          <h3>パスワード は　 <?php echo $Kyaku->get_wpwd(); ?>です。</h3>
      </div>
	<br>
	<span class="text-danger">※ご注意ください※</span><br>
    ◎ご登録いただいたメールアドレスあてにログインID、パスワードが記載された「利用者登録完了メール」が送付されます。<br>
    もし「利用者登録完了メール」が届かない場合は、メールアドレスの入力間違い、迷惑メールの拒否設定が考えられます。<br>
    　○メールアドレスの入力間違いの場合：<br>
    　　いったんログイン後、「利用者情報」画面でメールアドレスをご確認ください。<br>
    　　誤りがあれば正しいアドレスに変更し、再登録を行ってください。<br>
    　○迷惑メールの設定について：<br>　　設定をご確認のうえ、@kobe-ipc.or.jpドメインの受信許可をしてください。<br>
    　　設定方法はご契約会社により異なります。<br><br>
	◎今後、施設の使用をお申し込みの際は、予約受付やお支払に関するメールが送付されますので、メールアドレスをよくご確認ください。<br>
    ◎セキュリティを確保する為、初回ログイン後にパスワードの変更をおすすめします。<br>
    <a href="http://localhost/rk_ssc/mailtpl/member.txt"><small>受付メールサンプル</small></a><br>
    <!-- もし「利用者登録完了メール」が届かなかった場合、メールアドレスの記載ミス等が考えられますので、<br>
      下記までお問い合わせださい。<br><br>
      ＊＊ お問合せ窓口  TEL:078-360-3200 （お問い合わせ時間：9:00～17:00）＊＊-->
    <br>ご不明な点は下記窓口までお問い合わせださい。<br>
    <div class="alert alert-success" role="alert">
	<h4>＊＊ お問い合わせ窓口＊＊</h4><h3>TEL:078-360-3200 お問い合わせ時間：9:00～17:00</h3> ※登録状況を確認するにあたり、「ログインID」をお知らせください。
	</div>
    </div>    
	<div class="alert alert-info" role="alert" >
		<p class="lead">ご利用開始はこちらから>></p>
		<!--a class="btn btn-primary btn-lg" href="login.php" role="button">空き状況・予約申込　>></a-->
        <form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <input type="submit" name="submit" id="submit" value="空き状況・予約申込　" class="btn btn-primary btn-lg">
            <input type="hidden" name="next_page" value="search.php" class="btn btn-primary btn-lg">
        </form>
		<!--a class="btn btn-primary btn-lg" href="login.html" role="button">予約照会　>></a-->
		<form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <!--a class="btn btn-primary btn-lg" href="login.php" role="button">利用者情報変更　>></a-->
            <input type="submit" name="submit" id="submit" value="利用者情報変更　" class="btn btn-primary btn-lg">
            <input type="hidden" name="next_page" value="member_top.php" class="btn btn-primary btn-lg">
        </form>
	</div>
    </form>
        <a href="http://localhost/rk_ssc/mailtpl/member.txt"  target="_blank" onClick="disp('lhttp://localhost/rk_ssc/mailtpl/member.txt')">
            <li class="glyphicon glyphicon-question-sign" aria-hidden="true">受付メールサンプル</li>
        </a> 
    </div>
 </div>
</body>
</html>
</html>
<script type="text/javascript">
<!--
function disp(url){
    window.open(url, "window_name", "scrollbars=yes");
}
// -->