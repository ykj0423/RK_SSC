
var google_init = "�T�C�g������";

function chgGoogle(style_type) {

	var target = document.getElementById('search_text');

	if (style_type) {
		setElementClassById('search_text', 'search_text_active');
		if (target.value == google_init) { target.value = ""; }
	} else {
		setElementClassById('search_text', 'search_text_init');
		if (target.value == "") { target.value = google_init; }
	}

};

//class�v�f�̒ǉ��E�ύX
function setElementClassById(elem, value) {
	if(document.getElementById) {
		var obj = document.getElementById(elem);
		if(obj) {
			obj.className = value;
		}
	}
}
chgGoogle(false);

/*
var changed = false;
function chgGoogle(){
   if(changed){
      setElementClass('dmyimg','init');
   }else{
      setElementClass('dmyimg','active');
   }
   changed= (changed)?false:true;
}
//class�v�f�̒ǉ��E�ύX
function setElementClassById(elem, value) {
   if(document.getElementById) {
      var obj = document.getElementById(elem);
      if(obj) {
         obj.className = value;
      }
   }
}
addListener(document.getElementById('dmyimg'), 'click', chgGoogle(), false);
*/
