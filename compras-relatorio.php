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
    <div id="interface">

      <?php
        $chave = $_GET["cBusca"] ?? "";
        $ordem = $_GET["o"] ?? "";

        $nome_pag = "Relatório de Compras";
        $icone_pag = "documento.png";
        $iconeMouseOut = "documento.png";
        $bread_crumb = "Home > Relatórios > Relatório de Compras";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"/></a>
      
      <div id="alinhar">
        <form action="./compras-relatorio.php" method="get">
          Buscar: <input type="text" name="cBusca" id="cBusca" size="20" maxlength="30" style="margin-bottom: 8px;"> 
          <input type="image" class="buscar" title="Buscar/Listar todos" src="./_imagens/buscar.png"><br>
          
          Ordenar: 
          <a href="./compras-relatorio.php?o=cb&cBusca=<?php echo $chave ?>">Cód. Barra</a> |
          <a href="./compras-relatorio.php?o=prd&cBusca=<?php echo $chave ?>">Produto</a> | 
          <a href="./compras-relatorio.php?o=lt&cBusca=<?php echo $chave ?>">Lote</a> | 
          <a href="./compras-relatorio.php?o=meq&cBusca=<?php echo $chave ?>">Menor quant.</a> |
          <a href="./compras-relatorio.php?o=maq&cBusca=<?php echo $chave ?>">Maior quant.</a> |
          <a href="./compras-relatorio.php?o=mep&cBusca=<?php echo $chave ?>">Menor Preço</a> |
          <a href="./compras-relatorio.php?o=map&cBusca=<?php echo $chave ?>">Maior Preço</a> |
          <a href="./compras-relatorio.php?o=nf&cBusca=<?php echo $chave ?>">Nota Fiscal</a>
        </form>

      </div>
      <table class="busca">
        <?php
          $query = "SELECT produtos_compra.cod_barra, produtos.produto, produtos_compra.lote, produtos_compra.quant, produtos_compra.preco, produtos_compra.nf_compra FROM produtos_compra
                    INNER JOIN produtos
                    ON produtos_compra.cod_barra = produtos.cod_barra";

          if(!empty($chave)) {
            $query .= " WHERE produto LIKE '%$chave%' OR lote LIKE '%$chave%' OR quant LIKE '%$chave%' OR nf_compra LIKE '%$chave%'";
          }

          switch ($ordem) {
            case "cb":
                  $query .= " ORDER BY cod_barra";
                  break;
            case "prd":
                  $query .= " ORDER BY produto";
                  break;
            case "lt":
                  $query .= " ORDER BY lote";
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
            case "nf":
                  $query .= " ORDER BY nf_compra";
                break;
            /*default :
                $query .= " ORDER BY cod_barra"; <- não precisou do 'default', pois verifiquei que com o parâmetro "o" vazio, o select padrão é realizado (ordenando pelo 'cod_barra') */
          }

          $consulta = $conexao->query($query);
            if (!$consulta) {
              echo "<tr><td style='font-style: italic;'>Infelizmente, não foi possível realizar a consulta!</td></tr>";
            } else {
                    if ($consulta->num_rows == 0) {
                        echo "<tr><td style='font-style: italic;'>Nenhum registro encontrado!</td></tr>";
                    } else {
                            echo "<tr id='cabecalho'><td>Cód. barra</td><td>Produto</td><td>Lote</td><td>Quant.</td><td>Preço</td><td>NF</td></tr>";
                            while ($reg = $consulta->fetch_object()) {
                             $preco = str_replace(".", ",", $reg->preco);
                             echo "<tr><td>$reg->cod_barra</td><td>$reg->produto</td><td>$reg->lote</td><td>$reg->quant</td><td>$preco</td><td>$reg->nf_compra</td></tr>";
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