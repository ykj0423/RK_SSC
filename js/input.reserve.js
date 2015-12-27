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
		
		//表示用配列
		var html_array = new Array() ;
		var html_count = 0;

		for ( var i = 0; i < objData.length; i++ ){            
            
        	var oya = false;

        	//単独施設の場合
        	if( objData[i]['oyakokb'] == 1 ){
        		oya = false;
        	}

        	//親施設の場合
        	if( objData[i]['oyakokb'] == 2 ){
        		oya = true;
        	}
        	        	
        	//親施設が同じ、同一日、同一時間帯であれば、親子施設とみなす
			if( objData[i]['oyakokb'] == 3){
				for ( var j = (i + 1) ; j < objData.length; j++ ){
					if( objData[j]['oyakokb'] == 3){
						if( objData[i]['sumrmcd'] == objData[j]['sumrmcd']){
							if( objData[i]['usedt'] == objData[j]['usedt']){
								if( objData[i]['timekb'] == objData[j]['timekb']){
									objData[i]['rmcd'] = objData[i]['sumrmcd'];
									//alert( objData[i]['rmcd'] + objData[i]['sumrmcd'] );
									//objData[i]['disp'] = 1;
									objData[j]['disp'] = 0;
									oya = true;
									//break;
								}
							}
						}
					}
				}
			}

			if( objData[i]['timekb'] == 1 || objData[i]['timekb'] == 2 || objData[i]['timekb'] == 3 ){
				for ( var j = (i + 1) ; j < objData.length; j++ ){
					if( objData[i]['rmcd'] == objData[j]['rmcd']){
						if( objData[i]['usedt'] == objData[j]['usedt']){
							
							if( objData[i]['timekb'] == 1 && objData[j]['timekb']==2){
								objData[j]['jkn1'] = objData[i]['jkn1'];
								objData[i]['jkn2'] = objData[j]['jkn2'];
								objData[i]['timekb'] = 4;
								objData[j]['timekb'] = 4;
								objData[j]['disp'] = 0;					
								//break;
							}
							if( objData[i]['timekb'] == 2 && objData[j]['timekb']==3){
								objData[j]['jkn1'] = objData[i]['jkn1'];
								objData[i]['jkn2'] = objData[j]['jkn2'];
								objData[i]['timekb'] = 5;
								objData[j]['timekb'] = 5;
								objData[j]['disp'] = 0;					
								//break;
							}
						}
					}
				}
			}

			if( objData[i]['timekb'] == 4 || objData[i]['timekb'] == 5 ){
				for ( var j = (i + 1) ; j < objData.length; j++ ){
					if( objData[i]['rmcd'] == objData[j]['rmcd']){
						if( objData[i]['usedt'] == objData[j]['usedt']){

							if( objData[i]['timekb'] == 4 && objData[j]['timekb']== 3){
								objData[j]['jkn1'] = objData[i]['jkn1'];
								objData[i]['jkn2'] = objData[j]['jkn2'];
								objData[i]['timekb'] = 6;
								objData[j]['timekb'] = 6;
								objData[j]['disp'] = 0;					
								//break;
							}
							if( objData[i]['timekb'] == 1 && objData[j]['timekb']==5){
								objData[j]['jkn1'] = objData[i]['jkn1'];
								objData[i]['jkn2'] = objData[j]['jkn2'];
								objData[i]['timekb'] = 6;
								objData[j]['timekb'] = 6;
								objData[j]['disp'] = 0;					
								//break;
							}
						}
					}
				}
			}
			if( objData[i]['disp'] == 1 ){

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
				var td11 = $("<div></div>");
				var td12 = $("<div></div>");
				var td13 = $("<div></div>");
				/* 命名を配列っぽくしてもいいかもしれない */
				/* 後で変更 jkn1 jkn2 → stjkn edjkn */
				$("#list").append(tr);
				tr.append( td1 ).append( td2 ).append( td3 ).append( td4 ).append( td5 ).append( td6 ).append( td7 ).append( td8 ).append( td9 ).append( td10 ).append( td11 ).append( td12 ).append( td13 );
				
				/* 日付のフォーマット もう少しスマートな方法がないか検討*/
				var usedt = objData[i]['usedt'];
				var useyyyy = usedt.substring(0, 4);
				var usemm = objData[i]['usedt'].substring(4, 6);
				var usedd = usedt.substring(6, 8);		
				var d = new Date(useyyyy + "/" + usemm + "/" +  usedd);
				var w = ["日","月","火","水","木","金","土"];
				var yobikb = d.getDay();
				var yobi = w[yobikb];
				var gyo = i + 1;

				//td1.html( gyo );
				td1.html( "<input type=\"button\" class=\"btn btn-default btn-del\" id='btn-" + objData[i]['rmcd'] + objData[i]['usedt'] + objData[i]['timekb'] + "' name='" + i + "' role=\"button\" value=\"削除\">" );
				td2.html( useyyyy + "/" + usemm + "/" +  usedd + "（" + yobi + "）" + "<br>" + objData[i]['rmnm'] );
				td3.html( objData[i]['jkn1'] + "～" + objData[i]['jkn2'] );
				//td4.html( "時間内訳" );
				var jjkn ="<table class=\"table table-condensed  form-inline nest mb0\"><tr><th>練習・準備時間</th><td>"
	           		+ "<input type=\"text\" class=\"form-control\" name='jstjkn_h" + i + "' id='jstjkn_h" + i + "' value=\"" + objData[i]['jstjkn_h'] + "\" style=\"width:30px; ime-mode: inactive;\" maxlength=\"2\" >時"
	               	+ "<input type=\"text\" class=\"form-control\" name='jstjkn_m" + i + "' id='jstjkn_m" + i + "' value=\"" + objData[i]['jstjkn_m'] + "\" style=\"width:30px; ime-mode: inactive;\" maxlength=\"2\" >分～"
	               	+ "<input type=\"text\" class=\"form-control\" name='jedjkn_h" + i + "' id='jedjkn_h" + i + "' value=\"" + objData[i]['jedjkn_h'] + "\" style=\"width:30px; ime-mode: inactive;\" maxlength=\"2\" >時"
	               	+ "<input type=\"text\" class=\"form-control\" name='jedjkn_m" + i + "' id='jedjkn_m" + i + "' value=\"" + objData[i]['jedjkn_m'] + "\" style=\"width:30px; ime-mode: inactive;\" maxlength=\"2\" >分"
	               	+ "</td></tr></table>";
	            var hjkn = "<table class=\"table table-condensed  form-inline nest mb0\"><tr><th>催物時間</th><td>"
		            + "<input type=\"text\" class=\"form-control\" name='hstjkn_h" + i + "' id='hstjkn_h" + i + "' value=\"" + objData[i]['hstjkn_h'] + "\" style=\"width:30px; ime-mode: inactive;\" maxlength=\"2\" >時"
		            + "<input type=\"text\" class=\"form-control\" name='hstjkn_m" + i + "' id='hstjkn_m" + i + "' value=\"" + objData[i]['hstjkn_m'] + "\" style=\"width:30px; ime-mode: inactive;\" maxlength=\"2\" >分～"
		            + "<input type=\"text\" class=\"form-control\" name='hedjkn_h" + i + "' id='hedjkn_h" + i + "' value=\"" + objData[i]['hedjkn_h'] + "\" style=\"width:30px; ime-mode: inactive;\" maxlength=\"2\" >時"
		            + "<input type=\"text\" class=\"form-control\" name='hedjkn_m" + i + "' id='hedjkn_m" + i + "' value=\"" + objData[i]['hedjkn_m'] + "\" style=\"width:30px; ime-mode: inactive;\" maxlength=\"2\" >分"
		            + "</td></tr></table>";
		        var tjkn = "<table class=\"table table-condensed  form-inline nest mb0\"><tr><th>撤去時間</th><td>"
		            + "<input type=\"text\" class=\"form-control\" name='tstjkn_h" + i + "' id='tstjkn_h" + i + "' value=\"" + objData[i]['tstjkn_h'] + "\" style=\"width:30px; ime-mode: inactive;\" maxlength=\"2\" >時"
		            + "<input type=\"text\" class=\"form-control\" name='tstjkn_m" + i + "' id='tstjkn_m" + i + "' value=\"" + objData[i]['tstjkn_m'] + "\" style=\"width:30px; ime-mode: inactive;\" maxlength=\"2\" >分～"
		            + "<input type=\"text\" class=\"form-control\" name='tedjkn_h" + i + "' id='tedjkn_h" + i + "' value=\"" + objData[i]['tedjkn_h'] + "\" style=\"width:30px; ime-mode: inactive;\" maxlength=\"2\" >時"
		            + "<input type=\"text\" class=\"form-control\" name='tedjkn_m" + i + "' id='tedjkn_m" + i + "' value=\"" + objData[i]['tedjkn_m'] + "\" style=\"width:30px; ime-mode: inactive;\" maxlength=\"2\" >分"
		            + "</td></tr></table>";
				
				if( objData[i]['rmcd'] == "301" ){

					if(objData[i]['timekb'] == 1 ){

			            var jkn = "<input type=\"hidden\" class=\"form-control\" name='stjkn_h" + i + "' id='stjkn_h" + i + "' value=\"9\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='stjkn_m" + i + "' id='stjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_h" + i + "' id='edjkn_h" + i + "' value=\"12\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_m" + i + "' id='edjkn_m" + i + "' value=\"00\">";       
								
					}else if(objData[i]['timekb'] == 2 ){

	    				var jkn = "<input type=\"hidden\" class=\"form-control\" name='stjkn_h" + i + "' id='stjkn_h" + i + "' value=\"13\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='stjkn_m" + i + "' id='stjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_h" + i + "' id='edjkn_h" + i + "' value=\"17\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_m" + i + "' id='edjkn_m" + i + "' value=\"00\">";
					
					}else if(objData[i]['timekb'] == 3 ){

						var jkn = "<input type=\"hidden\" class=\"form-control\" name='stjkn_h" + i + "' id='stjkn_h" + i + "' value=\"18\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='stjkn_m" + i + "' id='stjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_h" + i + "' id='edjkn_h" + i + "' value=\"21\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_m" + i + "' id='edjkn_m" + i + "' value=\"00\">";

					}else if(objData[i]['timekb'] == 4 ){

						var jkn = "<input type=\"hidden\" class=\"form-control\" name='stjkn_h" + i + "' id='stjkn_h" + i + "' value=\"9\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='stjkn_m" + i + "' id='stjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_h" + i + "' id='edjkn_h" + i + "' value=\"17\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_m" + i + "' id='edjkn_m" + i + "' value=\"00\">";
						
					}else if(objData[i]['timekb'] == 5 ){

						var jkn = "<input type=\"hidden\" class=\"form-control\" name='stjkn_h" + i + "' id='stjkn_h" + i + "' value=\"13\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='stjkn_m" + i + "' id='stjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_h" + i + "' id='edjkn_h" + i + "' value=\"21\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_m" + i + "' id='edjkn_m" + i + "' value=\"00\">";
						
					}else if(objData[i]['timekb'] == 6 ){

						var jkn = "<input type=\"hidden\" class=\"form-control\" name='stjkn_h" + i + "' id='stjkn_h" + i + "' value=\"9\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='stjkn_m" + i + "' id='stjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_h" + i + "' id='edjkn_h" + i + "' value=\"21\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_m" + i + "' id='edjkn_m" + i + "' value=\"00\">";
						
					}

					td4.html( jkn + jjkn + hjkn + tjkn );	
				
				}else{
					
					if(objData[i]['timekb'] == 1 ){

			            var jkn = "<input type=\"hidden\" class=\"form-control\" name='stjkn_h" + i + "' id='stjkn_h" + i + "' value=\"9\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='stjkn_m" + i + "' id='stjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_h" + i + "' id='edjkn_h" + i + "' value=\"12\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_m" + i + "' id='edjkn_m" + i + "' value=\"00\">";
				        
				        var hjkn = "<input type=\"hidden\" class=\"form-control\" name='hstjkn_h" + i + "' id='hstjkn_h" + i + "' value=\"9\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hstjkn_m" + i + "' id='hstjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hedjkn_h" + i + "' id='hedjkn_h" + i + "' value=\"12\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hedjkn_m" + i + "' id='hedjkn_m" + i + "' value=\"00\">";
									
					}else if(objData[i]['timekb'] == 2 ){

	    				var jkn = "<input type=\"hidden\" class=\"form-control\" name='stjkn_h" + i + "' id='stjkn_h" + i + "' value=\"13\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='stjkn_m" + i + "' id='stjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_h" + i + "' id='edjkn_h" + i + "' value=\"17\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_m" + i + "' id='edjkn_m" + i + "' value=\"00\">";

	    				var hjkn = "<input type=\"hidden\" class=\"form-control\" name='hstjkn_h" + i + "' id='hstjkn_h" + i + "' value=\"13\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hstjkn_m" + i + "' id='hstjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hedjkn_h" + i + "' id='hedjkn_h" + i + "' value=\"17\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hedjkn_m" + i + "' id='hedjkn_m" + i + "' value=\"00\">";
					
					}else if(objData[i]['timekb'] == 3 ){

						var jkn = "<input type=\"hidden\" class=\"form-control\" name='stjkn_h" + i + "' id='stjkn_h" + i + "' value=\"18\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='stjkn_m" + i + "' id='stjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_h" + i + "' id='edjkn_h" + i + "' value=\"21\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_m" + i + "' id='edjkn_m" + i + "' value=\"00\">";

						var hjkn = "<input type=\"hidden\" class=\"form-control\" name='hstjkn_h" + i + "' id='hstjkn_h" + i + "' value=\"18\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hstjkn_m" + i + "' id='hstjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hedjkn_h" + i + "' id='hedjkn_h" + i + "' value=\"21\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hedjkn_m" + i + "' id='hedjkn_m" + i + "' value=\"00\">";
					}else if(objData[i]['timekb'] == 4 ){

						var jkn = "<input type=\"hidden\" class=\"form-control\" name='stjkn_h" + i + "' id='stjkn_h" + i + "' value=\"9\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='stjkn_m" + i + "' id='stjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_h" + i + "' id='edjkn_h" + i + "' value=\"17\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_m" + i + "' id='edjkn_m" + i + "' value=\"00\">";

						var hjkn = "<input type=\"hidden\" class=\"form-control\" name='hstjkn_h" + i + "' id='hstjkn_h" + i + "' value=\"9\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hstjkn_m" + i + "' id='hstjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hedjkn_h" + i + "' id='hedjkn_h" + i + "' value=\"17\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hedjkn_m" + i + "' id='hedjkn_m" + i + "' value=\"00\">";
					}else if(objData[i]['timekb'] == 5 ){

						var jkn = "<input type=\"hidden\" class=\"form-control\" name='stjkn_h" + i + "' id='stjkn_h" + i + "' value=\"13\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='stjkn_m" + i + "' id='stjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_h" + i + "' id='edjkn_h" + i + "' value=\"21\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_m" + i + "' id='edjkn_m" + i + "' value=\"00\">";

						var hjkn = "<input type=\"hidden\" class=\"form-control\" name='hstjkn_h" + i + "' id='hstjkn_h" + i + "' value=\"13\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hstjkn_m" + i + "' id='hstjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hedjkn_h" + i + "' id='hedjkn_h" + i + "' value=\"21\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hedjkn_m" + i + "' id='hedjkn_m" + i + "' value=\"00\">";
					}else if(objData[i]['timekb'] == 6 ){

						var jkn = "<input type=\"hidden\" class=\"form-control\" name='stjkn_h" + i + "' id='stjkn_h" + i + "' value=\"9\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='stjkn_m" + i + "' id='stjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_h" + i + "' id='edjkn_h" + i + "' value=\"21\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='edjkn_m" + i + "' id='edjkn_m" + i + "' value=\"00\">";

						var hjkn = "<input type=\"hidden\" class=\"form-control\" name='hstjkn_h" + i + "' id='hstjkn_h" + i + "' value=\"9\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hstjkn_m" + i + "' id='hstjkn_m" + i + "' value=\"00\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hedjkn_h" + i + "' id='hedjkn_h" + i + "' value=\"21\">"
				            + "<input type=\"hidden\" class=\"form-control\" name='hedjkn_m" + i + "' id='hedjkn_m" + i + "' value=\"00\">";
					}

					
					td4.html( "<input type='hidden' name='rmcd" + i + "' id='rmcd" + i + "' value='" + objData[i]['rmcd'] + "'>" + jkn + hjkn );

				}
				
				td5.html( "<input type='text' class='form-control' name='ninzu" + i + "' id='ninzu" + i + "' value='" + objData[i]['ninzu'] + "' style='width:50px'>人" );
				//td6.html( "営利目的での利用" + "入場料・受講料等の徴収" );			
			
				var piano = "";
				
				if( objData[i]['rmcd'] == "301" ){
					
					piano = "<tr><th>グランドピアノ</th><td>";
				
					if( objData[i]['piano'] == 0 ){
				    	piano = piano   + "<label class=\"radio-inline\"><input type=\"radio\" name=\"piano" + i + "\" id=\"piano" + i + "1\" value=\"1\">使用する</label>"
				            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"piano" + i + "\" id=\"piano" + i + "0\" value=\"0\" checked>使用しない</label>";
					}else if( objData[i]['piano'] == 1 ){
				    	piano = piano + "<label class=\"radio-inline\"><input type=\"radio\" name=\"piano" + i + "\" id=\"piano" + i + "1\" value=\"1\" checked>使用する</label>"
				            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"piano" + i + "\" id=\"piano" + i + "0\" value=\"0\">使用しない</label>";
					}else{
						piano = piano + "<label class=\"radio-inline\"><input type=\"radio\" name=\"piano" + i + "\" id=\"piano" + i + "1\" value=\"1\">使用する</label>"
				            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"piano" + i + "\" id=\"piano" + i + "0\" value=\"0\">使用しない</label>";
					}
		        
			        piano = piano + "</td></tr>";
			
				}
		    
			    var comlkb = "<tr><th>営利目的での利用<br>（販売やPR活動も含む）</th><td>";
			    
			    if( objData[i]['comlkb'] == 0 ){
		        	comlkb = comlkb + "<label class=\"\"><input type=\"radio\" name=\"comlkb" + i + "\" id=\"comlkb" + i + "1\" value=\"1\">あてはまる</label>"
			            + "<label class=\"\"><input type=\"radio\" name=\"comlkb" + i + "\" id=\"comlkb" + i + "0\" value=\"0\" checked>あてはまらない</label>";
		    	
		    	}else if( objData[i]['comlkb'] == 1 ){
	      			comlkb = comlkb + "<label class=\"\"><input type=\"radio\" name=\"comlkb" + i + "\" id=\"comlkb" + i + "1\" value=\"1\" checked>あてはまる</label>"
			            + "<label class=\"\"><input type=\"radio\" name=\"comlkb" + i + "\" id=\"comlkb" + i + "0\" value=\"0\">あてはまらない</label>";
		    	}else{
	      			comlkb = comlkb + "<label class=\"\"><input type=\"radio\" name=\"comlkb" + i + "\" id=\"comlkb" + i + "1\" value=\"1\">あてはまる</label>"
			            + "<label class=\"\"><input type=\"radio\" name=\"comlkb" + i + "\" id=\"comlkb" + i + "0\" value=\"0\">あてはまらない</label>";
		    	}
			    
			    comlkb = comlkb + "</td></tr>";       

				var feekb = "<tr><th>入場料・受講料等の徴収</th><td>";
				
				if( objData[i]['feekb'] == 0 ){
			          feekb = feekb  + "<label class=\"radio-inline\"><input type=\"radio\" name=\"feekb" + i + "\" id=\"feekb" + i + "1\" value=\"1\">する</label>"
			            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"feekb" + i + "\" id=\"feekb" + i + "0\" value=\"0\" checked>しない</label>";
			            + "</td></tr>";
			    }else if( objData[i]['feekb'] == 1 ){        
	        		feekb = feekb  +  "<label class=\"radio-inline\"><input type=\"radio\" name=\"feekb" + i + "\" id=\"feekb" + i + "0\" value=\"1\" checked>する</label>"
			            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"feekb" + i + "\" id=\"feekb" + i + "0\" value=\"0\">しない</label>";
	        	}else{
			    	feekb = feekb  +  "<label class=\"radio-inline\"><input type=\"radio\" name=\"feekb" + i + "\" id=\"feekb" + i + "1\" value=\"1\">する</label>"
			            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"feekb" + i + "\" id=\"feekb" + i + "0\" value=\"0\">しない</label>";
	        	}
			    
			    feekb = feekb + "</td></tr>";

		    	var partkb = "";

			    if(oya==true){
			    	
			    	partkb = "<tr><th>間仕切り</th><td>";
					
					//if(objData[i]['partkb'] == 0 ){
				          partkb = partkb  + "<label class=\"radio-inline\"><input type=\"radio\" name=\"partkb" + i + "\" id=\"partkb" + i + "1\" value=\"0\">あける</label>"
				            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"partkb" + i + "\" id=\"partkb" + i + "0\" value=\"1\">しめる</label>";
				            + "</td></tr>";
				    //}else if( objData[i]['partkb'] == 1 ){        
		        	//	partkb = partkb  +  "<label class=\"radio-inline\"><input type=\"radio\" name=\"partkb" + i + "\" id=\"partkb" + i + "\" value=\"1\" checked>あける</label>"
				    //        + "<label class=\"radio-inline\"><input type=\"radio\" name=\"partkb" + i + "\" id=\"partkb" + i + "\" value=\"0\">しめる</label>";
		        	//}else{
			        // 	partkb = partkb  + "<label class=\"radio-inline\"><input type=\"radio\" name=\"partkb" + i + "\" id=\"partkb" + i + "\" value=\"1\">あける</label>"
				    //        + "<label class=\"radio-inline\"><input type=\"radio\" name=\"partkb" + i + "\" id=\"partkb" + i + "\" value=\"0\">しめる</label>";
				    //        + "</td></tr>";
					//}

		        	//partkb = partkb + "</td></tr>";

	        	}

	        	var biko = "<tr><td colspan=\"2\">備考：<input type=\"text\" name='biko" + i + "' id='biko" + i + "' class=\"form-control\" style=\"width:100%; ime-mode: active;\" maxlength=\"30\"></td></tr>";
				td6.html( "<table class=\"table table-condensed  form-inline nest2 mb0\">" + piano + comlkb + feekb + partkb + biko + "</table>");
				td7.html( "<input type='hidden' name='rmcd" + i + "' id='rmcd" + i + "' value='" + objData[i]['rmcd'] + "'>" );
				td8.html( "<input type='hidden' name='gyo" + i + "' id='gyo" + i + "' value='" + gyo + "'>" );	//行番
				td9.html( "<input type='hidden' name='usedt" + i + "' id='usedt" + i + "' value=" + useyyyy + usemm + usedd + ">" ); //使用日付
				td10.html( "<input type='hidden' name='timekb" + i + "' id='timekb" + i + "' value='" + objData[i]['timekb'] + "'>" ); //時間帯
				//td14.html( "<input type='text' name='oyakokb" + i + "' id='oyakokb" + i + "' value='" + objData[i]['oyakokb'] + "'>" ); 
				//td12.html( "<input type='text' name='sumrmcd" + i + "' id='sumrmcd" + i + "' value='" + objData[i]['sumrmcd'] + "'>" ); //単価
				td11.html( "<input type='hidden' name='yobi" + i + "' id='yobi" + i + "' value='" + yobi + "'>" ); 
				td12.html( "<input type='hidden' name='yobikb" + i + "' id='yobikb" + i + "' value='" + yobikb + "'>" ); //単価
				td13.html( "<input type='hidden' name='rmnm" + i + "' id='rmnm" + i + "' value='" + objData[i]['rmnm'] + "'>" ); //単価
			}

 
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
//alert(objData.length);
			for ( var i = 0; i < objData.length; i++ ){

				if(objData[i]['disp'] == 0 ) {
					continue;
				}					
				
				var jkn1 = objData[i]['jkn1'];
				jkn1 = jkn1.replace( ":" , "" ) ;
				var jkn2 = objData[i]['jkn2'];
				jkn2 = jkn2.replace( ":" , "" ) ;

				jkn1 = parseInt(jkn1);
				jkn2 = parseInt(jkn2);

				var hstjkn_h = $('#hstjkn_h' + i ).val();
				var hstjkn_m = $('#hstjkn_m' + i ).val();
				var hstjkn = hstjkn_h.toString() + hstjkn_m.toString();
				var hedjkn_h = $('#hedjkn_h' + i ).val();
				var hedjkn_m = $('#hedjkn_m' + i ).val();
				var hedjkn = hedjkn_h.toString() + hedjkn_m.toString();
			
				//本番時間
				if( $('#hstjkn_h' + i ).length ){
					
					//本番時間チェック
					if( !check_time( hstjkn_h, hstjkn_m, hedjkn_h, hedjkn_m, "催物", true ) ){
						return false;
					}
					
					hstjkn = parseInt(hstjkn);
					hedjkn = parseInt(hedjkn);

					if( hstjkn < jkn1 || hedjkn > jkn2 ){
						alert("催物時間は使用時間内で入力してください");
						return false;
					}

					objData[i]['hstjkn_h'] = hstjkn_h;
					objData[i]['hstjkn_m'] = hstjkn_m;
					objData[i]['hedjkn_h'] = hedjkn_h;
					objData[i]['hedjkn_m'] = hedjkn_m;
					//alert("a");
	
				}
				//alert("b");
				//練習・準備
				if( $('#jstjkn_h' + i ).length ){

					var jstjkn_h = $('#jstjkn_h' + i ).val();
					var jstjkn_m = $('#jstjkn_m' + i ).val();
					var jstjkn = jstjkn_h.toString() + jstjkn_m.toString();
					var jedjkn_h = $('#jedjkn_h' + i ).val();
					var jedjkn_m = $('#jedjkn_m' + i ).val();
					var jedjkn = jedjkn_h.toString() + jedjkn_m.toString();
//alert(jstjkn+" " +hstjkn);
					//時間チェック
					if( !check_time( jstjkn_h, jstjkn_m, jedjkn_h, jedjkn_m, "準備・リハ", false ) ){
						return false;
					}
		
					jstjkn = parseInt(jstjkn);
					jedjkn = parseInt(jedjkn);

					if( jstjkn < jkn1 || jedjkn > jkn2 ){
						alert("準備・リハ時間は使用時間内で入力してください");
						//alert("準備・リハ時間は催物時間より前で入力してください");
						return false;
					}			
					if( jstjkn >= hstjkn || jedjkn > hstjkn ){
						alert("準備・リハ時間は催物時間より前で入力してください");
						return false;
					}
					if( jstjkn >= hedjkn || jedjkn >= hedjkn ){
						alert("準備・リハ時間は催物時間より前で入力してください");
						return false;
					}

					objData[i]['jstjkn_h'] = jstjkn_h;
					objData[i]['jstjkn_m'] = jstjkn_m;
					objData[i]['jedjkn_h'] = jedjkn_h;
					objData[i]['jedjkn_m'] = jedjkn_m;
					
				}//練習・準備
				//alert("c");
				
				if($('#tstjkn_h' + i ).length){
					//撤去
					var tstjkn_h = $('#tstjkn_h' + i ).val();
					var tstjkn_m = $('#tstjkn_m' + i ).val();
					var tstjkn = tstjkn_h.toString() + tstjkn_m.toString();
					var tedjkn_h = $('#tedjkn_h' + i ).val();
					var tedjkn_m = $('#tedjkn_m' + i ).val();
					var tedjkn = tedjkn_h.toString() + tedjkn_m.toString();

					//時間チェック
					if( !check_time( tstjkn_h, tstjkn_m, tedjkn_h, tedjkn_m, "撤去", false) ){
						return false;
					}

					tstjkn = parseInt(tstjkn);
					tedjkn = parseInt(tedjkn);

					if( tstjkn < jkn1 || tedjkn > jkn2 ){
						alert("撤去時間は使用時間内で入力してください");
						return false;
					}			
					if( tstjkn <= hstjkn || tedjkn <= hstjkn ){
						alert("撤去時間は催物時間より後で入力してください");
						return false;
					}
					if( tstjkn <= hedjkn || tedjkn <= hedjkn ){
						alert("撤去時間は催物時間より後で入力してください");
						return false;
					}

					objData[i]['tstjkn_h'] = tstjkn_h;
					objData[i]['tstjkn_m'] = tstjkn_m;
					objData[i]['tedjkn_h'] = tedjkn_h;
					objData[i]['tedjkn_m'] = tedjkn_m;
				}//撤去
				
				
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
				}//人数

//alert("a1");
				//if($('#comlkb0').length){
				if( $('input[name=comlkb'+ i +']:eq(0)').prop('checked') ){
				}else if( $('input[name=comlkb'+ i +']:eq(1)').prop('checked') ){
				}else{
					alert( "「営利目的での利用」に該当するかどうかを選択してください。" );	
					return false;
				}
//alert("a2");
				
				if( $('input[name=feekb'+ i +']:eq(0)').prop('checked') ){
				}else if( $('input[name=feekb'+ i +']:eq(1)').prop('checked') ){
				}else{
					alert( "入場料、受講料を徴収するかどうかを選択してください。" );	
					return false;
				}
//alert("a3");

				if ( $( '#piano'+i+'0').length > 0 ){
					if( $('input[name=piano'+ i +']:eq(0)').prop('checked') ){
					}else if( $('input[name=piano'+ i +']:eq(1)').prop('checked') ){
					}else{
						alert( "グランドピアノを使用するかどうかを選択してください。" );	
						return false;
					}
				}
//alert("a4");
 
				if ( $( '#partkb'+i+'0').length > 0 ){
					if( $('input[name=partkb'+ i +']:eq(0)').prop('checked') ){
					}else if( $('input[partkb=piano'+ i +']:eq(1)').prop('checked') ){
					}else{
						alert( "間仕切りを開けるか閉めるかを選択してください。" );	
						return false;
					}
				}

				objData[i]['comlkb'] = $( '#comlkb' + i ).val();//営利目的
				objData[i]['feekb'] = $( '#feekb' + i ).val();//入場料
				objData[i]['piano'] = $( '#piano' + i ).val();//グランドピアノ
				objData[i]['partkb'] = $( '#partkb' + i ).val();//間仕切り
				objData[i]['oyakokb'] = $( '#oyakokb' + i ).val();//間仕切り
//alert("a6");
				//明細使用料
				objData[i]['rmkin'] = objData[i]['tnk'];
				objData[i]['hzkin'] = 0;//objData[i]['tnk'];

//alert("a7");
			}
//alert("a8");			
			localStorage.removeItem('sentaku');
			//alert('removeitem');
			localStorage.setItem('sentaku', JSON.stringify(objData));
			//alert('setItem');
			$('#input_form').append($('<input>',{type:'hidden',name:'meisai_count',value:objData.length}));		
			
			//バリデーションチェックの結果submitしない場合、return falseすることでsubmitを中止することができる。
			//return false;
			return true;
		});

  		
  		$.isBlank = function(obj){
    		return(!obj || $.trim(obj) === "");
  		};

  		$.isH = function(obj){
  			//24hourではなく9-21
  			alert(parseInt( $.trim( obj )));
    		return ( ( parseInt( $.trim( obj ) ) >= 9 ) &&  ( parseInt( $.trim( obj ) ) <= 21) );
  		};
  		
  		$.isM = function(obj){
  			return ( ( parseInt( $.trim( obj ) ) >= 0 ) &&  ( parseInt( $.trim( obj ) ) <= 60) );
  		};

		function check_time (h1, m1, h2, m2, wd, req) {
			//if()
    		if(!req){
    			if( $.isBlank(h1) && $.isBlank(m1) && $.isBlank(h2) & $.isBlank(m2) ){    				
    				return true;
    			}
    		}

			if( $.isBlank(h1) || $.isBlank(m1) || $.isBlank(h2) || $.isBlank(m2) ){
				alert( wd + "開始時間と終了時間を入力してください");
				return false;
			}

    		if( $.isNumeric(h1) && $.isNumeric(m1) && $.isNumeric(h2) && $.isNumeric(m2) ){
    			h1 = parseInt( h1 );
    			m1 = parseInt( m1 );
    			h2 = parseInt( h2 );
    			m2 = parseInt( m2 );
    		}else{
    			alert( wd + "開始時間と終了時間を数字で入力してください");
    			return false;
    		}
    		if( (h1.length > 2) || (m2.length > 2) || (h2.length > 2) || (m2.length > 2) ){
    			alert( wd + "開始時間と終了時間を正しく入力してください。");
    			return false;
    		}
      		if ( ( h1 < 9 ) || ( h1 > 21) || ( m1 < 0 ) || ( m1 > 60 ) ){
				alert( wd + "開始時間と終了時間を正しく入力してください:");
    			return false;
    		}
      		if ( ( h2 < 9 ) || ( h2 > 21) || ( m2 < 0 ) || ( m2 > 60 ) ){
				alert( wd + "開始時間と終了時間を正しく入力してください.");
    			return false;
    		}
			if ( h2 <= h1 ){
				alert( wd + "開始時間と終了時間を正しく入力してください;"+h2+"<="+ h1);
    			return false;
    		}
    		return true;  		
		}

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