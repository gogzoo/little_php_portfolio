<?php 
    session_start();

    require_once "../includes/dbConnect_localhost.php";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $nameid = $_SESSION["username"];
        $title = $_POST["title"];
        $para = $_POST["para"];

        try {
            $statement = $pdo->prepare("INSERT INTO board (id, title, para, postdate) VALUES(?, ?, ?, CURRENT_DATE)");
            $statement->bindParam(1, $nameid, PDO::PARAM_STR);
            $statement->bindParam(2, $title, PDO::PARAM_STR);
            $statement->bindParam(3, $para, PDO::PARAM_STR);
            $statement->execute();

            echo "<script>";
            echo "  alert('게시글을 작성했습니다.');";
            echo "  window.location.href = '../index.php'";
            echo "</script>";

        }catch (PDOException $e) {

            //echo "". $e->getMessage();
            echo "<script>";
            echo "  alert('서버 오류가 발생했습니다. 나중에 시도해주세요.');";
            echo "  window.location.href = '../index.php'";
            echo "</script>";
        }
    }
?>