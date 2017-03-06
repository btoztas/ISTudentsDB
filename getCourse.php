<html>
  <body>
    <center>

<?php
  $host = "db.ist.utl.pt";
  $user = "ist179069";
  $pass = "XXXXX";
  $dsn = "mysql:host=$host;dbname=$user";
  try {
    $connection = new PDO($dsn, $user, $pass);
  }catch(PDOException $exception){
    echo("<p>Error: ");
    echo($exception->getMessage());
    echo("</p>");
    exit();
  }

  $course = $_REQUEST['course'];
  $year = $_REQUEST['year'];
  $semester = $_REQUEST['semester'];
  $stmt = $connection->prepare("SELECT participant.num as nb, student.name as name, student.username as user, student.course as course from participant, student where student.num = participant.num AND participant.name = :course AND participant.year = :year AND participant.semester = :semester");
  $stmt->bindParam(':course', $course);
  $stmt->bindParam(':year', $year);
  $stmt->bindParam(':semester', $semester);
  $stmt->execute();
  echo("<h1> {$course} </h1>");
  echo("<h2> {$year} - {$semester}º Semestre </h2>");

  echo("<h3>Existem ");
  $nrows = $stmt->rowCount();
  echo $nrows;
  echo(" alunos</h3>");
  echo("<table border=\"1\"><tr><th>Foto</th><th>Número</th><th>Nome</th><th>Curso</th><th>Disciplinas do Aluno</th></tr>");
  foreach ($stmt as $row) {
    $num = $row['nb'];
    $name = $row['name'];
    $course = $row['course'];
    $user = $row['user'];
    echo("<tr><td><img src='https://fenix.tecnico.ulisboa.pt/user/photo/{$user}'/></td><td>{$num}</td><td>{$user}</td><td>{$name}</td><td>{$course}</td><td><a href=\"getDataByNum.php?num={$num}\">Encontrar Disciplinas</a></td></tr>");
  }
?>
      </table>
    </center>
  </body>
</html>
