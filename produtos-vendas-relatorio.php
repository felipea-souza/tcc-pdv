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
  </head>

  <body>
    <?php 
      $nome_pag = "Relatório de Produtos de Vendas";
      $icone_pag = "documento.png";
      $iconeMouseOut = "documento.png";
      $bread_crumb = "Página Inicial > Relatórios > Relatório de Produtos de Vendas";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php
        $chave = $_GET["cBusca"] ?? "";
        $ordem = $_GET["o"] ?? "";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"/></a>
      
      <div id="alinhar">
        <form action="./produtos-vendas-relatorio.php" method="get">
          Buscar: <input type="text" name="cBusca" id="cBusca" size="20" maxlength="30" style="margin-bottom: 8px;"> 
          <input type="image" class="buscar" title="Buscar/Listar todos" src="./_imagens/buscar.png"><br>
          
          Ordenar: 
          <a href="./produtos-vendas-relatorio.php?o=cb&cBusca=<?php echo $chave ?>">Cód. Barra</a> |
          <a href="./produtos-vendas-relatorio.php?o=pr&cBusca=<?php echo $chave ?>">Produto</a> | 
          <a href="./produtos-vendas-relatorio.php?o=lt&cBusca=<?php echo $chave ?>">Lote</a> | 
          <a href="./produtos-vendas-relatorio.php?o=meq&cBusca=<?php echo $chave ?>">Menor quant.</a> |
          <a href="./produtos-vendas-relatorio.php?o=maq&cBusca=<?php echo $chave ?>">Maior quant.</a> |
          <a href="./produtos-vendas-relatorio.php?o=mep&cBusca=<?php echo $chave ?>">Menor Preço</a> |
          <a href="./produtos-vendas-relatorio.php?o=map&cBusca=<?php echo $chave ?>">Maior Preço</a> |
          <a href="./produtos-vendas-relatorio.php?o=idv&cBusca=<?php echo $chave ?>">ID Venda</a>
        </form>

      </div>
      <table class="busca">
        <?php
          $query = "SELECT produtos_compra.cod_barra, produtos.produto, produtos_venda.lote_estoque, produtos_venda.quant, produtos_venda.preco, produtos_venda.id_venda FROM produtos_venda
          INNER JOIN estoque
          ON produtos_venda.lote_estoque = estoque.lote

          INNER JOIN produtos_compra
          ON estoque.lote = produtos_compra.lote

          INNER JOIN produtos
          ON produtos_compra.cod_barra = produtos.cod_barra";

          if(!empty($chave)) {
            $query .= " WHERE produtos_compra.cod_barra LIKE '%$chave%' OR produto LIKE '%$chave%' OR lote_estoque LIkE '%$chave%' OR produtos_venda.quant LIKE '%$chave%' OR produtos_venda.preco LIKE '%$chave%' OR id_venda LIKE '%$chave%'";
          }

          switch ($ordem) {
            case "cb":
                  $query .= " ORDER BY cod_barra";
                  break;
            case "pr":
                  $query .= " ORDER BY produto";
                  break;
            case "lt":
                  $query .= " ORDER BY lote_estoque";
                break;
            case "meq":
                  $query .= " ORDER BY quant ASC";
                break;
            case "maq":
                  $query .= " ORDER BY quant DESC";
                break;
            case "mep":
                  $query .= " ORDER BY preco ASC";
                break;
            case "map":
                  $query .= " ORDER BY preco DESC";
                break;
            case "idv":
                  $query .= " ORDER BY id_venda";
                break;
          }

          $consulta = $conexao->query($query);
            if (!$consulta) {
              echo "<tr><td style='font-style: italic;'>Infelizmente, não foi possível realizar a consulta!</td></tr>";
            } else {
                    if ($consulta->num_rows == 0) {
                        echo "<tr><td style='font-style: italic;'>Nenhum registro encontrado!</td></tr>";
                    } else {
                            echo "<tr id='cabecalho'><td>Cód. barra</td><td>Produto</td><td>Lote</td><td>Quant.</td><td>Preço</td><td>ID Venda</td></tr>";
                            while ($reg = $consulta->fetch_object()) {
                             $preco = str_replace(".", ",", $reg->preco);
                             echo "<tr><td>$reg->cod_barra</td><td>$reg->produto</td><td>$reg->lote_estoque</td><td>$reg->quant</td><td>$preco</td><td>$reg->id_venda</td></tr>";
                            }
                      }
              }    
        ?>
      </table>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>