<!DOCTYPE html>

<?php
  session_start();
  require_once './_includes/funcoes.php';
  isLogged();
  require_once './_includes/connection.php';
?>

<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <title>Sistema PDV</title>
    <link rel="stylesheet" type="text/css" href="./_css/estilo.css">
    <link rel="shortcut icon" href="./_imagens/fav-icon.png" type="image/x-icon">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
  </head>

  <body>
    <?php
      $mes = $_GET['mes'] ?? null;
      $ano = $_GET['ano'] ?? date('Y');

      switch($mes) {
        case "01":
          $mes2 = "Janeiro";
          break;
        case "02":
          $mes2 = "Fevereiro";
          break;
        case "03":
          $mes2 = "Março";
          break;
        case "04":
          $mes2 = "Abril";
          break;
        case "05":
          $mes2 = "Maio";
          break;
        case "06":
          $mes2 = "Junho";
          break;
        case "07":
          $mes2 = "Julho";
          break;
        case "08":
          $mes2 = "Agosto";
          break;
        case "09":
          $mes2 = "Setembro";
          break;
        case "10":
          $mes2 = "Outubro";
          break;
        case "11":
          $mes2 = "Novembro";
          break;
        case "12":
          $mes2 = "Dezembro";
          break;
        default:
          $mes2 = "Janeiro";
      }

      $nome_pag = "Top 5 Mensal";
      $icone_pag = "gerencial.png";
      $iconeMouseOut = "gerencial.png";
      $bread_crumb = "Página Inicial > Relatórios > Gerenciais > Relatórios Gerenciais > Top 5 Mensal";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"></a>

      <div id="columnchart_material" style="width: 100%; height: 500px; margin-top: 15px;"></div>

      <script>
      google.charts.load('current', {'packages': ['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['<?php echo $mes2; ?>', 'Quant.'],

          <?php
            $query = "SELECT produtos.produto, sum(produtos_venda.quant) AS quant FROM produtos_venda
            INNER JOIN vendas
            ON produtos_venda.id_venda = vendas.id_venda

            INNER JOIN estoque
            ON produtos_venda.lote_estoque = estoque.lote

            INNER JOIN produtos_compra
            ON estoque.lote = produtos_compra.lote

            INNER JOIN produtos
            ON produtos_compra.cod_barra = produtos.cod_barra

            WHERE date_format(vendas.dt_hr, '%m/%Y') = '$mes/$ano'
            GROUP BY produto
            ORDER BY quant DESC
            LIMIT 5";

            $consulta = $conexao->query($query);

            $produto = array();
            $quant = array();
            $i = 4;
            while ($reg = $consulta->fetch_object()) {
              $produto[$i] = $reg->produto ?? "Nenhum produto";
              $quant[$i] = $reg->quant ?? 0;
              $i--;
            }

            for ($c=0 ; $c<=4 ; $c++) {
          ?>

          ['<?php echo $produto[$c]; ?>', '<?php echo $quant[$c]; ?>'],

          <?php } ?>
        ]);

        var options = {
          chart: {
            title: 'Mais vendidos',
            subtitle: '<?php echo $mes2; ?> de <?php echo $ano; ?>',
          }
        };

        var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
      }
    </script>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>