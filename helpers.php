<?php
//functions to help out add and make it less of a spaghetti
function getEducation($pdo, $profile_id){
    $sql = "SELECT year, name FROM EDUCATION JOIN Institution ON Education.institution_id = Institution.institution_id 
                WHERE profile_id = :profile ORDER BY rank";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array(':profile'=> $profile_id));
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
   
}
function insertEducation($pdo,$profile_id){
        $rank = 1;
        for ($i=0; $i<10;$i++){
            if (!isset($_POST['yearEdu'.$i])) continue;
            if (!isset($_POST['school'.$i])) continue;
            $year_edu = $_POST["yearEdu".$i];
            $school_name = $_POST["school".$i];
        
            $institution_id =false;
            $sql = "SELECT institution_id from Institution WHERE name = :name";
            $stmt = $pdo ->prepare($sql);
            $stmt->execute(array(':name'=>$school_name));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($row !==false) { //institution id found already there
                $institution_id=$row['institution_id'];
            }
            if ($institution_id ===false){
                $sql = "INSERT INTO Institution (name) VALUES (:name)";
                $stmt = $pdo->prepare($sql);
                $stmt->execute(array(':name'=>$school_name));
                $institution_id = $pdo->lastInsertId();

            }
            $sql = "INSERT INTO Education (profile_id,rank,year,institution_id) VALUES (:profile_id, :rank ,:year,:institution_id)";
            $stmt = $pdo->prepare($sql);
            $stmt ->execute(array(':profile_id'=>$profile_id,':rank'=>$rank,':year'=>$year_edu,':institution_id'=>$institution_id));
            $rank++;
        }
}
function errorValNotEmpty($param){
    if ( isset($param)&&strlen($param)<1){
       
        $_SESSION["error2"]="All values are required";
        header("location: add.php");
        return;
    }
   
}
function errorValIsNum($param){
    if(isset($param)&& is_numeric($param)===false){
        $_SESSION["error2"]="Year must be numeric";
        header("location: add.php");
        return;
        
    }
    
}
?>