<?php
$username = $_POST['username'];
$senha = md5($_POST['senha']);
$entrar = $_POST['entrar'];

$connect = mysqli_connect('localhost','root','', 'keepmylinks');
  if (isset($entrar)) {

    $verifica = mysqli_query($connect, "SELECT * FROM usuarios WHERE username =
    '$username' AND senha = '$senha'") or die("Erro ao selecionar");
      if (mysqli_num_rows($verifica)<=0){
        echo"<script>
        alert('Login e/ou senha incorretos');window.location
        .href='../index.html';</script>";
        header('Location:../index.html');
      }else{
        setcookie('username', $username, time()+3600*24, '/');
        setcookie('tema', '0', time()+3600*24, '/');
        header("Location:../painel.php");
      }
  }
?>