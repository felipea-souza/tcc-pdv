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
      $nome_pag = "Relatório de Produtos";
      $icone_pag = "gerencial.png";
      $iconeMouseOut = "gerencial.png";
      $bread_crumb = "Página Inicial > Relatórios Gerenciais > Relatório de Produtos";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"></a>

      <div id="columnchart_material" style="width: 100%; height: 500px;"></div>

      <script>
      google.charts.load('current', {'packages': ['bar']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Fevereiro', 'Quant'],

          <?php
            $query = "SELECT produtos.cod_barra, produtos.produto, sum(produtos_venda.quant) AS quant,  date_format(vendas.dt_hr, '%m') FROM produtos_venda
            INNER JOIN vendas
            ON produtos_venda.id_venda = vendas.id_venda

            INNER JOIN estoque
            ON produtos_venda.lote_estoque = estoque.lote

            INNER JOIN produtos_compra
            ON estoque.lote = produtos_compra.lote

            INNER JOIN produtos
            ON produtos_compra.cod_barra = produtos.cod_barra

            WHERE date_format(vendas.dt_hr, '%m') = '06'
            GROUP BY cod_barra
            ORDER BY quant LIMIT 5";

            $consulta = $conexao->query($query);

            if (!$consulta) {
              echo "Não foi possível realizar a consulta";
            } else {
            while ($reg = $consulta->fetch_object()) {
              $produto = $reg->produto;
              $quant = $reg->quant;
          ?>

          ['<?php echo $produto; ?>', '<?php echo $quant; ?>'],

          <?php }} ?>
        ]);

        var options = {
          chart: {
            title: 'Mais vendidos',
            subtitle: 'Mês fevereiro 2020',
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