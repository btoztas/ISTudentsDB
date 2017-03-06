<html>
  <body>
    <center>
      <h1>Ranking mais inscrições numa Unidade Curricular</h1>
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
  echo("<tr><th>Curso</th><th>Número</th><th>Nome</th><th>Unicade Curricular</th><th>Nº Inscrições</th></tr>");
  $sql = "select participant.num as num, student.name as name, participant.name as uc, student.course as course, count(participant.name) as c from participant, student where participant.num=student.num group by participant.num, participant.name having count(participant.name) >= 10 order by count(participant.name) desc";
  $result = $connection->query($sql);
  foreach ($result as $row) {
    $curso = $row['course'];
    $num = $row['num'];
    $name = $row['name'];
    $uc = $row['uc'];
    $c = $row['c'];
    echo("<tr><td>{$curso}</td><td>{$num}</td><td>{$name}</td><td>{$uc}</td><td>{$c}</td></tr>");
  }
  echo("</table>");

  $connection=null;
?>
    </center>
  </body>
</html>
