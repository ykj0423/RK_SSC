<?php
//include('include/menu.php');
require_once( "func.php" );
require_once( "model/db.php" );
//print_r($_POST);
/* データベース接続 */
$db = new DB;
$conErr = $db->connect();
if ( !empty( $conErr ) ) { echo $conErr;  die(); } //接続不可時は終了

/* 施設分類の取得 */
$rmcls = $db->select_rmcls();

/* 検索日付（自至） */
//検索開始日
               
$today = date( "Y/m/d" );
$rsv_sttdt = date( "Y/m/d", strtotime( "".$today." +15 day" ) );       //会議室申込開始日
$rsv_enddt = date( "Y/m/d", strtotime( "".$today." +365 day" ) );      //申込期限日
$rsv_sttdt_hole = date( "Y/m/d", strtotime( "".$today." +1 month" ) ); //ホール申込開始日
$rsv_before = date( "Y/m/d", strtotime( "".$today." +14 day" ) );      //申込終了日

$cal_year = (int)substr( $rsv_sttdt, 0, 4 );
$cal_month = (int)substr( $rsv_sttdt, 5, 2 );

/*  default  */
//年月ボタン
$calbtn_year = $cal_year;
if($cal_month<10){
  $cal_month = "0"+$cal_month;
}
$calbtn_month = $cal_month;

//カレンダーの開始日、終了日
$sttdt = $rsv_sttdt;
$enddt = date( "Y/m/d", strtotime( "".$sttdt." +13 day" ) );//14日後

/*  検索ボタン押下  */
if( isset( $_POST['calbtn'] ) ){

  $calbtn = $_POST['calbtn'];
  $calbtn_year = (int)substr( $calbtn, 0, 4 );
  //$calbtn_month = str_pad( (int)substr( $calbtn, 5, 2 ), 2, 0, STR_PAD_LEFT );
  $calbtn_month =  (int)substr( $calbtn, 4, 2 );
  if($calbtn_month < 10)
  {
    $calbtn_month = "0".$calbtn_month;
  }
  
  $sttdt = $calbtn_year."/".$calbtn_month."/01";//月初

}else{ 
  
  if( !empty ( $_POST['search_ymd_stt'] ) ){
      $sttdt = $_POST['search_ymd_stt'] ; 
  }

}

//検索終了日
//if( !empty ( $_POST['serch_ymd_end'] ) ){
//    $enddt = $_POST['serch_ymd_end'] ; 
//}else{
    //初期値
    $enddt = date("Y/m/d", strtotime("".$sttdt." +13 day"));
//}

//検索曜日
if( isset( $_POST[ 'yobi' ] )  && ( count( $_POST[ 'yobi' ] ) > 0 ) ){
    //配列代入
    $yobi = &$_POST[ 'yobi' ];
}else{
    //デフォルトではcheck_on
    $yobi = array ( 0, 1, 2, 3, 4, 5, 6 );
}
?>
<div class="row" id="srch">
    <form name="search_form" id="search_form" role="form" method="post" action="<?=$_SERVER["PHP_SELF"]?>">
    <div class="col-xs-8">
      <table id ="rsv_serach" class="table-bordered table-condensed srch" align="center" width="100%">
        <tbody>
          <tr>
            <th class="pt12">施設分類</th>
            <td>

<?php
/* 施設分類の表示 */
if( isset( $_POST[ 'bunrui' ] ) && (count( $_POST[ 'bunrui' ] ) > 0) ){

  //配列代入
  $bunrui = &$_POST[ 'bunrui' ];
   
}else{

  //デフォルトではcheck_on
  $bunrui = array();
  
  if(is_array($rmcls)){
    for ($i = 0; $i < ( count( $rmcls['data'] ) ) ; $i++ ) {
      array_push( $bunrui , $rmcls['data'][$i]['key'] );
    }
  }

}

if(is_array($rmcls)){

  for ($i = 0; $i < ( count( $rmcls['data'] ) ) ; $i++ ) {
      if( ( array_key_exists( $i, $rmcls['data']) ) && in_array ( $rmcls['data'][$i]['key'] , $bunrui )){
          echo "<label class=\"checkbox-inline\" for=\"bunrui". $i ."\"><input type=\"checkbox\" name=\"bunrui[]\" id=\"bunrui".$i."\" value=\"". $rmcls['data'][$i]['key'] ."\" checked>". $rmcls['data'][$i]['value'] . "</label>";
      } else {
          echo "<label class=\"checkbox-inline\" for=\"bunrui". $i ."\"><input type=\"checkbox\" name=\"bunrui[]\" id=\"bunrui".$i."\" value=\"". $rmcls['data'][$i]['key'] ."\">". $rmcls['data'][$i]['value'] . "</label>";
      }
  }

}
?>
      </td>
          </tr>
          <tr>
            <!--th>使用年月</th>
            <td>
              <div id="cal" class="btn-group" data-toggle="buttons"-->
                
              <?php
                
                /*for ($i = 0; $i < 13; $i++) {
                  
                  if($cal_month > 12){
                    $cal_month = 1;
                    $cal_year = $cal_year + 1;
                  }

                  if( ($cal_year == $calbtn_year) && ($cal_month == $calbtn_month)){
                    echo "<label class=\"btn btn-xs btn-cal active\">";                   
                    echo "<input name=\"calbtn\" value=\"".$cal_year.str_pad($cal_month, 2, 0, STR_PAD_LEFT)."\" type=\"radio\" checked>";
                  }else{
                    echo "<label class=\"btn btn-xs btn-cal\">";
                    echo "<input name=\"calbtn\" value=\"".$cal_year.str_pad($cal_month, 2, 0, STR_PAD_LEFT)."\" type=\"radio\">";
                  }
 
                  if($i == 0){
                    echo $cal_year."年<br>".$cal_month."月</label>";                   
                  }else if($cal_month == 1){
                    echo $cal_year."年<br>".$cal_month."月</label>";                  
                  }else{
                    echo "<br>".$cal_month."月</label>";
                  }

                  $cal_month++;  

                }
                */
              ?>
              <!--/div>
            </td-->
          </tr>
          <tr>
            <th class="pt12">曜日</th>
            <td>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi7" value = "0" <?php  echo ( in_array ( 0 , $yobi ) )? 'checked' : ''; ?>><div class="col-sun"> 日</div></label>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi1" value = "1" <?php  echo ( in_array ( 1 , $yobi ) )? 'checked' : ''; ?>> 月</label>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi2" value = "2" <?php  echo ( in_array ( 2 , $yobi ) )? 'checked' : ''; ?>> 火</label>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi3" value = "3" <?php  echo ( in_array ( 3 , $yobi ) )? 'checked' : ''; ?>> 水</label>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi4" value = "4" <?php  echo ( in_array ( 4 , $yobi ) )? 'checked' : ''; ?>> 木</label>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi5" value = "5" <?php  echo ( in_array ( 5 , $yobi ) )? 'checked' : ''; ?>> 金</label>
                <label class="checkbox-inline"><input type="checkbox" name="yobi[]" id="yobi6" value = "6" <?php  echo ( in_array ( 6 , $yobi ) )? 'checked' : ''; ?>><div class="col-sat"> 土</div></label>
              </div>
            </td>
          </tr>
          <tr>
            <th class="pt12">日付</th>
            <td>
              <div class="form-inline">
                  <div class=" input-group date">
                    <input type="text" id="date_timepicker_start" name="search_ymd_stt" value="<?php echo $sttdt; ?>" style="width:100px">
                    <span id="sttbtn" class="input-group-addon"></span>
              </div>～
              <div class=" input-group date">
                <input type="text" id="date_timepicker_end" name="serch_ymd_end" value="<?php echo $enddt; ?>" style="width:100px">
                <span  id="endbtn" class="input-group-addon"></span>
              </div>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="submit" class="btn btn-default " value="検索する >>">
            </td>
          </tr>
        </tbody>
      </table>
    </div><!-- col-xs-8 -->
    </form>
    <div class="col-xs-4">
      <a href="help.html#akijoukyou"  class="btn alert-info" target="window_name" onClick="disp('help.html#akijoukyou')"><li class="glyphicon glyphicon-question-sign" aria-hidden="true">&nbsp;この画面の操作方法についてはこちら>></li></a> <br>
    </div>
  </div>