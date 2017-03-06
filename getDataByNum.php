<html>
  <body>
    <center>
      <h1>Resultados</h1>
      <table border="1">
<?php
  $host = "db.ist.utl.pt";
  $user = "ist179069";
  $pass = "XXXXXXX";
  $dsn = "mysql:host=$host;dbname=$user";
  try {
    $connection = new PDO($dsn, $user, $pass);
  }catch(PDOException $exception){
    echo("<p>Error: ");
    echo($exception->getMessage());
    echo("</p>");
    exit();
  }
  $num = $_REQUEST['num'];

  $stmt = $connection->prepare("SELECT username, name FROM student WHERE num = :num");
  $stmt->bindParam(':num', $num);
  $stmt->execute();

  $row = $stmt->fetch();
  $name = $row['name'];
  $user = $row['username'];
  echo("<h2>Aluno {$num} - {$name}</h2><p><img src='https://fenix.tecnico.ulisboa.pt/user/photo/{$user}'/></p>");
  echo("<tr><th>Disciplina</th><th>Ano</th><th>Semestre</th></tr>");

  $stmt = $connection->prepare("SELECT * FROM participant WHERE num = :num order by year, semester");
  $stmt->bindParam(':num', $num);
  $stmt->execute();

  foreach ($stmt as $row) {
    $class = $row['name'];
    $year = $row['year'];
    $semester = $row['semester'];
    echo("<tr><td><a href=\"getCourse.php?course=$class&year=$year&semester=$semester\"> {$class} </a></td><td>{$year}</td><td>{$semester}</td></tr>");
  }
  echo("</table>");

  $stmt = $connection->prepare("SELECT name, count(name) AS c FROM participant WHERE num = :num GROUP BY name HAVING count(name) > 1 order by c DESC");
  $stmt->bindParam(':num', $num);
  $stmt->execute();
  $nrows = $stmt->rowCount();
  if($nrows!=0){
    echo("<h3>Disciplinas com mais do que uma inscrição</h3>");
    echo("<table border=\"1\">");
    echo("<tr><th>Disciplina</th><th>Inscrições</th></tr>");
    foreach ($stmt as $row) {
      $class = $row['name'];
      $c = $row['c'];
      echo("<tr><td>{$class}</td><td>{$c}</td></tr>");
    }
    echo("</table>");
  }
  $connection=null;
?>
    </center>
  </body>
</html>
