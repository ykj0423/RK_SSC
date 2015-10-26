<?php
@session_start();

$errmsg = "";
//header
$pageTitle = "利用者情報登録[確認]";
include('include/header.php');
include('model/Kyaku.php');
/**
 * ReserveKeeperWeb予約システム
 *
 * PHP versions 4
 *
 * @category   公益財団法人神戸市産業振興財団／利用者情報登[確認]
 * @package    none
 * @author     y.kamijo <y.kamijo@gandg.co.jp>
 * @copyright  2015 G&G Co.ltd.
 * @license    G&G Co.ltd.
 * @version    0.1
**/

if( isset( $_POST['submit'] ) && !empty( $_POST['submit'] ) ){

    if ( !$errmsg ) {
        //header( 'location: regist_cnf.php' );
        //exit();
      $Kyaku = new Kyaku();
      $Kyaku->add_kyaku( $_POST );
    }

}
print_r($_POST);

if( isset( $_POST['submit'] ) ){
 
    //if ( !$errmsg ) {
    //    header( 'location: regist_end.php' );
    //    exit();
    //}

}

print_r($_POST);
//エラーメッセージ
include('include/err.php');
?>

    <p>入力内容をご確認いただき、問題がなければ「送信する」ボタンを押してください。</p>
    <!------------------------>
      <table id="demo" align="center" class="table table-bordered table-condensed  form-inline f120" >
      <tr>
        <th>利用者名</th>
        <td>ジィ・アンド・ジィ株式会社　IT事業部</td>
      </tr>
      <tr>
        <th>利用者名（カナ）</th>
        <td>ｼﾞｨ･ｱﾝﾄﾞ･ｼﾞｨｶﾌﾞｼｷｶﾞｲｼｬ ｱｲﾃｨｼﾞｷﾞｮｳﾌﾞ</td>
      </tr>
      <!--tr>
        <th>部署課名</th>
        <td>IT事業部</td>
      </tr-->
      <tr>
        <th>代表者名</th>
        <td>竹中 睦芳</td>
      </tr>
      <!--tr>
        <th >代表者TEL</th>
        <td>078-222-1551</td>
      </tr-->
      <tr>
        <th>連絡者名</th>
        <td>村井 美穂</td>
      </tr>
      <tr>
        <th >連絡者TEL</th>
        <td>078-222-1041</td>
      </tr>      <tr>
        <th>FAX</th>
        <td>078-222-1042</td>
      </tr>
      <tr>
        <th>メールアドレス</th>
        <td>m.murai@gandg.co.jp</td>
      </tr>
      <tr>
        <th>住所</th>
        <td>
          〒651-0086<br>
          神戸市中央区磯上通4-1-6 シオノギビル2階
        </td>
      </tr>
      <tr>
        <th>業種</th>
        <td>サービス業</td>
      </tr>
      <tr>
        <th>資本金または元入金</th>
        <td>1000万円</td>
      </tr>
      <tr>
        <th>従業員数</th>
        <td>52名</td>
      </tr>
      <!--tr>
        <td colspan="2">資本金5千万円以下及び従業員が100人以下の会社及び個人事業主で、サービス業</td>
      </tr-->
    </table>

    <div style="text-align:center">
    <a class="btn btn-default btn-lg mb20" href="regist.html" role="button"><< 修正する</a>
    <a class="btn btn-primary btn-lg mb20" href="regist_end.html" role="button">送信する　>></a>
    </div>

 </div>
</body>
</html>
