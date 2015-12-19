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
print_r($_POST);



?>

    <p>入力内容をご確認いただき、問題がなければ「送信する」ボタンを押してください。</p>
      <table id="demo" align="center" class="table table-bordered table-condensed form-inline f120" >
      <tr>
        <th>利用者名</th>
        <td><?php echo $_POST['dannm'];//$Kyaku->put_data('dannm'); ?></td>
      </tr>
      <tr>
        <th>利用者名（カナ）</th>
        <td><?php echo mb_convert_kana($_POST['dannmk'],k);//echo mb_convert_kana($Kyaku->put_data('dannmk'),k); ?></td>
      </tr>
      <tr>
        <th>代表者名</th>
        <td><?php echo $_POST['daihyo'];//$Kyaku->put_data('daihyo'); ?></td>
      </tr>
      <tr>
        <th>連絡者名</th>
        <td><?php echo $_POST['renraku'];//$Kyaku->put_data('renraku'); ?></td>
      </tr>
      <tr>
        <th >連絡者TEL</th>
        <td><?php echo format_tel( $_POST['tel2_1'], $_POST['tel2_2'], $_POST['tel2_3'], "-" ); ?></td>
      </tr>
      <tr>
        <th>FAX</th>
        <td><?php echo format_tel( $_POST['fax_1'], $_POST['fax_2'], $_POST['fax_3'], "-" ); ?></td>
      </tr>
      <tr>
        <th>メールアドレス</th>
        <td><?php echo $_POST['mail'];//$Kyaku->put_data('mail'); ?></td>
      </tr>
      <tr>
        <th>住所</th>
        <td>
          〒651-0086<br>
          <?php echo $_POST['adr1'];//$Kyaku->put_data('adr1'); ?>
        </td>
      </tr>
      <tr>
        <th>業種</th>
        <td>
        <?php
          
          $code = $_POST['gyscd'];
          $gyous_name = "";
          
          if(!empty($code)){
            
            $sql = "SELECT name FROM mm_gyous where code =".$code;
            echo $sql."<br>";
            $stmt = sqlsrv_query( $conn, $sql );
          
            if( $stmt === false) {
                print_r( sqlsrv_errors()) ;
            }

            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                //$gyous_name = $row['name'];
               // $gyous_name = mb_convert_encoding($gyous_name, "UTF-8","SJIS");
                //echo "gyous_name=".$gyous_name;
              echo mb_convert_encoding($row['name'], "UTF-8","SJIS");
            }


          }
        ?>
        </td>
      </tr>
      <tr>
        <th>資本金または元入金</th>
        <td><?php //echo $Kyaku->put_data('sihon'); ?>万円</td>
      </tr>
      <tr>
        <th>従業員数</th>
        <td><?php //echo $Kyaku->put_data('jygsu'); ?>名</td>
      </tr>
    </table>

    <?php  //$_SESSION['Kyaku'] = serialize( $Kyaku );?>
    <div style="text-align:center">
      <a class="btn btn-default btn-lg mb20" href="regist.php" role="button"><< 修正する</a>
      <a class="btn btn-primary btn-lg mb20" href="regist_end.php" role="button">送信する　>></a>
    </div>
 </div>
</body>
</html>
