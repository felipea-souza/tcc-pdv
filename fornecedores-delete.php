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
      $nome_pag = "Excluir Fornecedor";
      $icone_pag = "fornecedores.png";
      $iconeMouseOut = "fornecedores.png";
      $bread_crumb = "Página Inicial > Fornecedores > Excluir Fornecedor";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./fornecedores.php"><img id="voltar-home" src="./_imagens/voltar.png"/></a>

      <?php 
        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p> <p>Você não é administrador.");
        } else {
                $cnpj = $_GET['cnpj'] ?? null;

                if (!isset($cnpj)) { 
                  echo msgAviso("Nenhum fornecedor a ser excluído!");
                } else {
                        if ($conexao->query("DELETE FROM fornecedores WHERE cnpj = '$cnpj'")) {
                          echo msgSucesso("Fornecedor excluído com sucesso!");
                        } else {
                                echo msgErro("Não foi possível excluir o fornecedor!");
                          }
                }
          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>