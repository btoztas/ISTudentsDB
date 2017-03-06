DROP TABLE IF EXISTS participant;
DROP TABLE IF EXISTS student;
DROP TABLE IF EXISTS class;

CREATE TABLE student(
  num integer,
  username varchar(255),
  name varchar(255),
  course varchar(255),
  PRIMARY KEY (num)
);


CREATE TABLE class(
  name varchar(255),
  year varchar(255),
  semester integer,
  professor varchar(255),
  PRIMARY KEY (name, year, semester)
);

CREATE TABLE participant(
  num integer,
  name varchar(255),
  year varchar(255),
  semester integer,
  PRIMARY KEY (num, name, year, semester),
  FOREIGN KEY (num) REFERENCES student (num),
  FOREIGN KEY (name, year, semester) REFERENCES class (name, year, semester)
);

/*
DROP TRIGGER IF EXISTS check_student;
DELIMITER $$
CREAT TRIGGER check_student before INSERT ON student
FOR EACH ROW
BEGIN
  IF EXISTS (SELECT num FROM student WHERE num = new.num) THEN
    CALL student_already_exists();
  END IF;
END$$
DELIMITER ;
*/
