<?php
@session_start();

$errmsg = "";
//header
$pageTitle = "新規利用者登録[入力]";
include('include/header.php');
include('model/Kyaku.php');
/**
 * ReserveKeeperWeb予約システム
 *
 * PHP versions 4
 *
 * @category   公益財団法人神戸市産業振興財団／新規利用者登録[入力]
 * @package    none
 * @author     y.kamijo <y.kamijo@gandg.co.jp>
 * @copyright  2015 G&G Co.ltd.
 * @license    G&G Co.ltd.
 * @version    0.1
**/
if( isset( $_POST['submit'] ) && !empty( $_POST['submit'] ) ){
        
    /* TODO　入力チェック */
    if ( !$errmsg ) {

        //利用者クラス
        $Kyaku = new Kyaku();
        $Kyaku->push_data( $_POST, 'dannm', true, false　);
        $Kyaku->push_data( $_POST, 'dannmk', true, false　);
        $Kyaku->push_data( $_POST, 'daihyo', true, false　);
        $Kyaku->push_data( $_POST, 'renraku', true, false　);
        $Kyaku->push_data( $_POST, 'tel2_1', true, false　);
        $Kyaku->push_data( $_POST, 'tel2_2', true, false　);
        $Kyaku->push_data( $_POST, 'tel2_3', true, false　);
        $Kyaku->push_data( $_POST, 'fax_1', false, false　);
        $Kyaku->push_data( $_POST, 'fax_2', false, false　);
        $Kyaku->push_data( $_POST, 'fax_3', false, false　);
        $Kyaku->push_data( $_POST, 'mail', true, false　);
        $Kyaku->push_data( $_POST, 'zipcd_1', true, false　);        
        $Kyaku->push_data( $_POST, 'adr1', true, false　);
        $Kyaku->push_data( $_POST, 'gyscd', true, false　);
        $Kyaku->push_data( $_POST, 'sihon', true, false　);
        $Kyaku->push_data( $_POST, 'jygsu', true, false　);

        //オブジェクトのシリアル化
        $_SESSION['Kyaku'] = serialize( $Kyaku );
        header( 'location: regist_cnf.php' );
        exit();

    }

}

//エラーメッセージ
include('include/err.php');
?>
  <div class="row mb10">
    <p class="f120 col-xs-8">必要事項をご入力のうえ、「確認画面へ」ボタンを押してください。<br>登録完了後、本システムのログインに必要な情報をメールでお送りいたします。</p>
      <div class="col-xs-4">
        <a href="help.html#regist"  class="btn alert-info" target="window_name"  onClick="disp('help.html#regist')"><li class="glyphicon glyphicon-question-sign" aria-hidden="true">&nbsp;この画面の操作方法についてはこちら>></li></a> 
        <a href="loginqa.html#regist"   class="btn alert-danger" target="window_name"  onClick="disp('loginqa.html#regist')"><li class="glyphicon glyphicon-exclamation-sign" aria-hidden="true">&nbsp;入力にあたってのご注意はこちら>></li></a> 
      </div>
  </div>
  <form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
    <div class="form-group">
      <table id="regist" align="center" class="table table-bordered table-condensed  form-inline f120" >
          <th >利用者名<span class="red">(必須)</span></th>
          <td>
              <input type="text" class="long" maxlength="20" name="dannm" placeholder="（例：公益財団法人神戸市産業振興財団&nbsp;&nbsp;総務部&nbsp;施設管理課）">
             <!--全角20文字-->
              <!-- span class="ml10 note">（例：公益財団法人神戸市産業振興財団&nbsp;&nbsp;総務部&nbsp;施設管理課）</span-->
          </td>
        </tr>    
        <tr>
          <th >利用者名（カナ）<span class="red">(必須)</span></th>
          <td><input type="text" name="dannmk" class="long" value="" maxlength="20"><br>
              <span class="ml10 note">（例：コウベシサンギョウシンコウザイダン ソウムブ シセツカンリカ）</span><!--カナ20文字--></td>
        </tr>
        <tr>
          <th >代表者名<span class="red">(必須)</span></th>
          <td><input type="text" name="daihyo" class="long" value="" maxlength="20"><!--全角20文字--><br>
              <span class="ml10 note">（例：神戸 太郎）</span></td>
        </tr>
        <tr>
          <th >連絡者名<span class="red">(必須)</span></th>
          <td><input type="text"  name="renraku" class="long"  value=""><br>
              <span class="ml10 note">（例：神戸 花子）</span>
          </td><!--全角20文字-->
        </tr>
        <tr>
          <th >連絡者TEL<span class="red">(必須)</span></th>
          <td>
              <input type="text" name="tel2_1" class="short mr10" value="" maxlength="5">-
              <input type="text" name="tel2_2" class="short mr10"  value="" maxlength="5">-
              <input type="text" name="tel2_3" class="short mr10"  value="" maxlength="5">
              <span class="ml10 note">(半角数字)</span>
          </td>
        </tr>
        <tr>
          <th >FAX</th>
          <td>
              <input type="text" name="fax_1" class="short mr10" value="" maxlength="5">-
              <input type="text" name="fax_2" class="short mr10"  value="" maxlength="5">-
              <input type="text" name="fax_3" class="short mr10"  value="" maxlength="5">
              <span class="ml10 note">(半角数字)</span>
          </td>
        </tr>
        <tr>
          <th >メールアドレス<span class="red">(必須)</span><br></th>
          <td>
            <span class="text-danger">
              ※ご登録いただいたメールアドレスあてに「利用者登録完了メール」が送付されます。<br>
              また、今後、施設の使用をお申し込みの際は、予約受付やお支払に関するメールが送付されます。<br>
              もし「利用者登録完了メール」等が届かない場合は、メールアドレスの入力間違い、迷惑メールの拒否設定が考えられます。<br>
              よくご確認のうえ、ご入力ください。<br>
              迷惑メールの拒否設定をされている場合は、@kobe-ipc.or.jpドメインの受信許可をしてください。</span><br><br>
              <input type="text"   name="mail" class="long"  value="" maxlength="60"><br>
  	          ※確認のため、再度ご入力をお願いします。<br>
              <input type="text"   name="re_mail" class="long"  value="" maxlength="60">
          </td>
        </tr>
        <tr>
          <th >住所<span class="red">(必須)</span></th>
          <td>
            〒<input type="text" class="short mr10" name="zipcd_1"  value=""  maxlength="3">-<input type="text" class="short ml10" name="zipcd_2"  value=""  onkeyup="AjaxZip3.zip2addr('zipcd1','zipcd2','adr1','adr1');" maxlength="4"><span class="ml10 note">(半角数字)</span><br>
            <input type="text"   name="adr1" class="long" value="" placeholder="(市区町村～番地）" maxlength="40"><br>
            <input type="text"   name="adr2" class="long" value="" placeholder="(建物名）" maxlength="40">
          </td>
        </tr>
        <tr>
          <th >業種<span class="red">(必須)</span></th>
          <td>
            <!-- TODO DBから取得　-->
            <label class="mr10"><input name="gyscd" type="radio" value="4">製造・建設・運輸・その他</label>
            <label class="mr10"><input name="gyscd" type="radio" value="1">小売業</label>
            <label class="mr10"><input name="gyscd" type="radio" value="2">サービス業</label>
            <label class="mr10"><input name="gyscd" type="radio" value="3">卸売業</label>
            <label class="mr10"><input name="gyscd" type="radio" value="0">事業者でない（趣味の会・サークル等）</label>
          </td>
        </tr>
        <tr>
          <th >資本金または元入金<span class="red">(必須)</span></th>
          <td><input type="text" class="txr"  name="sihon"  value="">万円<span class="ml10 note">(半角数字。事業者でない場合は0を入力)</span></td>
        </tr>
        <tr>
          <th >従業員数<span class="red">(必須)</span></th>
          <td><input type="text" class="short txr" name="jygsu"  value="">名<span class="ml10 note">(半角数字。事業者でない場合は0を入力)</span></td>
        </tr>
      </table>
      <div class="text-center mb20">
        <a class="btn btn-default btn-lg " href="top.html" role="button"><<　戻る</a>
        <input type="submit" name="submit" id="submit" value="確認画面へ" class="btn btn-primary btn-lg">
        <!-- a class="btn btn-primary btn-lg " href="regist_cnf.html" role="button">確認画面へ　>></a-->
      </div>
    </div><!--form-group-->
  </form>
</body>
</html>