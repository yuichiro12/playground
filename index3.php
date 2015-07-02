<?php
include "database.php";

$nums = "";
$maxNum = 9;
$m = sqrt($maxNum);

// SQL文のパーツを格納する配列
$selectItems = [];
$fromItems = [];
$whereItems = [];


// SQL文のパーツを上で用意した配列に格納
for($i = 1; $i <= 9; $i++){
	for($j = 1; $j <= 9; $j++){
		$label1 = "";
		$label2 = "";
		$item1 = "";
		$item2 = "";

		$label1 = 'R'. $i. 'C'. $j;
		$item1 = ($_POST["$i-$j"] >= 1 && $_POST["$i-$j"] <= 9) ? $_POST["$i-$j"] : "t${label1}.n";
		array_push($selectItems, $item1. ' AS '. $label1);
		if(!($_POST["$i-$j"] >= 1 && $_POST["$i-$j"] <= 9)){
			array_push($fromItems, "nums t". $label1);
		}
		for($k = 1; $k <= 9; $k++){
			for($l = 1; $l <= 9; $l++){
				if(!($_POST["$i-$j"] >= 1 && $_POST["$i-$j"] <= 9) || !($_POST["$k-$l"] >= 1 && $_POST["$k-$l"] <= 9)){
					if(($i !== $k && $j === $l)
						|| ($i === $k && $j !== $l)
						|| (($i !== $k && $j !== $l) && ($i / $m === $k / $m) && ($j / $m === $l / $m))){
						$label2 = 'R'. $k. 'C'. $l;
						if($_POST["$k-$l"] >= 1 && $_POST["$k-$l"] <= 9){
							$item2 = $_POST["$k-$l"];
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
	var_dump($stmt->execute());
	var_dump($dbh->errorInfo());
	var_dump($stmt->fetch());

	echo('success');
}catch (PDOException $e){
	print('Error:'.$e->getMessage());
	die();
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