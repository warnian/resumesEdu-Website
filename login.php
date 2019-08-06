<?php // Do not put any HTML above this line
session_start();
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to login.php
    header("Location: index.php");
    return;
}

$salt = 'XyZzy12*_';
$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is meow123
?>
<!DOCTYPE html>
<html>
<head>
<?php require_once "pdo.php"; ?>
<title>Ian Warn's Login Page</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<script
  src="https://code.jquery.com/jquery-3.2.1.js"
  integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
  crossorigin="anonymous"></script>
</head>
<body>
<div class="container">
<h1>Please Log In</h1>
<?php


if (isset($_SESSION["error"])){
    echo('<p style="color: red;">'.htmlentities($_SESSION["error"])."</p>\n");
    unset($_SESSION["error"]);
    
    $fail=false;
}
// Check to see if we have some POST data, if we do process it
if ( isset($_POST["email"]) && isset($_POST["pass"]) ) {
    
    $check = hash('md5', $salt.$_POST['pass']);
    $stmt = $pdo->prepare('SELECT user_id, name FROM users
    WHERE email = :em AND password = :pw');
    $stmt->execute(array( ':em' => $_POST['email'], ':pw' => $check));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ( $row !== false ) {
        $_SESSION['name'] = $row['name'];
        $_SESSION['user_id'] = $row['user_id'];
        // Redirect the browser to index.php
        echo('<p>login success</p>');
        $_SESSION["success"]="login success"; 
        header("Location: index.php");
        error_log("Login success ".$_POST["email"]);
        return;
    }
    
    if ($check!==false && strlen($_POST["pass"]) > 0){
        $_SESSION["error"] = "Incorrect password";
        
        error_log("Login fail "." ".$_SESSION["error"]);
        $fail=true;
        header("location: login.php");
        return;
    }
    if (strpos($_POST["email"],'@')===false && strlen($_POST["email"]) > 0){
        
        $_SESSION["error"]="Email must have an at-sign (@)";
        $_SESSION["email"]=$_POST["email"];
        
        error_log("Login fail ".$_SESSION["email"].' '.$_SESSION["email"]);
        $fail2=true;
        header("location: login.php");
        return;
    }
    if ( strlen($_POST["email"]) < 1 || strlen($_POST["pass"]) < 1 ) {
        $_SESSION["error"] = "Email and password are required";
        $_SESSION["email"]=$_POST["email"];
        
        error_log("Login fail ".$_SESSION["email"].' '.$_SESSION["email"]);
        $fail2=true;
        header("location: login.php");
        return;
    } 
    
    
}


// Fall through into the View
?>

<form method="POST">
<label for="email">Email</label>
<input type="text" name="email" id="email"><br/>
<label for="id_1723">Password</label>
<input type="password" name="pass" id="id_1723"><br/>
<input type="submit" onclick="return doValidate();" value="Log In">

<input type="submit" name="cancel" value="Cancel">
</form>
<p>
For a password hint, view source and find a password hint
in the HTML comments.
<!-- Hint: The password is the extension of the language these files are in followed by 123. -->
</p>
<script>
function doValidate() {
    console.log('Validating...');
    try {
        addr = document.getElementById('email').value;
        pw = document.getElementById('id_1723').value;
        console.log("Validating addr="+addr+" pw="+pw);
        if (addr == null || addr == "" || pw == null || pw == "") {
            alert("Both fields must be filled out");
            return false;
        }
        if ( addr.indexOf('@') == -1 ) {
            alert("Invalid email address");
            return false;
        }
        return true;
    } catch(e) {
        return false;
    }
    return false;
}
</script>
</div>
</body>
