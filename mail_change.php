<?php
@session_start();
require_once('model/Kyaku.php');
$errmsg = "";
//header
$pageTitle =  "メールアドレス変更";
include('include/header.php');
?>
</head>
<body class="container">
<p class="bg-head text-right"><?php echo $_SESSION['centername']; ?></p>
<?php
//メニュー
include('include/menu.php');
?>
<h1><span class="midashi">|</span><?php echo $pageTitle; ?><?php echo "<small>".$_SESSION['sysname']."</small>" ?></h1>
<?php
/**
 * ReserveKeeperWeb予約システム
 *
 * PHP versions 4
 *
 * @category   公益財団法人神戸市産業振興財団／メールアドレス変更
 * @package    none
 * @author     y.kamijo <y.kamijo@gandg.co.jp>
 * @copyright  2015 G&G Co.ltd.
 * @license    G&G Co.ltd.
 * @version    0.1
**/

$infomsg = "";

//メールアドレス
$mail_adress = "";

$Kyaku = new Kyaku();
$mail_adress = $Kyaku->get_mail_adress( $_SESSION['wloginid'] );

if( empty( $_POST['regist'] ) ){
    

    $infomsg = "予約申込の結果等はメールで連絡します。<br>連絡のとれるメールアドレスをご入力のうえ、「このアドレスで登録する」ボタンを押してください。";

}else{

//print_r($_POST);
 
    if( empty( $_POST['mail'] ) ){
        $errmsg = "メールアドレスを入力してください。";
    }else if( empty( $_POST['remail'] )) {
        $errmsg = "確認のため、もう一度メールアドレスを入力してください。";
    }else if( $_POST['mail'] != $_POST['remail'] ){
        $errmsg = "メールアドレスが一致しません。";
    }else{
        if( $Kyaku->change_mail_adress( $_SESSION['wloginid'] , $_POST['mail'] ) ){
            $infomsg = "メールアドレスの変更が完了致しました。";
            $mail_adress = $_POST['mail'];
        }
    }
}

//エラーメッセージ
include('include/err.php');
?>
<p><?php echo $infomsg; ?></p><br>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <table class="table table-bordered">
        <tbody>
        <!--tr><th width="30%">団体名</th><td>G&G株式会社</td></tr>
        <tr><th>ログインID</th><td>012345678</td></tr>
        <tr><th>新パスワード<span class="text-danger">(必須)</span></th><td><input type="password" name="pass" value=""></td></tr>
        <tr><th>新パスワード(再入力)</th><td><input type="password" name="pass" value=""></td></tr-->
        <tr><th>現在のメールアドレス</th>
        <td>
            <?php echo $mail_adress; ?><br>
        </td>
        <tr><th>新しいメールアドレス</th>
        <td>
            <input type="text" name="mail" style="width:60%" value=""><br>
            <span class="text-danger">※ご注意ください※</span><br>
                ※「テスト送信」ボタンを押しすと、テストメールが送信されます。<br>メールが正しく届くかどうかを事前に確認するためにご利用ください。<br>
                もし「テストメール」が届かない場合は、メールアドレスの入力間違い、迷惑メールの拒否設定が考えられます。<br>

                ◎変更完了後、ご入力いただいたメールアドレスあてに「確認メール」が送付されます。<br>
                もし「確認メール」が届かない場合は、メールアドレスの入力間違い、迷惑メールの拒否設定が考えられます。<br>
                　○メールアドレスの入力間違いの場合：<br>
                　　再度、この画面でメールアドレスを変更してください。<br>
                　○迷惑メールの設定について：<br>　　設定をご確認のうえ、@kobe-ipc.or.jpドメインの受信許可をしてください。<br>
                　　設定方法はご契約会社により異なります。<br><br>
              ◎今後、施設の使用をお申し込みの際は、予約受付やお支払に関するメールが送付されますので、メールアドレスをよくご確認ください。<br><br>
            </span>
        </td>
        </tr>
        <tr><th>新しいメールアドレス(再入力)</th>
            <td><input type="text" name="remail" style="width:60%" value=""></td>
        </tr>
        </tbody>
    </table>
    <input type="submit" class="btn btn-primary" role="button" name="regist" value="このアドレスで登録する"><br><br>
</form>
</body>
</html>