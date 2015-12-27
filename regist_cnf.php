<?php
@session_start();

$errmsg = "";
//header
$pageTitle = "新規利用者登録[確認]";
include('include/header.php');
include('model/Kyaku.php');
require_once( "func.php" );
/**
 * ReserveKeeperWeb予約システム
 *
 * PHP versions 4
 *
 * @category   公益財団法人神戸市産業振興財団／新規利用者登録[確認]
 * @package    none
 * @author     y.kamijo <y.kamijo@gandg.co.jp>
 * @copyright  2015 G&G Co.ltd.
 * @license    G&G Co.ltd.
 * @version    0.1
**/


if( isset( $_POST['submit'] ) && !empty( $_POST['submit'] ) ){

    /*if ( !$errmsg ) {
        header( 'location: regist_end.php' );
        exit();
    }*/

}


$ini = parse_ini_file('config.ini');        
$serverName = $ini['SERVER_NAME'];
$connectionInfo = array( "Database"=>$ini['DBNAME'], "UID"=>$ini['UID'], "PWD"=>$ini['PWD'] );
$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn === false ) {           
    die( print_r( sqlsrv_errors(), true));
}

//$Kyaku = unserialize( $_SESSION['Kyaku'] );

//エラーメッセージ
include('include/err.php');
//print_r($_POST);
$check = true;

if( empty( $_POST['dannm'] ) ){
  echo "<span class=\"text-danger\">※利用者名を入力してください。</span>";
  $check = false;
}

if( empty( $_POST['daihyo'] ) ){
  echo "<span class=\"text-danger\">※代表者名を入力してください。</span>";
  $check = false;
}


if( empty( $_POST['renraku'] ) ){
  echo "<span class=\"text-danger\">※連絡者名を入力してください。</span>";
  $check = false;
}


if( empty( $_POST['tel2_1'] ) || empty( $_POST['tel2_2'] ) || empty( $_POST['tel2_3'] ) ){
  echo "<span class=\"text-danger\">※連絡者TELを入力してください。</span>";
  $check = false;
}

if( empty( $_POST['mail'] ) ){
  echo "<span class=\"text-danger\">※メールアドレスを入力してください。</span>";
  $check = false;
}

if( empty( $_POST['re_mail'] ) ){
  echo "<span class=\"text-danger\">※メールアドレスを再度入力してください。</span>";
  $check = false;
}


if( empty( $_POST['zipcd_1'] ) || empty( $_POST['zipcd_2'] ) ){
  echo "<span class=\"text-danger\">※郵便番号を入力してください。</span>";
  $check = false;
}

if( empty( $_POST['adr1'] ) && empty( $_POST['adr2'] ) ){
  echo "<span class=\"text-danger\">※住所を入力してください。</span>";
  $check = false;
}

if( !$check ){ 

?>
  <br><br><div class="form-group">
      <div class="row mb20">
          <a class="btn btn-default btn-lg" href="javascript:history.back();">&lt;&lt;修正する</a>
        
      </div>
  </div>
<?php
  die();
}

?>
<form role="form" method="POST" action="regist_end.php">
    <p>入力内容をご確認いただき、問題がなければ「送信する」ボタンを押してください。</p>
      <table id="demo" align="center" class="table table-bordered table-condensed form-inline f120" >
      <tr>
        <th>利用者名</th>
        <td>
<?php 
$dannm = "";
$daihyo = "";
$renraku = "";

if( isset($_POST['dannm']) ){
  $dannm = $_POST['dannm'];
  echo $dannm;              
}
?>
        </td>
      </tr>
      <tr>
        <th>代表者名</th>
        <td>
<?php
if( isset($_POST['daihyo']) ){
  $daihyo = $_POST['daihyo'];
  echo $daihyo;
}
?>
        </td>
      </tr>
      <tr>
        <th>連絡者名</th>
        <td>
<?php 
if( isset($_POST['renraku']) ){
  $renraku = $_POST['renraku'];
  echo $renraku;
}
?>
         </td>
      </tr>
      <tr>
        <th >連絡者TEL</th>
        <td>
        <?php
            if( isset($_POST['tel2_1']) && isset($_POST['tel2_2']) && isset($_POST['tel2_3'])){
              $tel2 = format_tel( $_POST['tel2_1'], $_POST['tel2_2'], $_POST['tel2_3'], "-" );
              echo $tel2;
            }        
        ?>
        </td>
      </tr>
      <tr>
        <th>FAX</th>
        <td>
        <?php
        if( isset($_POST['fax_1']) && isset($_POST['fax_2']) && isset($_POST['fax_3'])){
            $fax = format_tel( $_POST['fax_1'], $_POST['fax_2'], $_POST['fax_3'], "-" );
            echo $fax;
        }   
        ?>
        </td>
      </tr>
      <tr>
        <th>メールアドレス</th>
        <td>
          <?php 
          if( isset($_POST['mail']) ){
            $mail=$_POST['mail'];
            echo $mail;
          }
          ?>
      </td>
      </tr>
      <tr>
        <th>住所</th>
        <td>
          <?php
            
            if( isset($_POST['zipcd_1']) && isset($_POST['zipcd_2']) ){
              $zipcd = format_zipcd( $_POST['zipcd_1'],  $_POST['zipcd_2'], "-" );
              echo "〒".$zipcd;
            }

            echo "<br>";
            echo $_POST['adr1'];
            echo "<br>";
            echo $_POST['adr2'];
            //$Kyaku->put_data('adr1'); 
          ?>
        </td>
      </tr>
      <tr>
        <th>業種</th>
        <td>
        <?php
          
          if( isset($_POST['gyscd']) ){
            $gyscd = $_POST['gyscd'];
            $gyous_name = "";
          

            if(!empty($gyscd)){
            
              $sql = "SELECT name FROM mm_gyous where code =".$gyscd;
              $stmt = sqlsrv_query( $conn, $sql );
            
              if( $stmt === false) {
                  print_r( sqlsrv_errors()) ;
              }

              while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                  echo mb_convert_encoding($row['name'], "UTF-8","SJIS");
                }
              }
          
          }
        ?>
        </td>
      </tr>
      <tr>
        <th>資本金または元入金</th>
        <td>
<?php 
$sihon = 0;

if( isset($_POST['sihon']) ){
  $jygsu = $_POST['sihon'];
}

echo $sihon;
?>
      万円</td>
      </tr>
      <tr>
        <th>従業員数</th>
        <td>
<?php
$jygsu = 0;

if( isset($_POST['jygsu']) ){
  $jygsu = $_POST['jygsu'];
    echo  $jygsu;
}

        ?>名</td>
      </tr>
    </table>
<?php 
echo "<input type='hidden' name='dannm' id='dannm' value=\"".$_POST[ 'dannm' ]."\">";
echo "<input type='hidden' name='daihyo' id='daihyo' value=\"".$_POST[ 'daihyo' ]."\">";
echo "<input type='hidden' name='renraku' id='renraku' value=\"".$_POST[ 'renraku' ]."\">";
echo "<input type='hidden' name='tel2' id='tel2' value=\"".$tel2."\">";
echo "<input type='hidden' name='fax' id='fax' value=\"".$fax."\">";
echo "<input type='hidden' name='mail' id='mail' value=\"".$_POST[ 'mail' ]."\">";
echo "<input type='hidden' name='zipcd' id='zipcd' value=\"".$zipcd."\">";
echo "<input type='hidden' name='adr1' id='adr1' value=\"".$_POST[ 'adr1' ]."\">";
echo "<input type='hidden' name='adr2' id='adr2' value=\"".$_POST[ 'adr2' ]."\">";
echo "<input type='hidden' name='gyscd' id='gyscd' value=\"".$sihon."\">";
echo "<input type='hidden' name='sihon' id='sihon' value=\"".$_POST[ 'sihon' ]."\">";
echo "<input type='hidden' name='jygsu' id='jygsu' value=\"".$jygsu."\">";
//$_SESSION['Kyaku'] = serialize( $Kyaku );
?>
    <!--div style="text-align:center">
      <a class="btn btn-default btn-lg mb20" href="regist.php" role="button"><< 修正する</a>      
      <input type="submit" name="submit" id="submit" value="送信する" class="btn btn-primary btn-lg">        
    </div-->
        <div class="text-center mb20">
        <a class="btn btn-default btn-lg" href="javascript:history.back();"><<修正する</a>
        <input type="submit" name="submit" id="submit" value="送信する" class="btn btn-primary btn-lg">
        <!-- a class="btn btn-primary btn-lg " href="regist_cnf.html" role="button">確認画面へ >></a-->
      </div>
    </div><!--form-group-->
  </form>
</body>
</html>
