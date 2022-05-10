<?php
	$id = $_POST['id'];
	if(isset($_COOKIE['username'])){
		$usuario = $_COOKIE['username'];
	} else {
		header("Location:index.html");
	}
	try {
		$conn = new PDO('mysql:host=localhost;dbname=keepmylinks', 'root', '');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $conn->prepare('DELETE FROM videos WHERE id = :id AND usuario=:usuario');
		$stmt->execute(array(
			':id' => $id,
			':usuario' => $usuario
		));

		header("Location: ../painel.php");
	} catch(PDOException $e) {
		echo 'Error: ' . $e->getMessage();
	}
?>