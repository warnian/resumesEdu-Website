
<html>
<head>
<title>Ian Warn Add Page</title>

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
    integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7"
    crossorigin="anonymous">

<link rel="stylesheet"
    href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css"
    integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r"
    crossorigin="anonymous">

<link rel="stylesheet" 
    href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" 
    integrity="sha384-xewr6kSkq3dBbEtB6Z/3oFZmknWn7nHqhLVLrYgzEFRbU/DHSxW7K3B44yWUN60D" 
    crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>

<script
  src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"
  integrity="sha256-T0Vest3yCU7pafRw9r+settMBX6JkKN06dqBnpQ8d30="
  crossorigin="anonymous"></script>
</head><body>
<?php 
//add.php part of autosSess, adds value to table then redirects to view.php 
//splits up autos.php from last_name project
//cancel redirects to view.php
//add stores and redirects to view.php
include 'helpers.php';
require_once "pdo.php";
session_start();

if (!isset($_SESSION["success"])){
    die("ACCESS DENIED NOT LOGGED IN");
}
if (isset($_POST["cancel"])){
    header("location: index.php");
    return;
}
//error checking
//should refactor this, lots of repeated code functions in helper.php used below dont work for some reason


    if (isset($_POST['Add'])&&isset($_POST['first_name'])&&isset($_POST['last_name'])&&isset($_POST['email'])&&isset($_POST['headline'])&&isset($_POST['summary'])){
        unset($_SESSION["first_name"]);
        unset($_SESSION["last_name"]);
        unset($_SESSION["email"]);
        unset($_SESSION["headline"]);
        unset($_SESSION["summary"]);
        
        for ($i=0;$i<10;$i++){
            unset($_SESSION["rank".$i]);
            unset($_SESSION["year".$i]);
            unset($_SESSION["desc".$i]);
        }
    
        if ( isset($_POST['first_name'])&&strlen($_POST['first_name'])<1){
            $_SESSION["first_name"]=$_POST['first_name'];
            $_SESSION["error2"]="All values are required";
            header("location: add.php");
            return;
        }
        if ( isset($_POST['last_name'])&&strlen($_POST['last_name'])<1){
            $_SESSION["last_name"]=$_POST['last_name'];
            $_SESSION["error2"]="All values are required";
            header("location: add.php");
            return;
        }
        if ( isset($_POST['email'])&&strlen($_POST['email'])<1){
            $_SESSION["email"]=$_POST['email'];
            $_SESSION["error2"]="All values are required";
            header("location: add.php");
            return;
        }
        if ( isset($_POST['headline'])&&strlen($_POST['headline'])<1){
            $_SESSION["email"]=$_POST['email'];
            $_SESSION["error2"]="All values are required";
            header("location: add.php");
            return;
        }
        if(isset($_POST['year'])&& strlen($_POST['year'])<1){
            $_SESSION["year"]=$_POST['year'];
            $_SESSION["error2"]="All values are required";
            header("location: add.php");
            return;
        }
        for ($i=0;$i<10;$i++){
            if(isset($_POST['year'.$i])&& strlen($_POST['year'.$i])<1){
                $_SESSION["year".$i]=$_POST['year'.$i];
                $_SESSION["error2"]="All values are required";
                header("location: add.php");
                return;
            }
            if(isset($_POST['year'.$i])&& is_numeric($_POST['year'.$i])===false){
                $_SESSION["year".$i]=$_POST['year'.$i];
                $_SESSION["error2"]="Year Must Be Numeric";
                header("location: add.php");
                return;
            }
            if(isset($_POST['desc'.$i])&& strlen($_POST['desc'.$i])<1){
                $_SESSION["desc".$i]=$_POST['desc'.$i];
                $_SESSION["error2"]="All values are required";
                header("location: add.php");
                return;
            }
            if(isset($_POST['yearEdu'.$i])&& strlen($_POST['yearEdu'.$i])<1){
                $_SESSION["yearEdu".$i]=$_POST['yearEdu'.$i];
                $_SESSION["error2"]="All values are required";
                header("location: add.php");
                return;
            }
            if(isset($_POST['yearEdu'.$i])&& is_numeric($_POST['yearEdu'.$i])===false){
                $_SESSION["yearEdu".$i]=$_POST['yearEdu'.$i];
                $_SESSION["error2"]="Year must be numeric";
                header("location: add.php");
                return;
            }
            if(isset($_POST['school'.$i])&& strlen($_POST['school'.$i])<1){
                $_SESSION["school".$i]=$_POST['school'.$i];
                $_SESSION["error2"]="All values are required";
                header("location: add.php");
                return;
            }
            
        }
        /*
    //error validation methods
    //method in helpers.php 
    //if field is blank throw error
    errorValNotEmpty($_POST['first_name']);
    errorValNotEmpty($_POST['last_name']);
    errorValNotEmpty($_POST['email']);
    errorValNotEmpty($_POST['headline']);
    errorValNotEmpty($_POST['summary']);
    
   
    for ($i=1;$i<10;$i++){
        if (isset($_POST['year'.$i]) && isset($_POST['desc'.$i])){
            errorValNotEmpty($_POST['year'.$i]);
            errorValNotEmpty($_POST['desc'.$i]);
            //errorValIsNum($_POST['year'.$i]);
        }
        if (isset($_POST['yearEdu'.$i]) && isset($_POST['school'.$i])){
            errorValNotEmpty($_POST['yearEdu'.$i]);
            errorValNotEmpty($_POST['school'.$i]);
            
        }
        if (isset($_POST['yearEdu'.$i])&& errorValNotEmpty($_POST['yearEdu'.$i])){
            errorValIsNum($_POST['yearEdu']);
        }
        if (isset($_POST['year'.$i])&& errorValNotEmpty($_POST['year'.$i])){
            errorValIsNum($_POST['year']);
        }
        
    }
    */
   
    
    $sql = "INSERT INTO Profile (user_id,first_name,last_name,email,headline,summary) VALUES(:user_id,:first_name, :last_name, :email, :headline, :summary)";
    $_SESSION["headline"]=$_POST['headline'];
    $_SESSION["last_name"] = $_POST["last_name"];
    $_SESSION["first_name"]=$_POST['first_name'];
    $_SESSION["email"]=$_POST['email'];
    $_SESSION["summary"]=$_POST['summary'];
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':user_id'=>$_SESSION['user_id'],':first_name'=> $_SESSION["first_name"],":last_name"=>$_SESSION["last_name"],':email'=> $_SESSION["email"],':headline' => $_SESSION["headline"],':summary' => $_SESSION["summary"]));
    $_SESSION["added"]="value inserted";
    
    $profile_id_inserted=$pdo->lastInsertId();
    for ($i=0;$i<10;$i++){
        
        $_SESSION["rank".$i]=$i;
        $_SESSION["year".$i] = $_POST["year".$i];
        $_SESSION["desc".$i] = $_POST["desc".$i];
       
        //inserts new position into sql table
        $sqlPos = "INSERT INTO Position (profile_id,rank,year,description) VALUES(:profile_id, :rank, :year, :description)";
        $stmtPos = $pdo->prepare($sqlPos);
        $stmtPos->execute(array(':profile_id'=>$profile_id_inserted,':rank'=> $_SESSION["rank".$i],':year'=>$_SESSION["year".$i],':description'=>$_SESSION["desc".$i]));
       

    }
    insertEducation($pdo,$profile_id_inserted);
    header("location: index.php");
    return;
    
    
}


?>
<p><font size='20'>Adding profile for <?php echo($_SESSION['name'])?></font>  </p>
<?php
if ( isset ($_SESSION["error2"])) {
    // Look closely at the use of single and double quotes
    
    echo('<p style="color: red;">'.htmlentities($_SESSION["error2"])."</p>\n");
    unset($_SESSION["error2"]);

}
?>
<form method="post">

<p>First Name:<input type="text" name="first_name" size="40"></p>
<p>Last Name:<input type="text" name="last_name" size= "40"></p>
<p>Email:<input type="text" name="email" size="40"></p>
<p>Headline:<br/><input type="textf" name="headline" size="40"></p>
<p>Summary:<br/>

<textarea name ="summary" rows = "8" cols = "80"></textarea>
<p>
Education: <input type="submit" id="addEdu" value="+">
<div id="education_fields">
</div>
</p>
<p>
Position: <input type="submit" id="addPos" value="+">
<div id="position_fields">
</div>
</p>

<p><input type="submit" name="Add" value="Add"/>
<input type='submit'name='cancel'value='Cancel'/></p>
</form>



<script src="addRemove.js"></script>
</div>
</body>
</html>