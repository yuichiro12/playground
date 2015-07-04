$(document).ready(function(){
	$("html").mousemove(function(e){
		coordinate = "Mouse Coordinate [ x : " + e.clientX + ", y :" + e.clientY + " ]";
		$("#result").text(coordinate);
		$(".jacob").css({"-webkit-transform": "skew(" + e.clientX + "deg)"});
		$(".jacob2").css({"-webkit-transform": "skew(" + e.clientX / 2 + "deg)"});
		$(".jacob3").css({"-webkit-transform": "skew(" + e.clientX / 3 + "deg)"});
	})
});

window.addEventListener('DOMContentLoaded', function() {
	if (HTMLCanvasElement) {
		var cv = document.querySelector('#cv');
		var c = cv.getContext('2d');
		c.beginPath();
		c.moveTo(15, 15);
		c.lineTo(30, 250);
		c.lineTo(250, 200);
		c.lineTo(280, 130);
		c.lineTo(250, 80);
		c.closePath();
		c.fillStyle = 'Red';
		c.globalAlpha = 0.5;
		c.fill();
	}
});
