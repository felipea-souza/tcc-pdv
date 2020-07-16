<!DOCTYPE html>

<?php
  session_start();
  require_once './_includes/funcoes.php';
  isLogged();
  require_once './_includes/connection.php';

  $produto = $_GET['selProduto'] ?? null;
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
          ['Mês', '<?php echo $produto; ?>', {role: 'annotation'}],

          <?php
            $quant = array();
            for ($i=1 ; $i<=12 ; $i++) {
              $mes = ($i < 10) ? "0$i": "$i"; 
              $query = "SELECT sum(produtos_venda.quant) AS quant FROM produtos_venda
              INNER JOIN vendas
              ON produtos_venda.id_venda = vendas.id_venda

              INNER JOIN estoque
              ON produtos_venda.lote_estoque = estoque.lote

              INNER JOIN produtos_compra
              ON estoque.lote = produtos_compra.lote

              INNER JOIN produtos
              ON produtos_compra.cod_barra = produtos.cod_barra

              WHERE produtos.produto = '$produto' AND date_format(vendas.dt_hr, '%m/%Y') = '$mes/$ano'";

              $consulta = $conexao->query($query);
              
              $reg = $consulta->fetch_object();
              $quant[] = $reg->quant ?? 0;
            }
          ?>

          ['Jan',  <?php echo $quant[0]; ?>, <?php echo $quant[0]; ?>],
          ['Fev',  <?php echo $quant[1]; ?>, <?php echo $quant[1]; ?>],
          ['Mar',  <?php echo $quant[2]; ?>, <?php echo $quant[2]; ?>],
          ['Abr',  <?php echo $quant[3]; ?>, <?php echo $quant[3]; ?>],
          ['Mai',  <?php echo $quant[4]; ?>, <?php echo $quant[4]; ?>],
          ['Jun',  <?php echo $quant[5]; ?>, <?php echo $quant[5]; ?>],
          ['Jul',  <?php echo $quant[6]; ?>, <?php echo $quant[6]; ?>],
          ['Ago',  <?php echo $quant[7]; ?>, <?php echo $quant[7]; ?>],
          ['Set',  <?php echo $quant[8]; ?>, <?php echo $quant[8]; ?>],
          ['Out',  <?php echo $quant[9]; ?>, <?php echo $quant[9]; ?>],
          ['Nov',  <?php echo $quant[10]; ?>, <?php echo $quant[10]; ?>],
          ['Dez',  <?php echo $quant[11]; ?>, <?php echo $quant[11]; ?>]
        ]);

        var options = {
          title: 'Vendas Anual do Produto - <?php echo $ano; ?>',
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
      $nome_pag = "Período Anual";
      $icone_pag = "gerencial.png";
      $iconeMouseOut = "gerencial.png";
      $bread_crumb = "Página Inicial > Relatórios > Gerenciais > Relatórios Gerenciais > Período Anual";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"></a>

      <div id="curve_chart" style="width: 100%; height: 500px"></div>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>