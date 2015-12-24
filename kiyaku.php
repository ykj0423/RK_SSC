<?php
@session_start();

$errmsg = "";
//header
$pageTitle =  "利用規約";
include('include/header.php');
?>
</head>
<body class="container">
<p class="bg-head text-right"><?php echo $_SESSION['centername']; ?></p>
<h1><span class="midashi">|</span><?php echo $pageTitle; ?></h1>
<?php

/**
 * ReserveKeeperWeb予約システム
 *
 * PHP versions 4
 *
 * @category   公益財団法人神戸市産業振興財団／利用規約
 * @package    none
 * @author     y.kamijo <y.kamijo@gandg.co.jp>
 * @copyright  2015 G&G Co.ltd.
 * @license    G&G Co.ltd.
 * @version    0.1
**/

if ( !empty( $_POST ) ){

  if( $_POST['agree'] == 1 ){
      
      if( $_SESSION['next_page'] == 'regist.php'){
            header( 'location: regist.php' );
      }else{
          header( 'location: login.php' );
      }  

      exit();
  
  }else{
      $errmsg = "ご利用規約にご意いただけない場合は、システムを使用できません。"; 
  }

}

//エラーメッセージ
include('include/err.php');
?>
<p>
<?php
/* ご利用規約 */
include('include/kiyaku.txt');
?>
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <div style="text-align:center">
    <label class="mr10 f120"><input name="agree" type="radio" checked value="0">同意しない</label>
    <label class="mr10 f120"><input name="agree" id="agree" type="radio" value="1">同意する</label><br><br>
    <a class="btn btn-default btn-lg mb20" href="top.php" role="button"><< 戻る</a>
    <!-- input type="submit" value="次へ進む&gt;&gt;" class="btn btn-primary btn-lg mb20"/-->
    <input type="submit" value="次へ進む＞＞" class="btn btn-primary btn-lg mb20"/>
  </div>
  </form>
</div>
</body>
</html>
