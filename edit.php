<html>
<title>Ian Warn Edit Page</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
<head></head><body>
<?php
include "helpers.php";
require_once "pdo.php";
session_start();

if ( isset($_POST['first_name']) && isset($_POST['last_name'])
     && isset($_POST['email']) && isset($_POST['headline'])) {

    // Data validation
    if ( isset($_POST['first_name'])&&strlen($_POST['first_name'])<1){
        $_SESSION['error'] = 'Missing first_name';
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }

    if ( isset($_POST['last_name'])&&strlen($_POST['last_name'])<1){
        $_SESSION['error'] = 'Missing last_name';
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }
    if ( isset($_POST['email'])&&strlen($_POST['email'])<1){
        $_SESSION['error'] = 'Missing email';
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }
    if (strpos($_POST["email"],'@')===false && strlen($_POST["email"]) > 0){
        
        $_SESSION["error"]="Email must have an at-sign (@)";
      
    
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }
    if ( isset($_POST['headline'])&&strlen($_POST['headline'])<1){
        $_SESSION['error'] = 'Missing headline';
        header("Location: edit.php?profile_id=".$_POST['profile_id']);
        return;
    }
    

    $sql = "UPDATE Profile SET first_name = :first_name,
            last_name = :last_name, email= :email, headline = :headline, summary = :summary
            WHERE profile_id = :profile_id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(
        ':first_name' => $_POST['first_name'],
        ':last_name' => $_POST['last_name'],
        ':email' => $_POST['email'],
        ':headline' => $_POST['headline'],
        ':summary' => $_POST['summary'],
        ':profile_id'=>$_POST['profile_id']));
    $_SESSION['success'] = 'Record updated';
    // Clear out the old position entries
    $stmt = $pdo->prepare('DELETE FROM Position
        WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
        
    // Insert the position entries
    $rank = 1;
    for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

        $stmt = $pdo->prepare('INSERT INTO Position
            (profile_id, rank, year, description)
        VALUES ( :pid, :rank, :year, :desc)');
        $stmt->execute(array(
            ':pid' => $_REQUEST['profile_id'],
            ':rank' => $rank,
            ':year' => $year,
            ':desc' => $desc)
        );
        $rank++;
    }
    //delte educaction
    $stmt = $pdo->prepare('DELETE FROM Education
        WHERE profile_id=:pid');
    $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
    insertEducation($pdo,$_REQUEST['profile_id']);
    header( 'Location: index.php' ) ;
    return;
}

// Guardian: first_name sure that profile_id is present
if ( ! isset($_GET['profile_id']) ) {
  $_SESSION['error'] = "Missing profile_id";
  header('Location: index.php');
  return;
}

$stmt = $pdo->prepare("SELECT * FROM profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row === false ) {
    $_SESSION['error'] = 'Bad value for profile_id';
    header( 'Location: index.php' ) ;
    return;
}

// Flash pattern
if ( isset($_SESSION['error']) ) {
    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
    unset($_SESSION['error']);
}

$first_name = htmlentities($row['first_name']);
$last_name = htmlentities($row['last_name']);
$email = htmlentities($row['email']);
$headline = htmlentities($row['headline']);
$summary = htmlentities($row['summary']);
$profile_id = $row['profile_id'];

?>
<h1>Edit Profile</h1>
<form method="post">
<p>first_name:
<input type="text" name="first_name" value="<?= $first_name ?>"></p>
<p>last_name:
<input type="text" name="last_name" value="<?= $last_name ?>"></p>
<p>email:
<input type="text" name="email" value="<?= $email ?>"></p>
<p>headline:
<input type="text" name="headline" value="<?= $headline ?>"></p>
<p>Summary:<br/>
<textarea name ="summary" rows = "8" cols = "80" ><?=$summary?></textarea>
<input type="hidden" name="profile_id" value="<?= $profile_id ?>">
<p>
<p>
Education: <input type="submit" id="addEdu" value="+">
<div id="education_fields">
</div>
</p>
<?php

$countEdu  = 0;
foreach(getEducation($pdo,$profile_id) as $row){
    $countEdu++;
    if ($row["year"]!==NULL){
        $year = $row["year"];
        $name = $row['name'];
       echo('<div id="education'.$countEdu.'"> 
            <p>Year: <input type="text" name="year'.$countEdu.'" value="'.$year.'" /> 
            <input type="button" value="-" 
                onclick="$(\'#education'.$countEdu.'\').remove();return false;"></p> 
                <p>School: <input type="text" size="80" name="school'.$countEdu.'" class="school" value="'.$name.'"/></p>
            </div>');
    }
}

?>
<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">
</div>
</p>
<script src="addRemove.js"></script>
<?php
$stmt = $pdo->query("SELECT year, description FROM Position where profile_id = $profile_id ");

$countPos = 0;
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
    $countPos++;
    if ($row["year"]!==NULL){
        $year = $row["year"];
        $desc = $row['description'];
       echo('<div id="position'.$countPos.'"> 
            <p>Year: <input type="text" name="year'.$countPos.'" value="'.$year.'" /> 
            <input type="button" value="-" 
                onclick="$(\'#position'.$countPos.'\').remove();return false;"></p> 
            <textarea name="desc'.$countPos.'" rows="8" cols="80">'.$desc.'</textarea>
            </div>');
    }
} 
?>

<script src="addRemove.js"></script>
<p><input type="submit" value="Save"/>
<a href="index.php">Cancel</a></p>
</form>
