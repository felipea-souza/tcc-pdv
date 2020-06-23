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
    <style>
      form {
        width: 500px;
      }
    </style>
  </head>

  <body>
    <?php 
      $nome_pag = "Relatórios Gerenciais";
      $icone_pag = "gerencial.png";
      $iconeMouseOut = "gerencial.png";
      $bread_crumb = "Página Inicial > Relatórios > Gerenciais";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"></a>
      
      <form action="relatorio-gerencial-produtos.php" method="get"><fieldset><legend>Relatório de Vendas</legend>
        <p>Mês: <select>
                      <option value="01">Jan</option>
                      <option value="02">Fev</option>
                      <option value="03">Mar</option>
                      <option value="04">Abr</option>
                      <option value="05">Mai</option>
                      <option value="06">Jun</option>
                      <option value="07">Jul</option>
                      <option value="08">Ago</option>
                      <option value="09">Set</option>
                      <option value="10">Out</option>
                      <option value="11">Nov</option>
                      <option value="12">Dez</option>
                    </select></p>
        <p>Ano: <select>
                  <option>2020</option>
                </select></p>

        <input type="submit" value="GERAR">
      </fieldset></form>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>