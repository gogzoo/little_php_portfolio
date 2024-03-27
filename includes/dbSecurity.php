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
?>