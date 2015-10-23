<?php
@session_start();

$errmsg = "";
//header
$pageTitle =  "パスワードの変更";
include('include/header.php');
//メニュー
include('include/menu.php');
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

if (empty($_POST['regist'])){
    $infomsg = "システムを安全にお使いいただく為に、すぐに推測できる単語は使用しないでください。";
}else{
    $infomsg = "パスワードの変更が完了致しました。"; 
}
?>
<p><?php echo $infomsg; ?></p><br>
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      	<div class="col-xs-4  text-right">
          <span class="f120">現在の時間：　2015/9/29   16:26:22</span>
       </div>
   </div>
	<table class="table table-bordered">
    <tbody>
      <!--tr><th width="30%">現在のパスワード<span class="text-danger">(必須)</span></th><td><input type="password" name="pass" value=""></td></tr-->
      <tr><th>新しいパスワード<span class="text-danger">(必須)</span></th><td><input type="password" name="passnew" value=""><span class="small note">(半角英数字記号　8～10文字）</span></td></tr>
      <tr><th>新しいパスワード（再入力）<span class="text-danger">(必須)</span></th><td><input type="password" name="passnew2" value=""><span class="small note">(半角英数字記号　8～10文字）</span></td></tr>	     </tbody>
    </table>
    <input type="submit" class="btn btn-primary" role="button" name="regist" value="変更する　>>"><br><br>
</form>
</body>
</html>