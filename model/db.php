<?php

class DB
{
    var $con;
    var $Errcd;
    var $ErrMsg;

    public function db()
    { 
        $this->con = null;
        $this->Errcd = null;
        $this->ErrMsg = null;
    }


    public function connect()
    {
        $info = '';
        $ini = parse_ini_file('./config.ini');

        $conInfo = array(
            'UID' => $ini['UID'],
            'PWD' => $ini['PWD'],
            'Database' =>  $ini['DBNAME']);

        $this->con = sqlsrv_connect($ini['SERVER_NAME'], $conInfo);

        if($this->con == false){

        	$info = "Cound not connect.\n";

            if(($errors = sqlsrv_errors()) != null){
                foreach($errors as $error){
        	        $info = $info."SQLSTATE: ".$error[ 'SQLSTATE']."\n";
        	        $info = $info."code: ".$error[ 'code']."\n";
        	        $info = $info."message: ".$error[ 'message']."\n";
                }
            }
        }

        return $info;

    }

    /*--------------------------------------------------------
    //  selectした結果を（文字項目はutf8に変換した後）配列で渡す。
    //
    //      引数  ：$table(string),$field(string),$wh(string)
    //      戻り値：array(sqlErrCD,sqlErrMsg,recCnt,data())
    ----------------------------------------------------------*/
    public function selectTB($table,$field,$wh="")
    {
        try{
            $ret = array();
            $ret['sqlErrCD'] =  0;
            $ret['sqlErrMsg'] = '';
            $ret['recCnt'] = 0;

            $sql = ' select '.$field.' from '.$table;
            if (!empty($wh)) { $sql .= $wh; }
            $result = sqlsrv_query( $this->con, $sql );
        	if(!$result){
                if(($errors = sqlsrv_errors()) != null){
                     $ret['sqlErrCD'] =  $errors[0]['code'];
                     $ret['sqlErrMsg'] = $errors[0]['message'];
                }
        		return $ret;

        	}

            $i = 0;
            while($row = sqlsrv_fetch_array($result)) {
                foreach ($row as $key => $value) {
                    if (!is_numeric($value)) { 
                        //数値化できないものはutf8に変換して値を返す
                        $value = (!empty($value)) ? mb_convert_encoding($value, "utf8", "SJIS") : $value;
                    }
                    $ret['data'][$i][$key] = $value;
                }
                $i = $i + 1;
            }
            $ret['recCnt'] =  $i;

        }catch (Exception $e)  {
            $ret['sqlErrCD'] =  99999;
            $ret['sqlErrMsg'] =  $e->getMessage();
        }

        return $ret;
 
    }


    /*--------------------------------------------------------
    //  コンボやリスト用:（文字項目はutf8に変換した後）配列で渡す。
    ----------------------------------------------------------*/
    public function listTB($table,$idNm,$valNm,$wh="")
    {
        try{
            $ret = array();
            $ret['sqlErrCD'] =  0;
            $ret['sqlErrMsg'] = '';

            $sql = ' select * from '.$table;
            if (!empty($wh)) { $sql .= $wh; }
            $result = sqlsrv_query( $this->con, $sql );
            $i = 0;
            while($row = sqlsrv_fetch_array($result)) {
                $key = $row[$idNm];
                $value = $row[$valNm];

                if (!is_numeric($value)) { 
                        //数値化できないものはutf8に変換して値を返す
                        $value = (!empty($value)) ? mb_convert_encoding($value, "utf8", "SJIS") : $value;
                }
                //2015.06.01 y.kamijo mod
                //$ret['data'][$key] = $value;
                $ret['data'][$i]['key'] = $key;
                $ret['data'][$i]['value'] = $value;
                $i++;
                //2015.06.01 y.kamijo mod
            }

        }catch (Exception $e)  {
            $ret['sqlErrCD'] =  99999;
            $ret['sqlErrMsg'] =  $e->getMessage();
        }

        return $ret;

    }

    /*--------------------------------------------------------
    //  コンボやリスト用:（文字項目はutf8に変換した後）配列で渡す。
    ----------------------------------------------------------*/
    public function select_rmcls( $webflg )
    {
        
        $ret = array();

        $ret['sqlErrCD'] =  0;
        $ret['sqlErrMsg'] = '';

        $sql = ' select * from mm_rmcls';

        if($webflg){
            $sql = $sql.' where fild1 = 1';//web対象のもののみ抽出
        }

        $result = sqlsrv_query( $this->con, $sql );

        if( $result === false ) {
            $ret['sqlErrCD'] =  99999;
            //$ret['sqlErrMsg'] =  $e->getMessage();
            return $ret;        
        }

        $i = 0;

        while($row = sqlsrv_fetch_array($result)) {
            
            $value = $row['name'];

            if (!is_numeric($value)) { 
                //数値化できないものはutf8に変換して値を返す
                $value = (!empty($value)) ? mb_convert_encoding($value, "utf8", "SJIS") : $value;
            }
     
            $ret['data'][$i]['key'] = $row['code'];
            $ret['data'][$i]['value'] = $value;
            $i++;
        }

        return $ret;

    }

    /*--------------------------------------------------------
    //  配列用:（文字項目はutf8に変換した後）配列で渡す。y.kamijo
    ----------------------------------------------------------*/
    public function select_mroom( $wh )
    {
        //try{

            $ret = array();
            $ret['sqlErrCD'] =  0;
            $ret['sqlErrMsg'] = '';
            
            //$column = "";
            $sql = 'select rmcd , rmnm , capacity from mt_room order by rmcd';

            if (!empty($wh)) { $sql .= $wh; }
            
			$result = sqlsrv_query( $this->con, $sql );
            
			if( $result === false ) {
				echo $sql;
				die( print_r( sqlsrv_errors(), true));
			}

            $i = 0;

            while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_NUMERIC ) ) {

                for( $j = 0; $j < count( $row ); $j++ ){

                    $value = $row[$j];

                    if (!is_numeric($value)) { 
                        //数値化できないものはutf8に変換して値を返す
						$value = ( !empty($value) ) ? mb_convert_encoding( $value, "utf8", "SJIS" ) : $value;
                    }
                    
                    $ret['data'][$i][$j] = $value;

                }

                $i++;
            }

        //}catch (Exception $e)  {
            //$ret['sqlErrCD'] =  99999;
            //$ret['sqlErrMsg'] =  $e->getMessage();
        //}

        return $ret;

    }
    
    /*--------------------------------------------------------
    //  配列用:（文字項目はutf8に変換した後）配列で渡す。y.kamijo
    ----------------------------------------------------------*/
    public function select_ksjknksi( $rmcd , $timekb , $sttdt , $enddt )
    {
        try{

            $ret = array();
            $ret['sqlErrCD'] =  0;
            $ret['sqlErrMsg'] = '';
            //echo "b";
            for( $k = 0 ; $k < 14 ; $k++ ){
                $date = $sttdt + $k;
                //echo $date;
                //echo "<br>";
                $ret['data'][$date] = 0;
            }
            
            //$sql = " select usedt , ukeno from ks_jkntai ";
            $sql = " select distinct usedt from ks_jknksi ";
            
            if($timekb==1){
                //$timestr =" timekb in(1,4,6) ";
                $timestr =" jikan in(9,10,11) ";
            }
            if($timekb==2){
                //$timestr =" timekb in(2,4,5,6) ";
                $timestr =" jikan in(13,14,15,16) ";
            }
            if($timekb==3){
                //$timestr =" timekb in(3,5,6) ";
                $timestr =" jikan in(18,19,20) ";
            }

            //$sql .= " WHERE rmcd = ".$rmcd." AND ". $timestr." AND usedt >= ".$sttdt." AND usedt <= ".$enddt." AND ukeno <> 0";
            $sql .= " WHERE rmcd = ".$rmcd." AND ". $timestr." AND usedt >= ".$sttdt." AND usedt <= ".$enddt." AND rjyokb <> 0";
            //$sql .= " WHERE rmcd = ".$rmcd." AND timekb = ".$timekb." AND usedt >= ".$sttdt." AND usedt <= ".$enddt." AND ukeno <> 0";
            $sql .= " order by usedt ";
            
            $result = sqlsrv_query( $this->con, $sql );

			if( $result === false ) {
				echo $sql;
				die( print_r( sqlsrv_errors(), true));
			}

			$i = 0;
            
            while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC)) {

                $usedt  = $row[0];
                //$value  = $row[1];

                //if (!is_numeric($value)) { 
                    //数値化できないものはutf8に変換して値を返す
				//	$value = 0;
                //}
                    
                $ret['data'][$usedt] = 1;//$value;                    
                $i++;
            }

        }catch (Exception $e)  {
            $ret['sqlErrCD'] =  99999;
            $ret['sqlErrMsg'] =  $e->getMessage();
        }

        return $ret;

    }
    
	
	/*--------------------------------------------------------
    //  配列用:（文字項目はutf8に変換した後）配列で渡す。y.kamijo
    ----------------------------------------------------------*/
    public function select_ksjkntai2( $room_array, $date_array )
    {
         
		$ret = array();
		//$rmcd = null;
		//$usedt = null;
		//$timekb = null;

		$sql = " SELECT DISTINCT rmcd , usedt, timekb , ukeno FROM web_ksjkntai ";
		//$sql .= " WHERE rmcd = ".$room_array[ $i ]." AND usedt = ".$date_array[ $j ]['yyyy'].$date_array[ $j ]['mm'].$date_array[ $j ]['dd']." AND timekb = ".$k." AND ukeno <> 0";
		
		$sql .= " WHERE rmcd IN  ( ";
			
		for( $i = 0 ; $i < count( $room_array ); $i++ ){//施設コードの連結
			
			if ( $i  != 0 ) {
				$sql .= ",";
			}
			
			$sql .= $room_array[ $i ];

		}

		$sql .=  " )";
		$sql .=  "AND usedt IN ( ";

		for( $j = 0 ; $j < count( $date_array ); $j++ ){//日付条件の連結
			
			if ( $j  != 0 ) {
				$sql .= ",";
			}
			
			$sql .= $date_array[ $j ][ 'yyyy' ].$date_array[ $j ][ 'mm' ].$date_array[ $j ][ 'dd' ];
			
		}

		$sql .=  " )";
		$sql .=  " AND ukeno <> 0";
		$sql .=  " ORDER BY rmcd, usedt, timekb";
		
		$result = sqlsrv_query( $this->con, $sql );
		
		if( $result === false ) {
			echo $sql;
			die( print_r( sqlsrv_errors(), true));
		}

		while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
			//print_r($row);
			array_push( $ret, $row );
			echo "<br>";
			//$ret($row['rmcd'] => $row['rmcd'] =>$row['timekb']); 
		}
	
		return $ret;

	}
	

    public function select_kscal( $usedt , $timekb )
    {
        
        $sql = "select eigkb from ks_cal where stdt = ".$usedt;
        
        $result = sqlsrv_query( $this->con, $sql );

        if( $result === false ) {
            echo $sql;
            die( print_r( sqlsrv_errors(), true));
        }
        
        $ret = false;

        while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_NUMERIC)) {

            $eigkb = $row[0];
        
            if($eigkb==0){
                
                $ret = true; 
            
            }else{

                //朝
                if( $timekb == 1 ){
                    //昼休、夜休はOK
                    if( $eigkb ==2 || $eigkb == 3 ){
                         $ret = true;
                    }
                
                }

                //昼
                if( $timekb == 2 ){
                    //朝休、夜休はOK
                    if( $eigkb == 1 || $eigkb == 3 ){
                         $ret = true;
                    }
                
                }

                //夜
                if( $timekb == 3 ){
                    //朝休、昼休はOK
                    if( $eigkb == 1 || $eigkb == 2 ){
                         $ret = true;
                    }
                
                }

            }

        }
     
        return $ret;

    }

	
	/*--------------------------------------------------------
    //  updateする。
    //
    //      引数  ：$table(string),$record(array),$wh(string)
    //      戻り値：array(sqlErrCD,sqlErrMsg,rcd)
    ----------------------------------------------------------*/
    public function updateTB($table,$record,$wh)
    {
        if (empty($table)) { return;}
        if (empty($record)) { return;}

        try{
            $ret = array();
            $ret['sqlErrCD'] =  0;
            $ret['sqlErrMsg'] = '';
            $ret['rcd'] = 0;
            

            $setParam = '';
            foreach ($record as $key => $value) {
               if (!is_numeric($value)) { 
                  //数値化できないものはsjisに変換して値を返す
                  $value = (!empty($value)) ? "'".mb_convert_encoding($value, "SJIS", "utf8")."'" : $value;
                }
                $setParam .= $key.' = '.$value.",";
            }

            $setParam = substr($setParam, 0, -1);
            $sql = ' update '.$table.' set '.$setParam.'  ';
            if (!empty($wh)) { $sql .= $wh; }
            // トランザクションの開始
            if ( !sqlsrv_begin_transaction($this->con) ) {
                 //トランザクション開始できなかった
                return $ret;
            }

            if( sqlsrv_query( $this->con, $sql) ) {
            	$ret['rcd'] = '1';
                $ret['sqlErrCd'] = 0;
                $ret['sqlErrMsg'] ='';
                sqlsrv_commit($this->con);
            } else {
                if(($errors = sqlsrv_errors()) != null){
                     $ret['sqlErrCD'] =  $errors[0]['code'];
                     $ret['sqlErrMsg'] = $errors[0]['message'];
                }
                sqlsrv_rollback($this->con);
            }

        }catch (Exception $e)  {
                $ret['sqlErrCD'] =  99999;
                $ret['sqlErrMsg'] =  $e->getMessage();
        }

        return $ret;

    }


    /*--------------------------------------------------------
    //  insertする。
    //
    //      引数  ：$table(string),$record(array),$wh(string)
    //      戻り値：array(sqlErrCD,sqlErrMsg,rcd)
    ----------------------------------------------------------*/
    public function insertTB($table,$record)
    {
        if (empty($table)) { return;}
        if (empty($record)) { return;}
 
        try{
            $ret = array();
            $ret['sqlErrCD'] =  0;
            $ret['sqlErrMsg'] = '';
            $ret['rcd'] = 0;
            
            $colStr = '';
            $valStr = '';
            foreach ($record as $key => $value) {
               if (!is_numeric($value)) { 
                  //数値化できないものはsjisに変換して値を返す
                  $value = (!empty($value)) ? "'".mb_convert_encoding($value, "SJIS", "utf8")."'" : $value;
                }
                $colStr .= $key.",";
                $valStr .= $value.",";
            }
            $colStr = substr($colStr, 0, -1);
            $valStr = substr($valStr, 0, -1);

            $sql="insert into ".$table." (".$colStr.") values (".$valStr.")";

            // トランザクションの開始
            if ( !sqlsrv_begin_transaction($this->con) ) {
                 //トランザクション開始できなかった
                return $ret;
            }

            if( sqlsrv_query( $this->con, $sql) ) {
            	$ret['rcd'] = '1';
                $ret['sqlErrCd'] = 0;
                $ret['sqlErrMsg'] ='';
                sqlsrv_commit($this->con);
            } else {
                if(($errors = sqlsrv_errors()) != null){
                     $ret['sqlErrCD'] =  $errors[0]['code'];
                     $ret['sqlErrMsg'] = $errors[0]['message'];
                }
                sqlsrv_rollback($this->con);
            }

        }catch (Exception $e)  {
                $ret['sqlErrCD'] =  99999;
                $ret['sqlErrMsg'] =  $e->getMessage();
        }

        return $ret;

    }


    /*--------------------------------------------------------
    //  deleteする。
    //
    //      引数  ：$table(string),$wh(string)
    //      戻り値：array(sqlErrCD,sqlErrMsg,rcd)
    ----------------------------------------------------------*/
    public function deleteTB($table,$wh)
    {
        if (empty($table)) { return;}

        try{
            $ret = array();
            $ret['sqlErrCD'] =  0;
            $ret['sqlErrMsg'] = '';
            $ret['rcd'] = 0;

            $sql="delete from ".$table." ";
            if (!empty($wh)) { $sql .= $wh; }

            // トランザクションの開始
            if ( !sqlsrv_begin_transaction($this->con) ) {
                 //トランザクション開始できなかった
                return $ret;
            }

            if( sqlsrv_query( $this->con, $sql) ) {
            	$ret['rcd'] = '1';
                $ret['sqlErrCd'] = 0;
                $ret['sqlErrMsg'] ='';
                sqlsrv_commit($this->con);
            } else {
                if(($errors = sqlsrv_errors()) != null){
                     $ret['sqlErrCD'] =  $errors[0]['code'];
                     $ret['sqlErrMsg'] = $errors[0]['message'];
                }
                sqlsrv_rollback($this->con);
            }

        }catch (Exception $e)  {
            $ret['sqlErrCD'] =  99999;
            $ret['sqlErrMsg'] =  $e->getMessage();
        }

        return $ret;
    }

    public function close()
    {
        sqlsrv_close($this->con);
    }
	
	/*--------------------------------------------------------
    //  配列用:（文字項目はutf8に変換した後）配列で渡す。y.kamijo
    ----------------------------------------------------------*/
    public function select_rsvlist( $kyakcd )
    {

            $ret = array();

            $sql = " select dt_roomrmei.* , dt_roomr.kaigi, dt_roomr.ukedt, dt_roomr.paylmtdt, dt_roomr.kounoukb, dt_roomr.expkb,";
            $sql .= " dt_wbseikyu.seino, dt_wbseikyu.seiurl, dt_wbseikyu.seifile, dt_wbseikyu.seideal, dt_wbseikyu.seifbd, ";
            $sql .= " mt_room.rmnm, mt_room.oyakokb from dt_roomrmei ";
            $sql .= " left outer join dt_roomr on dt_roomrmei.ukeno = dt_roomr.ukeno ";
            $sql .= " left outer join dt_wbseikyu on dt_roomrmei.ukeno = dt_wbseikyu.ukeno ";
            $sql .= " left outer join mt_room on dt_roomrmei.rmcd = mt_room.rmcd ";
            $sql .= " WHERE dt_roomr.kyacd = ".$kyakcd;
			$sql .= " order by dt_roomrmei.ukeno , dt_roomrmei.gyo ";

			$result = sqlsrv_query( $this->con, $sql );

            $i = 0;

            while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

                $ret[$i] = $row;                    
                $i++;

            }

       
        return $ret;

    }

	/*--------------------------------------------------------
    //  配列用:（文字項目はutf8に変換した後）配列で渡す。y.kamijo
    ----------------------------------------------------------*/
    public function select_nyukin_status( $ukeno )
    {
            $sql = " select count(*) as count from  dt_nyukn ";
            $sql .= " WHERE nykin <> 0 and  ukeno = ".$ukeno;
			
			$result = sqlsrv_query( $this->con, $sql );

			if( $result === false ) {
				 die( print_r( sqlsrv_errors(), true));
			}

			while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {

				if ( $row['count'] > 0 ){	
						return true;
				}

			}

        return false;  
    }
	
	/*---------------------------------------------------------
	//条件に合致した施設を抽出する
	//$bldkb　施設区分
	//$rmclkb 施設形式（配列）
	//sampleArray
	//Array ( [11] => Array ( [rmnmw] => 体育館全面 [capacity] => 0 ) 
	//[21] => Array ( [rmnmw] => 大ホール [capacity] => 500 ) 
	//[111] => Array ( [rmnmw] => 多目的ホール [capacity] => 120 )
	//[121] => Array ( [rmnmw] => トレーニング室 [capacity] => 0 ) ) 
	-----------------------------------------------------------*/
	//public function get_web_mroomr( $bldkb, $rmclkb )
	public function get_web_mroomr( $rmclkb ,$webflg )
	{
	
		$ret = array();
		//施設コード、WEB名称、定員、WEBリンク
		$sql = "SELECT mt_room.rmcd, mt_room.rmnmw, mt_room.capacity, mt_room.oyakokb, mt_room.sumrmcd, mt_room.weblink2 AS weblink, "; 
        $sql = $sql." asa.tnk AS asatnk, hiru.tnk AS hirutnk , yoru.tnk AS yorutnk FROM mt_room "; 
        $sql = $sql." LEFT OUTER JOIN mt_rmtnk as asa on mt_room.rmcd = asa.rmcd "; 
        $sql = $sql." LEFT OUTER JOIN mt_rmtnk as hiru on mt_room.rmcd = hiru.rmcd ";
        $sql = $sql." LEFT OUTER JOIN mt_rmtnk as yoru on mt_room.rmcd = yoru.rmcd "; 
        
		if ( count ( $rmclkb )  > 0 ){		
			
			$sql = $sql." where ( mt_room.rmclkb = ".$rmclkb[0];

			for ( $i = 1;  $i < count ( $rmclkb );  $i++ ) {
				$sql = $sql." or  mt_room.rmclkb = ".$rmclkb[$i];
			}

			$sql = $sql." )";

		}

		if($webflg){
            $sql = $sql." and mt_room.weboutkb = 1 "; 
        }

        $sql = $sql." and asa.kyakb = 1 "; 
        $sql = $sql." and asa.stjkn = 900 and asa.edjkn = 1200 ";
        $sql = $sql." and hiru.kyakb = 1 "; 
        $sql = $sql." and hiru.stjkn = 1300 and hiru.edjkn = 1700 ";
        $sql = $sql." and yoru.kyakb = 1 "; 
        $sql = $sql." and yoru.stjkn = 1800 and yoru.edjkn = 2100 ";

		$sql = $sql." order by mt_room.rmcd";

		$result = sqlsrv_query( $this->con, $sql );

		if( $result === false ) {
			 return false;
			 //die( print_r( sqlsrv_errors(), true));
		}

		while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {				
			array_push( $ret, $row );
		}

		return $ret;

	}

	/* 利用目的の取得 */
	public function get_mm_riyo($code)
    {

        $ret = array();

        $sql = "select code , name from mm_riyo";
        $sql = $sql." where code = ".$code;

        $result = sqlsrv_query( $this->con, $sql );

        if( $result === false ) {
             //echo $sql;
             die( print_r( sqlsrv_errors(), true));
        }

        while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_ASSOC) ) {          
            array_push( $ret, $row );
        }

        return $ret;

    }

	//いきいき仕様
	public function check_monthly_count( $kyakucd , $year , $month ){
	
		$ret = 0;
		
		if( empty ( $kyakucd ) ){
			return $ret;	
		}
		
		$nextmonth = sprintf('%02d', $month + 1);
		$month = sprintf('%02d', $month);
		
		$sql = "select count(*) from dt_roomrmei left outer join dt_roomr on dt_roomr.ukeno = dt_roomrmei.ukeno";
		$sql = $sql." where candt = 0 and dt_roomr.kyacd = ".$kyakucd;
		$sql = $sql." and dt_roomrmei.usedt > ".$year.$month."00" ." and dt_roomrmei.usedt < ".$year.$nextmonth."00";
//echo $sql;
//echo "<br>";
		$result = sqlsrv_query( $this->con, $sql );

		if( $result === false ) {
			 //echo $sql;
			die( print_r( sqlsrv_errors(), true));
		}

		while( $row = sqlsrv_fetch_array( $result, SQLSRV_FETCH_NUMERIC) ) {
			$ret = 	$row[0];
		}

		return $ret;
	
	}
	
}
?>