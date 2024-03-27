<?php
    require_once "./dbConnect_localhost.php";
    require "./dbSecurity.php";
?>

<?php 
    // 아이디와 비번이 유효한지 확인
    function validateForm($form) {
        $validPattern = '/^[a-zA-Z0-9]+$/';
        // 입력값이 영대소문자, 숫자로만 이루어져있는지 확인.

        if (preg_match($validPattern, $form)) {
            return true;
        } else {
            return false;
        }
    }
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["id_r"];
        $id = filter_SQL($id);
        $idValid = validateForm($id);

        $pw_r = $_POST["pw_r"];
        $pw_r = filter_SQL($pw_r);
        $pwValid = validateForm($pw_r);

        $name = $_POST["name_r"];
        $name = filter_SQL($name);

        if ($idValid == false || $pwValid == false) {
            echo "<script>";
            echo "  alert('아이디 또는 비밀번호가 유효하지 않습니다.');";
            echo "  window.location.href = '../portfolio4/index.php'";
            echo "</script>";

        } else if ($idValid == true && $pwValid == true) {

            try {
                $sql = "INSERT INTO member (id, pw, name, regidate) VALUES
                (:id, :pw, :name, CURRENT_DATE)";

                $statement = $pdo->prepare($sql);
                $statement->bindParam("id", $id, PDO::PARAM_STR);
                $statement->bindParam("pw", $pw_r, PDO::PARAM_STR);
                $statement->bindParam("name", $name, PDO::PARAM_STR);
                $statement->execute();

                echo "<script>";
                echo "  alert('가입이 완료되었습니다.');";
                echo "  window.location.href = '../index.php'";
                echo "</script>";

            } catch (Exception $e) {
                echo "<script>";
                echo "  alert('서버 오류가 발생했습니다. 나중에 다시 시도해주세요.');";
                echo "  window.location.href = '../index.php'";
                echo "</script>";
            }
        }
    }
?>