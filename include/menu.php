  <p class="bg-head text-right"><?php echo $_SESSION['centername']; ?><?php echo $_SESSION['sysname']; ?></p>
  <nav class="navbar nav-custom">
    <div class="container-fluid">
        <div class="nav-header">
           <a href="top.php" class="navbar-brand">トップ</a>
         </div>
         <ul class="nav navbar-nav">
            <li><a href="search.php">空き状況</a></li>
            <li><a href="rsvlist.php">予約照会</a></li>
            <li><a  href="member_top.php">利用者情報変更</a></li>
            <li>
            <?php
              $ini = parse_ini_file('config.ini'); 
              echo "<a href=".$ini['RATE_LINK']." target=\"_blank\">料金表</a>";
            ?>
            </li>
            <li><a href="hitudoku2.html" target="_blank">ご使用にあたってのご注意</a></li>
            <li><a href="help.html" target="_blank">システムガイド</a></li>
         </ul>
         <form class="navbar-form pull-right">
            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
              NPO法人　ログイン中 <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
              <li><a href="login.php">ログアウト</a></li>
            </ul>
         </form>
   </div>
  </nav>
