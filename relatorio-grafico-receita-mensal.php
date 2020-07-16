<!DOCTYPE html>

<?php
  session_start();
  require_once './_includes/funcoes.php';
  isLogged();
  require_once './_includes/connection.php';

  $ano = $_GET['ano'] ?? date('Y');
?>

<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <title>Sistema PDV</title>
    <link rel="stylesheet" type="text/css" href="./_css/estilo.css">
    <link rel="shortcut icon" href="./_imagens/fav-icon.png" type="image/x-icon">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Mês', 'Receita, em reais (R$)', {role: 'annotation'}],

          <?php
            $quant = array();
            for ($i=1 ; $i<=12 ; $i++) {
              $mes = ($i < 10) ? "0$i": "$i"; 
              $query = "SELECT sum(total) AS total FROM vendas
              WHERE date_format(dt_hr, '%m/%Y') = '$mes/$ano'";

              $consulta = $conexao->query($query);
              
              $reg = $consulta->fetch_object();
              $total[] = $reg->total ?? 0;
            }
          ?>

          ['Jan',  <?php echo $total[0]; ?>, <?php echo $total[0]; ?>],
          ['Fev',  <?php echo $total[1]; ?>, <?php echo $total[1]; ?>],
          ['Mar',  <?php echo $total[2]; ?>, <?php echo $total[2]; ?>],
          ['Abr',  <?php echo $total[3]; ?>, <?php echo $total[3]; ?>],
          ['Mai',  <?php echo $total[4]; ?>, <?php echo $total[4]; ?>],
          ['Jun',  <?php echo $total[5]; ?>, <?php echo $total[5]; ?>],
          ['Jul',  <?php echo $total[6]; ?>, <?php echo $total[6]; ?>],
          ['Ago',  <?php echo $total[7]; ?>, <?php echo $total[7]; ?>],
          ['Set',  <?php echo $total[8]; ?>, <?php echo $total[8]; ?>],
          ['Out',  <?php echo $total[9]; ?>, <?php echo $total[9]; ?>],
          ['Nov',  <?php echo $total[10]; ?>, <?php echo $total[10]; ?>],
          ['Dez',  <?php echo $total[11]; ?>, <?php echo $total[11]; ?>]
        ]);

        var options = {
          title: 'Receita Mensal - <?php echo $ano; ?>',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
  </head>

  <body>
    <?php 
      $nome_pag = "Vendas Período Anual";
      $icone_pag = "gerencial.png";
      $iconeMouseOut = "gerencial.png";
      $bread_crumb = "Página Inicial > Relatórios > Gerenciais > Relatórios Gerenciais > Vendas Período Anual";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href=""><img id="voltar-home" src="./_imagens/voltar.png"></a>      

      <div id="curve_chart" style="width: 100%; height: 500px"></div>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>