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
      $nome_pag = "Relatório de Compras";
      $icone_pag = "documento.png";
      $iconeMouseOut = "documento.png";
      $bread_crumb = "Página Inicial > Relatórios > Relatório de Compras";

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
        <form action="./notas-fiscais-relatorio.php" method="get">
          Ordenar: 
          <a href="./notas-fiscais-relatorio.php?o=nf&cBusca=<?php echo $chave ?>">Nota Fiscal</a> |
          <a href="./notas-fiscais-relatorio.php?o=for&cBusca=<?php echo $chave ?>">Fornecedor</a> | 
          <a href="./notas-fiscais-relatorio.php?o=dtem&cBusca=<?php echo $chave ?>">Emissão</a> | 
          <a href="./notas-fiscais-relatorio.php?o=dtre&cBusca=<?php echo $chave ?>">Receb.</a> |
          <a href="./notas-fiscais-relatorio.php?o=mav&cBusca=<?php echo $chave ?>">Maior Valor</a> |
          <a href="./notas-fiscais-relatorio.php?o=mev&cBusca=<?php echo $chave ?>">Menor Valor</a> | 

          Buscar: <input type="text" name="cBusca" id="cBusca" size="20" maxlength="30"> 
          <input type="image" class="buscar" title="Buscar/Listar todos" src="./_imagens/buscar.png">
        </form>

      </div>
      <table class="busca">
        <?php
          $query = "SELECT compras.nf, fornecedores.razao_social, date_format(compras.dt_emissao, '%d/%m/%Y') AS dt_emissao, date_format(compras.dt_receb, '%d/%m/%Y') AS dt_receb, compras.total, compras.pagto FROM compras
                    INNER JOIN fornecedores
                    ON compras.cnpj_forn = fornecedores.cnpj";

          if(!empty($chave)) {
            $query .= " WHERE nf LIKE '%$chave%' OR razao_social LIKE '%$chave%' OR dt_emissao LIKE '%$chave%' OR dt_receb LIKE '%$chave%'";
          }

          switch ($ordem) {
            case "nf":
                  $query .= " ORDER BY nf";
                  break;
            case "for":
                  $query .= " ORDER BY razao_social";
                  break;
            case "dtem":
                  $query .= " ORDER BY dt_emissao";
                break;
            case "dtre":
                  $query .= " ORDER BY dt_receb";
                break;
            case "mav":
                  $query .= " ORDER BY total DESC";
                break;
            case "mev":
                  $query .= " ORDER BY total ASC";
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
                            echo "<tr id='cabecalho'><td>NF</td><td>Fornecedor</td><td>Emissão</td><td>Receb.</td><td>Total</td><td>Pagto</td></tr>";
                            while ($reg = $consulta->fetch_object()) {
                             $total = str_replace(".", ",", $reg->total);
                             $pagto = ($reg->pagto == "bol") ? "Boleto" : "Espécie";
                             echo "<tr><td>$reg->nf</td><td>$reg->razao_social</td><td>$reg->dt_emissao</td><td>$reg->dt_receb</td><td>$total</td><td>$pagto</td></tr>";
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