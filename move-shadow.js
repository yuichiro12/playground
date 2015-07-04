$(document).ready(function(){
	$("html").mousemove(function(e){
		$(".shadow").css({"-webkit-transform": " translate(" + (-3.0 - 84.0 / Math.tan(120 + (e.clientX / 17.3) * Math.PI / 180)) + "px) skew(" + (120 + (e.clientX) / 16) + "deg) scale(1, -0.4)"});
		// $(".shadow").css({"-moz-transform": " translate(" + (-3.0 - 84.0 / Math.tan(120 + (e.clientX / 17.3) * Math.PI / 180)) + "px) skew(" + (120 + (e.clientX) / 16) + "deg) scale(1, -0.4)"});
		// $(".shadow").css({"-o-transform": " translate(" + (-3.0 - 84.0 / Math.tan(120 + (e.clientX / 17.3) * Math.PI / 180)) + "px) skew(" + (120 + (e.clientX) / 16) + "deg) scale(1, -0.4)"});
	});
});