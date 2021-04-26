<?php

$nome = $_POST['nome'];
$username = $_POST['username'];
$email = $_POST['email'];
$sexo = $_POST['sexo'];
$senha = MD5($_POST['senha']);

$connect = mysqli_connect('localhost','root','', 'keepmylinks');
$query_select = "SELECT username FROM usuarios WHERE username = '$username'";
$select = mysqli_query($connect, $query_select);
$array = mysqli_fetch_array($select);
$logarray = $array['username'];


if($logarray == $username){

echo"<script language='javascript' type='text/javascript'>
alert('Esse login já existe');window.location.href='
../cadastro.html';</script>";
die();

}else{
$query = "INSERT INTO usuarios VALUES ('$nome', '$username', '$email', '$sexo', '$senha')";
$insert = mysqli_query($connect, $query);

if($insert){
  echo"<script language='javascript' type='text/javascript'>
  alert('Usuário cadastrado com sucesso!');window.location.
  href='../index.html'</script>";
}else{
  echo"<script language='javascript' type='text/javascript'>
  alert('Não foi possível cadastrar esse usuário');window.location
  .href='../cadastro.php'</script>";
}
}
?>