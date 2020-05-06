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

    <link rel="stylesheet" type="text/css" href="./_css/estilo.css"/>
    <link rel="stylesheet" type="text/css" href="./_css/produtos.css"/>

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

        $nome_pag = "Página de Produtos";
        $icone_pag = "produtos.png";
        $iconeMouseOut = "produtos.png";
        $bread_crumb = "Home > Produtos";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./home.php"><img id="voltar-home" src="./_imagens/voltar.png"/></a>
      
      <form id="busca" action="./produtos.php" method="get">
        <input type="hidden" name="iBusca" value="buscar">
        Ordenar: 
        <a href="./produtos.php?o=p&cBusca=<?php echo $chave ?>" name="iBusca">Produto</a> | 
        <a href="./produtos.php?o=meq&cBusca=<?php echo $chave ?>" name="iBusca">Menor quant.</a> | 
        <a href="./produtos.php?o=maq&cBusca=<?php echo $chave ?>" name="iBusca">Maior quant.</a> | 

        Buscar: <input type="text" name="cBusca" id="cBusca" size="20" maxlength="30"/> 
        <input type="image" class="buscar" id="iBusca" name="iBusca" title="Buscar/Listar todos" src="./_imagens/buscar.png"/>
        <?php 
        if (isAdmin()) {
          echo "<a class='adicionar' href='./produtos-cadastrar.php' title='Adicionar novo produto'><img src='./_imagens/adicionar.png'/></a>";
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
                                           $i = 0;
                                           while ($reg = $consulta->fetch_object()) {
                                             $codBarra = $reg->cod_barra;

                                             $preco = str_replace(".", ",", $reg->preco);
                                             echo "<tr><td>$reg->cod_barra</td><td>$reg->produto</td><td>$reg->quant</td><td>R$ $preco</td><td><a title='Editar' href='produtos-edit.php?cb=$codBarra'><img src='./_imagens/editar.png'/></a> <a title='Excluir' href='produtos-delete.php?cb=$codBarra'><img src='./_imagens/deletar.png'/></a></td></tr>";
                                             $i++;
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