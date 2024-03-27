<?php 
    require_once "./dbConnect_localhost.php";
    require "./dbSecurity.php";
?>

<?php 
    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $inputed_id = $_POST["id"];
        $inputed_pw = $_POST["pw"];

        $inputed_id = filter_SQL($inputed_id);
        $inputed_pw = filter_SQL($inputed_pw); 

        try {

            $sql = "SELECT id, pw FROM member WHERE id=:id AND pw=:pw;";
            $statement = $pdo->prepare($sql);
    
            $statement->bindParam("id", $inputed_id, PDO::PARAM_STR);
            $statement->bindParam("pw", $inputed_pw, PDO::PARAM_STR);
            $statement->execute();
    
            $sqlResult = $statement->fetchAll();
    
            //var_dump($sqlResult);
            // 리턴형 예
            // array(1) { [0]=> array(2) { ["id"]=> string(4) "koko" ["pw"]=> string(4) "0000" } }
    
            $finded_id = $sqlResult[0]["id"];
            $finded_pw = $sqlResult[0]["pw"];

            if($inputed_id === $finded_id && $inputed_pw === $finded_pw) {
                session_regenerate_id(true);
                $_SESSION["logined"] = true;
                $_SESSION["username"] = $finded_id;
                header("Location: ../index.php");
                exit;

            } else {
                throw new Exception("로그인 정보가 올바르지 않습니다.");
            }

        } catch (Exception $e) {

            echo "<script>";
            echo "  alert('아이디 또는 비밀번호가 유효하지 않습니다.');";
            echo "  window.location.href = '../index.php'";
            echo "</script>";
        }
    }
?>