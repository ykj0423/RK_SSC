<?php
/*
// 本日の日付から年度を算出する(西暦2桁)
*/
function get_financial_year(){
	
	define('CLOSING_MONTH', 3); //決算月
	
	if( (int)date( 'm' ) > CLOSING_MONTH ){
        return date( 'y' );
    } else {
        return date( 'y' ) - 1;
    }

}
/*
// 検索条件に応じた日付配列を作成する
*/
function get_date_array( $start, $end,  $yobi, $span_stt, $span_end )
{

	//初期値
    $stack  = array();
	//$weekday = array( "日", "月", "火", "水", "木", "金", "土" );//日本語曜日定義
	$weekday = array('日','月','火','水','木','金','土');
	//echo  "get_date_array param is ..." . $start ." , ". $end ;//." , ". $yobi ;
	//print_r($yobi);
	
	/*パラメータチェック*/
	//終了日がない場合（次へボタン） +15 week?? 
	//if( empty ( $end ) ){
	//	date("Y/m/d",strtotime("+15 week" ,strtotime($start)));
	//}
	
	// 区切り文字を"/"を除去
	$start = str_replace("/", "", $start);
	$span_stt = str_replace("/", "", $span_stt);
	
	if($start < $span_stt){
		$start = $span_stt;	
	}
	
	$end = str_replace("/", "", $end);
	$span_end = str_replace("/", "", $span_end);
	
	if($end > $span_end){
		$end = $span_end;	
	}
	//echo  "get_date_array param is ..." . $start ." , ". $end ;//." , ". $yobi ;
	
	for ( $element = $start;  $element <= $end;  $element++ ) {

		if( count( $stack )  > 13 ){
			break;
		}
		
		$yyyy = substr( $element, 0, 4 );
		$mm = substr( $element, 4, 2 );
		$dd = substr( $element, 6, 2 ) ;

		if ( checkdate( $mm, $dd, $yyyy ) ){ //日付として妥当か
			
			//曜日の算出
			$w =  date( "w", mktime( 0,  0,  0, $mm, $dd, $yyyy ) );

			//$wd = mb_convert_encoding( $weekday[ $w ], "UTF-8", "SJIS");
			$wd = $weekday[ $w ];
			if ( in_array ( $w, $yobi ) ) { //配列に存在するか
				
				array_push( $stack , array(
							   "yyyy" => $yyyy,
							   "mm" => $mm,
							   "dd" => $dd,
							   "w" => $w,
							   "wd" => $wd
								)
							);
			
			}

		}
		
	}
	//echo  "<br> get_date_array result is ...";
	//print_r($stack);
	return $stack;

}

function get_wday( $param )
{
	
	$yyyy = substr( $param, 0, 4 );
	$mm = substr( $param, 4, 2 );
	$dd = substr( $param, 6, 2 ) ;
	$w =  date( "w", mktime( 0,  0,  0, $mm, $dd, $yyyy ) );		
		
	return $w;

}

function get_mb_wday( $param )
{

	$weekday = array( "日", "月", "火", "水", "木", "金", "土" );//日本語曜日定義
	
	$yyyy = substr( $param, 0, 4 );
	$mm = substr( $param, 4, 2 );
	$dd = substr( $param, 6, 2 ) ;
	
	$w =  date( "w", mktime( 0,  0,  0, $mm, $dd, $yyyy ) );
	//$wd = mb_convert_encoding( $weekday[ $w ], $to_encoding, $from_encoding );
	$wd = mb_convert_encoding( $weekday[ $w ]);//, "UTF-8", "SJIS");		
	return $wd;

}
function format_jkn( $param , $delimiter )
{
	$val = $param;
	$val = str_pad( $param, 4, "0", STR_PAD_LEFT );
	$val = intval( substr( $val, 0, 2 ) ) .  $delimiter . substr( $val , 2, 2 );
	return $val;
}

function format_db_jkn( $h , $m )
{
	
	$val = 0;

	if(is_numeric($h)){

		$val = intval( $h ) * 100;

	}
	
	if(is_numeric($m)){

		$val = intval( $val ) + intval( $m );

	}

	return $val;

}

function format_tel( $param1, $param2, $param3, $delimiter )
{

    if( !empty($param1) && !empty($param2) && !empty($param3) ){
        
        if( is_numeric($param1) && is_numeric($param2) && is_numeric($param3) ){
            return $param1.$delimiter.$param2.$delimiter.$param3;
        }
    
    }

    return false;
}

function format_zipcd( $param1, $param2, $delimiter )
{

    if( !empty($param1) && !empty($param2) ){
        
        if( is_numeric($param1) && is_numeric($param2) ){
            return $param1.$delimiter.$param2;
        }
    
    }

    return false;
}
function judge_tyusyo( $shinon, $gyscd, $ninzu )
{

	//製造業、建設業、運輸業、漁業林業、鉱業、電気・ガス・熱供給・水道業、通信業、金融・保険業、不動産業・物品賃貸業、教育・学習支援業、医療・福祉
	if( $gyscd == 4 ){
		
		if( ( $shinon <= 30000 ) || ( $ninzu <= 300 ) ){
				return true;
		}
		
		return false;
	}

	//卸売業
	if( $gyscd == 3 ){
	
		if( ( $shinon <= 10000 ) || ( $ninzu <= 100 ) ){
			return true;
		}

		return false;
	}

	//サービス業
	if( $gyscd == 2 ){
	
		if( ( $shinon <= 5000 ) || ( $ninzu <= 100 ) ){
			return true;
		}

		return false;
	}

	//小売業
	if( $gyscd == 2 ){
	
		if( ( $shinon <= 5000 ) || ( $ninzu <= 50 ) ){
			return true;
		}

		return false;
	}

	
	return false;
}

//DB登録のためSJISにコンバート
 function convert_to_SJIS( $str )
 {

    if( isset( $str ) && !empty( $str ) ){
        return mb_convert_encoding($str, "SJIS", "UTF-8");
    }else{
        return "";
    }

}
?>