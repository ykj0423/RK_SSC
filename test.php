<?php
//echo "test1";
//include("model/Reserve.php");
$ukedt=20160103;
            $nen = substr( $ukedt, 0, 4 );
        $m = intval(substr( $ukedt, 4, 2 ));
        if( $m < 10 ) { $m = '0'.$m; }
        $d = intval(substr( $ukedt, 6, 4 ));
        if( $d < 10 ) { $d = '0'.$d; }

echo $ukedt;
         echo "<br>";
         echo $nen;
         echo "<br>";echo $m;
         echo "<br>";echo $d;
         echo "<br>";


         $date_ukedt = strtotime( $nen.'-'.$m.'-'.$d );
         echo $date_ukedt;
         echo "<br>";
        $paylmtdt = date('Ymd', strtotime(' +9 days', $date_ukedt));
        echo $paylmtdt;

?> 