<!DOCTYPE html>

<?php
  session_start(); 
  require_once './_includes/funcoes.php';
  isLogged();
  require_once './_includes/connection.php'; 
?>

<html lang="pt-br">
  <head>
    <meta charset="utf-8"/>
    <title>Sistema PDV</title>

    <link rel="stylesheet" type="text/css" href="./_css/estilo.css">
    <link rel="stylesheet" type="text/css" href="./_css/produtos.css">
    <link rel="shortcut icon" href="./_imagens/fav-icon.png" type="image/x-icon">
  </head>

  <body>
    <div id="interface">

      <?php 
        $nome_pag = "Excluir Produto";
        $icone_pag = "produtos.png";
        $iconeMouseOut = "produtos.png";
        $bread_crumb = "Home > Produtos > Listar Produtos > Excluir Produto";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./produtos.php"><img id="voltar-produto" src="./_imagens/voltar.png"/></a>
      
      <?php 
        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p> <p>Você não é administrador.");
        } else {
                $codBarra = $_GET['codBarra'] ?? null;
                $codBarra2 = $codBarra;

                $codBarraDelete = $_GET['cbd'] ?? null;

                if (isset($codBarra)) {

                  $consulta = $conexao->query("SELECT cod_barra, produto, quant, preco FROM estoque WHERE cod_barra = '$codBarra'");

                  if (!$consulta) {
                    echo msgErro("Não foi possível realizar a consulta ao produto!");
                  } else {
                          echo msgAviso("Atenção!<br/>Esta operação não poderá ser desfeita!");
                          $reg = $consulta->fetch_object();

                          echo "<fieldset class='editar'><legend>Excluir Produto</legend><table class='excluir'>";
                            echo "<tr><td>Cod. Barra:</td><td>$reg->cod_barra</td></tr>";
                            echo "<tr><td>Produto:</td><td>$reg->produto</td></tr>";
                            echo "<tr><td>Quant.:</td><td>$reg->quant</td></tr>";
                            $preco = str_replace(".", ",", $reg->preco);
                            echo "<tr><td>Preço:</td><td>R$ $preco</td></tr>";
                          echo "</table>";

                          echo "<a href='./produtos-delete.php?cbd=$codBarra2'><button style='margin-left: 15px;'>EXCLUIR</button></a></fieldset>";
                    }
                } else {
                        if ($conexao->query("DELETE FROM estoque WHERE cod_barra = '$codBarraDelete'")) {
                          echo msgSucesso("Produto excluído com sucesso!");
                        } else {
                                echo msgErro("Não foi possível excluir o produto!");
                          }
                  }
          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="_javascript/funcoes.js"></script>
  </body>

</html>