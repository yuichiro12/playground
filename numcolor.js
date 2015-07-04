$(document).ready(function(){
	for(i = 0; i < 81; i++){
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
				$('.cell').eq(i).css({'color': 'yellow'});
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
				$('.cell').eq(i).css({'color': 'turquoise'});
				break;
		}
	}
	$('.cell').on('change', function(){
		for(i = 0; i < 81; i++){
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
					$('.cell').eq(i).css({'color': 'yellow'});
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
					$('.cell').eq(i).css({'color': 'pink'});
					break;
			}
		}
	})
});