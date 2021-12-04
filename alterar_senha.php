<?php
  $conn = new PDO('mysql:host=localhost;dbname=keepmylinks', 'root', '');
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if(isset($_COOKIE['username'])){
    $login_cookie = $_COOKIE['username'];
  } else {
    header("Location:index.html");
  }
?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/estilo.css">
		<title>Alterar senha</title>
	</head>
	<body style="background-color: salmon;">

		<h1 class="text-center text-light fw-bolder my-4"><i class="fas fa-link"></i> Keep My Links</h1>

		<div id="login" class="mx-auto m-5 bg-light rounded shadow-lg p-4">
			<h1>Alterar senha</h1>
			<hr>
			<form method="POST" action="php/alterar_senha.php">
			  <div class="mb-3">
			    <label for="current" class="form-label">Senha atual</label>
			    <input type="password" name="current" class="form-control" id="current" placeholder="Sua senha atual">
			  </div>
			  <div class="mb-3">
			    <label for="newpass" class="form-label">Nova senha</label>
			    <input type="password" name="newpass" class="form-control" id="newpass" placeholder="Insira a nova senha" min='6'>
			  </div>
			  <input type="submit" name="alterar" value="Alterar" class="btn btn-outline-primary">
			</form>
		</div>


		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
		<script src="https://use.fontawesome.com/releases/v5.15.2/js/all.js" data-auto-replace-svg="nest"></script>
	</body>
</html>