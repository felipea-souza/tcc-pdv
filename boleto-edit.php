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
        $nome_pag = "Alterar Boleto";
        $icone_pag = "compras.png";
        $iconeMouseOut = "compras.png";
        $bread_crumb = "Home > Compras > Alterar Nota Fiscal > Boletos > Editar Boleto";

        require_once './cabecalho.php';
      ?>

      <?php 
        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p> <p>Você não é administrador.");
        } else {
                if (isset($_GET['bol'])) {
                  $bol = $_GET['bol'];
                  $valor = $_GET['valor'];
                  $vcto = $_GET['vcto'];
                  echo "<a title='Voltar' href='javascript:history.go(-1)'><img id='voltar-home' src='./_imagens/voltar.png'></a>";
                  echo "<form action='./boleto-edit.php' method='get'><fieldset class='conjunto_campos' id='conjunto2'><legend>Editar Boleto</legend>
                          <table id='duplicatas'>
                            <tr><td>Nº boleto</td><td>Valor</td><td>Venc.</td></tr>
                            <tr><td><input type='text' id='boletoForm' name='boletoForm' maxlength='50' size='50' value='$bol' readonly style='background: #ebebe4;'></td>
                            <td><input type='text' id='valorForm' name='valorForm' maxlength='10' size='10' value='$valor'></td>
                            <td><input type='date' id='vctForm' name='vctForm' value='$vcto'></td></tr>
                          </table>

                          <p><input type='button' value='Salvar Alteração' onclick='validarCamposBoleto()'></p>
                          <p style='display: none;'><input id='submit' type='submit' value='SUBMIT'></p></fieldset></form>";
                } else {
                        $boletoForm = $_GET['boletoForm'] ?? null;
                        if (!isset($boletoForm)) {
                          echo "<a title='Voltar' href='javascript:history.go(-1)'><img id='voltar-home' src='./_imagens/voltar.png'></a>";
                          echo msgAviso("Nenhum boleto foi previamente verificado para alteração!");
                        } else {
                                $valorForm = str_replace(",", ".", $_GET['valorForm']);
                                $vctForm = $_GET['vctForm'];
                                if ($conexao->query("UPDATE contas_a_pagar SET valor = '$valorForm', vcto = '$vctForm' WHERE cod_barra_boleto = '$boletoForm'")) {
                                  echo "<a title='Voltar' href='javascript:history.go(-1)'><img id='voltar-home' src='./_imagens/voltar.png'></a>";
                                  echo msgSucesso("Produto alterado com sucesso!");
                                } else {
                                        echo "<a title='Voltar' href='javascript:history.go(-1)'><img id='voltar-home' src='./_imagens/voltar.png'></a>";
                                        echo msgErro("Não foi possível alterar os dados do boleto!");
                                  }
                          }
                  
                  }
          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
    <script>
      var valor = document.getElementById('valorForm').value;
      var vct = document.getElementById('vctForm').value;

      // Funções para verificação de campos vazios de formulários (submit)
      function validarCamposBoleto() {
        var valor2 = document.getElementById('valorForm').value;
        var vct2 = document.getElementById('vctForm').value;

        if (valor == valor2 && vct == vct2) {
          window.alert(`Não há alteração de dados!`);
        } else {
                if(valor2.length == 0 || vct2.length == 0) {
                  window.alert(`Todos os campos devem ser preenchidos!`);
                } else {
                        document.getElementById('submit').click();
                  }
          }
      }
    </script>
  </body>
</html>