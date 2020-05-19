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
        $nome_pag = "nome_pag";
        $icone_pag = "icone_pag.png";
        $iconeMouseOut = "iconeMouseOut.png";
        $bread_crumb = "Home > pag2 > pag3";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href=""><img id="voltar-home" src="./_imagens/voltar.png"></a>
      
      <p>Conteúdo da página</p>

      <?php 

      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>