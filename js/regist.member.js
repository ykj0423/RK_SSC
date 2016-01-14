jQuery(function () {
 
	　$('input[type=text]').on('blur', function(){
	　　var txt = $(this).val();
	　　var search_txt = "[①②③④⑤⑥⑦⑧⑨⑩⑪⑫⑬⑭⑮⑯⑰⑱⑲⑳ⅠⅡⅢⅣⅤⅥⅦⅧⅨⅩ㍉㌔㌢㍍㌘㌧㌃㌶㍑㍗㌍㌦㌣㌫㍊㌻㎜㎝㎞㎎㎏㏄㎡㍻〝〟№㏍℡㊤㊥㊦㊧㊨㈱㈲㈹㍾㍽㍼]";
	　　if(txt.match(search_txt)){
	　　　alert("機種依存文字が入力されています。\n数字①Ⅰなどはご使用になれません。\n㈱㈲などは(株)(有)のようにご入力ください。");
	　　}
	　});

	//フォーム送信時
	$('#regist_form').submit(function(){

		// バリデーションチェックや、データの加工を行う。

		if( $('#dannm').val()=='' ){
			alert("利用者名を入力してください。");
			$('#dannm').focus();
			return false;
		}
		if( $('#daihyo').val()=='' ){
			alert("代表者を入力してください。");
			$('#daihyo').focus();
			return false;
		}
					
		if( $('#renraku').val() == '' ){
			alert("連絡者を入力してください。");
			return false;
		}

		if( $('#tel2_1').val() == '' ){
			alert("電話番号を入力してください。");
			$('#tel2_1').focus();
			return false;
		}
		
		if( $('#tel2_2').val() == '' ){
			alert("電話番号を入力してください。");
			$('#tel2_2').focus();
			return false;
		}
		
		if( $('#tel2_3').val() == '' ){
			alert("電話番号を入力してください。");
			$('#tel2_3').focus();
			return false;
		}

		if( $('#mail').val() == '' ){
			alert("メールアドレスを入力してください。");
			$('#mail').focus();
			return false;
		}

		if( $('#re_mail').val() == '' ){
			alert("メールアドレスを入力してください。");
			$('#re_mail').focus();
			return false;
		}
		
		if( $('#mail').val() != $('#re_mail').val() ){
			alert("メールアドレスが異なっています。");
			$('#mail').focus();
			return false;
		}

		if( $('#zipcd_1').val() == '' ){
			alert("郵便番号を入力してください。");
			$('#zipcd_1').focus();
			return false;
		}

		if( $('#zipcd_2').val() == '' ){
			alert("郵便番号を入力してください。");
			$('#zipcd_2').focus();
			return false;
		}

		if( $('#adr1').val() == '' && $('#adr2').val() == '' ){
			$('#adr1').focus();
			alert("住所を入力してください。");
			return false;
		}

		//バリデーションチェックの結果submitしない場合、return falseすることでsubmitを中止することができる。
		//return false;
		return true;
	});

		
});