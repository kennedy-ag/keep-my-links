<?php
    $padrao='';

    if($_SERVER['REQUEST_METHOD']=='POST'){
        function verificar_username($username){
            $username = !empty( $_POST['username'] ) ? $_POST['username'] : $username;
            $connect = mysqli_connect('localhost','root','', 'keepmylinks');
            $verifica = mysqli_query($connect, "SELECT * FROM usuarios WHERE username='{$username}'") or die("Erro ao selecionar");
            $quantidade = mysqli_num_rows($verifica);
            $username = $quantidade==0 ? 'Nome de usuário disponível!' : 'Este username já existe!';
            return $username;
        }
        exit(verificar_username($padrao));
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="css/estilo.css">
		<title>Cadastrar-se</title>
	</head>
	<body style="background-color: #7d9dbd;">

		<h1 class="text-center text-light fw-bolder my-4"><i class="fas fa-link"></i> Keep My Links</h1>

		<div id="registro" class="mx-auto m-4 bg-light rounded shadow-lg p-4">
			<h1>Cadastre-se</h1>
			<hr>
			<form method="POST" action="php/registrar.php">
			  <div class="mb-3">
			    <label for="ipt_nome" class="form-label">Nome</label>
			    <input type="text" class="form-control" maxlength="45" name="nome" id="ipt_nome" placeholder="Insira seu nome" required>
			  </div>

			  <div class="mb-3">
			    <label for="ipt_username" class="form-label">Nome de usuário</label>
			    <input type="text" class="form-control" oninput='verificar_username(event)' maxlength="30" name="username" id="ipt_username" placeholder="Insira seu nome de usuário" required>
			  </div>
			  <div id="texto"></div>

		  	<div class="mb-3">
			    <label for="ipt_email" class="form-label">E-mail</label>
			    <input type="email" class="form-control" maxlength="50" name="email" id="ipt_email" placeholder="Insira seu e-mail" required>
			</div>

			  <fieldset class="border rounded p-3 mb-3">
			  	<div class="form-check">
					  <input class="form-check-input" type="radio" value="masculino" name="sexo" id="radio1" checked>
					  <label class="form-check-label" for="radio1">Masculino</label>
					</div>
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="sexo" value="feminino" id="radio2">
					  <label class="form-check-label" for="radio2">Feminino</label>
					</div>
					<div class="form-check">
					  <input class="form-check-input" type="radio" name="sexo" value="outros" id="radio3">
					  <label class="form-check-label" for="radio3">Outros</label>
					</div>
			  </fieldset>

			  <div class="mb-3">
			    <label for="ipt_senha" class="form-label">Senha</label>
			    <input type="password" class="form-control" name="senha" maxlength="40" id="ipt_senha" placeholder="Insira sua senha" required>
			  </div>

			  <div class="text-end">
			  	<a href="index.html" class="text-decoration-none">Já tenho uma conta</a>
			  </div>

			  <input type="submit" class="btn btn-primary">
			</form>
		</div>


		<script>
            function ajax(url, params, callback){
                var xhr=new XMLHttpRequest();
                xhr.onreadystatechange=function(){
                    if( this.readyState==4 && this.status==200 ) callback.call( this, xhr.response );
                };

                var payload=[];
                for( var n in params )payload.push( n+'='+params[n] );
                payload=payload.join('&');

                xhr.open( 'POST', url, true );
                xhr.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
                xhr.send( payload );
            }

            function verificar_username( event ){
                ajax.call( this, location.href, { username:event.target.value }, function(r){
                    if(r.indexOf('disponível')!=-1){
                    	document.querySelector('#texto').innerHTML="<div class='bg-success text-center text-light mb-2 p-2'>"+r+"</div>";
                    } else {
                    	document.querySelector('#texto').innerHTML="<div class='bg-danger text-center text-light mb-2 p-2'>"+r+"</div>";
                    }
                    if(document.getElementById('ipt_username').value==''){
                    	document.querySelector('#texto').innerHTML="<div class='bg-info text-center text-light mb-2 p-2'>Preencha este campo</div>";
                    }
                });
            }
        </script>

		<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"></script>
		<script src="https://use.fontawesome.com/releases/v5.15.2/js/all.js" data-auto-replace-svg="nest"></script>
	</body>
</html>