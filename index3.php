<?php
include "database.php";

$prob = $_POST;

$maxNum = 9;
$m = (int) sqrt($maxNum);

// SQL文のパーツを格納する配列
$selectItems = [];
$fromItems = [];
$whereItems = [];

// 結果取得用配列
$result = [];

// SQL文のパーツを上で用意した配列に格納
for($i = 1; $i <= 9; $i++){
	for($j = 1; $j <= 9; $j++){
		$label1 = "";
		$label2 = "";
		$item1 = "";
		$item2 = "";

		$label1 = 'R'. $i. 'C'. $j;
		$item1 = ($prob[$label1] >= 1 && $prob[$label1] <= 9) ? $prob[$label1] : "t${label1}.n";
		array_push($selectItems, $item1. ' AS '. $label1);
		if(!($prob[$label1] >= 1 && $prob[$label1] <= 9)){
			array_push($fromItems, "nums t". $label1);
		}
		for($k = 1; $k <= 9; $k++){
			for($l = 1; $l <= 9; $l++){
				$label2 = 'R'. $k. 'C'. $l;
				if(!($prob[$label1] >= 1 && $prob[$label1] <= 9) || !($prob[$label2] >= 1 && $prob[$label2] <= 9)){
					if(($i !== $k && $j === $l)
						|| ($i === $k && $j !== $l)
						|| (($i !== $k && $j !== $l) && (int)(($i - 1) / $m) === (int)(($k - 1) / $m) && (int)(($j - 1) / $m) === (int)(($l - 1) / $m))){
						if($prob[$label2] >= 1 && $prob[$label2] <= 9){
							$item2 = $prob[$label2];
						}else{
							$item2 = 't'. $label2. '.n';
						}

						array_push($whereItems, $item1. "!=". $item2);
					}
				}
			}
		}
	}
}

try{
	$dbh = new PDO($dsn, $user, $password);
	$sql = "SELECT STRAIGHT_JOIN ". implode(",", $selectItems). " FROM ". implode(",", $fromItems). " WHERE ". implode(" AND ", $whereItems);
	$stmt = $dbh->prepare("DROP TABLE IF EXISTS nums");
	$stmt->execute();
	$stmt = $dbh->prepare("CREATE TABLE nums (n INT NOT NULL PRIMARY KEY)");
	$stmt->execute();
	$stmt = $dbh->prepare("INSERT INTO nums VALUES (?)");
	for($i = 1; $i <= $maxNum; $i++){
		$stmt->bindValue(1, $i, PDO::PARAM_STR);
		$stmt->execute();
	}
	$stmt = $dbh->prepare($sql);
	$stmt->execute();
	$result = $stmt->fetch();
}catch (PDOException $e){
	print('Error:'.$e->getMessage());
	die();
}

$dbh = null;
?>

<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>sudoku solver</title>
	<link rel="stylesheet" type="text/css" href="test3.css">
</head>
<body>
<script src="jquery-2.1.4.min.js"></script>
<script src="numcolor.js"></script>
<script src="button.js"></script>
<script src="move-shadow.js"></script>
<div id="container">
<header>
	<h2>sudoku solver</h2>
</header>
<form action="/playground/index3.php" method="POST">
<input type="submit" class="buttons submit" value="resolve">
<a href="javascript:void(0)" class="buttons clear">clear</a>
	<table class="table">
		<tbody>
		<?php for($i = 1; $i <= 9; $i++): ?>
			<tr>
			<?php for($j = 1; $j <= 9; $j++): ?>
				<td>
					<input type="text" name="<?php echo('R'. $i. 'C'. $j);?>" class="cell" maxlength="1" value="<?php
						echo(isset($result['R'. $i. 'C'. $j]) ? $result['R'. $i. 'C'. $j] : '');?>" style="<?php
						echo((($i === 3 || $i === 6) ? 'border-bottom: solid 2px #333' : '')
							.';'
							.(($j === 3 || $j === 6) ? 'border-right: solid 2px #333' : ''));  ?>">
				</td>
			<?php endfor; ?>
			</tr>
		<?php endfor; ?>
		</tbody>
	</table>
	</form>
	<table class="table shadow">
		<tbody>
		<?php for($i = 1; $i <= 9; $i++): ?>
			<tr>
			<?php for($j = 1; $j <= 9; $j++): ?>
				<td>
					<input type="text" name="<?php echo('dummy-R'. $i. 'C'. $j);?>" class="cell" maxlength="1" value="<?php
						echo(isset($result['R'. $i. 'C'. $j]) ? $result['R'. $i. 'C'. $j] : '');?>" style="<?php
						echo((($i === 3 || $i === 6) ? 'border-bottom: solid 2px #333' : '')
							.';'
							.(($j === 3 || $j === 6) ? 'border-right: solid 2px #333' : ''));  ?>">
				</td>
			<?php endfor; ?>
			</tr>
		<?php endfor; ?>
		</tbody>
	</table>
</div>
<footer>
	<hr>
	<p class="copyright">Copyright© mov, 2014 All Rights Reserved.</p>
</footer>
</body>
</html>
