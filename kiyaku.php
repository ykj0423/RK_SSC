<?php
@session_start();

$errmsg = "";
//header
$pageTitle =  "利用規約";
include('include/header.php');

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
      header( 'location: login.php' );
      exit();
  }else{
      $errmsg = "ご利用規約にご意いただけない場合は、システムを使用できません。"; 
  }
}

//エラーメッセージ
include('include/err.php');
?>
<p>
手続きを行うためには、以下にご同意いただくことが必要です。 <br>
ご同意いただけない場合は、サービスの利用をお断りいたしますのであらかじめご了承ください。 </p>
    <div class="kiyaku">
      <dl>
      <dt>第1条（目的）</dt>
      <dd>・・・ </dd>
      <dt>第2条（利用規約の同意）</dt>
      <dd>・・・ </dd>
      <dt>第3条（施設規則等の遵守）</dt>
      <dd>・・・ </dd>
      <dt>第4条（利用者登録）</dt>
      <dd>・・・ </dd>
      <dt>第5条（利用者ID、パスワード）</dt>
      <dd>・・・ </dd>
      <dt>第8条（登録事項の変更）</dt>
      <dd>・・・ </dd>
      <dt>第9条（登録資格の喪失）</dt>
      <dd>・・・ </dd>
      <dt>第10条（施設利用手続）</dt>
      <dd>・・・ </dd>
      <dt>第11条（費用）</dt>
      <dd>・・・ </dd>
      <dt>第12条（個人情報の利用目的）</dt>
      <dd>・・・ </dd>
      <dt>第13条（禁止事項）</dt>
      <dd>・・・ </dd>
      <dt>第14条（免責事項）</dt>
      <dd>・・・ </dd>
      <dt>第15条（規約の変更）</dt>
      <dd>・・・ </dd>
      <dt>第16条（その他)</dt>
      <dd>本規約に定めるものの他必要な事項については、別に定めることとします。</dd>
      <dt>附則</dt>
      <dd>この規約は、平成XX年X月X日から施行します </dd>
      </dl>
   </div>
  <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
  <div style="text-align:center">
    <label class="mr10 f120"><input name="nonagree" type="radio" checked value="0">同意しない</label>
    <label class="mr10 f120"><input name="agree" id="agree" type="radio" value="1">同意する</label><br><br>
    <a class="btn btn-default btn-lg mb20" href="top.php" role="button"><< 戻る</a>
    <input type="submit" value="次へ進む&gt;&gt;" class="btn btn-primary btn-lg mb20"/>
  </div>
  </form>
</div>
</body>
</html>
