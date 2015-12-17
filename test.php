<?php
echo "test1";
//include("model/Reserve.php");
include("model/Seikyu.php");


//$Reserve = new Reserve();

//$list = array();
//$list[] = array('gyo' => 1, 'usedt' => '20151213' , 'rmcd' => '801' , 'timekb' => 2);
//$Reserve->reserve( $list,  15000182 , "test" ) ;

echo "test1";
$Seikyu = new Seikyu();
echo "test1";

$ukeno =771;
$ukedt = 20151214;
$kyacd = 1; 

$list = array();
echo "test1";

/*$usedt = $rec['usedt'];     //使用日
            $yobi = $rec['yobi'];       //使用曜日
            $yobikb = $rec['yobikb'];   //使用曜日区分
            $hzkb = 0;                  //付属設備区分
            $rmcd = $rec['rmcd'];       //施設コード
            $rmnmr = $rec['rmnmr'];     //施設名称
            $hzcd = 0;                  //付属設備コード
            $hznmr = ''；                //付属設備名称
            $stjkn = $rec['stjkn'];     //使用開始時間
            $edjkn = $rec['edjkn'];     //使用終了時間
            $hbstjkn = $rec['hbstjkn']; //本番開始時間
            $hbedjkn = $rec['hbedjkn']; //本番終了時間
            if( $rec['pianokb'] ==1 ){
            	$kin = $rec['rmkin'];*/


$list[] = array('gyo' => 1, 'usedt' => '20151213', 'yobi' => '月', 'yobikb' => 1,'rmcd' => '801', 'rmnmr' => '会議室８０１', 'stjkn' => 900, 'edjkn' => 1200 , 
	'hbstjkn' => 900 , 'hbedjkn' => 1200, 'piano'=>1 ,'rmkin'=> 16000 , 'hzkin'=>6500);
$list[] = array('gyo' => 2, 'usedt' => '20151213', 'yobi' => '月', 'yobikb' => 1,'rmcd' => '802', 'rmnmr' => '会議室８０２', 'stjkn' => 900, 'edjkn' => 1200 , 
      'hbstjkn' => 900 , 'hbedjkn' => 1200, 'piano'=>1 ,'rmkin'=> 16000 , 'hzkin'=>6500);
echo "test2";
print_r($list);
$Seikyu->seikyu( $ukeno, $ukedt, $kyacd, $list );

?> 