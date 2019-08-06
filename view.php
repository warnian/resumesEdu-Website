
<html>
<title>Ian Warn View Page</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
<head></head><body>
<?php 

require_once "pdo.php";
$fail=false;
session_start();
include "helpers.php";
if (!isset($_SESSION["success"])){
    die("Not logged in");
}

?>
<p><font size='20'>Profile infromation</font>  </p>

<?php
$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$profID = $_GET['profile_id'];

while ($row=$stmt->fetch(PDO::FETCH_ASSOC)){
    echo('<p>First Name: '.htmlentities($row['first_name']).'</p>');
    echo('<p>Last Name: '.htmlentities($row['last_name']).'</p>');
    echo('<p>Email: '.htmlentities($row['email']).'</p>');
    echo('<p>Headline:</p><p>'.htmlentities($row['headline']).'</p>');
    echo('<p>Summary:</p><p>'.htmlentities($row['summary']).'</p>');
    $stmt2 = $pdo->query("SELECT year, description FROM Position where profile_id=$profID");
    $countPos = 0;
    $countEdu  = 0;
    echo('<ul>Education');
    
    
    
foreach(getEducation($pdo, $profID) as $row){
    $countEdu++;
    if ($row["year"]!==NULL){
        $year = $row["year"];
        $name = $row['name'];
       echo('<div id="education'.$countEdu.'"> 
            <li>'.$year.': '.$name.' </li>
            </div>');
    }
}

echo('</ul>');
    echo('<ul>Position');
    while ( $row = $stmt2->fetch(PDO::FETCH_ASSOC) ) {
        $countPos++;
        if ($row["year"]!==NULL){
            $year = $row["year"];
            $desc = $row['description'];
          echo('<div id="position'.$countPos.'"> 
                <li>'.$year.': '.$desc.' </li>
                </div>');
        }
    } 
    echo('</ul>');
}

?>
<p>
<a href="index.php">Done</a> 
</p>
</body>