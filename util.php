<?php
//functions to help out add and make it less of a shitshow
function insertEducation($pdo,$profile_id){
        $rank = 1;
        for ($i=1; $i<10;$i++){
            $year_edu = $_POST["yearEdu".$i];
            $school_name = $_POST["school".$i];
        }
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
?>