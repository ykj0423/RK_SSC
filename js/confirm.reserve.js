jQuery(function () {

    //HTMLを初期化  
    $("table.rsv_input tbody.list").html("");
    	
	$('#confirm_form').append($('<input>',{type:'text',name:'meisai_count',value:objData.length}));
	
	$('#submit_prev').click(function(){
		
		$('#confirm_form').attr("action","input.php");
		return true;
	});

	$('#submit_next').click(function(){
		$('#confirm_form').attr("action","end.php");
		return true;
	});

	/* submit */
	$('#confirm_form').submit(function(){
		//バリデーションチェックの結果submitしない場合、return falseすることでsubmitを中止することができる。
		//件数を追加;
		$('#confirm_form').append($('<input>',{type:'hidden',name:'meisai_count',value:objData.length}));
		return true;
	});
	
});