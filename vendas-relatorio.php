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
      $nome_pag = "Relatório de Vendas";
      $icone_pag = "documento.png";
      $iconeMouseOut = "documento.png";
      $bread_crumb = "Página Inicial > Relatórios > Relatório de Vendas";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php
        $chave = $_GET["cBusca"] ?? "";
        $ordem = $_GET["o"] ?? "";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"></a>
      
      <div id="alinhar">
        <form action="./vendas-relatorio.php" method="get">          
          Ordenar: 
          <a href="./vendas-relatorio.php?o=dt&cBusca=<?php echo $chave ?>">Data</a> |
          <a href="./vendas-relatorio.php?o=tot&cBusca=<?php echo $chave ?>">Total</a> | 
          <a href="./vendas-relatorio.php?o=fo&cBusca=<?php echo $chave ?>">Forma</a> | 
          <a href="./vendas-relatorio.php?o=vr&cBusca=<?php echo $chave ?>">Valor Receb.</a> |
          <a href="./vendas-relatorio.php?o=vt&cBusca=<?php echo $chave ?>">Valor Troco.</a> |

          Buscar: <input type="text" name="cBusca" id="cBusca" size="20" maxlength="30"> 
          <input type="image" class="buscar" title="Buscar/Listar todos" src="./_imagens/buscar.png">
        </form>

      </div>
        <table class="busca">
          <?php
            $query = "SELECT id_venda, date_format(dt_hr, '%d/%m/%Y %T') AS dt_hr, total, forma, valor_receb, troco FROM vendas";

            if(!empty($chave)) {
              $query .= " WHERE dt_hr LIKE '%$chave%' OR total LIKE '%$chave%' OR valor_receb LIKE '%$chave%' OR troco LIKE '%$chave%'";
            }

            switch ($ordem) {
              case "dt":
                    $query .= " ORDER BY dt_hr";
                    break;
              case "tot":
                    $query .= " ORDER BY total";
                    break;
              case "fo":
                    $query .= " ORDER BY forma";
                  break;
              case "vr":
                    $query .= " ORDER BY valor_receb";
                  break;
              case "vt":
                    $query .= " ORDER BY troco";
                  break;
              /*default :
                  $query .= " ORDER BY id_venda"; <- não precisou do 'default', pois verifiquei que com o parâmetro "o" vazio, o select padrão é realizado (ordenando pelo 'id_venda') */
            }

            $consulta = $conexao->query($query);
              if (!$consulta) {
                echo "<tr><td style='font-style: italic;'>Infelizmente, não foi possível realizar a consulta!</td></tr>";
              } else {
                      if ($consulta->num_rows == 0) {
                          echo "<tr><td style='font-style: italic;'>Nenhum registro encontrado!</td></tr>";
                      } else {
                              echo "<tr id='cabecalho'><td>Id Venda</td><td>Data</td><td>Total</td><td>Forma</td><td>Valor Receb.</td><td>Troco</td></tr>";
                              while ($reg = $consulta->fetch_object()) {
                               $total = str_replace(".", ",", $reg->total);
                               $valor_receb = str_replace(".", ",", $reg->valor_receb);
                               $troco = str_replace(".", ",", $reg->troco);
                               echo "<tr><td>$reg->id_venda</td><td>$reg->dt_hr</td><td>$total</td><td>$reg->forma</td><td>$valor_receb</td><td>$troco</td></tr>";
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