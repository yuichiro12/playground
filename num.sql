DROP TABLE IF EXISTS problems;

CREATE TABLE problems (
  id INT NOT NULL DEFAULT 0,
  row INT NOT NULL,
  col INT NOT NULL,
  val INT NOT NULL,
  CONSTRAINT pro_pri PRIMARY KEY(id,row,col),
  INDEX (id), INDEX (row), INDEX (col), INDEX (val)
);

DROP TABLE IF EXISTS candidates;

CREATE TABLE candidates (
  id INT NOT NULL,
  row INT NOT NULL,
  col INT NOT NULL,
  val INT NOT NULL,
  INDEX (id), INDEX (row), INDEX (col), INDEX (val)
);


DROP TABLE IF EXISTS nums;

CREATE TABLE nums (n INT NOT NULL PRIMARY KEY);


DROP PROCEDURE IF EXISTS makeNumSeq;

CREATE PROCEDURE makeNumSeq(maxNum INT)
BEGIN
  DECLARE i INT DEFAULT 1;
  DELETE FROM nums;
  WHILE i<=maxNum DO
    INSERT INTO nums (n) VALUES (i);
    SET i=i+1;
  END WHILE;
END;;


DROP PROCEDURE IF EXISTS initialize;

CREATE PROCEDURE initialize(board TEXT)
BEGIN
  DELETE FROM problems;
  DELETE FROM candidates;
  SET @maxNum=SQRT(CHAR_LENGTH(board));
  SET @m=SQRT(@maxNum);
  CALL makeNumSeq(@maxNum);

  INSERT INTO problems (row,col,val)
    SELECT a.n,b.n,SUBSTRING(board,@maxNum*(a.n-1)+b.n,1)+0 FROM nums a,nums b;
END;;


DROP PROCEDURE IF EXISTS display;

CREATE PROCEDURE display(theId INT)
BEGIN
  DECLARE r,c,v INT DEFAULT 1;
  DECLARE result TEXT;

  SET result='\n';
  WHILE r<=@maxNum DO
    SET c=1;
    WHILE c<=@maxNum DO
      SELECT val INTO v FROM problems WHERE id=theId AND row=r AND col=c;
      SET result=CONCAT(result,v,' ');
      SET c=c+1;
    END WHILE;
    SET result=CONCAT(result,'\n');
    SET r=r+1;
  END WHILE;
  SELECT result;
END;;




DROP PROCEDURE IF EXISTS eval;

CREATE PROCEDURE eval(t text)
BEGIN
  SET @stmt=t;
  PREPARE stmt FROM @stmt;
  EXECUTE stmt;
  DEALLOCATE PREPARE stmt;
END;


DROP FUNCTION IF EXISTS makeSelect;

CREATE FUNCTION makeSelect(theId INT) RETURNS TEXT READS SQL DATA
BEGIN
  DECLARE done INT DEFAULT 0;
  DECLARE stmt TEXT;
  DECLARE r,c,v INT;
  DECLARE label VARCHAR(10);
  DECLARE cur CURSOR FOR SELECT row,col,val FROM problems WHERE id=theId;
  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

  SET stmt='SELECT ';
  OPEN cur;
  REPEAT
    FETCH cur INTO r,c,v;
    IF NOT done THEN
      SET label=CONCAT('R',r,'C',c);
      SET stmt=CONCAT(stmt,IF(v!=0,v,CONCAT('t',label,'.n')),' AS ',label,',');
    END IF;
  UNTIL done END REPEAT;
  CLOSE cur;
  RETURN SUBSTRING(stmt,1,LENGTH(stmt)-1);          -- 最後の余計なカンマを削除
END;


DROP FUNCTION IF EXISTS makeFrom;

CREATE FUNCTION makeFrom(theId INT) RETURNS TEXT READS SQL DATA
BEGIN
  DECLARE done INT DEFAULT 0;
  DECLARE stmt TEXT;
  DECLARE r,c INT;
  DECLARE cur CURSOR FOR SELECT row,col FROM problems WHERE id=theId AND val=0 ORDER BY row,col;
  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

  SET stmt=' FROM ';
  OPEN cur;
  REPEAT
    FETCH cur INTO r,c;
    IF NOT done THEN
      SET stmt=CONCAT(stmt,'nums tR',r,'C',c,',');
    END IF;
  UNTIL done END REPEAT;
  CLOSE cur;
  RETURN SUBSTRING(stmt,1,LENGTH(stmt)-1);          -- 最後の余計なカンマを削除
END;


DROP TABLE IF EXISTS whereItems;

CREATE TABLE whereItems (item VARCHAR(20));


DROP FUNCTION IF EXISTS makeWhere;

CREATE FUNCTION makeWhere(theId INT) RETURNS TEXT READS SQL DATA
BEGIN
  DECLARE done INT DEFAULT 0;
  DECLARE stmt TEXT;
  DECLARE aRow,aCol,aVal,bRow,bCol,bVal INT;
  DECLARE item1,item2,itemTmp CHAR(20);
  DECLARE cur CURSOR FOR
    SELECT a.row,a.col,a.val,b.row,b.col,b.val
    FROM problems a,problems b
    WHERE a.id=theId AND b.id=theId
      AND (a.val=0 OR b.val=0)
      AND (a.row=b.row AND a.col!=b.col
        OR a.row!=b.row AND a.col=b.col
        OR ((a.row!=b.row OR a.col!=b.col)
          AND (a.row-1) DIV @m=(b.row-1) DIV @m
          AND (a.col-1) DIV @m=(b.col-1) DIV @m));
  DECLARE cur2 CURSOR FOR SELECT DISTINCT item FROM whereItems ORDER BY item;
  DECLARE CONTINUE HANDLER FOR SQLSTATE '02000' SET done = 1;

  DELETE FROM whereItems;
  OPEN cur;
  REPEAT
    FETCH cur INTO aRow,aCol,aVal,bRow,bCol,bVal;
    IF NOT done THEN
      SET item1=IF(aVal!=0,aVal,CONCAT('tR',aRow,'C',aCol,'.n'));
      SET item2=IF(bVal!=0,bVal,CONCAT('tR',bRow,'C',bCol,'.n'));
      IF STRCMP(item1,item2)<0 THEN                                  -- ペアを辞書順で並び替える。
        INSERT INTO whereItems VALUES (CONCAT(item1,'!=',item2));
      ELSE
        INSERT INTO whereItems VALUES (CONCAT(item2,'!=',item1));
      END IF;
    END IF;
  UNTIL done END REPEAT;
  CLOSE cur;

  SET stmt=' WHERE ';          -- 上で作成した条件をつなぎ合わせてWHERE節を作る。
  SET done=0;
  OPEN cur2;
  REPEAT
    FETCH cur2 INTO itemTmp;
    IF NOT done THEN
      SET stmt=CONCAT(stmt,itemTmp,' AND ');
    END IF;
  UNTIL done END REPEAT;
  CLOSE cur2;

  RETURN SUBSTRING(stmt,1,LENGTH(stmt)-5);
END;


DROP FUNCTION IF EXISTS makeSql;

CREATE FUNCTION makeSql(theId INT) RETURNS TEXT READS SQL DATA
BEGIN
  RETURN CONCAT(makeSelect(theId),makeFrom(theId),makeWhere(theId));
END;


CALL initialize(CONCAT(
'1    7 9 ',
' 3  2   8',
'  96  5  ',
'  53  9  ',
' 1  8   2',
'6    4   ',
'3      1 ',
' 4      7',
'  7   3  '));

RESET QUERY CACHE;
DROP TABLE IF EXISTS tmp;
CALL eval(CONCAT('CREATE TABLE tmp ',makeSql(0)));


ALTER TABLE tmp ADD id INT AUTO_INCREMENT PRIMARY KEY;


DROP PROCEDURE IF EXISTS extractResult;

CREATE PROCEDURE extractResult(theId INT)
BEGIN
  DECLARE i,r,c INT;

  SET i=0;
  REPEAT
    SELECT MIN(id) INTO i FROM tmp WHERE id>i;  -- テーブルtmpを1行ずつ処理する。
    IF i IS NOT NULL THEN
      SET r=1;
      WHILE r<=@maxNum DO
        SET c=1;
        WHILE c<=@maxNum DO
          CALL eval(CONCAT(                     -- 解をテーブルproblemsに埋め込む。
            'UPDATE problems SET val=(SELECT R',r,'C',c,' FROM tmp WHERE id=',i,') ',
            'WHERE id=',theId,' AND row=',r,' AND col=',c));
          SET c=c+1;
        END WHILE;
        SET r=r+1;
      END WHILE;
      CALL display(theId);                      -- 盤面表示用のプロシジャを呼び出す。
    END IF;
  UNTIL i IS NULL END REPEAT;
END;


CALL extractResult(0);
