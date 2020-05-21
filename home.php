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

    <link rel="stylesheet" type="text/css" href="./_css/estilo.css"/>
    <link rel="stylesheet" type="text/css" href="./_css/pag_home.css"/>
    <link rel="shortcut icon" href="./_imagens/fav-icon.png" type="image/x-icon">
  </head>

  <body>
    <div id="interface">

      <?php 
        $nome_pag = "Página Inicial";
        $icone_pag = "home.png";
        $iconeMouseOut = "home.png";
        $bread_crumb = "Home";

        require_once './cabecalho.php';
      ?>
      
      <section id='corpo'>
        
      </section>

      <?php 
        $consulta = $conexao->query("SELECT cod_barra, produto, quant FROM estoque WHERE quant <= 10");
        if (!$consulta) {
          echo msgErro("Infelizmente não foi possível realizar a consulta!");
        } else {
                if ($consulta->num_rows == 0) {
                  echo "<aside class='sucesso'><p><img src='./_imagens/checked.png'> Sem alertas no momento!</p></aside>";
                } else {
                        echo ("<aside class='aviso'><p><img src='./_imagens/exclamacao.png'> Itens com estoque baixo:</p><table>");
                        while ($reg = $consulta->fetch_object()) {
                              echo "<tr><td>$reg->cod_barra</td><td>$reg->produto</td><td>$reg->quant</td><td>unid</td></tr>";
                        }
                        echo ("</table></aside>");
                  }
          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>
    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>