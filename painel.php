<?php
  $conn = new PDO('mysql:host=localhost;dbname=keepmylinks', 'root', '');
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  if(isset($_COOKIE['username'])){
    $login_cookie = $_COOKIE['username'];
    $tema_cookie = $_COOKIE['tema'];
  } else {
    header("Location:index.html");
  }
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
  <body onload="alterar_contraste();" class="contraste" style="margin-top: 56px; padding-bottom: 70px;">
    

    <nav id="menu" class="navbar navbar-expand-lg navbar-dark fixed-top shadow-lg" style="background-color: #44475a;">
      <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="fa fa-link"></i> KML</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item mx-2 my-1">
              <form method='POST' action='/php/tema.php'>
                <input type='number' name='theme' value='1' class='d-none'>
                <button id="contraste" type="submit" class="btn contraste">
                  <i class="fa fa-adjust"></i> Alterar tema
                </button>
              </form>
            </li>
            <li class="nav-item mx-2 my-1">
              <button type="button" class="btn contraste" data-bs-toggle="modal" data-bs-target="#modal-adicionar">
                <i class="fa fa-plus"></i> Novo
              </button>
            </li>
            <li class="nav-item mx-2 my-1">
              <button type="button" class="btn contraste" data-bs-toggle="modal" data-bs-target="#modal-atualizar">
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

      <div class="dropdown" style="color: blue;">
          <i class="fa fa-user"></i> Logado como: &nbsp;
          <a class="dropdown-toggle text-decoration-none" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
          <strong><?php echo $login_cookie; ?></strong>
        </a>

        <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="/alterar_senha.php"><i class="fa fa-cog"></i> Alterar senha</a></li>
          <li><a class="dropdown-item" href="/php/logout.php"><i class="fa fa-sign-out-alt"></i> Sair</a></li>
        </ul>
      </div>
    </div>


    <div class="container">
      <h4 id="quantidade-de-registros" class="text-center p-2 m-3 text-secondary"></h4>

      <div id="filters" class="p-3">
        <p class="contraste"><i class="fa fa-filter text-secondary"></i> Filtros:
          <?php
            if(isset($_GET['nota']) or isset($_GET['duracao']) or isset($_GET['categoria'])){
              echo "<a href='painel.php' class='btn btn-outline-danger m-3'>Limpar filtros</a>";
            }
          ?>
        </p>

        <form id="filtros" class="row m-3" method="GET" action="painel.php">
          <select name="nota" class="form-select col-md-2 m-1 sel">
            <option value="Nota" selected>Nota</option>
            <option value="60">51 - 60</option>
            <option value="70">61 - 70</option>
            <option value="80">71 - 80</option>
            <option value="90">81 - 90</option>
            <option value="100">91 - 100</option>
            
          </select>

          <select name="duracao" class="form-select col-md-2 m-1 sel">
            <option value="Duracao" selected>Duração</option>
            <option value="5">Até 5 min.</option>
            <option value="10">Entre 5 e 10 min.</option>
            <option value="15">Entre 10 e 15 min.</option>
            <option value="20">Entre 15 e 20 min.</option>
            <option value="30">Entre 20 e 30 min.</option>
            <option value="31">Acima de 30 min.</option>
          </select>

          <select name="categoria" class="form-select col-md-2 m-1 sel">
            <option value="Categoria" selected>Categoria</option>
            <?php
              try {
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
      </div>

      <div class="container">
        <div class="table-responsive">
        <div id="w-escolha-aleatoria">
          <table id="escolha-aleatoria" class="table contraste"></table>
        </div>
        </div>

        <div class="container-fluid my-4">

          <ul class="nav justify-content-end">
            <li class="nav-item mx-2">
              <form class="d-flex" method="GET" action="painel.php">
                <input class="form-control me-2" name="busca" type="search" placeholder="Digite sua busca">
                <button class="btn btn-outline-primary" type="submit">Buscar</button>
              </form>
            </li>
            <li class="nav-item my-md-0 my-3">
              <button id="pick-random" onclick="escolher()" class="btn contraste"><i class='fa fa-dice me-2'></i>Aleatório</button>
            </li>
          </ul>
        </div>
      </div>


      <div class="table-responsive">
        <table id="tabela" class="table table-striped table-hover">
          <thead>
            <tr id="cabecalho-tabela" class="text-light" style="background-color: #44475a;">
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

                // Aplicação dos filtros =====================================================>
                if (isset($_GET['nota'])) {
                  if ($_GET['nota']=='Nota') {
                    $a = '';
                  } else {
                    $max = intval($_GET['nota']);
                    $min = $max - 9;
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

                if (isset($_GET['busca'])) {
                  if ($_GET['busca']=='') {
                    $a = '';
                  } else {
                    $busca = $_GET['busca'];
                    $q = $q . " AND (nome LIKE '%{$busca}%' OR categoria LIKE '%{$busca}%' OR link LIKE '%{$busca}%')";
                  }
                }

                $q = $q . ";";

                $consulta = $conn->query($q);
                while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
                  echo "
                  <tr class='registro'>
                    <td class='text-info fw-bolder p-3'>{$linha['id']}</td>
                    <td class='text-center p-3'>{$linha['nome']}</td>
                    <td class='text-center p-3'><a target='_blank' href='{$linha['link']}'>Visualizar</a></td>
                    <td class='text-center p-3'>{$linha['duracao']} min.</td>
                    <td class='text-center p-3'>{$linha['nota']} pts</td>
                    <td class='text-center p-3'>{$linha['categoria']}</td>
                  </tr>";
              }
              } catch(PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
              }
              ?>
          </tbody>
        </table>
      </div>
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
                <input name="duracao" type="number" class="form-control" min="0" id="duracao-input" placeholder="Duração em minutos. 0, se não se aplicar" required="required">
              </div>
              <div class="form-group my-3">
                <label for="nota-input">Nota</label>
                <input name="nota" type="number" class="form-control" id="nota-input" placeholder="Avalie o vídeo de 0 a 100" required="required" min="0" max="100">
              </div>
              <div class="form-group my-3">
                <label for="categoria-input">Categoria</label>
                <input list="categories" name="categoria" type="text" class="form-control" id="categoria-input" placeholder="Insira a categoria principal. Ex. games" required="required" maxlength="20">
                <datalist id="categories">
                <?php
                  try {
                    $lista = array();
                    $consulta = $conn->query("SELECT categoria FROM videos WHERE usuario='{$login_cookie}';");
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
                </datalist>
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

    <a id="back-to-top" href="#">^</a>
    <a id="to-bot" href="#bot">v</a>
    <a id="to-dados" href="/dados.php"><i class="fa fa-chart-bar"></i></a>
    <footer id="bot"></footer>


    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/js/bootstrap.bundle.min.js" integrity="sha384-JEW9xMcG8R+pH31jmWH6WWP0WintQrMb4s7ZOdauHnUtxwoG2vI5DkLtS3qm9Ekf" crossorigin="anonymous"></script>
    <script src="https://use.fontawesome.com/releases/v5.15.2/js/all.js" data-auto-replace-svg="nest"></script>
    <script src="js/script.js"></script>

    <!-- Função de alterar tema -->
    <script>
      function alterar_contraste(){
        let elementos = document.getElementsByClassName('contraste');
        let tema = document.getElementsByClassName('dk');
        
        if('1'==<?php echo $tema_cookie; ?>){
          document.querySelector('.contraste').classList.add("dk");
          document.querySelector('p.contraste').classList.add("text-light");
          document.querySelector('table.contraste').classList.add("text-light", "dk");
          document.querySelector('#tabela').classList.add("table-dark");
        } else {
          document.querySelector('.contraste').classList.remove("dk");
          document.querySelector('p.contraste').classList.remove("text-light");
          document.querySelector('table.contraste').classList.remove("text-light", "dk");
          document.querySelector('#tabela').classList.remove("table-dark");
        }

      }
    </script>

  </body>
</html>