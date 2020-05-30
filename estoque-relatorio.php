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
        $flag = $_GET["iBusca"] ?? "";
        $chave = $_GET["cBusca"] ?? "";
        $ordem = $_GET["o"] ?? "";

        $nome_pag = "Relatório de Estoque";
        $icone_pag = "produtos.png";
        $iconeMouseOut = "produtos.png";
        $bread_crumb = "Home > Relatório de Estoque";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"></a>

      <form class="buscar" action="./estoque-relatorio.php" method="get">
        <input type="hidden" name="iBusca" value="buscar">
        Ordenar: 
        <a href="./estoque-relatorio.php?o=lot&cBusca=<?php echo $chave ?>" name="iBusca">Lote</a> |
        <a href="./estoque-relatorio.php?o=cb&cBusca=<?php echo $chave ?>" name="iBusca">Cód. barra/Produto</a> |
        <a href="./estoque-relatorio.php?o=meq&cBusca=<?php echo $chave ?>" name="iBusca">Menor quant.</a> | 
        <a href="./estoque-relatorio.php?o=maq&cBusca=<?php echo $chave ?>" name="iBusca">Maior quant.</a> | 
        <a href="./estoque-relatorio.php?o=vld&cBusca=<?php echo $chave ?>" name="iBusca">Validade</a> | 

        Buscar: <input type="text" name="cBusca" id="cBusca" size="20" maxlength="30"> 
        <input type="image" class="buscar" id="iBusca" name="iBusca" title="Buscar/Listar todos" src="./_imagens/buscar.png"/>
      </form>
      
      <table class="busca">
      <?php 
        $query = "SELECT estoque.lote AS lote, produtos_compra.cod_barra AS cod_barra, produtos.produto AS produto, estoque.quant AS quant, date_format(estoque.validade, '%d/%m/%Y') AS validade FROM estoque
                  INNER JOIN produtos_compra 
                  ON estoque.id_prod_compra = produtos_compra.id_prod_compra

                  INNER JOIN produtos
                  ON produtos_compra.cod_barra = produtos.cod_barra";

        if(!empty($chave)) {
          $query .= " WHERE lote LIKE '%$chave%' OR produto LIKE '%$chave%' OR validade LIKE '%$chave%'";
        }

        switch ($ordem) {
          case "lot":
                $query .= " ORDER BY lote";
                break;
          case "cb":
                $query .= " ORDER BY cod_barra";
                break;
          case "meq":
                $query .= " ORDER BY quant ASC";
                break;          
          case "maq":
                $query .= " ORDER BY quant DESC";
                break;
          case "vld":
                $query .= " ORDER BY validade";
              break;
          /*default :
              $query .= " ORDER BY lote"; <- não precisou do 'default', pois verifiquei que com o parâmetro "o" vazio, o select padrão é realizado (ordenando pelo 'lote') */
        }

        $consulta = $conexao->query($query);
        if (!$consulta) {
          echo "<tr><td style='font-style: italic;'>Infelizmente, não foi possível realizar a consulta!</td></tr>";
        } else {
                if ($consulta->num_rows == 0) {
                    echo "<tr><td style='font-style: italic;'>Nenhum registro encontrado!</td></tr>";
                } else {
                        echo "<tr id='cabecalho'><td>Lote</td><td>Cód. barra</td><td>Produto</td><td>Quant.</td><td>Validade</td><td></td></tr>";
                        while ($reg = $consulta->fetch_object()) {
                         echo "<tr><td>$reg->lote</td><td>$reg->cod_barra</td><td>$reg->produto</td><td>$reg->quant</td><td>$reg->validade</td><td></td></tr>";
                                
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