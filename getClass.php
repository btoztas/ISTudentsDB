<?php

  include 'simple_html_dom.php';

  function insertStudent($connection, $user, $num, $name, $course){
    $sql = "INSERT INTO student VALUES ($num, '$user', '$name', '$course')";
    $nrows = $connection->exec($sql);
    //echo($sql);
  }

  function insertClass($connection, $name, $professor, $year, $semester){
    $sql = "INSERT INTO class VALUES ('$name', '$year', $semester, '$professor')";
    $nrows=$connection->exec($sql);
    //echo($sql);
  }

  function insertStudentClass($connection, $num, $name, $year, $semester){
    $sql = "INSERT INTO participant VALUES ($num, '$name', '$year', $semester)";
    $nrows=$connection->exec($sql);
    //echo($sql);
  }

  function getStudents($connection, $link, $name, $year, $semester){
    $go=0;
    $linkparts = parse_url($link);
    $linkparts = Explode('/', $link);
    if(strcmp($linkparts[2], "fenix.tecnico.ulisboa.pt")==0){
      $studentList = file_get_html("https://".$linkparts[2]."/".$linkparts[3]."/".$linkparts[4]."/".$linkparts[5]."/".$linkparts[6]."/notas");
      foreach ($studentList->find('tr') as $row) {
        if($go){
          $ncell=0;
          foreach($row->find('td') as $cell){
            $info[$ncell] = $cell->innertext;
            $ncell++;
          }
          insertStudent($connection, $info[0], $info[1], $info[2], $info[3]);
          insertStudentClass($connection, $info[1], $name, $year, $semester);
          //echo("<p> {$info[0]} {$info[1]} {$info[2]} {$info[3]} </p>");
        }
        $go=1;
      }
    }else{
      echo("error @ students @ {$link}\n");
    }
  }

  function getClass($connection, $link, $year, $semester){
    $linkparts = parse_url($link);
    $linkparts = Explode('/', $link);
    if(strcmp($linkparts[2], "fenix.tecnico.ulisboa.pt")==0){
      $classLink = "https://".$linkparts[2]."/".$linkparts[3]."/".$linkparts[4]."/".$linkparts[5]."/".$linkparts[6];
      $html = file_get_html($classLink);
      $name=$html->find('a',1)->innertext;
      echo("{$name}\n");
      $professor = array();
      foreach($html->find('div[class="col-sm-11"]') as $div) {
        foreach ($div->find('a') as $a) {
          $professor_name[] = $a->innertext;
        }
      }
      //echo($professor_name[0]);
      insertClass($connection, $name, $professor_name[0], $year, $semester);
      return $name;
    }else{
      echo("error @ class @ {$link}\n");
    }
  }

  function getSemester($connection, $link){
    $html = file_get_html($link);
    foreach ($html->find('div[id="older"]') as $div){
      foreach($div->find('ul[class="list-unstyled"]') as $ul){
        foreach($ul->find('li') as $li){
          foreach ($li->find('a') as $a){
            $semesterText = $a->innertext;
            $semesterLink = $a->href;
            sscanf($semesterText, "%s - %d -- %s", $year, $semester, $courses);
            $linkparts = parse_url($link);
            $linkparts = Explode('/', $link);
            if(strcmp($semesterLink, "http://www.math.ist.utl.pt/~fteix/CI2014_15_1S/")!=0 &&
               strcmp($semesterLink, "http://www.math.ist.utl.pt/~fteix/CI2015_16_1S/")!=0 &&
               strcmp($semesterLink, "http://www.math.ist.utl.pt/~fteix/CI2016_17_1S/")!=0 &&
               strcmp($semesterLink, "http://www.math.ist.utl.pt/~fteix/CI2015_16_2S/")!=0 &&
               strcmp($semesterLink, "http://www.math.ist.utl.pt/~fteix/CI2016_17_1S/")!=0 &&
               strcmp($semesterLink, "http://www.isr.ist.utl.pt/~aguiar/ss")!=0 &&
               strcmp($semesterLink, "http://www.lx.it.pt/~lbalmeida/aauto/")!=0 &&
               strcmp($semesterLink, "http://users.isr.ist.utl.pt/~jag/courses/api15/api1516.html")!=0 &&
               strcmp($linkparts[2], "fenix.tecnico.ulisboa.pt")==0 &&
               strcmp($semesterLink, "https://fenix.tecnico.ulisboa.pt/disciplinas/AC/2009-2010/2-semestre")!=0){
                $name = getClass($connection, $semesterLink, $year, $semester);
                getStudents($connection, $semesterLink, $name, $year, $semester);
            }else{
              echo("error @ semester @ {$semesterLink}\n");
            }
          }
        }
      }
    }
    $parts = parse_url($link);
    $parts = Explode('/', $link);
    sscanf($parts[6], "%d", $semester);
    list($year1, $year2) = Explode('-', $parts[5]);
    $year = "$year1/$year2";
    $name = getClass($connection, $link, $year, $semester);
    getStudents($connection, $link, $name, $year, $semester);
    //echo(" {$year} {$semester}");
  }
  //Database connection
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



  $link = "https://fenix.tecnico.ulisboa.pt/cursos/meec/paginas-de-disciplinas";
  $html = file_get_html($link);
  foreach($html->find('div[id="content-block"]') as $semester){ //content-block 566806834053124 https://fenix.tecnico.ulisboa.pt/cursos/meaer/paginas-de-disciplinas
    foreach($semester->find('li') as $li){
      foreach($li->find('a') as $classLink){
        getSemester($connection, $classLink->href);
      }
    }
  }


  /*
  $link = "https://fenix.tecnico.ulisboa.pt/disciplinas/AC/2009-2010/2-semestre";
  $parts = parse_url($link);
  $parts = Explode('/', $link);
  echo $parts[2];


  $link = "https://fenix.tecnico.ulisboa.pt/disciplinas/MC45179/2016-2017/1-semestre";
  getSemester($connection, $link);
  */
?>
