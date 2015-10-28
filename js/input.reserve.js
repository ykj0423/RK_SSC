jQuery(function () {
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
			var td11 = $("<div></div>");
			
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
			tr.append( td1 ).append( td2 ).append( td3 ).append( td4 ).append( td5 ).append( td6 ).append( td7 ).append( td8 ).append( td9 ).append( td10 );
			//td1.html( gyo );
			td1.html( "<a class=\"btn btn-default btnclass\" id='btn-" + objData[i]['rmcd'] + objData[i]['usedt'] + objData[i]['timekb'] + "' name='" + i + "' href=\"#\" role=\"button\">削除</a>" );
			td2.html( useyyyy + "/" + usemm + "/" +  usedd  + yobi + "<br>" + objData[i]['rmnm'] );
			td3.html( objData[i]['jkn1'] + "～" + objData[i]['jkn2'] );
			//td4.html( "時間内訳" );
			td4.html( "<table class=\"table table-condensed  form-inline nest mb0\"><tr><th>催物時間</th><td>"
               + "<input type=\"text\" class=\"form-control\" value=\"\" style=\"width:30px\" maxlength=\"2\" >時"
               + "<input type=\"text\" class=\"form-control\" value=\"\" style=\"width:30px\"  maxlength=\"2\" >分～"
               + "<input type=\"text\" class=\"form-control\" value=\"\" style=\"width:30px\" maxlength=\"2\" >時"
               + "<input type=\"text\" class=\"form-control\" value=\"\" style=\"width:30px\"  maxlength=\"2\" >分"
               + "</td></tr></table>");
			td5.html( "<input type='text' class='form-control' name='ninzu" + i + "' id='ninzu" + i + "' value='' style='width:50px'>人<span class='text-danger'>（必須)</span>" );
			//td6.html( "営利目的での利用" + "入場料・受講料等の徴収" );			
			td6.html( "<table class=\"table table-condensed  form-inline nest2 mb0\"><tr><th>営利目的での利用<br>（販売やPR活動も含む）</th><td>"
	            + "<label class=\"\"><input type=\"radio\" name=\"j0[1]\" value=\"2\">あてはまる</label>"
	            + "<label class=\"\"><input type=\"radio\" name=\"j0[1]\" value=\"2\" >あてはまらない</label>"
	            + "</td></tr><tr><th>入場料・受講料等の徴収</th><td>"
	            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"j1[1]\" value=\"2\">する</label>"
	            + "<label class=\"radio-inline\"><input type=\"radio\" name=\"j1[1]\" value=\"2\">しない</label>"
	            + "</td></tr></table>");
			td7.html( "<input type='hidden' name='rmcd" + i + "' id='rmcd" + i + "' value='" + objData[i]['rmcd'] + "'>" );
			td8.html( "<input type='hidden' name='gyo" + i + "' id='gyo" + i + "' value='" + gyo + "'>" );	//行番
			td9.html( "<input type='hidden' name='usedt" + i + "' id='usedt" + i + "' value=" + useyyyy + usemm + usedd + ">" ); //使用日付
			td10.html( "<input type='hidden' name='timekb" + i + "' id='timekb" + i  + "' value='" + objData[i]['timekb'] + "'>" ); //時間帯
		}

		//フォーム送信時
		$('#input_form').submit(function(){

			// バリデーションチェックや、データの加工を行う。
			if($('#kaigi').val()=='' ){
				alert("行事名を入力してください。");
				return false;
			}
			if($('#riyokb').val()=='' ){
				alert("利用目的を入力してください。");
				return false;
			}
			
			for ( var i = 0; i < objData.length; i++ ){
					if($('#ninzu' + i ).length){
						//後でちゃんと書き直す
						if(  $('#ninzu' + i ).val()=='' ){
							alert("人数を入力してください。");
							return false;
						}
						if(  $('#ninzu' + i ).val()==0){
							alert("人数は０以上で入力してください。");
							return false;
						}
						objData[i]['ninzu'] = $('#ninzu' + i ).val();//入力された人数を格納
					}
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
		$(".btnclass").click(function(){
			if (!confirm('この施設のお申込みを取りやめます。よろしいですか？')) {
				return false;
			}
			//name属性 からrowNo取得し、該当DOMを消去。
			var rowNo = $(this).attr("name"); 
			
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
			$( '#list tr' ).eq( rowNo ).remove();
			localStorage.setItem('sentaku', JSON.stringify(strlist));

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
		
    });   