jQuery(function () {
			
		$("#naiyo").attr("disabled","disabled");
		$("#naiyo").val='';
		
		$('#riyokb').change(function() {
			if($(this).val()==99){
				$("#naiyo").removeAttr("disabled");
				
			}else{
				$("#naiyo").attr("disabled","disabled");
				$("#naiyo").val='';
			}
		});

        //HTMLを初期化 
        $("table.rsv_input tbody.list").html("");
        
		var objData = JSON.parse(localStorage.getItem("sentaku"));
        if (objData==null){
			return false;
		}
		
		for ( var i = 0; i < objData.length; i++ ){
            
			var tr = $("<tr></tr>");
            var td1 = $("<td></td>");
            var td2 = $("<td></td>");
            var td3 = $("<td></td>");
            var td4 = $("<td></td>");
            var td5 = $("<td></td>");
			var td6 = $("<td></td>");
			var td7 = $("<div></div>");
			var td8 = $("<div></div>");
			var td9 = $("<div></div>");
			var td10 = $("<div></div>");
			//var td11 = $("<td></td>");
			
			/* 日付のフォーマット もう少しスマートな方法がないか検討*/
			var usedt = objData[i]['usedt'];
			var useyyyy = usedt.substring(0, 4);
			var usemm = objData[i]['usedt'].substring(4, 6);
			var usedd = usedt.substring(6, 8);
			
			var d = new Date(useyyyy + "/" + usemm + "/" +  usedd);
			var w = ["（日）","（月）","（火）","（水）","（木）","（金）","（土）"];
			var yobi = w[d.getDay()];
			var gyo = i + 1;

			
			/* 命名を配列っぽくしてもいいかもしれない */
			/* 後で変更 jkn1 jkn2 →　stjkn　edjkn */
			$("#list").append(tr);
			tr.append( td1 ).append( td2 ).append( td3 ).append( td4 ).append( td5 ).append( td6 ).append( td7 ).append( td8 ).append( td9 ).append( td10 );//.append( td11 );
			//td1.html( gyo );
			td1.html( "<input type=\"button\" class=\"btn btn-default btn-del\" id='btn-" + objData[i]['rmcd'] + objData[i]['usedt'] + objData[i]['timekb'] + "' name='" + i + "' role=\"button\" value=\"削除\">" );
			td2.html( useyyyy + "/" + usemm + "/" +  usedd  + yobi + "<br>" + objData[i]['rmnm'] );
			td3.html( objData[i]['jkn1'] + "～" + objData[i]['jkn2'] );
			//td4.html( "時間内訳" );
			var jjkn ="<table class=\"table table-condensed  form-inline nest mb0\"><tr><th>練習・準備時間</th><td>"
           		+ "<input type=\"text\" class=\"form-control\" name='jnstjkn_h" + i + "' id='jnstjkn_h" + i + "' value=\"" + objData[i]['jstjkn_h'] + "\" style=\"width:30px; ime-mode: disabled\" maxlength=\"2\" >時"
               	+ "<input type=\"text\" class=\"form-control\" name='jnstjkn_m" + i + "' id='jnstjkn_m" + i + "' value=\"" + objData[i]['jstjkn_m'] + "\" style=\"width:30px; ime-mode: disabled\"  maxlength=\"2\" >分～"
               	+ "<input type=\"text\" class=\"form-control\" name='jnedjkn_h" + i + "' id='jnedjkn_h" + i + "' value=\"" + objData[i]['jedjkn_h'] + "\" style=\"width:30px; ime-mode: disabled\" maxlength=\"2\" >時"
               	+ "<input type=\"text\" class=\"form-control\" name='jnedjkn_m" + i + "' id='jnedjkn_m" + i + "' value=\"" + objData[i]['jedjkn_m'] + "\" style=\"width:30px; ime-mode: disabled\"  maxlength=\"2\" >分"
               	+ "</td></tr></table>";
            var hjkn = "<table class=\"table table-condensed  form-inline nest mb0\"><tr><th>催物時間</th><td>"
	            + "<input type=\"text\" class=\"form-control\" name='hstjkn_h" + i + "' id='hstjkn_h" + i + "' value=\"" + objData[i]['hstjkn_h'] + "\" style=\"width:30px; ime-mode: disabled\" maxlength=\"2\" >時"
	            + "<input type=\"text\" class=\"form-control\" name='hstjkn_m" + i + "' id='hstjkn_m" + i + "' value=\"" + objData[i]['hstjkn_m'] + "\" style=\"width:30px; ime-mode: disabled\"  maxlength=\"2\" >分～"
	            + "<input type=\"text\" class=\"form-control\" name='hedjkn_h" + i + "' id='hedjkn_h" + i + "' value=\"" + objData[i]['hedjkn_h'] + "\" style=\"width:30px; ime-mode: disabled\" maxlength=\"2\" >時"
	            + "<input type=\"text\" class=\"form-control\" name='hedjkn_m" + i + "' id='hedjkn_m" + i + "' value=\"" + objData[i]['hedjkn_m'] + "\" style=\"width:30px; ime-mode: disabled\"  maxlength=\"2\" >分"
	            + "</td></tr></table>";
	        var tjkn = "<table class=\"table table-condensed  form-inline nest mb0\"><tr><th>撤去時間</th><td>"
	            + "<input type=\"text\" class=\"form-control\" name='tkstjkn_h" + i + "' id='tkstjkn_h" + i + "' value=\"" + objData[i]['tstjkn_h'] + "\" style=\"width:30px; ime-mode: disabled\" maxlength=\"2\" >時"
	            + "<input type=\"text\" class=\"form-control\" name='tkstjkn_m" + i + "' id='tkstjkn_m" + i + "' value=\"" + objData[i]['tstjkn_m'] + "\" style=\"width:30px; ime-mode: disabled\"  maxlength=\"2\" >分～"
	            + "<input type=\"text\" class=\"form-control\" name='tkedjkn_h" + i + "' id='tkedjkn_h" + i + "' value=\"" + objData[i]['tedjkn_h'] + "\" style=\"width:30px; ime-mode: disabled\" maxlength=\"2\" >時"
	            + "<input type=\"text\" class=\"form-control\" name='tkedjkn_m" + i + "' id='tkedjkn_m" + i + "' value=\"" + objData[i]['tedjkn_m'] + "\" style=\"width:30px; ime-mode: disabled\"  maxlength=\"2\" >分"
	            + "</td></tr></table>";
			if( ( objData[i]['rmcd'] == "201" ) || ( objData[i]['rmcd'] == "301" ) ){
				td4.html( jjkn + hjkn + tjkn );	
			}else{				
				td4.html( objData[i]['jkn1'] + "～" + objData[i]['jkn2'] );
			}
			
			td5.html( "<input type='text' class='form-control' name='ninzu" + i + "' id='ninzu" + i + "' value='" + objData[i]['ninzu'] + "' style='width:50px'>人" );
			//td6.html( "営利目的での利用" + "入場料・受講料等の徴収" );			
			
			var piano = "";
			
			if(　objData[i]['rmcd']　==　"301"　){
				
				piano = "<tr><th>グランドピアノ</th><td>";
			
				if( objData[i]['piano'] == 0 ){
			    	piano = piano   + "<label class=\"radio-inline\"><input type=\"radio\" name=\"piano" + i + "\" id=\"piano" + i + "\" value=\"1\">使用する</label>"
			            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"piano" + i + "\" id=\"piano" + i + "\" value=\"0\" checked>使用しない</label>";
				}else if( objData[i]['piano'] == 1 ){
			    	piano = piano + "<label class=\"radio-inline\"><input type=\"radio\" name=\"piano" + i + "\" id=\"piano" + i + "\" value=\"1\" checked>使用する</label>"
			            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"piano" + i + "\" id=\"piano" + i + "\" value=\"0\">使用しない</label>";
				}else{
					piano = piano + "<label class=\"radio-inline\"><input type=\"radio\" name=\"piano" + i + "\" id=\"piano" + i + "\" value=\"1\">使用する</label>"
			            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"piano" + i + "\" id=\"piano" + i + "\" value=\"0\">使用しない</label>";
				}
	        
		        piano = piano + "</td></tr>";
		
			}
		    
		    var comlkb = "<tr><th>営利目的での利用<br>（販売やPR活動も含む）</th><td>";
		    
		    if( objData[i]['comlkb'] == 0 ){
	        	comlkb = comlkb + "<label class=\"\"><input type=\"radio\" name=\"comlkb" + i + "\" id=\"comlkb" + i + "\" value=\"1\">あてはまる</label>"
		            + "<label class=\"\"><input type=\"radio\" name=\"comlkb" + i + "\" id=\"comlkb" + i + "\" value=\"0\" checked>あてはまらない</label>";
	    	
	    	}else if( objData[i]['comlkb'] == 1 ){
      			comlkb = comlkb + "<label class=\"\"><input type=\"radio\" name=\"comlkb" + i + "\" id=\"comlkb" + i + "\" value=\"1\" checked>あてはまる</label>"
		            + "<label class=\"\"><input type=\"radio\" name=\"comlkb" + i + "\" id=\"comlkb" + i + "\" value=\"0\">あてはまらない</label>";
	    	}else{
      			comlkb = comlkb + "<label class=\"\"><input type=\"radio\" name=\"comlkb" + i + "\" id=\"comlkb" + i + "\" value=\"1\">あてはまる</label>"
		            + "<label class=\"\"><input type=\"radio\" name=\"comlkb" + i + "\" id=\"comlkb" + i + "\" value=\"0\">あてはまらない</label>";
	    	}
		    
		    comlkb = comlkb + "</td></tr>";       

			var feekb = "<tr><th>入場料・受講料等の徴収</th><td>";
			
			if( objData[i]['feekb'] == 0 ){
		          feekb = feekb  + "<label class=\"radio-inline\"><input type=\"radio\" name=\"feekb" + i + "\" id=\"feekb" + i + "\" value=\"1\">する</label>"
		            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"feekb" + i + "\" id=\"feekb" + i + "\" value=\"0\" checked>しない</label>";
		            + "</td></tr>";
		    }else if( objData[i]['feekb'] == 1 ){        
        		feekb = feekb  +  "<label class=\"radio-inline\"><input type=\"radio\" name=\"feekb" + i + "\" id=\"feekb" + i + "\" value=\"1\" checked>する</label>"
		            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"feekb" + i + "\" id=\"feekb" + i + "\" value=\"0\">しない</label>";
        	}else{
		    	feekb = feekb  +  "<label class=\"radio-inline\"><input type=\"radio\" name=\"feekb" + i + "\" id=\"feekb" + i + "\" value=\"1\">する</label>"
		            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"feekb" + i + "\" id=\"feekb" + i + "\" value=\"0\">しない</label>";
        	}
		    feekb = feekb + "</td></tr>";

		    var partkb = "";

		    if( objData[i]['oyakokb'] == 2 ){
		    	
		    	partkb = "<tr><th>間仕切り</th><td>";
				
				if(objData[i]['partkb'] == 0 ){
			          partkb = partkb  + "<label class=\"radio-inline\"><input type=\"radio\" name=\"partkb" + i + "\" id=\"partkb" + i + "\" value=\"1\">あける</label>"
			            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"partkb" + i + "\" id=\"partkb" + i + "\" value=\"0\" checked>しめる</label>";
			            + "</td></tr>";
			    }else if( objData[i]['partkb'] == 1 ){        
	        		partkb = partkb  +  "<label class=\"radio-inline\"><input type=\"radio\" name=\"partkb" + i + "\" id=\"partkb" + i + "\" value=\"1\" checked>あける</label>"
			            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"partkb" + i + "\" id=\"partkb" + i + "\" value=\"0\">しえｍる</label>";
	        	}else{
		         	partkb = partkb  + "<label class=\"radio-inline\"><input type=\"radio\" name=\"partkb" + i + "\" id=\"partkb" + i + "\" value=\"1\">あける</label>"
			            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"partkb" + i + "\" id=\"partkb" + i + "\" value=\"0\">しめる</label>";
			            + "</td></tr>";
				}

	        	partkb = partkb + "</td></tr>";

        	}
        	var biko = "<tr><td colspan=\"2\">備考：<input type=\"text\" class=\"form-control\" style=\"width:100%\" placeholder=\"\"></td></tr>";
			td6.html( "<table class=\"table table-condensed  form-inline nest2 mb0\">" + piano + comlkb + feekb + partkb + biko + "</table>");

			td7.html( "<input type='hidden' name='rmcd" + i + "' id='rmcd" + i + "' value='" + objData[i]['rmcd'] + "'>" );
			td8.html( "<input type='hidden' name='gyo" + i + "' id='gyo" + i + "' value='" + gyo + "'>" );	//行番
			td9.html( "<input type='hidden' name='usedt" + i + "' id='usedt" + i + "' value=" + useyyyy + usemm + usedd + ">" ); //使用日付
			td10.html( "<input type='hidden' name='timekb" + i + "' id='timekb" + i + "' value='" + objData[i]['timekb'] + "'>" ); //時間帯
			//td11.html( "<input type='text' name='tnk" + i + "' id='tnk" + i + "' value='" + objData[i]['tnk'] + "'>" ); //単価
		
		}
		
		//フォーム送信時
		$('#input_form').submit(function(){

			// バリデーションチェックや、データの加工を行う。
			if( $('#kaigi').val()=='' ){
				alert("行事名を入力してください。");
				return false;
			}
			
			if( $('#riyokb').val()=='' ){
				alert( "利用目的を入力してください。");
				return false;
			}
			
			if( $('#naiyo').val() == '' ){
				if( $('#riyokb').val() == '99' ){
					alert( "内容を具体的に入力してください。");
					return false;
				}
			}

			if( $('#sekinin').val() == '' ){
				alert("当日の利用責任者を入力してください。");
				return false;
			}

			for ( var i = 0; i < objData.length; i++ ){
				
				//本番時間
				if($('#hstjkn_h' + i ).length){
					
					var jkn1 = objData[i]['jkn1'];
					jkn1 = jkn1.replace( ":" , "" ) ;
					var jkn2 = objData[i]['jkn2'];
					jkn2 = jkn2.replace( ":" , "" ) ;

					//後でちゃんと書き直す
					var hstjkn_h = $('#hstjkn_h' + i ).val();
					var hstjkn_m = $('#hstjkn_m' + i ).val();
					var hstjkn = hstjkn_h.toString() + hstjkn_m.toString();
					var hedjkn_h = $('#hedjkn_h' + i ).val();
					var hedjkn_m = $('#hedjkn_m' + i ).val();
					var hedjkn = hedjkn_h.toString() + hedjkn_m.toString();

					if( $('#hstjkn_h' + i ).val()=='' ){
						alert( "催物時間を入力してください。" );
						return false;
					}
					if( $('#hstjkn_m' + i ).val()=='' ){
						alert( "催物時間を入力してください。" );
						return false;
					}
					if( hstjkn　< jkn1 ){
						alert( "催物時間を"+ jkn1 + "以降で入力してください。" );
						return false;
					}
					if( $('#hedjkn_h' + i ).val()=='' ){
						alert( "催物時間を入力してください。" );
						return false;
					}
					if( $('#hedjkn_m' + i ).val()=='' ){
						alert( "催物時間を入力してください。" );
						return false;
					}
					//alert(hedjkn +  "> " + jkn2);
					if( hedjkn > jkn2 ){
						alert( "催物時間を"+ jkn2 + "以前で入力してください。"+ hedjkn );
						return false;
					}

					objData[i]['hstjkn_h'] = hstjkn_h;
					objData[i]['hstjkn_m'] = hstjkn_m;
					objData[i]['hedjkn_h'] = hedjkn_h;
					objData[i]['hedjkn_m'] = hedjkn_m;

				}
				//練習・準備
				if($('#jstjkn_h' + i ).length){
					objData[i]['jstjkn_h'] =$( '#jstjkn_h' + i ).val();
					objData[i]['jstjkn_m'] =$( '#jstjkn_m' + i ).val();
					objData[i]['jedjkn_h'] =$( '#jedjkn_h' + i ).val();
					objData[i]['jedjkn_m'] =$( '#jedjkn_m' + i ).val();
				}
				
				/*if($('#hstjkn_h' + i ).length){	
					//本番
					//alert($( '#hstjkn_h' + i ).val());
					objData[i]['hstjkn_h'] = $( '#hstjkn_h' + i ).val();
					objData[i]['hstjkn_m'] = $( '#hstjkn_m' + i ).val();
					objData[i]['hedjkn_h'] = $( '#hedjkn_h' + i ).val();
					objData[i]['hedjkn_m'] = $( '#hedjkn_m' + i ).val();
				}*/
				
				if($('#tstjkn_h' + i ).length){	
					//撤去
					objData[i]['tstjkn_h'] = $( '#tstjkn_h' + i ).val();
					objData[i]['tstjkn_m'] = $( '#tstjkn_m' + i ).val();
					objData[i]['tedjkn_h'] = $( '#tedjkn_h' + i ).val();
					objData[i]['tedjkn_m'] = $( '#tedjkn_m' + i ).val();
				}	
				
				if($('#ninzu' + i ).length){
					//後でちゃんと書き直す
					if(  $('#ninzu' + i ).val()=='' ){
						alert( "人数を入力してください。" );
						return false;
					}
					if(  $('#ninzu' + i ).val()==0){
						alert( "人数は0以上で入力してください。" );
						return false;
					}
				
					objData[i]['ninzu'] = $( '#ninzu' + i ).val();//入力された人数を格納
				}

				objData[i]['comlkb'] = $( '#comlkb' + i ).val();//営利目的
				objData[i]['feekb'] = $( '#feekb' + i ).val();//入場料
				objData[i]['piano'] = $( '#piano' + i ).val();//グランドピアノ
				objData[i]['partkb'] = $( '#partkb' + i ).val();//間仕切り
				objData[i]['oyakokb'] = $( '#oyakokb' + i ).val();//間仕切り

				//明細使用料
				objData[i]['rmkin'] = objData[i]['tnk'];
				objData[i]['hzkin'] = 0;//objData[i]['tnk'];


			}
			
			localStorage.removeItem('sentaku');
			//alert('removeitem');
			localStorage.setItem('sentaku', JSON.stringify(objData));
			//alert('setItem');
			
			
			//objData
			//バリデーションチェックの結果submitしない場合、return falseすることでsubmitを中止することができる。
			//return false;
			return true;
		});

		//申し込みをやめる処理
		$(".btn-del").click(function(){
			if (!confirm('この施設のお申込みを取りやめます。よろしいですか？')) {
				return false;
			}
			//name属性 からrowNo取得し、該当DOMを消去。
			var rowNo = $(this).attr("name"); 
			//alert(rowNo);
			//var lnkstr = $(this).attr("id"); 
			var btnkey = $(this).attr("id").replace('btn-','a-');
			//alert(btnkey);
			var strlist = JSON.parse(localStorage.getItem("sentaku"));//選択リスト
			//alert(btnkey);
			//console.log( btnkey );
			//var removed;
			
                //strlist.some(function (v, i) {
                //    if (v.key == lnkstr) strlist.splice(i, 1); //key:lnkstrの要素を削除
                //});
                //localStorage.setItem('sentaku', JSON.stringify(strlist));
			//該当の予約をstrlistから除く
			for ( var i = 0; i < strlist.length; i++ ){
				if( btnkey ==  strlist[i]['key']){
					//alert(btnkey);
					strlist.splice(i, 1);
				}
			}
			//localStorage.removeItem('sentaku');
			//$( '#list tr' ).eq( rowNo ).remove();
			localStorage.setItem('sentaku', JSON.stringify(strlist));
			location.reload();

		});
		
		
		//ログアウト時ローカルストレージクリア
		$(".logout").click(function(){			
			var wklist = JSON.parse(localStorage.getItem("sentaku"));
			
			for ( var i = 0; i < wklist.length; i++ ){
					wklist.splice(i, 1);
			}			
			localStorage.setItem('sentaku', JSON.stringify(wklist));
        });

		//$('#ninzu').change(function() {
        //    isChange = true;
        //    console.log("Hello world");
        //});

        //formを作成
            //サーバ側入力チェック

		/*$('#submit_Click').click(function() {
			//var val = $('#my-form [name=my-text]').val();
			//console.log(val);  //
			$('#inpit_form').submit();
		});*/

	    /*$(".btn_Click").click(function(){
		    //attrで発生したイベントのidを取得する
		    var anc = $(this).attr("id");
		    //form用のHTMLを作成する
		    var form = $('<form></form>',
					    {id:'btnid',action:'end.php',method:'POST'}).hide();
		    //bodyのオブジェクトを取得
		    var body = $('body');
		    //bodyに作成したformを追加する
		    body.append(form);
		    //追加したformにinputを追加する
		    form.append($('<input>',{type:'hidden',name:'btnid',value:anc}));
		    //作成したformでsubmitする
		    form.submit();
		    return false;
	    });*/
		

		//var val = $('#my-form [name=my-text]').val();
		//console.log(val);  //
		
		/*
		jQuery.post( url [,object] [function] [type] )ver1.0〜
		・url：読む込むデータのurl
		・object：サーバに送るデータを設定。値の型はobjectオブジェクト。
		・function：通信が「成功」したら実行される処理を設定。以下の引数を受け取る
		　・第1引数：取得したデータ
		　・第2引数：状態（success、error、notmodified、timeout、parsererror）
		　・第3引数：jqXHRオブジェクト
		・type：予期されるデータの形式（省略してもxml,json,script,html位は判断してくれます）
		*/
		
 //);
});