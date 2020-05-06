<!DOCTYPE html>

<?php
  session_start();
  #require_once './_includes/verifica_login.php';
  require_once './_includes/funcoes.php';
  isLogged();
?>

<html lang="pt-br">
  <head>
    <meta charset="utf-8"/>
    <title>Sistema PDV</title>

    <link rel="stylesheet" type="text/css" href="./_css/estilo.css"/>
    <link rel="stylesheet" type="text/css" href="./_css/produtos.css"/>
  </head>

  <body>
    <div id="interface">
      
      <?php 
        $nome_pag = "Página de Produtos";
        $icone_pag = "produtos.png";
        $iconeMouseOut = "produtos.png";
        $bread_crumb = "Home > Produtos";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./home.php"><img id="voltar-estoque" src="./_imagens/voltar.png"/></a>
      
      <p style="margin-top: 45px;"><a href="./produtos-cadastrar.php"><button id="bAdicionar" <?php if (!isAdmin()) {echo "disabled";} ?> >Adicionar</button></a></p>
      <!-- <p><button id="bAlterar" <?php if (!isAdmin()) {echo "disabled";} ?> >Alterar</button></p>
      <p><button id="bExcluir" <?php if (!isAdmin()) {echo "disabled";} ?> >Excluir</button></p> -->
      <form id="busca" action="./estoque.php" method="get">
        <p>Consultar: <input type="text" name="cBusca" id="cBusca" size="20" maxlength="30"/> <input type="image" id="buscar" name="tBuscar" title="Buscar" src="./_imagens/buscar.png"/></p>
      </form>
      <p><a href="./estoque.php"><button id="bListar">Listar todos</button></a></p>
    </div>

    <?php include_once "./rodape.php"; ?>
    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>

</html>