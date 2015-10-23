<?php
@session_start();

$errmsg = "";
//header
$pageTitle =  "利用者情報変更";
include('include/header.php');
//メニュー
include('include/menu.php')
/**
 * ReserveKeeperWeb予約システム
 *
 * PHP versions 4
 *
 * @category   公益財団法人神戸市産業振興財団／利用者情報変更
 * @package    none
 * @author     y.kamijo <y.kamijo@gandg.co.jp>
 * @copyright  2015 G&G Co.ltd.
 * @license    G&G Co.ltd.
 * @version    0.1
**/
?>
<ul class="f120">
    <li>連絡用のメールアドレスは変更可能です。<a href="mail_chaneg.php"><b>こちら</b></a>からお手続きください</li>
    <li>パスワードはご自身の覚えやすいものに変更できます。<a href="pass_change.php"><b>こちら</b></a>からお手続きください</li>
</ul>
</body>
</html>
