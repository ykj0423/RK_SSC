<!--
window.onload = function(){
myClock.time();
setInterval("myClock.time()", 1000);
}
var myClock = {
time : function(){
var dateObj = new Date();
var yy = dateObj.getFullYear();
var mm = dateObj.getMonth() + 1;
var dd = dateObj.getDate();


var h = dateObj.getHours();
var m = dateObj.getMinutes();
var s = dateObj.getSeconds();
document.getElementById("currentTime").innerHTML = yy + "/" + mm + '/' + dd + '  ' +  h+":"+m+":"+s+"";
}
}
// -->