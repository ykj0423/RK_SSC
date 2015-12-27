<?php
   //session_start();
    /*print('セッション変数の一覧を表示します。<br>');
    print_r($_SESSION);
    print('<br>');
*/
    //print('セッションIDを表示します。<br>');
    //if(isset($_COOKIE["PHPSESSID"])){
    //print($_COOKIE["PHPSESSID"].'<br>');
    //}

    //print('<p>ログアウトします</p>');

    //$_SESSION = array();

    /*if (isset($_COOKIE["PHPSESSID"])) {
        setcookie("PHPSESSID", '', time() - 1800, '/');
    }*/

    //session_destroy();

    $ini = parse_ini_file('config.ini');
    //echo "SYSTEM_NAME".$ini['SYSTEM_NAME'];
    $_SESSION['sysname'] = $ini['SYSTEM_NAME'];
    $_SESSION['centername'] = $ini['CENTER_NAME'];

    $_SESSION['kyacd'] = "";
    $_SESSION['dannm'] = "";
    $_SESSION['kyakb'] = "";
    $_SESSION['kounoukb'] = "";
?>