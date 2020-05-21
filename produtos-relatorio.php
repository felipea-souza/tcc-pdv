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

    <style>
      a.adicionar {
        position: relative;
        top: 6px;
      }
    </style>
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
        $bread_crumb = "Home > Produtos > Relatório de Produtos";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"/></a>
      
      <form id="busca" action="./produtos-relatorio.php" method="get">
        <input type="hidden" name="iBusca" value="buscar">
        Ordenar: 
        <a href="./produtos-relatorio.php?o=p&cBusca=<?php echo $chave ?>" name="iBusca">Produto</a> | 
        <a href="./produtos-relatorio.php?o=meq&cBusca=<?php echo $chave ?>" name="iBusca">Menor quant.</a> | 
        <a href="./produtos-relatorio.php?o=maq&cBusca=<?php echo $chave ?>" name="iBusca">Maior quant.</a> | 

        Buscar: <input type="text" name="cBusca" id="cBusca" size="20" maxlength="30"/> 
        <input type="image" class="buscar" id="iBusca" name="iBusca" title="Buscar/Listar todos" src="./_imagens/buscar.png"/>
        <?php 
        if (isAdmin()) {
          echo "<a class='adicionar' href='./produtos.php' title='Adicionar novo produto'><img src='./_imagens/adicionar.png'/></a>";
        } 
      ?>
      </form>

      
      
      <table class="busca">
        <?php
          if (empty($flag) && empty($ordem)) {
            echo "<tr><td style='font-style: italic;'>Sem consulta!</td></tr>";
          } else {
                  $query = "select cod_barra, produto, quant, preco from estoque";

                  if(!empty($chave)) {
                    $query .= " WHERE cod_barra LIKE '%$chave%' OR produto LIKE '%$chave%'";
                  }

                  switch ($ordem) {
                    case "p":
                          $query .= " ORDER BY produto";
                          break;
                      case "meq":
                          $query .= " ORDER BY quant ASC";
                          break;
                      case "maq":
                          $query .= " ORDER BY quant DESC";
                          break;
                      /*default :
                          $query .= " ORDER BY cod_barra"; <- não precisou do 'default', pois verifiquei que com o parâmetro "o" vazio, o select padrão é realizado (ordenando pelo 'cod_barra') */
                  }

                  $consulta = $conexao->query($query);
                    if (!$consulta) {
                      echo "<tr><td style='font-style: italic;'>Infelizmente, não foi possível realizar a consulta!</td></tr>";
                    }
                      else {
                            if ($consulta->num_rows == 0) {
                                echo "<tr><td style='font-style: italic;'>Nenhum registro encontrado!</td></tr>";
                            } else {
                                   if ($_SESSION['tipo'] == 'adm') {
                                           echo "<tr id='cabecalho'><td>Cód. barra</td><td>Produto</td><td>Quant.</td><td>Preço</td><td></td></tr>";
                                           while ($reg = $consulta->fetch_object()) {

                                             $preco = str_replace(".", ",", $reg->preco);
                                             echo "<tr><td>$reg->cod_barra</td><td>$reg->produto</td><td>$reg->quant</td><td>R$ $preco</td><td><a title='Editar' href='produtos-edit.php?cb=$reg->cod_barra&pdt=$reg->produto&qtd=$reg->quant&prc=$reg->preco'><img src='./_imagens/editar.png'/></a> <a title='Excluir' href='javascript:confirmacao(`$reg->cod_barra`);'><img src='./_imagens/deletar.png'/></a></td></tr>";
                                             //$i++;
                                           }
                                    } else {
                                            echo "<tr id='cabecalho'><td>Cód. barra</td><td>Produto</td><td>Quant.</td><td>Preço</td><td></td></tr>";
                                            while ($reg = $consulta->fetch_object()) {
                                             $preco = str_replace(".", ",", $reg->preco);
                                             echo "<tr><td>$reg->cod_barra</td><td>$reg->produto</td><td>$reg->quant</td><td>R$ $preco</td><td></td></tr>";
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