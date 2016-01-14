jQuery(function () {
		
	//location.reload();

	/* 申込開始日の計算*/
	var dt = new Date();
	dt.setDate(dt.getDate() + 14 );//申込期限
	var y = dt.getFullYear();
	var m = dt.getMonth() + 1;
	var d = dt.getDate();
	m = (m < 10) ? '0' + m : m ;
	d = (d < 10) ? '0' + d : d ;
	
	var limit = y.toString() + m.toString() + d.toString();

	dt = new Date();
	y = dt.getFullYear();
	m = dt.getMonth() + 4;//1月は0->12月は11
	d = dt.getDate();
	
	y = (m > 12) ? y + 1: y ;
	m = (m > 12) ? m - 12: m ;		
	m = (m < 10) ? '0' + m : m ;
	d = (d < 10) ? '0' + d : d ;

	var limit_hl = y.toString() + m.toString() + d.toString();

	/* 予約件数の復元 */		
	var strlist = new Array();
	var strlist = JSON.parse(localStorage.getItem("sentaku"));//選択リスト
	if(strlist!=null){
		$(".selcnt").text("現在の選択 ： " + strlist.length + "件");
	}else{
		$(".selcnt").text("現在の選択 ： 0件");
	}
        
    jQuery('#date_timepicker_start').datetimepicker({
        format: 'Y/m/d',
        lang: 'ja',
        //startDate: new Date(),
        //defaultDate: new Date(),
        //onShow: function (ct) {
        //    this.setOptions({
        //        maxDate: jQuery('#date_timepicker_end').val() ? jQuery('#date_timepicker_end').val() : false
        //    })
        //},
        timepicker: false
    });
	//初期値は？
	jQuery('#date_timepicker_end').datetimepicker({
        format: 'Y/m/d',
        lang: 'ja',
        //onShow: function (ct) {
        //    this.setOptions({
        //        minDate: jQuery('#date_timepicker_start').val() ? jQuery('#date_timepicker_start').val() : false
        //    })
        //},
        timepicker: false
    });

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

			//やり方ダサすぎる
			calc = 0;
			
			for (var j = 0; j < 8 ; j++) {			
				for (var i = 1; i < 8; i++) {			
					if($("#yobi" + i).prop('checked')) {
						
						calc++;
						
						if(calc>12 ) {
							break;
						}					
					}				
				}
				
				if(calc>12 ) {
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
						
						if(calc>12 ) {
							break;
						}					
					}				
				}
				
				if(calc>12 ) {
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
		var strlist = JSON.parse(localStorage.getItem("sentaku"));//選択リスト
		if( strlist != null){
			for ( var i=0; i < strlist.length; i++ ){
				var  lnkstr = strlist[i]['key'];
				var imgstr = lnkstr.replace('a-', 'img-');
				$("#" + imgstr).attr('src', 'icon/sentaku.png');
			}
		}
		
		/* 空室・選択クリック時 */                                                  
        $("a").click(function () {

			/* 予約状態の復元 */
			var lnkstr = $(this).attr("id");
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
            var oyakokb = $("#" + datastr).attr('data-oyakokb');//親子区分 1:単独 2:親 3:子(ct_oyako)
			var sumrmcd = $("#" + datastr).attr('data-sumrmcd');//集約施設
            var teiin = $("#" + datastr).attr('data-teiin');	//定員
            var src = $("#" + imgstr).attr('src');
			
			if( (rmcd == '301') && ( usedt < limit_hl )){
				alert("ご選択の日付についてはインターネット予約でお申し込みできません。受付窓口までお問い合わせください。");
				return false;
			}
			
			if( (rmcd != '301') && ( usedt < limit )){
				alert("ご選択の日付についてはインターネット予約でお申し込みできません。受付窓口までお問い合わせください。");
				return false;
			}
			
			if(strlist!=null){
				if( strlist.length >= 98 ){
					alert("一度に98件を超えるお申し込みはできません。");
					return false;
				}
			}
			
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
                    jstjkn_h: '',
                    jstjkn_m: '',
                    jedjkn_h: '',
                    jedjkn_m: '',
                    hstjkn_h: '',
                    hstjkn_m: '',
                    hedjkn_h: '',
                    hedjkn_m: '',
                    tstjkn_h: '',
                    tstjkn_m: '',
                    tedjkn_h: '',
                    tedjkn_m: '',
                    tnk: tnk,
					ninzu: '',
					commercially: '',
					fee: '',
					piano: '',
					partition: '',
					oyakokb: oyakokb,
					sumrmcd: sumrmcd,
					teiin: teiin,
					disp: 1,
					rmkin: 0,
					hzkin: 0,
                    value: 1
                }

                if($.isEmptyObject(strlist)){
                	strlist=new Array();
                }
	            
	            strlist.push(data);
                
				localStorage.setItem('sentaku', JSON.stringify(strlist));
				
            } else {	//選択解除

				for(var i in strlist){
					if (strlist[i].key == lnkstr){
            			strlist.splice(i, 1);
            		}            		
        		}
				
                localStorage.setItem('sentaku', JSON.stringify(strlist));

				$("#" + imgstr).attr('src', 'icon/kara.jpg');
			}

			//現在の選択件数の更新
			$(".selcnt").text("現在の選択 ： " + strlist.length + "件");
		});

		//選択解除ボタン押下時
		$("#release_select").click(function(){
			
			for ( var i=0; i < strlist.length; i++ ){				
				//画像差し替え
				$("#" + strlist[i].key.replace('a-', 'img-')).attr('src', 'icon/kara.jpg');
				
			}
						
			strlist = new Array();
			localStorage.setItem('sentaku', JSON.stringify(strlist));

			//ローカルストレージクリア
			//現在の選択件数の更新
			$(".selcnt").text("現在の選択 ： " + strlist.length + "件");

			location.reload();

        });

		//フォーム送信時
		$('#yoyaku_form').submit(function()
		{
			
			if( strlist.length == 0){
				alert("施設を選択してください");
				return false;
			}
			
			//日付チェックリストの作成
			var sort_array = new Array();

			for(var i in strlist){
				
				if(sort_array.indexOf(strlist[i].usedt) == -1){
					sort_array.push(strlist[i].usedt);
					sort_array.sort();
				}

			}

			//施設コードリスト
			var sort_room = new Array();
			
			for(var i in strlist){
				
				if(sort_room.indexOf(strlist[i].rmcd) == -1){
					sort_room.push(strlist[i].rmcd);
					sort_room.sort();
				}

			}

			/*日付不連続チェック*/
			for( var i = 0; i < ( sort_array.length-1 ); i++ ){
				
				var j = i + 1;
				
				var dateMsec_j = new Date( sort_array[j].substr(4,2) + '/' +sort_array[j].substr(6,2) + '/' + sort_array[j].substr(0,4)).getTime();
				var dateMsec_i = new Date( sort_array[i].substr(4,2) + '/' +sort_array[i].substr(6,2) + '/' + sort_array[i].substr(0,4)).getTime();
				var interval = Math.floor( ( dateMsec_j - dateMsec_i) / (1000 * 60 * 60 * 24) );

				if( parseInt(interval) > 1 ){
					alert("規定により、連続していないお申込みは受け付けできません。一日単位でお申し込みください。");
					return false;
					break;
				}
				
			}
		
			/* 施設ごとのの不連続チェック*/
			var discontinuity = true;//不連続フラグ

			for(var i in sort_room){
			
				var room_usedt_array = new Array();
				
				for(var j in strlist){

					if(sort_room[i] == strlist[j].rmcd){

						if( room_usedt_array.length == 0 ){
							room_usedt_array.push( strlist[j].usedt );	
						}else if( $.inArray( strlist[j].usedt, room_usedt_array ) == -1 ){
							room_usedt_array.push( strlist[j].usedt );
							room_usedt_array.sort();				
						}
	
					}
				
				}

				if( sort_array.toString() != room_usedt_array.toString() ){
					discontinuity = false;
					break;
				}	

			}
				
			if( !discontinuity ){
				alert("規定により、連続した複数日、複数施設のお申込みは受け付けできません。一日単位でお申込みいただくか、一施設単位でお申込みください。");
				return false;
			}


			if( sort_array.length > 7 ){
				alert("規定により、連続して7日を超えるお申込みは受け付けできません。７日以内でお申し込みください。");
				return false;
			}

			return true;
		 
		 });

		//ログアウト時ローカルストレージクリア
		$(".logout").click(function(){			
			var wklist = JSON.parse(localStorage.getItem("sentaku"));
			
			for ( var i = 0; i < wklist.length; i++ ){
					wklist.splice(i, 1);
			}			
			
			localStorage.setItem('sentaku', JSON.stringify(wklist));
        
        });

 });