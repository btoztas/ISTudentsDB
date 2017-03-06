<html>
  <body>
    <center>
      <h1>Escolhe um aluno</h1>
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
  $name = $_REQUEST['name'];

  $stmt = $connection->prepare("SELECT * FROM student WHERE name LIKE CONCAT('%',:name,'%')");
  $stmt->bindParam(':name', $name);
  $stmt->execute();

  echo("<h3>Encontrados ");
  $nrows = $stmt->rowCount();
  echo $nrows;
  echo(" resultados </h3>");
  echo("<tr><th>Foto</th><th>Número</th><th>Username</th><th>Nome</th><th>Curso</th><th>Find</th></tr>");
  foreach ($stmt as $row) {
    $user = $row['username'];
    $num = $row['num'];
    $name = $row['name'];
    $user = $row['username'];
    $course = $row['course'];
    echo("<tr><td><img src='https://fenix.tecnico.ulisboa.pt/user/photo/{$user}'/></td><td>{$num}</td><td>{$user}</td><td>{$name}</td><td>{$course}</td><td><a href=\"getDataByNum.php?num={$num}\">Encontrar Disciplinas</a></td></tr>");
  }
?>
      </table>
    </center>
  </body>
</html>
