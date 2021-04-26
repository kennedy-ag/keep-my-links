<?php

	$id = $_POST['id'];
	$novo_dado = $_POST['campo'];
	$campo = $_POST['radios'];

	try {
		$conn = new PDO('mysql:host=localhost;dbname=keepmylinks', 'root', '');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		switch ($campo) {
			case 'nome':
				$stmt = $conn->prepare('UPDATE videos SET nome = :novo_dado WHERE id = :id;');
				break;
			case 'link':
				$stmt = $conn->prepare('UPDATE videos SET link = :novo_dado WHERE id = :id;');
				break;
			case 'duracao':
				$stmt = $conn->prepare('UPDATE videos SET duracao = :novo_dado WHERE id = :id;');
				break;
			case 'nota':
				$stmt = $conn->prepare('UPDATE videos SET nota = :novo_dado WHERE id = :id;');
				break;
			case 'categoria':
				$stmt = $conn->prepare('UPDATE videos SET categoria = :novo_dado WHERE id = :id;');
				break;
		}

		$stmt->execute(array(
		':id' => $id,
		':novo_dado' => $novo_dado
		));

		header('Location: ../painel.php');

	} catch(PDOException $e) {
	  echo 'Error: ' . $e->getMessage();
	}
?>