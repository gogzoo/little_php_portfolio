<?php 

    require_once "../includes/dbConnect_localhost.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nameid = "koko";
        $title = $_POST["title"];
        $para = $_POST["para"];
        $postdate = date("Y-m-d");

        try {
            $statement = $pdo->prepare("INSERT INTO board (id, title, para, postdate) VALUES(?, ?, ?, ?)");
            $statement->bindParam(1, $nameid, PDO::PARAM_STR);
            $statement->bindParam(2, $title, PDO::PARAM_STR);
            $statement->bindParam(3, $para, PDO::PARAM_STR);
            $statement->bindParam(4, $postdate, PDO::PARAM_STR);
            $statement->execute();

            header("Location: ../index.php?success=true");
            exit();
        }catch (PDOException $e) {
            header("Location: ../index.php?success=false");
            exit();
        }
    }
?>