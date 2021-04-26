<?php
	try {
		$nome = $_POST['nome'];
		$link = $_POST['link'];
		$duracao = $_POST['duracao'];
		$nota = $_POST['nota'];
		$categoria = $_POST['categoria'];

		$conn = new PDO('mysql:host=localhost;dbname=keepmylinks', 'root', '');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $conn->prepare('INSERT INTO videos VALUES(default, :nome, :link, :duracao, :nota, :categoria, :usuario)');
		$stmt->execute(array(
			':nome' => $nome,
			':link' => $link,
			':duracao' => $duracao,
			':nota' => $nota,
			':categoria' => $categoria,
			':usuario' => $_COOKIE['username']
		));

		header("Location: ../painel.php");

	} catch(PDOException $e) {
		echo 'Error: ' . $e->getMessage();
	}
?>