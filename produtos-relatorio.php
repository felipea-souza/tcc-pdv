<!DOCTYPE html>

<?php
  session_start();
  require_once './_includes/funcoes.php';
  isLogged();
?>

<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <title>Sistema PDV</title>

    <link rel="stylesheet" type="text/css" href="./_css/estilo.css">
    <link rel="stylesheet" type="text/css" href="./_css/produtos.css">
    <link rel="shortcut icon" href="./_imagens/fav-icon.png" type="image/x-icon">
  </head>

  <body>
    <div id="interface">
      
      <?php 
        require_once "./_includes/connection.php";
        $flag = $_GET["iBusca"] ?? "";
        $chave = $_GET["cBusca"] ?? "";
        $ordem = $_GET["o"] ?? "";

        $nome_pag = "Relatório de Produtos";
        $icone_pag = "documento.png";
        $iconeMouseOut = "documento.png";
        $bread_crumb = "Home > Relatórios > Relatório de Produtos";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"/></a>
      
      <div id="alinhar">
        <form action="./produtos-relatorio.php" method="get">
          <input type="hidden" name="iBusca" value="buscar">
          Ordenar: 
          <a href="./produtos-relatorio.php?o=codb&cBusca=<?php echo $chave ?>" name="iBusca">Cod. barra</a> |
          <a href="./produtos-relatorio.php?o=prd&cBusca=<?php echo $chave ?>" name="iBusca">Produto</a> | 
          <a href="./produtos-relatorio.php?o=mep&cBusca=<?php echo $chave ?>" name="iBusca">Menor preço.</a> | 
          <a href="./produtos-relatorio.php?o=map&cBusca=<?php echo $chave ?>" name="iBusca">Maior preço.</a> | 

          Buscar: <input type="text" name="cBusca" id="cBusca" size="20" maxlength="30"/> 
          <input type="image" class="buscar" id="iBusca" name="iBusca" title="Buscar/Listar todos" src="./_imagens/buscar.png"/>
        </form>

        <?php 
          if (isAdmin()) {
            echo "<a class='adicionar' href='./produtos.php' title='Adicionar novo produto'><img id='adicionar' src='./_imagens/adicionar.png'/></a>";
          } 
        ?>
      </div>
      <table class="busca">
        <?php
          if (empty($flag) && empty($ordem)) {
            echo "<tr><td style='font-style: italic;'>Sem consulta!</td></tr>";
          } else {
                  $query = "select cod_barra, produto, preco from produtos";

                  if(!empty($chave)) {
                    $query .= " WHERE cod_barra LIKE '%$chave%' OR produto LIKE '%$chave%'";
                  }

                  switch ($ordem) {
                    case "codb":
                          $query .= " ORDER BY cod_barra";
                          break;
                    case "prd":
                          $query .= " ORDER BY produto";
                          break;
                    case "mep":
                        $query .= " ORDER BY preco ASC";
                        break;
                    case "map":
                        $query .= " ORDER BY preco DESC";
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
                                   if ($_SESSION['tipo'] == 'adm') {
                                           echo "<tr id='cabecalho'><td>Cód. barra</td><td>Produto</td><td>Preço</td><td></td></tr>";
                                           while ($reg = $consulta->fetch_object()) {

                                             $preco = str_replace(".", ",", $reg->preco);
                                             echo "<tr><td>$reg->cod_barra</td><td>$reg->produto</td><td>R$ $preco</td><td><a title='Editar' href='produtos-edit.php?cb=$reg->cod_barra&pdt=$reg->produto&prc=$reg->preco'><img src='./_imagens/editar.png'/></a> <a title='Excluir' href='javascript:confirmacaoProduto(`$reg->cod_barra`);'><img src='./_imagens/deletar.png'/></a></td></tr>";
                                           }
                                    } else {
                                            echo "<tr id='cabecalho'><td>Cód. barra</td><td>Produto</td><td>Preço</td><td></td></tr>";
                                            while ($reg = $consulta->fetch_object()) {
                                             $preco = str_replace(".", ",", $reg->preco);
                                             echo "<tr><td>$reg->cod_barra</td><td>$reg->produto</td><td>R$ $preco</td><td></td></tr>";
                                            }
                                      }
                              }  
                      }        
            }
        ?>
      </table>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="_javascript/funcoes.js"></script>
  </body>

</html>