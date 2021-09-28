<?php
    $tema = $_COOKIE['tema'];
    if($tema=='0'){
        setcookie('tema', '1', time()+3600*24, '/');
        header("Location:../painel.php");
    } else {
        setcookie('tema', '0', time()+3600*24, '/');
        header("Location:../painel.php");
    }
?>