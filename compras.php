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
        $nome_pag = "Compras";
        $icone_pag = "compras.png";
        $iconeMouseOut = "compras.png";
        $bread_crumb = "Home > Compras";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"></a>

      <?php
        $nfForm = $_GET['nfForm'] ?? null;

        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p><p>Você não é administrador.");
        } else {
                if (empty($nfForm)){
                  # Verificar se a NF se encontra ou não cadastrado no sistema
                  echo "<form class='cadastro' action='compras.php' method='get'><fieldset><legend>Nota Fiscal</legend>
                          <p>Nota fiscal: <input type='text' name='nfForm' id='nfForm' maxlength='15' size='10' placeholder='Somente nº' onkeypress='return validarNF(event)'> 
                          <input type='image' class='buscar' id='iBusca' name='tBuscar' title='Buscar' src='./_imagens/buscar.png'></p>
                        </fieldset></form>";
                } else {
                        $query = "SELECT compras.nf AS nf, fornecedores.razao_social AS razao_social, compras.dt_emissao AS dt_emissao, compras.dt_receb AS dt_receb, compras.total AS total, compras.pagto AS pagto FROM compras
                        INNER JOIN fornecedores
                        ON compras.cnpj_forn = fornecedores.cnpj
                        WHERE nf = '$nfForm'";
                                   $consulta = $conexao->query($query);

                                   if (!$consulta) {
                                     echo "Não foi possível realizar a consulta!";
                                   } else { 
                                           if ($consulta->num_rows == 0) {
                                            header("Location: ./compras-cadastrar.php?nf=$nfForm");
                                           } else {
                                                   $reg = $consulta->fetch_object();
                                                   header("Location: ./compras-edit.php?nf=$reg->nf&razao=$reg->razao_social&de=$reg->dt_emissao&dr=$reg->dt_receb&tot=$reg->total&forma=$reg->pagto");
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
      function validarNF(e) {
        codBarraForm = document.getElementById('nfForm');

        var charCode = e.charCode ? e.charCode : e.keyCode;

        if (charCode != 8 && charCode != 9) {
            if (charCode < 48 || charCode > 57) {
                return false;
            }
        }
      }
    </script>
  </body>
</html>