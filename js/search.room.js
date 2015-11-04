jQuery(function () {

		/* 予約件数の復元 */		
		var strlist = new Array(99);
		//strlist = JSON.parse( localStorage.getItem("sentaku") );//選択リスト
		//$(".selcnt").text("現在の選択 ： " + strlist.length + "件");
		$(".selcnt").text("現在の選択 ： 0 件");
        
        jQuery('#date_timepicker_start').datetimepicker({
            format: 'Y/m/d',
            lang: 'ja',
            //startDate: new Date(),
            //defaultDate: new Date(),
            onShow: function (ct) {
                this.setOptions({
                    maxDate: jQuery('#date_timepicker_end').val() ? jQuery('#date_timepicker_end').val() : false
                })
            },
            timepicker: false
        });
		//初期値は？
		jQuery('#date_timepicker_end').datetimepicker({
            format: 'Y/m/d',
            lang: 'ja',
            onShow: function (ct) {
                this.setOptions({
                    minDate: jQuery('#date_timepicker_start').val() ? jQuery('#date_timepicker_start').val() : false
                })
            },
            timepicker: false
        });

		//現在の選択件数の更新
		//$(".selcnt").text("現在の選択 ： " + strlist.length + "件");
		
		//前へボタン
		$('.prev').click(function() {
			
			//postされる値にパラメータをセット
			var stt = $('#date_timepicker_start').val();
			var sttDate = new Date( stt );
			var calc = -14;
			
			for (var i = 1; i < 8; i++) {
			
				if($("#yobi" + i).prop('checked')) {
				}else{
					calc--;
				}
			
			}
			
			var calcDate = new Date(sttDate.getTime() + calc*24*60*60*1000);//開始日
			var calc_yyyy = calcDate.getFullYear();
			var calc_mm = calcDate.getMonth()+1 ;
			var calc_dd = calcDate.getDate();
			
			if (calc_mm < 10) {
				calc_mm = '0' + calc_mm
			}

			if (calc_dd < 10) {
				calc_dd = '0' + calc_dd;
			}
			
			var sttdt = calc_yyyy + "/" + calc_mm + "/" + calc_dd;

			//var sttdt = calcDate.getFullYear() + "/" + (calcDate.getMonth()+1) + "/" + calcDate.getDate();
			//$('#date_timepicker_start').attr({'value': sttdt });
			
			//やり方ダサすぎる
			calc = 0;
			
			for (var j = 0; j < 8 ; j++) {			
				for (var i = 1; i < 8; i++) {			
					if($("#yobi" + i).prop('checked')) {
						
						calc++;
						
						if(　calc　>　12 ) {
							break;
						}					
					}				
				}
				
				if(　calc　>　12 ) {
					break;
				}
			
			}
			
			var calcDate = new Date( calcDate.getTime() + calc*24*60*60*1000 );//開始日 + calc
			
			var calc_yyyy = calcDate.getFullYear();
			var calc_mm = calcDate.getMonth()+1 ;
			var calc_dd = calcDate.getDate();
			
			if (calc_mm < 10) {
				calc_mm = '0' + calc_mm
			}

			if (calc_dd < 10) {
				calc_dd = '0' + calc_dd;
			}

			//var enddt = calcDate.getFullYear() + "/" + (calcDate.getMonth()+1) + "/" + calcDate.getDate();

			var enddt = calc_yyyy + "/" + calc_mm + "/" + calc_dd;

			$('#date_timepicker_start').attr({'value': sttdt });
			$('#date_timepicker_end').attr({'value': enddt });
			$('#search_form').submit();

		});

		//次へボタン
		$('.next').click(function() {
			
			//postされる値にパラメータをセット
			var stt = $('#date_timepicker_end').val();			
			var sttDate = new Date( stt );
			var calc = 1;
			
			for (var i = 1; i < 8; i++) {
			
				if($("#yobi" + i).prop('checked')) {
					break;
				}else{
					calc++;
				}
			
			}

			//チェックしたら増えていく
			var calcDate = new Date( sttDate.getTime() + calc*24*60*60*1000 );//開始日
			var calc_yyyy = calcDate.getFullYear();
			var calc_mm = calcDate.getMonth()+1 ;
			var calc_dd = calcDate.getDate();
			
			if (calc_mm < 10) {
				calc_mm = '0' + calc_mm
			}

			if (calc_dd < 10) {
				calc_dd = '0' + calc_dd;
			}
			
			var sttdt = calc_yyyy + "/" + calc_mm + "/" + calc_dd;
			
			sttDate = new Date( sttdt );
			
			calc=0;
			
			for (var j = 0; j < 8 ; j++) {			
				for (var i = 1; i < 8; i++) {			
					if($("#yobi" + i).prop('checked')) {
						
						calc++;
						
						if(　calc　>　12 ) {
							break;
						}					
					}				
				}
				
				if(　calc　>　12 ) {
					break;
				}			
			}

			var calcDate = new Date( sttDate.getTime() + calc*24*60*60*1000 );//開始日
			var calc_yyyy = calcDate.getFullYear();
			var calc_mm = calcDate.getMonth()+1 ;
			var calc_dd = calcDate.getDate();
			
			if (calc_mm < 10) {
				calc_mm = '0' + calc_mm
			}

			if (calc_dd < 10) {
				calc_dd = '0' + calc_dd;
			}

			var enddt = calc_yyyy + "/" + calc_mm + "/" + calc_dd;
			
			$('#date_timepicker_start').attr({'value': sttdt });			
			$('#date_timepicker_end').attr({'value': enddt });
			$('#search_form').submit();

		});

		/* 選択状態格納リスト */
        var strlist = new Array();

		/* 予約状態の復元 */		
		var objData = JSON.parse(localStorage.getItem("sentaku"));//選択リスト
		if( objData != null){
			for ( var i=0; i < objData.length; i++ ){
				var  lnkstr = objData[i]['key'];
				var imgstr = lnkstr.replace('a-', 'img-');
				$("#" + imgstr).attr('src', 'icon/sentaku.png');
			}
		}
		/* 空室・選択クリック時 */
        $("a").click(function () {

			/* 予約状態の復元 */
			//var strlist = JSON.parse(localStorage.getItem("sentaku"));
			//var strlist = array();
			var lnkstr = $(this).attr("id");
            //var tdstr = lnkstr.replace('a-', '');
            var imgstr = lnkstr.replace('a-', 'img-');
            var datastr = lnkstr.replace('a-', 'data-');
            var usedt = $("#" + datastr).attr('data-usedt');	//使用日
			var yobi = $("#" + datastr).attr('data-yobi');		//使用日
            var rmcd = $("#" + datastr).attr('data-rmcd');		//施設コード
            var rmnm = $("#" + datastr).attr('data-rmnm');		//施設名
            var timekb = $("#" + datastr).attr('data-timekb');	//時間帯区分
            var jkn1 = $("#" + datastr).attr('data-jkn1');		//時間（自）
            var jkn2 = $("#" + datastr).attr('data-jkn2');		//時間（至）
            var tnk = $("#" + datastr).attr('data-tnk');		//時間（至）
            var src = $("#" + imgstr).attr('src');
			
			if (src == 'icon/kara.jpg') {	//空室選択時

                $("#" + imgstr).attr('src', 'icon/sentaku.png');
                //オブジェクトからJSONに直して格納する
                var data = {
                    key: lnkstr,
                    usedt: usedt,
					timekb: timekb,
                    rmcd: rmcd,
                    rmnm: rmnm,
					yobi: yobi,
                    jkn1: jkn1,
                    jkn2: jkn2,
                    jstjkn_h: 0,
                    jstjkn_m: 0,
                    jedjkn_h: 0,
                    jedjkn_m: 0,
                    hstjkn_h: 0,
                    hstjkn_m: 0,
                    hedjkn_h: 0,
                    hedjkn_m: 0,
                    tstjkn_h: 0,
                    tstjkn_m: 0,
                    tedjkn_h: 0,
                    tedjkn_m: 0,
                    tnk: tnk,
					ninzu: 0,
					commercially: 0,
					fee: 0,
					piano: 0,
					partition: 0,
					rmkin: 0,
					hzkin: 0,
                    value: 1
                }
                strlist.push(data);
                //選択中のtdの背景色変更
        //$("#"+ tdstr ).css('background-color', '#f4f984');
                //var list = JSON.parse(localStorage.getItem("sentaku"));//選択リスト
				//9件/月にすべきか
				for ( var i=0; i < strlist.length; i++ ){
					var wkey = strlist[i]['key'].slice(4, 10);			
					//alert(wkey);				
				}
				
				//if(strlist.length>9){
				//	alert("一度に9枠を超えるお申し込みは受け付けできません");
				//}else{
					localStorage.setItem('sentaku', JSON.stringify(strlist));
				//}
            } else {	//選択解除時
				$("#" + imgstr).attr('src', 'icon/kara.jpg');
                strlist.some(function (v, i) {
                    if (v.key == lnkstr) strlist.splice(i, 1); //key:lnkstrの要素を削除
                });
                localStorage.setItem('sentaku', JSON.stringify(strlist));
			}

			//現在の選択件数の更新
			$(".selcnt").text("現在の選択 ： " + strlist.length + "件");
		});

		//選択解除ボタン押下時
		$("#release_select").click(function(){
			
			var wklist = JSON.parse(localStorage.getItem("sentaku"));//ワークリスト
			
			for ( var i=0; i < wklist.length; i++ ){
				
				//画像差し替え
				var  wkey = wklist[i]['key'];			
				var imgstr = wkey.replace('a-', 'img-');			
				$("#" + imgstr).attr('src', 'icon/kara.jpg');
				
				//strlistから一つ一つ要素を削除してゆく
				strlist.some(function (v, i) {
					if (v.key == wkey) strlist.splice(i, 1); //key:lnkstrの要素を削除
				});
				//strlistの更新
				localStorage.setItem('sentaku', JSON.stringify(strlist));
				
			}
			//ローカルストレージstrlistクリア
			//localStorage.removeItem('sentaku', JSON.stringify(strlist));
			//現在の選択件数の更新
			$(".selcnt").text("現在の選択 ： " + strlist.length + "件");
        });

		//フォーム送信時
		$('#yoyaku_form').submit(function()
		{
			//$(this).attr('id');
			//if($(this).attr("id") == "submit_Click"){
				/*本当はボタンをfalseにしたほうがよい*/
				var objData = JSON.parse(localStorage.getItem("sentaku"));//選択リスト
				if( objData == null){
					alert("施設を選択してください");
					return false;
				}
				if( objData.length == 0){
					alert("施設を選択してください");
					return false;
				}
			//}
			return true;
		 });

		//ログアウト時　ローカルストレージクリア
		$(".logout").click(function(){			
			var wklist = JSON.parse(localStorage.getItem("sentaku"));
			
			for ( var i = 0; i < wklist.length; i++ ){
					wklist.splice(i, 1);
			}			
			localStorage.setItem('sentaku', JSON.stringify(wklist));
        });

 });