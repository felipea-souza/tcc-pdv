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

    <link rel="stylesheet" type="text/css" href="./_css/estilo.css"/>
    <link rel="stylesheet" type="text/css" href="./_css/pag_home.css"/>
    <link rel="shortcut icon" href="./_imagens/fav-icon.png" type="image/x-icon">
  </head>

  <body>
    <?php
      $nome_pag = "Página Inicial";
      $icone_pag = "home.png";
      $iconeMouseOut = "home.png";
      $bread_crumb = "Página Inicial";
      
      require_once './menu.php';
    ?>
    
    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>
      
      <main id='corpo'>
        <a class="atividade" href="vendas.php"><div id="vendas">
          <p>Vendas</p>
          <img class="atividade" src="./_imagens/vendas.png">
        </div></a>

        <a class="atividade" href="compras.php"><div id="compras">
          <p>Compras</p>
          <img class="atividade" src="./_imagens/compras.png" style="width: 50px;">
        </div></a>
      </main>

      <?php 
        $consulta = $conexao->query("SELECT produtos.cod_barra AS cod_barra, produtos.produto AS produto, sum(estoque.quant) AS quant FROM estoque
                                     INNER JOIN produtos_compra 
                                     ON estoque.lote = produtos_compra.lote

                                     INNER JOIN produtos
                                     ON produtos_compra.cod_barra = produtos.cod_barra
                                     GROUP BY cod_barra
                                     HAVING quant <= 15");
        if (!$consulta) {
          echo msgErro("Infelizmente, não foi possível realizar a consulta!");
        } else {
                if ($consulta->num_rows == 0) {
                  echo "<aside class='sucesso'><p><img src='./_imagens/checked.png'> Sem alertas de estoque no momento!</p></aside>";
                } else {
                        echo ("<aside class='aviso'><p><img src='./_imagens/exclamacao.png'> Itens com estoque baixo:</p><table>");
                        while ($reg = $consulta->fetch_object()) {
                              echo "<tr><td>$reg->cod_barra</td><td>$reg->produto</td><td>$reg->quant</td><td>unid</td></tr>";
                        }
                        echo ("</table></aside>");
                  }
          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>
    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>