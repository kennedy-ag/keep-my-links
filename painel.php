<?php
  $conn = new PDO('mysql:host=localhost;dbname=keepmylinks', 'root', '');
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if(isset($_COOKIE['username'])){
    $login_cookie = $_COOKIE['username'];
  } else {
    header("Location:index.html");
  }

  $connect = mysqli_connect('localhost','root','', 'keepmylinks');
  $verifica = mysqli_query($connect, "SELECT * FROM videos WHERE usuario = '$login_cookie'") or die("Erro ao selecionar");
  $quantidade = mysqli_num_rows($verifica);
?>

<!DOCTYPE html>
<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/estilo.css">
    <title>Keep My Links - Home</title>
  </head>
  <body style="margin-top: 56px;">
    

    <nav id="menu" class="navbar navbar-expand-lg navbar-dark fixed-top shadow-lg" style="background-color: #7d9dbd;">
      <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="fa fa-link"></i> KML</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item mx-2 my-1">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-adicionar">
                <i class="fa fa-plus"></i> Novo
              </button>
            </li>
            <li class="nav-item mx-2 my-1">
              <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-atualizar">
                <i class="fa fa-edit"></i> Atualizar
              </button>
            </li>
            <li class="nav-item mx-2 my-1">
              <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modal-excluir">
                <i class="fa fa-trash"></i> Excluir
              </button>
            </li>
          </ul>
      </div>
    </nav>

    <div class="container-fluid p-3">

      <div class="dropdown">
          <i class="fa fa-user"></i> Logado como: &nbsp;
          <a class="dropdown-toggle text-decoration-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <strong><?php echo $login_cookie; ?></strong>
        </a>

        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="/php/logout.php"><i class="fa fa-sign-out-alt"></i> Sair</a></li>
        </ul>
      </div>
    </div>


    <div class="container">
      <h4 class="text-center p-2 m-3 text-secondary">Todos os seus links (<?php echo $quantidade; ?>)</h4>
      <hr>
      <p><i class="fa fa-filter text-secondary"></i> Filtros:</p>

      <form id="filtros" class="row m-3" method="GET" action="painel.php">
        <select name="nota" class="form-select col-md-2 m-1">
          <option value="Nota" selected>Nota</option>
          <option value="20">1 - 20</option>
          <option value="40">21 - 40</option>
          <option value="60">41 - 60</option>
          <option value="80">61 - 80</option>
          <option value="100">81 - 100</option>
        </select>

        <select name="duracao" class="form-select col-md-2 m-1">
          <option value="Duracao" selected>Duração</option>
          <option value="5">Até 5 min.</option>
          <option value="10">Entre 5 e 10 min.</option>
          <option value="15">Entre 10 e 15 min.</option>
          <option value="20">Entre 15 e 20 min.</option>
          <option value="30">Entre 20 e 30 min.</option>
          <option value="31">Acima de 30 min.</option>
        </select>

        <select name="categoria" class="form-select col-md-2 m-1">
          <option value="Categoria" selected>Categoria</option>
          <?php
            try {
              $conn = new PDO('mysql:host=localhost;dbname=keepmylinks', 'root', '');
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $login_cookie = $_COOKIE['username'];

              $lista = array();
              $consulta = $conn->query("SELECT categoria, nome FROM videos WHERE usuario='{$login_cookie}';");
              while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
                if(!in_array($linha['categoria'], $lista)){
                  array_push($lista, $linha['categoria']);
                }
              }
              for ($i=0; $i < count($lista); $i++) { 
                  echo "<option value='{$lista[$i]}'>{$lista[$i]}</option>";
              }
            } catch(PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
            }
          ?>
        </select>

        <input class="btn btn-outline-primary m-1 col-md-2" type="submit" value="Aplicar">
      </form>

      <hr>

      <table id="tabela" class="table table-striped table-hover">
        <thead>
          <tr id="cabecalho-tabela" class="text-light" style="background-color: #7d9dbd;">
            <th scope="col"><i class='fa fa-list'></i> ID</th>
            <th scope="col" class="text-center"><i class='fa fa-signature'></i> Nome</th>
            <th scope="col" class='text-center'><i class='fa fa-link'></i> Link</th>
            <th scope="col" class='text-center'><i class='fa fa-clock'></i> Duração</th>
            <th scope="col" class='text-center'><i class='fa fa-sticky-note'></i> Nota</th>
            <th scope="col" class='text-center'><i class='fa fa-bars'></i> Categoria</th>
          </tr>
        </thead>
        <tbody>
          <?php
            try {
              $q = "SELECT * FROM videos WHERE usuario='{$login_cookie}'";

              // Aplicação dos filtros
              if (isset($_GET['nota'])) {
                if ($_GET['nota']=='Nota') {
                  $a = '';
                } else {
                  $max = intval($_GET['nota']);
                  $min = $max - 19;
                  $q = $q . " AND nota BETWEEN {$min} AND {$max}";
                }
              }

              if (isset($_GET['duracao'])) {
                if ($_GET['duracao']=='Duracao') {
                  $a = '';
                } else {
                  $max = intval($_GET['duracao']);
                  if ($max==30) {
                    $min = $max - 10;
                    $q = $q . " AND duracao BETWEEN {$min} AND {$max}";
                  } elseif ($max==31) {
                    $min = 30;
                    $q = $q . " AND duracao > {$min}";
                  } else {
                    $min = $max - 5;
                    $q = $q . " AND duracao BETWEEN {$min} AND {$max}";
                  }
                }
              }

              if (isset($_GET['categoria'])) {
                if ($_GET['categoria']=='Categoria') {
                  $a = '';
                } else {
                  $categoria = $_GET['categoria'];
                  $q = $q . " AND categoria='{$categoria}'";
                }
              }

              $q = $q . ";";

              $consulta = $conn->query($q);
              while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
                echo "
                <tr>
                  <td>{$linha['id']}</td>
                  <td class='text-center'>{$linha['nome']}</td>
                  <td class='text-center'><a target='_blank' href='{$linha['link']}'>Assistir</a></td>
                  <td class='text-center'>{$linha['duracao']}</td>
                  <td class='text-center'>{$linha['nota']}</td>
                  <td class='text-center'>{$linha['categoria']}</td>
                </tr>";
            }
            } catch(PDOException $e) {
              echo 'ERROR: ' . $e->getMessage();
            }
            ?>
        </tbody>
      </table>
    </div>


    <div class="modal fade" id="modal-adicionar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Adicionar um link</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form method="post" action="php/inserir.php">
              <div class="form-group mb-3">
                <label for="nome-input">Nome</label>
                <input name="nome" type="text" class="form-control" id="nome-input" placeholder="Insira o nome do vídeo" required="required" maxlength="50">
              </div>
              <div class="form-group my-3">
                <label for="link-input">Link</label>
                <input name="link" type="text" class="form-control" id="link-input" placeholder="Insira o link do vídeo" required="required" maxlength="300">
              </div>
              <div class="form-group my-3">
                <label for="duracao-input">Duração</label>
                <input name="duracao" type="number" class="form-control" min="0" id="duracao-input" placeholder="Duração em minutos. Ex. 36" required="required">
              </div>
              <div class="form-group my-3">
                <label for="nota-input">Nota</label>
                <input name="nota" type="text" class="form-control" id="nota-input" placeholder="Avalie o vídeo de 0 a 100" required="required" min="0" max="100">
              </div>
              <div class="form-group my-3">
                <label for="categoria-input">Categoria</label>
                <input name="categoria" type="text" class="form-control" id="categoria-input" placeholder="Insira a categoria principal. Ex. games" required="required" maxlength="20">
              </div>
              <hr>
              <button type="submit" class="btn btn-primary my-3 d-flex mx-auto">Adicionar</button>
            </form>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="modal-atualizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Atualizar um link</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form method="post" action="php/atualizar.php">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radios" id="radio-1" value="nome" checked>
                <label class="form-check-label" for="radio-1">
                Nome
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radios" id="radio-2" value="link">
                <label class="form-check-label" for="radio-2">
                Link
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radios" id="radio-3" value="duracao">
                <label class="form-check-label" for="radio-3">
                Duração
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radios" id="radio-4" value="nota">
                <label class="form-check-label" for="radio-4">
                Nota
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radios" id="radio-4" value="categoria">
                <label class="form-check-label" for="radio-4">
                Categoria
                </label>
              </div>
              <hr>
              <div class="form-group my-3">
                <label for="id-atualizar">ID</label>
                <input name="id" type="text" class="form-control" id="id-atualizar" placeholder="Insira o ID do vídeo" required="required">
              </div>
              <div class="form-group my-3">
                <label for="campo">Nova informação</label>
                <input name="campo" type="text" class="form-control" id="campo" placeholder="Insira a nova informação" required="required" maxlength="200">
              </div>
              <hr>
              <button type="submit" class="btn btn-primary my-3 d-flex mx-auto">Atualizar</button>
            </form>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="modal-excluir" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Excluir um link</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <form method="post" action="php/deletar.php">
              <div class="form-group">
                <label for="id-excluir">ID</label>
                <input name="id" type="text" class="form-control" id="id-excluir" placeholder="Insira o id do vídeo" required="required">
              </div>
              <hr>
              <button type="submit" class="btn btn-primary my-3 d-flex mx-auto">Excluir</button>
            </form>
          </div>
        </div>
      </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/releases/v5.15.2/js/all.js" data-auto-replace-svg="nest"></script>
    <script src="js/script.js"></script>
  </body>
</html>