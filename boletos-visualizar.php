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
      table {
        border-collapse: collapse;
      }
      tr#cabecalho {
        background: #dddddd;
      }
      td {
        border: 1px solid;
        padding: 6px;
        font-color: red;
      }
    </style>
  </head>

  <body>
    <div id="interface">

      <?php 
        $nome_pag = "Alterar Boleto";
        $icone_pag = "compras.png";
        $iconeMouseOut = "compras.png";
        $bread_crumb = "Home > Compras > Alterar Nota Fiscal > Boletos";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"></a>

      <?php 
        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p> <p>Você não é administrador.");
        } else {
                $nf = $_GET['nf'];
                if (!isset($nf)) {
                  echo msgAviso("Nenhum boleto a ser verificado.");
                } else {
                        $consulta = $conexao->query("SELECT cod_barra_boleto, valor, vcto FROM contas_a_pagar WHERE nf_compra = '$nf'");

                        if (!$consulta) {
                          echo msgErro("Infelizmente, não foi possível realizar a consulta!");
                        } else {
                                echo "<fieldset class='conjunto_campos'><legend>Boletos</legend>
                                  <table id='boletos'>
                                    <tr id='cabecalho'><td>Nº boleto</td><td>Valor</td><td>Venc.</td><td></td></tr>";
                                    while ($reg = $consulta->fetch_object()) {
                                      $valor = str_replace(".", ",", $reg->valor);
                                      $vcto = explode("-", $reg->vcto);
                                      $vcto2 = "";
                                      for ($i=2 ; $i>=0 ; $i--) {
                                        $vcto2 .= $vcto[$i];
                                        if($i != 0) $vcto2 .= "/";
                                      }
                                      echo "
                                          <tr><td>$reg->cod_barra_boleto</td><td>$valor</td><td>$vcto2</td><td><a href='./boleto-edit.php?bol=$reg->cod_barra_boleto&valor=$reg->valor&vcto=$reg->vcto' title='Editar'><img src='./_imagens/editar.png'></a><a href='javascript:confirmacaoBoleto(`$reg->cod_barra_boleto`)' title='Excluir'><img src='./_imagens/deletar.png'></a></td></tr>";
                                    }
                                    echo "</table></fieldset>";
                          }
                  }
          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>