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
    <style>
      table.busca {
        font-size: 11px;
      }
    </style>
  </head>

  <body>
    <div id="interface">

      <?php 
        $nome_pag = "Página de Cadastro de Fornecedores";
        $icone_pag = "fornecedores.png";
        $iconeMouseOut = "fornecedores.png";
        $bread_crumb = "Home > Fornecedores > Adicionar Fornecedor";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="fornecedores.php"><img id="voltar-home" src="./_imagens/voltar.png"/></a>

      <?php
        $cnpjVerificar = $_GET['cnpjVerificar'] ?? null;
        $cnpjForm = $_GET['cnpjForm'] ?? null;

        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p><p>Você não é administrador.");
        } else {
            if (empty($cnpjForm)) {

              if (empty($cnpjVerificar)) { # Verificar se o fornecedor já se encontra cadastrado
                echo "<form class='cadastro' action='fornecedores-cadastrar.php' method='get'><fieldset><legend>Cadastrar Novo Fornecedor</legend>
                        <p>CNPJ: <input type='text' name='cnpjVerificar' id='cnpjVerificar' maxlength='18' size='18'  placeholder='Somente números' onkeypress='return validarCNPJ(event)'> 
                        <a href='javascript:validarCampoCNPJ()'><img src='./_imagens/buscar.png' id='busca' title='Verificar'></a>
                        <input type='submit' class='buscar' id='submit' name='tBuscar' title='Buscar' src='./_imagens/buscar.png' style='display: none;'></p>
                      </form></form>";
              } else {
                      $query = "SELECT cnpj, razao_social, nome_fantasia, rua, cep, bairro, tel, cel FROM fornecedores WHERE cnpj = '$cnpjVerificar'";
                                   $consulta = $conexao->query($query);

                                   if (!$consulta) {
                                     echo "Não foi possível realizar a consulta!";
                                   } else {
                                           if ($consulta->num_rows == 0) {  # Fornecedor não está cadastrado (pode cadastrar fornecedor)
                                             echo "<form class='cadastro' action='./fornecedores-cadastrar.php' method='get'><fieldset><legend>Cadastrar Novo Fornecedor</legend>
                                                     <p>CNPJ: <input type='text' id='cnpjForm' name='cnpjForm' maxlenght='14' size='14' value='".$cnpjVerificar."' readonly style='background-color: #ebebe4;'/></p>
                                                     <p>Razão Social: <input type='text' id='razao' name='razao' maxlength='40' size='20'></p>
                                                     <p>Nome Fantasia: <input type='text' id='fant' name='fant' maxlength='20' size='20'></p>
                                                     <p>Rua: <input type='text' id='rua' name='rua' maxlength='40' size='20'></p>
                                                     <p>CEP: <input type='text' id='cep' name='cep' maxlength='10' size='10' placeholder='Somente nº' onkeypress='return validarCEP(event)'></p>
                                                     <p>Bairro: <input type='text' id='bairro' name='bairro' maxlength='20' size='15'></p>
                                                     <p>Telefone: <input type='text' id='tel' name='tel' maxlength='14' size='14' placeholder='Somente nº' onkeypress='return validarTel(event)'></p>
                                                     <p>Celular: <input type='text' id='cel' name='cel' maxlength='15' size='15' placeholder='Somente nº' onkeypress='return validarCel(event)'></p>

                                                     <p><input type='button' id='salvar' value='Salvar' onclick='validarCampos()'></p>
                                                     <p><input type='submit' id='submit' value='Salvar' style='display: none;'></p>
                                                   </fieldset></form>";
                                           } else { # Fornecedor já cadastrado (não pode cadastrar)
                                                   echo msgAviso("O fornecedor já se encontra cadastrado no sistema!");
                                                   $reg = $consulta->fetch_object();
                                                   echo "<table class='busca'>";
                                                     echo "<tr id='cabecalho'><td>CNPJ</td><td>Razão Social</td><td>Nome Fantasia</td><td>Rua</td><td>CEP</td><td>Bairro</td><td>Telefone 1</td><td>Telefone 2</td></tr>";
                                                     echo "<tr><td>$reg->cnpj</td><td>$reg->razao_social</td><td>$reg->nome_fantasia</td><td>$reg->rua</td><td>$reg->cep</td><td>$reg->bairro</td><td>$reg->tel</td><td>$reg->cel</td></tr>";
                                                   echo "</table>";
                                             }   
                                     }
                }
            } else {
                    $cnpjForm;
                    $razao = $_GET['razao'] ?? null;
                    $fant = $_GET['fant'] ?? null;
                    $rua = $_GET['rua'] ?? null;
                    $cep = $_GET['cep'] ?? null;
                    $bairro = $_GET['bairro'] ?? null;
                    $tel = $_GET['tel'] ?? null;
                    $cel = $_GET['cel'] ?? null;

                    $query = "INSERT INTO fornecedores (cnpj, razao_social, nome_fantasia, rua, cep, bairro, tel, cel) VALUES ('$cnpjForm', '$razao', '$fant', '$rua', '$cep', '$bairro', '$tel', '$cel')";
                    if ($conexao->query($query)) {
                      echo msgSucesso("Produto cadastrado com sucesso!");
                    } else {
                            echo msgErro("Não foi possível cadastrar o fornecedor!");
                            //echo "$cnpjForm - $razao - $fant - $rua - $cep - $bairro - $tel - $cel";
                    }
                            
                    
            }
          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
    <script>      
      function validarCNPJ(e) {

        cnpj = document.getElementById('cnpjVerificar');

        var charCode = e.charCode ? e.charCode : e.keyCode;
        // charCode 8 = backspace   
        // charCode 9 = tab
        if (charCode != 8 && charCode != 9) {
            // charCode 48 equivale a 0   
            // charCode 57 equivale a 9
            if (charCode < 48 || charCode > 57) {
                return false;
            } else {
                    if (cnpj.value.length == 2 || cnpj.value.length == 6) {
                      cnpj.value += `.`;
                    }

                    if (cnpj.value.length == 10) {
                      cnpj.value += `/`;
                    }

                    if (cnpj.value.length == 15) {
                      cnpj.value += `-`;
                    }
              }
        }
      }

      function validarCampoCNPJ() {
        cnpjVerificar = document.getElementById('cnpjVerificar').value;

        if (cnpjVerificar.length < 18) {
          window.alert(`O campo "CNPJ" possui 14 dígitos numéricos!`);
        } else {
          document.getElementById(`submit`).click();
        }
      }
    </script>
  </body>
</html>