<?php
  session_start();
 //        $col = 3;//表示件数
 //        $result = array();//出力用の配列
 //        /*
 //  	$url = "localhost";
 // 	$user = "root";
 //  	$pass = "";
 //  	$db = "test";
 //        */
        
	// $link = mysql_connect($url,$user,$pass) or die("MySQLへの接続に失敗しました。");
 // 	$sdb = mysql_select_db($db,$link) or die("データベースの選択に失敗しました。");
 //        //文字コードの変換
 //        mysql_query('SET NAMES utf8');
 //        //ページ数を計算
 //        $count ="SELECT count(*) as cnt FROM girl_lists";
 //        $count_result = mysql_query($count, $link) or die("クエリの送信に失敗しました。<br />SQL:".$count);
 //        $count_num = mysql_fetch_array($count_result);
        
 //        if($count_num['cnt']%$col==0){
 //            $pages= floor($count_num['cnt']/$col)-1;
 //        }else{                
 //          $pages = floor($count_num['cnt']/$col);
 //        }
 //        //ページ数の表示
 //        if(!isset($_SESSION['page_no']))
	// {
	// $_SESSION['page_no'] = 0;
	// }
 //        if($_POST['val'] ==  1 && $_SESSION['page_no'] < $pages)
	// {
	// $_SESSION['page_no'] += 1;
	// }
	
	// if($_POST['val'] ==  2 && $_SESSION['page_no'] > 0)
	// {
	// $_SESSION['page_no'] -= 1;
	// }
	// $page_start = $_SESSION['page_no'] * $col;	
        
 // 	//表示するデータを出力                
	// 	$sql = "SELECT * FROM girl_lists LIMIT {$page_start},$col";
 //  		$sql_result = mysql_query($sql, $link) or die("クエリの送信に失敗しました。<br />SQL:".$sql);
	
	// 		while($rows = mysql_fetch_array($sql_result))
	// 		{
	// 			$result[] = array(
 //                                            'id'=>"{$rows['id']}",
 //                                            'name'=>"{$rows['name']}",                                                    
 //                                            'tall'=>"{$rows['tall']}",
 //                                            'bust'=>"{$rows['bust']}",
 //                                            'west'=>"{$rows['west']}",
 //                                            'hip'=>"{$rows['hip']}"
 //                                            );
	// 		}
    $result= array(
                    'id'=>"1",
                    'pass'=>"pass"
                    );
    // $result[] = array(
    // 'id'=>"2",
    // 'name'=>"name",                                                    
    // 'tall'=>"tall",
    // 'bust'=>"bust",
    // 'west'=>"west",
    // 'hip'=>"hip"
    // );
    // $result[] = array(
    // 'id'=>"3",
    // 'name'=>"name",                                                    
    // 'tall'=>"tall",
    // 'bust'=>"bust",
    // 'west'=>"west",
    // 'hip'=>"hip"
    // );
    // $result[] = array(
    // 'id'=>"4",
    // 'name'=>"name",                                                    
    // 'tall'=>"tall",
    // 'bust'=>"bust",
    // 'west'=>"west",
    // 'hip'=>"hip"
    // );
	$result = json_encode($result);
    echo $result;       
?>