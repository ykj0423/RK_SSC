<?php
@session_start();
//echo $_SESSION['next_page'];
//header
$pageTitle = "ご使用にあたってのご注意";
include('include/header.php');
?>
</head>
<body class="container">
<p class="bg-head text-right"><?php echo $_SESSION['centername']; ?></p>
<h1><span class="midashi">|</span><?php echo $pageTitle; ?></h1>
<?php
$next_page = 'search.php';
if(isset($_SESSION['next_page'])){
	$next_page = $_SESSION['next_page'];
}else{
	$next_page = 'search.php';
}
echo $next_page;
if( isset( $_POST['submit'] ) ){
    header( 'location: '.$next_page );
}
/**
 * ReserveKeeperWeb予約システム
 *
 * PHP versions 4
 *
 * @category   公益財団法人神戸市産業振興財団／お申し込みにあたってのご注意
 * @package    none
 * @author     y.kamijo <y.kamijo@gandg.co.jp>
 * @copyright  2015 G&G Co.ltd.
 * @license    G&G Co.ltd.
 * @version    0.1
**/

/* お申し込みにあたってのご注意 */
include('include/notice.txt');
?>   
<div style="text-align:center">
    <img src="img/img_02.gif" width="864">
    <br><br><br>
</div>
    <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
        <div style="text-align:center">
            <input type="submit" name="submit" id="submit" value="次へ進む&nbsp;&gt;&gt;" class="btn btn-primary btn-lg">
        </div>
    </form>
</body>
</html>
