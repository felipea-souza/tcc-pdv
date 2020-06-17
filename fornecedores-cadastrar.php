<!DOCTYPE html>

<?php
  session_start();
  #require_once './_includes/verifica_login.php';
  require_once './_includes/funcoes.php';
  isLogged();
  require_once "./_includes/connection.php";
?>

<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <title>Sistema PDV</title>

    <link rel="stylesheet" type="text/css" href="./_css/estilo.css">
    <link rel="stylesheet" type="text/css" href="./_css/produtos.css">
    <link rel="shortcut icon" href="./_imagens/fav-icon.png" type="image/x-icon">
  </head>

  <body>
    <?php 
      $nome_pag = "Cadastrar Fornecedor";
      $icone_pag = "fornecedores.png";
      $iconeMouseOut = "fornecedores.png";
      $bread_crumb = "Página Inicial > Fornecedor > Cadastrar Fornecedor";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./fornecedores.php"><img class="voltar" src="./_imagens/voltar.png"/></a>

      <?php
        $cnpj = $_GET['cnpj'] ?? null;

        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p><p>Você não é administrador.");
        } else {
                if (isset($cnpj)) {
                  echo "<form class='cadastro' action='./fornecedores-cadastrar.php' method='get'><fieldset><legend>Cadastrar Novo Fornecedor</legend>
                          <p>CNPJ: <input type='text' id='cnpjForm' name='cnpjForm' size='18' maxlenght='18' value='$cnpj' readonly style='background-color: #ebebe4;'></p>
                          <p>Razão Social: <input type='text' id='razaoForm' name='razaoForm' size='30' maxlength='40'></p>
                          <p>Nome Fantasia: <input type='text' id='fantForm' name='fantForm' size='20' maxlength='20'></p>
                          <p>Rua: <input type='text' id='ruaForm' name='ruaForm' size='30' maxlength='40'></p>
                          <p>CEP: <input type='text' id='cepForm' name='cepForm' maxlength='10' size='10' onkeypress='return validarCEP(event)'></p>
                          <p>Bairro: <input type='text' name='bairroForm' id='bairroForm' maxlength='20' size='20'></p>
                          <p>Tel.: <input type='text' name='telForm' id='telForm' maxlength='14' size='14' placeholder='Somente nº' onkeypress='return validarTel(event)'></p>
                          <p>Cel.: <input type='text' name='celForm' id='celForm' maxlength='15' size='15' placeholder='Somente nº' onkeypress='return validarCel(event)'></p>

                          <p><input type='button' value='Salvar' onclick='validarCamposFornecedor()'></p>
                          <p><input type='submit' id='submit' value='Salvar' style='display: none;'></p>
                        </fieldset></form>";
                } else {
                        $cnpjForm = $_GET['cnpjForm'] ?? null;
                        if (!isset($cnpjForm)) {
                          echo msgAviso("Nenhum fornecedor foi previamente verificado para inclusão!");
                        } else {
                                # GRAVANDO novo fornecedor no banco (parâmetros vindos do formulário
                                $cnpj = $_GET['cnpjForm'] ?? null;
                                $razao = $_GET['razaoForm'] ?? null;
                                $fant = $_GET['fantForm'] ?? null;
                                $rua = $_GET['ruaForm'] ?? null;
                                $cep = $_GET['cepForm'] ?? null;
                                $bairro = $_GET['bairroForm'] ?? null;
                                $tel = $_GET['telForm'] ?? null;
                                $cel = $_GET['celForm'] ?? null;

                                $query = "INSERT INTO fornecedores (cnpj, razao_social, nome_fantasia, rua, cep, bairro, tel, cel) VALUES ('$cnpj', '$razao', '$fant', '$rua', '$cep', '$bairro', '$tel', '$cel')";
                                if ($conexao->query($query)) {
                                  echo msgSucesso("Novo fornecedor cadastrado com sucesso!");
                                } else {
                                        echo msgErro("Não foi possível cadastrar o fornecedor!");
                                  }
                          }
                                        }
          }
      ?>
    </div>
    <?php include_once "./rodape.php"; ?>
    
    <script type="text/javascript" src="_javascript/funcoes.js"></script>
    <script>
      // Funções para verificação de campos vazios de formulários (submit)
      function validarCamposFornecedor() {
        //var cnpj = document.getElementById('cnpjForm').value;
        var razao = document.getElementById('razaoForm').value;
        var fant = document.getElementById('fantForm').value;
        var rua = document.getElementById('ruaForm').value;
        var cep = document.getElementById('cepForm').value;
        var bairro = document.getElementById('bairroForm').value;

        if (razao.length == 0 || fant.length == 0 || rua.length == 0 || cep.length == 0 || bairro.length == 0) {
          window.alert(`Existem campos a serem preenchidos!`);
        } else {
                if (cep.length < 10) {
                  window.alert(`O campo "CEP" possui 8 (oito) dígitos numéricos`);
                } else {
                        document.getElementById('submit').click();
                  }
          }
      }
    </script>
  </body>

</html>