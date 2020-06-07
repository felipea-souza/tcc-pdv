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
        $nome_pag = "Excluir Boleto";
        $icone_pag = "compras.png";
        $iconeMouseOut = "compras.png";
        $bread_crumb = "Home > Compras > Alterar Nota Fiscal > Boletos > Excluir Boleto";

        require_once './cabecalho.php';
      ?>

      <?php 
        if (!isAdmin()) {
          echo "<a title='Voltar' href='javascript:history.go(-1)'><img id='voltar-home' src='./_imagens/voltar.png'></a>";
          echo msgAviso("Área restrita!</p> <p>Você não é administrador.");
        } else {
                $bol = $_GET['bol'] ?? null;
                if (!isset($bol)) {
                  echo "<a title='Voltar' href='javascript:history.go(-1)'><img id='voltar-home' src='./_imagens/voltar.png'></a>";
                  echo msgAviso("Nenhum boleto a ser excluído!");
                } else {
                        if ($conexao->query("DELETE FROM contas_a_pagar WHERE cod_barra_boleto = '$bol'")) {
                          echo "<a title='Voltar' href='javascript:history.go(-1)'><img id='voltar-home' src='./_imagens/voltar.png'></a>";
                          echo msgSucesso("Boleto excluído com sucesso!");
                        } else {
                                echo "<a title='Voltar' href='javascript:history.go(-1)'><img id='voltar-home' src='./_imagens/voltar.png'></a>";
                                echo msgErro("Não foi possível excluir o produto!");
                          }
                }
          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>