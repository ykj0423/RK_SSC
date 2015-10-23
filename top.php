<?php
@session_start();

$ini = parse_ini_file('model/config.ini');

$_SESSION['sysname'] = $ini['SYSTEM_NAME'];
$_SESSION['centername'] = $ini['CENTER_NAME'];
$errmsg = "";
//header
$pageTitle = "トップページ";
include('include/header.php');

/**
 * ReserveKeeperWeb予約システム
 *
 * PHP versions 4
 *
 * @category   公益財団法人神戸市産業振興財団／トップメニュ－
 * @package    none
 * @author     y.kamijo <y.kamijo@gandg.co.jp>
 * @copyright  2015 G&G Co.ltd.
 * @license    G&G Co.ltd.
 * @version    0.1
**/

//選択された画面へ遷移する。未ログインであれば、ログイン画面に遷移する。
$next_page = 'kiyaku.php';
//$next_page = 'login.php';

if (!empty($_POST['pre_search'])){
    //空き状況検索
    $url = 'pre_search.php';
}else if (!empty($_POST['new_regist'])){
    //新規利用者登録
    $url = 'kiyaku.php';
}else if (!empty($_POST['search'])){
    //空き状況・予約申込み　
    $url = (isset($_SESSION['loginid'])) ? 'search.php' : $next_page;
}else if (!empty($_POST['rsvlist'])){
    //予約照会　
    $url = (isset($_SESSION['loginid'])) ? 'rsvlist.php' : $next_page;
}else if (!empty($_POST['member'])){
    //利用者情報変更　
    $url = (isset($_SESSION['loginid'])) ? 'member_top.php' : $next_page;
}

if (!empty($url)){
    $_SESSION['next_page'] = $url;
    header('location: '.$url);
    exit();
}
//エラーメッセージ
include('include/err.php')
?>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div class="alert alert-info" role="alert">
	<p class="lead">ご利用登録されている方はこちら</p>
  <input type="submit" class="btn btn-warning btn-lg" name="search" role="button" value="空き状況・予約申込み　>>">
  <input type="submit" class="btn btn-warning btn-lg" name="rsvlist" role="button" value="予約照会　>>">
  <input type="submit" class="btn btn-warning btn-lg" name="member" role="button" value="利用者情報変更　>>">
</div>
<div class="alert alert-warning" role="alert">
  <p class="lead">新規利用者登録をされる方はこちら</p>
  <input type="submit" class="btn btn-warning btn-lg" name="new_regist"role="button" value="新規利用者登録　>>">
</div>
<div class="alert alert-success" role="alert">
<p class="lead">ご利用登録せず、空き状況のみご覧になる場合はこちら</p>
  <input type="submit" class="btn btn-warning btn-lg" name="pre_search" role="button" value="空き状況　>>">
</div>
</div>
<div class="panel panel-default">
  <div class="panel-heading">,
    <h3 class="panel-title">お知らせ</h3>
  </div>
  <div class="panel-body">
</form>
<?php
//TODO　InformationClassを作成する
//XXXX/XX/XX  定期メンテナンスの実施のお知らせetc....お知らせマスタの内容
?>
  </div>
</div>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script-->
</body>
</html>