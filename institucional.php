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
  </head>

  <body>
    <div id="interface">

      <?php 
        $nome_pag = "Institucional";
        $icone_pag = "documento.png";
        $iconeMouseOut = "documento.png";
        $bread_crumb = "Home > Institucional";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./home.php"><img id="voltar-home" src="./_imagens/voltar.png"/></a>
      
      <div>
        <h1>Quem Somos</h1>
        <p>O armazém Santo Antônio é uma micro empresa familiar formada no ano 2000 que tem o intuito de trazer aos nossos clientes produtos de qualidade e variedade, ótimos preços, atendimento com excelência e um ambiente aconchegante para atender o público.</p>
      
        <h1>Missão</h1>
        <p>Nossa missão é oferecer produtos e atendimento que superem as expectativas de nossos clientes, garantindo a qualidade e a procedência de todo o nosso estoque e investindo na qualidade, eficiência do atendimento e ótimos preços.</p>
      
        <h1>Visão</h1>
        <p>Criar texto da "Visão".</p>
      </div>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>