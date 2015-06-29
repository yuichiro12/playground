<?php echo("hello<br>");

$problem = [
	[1,0,0,0,0,7,0,9,0],
	[0,3,0,0,2,0,0,0,8],
	[0,0,9,6,0,0,5,0,0],
	[0,0,5,3,0,0,9,0,0],
	[0,1,0,0,8,0,0,0,2],
	[6,0,0,0,0,4,0,0,0],
	[3,0,0,0,0,0,0,1,0],
	[0,4,0,0,0,0,0,0,7],
	[0,0,7,0,0,0,3,0,0],
];

$dsn = 'mysql:dbname=nums;host=localhost;charset=utf8';
$user = 'root';
$password = '7q2Q1204';

phpinfo();
	error_log(2);
	error_log(2);
	error_log(2);
	error_log(2);
	error_log(2);
	error_log(2);
	error_log(2);
	error_log(2);
	error_log(2);

try{
	$dbh = new PDO($dsn, $user, $password);
	$stmt = $dbh->prepare(file_get_contents("num.sql"));
	$stmt->execute(array($_GET['result']));
	while($row = $stmt->fetch()){
		var_dump($_GET['result']);
		var_dump($row);
		echo("<br>");
		echo('success');
	}
}catch (PDOException $e){
	print('Error:'.$e->getMessage());
	die();
}

$dbh = null;