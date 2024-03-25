<?php
    // 필터링 함수

    function filter_SQL($content){
        $content = str_replace("&", "&amp", $content); 
        $content = str_replace("<", "&lt", $content);  
        $content = str_replace(">", "&gt", $content);  
        $content = str_replace("'", "&apos", $content);   
        $content = str_replace("\"", "&quot", $content);  
        $content = str_replace("\r", "", $content);
        $content = str_replace("'", "", $content);   
        $content = str_replace('"', "", $content);  
        $content = str_replace("--", "", $content);
        $content = str_replace(";", "", $content);
        $content = str_replace("%", "", $content);
        $content = str_replace("+", "", $content);
        $content = str_replace("script", "", $content);
        $content = str_replace("alert", "", $content);
        $content = str_replace("cookie", "", $content);
        $content = SQL_Injection($content);
        return $content;
    }
    function SQL_Injection($get_Str) { 
        return preg_replace("/( select| union| insert| update| delete| drop| and| or|\"|\'|#|\/\*|\*\/|\\\|\;)/i","", $get_Str); 
    }

    $userid = filter_SQL($_REQUEST["userid"]);
?>

<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST["id_r"];
        $id = filter_SQL($id);

        $pw_r = $_POST["pw_r"];
        $pw_r = filter_SQL($pw_r);

        $name = $_POST["name_r"];
        $name = filter_SQL($name);

        try {
            
        } catch (Exception $e) {

        }
    }
?>