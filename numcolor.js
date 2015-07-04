$(document).ready(function(){
	for(i = 0; i < 162; i++){
		switch($('.cell').eq(i).val()){
			case '1':
				$('.cell').eq(i).css({'color': 'black'});
				break;
			case '2':
				$('.cell').eq(i).css({'color': 'blue'});
				break;
			case '3':
				$('.cell').eq(i).css({'color': 'red'});
				break;
			case '4':
				$('.cell').eq(i).css({'color': 'orange'});
				break;
			case '5':
				$('.cell').eq(i).css({'color': 'green'});
				break;
			case '6':
				$('.cell').eq(i).css({'color': 'magenta'});
				break;
			case '7':
				$('.cell').eq(i).css({'color': 'lightgreen'});
				break;
			case '8':
				$('.cell').eq(i).css({'color': 'cyan'});
				break;
			case '9':
				$('.cell').eq(i).css({'color': 'gold'});
				break;
		}
	}
	$('.cell').keyup(function(event){
		var n = $('.cell').index(this);
		var obj = $('.cell').eq(n);
		switch(obj.val()){
			case '1':
				obj.css({'color': 'black'});
				break;
			case '2':
				obj.css({'color': 'blue'});
				break;
			case '3':
				obj.css({'color': 'red'});
				break;
			case '4':
				obj.css({'color': 'orange'});
				break;
			case '5':
				obj.css({'color': 'green'});
				break;
			case '6':
				obj.css({'color': 'magenta'});
				break;
			case '7':
				obj.css({'color': 'lightgreen'});
				break;
			case '8':
				obj.css({'color': 'cyan'});
				break;
			case '9':
				obj.css({'color': 'gold'});
				break;
			case '':
				obj.css({'color': 'black'});
				break;
		}
		if(n < 81){
			$('.cell').eq(n + 81).val(obj.val()).css({'color': obj.css('color')});
		}else{
			$('.cell').eq(n - 81).val(obj.val()).css({'color': obj.css('color')});
		}
	})
});