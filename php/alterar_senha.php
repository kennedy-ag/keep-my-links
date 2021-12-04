<?php
  if(isset($_COOKIE['username'])){
    $login_cookie = $_COOKIE['username'];
  } else {
    header("Location:index.html");
  }
  $atual = md5($_POST['current']);
  $nova = md5($_POST['newpass']);
  $alterar = $_POST['alterar'];

  $connect = mysqli_connect('localhost','root','', 'keepmylinks');
  if (isset($alterar)) {

    $verifica = mysqli_query($connect, "SELECT * FROM usuarios WHERE username =
    '$login_cookie' AND senha = '$atual'") or die("Erro ao selecionar");
      if (mysqli_num_rows($verifica)<=0){
        echo"<script>
        alert('Senha incorreta');window.location
        .href='../index.html';</script>";
        header('Location:../alterar_senha.php');
      }else{
        try {
            $conn = new PDO('mysql:host=localhost;dbname=keepmylinks', 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare('UPDATE usuarios SET senha = :nova_senha WHERE username = :username;');
            $stmt->execute(array(
            ':username' => $login_cookie,
            ':nova_senha' => $nova
            ));
        } catch(PDOException $e) {
          echo 'Error: ' . $e->getMessage();
        }
        setcookie('username', null, -1, '/');
	    header("Location:../index.html");
      }
  }
?>