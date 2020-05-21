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
        $nome_pag = "Editar Fornecedor";
        $icone_pag = "fornecedores.png";
        $iconeMouseOut = "fornecedores.png";
        $bread_crumb = "Home > Fornecedores > Editar Fornecedor";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./fornecedores.php"><img id="voltar-home" src="./_imagens/voltar.png"/></a>

      <?php 
        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p> <p>Você não é administrador.");
        } else {
                if (isset($_GET['cnpj'])) {
                  $cnpj = $_GET['cnpj'] ?? null;
                  $razao = $_GET['razao'] ?? null;
                  $fant = $_GET['fant'] ?? null;
                  $rua = $_GET['rua'] ?? null;
                  $cep = $_GET['cep'] ?? null;
                  $ba = $_GET['ba'] ?? null;
                  $tel = $_GET['tel'] ?? null;
                  $cel = $_GET['cel'] ?? null;

                  echo "<fieldset class='editar'><legend>Alterar Fornecedor</legend><form action='./fornecedores-edit.php' method='get'>";
                    echo "<p>CNPJ: <input type='text' id='cnpjForm' name='cnpjForm' size='18' maxlenght='18' value='$cnpj' readonly style='background-color: #ebebe4;'/></p>";
                    echo "<p>Razão Social: <input type='text' id='razaoForm' name='razaoForm' size='20' maxlength='40' value='$razao'/></p>";
                    echo "<p>Nome Fantasia.: <input type='text' id='fantForm' name='fantForm' size='20' maxlength='20' value='$fant'/></p>";
                    echo "<p>Rua: <input type='text' id='ruaForm' name='ruaForm' size='30' maxlength='40' value='$rua'/></p>";
                    echo "<p>CEP: <input type='text' id='cepForm' name='cepForm' size='10' maxlength='10' value='$cep' onkeypress='return validarCEP(event)'></p>";
                    echo "<p>Bairro: <input type='text' id='bairroForm' name='bairroForm' size='15' maxlength='20' value='$ba'/></p>";
                    echo "<p>Tel.: <input type='text' id='telForm' name='telForm' size='14' maxlength='14' value='$tel' onkeypress='return validarTel(event)'></p>";
                    echo "<p>Cel.: <input type='text' id='celForm' name='celForm' size='15' maxlength='15' value='$cel' onkeypress='return validarCel(event)'></p>";

                    echo "<ul class='botoes'>";
                    echo "<li><input type='button' value='Salvar Alteração' onclick='validarCamposFornecedor()'></li>";
                    echo "<li><input type='submit' id='submit' value='Salvar' style='display: none;'></li>";
                  echo "</form><li><a href='javascript:confirmacaoForn(`$cnpj`)'><input type='button' value='EXCLUIR'></a></li></ul></fieldset>";
                } else {
                        $cnpjForm = $_GET['cnpjForm'] ?? null;
                        if(!isset($cnpjForm)) {
                          echo msgAviso("Nehum fornecedor foi previamente verificado para alteração!");
                        } else {
                                $razaoForm = $_GET['razaoForm'];
                                $fantForm = $_GET['fantForm'];
                                $ruaForm = $_GET['ruaForm'];
                                $cepForm = $_GET['cepForm'];
                                $bairroForm = $_GET['bairroForm'];
                                $telForm = $_GET['telForm'];
                                $celForm = $_GET['celForm'];
                                if ($conexao->query("UPDATE fornecedores SET cnpj = '$cnpjForm', razao_social = '$razaoForm', nome_fantasia = '$fantForm', rua = '$ruaForm', cep = '$cepForm', bairro = '$bairroForm', tel = '$telForm', cel = '$celForm' WHERE cnpj = '$cnpjForm'")) {
                                  echo msgSucesso("Fornecedor alterado com sucesso!");
                                  } else {
                                          echo msgErro("Não foi possível alterar os dados do fornecedor!");
                                    }
                          }
                  }
          } 
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
    <script>
      var razao = document.getElementById('razaoForm').value;
      var fant = document.getElementById('fantForm').value;
      var rua = document.getElementById('ruaForm').value;
      var cep = document.getElementById('cepForm').value;
      var bairro = document.getElementById('bairroForm').value;
      var tel = document.getElementById('telForm').value;
      var cel = document.getElementById('celForm').value;

      // Funções para verificação de campos vazios de formulários (submit)
      function validarCamposFornecedor() {
        //var cnpj = document.getElementById('cnpjForm').value;
        var razao2 = document.getElementById('razaoForm').value;
        var fant2 = document.getElementById('fantForm').value;
        var rua2 = document.getElementById('ruaForm').value;
        var cep2 = document.getElementById('cepForm').value;
        var bairro2 = document.getElementById('bairroForm').value;
        var tel2 = document.getElementById('telForm').value;
        var cel2 = document.getElementById('celForm').value;

        if (razao == razao2 && fant == fant2 && rua == rua2 && cep == cep2 && bairro == bairro2 && tel == tel2 && cel == cel2) {
          window.alert("Não há alteração de dados!");
        } else {
                if (razao2.length == 0 || fant2.length == 0 || rua2.length == 0 || cep2.length == 0 || bairro2.length == 0) {
                  window.alert(`Existem campos a serem preenchidos!`);
                } else {
                        if (cep2.length < 10) {
                          window.alert(`O campo "CEP" possui 8 (oito) dígitos numéricos`);
                        } else {
                                document.getElementById('submit').click();
                          }
                  }
          }

        
      }
    </script>
  </body>
</html>