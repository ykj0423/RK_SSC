
window.onload = function(){//<head>にスクリプトを配置する場合は行先頭のコメントアウトを外す
if(document.getElementById && document.all && !navigator.userAgent.match(/Opera/)){
	var obj = document.getElementById("navi_menu");
	for(var i=0;i<obj.childNodes.length;i++) {
		if(obj.childNodes[i].className=="plist") {
			obj.childNodes[i].onmouseover = function(){pull(this)};
			obj.childNodes[i].onmouseout = function(){pull(this)};
		}
	}
}

}//<head>にスクリプトを配置する場合は行先頭のコメントアウトを外す

function pull(obj){
	for(var i=0;i<obj.childNodes.length;i++)
		if(obj.childNodes[i].nodeName.toUpperCase()=="UL")
		obj.childNodes[i].style.display=obj.childNodes[i].style.display=="block"?"none":"block";
	}


