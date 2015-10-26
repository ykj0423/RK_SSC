<?php 
//----------------------
//‘ÎÛ“ú
//----------------------
//–{“–‚Í$date_array‚ÍƒNƒ‰ƒX‚ğì¬‚µ‚Ä‚»‚±‚©‚çæ‚Á‚½‚Ù‚¤‚ª—Ç‚¢

for ( $k = 0; $k < count ( $date_array ) ; $k++ ) {
    echo "<th width=\"50\">".intval( $date_array[ $k ][ 'mm' ] )."</th>";
}

echo "</tr>";
echo "<tr class=\"head\">";

for ( $k = 0; $k < count ( $date_array ) ; $k++ ) {
	echo "<th>".intval( $date_array[ $k ][ 'dd' ] )."</th>";
}

echo "</tr>";
echo "<tr class=\"head\">";

for ( $k = 0; $k < count ( $date_array ) ; $k++ ) {
	
	$w = $date_array[ $k ][ 'w' ];
	$wd = $date_array[ $k ][ 'wd' ];
	
	if( $w == 6 ){      //“y—j“ú‚Ìê‡
    
		echo "<th class=\"col-sat\">".$wd."</th>";
    
	}elseif( $w == 0 ){ //“ú—j“ú‚Ìê‡
    
		echo "<th class=\"col-sun\">".$wd."</th>";
    
	}else{
    
		echo "<th>".$wd."</th>";
    
	}

}
?>