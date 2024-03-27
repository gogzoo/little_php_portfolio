<?php 
    session_start();

    require_once "../includes/dbConnect_localhost.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $sn = $_POST["sn"];

        try {
            $statement = $pdo->prepare("DELETE FROM board WHERE sn=:sn;");
            $statement->bindParam(":sn", $sn, PDO::PARAM_INT);
            $statement->execute();

            echo "success";

        } catch (PDOException $e) {
            
            echo "". $e->getMessage();
        }
    }
?>