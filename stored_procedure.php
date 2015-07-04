<?php

$dbh->exec("
	DROP TABLE IF EXISTS nums;
	CREATE TABLE nums (n INT NOT NULL PRIMARY KEY);
	DROP TABLE IF EXISTS problems;
	CREATE TABLE problems (
		id INT NOT NULL DEFAULT 0,
		row INT NOT NULL,
		col INT NOT NULL,
		val INT NOT NULL,
		CONSTRAINT pro_pri PRIMARY KEY(id, row, col),
		INDEX(id), INDEX(row), INDEX(col), INDEX(val)
	);
	DROP TABLE IF EXISTS candidates;
	CREATE TABLE candidates (
		id INT NOT NULL,
		row INT NOT NULL,
		col INT NOT NULL,
		val INT NOT NULL
	);
");
$stmt = $dbh->prepare("INSERT INTO nums VALUES (?)");
for($i = 1; $i <= $maxNum; $i++){
	$stmt->bindValue(1, $i, PDO::PARAM_STR);
	$stmt->execute();
}

$dbh->exec("
	DROP PROCEDURE IF EXISTS makeCandidates;
	CREATE PROCEDURE makeCandidates(theId INT)
	BEGIN
		SELECT 0 AS id, a.n AS row, b.n AS col, c.n AS val
		FROM nums a, nums b, nums c
		WHERE EXISTS (
			SELECT * FROM problems WHERE val = 0 AND row = a.n AND col = b.n
		)
		AND NOT EXISTS (
			SELECT * FROM problems WHERE val = c.n AND (
				row=a.n OR col=b.n OR (
					(row - 1) DIV @m = (a.n - 1) DIV @m AND (col - 1) DIV @m = (b.n - 1) DIV @m
				)
			)
		);
	END;
");
$dbh->exec("
	DROP PROCEDURE IF EXISTS updateProblem;
	CREATE PROCEDURE updateProblem(theId INT)
	BEGIN
		UPDATE problems JOIN (
			SELECT row, col, val FROM candidates GROUP BY row, col HAVING COUNT(val) = 1
		) tmp ON problems.row = tmp.row AND problems.col = tmp.col
		SET problems.val = tmp.val;
	END;
");
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, 1);
$stmt = $dbh->prepare("
	DROP PROCEDURE IF EXISTS simpleDeduction;

	CREATE PROCEDURE simpleDeduction(theId INT)
	BEGIN
		DECLARE oldResult, newResult INT DEFAULT 0;

		REPEAT
			SET oldResult = newResult;
			CALL makeCandidates(0);
			CALL updateProblem(0);
			SELECT COUNT(*) INTO newResult FROM problems WHERE val = 0;
		UNTIL (newResult = 0 || oldResult = newResult) END REPEAT;
	END;
");
var_dump($stmt->execute());
$dbh->exec("
	DROP PROCEDURE IF EXISTS initialize;

	CREATE PROCEDURE initialize(board TEXT)
	BEGIN
		DELETE FROM problems;
		DELETE FROM candidates;
		SET @maxNum = SQRT(CHAR_LENGTH(board));

		INSERT INTO problems (row,col,val)
			SELECT a.n, b.n, SUBSTRING(board, @maxNum * (a.n - 1) + b.n, 1) + 0
				FROM nums a, nums b;
	END;
");
$dbh->exec("
	DROP PROCEDURE IF EXISTS display;
	CREATE PROCEDURE display(theId INT)
	BEGIN
		DECLARE r, c, v INT;
		DECLARE result TEXT;

		SET result = '';
		SET r = 1;
		WHILE r <= @maxNum DO
			SET c = 1;
			WHILE c <= @maxNum DO
				SELECT val INTO v FROM problems
					WHERE id=theId AND row=r AND col=c;
				SET result = CONCAT(result, v);
				SET c = c+1;
			END WHILE;
			SET r = r+1;
		END WHILE;
		SELECT result;
	END;
");

$dbh->exec("
	DROP FUNCTION IF EXISTS copyProblem;

	CREATE FUNCTION copyProblem(theId INT, r INT, c INT, v INT)
		RETURNS INT MODIFIES SQL DATA
	BEGIN
		DECLARE newId INT;

		SELECT MAX(id) + 1 INTO newId FROM problems;

		INSERT INTO problems
			SELECT newId AS id, row, col, val FROM problems WHERE id = theId;
		UPDATE problems SET val = v WHERE id = newId AND row = r AND col = c;

		RETURN 1;
	END;
");
