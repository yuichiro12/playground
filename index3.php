<?php
include "database.php";

$nums = "";
$selectItems = [];
$fromItems = [];
$whereItems = [];


for($i = 1; $i <= 9; $i++){
	for($j = 1; $j <= 9; $j++){
		$label1 = 'R'. $i. 'C'. $j;
		$item1 = ($_POST["$i-$j"] >= 1 && $_POST["$i-$j"] <= 9) ? $_POST["$i-$j"] : " ";
		$nums .= ($_POST["$i-$j"] >= 1 && $_POST["$i-$j"] <= 9) ? $_POST["$i-$j"] : " ";
		array_push($selectItems, $item1. 'AS'. $label1);
	}
}

// $nums=" 4 7 9 5 3 6 3291 4 16387925587 892653147 1 24 68 54 71293";

try{
	$dbh = new PDO($dsn, $user, $password);
	echo('success');
}catch (PDOException $e){
	print('Error:'.$e->getMessage());
	die();
	echo('failed');
}

$dbh = null;
echo($nums);

?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
	<link rel="stylesheet" type="text/css" href="test3.css">
</head>
<body>
<form action="/playground/index3.php" method="POST">
<input type="submit">
<input type="reset">
<hr>
	<table class="table">
		<tbody class="table">
		<?php for($i = 0; $i < 3; $i++): ?>
			<tr>
			<?php for($j = 0; $j < 3; $j++): ?>
				<td>
					<table class="table-inner">
						<tbody class="tbody-inner">
						<?php for($k = 0; $k < 3; $k++): ?>
							<tr>
							<?php for($l = 0; $l < 3; $l++): ?>
								<td><input type="text" name="<?php echo(($i*3+$k+1). '-'. ($j*3+$l+1));?>" class="cell" maxlength="1"></td>
							<?php endfor; ?>
							</tr>
						<?php endfor; ?>
						</tbody>
					</table>
				</td>
			<?php endfor; ?>
			</tr>
		<?php endfor; ?>
		</tbody>
	</table>
</form>
</body>
</html>