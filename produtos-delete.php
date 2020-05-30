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
                $codBarra = $_GET['cb'] ?? null;

                if (!isset($codBarra)) { 
                  echo msgAviso("Nenhum produto a ser excluído!");
                } else {
                        if ($conexao->query("DELETE FROM produtos WHERE cod_barra = '$codBarra'")) {
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