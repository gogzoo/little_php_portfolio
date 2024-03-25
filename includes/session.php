<?php 
    session_start();

    $_SESSION["logined"] = false;

    // 일단 익명용 아이디 생성
    $anonId = '#'. dechex(rand(0x000000, 0xFFFFFF));
    $_SESSION["anonId"] = $anonId;

    

?>