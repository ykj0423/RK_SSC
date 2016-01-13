<?php @session_start();
//if(empty($_SESSION['webrk']['user']['userid'])){
//	header("Location : top.php");	
//}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta NAME="ROBOTS" CONTENT="NOINDEX,NOFOLLOW,NOARCHIVE">
<title>使用申込[確認]　 | 神戸市産業振興センター　予約システム</title>
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/custom.css" rel="stylesheet">
<!--script src="js/confirm.reserve.js"></script-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<!--script src="js/input.reserve.js"></script -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="container">
<?php
include('session_check.php');
//print_r($_POST);
require_once( "func.php" );
require_once( "model/db.php" );
include("model/Kyaku.php"); 
include("include/menu.php"); 
$Kyaku = unserialize( $_SESSION['Kyaku'] );
$Kyaku->get_user_info( $_SESSION['wloginid'] );

/* データベース接続 */
$db = new DB;
$conErr = $db->connect();
if ( !empty( $conErr ) ) { echo $conErr;  die(); } //接続不可時は終了

?>

   <div class="row">
  		<div class="col-xs-6" style="padding:0">
        	<h1><span class="midashi">|</span>使用申込[確認]</h1>
       	</div>
      	<div class="col-xs-6  text-right">
        	<span class="f120">現在の時間：　<span id="currentTime"></span>
       	</div>
   </div>
   <h4>入力内容をご確認いただき、問題がなければ「送信する」ボタンを押してください。</h4>
  <span class="status2">＊＊ この画面では、ご予約は確保されていません。ご希望の内容を送信後、受付結果をメールでお知らせいたします。＊＊</span><br><br>
	<!--form name="confirm_form" id="confirm_form" role="form" action="end.php"　method="post"-->
	<form class="form-horizontal" name="confirm_form" id="confirm_form" role="form" method="post" action="end.php">
		<?php 
			
			echo "<input type='hidden' name='kaigi' id='kaigi' value=\"".$_POST[ 'kaigi' ]."\">";
			echo "<input type='hidden' name='riyokb' id='riyokb' value=\"".$_POST[ 'riyokb' ]."\">";
			//echo "<input type='hidden' name='sekinin' id='sekinin' value=\"".$_POST[ 'sekinin' ]."\">";
			$str_naiyo = "<input type='hidden' name='naiyo' id='riyokb' value=\"";

			if(isset($_POST[ 'naiyo' ])){
				$str_naiyo .= $_POST[ 'naiyo' ];	
			}
			
			$str_naiyo .= "\">";
			echo "<input type='hidden' name='sekinin' id='sekinin' value=\"".$_POST[ 'sekinin' ]."\">";
		?>
		<table id ="rsv_input" class="table table-bordered table-condensed  form-inline">
		    <tbody>
		        <tr><th colspan="8">お申込み内容</th></tr>
		        <tr>
    				<th colspan="2">利用者名</th>
    				<td colspan="6"><?php echo mb_convert_encoding($Kyaku->get_dannm(), "UTF-8", "SJIS"); ?></td>
    			</tr>
		        <tr>
			    	<th colspan="2">メールアドレス</th>
			    	<td colspan="6"><?php echo $Kyaku->get_mail(); ?>
			        </td>
		        </tr>
		        <tr>
			        <th colspan="2" width="20%">行事名称</th>
			        <td colspan="6" width="70%"><?php echo $_POST[ 'kaigi' ]; ?></td>
		        </tr>
		        <tr>
			        <th colspan="2">利用目的</th>
			        <td colspan="6">
					<?php 
					//利用目的はマスタから読み込む
					if ( !empty( $_POST['riyokb'] ) ) {
						
						$riyo = $db->get_mm_riyo( $_POST['riyokb'] );

						for ( $i = 0; $i < ( count( $riyo ) ) ; $i++ ) {
							echo mb_convert_encoding( $riyo[ $i ][ 'name' ], "utf8", "SJIS" );
							break;						
						}
					
					}
					?></td>
		        </tr>
		        <!--tr>
			        <th  colspan="2">利用人数<span class="red">（必須)</span></th>
			        <td  colspan="2"><?php //echo $_POST['ninzu']; ?></td>
		        </tr-->
		         	<?php if( isset( $_POST[ 'naiyo' ] )  && ( !empty( $_POST[ 'naiyo' ] ) ) ){ ?>
		         	<tr>
					<th colspan="2">内容</th>
		      		<td colspan="6"><?php echo $_POST[ 'naiyo' ]; ?></td>
		      		</tr>
		         	<?php } ?>
			    <tr>
		      		<th  colspan="2">使用当日の管理責任者名</th>
		      		<td  colspan="6"><?php echo $_POST[ 'sekinin' ]; ?></td>
		      	</tr>			   
		        <tr><th colspan="8">お申し込み施設</th></tr>
		    	<tr>
		    	<th width="12%">使用日/施設名</th>
		    	<th width="12%">使用時間</th>
		    	<th width="22%">時間内訳</th>
		      	<th width="8%">利用人数</th>
		      	<th>その他確認事項</th>
		    	<th>使用料</th>
		    	<th>設備料</th>
		    	</tr>
	        </tbody>
	        <tbody id="list">
	        	<?php

					$ini = parse_ini_file('config.ini');        
					$serverName = $ini['SERVER_NAME'];
					$connectionInfo = array( "Database"=>$ini['DBNAME'], "UID"=>$ini['UID'], "PWD"=>$ini['PWD'] );
					$conn = sqlsrv_connect( $serverName, $connectionInfo);

					if( $conn === false ) {           
					    die( print_r( sqlsrv_errors(), true));				
					}

					/* 初期値 */
					$go = true;
					$total = 0;
					$holekb = 0;
	        		
	        		for( $i = 0; $i < $_POST['meisai_count']; $i++){
	        			
	        			//施設単価						
						$kyakb = $_SESSION['kyakb'];

						if( isset( $_POST['rmcd'.$i] ) && ( !empty( $_POST['rmcd'.$i] ) ) ){							
							
							$stjkn = 0;
							$edjkn = 0;
							$hstjkn = 0;
							$hedjkn = 0;
							$rmtnk = 0;
							$rmentnk = 0;							
							$rmcd = $_POST['rmcd'.$i];
							$usedt = $_POST['usedt'.$i];
							$rmnm = $_POST['rmnm'.$i];
							//ホールを含むかどうか
							if( $rmcd == 301){
								$holekb = 1;
							}

							//親子施設の場合、コードが書き換えられているため、再度名称を取得
							$sql = "SELECT rmnmw FROM mt_room WHERE rmcd = ".$rmcd;
												        
						    $stmt = sqlsrv_query( $conn, $sql );
						    
						    if( $stmt === false) {
						    	//print_r( sqlsrv_errors(), true);
							}

						 	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
						        $rmnm = mb_convert_encoding($row['rmnmw'], "UTF-8","SJIS");
						    }
							
							echo "<td>".format_ymd( $usedt )."<br>".$rmnm."</td>";
							
							$stjkn = $_POST['stjkn_h'.$i].$_POST['stjkn_m'.$i];
							$edjkn = $_POST['edjkn_h'.$i].$_POST['edjkn_m'.$i];
							
							$timekb = $_POST['timekb'.$i];
							$yobi = $_POST['yobi'.$i];
							$yobikb = $_POST['yobikb'.$i];
							
							$biko = $_POST['biko'.$i];
							$zgrt = 100;

							//使用開始、終了時間
							echo "<td>".format_jkn( $stjkn, ":", "" )."～".format_jkn( $edjkn, ":", "" )."</td>";

							//本番時間
							$hstjkn = $_POST['hstjkn_h'.$i].$_POST['hstjkn_m'.$i];
							$hedjkn = $_POST['hedjkn_h'.$i].$_POST['hedjkn_m'.$i];

							if($rmcd==301){
								
								echo "<td>";
								//準備時間
								if( isset($_POST['jnstjkn_h'.$i]) && isset($_POST['jnstjkn_m'.$i]) && isset($_POST['jnedjkn_h'.$i]) && isset($_POST['jnedjkn_m'.$i]) ){
									
									echo "<input type='hidden' name='jnstjkn_h".$i."' jnstjkn_h".$i."' value='".$_POST['jnstjkn_h'.$i]."'>";
									echo "<input type='hidden' name='jnstjkn_m".$i."' jnstjkn_m".$i."' value='".$_POST['jnstjkn_m'.$i]."'>";		
									echo "<input type='hidden' name='jnedjkn_h".$i."' jnedjkn_h".$i."' value='".$_POST['jnedjkn_h'.$i]."'>";
									echo "<input type='hidden' name='jnedjkn_m".$i."' jnedjkn_m".$i."' value='".$_POST['jnedjkn_m'.$i]."'>";
									echo "準備・リハ：".$_POST['jnstjkn_h'.$i].":".$_POST['jnstjkn_m'.$i]."～".$_POST['jnedjkn_h'.$i].":".$_POST['jnedjkn_m'.$i]."<br>";
								
								}
								
								if( isset($_POST['hstjkn_h'.$i]) && isset($_POST['hstjkn_m'.$i]) && isset($_POST['hedjkn_h'.$i]) && isset($_POST['hedjkn_m'.$i]) ){
									
									echo "<input type='hidden' name='hstjkn_h".$i."' hstjkn_h".$i."' value='".$_POST['hstjkn_h'.$i]."'>";
									echo "<input type='hidden' name='hstjkn_m".$i."' hstjkn_m".$i."' value='".$_POST['hstjkn_m'.$i]."'>";		
									echo "<input type='hidden' name='hedjkn_h".$i."' hedjkn_h".$i."' value='".$_POST['hedjkn_h'.$i]."'>";
									echo "<input type='hidden' name='hedjkn_m".$i."' hedjkn_m".$i."' value='".$_POST['hedjkn_m'.$i]."'>";
									echo "本　　番：".$_POST['hstjkn_h'.$i].":".$_POST['hstjkn_m'.$i]."～".$_POST['hedjkn_h'.$i].":".$_POST['hedjkn_m'.$i]."<br>";
								
								}
								
								if( isset($_POST['tkstjkn_h'.$i]) && isset($_POST['tkstjkn_m'.$i]) && isset($_POST['tkedjkn_h'.$i]) && isset($_POST['tkedjkn_m'.$i]) ){

									echo "<input type='hidden' name='tkstjkn_h".$i."' tkstjkn_h".$i."' value='".$_POST['tkstjkn_h'.$i]."'>";
									echo "<input type='hidden' name='tkstjkn_m".$i."' tkstjkn_m".$i."' value='".$_POST['tkstjkn_m'.$i]."'>";		
									echo "<input type='hidden' name='tkedjkn_h".$i."' tkedjkn_h".$i."' value='".$_POST['tkedjkn_h'.$i]."'>";
									echo "<input type='hidden' name='tkedjkn_m".$i."' jnedjkn_m".$i."' value='".$_POST['tkedjkn_m'.$i]."'>";
									echo "撤　　去：".$_POST['tkstjkn_h'.$i].":".$_POST['tkstjkn_m'.$i]."～".$_POST['tkedjkn_h'.$i].":".$_POST['tkedjkn_m'.$i]."<br>";

								}
								
								echo "</td>";

							}else{
																
								echo "<td>".format_jkn( $hstjkn , ":", "" )."～".format_jkn( $hedjkn, ":", "" )."</td>";

							}

							
							$ninzu = $_POST['ninzu'.$i];

							if(!is_numeric($ninzu)){
								$go = false;
								echo "<span class=\"status2\">人数を正しく入力してください<br></span>";										
							}

							echo "<td>".$ninzu."人</td>";							
							echo "<td>";

							$comlkb = 0;

							if(isset( $_POST['comlkb'.$i] )){
								$comlkb = $_POST['comlkb'.$i];
							}

							$feekb = 0;

							if(isset( $_POST['feekb'.$i] )){
								$feekb = $_POST['feekb'.$i];
							}

							$partkb = -1;
							
							if(isset( $_POST['partkb'.$i] )){
								$partkb = $_POST['partkb'.$i];
							}

							//ピアノ
							$piano = 0;
							
							if(isset( $_POST['piano'.$i] )){
							
								$piano = $_POST['piano'.$i];

								if( $piano == 1 ){
									echo "グランドピアノの利用：する<br>";
								}else{
									echo "グランドピアノの利用：しない<br>";
								}
							
							}
							
							//営利目的
							if( $comlkb == 1 ){
								echo "営利目的での利用：する<br>";
							}else{
								echo "営利目的での利用：しない<br>";
							}
							//入場料
							if( $feekb == 1 ){
								echo "入場料・受講料等の徴収：する<br>";
							}else{
								echo "入場料・受講料等の徴収：しない<br>";
							}
							//間仕切り
							if(isset( $_POST['partkb'.$i] )){
								if( $partkb == 1 ){
									echo "間仕切り：閉める<br>";
								}else{
									echo "間仕切り：開ける<br>";
								}
							}
							
							if(isset( $_POST['biko'.$i] )){
								echo ( $_POST['biko'.$i] );
							}
							echo "</td>";
							
							//営利目的割り増し
						    if( $comlkb == 1 && $feekb == 1 ){
						    	
						    	$zgrt = 150;

						    }
						    
						    if($holekb == 1){

							    //練習準備撤去割引
							    if($hstjkn==0 && $hedjkn==0){

							    	$zgrt = 50;
							    	$tnk_hstjkn = $stjkn;
							    	$tnk_hedjkn = $edjkn;

							    }else{

							    	if($hstjkn <= 1200){
						    			$tnk_hstjkn = 900;
							    	}elseif($hstjkn <= 1700){
							    		$tnk_hstjkn = 1300;
							    	}else{
							    		$tnk_hstjkn = 1800;
							    	}

							    	if($hedjkn <= 1200){
							    		$tnk_hedjkn = 1200;
							    	}elseif($hedjkn <= 1700){
							    		$tnk_hedjkn = 1700;
							    	}else{
							    		$tnk_hedjkn = 2100;
							    	}


							    }

						    	
								$sql = "SELECT tnk, entnk FROM mt_rmtnk WHERE rmcd = ".$rmcd." AND kyakb = ".$kyakb." AND ratesb = 0 AND stjkn = ".$stjkn." AND edjkn = ".$edjkn;
								$sql .= " AND hstjkn = ".$tnk_hstjkn." AND hedjkn = ".$tnk_hedjkn;
							}else{
								
								$sql = "SELECT tnk, entnk FROM mt_rmtnk WHERE rmcd = ".$rmcd." AND kyakb = ".$kyakb." AND ratesb = 0 AND stjkn = ".$stjkn." AND edjkn = ".$edjkn;
								$sql .= " AND hstjkn = ".$stjkn." AND hedjkn = ".$edjkn;
						    
						    }
					        
//echo $sql."<br>";
//echo "増減率".$zgrt."<br>";
						    $stmt = sqlsrv_query( $conn, $sql );
						    
						    if( $stmt === false) {
						    	//echo "mt_rmtnk";
						    	//echo $sql;
						    	print_r( sqlsrv_errors(), true);
							}

						 	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {

						        $rmtnk = $row['tnk'];		//通常単価
						        $rmentnk = $row['entnk'];	//延長単価

						    }  
							
							//$go = true;
							
							if($holekb == 1){
							
								if($kyakb!=99){
							
									if(empty($rmtnk)){
							
										$go = false;
										echo "<span class=\"status2\">時間内訳を正しく入力してください<br></span>";
										//echo $sql;
							
									}
							
								}
							
							}
						    		
						    //通常金額
						    $rmtukin = $rmtnk * $zgrt / 100;

						    //延長金額
						    $rmenkin = $rmentnk;// * $zgrt / 100;

						    //施設使用合計金額
						    //$rmkin = intval( $rmtukin ) + intval( $rmenkin );
						    $rmkin =  $rmtukin  + $rmenkin;

						    //付属設備
						    $hzkin = 0;
							
							//グランドピアノ
							if($piano==1){
							
								$sql = "SELECT tnk FROM mt_hztnk WHERE hzcd = 1006 AND kyakb = ".$kyakb;
								
								$stmt = sqlsrv_query( $conn, $sql );
													    
							    if( $stmt === false) {
							    	//echo "mt_rmtnk";
							    	//echo $sql;
							    	//print_r( sqlsrv_errors(), true);
								}

							 	while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
							        $hzkin = $row['tnk'];
							    }  
									
							}

							echo "<input type='hidden' name='rmtnk".$i."' id='rmtnk".$i."' value='".$rmtnk."'>";
							echo "<input type='hidden' name='rmentnk".$i."' id='rmentnk".$i."' value='".$rmentnk."'>";
							echo "<input type='hidden' name='rmtukin".$i."' id='rmtukin".$i."' value='".$rmtukin."'>";
							echo "<input type='hidden' name='rmenkin".$i."' id='rmenkin".$i."' value='".$rmenkin."'>";
							echo "<input type='hidden' name='rmkin".$i."' id='rmkin".$i."' value='".$rmkin."'>";
							echo "<input type='hidden' name='hzkin".$i."' id='hzkin".$i."' value='".$hzkin."'>";
						    echo "<td>\\".number_format($rmtukin)."</td>";
							echo "<td>\\".number_format($hzkin)."</td>";
							
							$gyo = $i+1;

							echo "<input type='hidden' name='rmcd".$i."' id='rmcd".$i."' value='".$rmcd."'>";
							echo "<input type='hidden' name='gyo".$i."' id='gyo".$i."' value='".$gyo."'>";
							echo "<input type='hidden' name='usedt".$i."' id='usedt".$i."' value='".$usedt."'>";
							echo "<input type='hidden' name='timekb".$i."' id='timekb".$i."' value='".$timekb."'>";
							echo "<input type='hidden' name='stjkn".$i."' id='stjkn".$i."' value='".$stjkn."'>";
							echo "<input type='hidden' name='edjkn".$i."' id='edjkn".$i."' value='".$edjkn."'>";
							echo "<input type='hidden' name='ninzu".$i."' id='ninzu".$i."' value='".$ninzu."'>";
							
							echo "<input type='hidden' name='piano".$i."' id='piano".$i."' value='".$piano."'>";
							echo "<input type='hidden' name='partkb".$i."' id='partkb".$i."' value='".$partkb."'>";
							echo "<input type='hidden' name='yobi".$i."' id='yobi".$i."' value='".$yobi."'>";
							echo "<input type='hidden' name='yobikb".$i."' id='yobikb".$i."' value='".$yobikb."'>";
							echo "<input type='hidden' name='rmnm".$i."' id='rmnm".$i."' value='".$rmnm."'>";
							echo "<input type='hidden' name='hbstjkn".$i."' id='hbstjkn".$i."' value='".$hstjkn."'>";
							echo "<input type='hidden' name='hbedjkn".$i."' id='hbedjkn".$i."' value='".$hedjkn."'>";
							echo "<input type='hidden' name='comlkb".$i."' id='comlkb".$i."' value='".$comlkb."'>";
							echo "<input type='hidden' name='feekb".$i."' id='comlkb".$i."' value='".$feekb."'>";
							echo "<input type='hidden' name='biko".$i."' id='biko".$i."' value='".$biko."'>";
							
							echo "</tr>";
							
							$total = $total + $rmkin + $hzkin;

						}

	        		}

	        	?>        	
	        </tbody>
	        <tbody id="list">	        	
	        </tbody>
	        <tbody>
	          	<tr>
        		<td class="text-right  f120" colspan="6">使用料合計</th>
         		<td colspan="2" class="text-right f120">
         			<div id="total">\<?php echo number_format($total); ?>
         			</div>
         		</th>
      			</tr>
	    	</tbody>
	        </table>
	        
	        <input type='hidden' name="meisai_count" value='<?php echo $_POST['meisai_count']; ?>'>
	        <?php if($holekb == 1){ ?>
	      		<span class="red">ホールのご利用時の人件費は上記使用料に含まれません。別途ご請求させていただきます。<br>詳細は事前打合せで決定いたします。使用日の1か月前までに必ず事前打合せをお願いいたします。</span><br><br>
	        <?php } ?>
			 <div class="form-group">
			 	<div class="row mb20">
			 		<a class="btn btn-default btn-lg" href="javascript:history.back();">&lt;&lt;&nbsp;修正する</a>&nbsp;
			 		<?php if($go){ ?>
					<input type='submit' class="btn btn-primary btn-lg" role="button" name="submit_next" id="submit_next" value='送信する&nbsp;&gt;&gt;'>
			 		<?php } ?>
				</div>
	        </div>
	    </div>
	</form>
<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>