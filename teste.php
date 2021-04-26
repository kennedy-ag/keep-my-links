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
<html>
    <head>
        <title></title>
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
                    document.querySelector('#texto').innerHTML=r;
                });
            }
        </script>
    </head>
    <form method='post'>
        Username:  <input type='text' name='username' oninput='verificar_username(event)'/><br/>
        <div id="texto"></div>
    </form>
</html>