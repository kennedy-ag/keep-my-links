<?php
	$id = $_POST['id'];

	try {
		$conn = new PDO('mysql:host=localhost;dbname=keepmylinks', 'root', '');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

		$stmt = $conn->prepare('DELETE FROM videos WHERE id = :id');
		$stmt->bindParam(':id', $id);
		$stmt->execute();

		header("Location: ../painel.php");
	} catch(PDOException $e) {
		echo 'Error: ' . $e->getMessage();
	}
?>