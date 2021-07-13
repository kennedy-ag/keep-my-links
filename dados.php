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
  <meta http-equiv="X-UA-Compatible" content="IE=Edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Keep My Links - Dados</title>
  <link rel="stylesheet" type="text/css" href="https://www.chartjs.org/samples/latest/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <script src="https://www.chartjs.org/dist/2.9.4/Chart.min.js"></script>
  <script src="https://www.chartjs.org/samples/latest/utils.js"></script>
</head>
<body style="margin-top: 70px;">

  <nav class="navbar navbar-expand-lg navbar-dark bg-info shadow-lg fixed-top">
    <a class="navbar-brand js-scroll-trigger mx-2" href="#"><i class="fa fa-chart-bar"></i>  Dados</a>
    <button class="navbar-toggler mx-2" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link js-scroll-trigger mx-2 active" href="/painel.php"><i class="fa fa-home"></i>  Home</a>
        </li>
      </ul>
    </div>
  </nav>

  <div class="card shadow m-4">
    <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
      <h6 class="m-0 font-weight-bold text-primary">Gráfico das categorias dos seus links</h6>
    </div>
    <!-- Card Body -->
    <div class="card-body">
      <div class="chart-area">
        <div class="content">
          <div class="wrapper">    
            <canvas id="myChart" width="400" height="400"></canvas>
          </div>
          <div id="chart-analyser" class="analyser"></div>
        </div>
      </div>
    </div>
  </div>


  <?php
    try {
      $categorias = array();
      $quantidades = array();

      $backgrounds = array(
        'rgba(255, 99, 132, 0.5)',
        'rgba(54, 162, 235, 0.5)',
        'rgba(255, 206, 86, 0.5)',
        'rgba(75, 192, 192, 0.5)',
        'rgba(153, 102, 255, 0.5)',
        'rgba(255, 159, 64, 0.5)');


      $consulta = $conn->query("SELECT categoria, count(categoria) as quantidade FROM videos WHERE usuario='{$login_cookie}' group by categoria;");
      while ($linha = $consulta->fetch(PDO::FETCH_ASSOC)) {
        array_push($categorias, $linha['categoria']);
        array_push($quantidades, $linha['quantidade']);
      }
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
    }
  ?>


  <script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: [<?php
            for ($i=0; $i < count($categorias); $i++) { 
              echo "'{$categorias[$i]}', ";
            }
          ?>],
          datasets: [{
            label: 'Total de links',
            pointRadius: 3,
            pointBackgroundColor: "rgba(78, 115, 223, 1)",
            pointBorderColor: "rgba(78, 115, 223, 1)",
            pointHoverRadius: 3,
            pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
            pointHoverBorderColor: "rgba(78, 115, 223, 1)",
            pointHitRadius: 10,
            pointBorderWidth: 2,
            data: [<?php
              for ($i=0; $i < count($quantidades); $i++) { 
                echo "'{$quantidades[$i]}', ";
              }
            ?>],
            backgroundColor: [<?php
              for ($i=0; $i < count($categorias); $i++) { 
                echo "'{$backgrounds[array_rand($backgrounds, 1)]}', ";
              }
            ?>],
            borderWidth: 2
          }]
        },
        options: {
        maintainAspectRatio: false,
        layout: {
          padding: {
            left: 10,
            right: 25,
            top: 25,
            bottom: 0
          }
        },
        legend: {
          display: false
        },
        tooltips: {
          backgroundColor: "rgb(255,255,255)",
          bodyFontColor: "#858796",
          titleMarginBottom: 10,
          titleFontColor: '#6e707e',
          titleFontSize: 14,
          borderColor: '#dddfeb',
          borderWidth: 1,
          xPadding: 15,
          yPadding: 15,
          displayColors: false,
          intersect: false,
          mode: 'index',
          caretPadding: 10
        }
      }
    });
  </script>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
  <script src="https://use.fontawesome.com/releases/v5.15.2/js/all.js" data-auto-replace-svg="nest"></script>
</body>
</html>