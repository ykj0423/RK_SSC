jQuery(function () {
	//ローカルストレージクリア
	var wklist = JSON.parse(localStorage.getItem("sentaku"));
	
	for ( var i = 0; i < wklist.length; i++ ){
			wklist.splice(i, 1);
	}			
	localStorage.setItem('sentaku', JSON.stringify(wklist));
});