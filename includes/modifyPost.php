<?php 
    session_start();

    require_once "../includes/dbConnect_localhost.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        try {
            $modifyTitle = $_POST["modifyTitle"];
            $modifyPara = $_POST["modifyPara"];
            $sn = $_POST["sn"];
    
            $statement = $pdo->prepare("UPDATE board SET title=:title, para=:para WHERE sn=:sn");
            $statement->bindParam(":title", $modifyTitle, PDO::PARAM_STR);
            $statement->bindParam(":para", $modifyPara, PDO::PARAM_STR);
            $statement->bindParam(":sn", $sn, PDO::PARAM_INT);
            $statement->execute();

            echo "success";

        }catch (PDOException $e) {

            //echo $e->getMessage();
            echo "fail";
        }
    }
?>