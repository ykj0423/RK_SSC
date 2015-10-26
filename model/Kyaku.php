<?php
require_once("ModelBase.php");
class Kyaku extends ModelBase {
	/*--------------------
    // ログイン処理
    ---------------------*/
    // プロパティの宣言
    //public $conn;
    var $data;
    /*--------------------
    // ログイン処理
    ---------------------*/
    function __construct() {
        
        parent::__construct();

        $this->data = array();    
        
        if( $this->conn === false ) {
             //TODO
            //echo "err";
        }else{
            //echo "suc";
        }

        $this->data = array();    
        
    }

    public function put_data( $key_name , $require , $beZero　) {
        
        if ( array_key_exists( $key_name, $data ) ) {
            //echo "この配列には 'first' という要素が存在します";
            //見直しが必要かもしれない
            return true;
        }else{

            if( $require ){

                return false;

            }else{

                if( $beZero ) {
                    $this->data[$key_name] = 0;
                }else{
                    $this->data[$key_name] = '';
                }

                return true;
            }     

        }

    }

    /*--------------------
    // ログイン処理
    ---------------------*/
    public function login($wloginid , $wpwd) {
        
        $get_pwd="";

        $sql = "SELECT * FROM mt_kyaku WHERE wloginid =  '". $wloginid."'";

        $stmt = sqlsrv_query( $this->conn, $sql );
        
        if( $stmt === false) {
            return false;//TODO
        }

        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
            $get_pwd = trim( $row['wpwd'] );
        }
 
        if($get_pwd == $wpwd){
            return true;
        }else{
            return false;
        }

    }
    
    /*--------------------
    // メールアドレス取得処理
    ---------------------*/
    public function get_mail_adress( $wloginid ) {
        
        $mail_adress = "";
        
        $sql = "select mail from mt_kyaku where wloginid ='".$wloginid."'";

        $stmt = sqlsrv_query( $this->conn, $sql );
        
        if( $stmt === false) {
            return false;//TODO
        }

        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
            $mail_adress = trim( $row['mail'] );
        }

        return $mail_adress;

    }

	/*--------------------
    // メールアドレス変更処理
    ---------------------*/
    public function change_mail_adress($wloginid , $mail_adress) {
        
        $sql = "update mt_kyaku set mail = '".$mail_adress."' where wloginid ='".$wloginid."'";

        $stmt = sqlsrv_query( $this->conn, $sql );
        
        if( $stmt === false) {
            echo $sql;
            echo "stmterr";
            return false;
        }

        return true;

    }

    public function add_kyaku( $arr ) {
        //TODO
        $kyaku_cd = "";

        $sql = "select max(kyacd) from mt_kyaku ";

        $stmt = sqlsrv_query( $this->conn, $sql );
        
        if( $stmt === false) {
                        echo $sql;
            echo "stmterr";
            return false;
        }

        //顧客番号採番　//TODOデッドロック
        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
              $kyaku_cd = intval($row[0])+1;
        }

        $sql = "insert into mt_kyaku ( kyacd, dannm, dannm2, dannmk, daihyo, renraku, tel1, tel2,
            fax, url, mail, zipcd, adr1, adr2, gyscd, sihon, jygsu, kyakb, biko, login, udate, utime, wloginid, wpwd )";
        $sql .= " values  ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,? )";

        $params = array( $kyaku_cd, 
            parent::convertToSJIS( $arr['dannm'] ), "''", 
            parent::convertToSJIS( $arr['dannmk'] ), 
            parent::convertToSJIS( $arr['daihyo'] ), 
            parent::convertToSJIS( $arr['renraku'] ),
            $arr['tel1'], $arr['tel2'], $arr['fax'], "''", $arr['mail'], $arr['zipcd'],
            parent::convertToSJIS( $arr['adr1'] ), 
            parent::convertToSJIS( $arr['adr2'] ), 
            parent::convertToZero( $arr['gyscd'] ),
            parent::convertToZero( $arr['sihon'] ), 
            parent::convertToZero( $arr['jygsu'] ), 
            parent::convertToZero( $arr['kyakb'] ), 
            "''", $arr['login'], parent::getUdate(), parent::getUtime(), $arr['wloginid'], $arr['wpwd']);
        
        $stmt = sqlsrv_query( $this->conn, $sql, $params);

        if( $stmt === false) {
            echo $sql;
            print_r( sqlsrv_errors(), true) ;
            return false;
        }

        return true;

    }

    function get_user_info( $wloginid ) 
	{
	 
		$serverName = "WEBRK\SQLEXPRESS";
        $connectionInfo = array( "Database"=>"RK_SSC_DB", "UID"=>"sa", "PWD"=>"Webrk_2015" );

		$conn = sqlsrv_connect( $serverName, $connectionInfo);

		if( $conn === false ) {
			 //TODO
            die( print_r( sqlsrv_errors(), true));
		}

		/* --------------------*/
		/*  顧客情報取得処理  */
		/* --------------------*/
		$sql = "SELECT * FROM mt_kyaku WHERE wloginid =  ". $wloginid;

		$stmt = sqlsrv_query( $conn, $sql );
		
		if( $stmt === false) {
			ie( print_r( sqlsrv_errors(), true) );
		}

		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
			/* セッションに入れるのがあまりいい形ではないがとりあえず */
			$_SESSION[ 'webrk' ][ 'kyacd' ] = $row[ 'kyacd' ];
			$_SESSION[ 'webrk' ][ 'dannm' ] = $row[ 'dannm' ];
			//$_SESSION[ 'webrk' ][ 'dannm2' ] = $row[ 'dannm2' ];
			$_SESSION[ 'webrk' ][ 'wpwd' ] = $row[ 'wpwd' ];
			$_SESSION[ 'webrk' ][ 'mail' ] = $row[ 'mail' ];
			$_SESSION[ 'webrk' ][ 'wlastlogindt' ] = $row[ 'wlastlogindt' ];
			$_SESSION[ 'webrk' ][ 'wuserupd' ] = $row[ 'lastlogin' ];
		}
		
	 }
    

	

    /*--------------------
    // ユーザ情報の変更
    ---------------------*/
    function Edit($mode){

        // ---- 送信情報の入力情報チェック ----------------
        $mail = $_POST['mail'];
        $remail = $_POST['remail'];
        $passnew = $_POST['passnew'];
        $passnew2 = $_POST['passnew2'];

        $rtnAry = array();
        $rtnAry['ErrCd'] = '0';
        $rtnAry['ErrMsg'] = '';
        $rtnAry['step'] = 'input';
        $rtnAry['mail'] = $mail;
        $rtnAry['remail'] = $remail;
        $rtnAry['passnew'] = $passnew;
        $rtnAry['passnew2'] = $passnew2;

        if ($mode == 'first'){
            $this->check('passnew',$passnew,1,'all-hankaku',10);
            $this->check('passnew2',$passnew2 ,1,'all-hankaku',10);
            $this->check('mail',$mail ,0,'mail',60);
            $this->check('remail',$mail,0,'mail',10);

        }
        if (empty($this->err)){
            if ($passnew != $passnew2){
                $rtnAry['ErrCd']  = '11';
                $rtnAry['ErrMsg']  = '入力されたパスワードと確認用のパスワードが一致しません';
            }else if ($pwd != $_SESSION['webrk']['user']['pwd']){
                $rtnAry['ErrCd']  = '12';
                $rtnAry['ErrMsg']  = '現在のパスワードが一致しません';
            }
        }else{
            $rtnAry['ErrCd']  = '10';
            $rtnAry['ErrMsg']  = $this->err['paramErrMsg'][0];
        }  

        // ---- DB更新 ----------------     
        if (empty($_POST['regist'])){
            //確認
            $rtnAry['step'] = 'confirm';
            
        }else{
            //送信
            $record = array();
            $table = ' web_mkyaku ';
            $record['mail'] = $_POST['mail'];
            $record['pwd'] = $_POST['passnew'];
            $wh = ' where kyacd = '.$_SESSION['webrk']['user']['kyacd'];

            $ret=$this->updateTB($table, $record, $wh);
            if (empty($ret['rcd'])) { 
                    $rtnAry['ErrCd'] = $ret['sqlErrCD'];
                    $rtnAry['ErrMsg'] =  '更新に失敗しました：'.$ret['sqlErrCD'].':'.$ret['sqlErrMsg'];
            }
            $rtnAry['step'] = 'finish';

        }

        return $rtnAry;
    }

	public function change_password( $kyacd, $passold, $passnew ){
		
		$serverName = "ITWEB1";
		$connectionInfo = array( "Database"=>"RK_KIK_DB1", "UID"=>"sa", "PWD"=>"" );
		$conn = sqlsrv_connect( $serverName, $connectionInfo);

		if( $conn === false ) {
			 die( print_r( sqlsrv_errors(), true));
		}

		
		$sql = "SELECT pwd FROM web_mkyaku where kyacd = ".$kyacd;
		
		$stmt = sqlsrv_query( $conn, $sql );
		
		if( $stmt === false) {
			die( print_r( sqlsrv_errors(), true) );
		}
		
		$pass_db="";

		while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_NUMERIC) ) {
			  $pass_db = $row[0];
		}
		
		sqlsrv_free_stmt( $stmt);
		
		if( $pass_db == $passold ){
		}else{
			
			echo "パスワードが一致しません。";

		}
		
		return false;


		$sql = "update web_mkyaku set pwd='".$passnew."' where kyacd = ".$kyacd;
		$params = array($pass);
		echo $sql;
		print_r($params);
		sqlsrv_query( $conn, $sql );//, $params);

		if( $stmt === false ) {
			die( print_r( sqlsrv_errors(), true));
		}
	
		return true;
	}


}

?>
