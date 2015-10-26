<?php
/*
// �{���̓��t����N�x���Z�o����(����2��)
*/
function get_financial_year(){
	
	define('CLOSING_MONTH', 3); //���Z��
	
	if( (int)date( 'm' ) > CLOSING_MONTH ){
        return date( 'y' );
    } else {
        return date( 'y' ) - 1;
    }

}
/*
// ���������ɉ��������t�z����쐬����
*/
function get_date_array( $start , $end ,  $yobi )
{

	//�����l
    $stack  = array();
	$weekday = array( "��", "��", "��", "��", "��", "��", "�y" );//���{��j����`
	//echo  "get_date_array param is ..." . $start ." , ". $end ;//." , ". $yobi ;
	//print_r($yobi);
	
	/*�p�����[�^�`�F�b�N*/
	//�I�������Ȃ��ꍇ�i���փ{�^���j +15 week?? 
	//if( empty ( $end ) ){
	//	date("Y/m/d",strtotime("+15 week" ,strtotime($start)));
	//}
	
	// ��؂蕶����"/"������
	$start = str_replace("/", "", $start);
	$end = str_replace("/", "", $end);
	//echo  "get_date_array param is ..." . $start ." , ". $end ;//." , ". $yobi ;
	
	for ( $element = $start;  $element <= $end;  $element++ ) {

		if( count( $stack )  > 13 ){
			break;
		}
		
		$yyyy = substr( $element, 0, 4 );
		$mm = substr( $element, 4, 2 );
		$dd = substr( $element, 6, 2 ) ;

		if ( checkdate( $mm, $dd, $yyyy ) ){ //���t�Ƃ��đÓ���
			
			//�j���̎Z�o
			$w =  date( "w", mktime( 0,  0,  0, $mm, $dd, $yyyy ) );

			$wd = mb_convert_encoding( $weekday[ $w ], "UTF-8", "SJIS");

			if ( in_array ( $w, $yobi ) ) { //�z��ɑ��݂��邩
				
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

	$weekday = array( "��", "��", "��", "��", "��", "��", "�y" );//���{��j����`
	
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
?>