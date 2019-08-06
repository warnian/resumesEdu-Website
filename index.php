<!DOCTYPE html>
<html>
<head>
<title>Ian Warn -  Resume Database</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
<?php require_once "pdo.php"; 
session_start();
?>
</head>
<body>
<div class="container">
<h1>Welcome to Resume Registry</h1>
<?php
if (isset($_SESSION["added"])){
    echo('<p style="color: green;">'.'Record Added'."</p>\n");
    unset($_SESSION["added"]);
}
if (isset($_SESSION['deleted'])){
    echo('<p style="color: green;">'.'Record Deleted'."</p>\n");
    unset($_SESSION["deleted"]);
}
if (isset($_SESSION["success"])){
    echo('<table border="1">'."\n");
    $stmt = $pdo->query("SELECT first_name, last_name, email,headline, profile_id FROM Profile");
    echo('<tr><th>Name</th><th>Headline</th><th>Action</th><tr>');
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
        echo ("<tr><td>");
        echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.htmlentities($row['first_name'].' '.$row['last_name'])."</a>");
        echo("</td><td>");
        echo(htmlentities($row['headline']));
        
        echo("</td><td>");
        echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
        echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
        echo("</td></tr>\n");
    }
    echo("</table>");
    echo('<p><a href="add.php">Add New Entry</a> | <a href="logout.php">Logout</a></p>');
}
else {
    echo('<p><a href="login.php">Please log in</a></p>');
    echo("<p> Attempt to  <a href='add.php'> add data</a> without logging in</a>");
    echo('</p> <a href="https://www.wa4e.com/assn/res-profile/"target="_blank">Spec for this Application</a></p>');
}
?>



</div>
</body>

