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
        $nome_pag = "Produtos";
        $icone_pag = "produtos.png";
        $iconeMouseOut = "produtos.png";
        $bread_crumb = "Home > Produtos";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"/></a>

      <?php
        $codBarraForm = $_GET['codBarraForm'] ?? null;

        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p><p>Você não é administrador.");
        } else {
                if (empty($codBarraForm)){
                  # Verificar se o produto se encontra ou não cadastrado no sistema
                  echo "<form class='cadastro' action='produtos.php' method='get'><fieldset><legend>Produto</legend>
                          <p>Cód. barra: <input type='text' name='codBarraForm' id='codBarraForm' size='13' maxlength='13' placeholder='Somente nº' onkeypress='return validarCodBarra(event)'> 
                          <a href='javascript:validarCampoCodBarra()'><img src='./_imagens/buscar.png' id='busca' title='Buscar'></a>
                          <input type='submit' id='iBusca' name='tBuscar' title='Buscar' src='./_imagens/buscar.png' style='display: none;'></p>
                        </fieldset></form>";
                } else {
                        $query = "SELECT cod_barra, produto, preco FROM produtos WHERE cod_barra = '$codBarraForm'";
                                   $consulta = $conexao->query($query);

                                   if (!$consulta) {
                                     echo "Não foi possível realizar a consulta!";
                                   } else { 
                                           if ($consulta->num_rows == 0) {
                                            header("Location: ./produtos-cadastrar.php?cb=$codBarraForm");
                                           } else {
                                                   $reg = $consulta->fetch_object();
                                                   header("Location: ./produtos-edit.php?cb=$reg->cod_barra&pdt=$reg->produto&prc=$reg->preco"); 
                                             }
                                     }
                }
                

          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
    <script>
      //Funções para validação de formatos de campos (onkeypress)
      function validarCodBarra(e) {
        codBarraForm = document.getElementById('codBarraForm');

        var charCode = e.charCode ? e.charCode : e.keyCode;

        if (charCode != 8 && charCode != 9) {
            if (charCode < 48 || charCode > 57) {
                return false;
            }
        }
      }

      // Funções para verificação de campos vazios de formulários (submit)
      function validarCampoCodBarra() {
        var codBarraForm = document.getElementById('codBarraForm').value;

        if (codBarraForm.length == 0 || codBarraForm.length < 13) {
          window.alert(`O código de barras é composto por 13 dígitos.`);
        } else {
          document.getElementById('iBusca').click();
        }
      }
    </script>
  </body>
</html>