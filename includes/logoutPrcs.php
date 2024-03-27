<?php 
    session_start();

    session_destroy();

    echo "<script>";
    echo "  alert('로그아웃 하였습니다.');";
    echo "  window.location.href = '../index.php'";
    echo "</script>";
?>