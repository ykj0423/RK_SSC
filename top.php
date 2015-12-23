<?php
@session_start();

$ini = parse_ini_file('config.ini');

$_SESSION['sysname'] = $ini['SYSTEM_NAME'];
$_SESSION['centername'] = $ini['CENTER_NAME'];
$next_page = "";
$errmsg = "";

//header
$pageTitle = "トップメニュー";
include('include/header.php');
?>
</head>
<body class="container">
<p class="bg-head text-right"><?php echo $_SESSION['centername']; ?></p>
<h1><span class="midashi">|</span><?php echo $_SESSION['sysname']; ?><?php echo "<small>".$pageTitle."</small>" ?></h1>
<?php
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

if (!empty($_POST['pre_search'])){
    //空き状況検索
    $next_page = 'pre_search.php';
}else if (!empty($_POST['new_regist'])){
    //新規利用者登録
    $_SESSION['next_page'] = 'regist.php';
    $next_page = 'kiyaku.php';
}else if (!empty($_POST['search'])){
    //空き状況・予約申込み　
    $_SESSION['next_page'] = 'search.php';
    $next_page = (isset($_SESSION['loginid'])) ? 'search.php' : 'kiyaku.php';
}else if (!empty($_POST['rsvlist'])){
    //予約照会
    $_SESSION['next_page'] = 'rsvlist.php';
    $next_page = (isset($_SESSION['loginid'])) ? 'rsvlist.php' : 'kiyaku.php';
}else if (!empty($_POST['member'])){
    //利用者情報変更
    $_SESSION['next_page'] = 'member_top.php';
    $next_page = (isset($_SESSION['loginid'])) ? 'member_top.php' : 'kiyaku.php';
}

if (!empty($next_page)){
    header('location: '.$next_page);
    exit();
}
//エラーメッセージ
include('include/err.php')
?>
<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
<div class="alert alert-info" role="alert">
	<p class="lead">ご利用登録されている方はこちら</p>
  <input type="submit" class="btn btn-primary btn-lg" name="search" role="button" value="空き状況・使用申込　>>">
  <input type="submit" class="btn btn-primary btn-lg" name="rsvlist" role="button" value="予約照会　>>">
  <input type="submit" class="btn btn-primary btn-lg" name="member" role="button" value="利用者情報変更　>>">
</div>
<div class="alert alert-warning" role="alert">
  <p class="lead">新規利用者登録をされる方はこちら</p>
  <input type="submit" class="btn btn-warning btn-lg" name="new_regist"role="button" value="新規利用者登録　>>">
</div>
<div class="alert alert-success" role="alert">
<p class="lead">ご利用登録せず、空き状況のみご覧になる場合はこちら</p>
  <input type="submit" class="btn btn-success btn-lg" name="pre_search" role="button" value="空き状況　>>">
</div>
</div>
<h5>&nbsp;&nbsp;※使用許可のお取消は、受付までお問い合わせください。<br>
      &nbsp;&nbsp;&nbsp;&nbsp;また、<a href="torikesi.pdf" target="_blank"><img src="icon_btn_pdf.png" alt=""><u>「神戸市産業振興センター使用許可取消申出書」</u></a>を受付までご提出ください。
</h5>
<br>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">お知らせ</h3>
    </div>
    <div class="panel-body">
      2016/01/03  定期メンテナンスのお知らせ
    </div>
  </div>
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