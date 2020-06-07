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

        $nome_pag = "Relatório de Contas a Pagar";
        $icone_pag = "documento.png";
        $iconeMouseOut = "documento.png";
        $bread_crumb = "Home > Relatórios > Relatório de Contas a Pagar";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"/></a>
      
      <div id="alinhar">
        <form action="./contas-relatorio.php" method="get">
          Ordenar: 
          <a href="./contas-relatorio.php?o=codb&cBusca=<?php echo $chave ?>">Cod. barra</a> |
          <a href="./contas-relatorio.php?o=mev&cBusca=<?php echo $chave ?>">Menor valor</a> | 
          <a href="./contas-relatorio.php?o=mav&cBusca=<?php echo $chave ?>">Maior valor</a> | 
          <a href="./contas-relatorio.php?o=vcto&cBusca=<?php echo $chave ?>">Venc.</a> |
          <a href="./contas-relatorio.php?o=nf&cBusca=<?php echo $chave ?>">Nota</a> | 

          Buscar: <input type="text" name="cBusca" id="cBusca" size="20" maxlength="30"/> 
          <input type="image" class="buscar" id="iBusca" name="iBusca" title="Buscar/Listar todos" src="./_imagens/buscar.png">
        </form>

      </div>
      <table class="busca">
        <?php
          $query = "SELECT cod_barra_boleto, valor, date_format(vcto, '%d/%m/%Y') AS vcto, nf_compra from contas_a_pagar";

          if(!empty($chave)) {
            $query .= " WHERE cod_barra_boleto LIKE '%$chave%' OR valor LIKE '%$chave%' OR vcto LIKE '%$chave%' OR nf_compra LIKE '%$chave%'";
          }

          switch ($ordem) {
            case "codb":
                  $query .= " ORDER BY cod_barra_boleto";
                  break;
            case "mev":
                  $query .= " ORDER BY valor ASC";
                  break;
            case "mav":
                  $query .= " ORDER BY valor DESC";
                break;
            case "vcto":
                  $query .= " ORDER BY vcto";
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
                            echo "<tr id='cabecalho'><td>Boleto</td><td>valor</td><td>Venc.</td><td>NF</td></tr>";
                            while ($reg = $consulta->fetch_object()) {
                             $valor = str_replace(".", ",", $reg->valor);
                             echo "<tr><td>$reg->cod_barra_boleto</td><td>R$ $valor</td><td>$reg->vcto</td><td>$reg->nf_compra</td></tr>";
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