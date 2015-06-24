$(document).ready(function(){
	$("html").mousemove(function(e){
		coordinate = "Mouse Coordinate [ x : " + e.clientX + ", y :" + e.clientY + " ]";
		$("#result").text(coordinate);
		$(".jacob").css({"-webkit-transform": "skew(" + e.clientX + "deg)"});
	})
});