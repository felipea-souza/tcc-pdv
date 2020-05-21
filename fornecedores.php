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
        $nome_pag = "Fornecedores";
        $icone_pag = "fornecedores.png";
        $iconeMouseOut = "fornecedores.png";
        $bread_crumb = "Home > Fornecedores";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"/></a>

      <?php
        $cnpjForm = $_GET['cnpjForm'] ?? null;

        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p><p>Você não é administrador.");
        } else {
                if (empty($cnpjForm)){
                  # Verificar se o fornecedor se encontra ou não cadastrado no sistema
                  echo "<form class='cadastro' action='fornecedores.php' method='get'><fieldset><legend>Cadastrar Novo Fornecedor</legend>
                        <p>CNPJ: <input type='text' name='cnpjForm' id='cnpjForm' maxlength='18' size='18'  placeholder='Somente números' onkeypress='return validarCNPJ(event)'> 
                        <a href='javascript:validarCampoCNPJ()'><img src='./_imagens/buscar.png' id='busca' title='Verificar'></a>
                        <input type='submit' class='buscar' id='submit' name='tBuscar' title='Buscar' src='./_imagens/buscar.png' style='display: none;'></p>
                      </form></form>";
                } else {
                        $query = "SELECT cnpj, razao_social, nome_fantasia, rua, cep, bairro, tel, cel FROM fornecedores WHERE cnpj = '$cnpjForm'";
                                   $consulta = $conexao->query($query);

                                   if (!$consulta) {
                                     echo "Não foi possível realizar a consulta!";
                                   } else { 
                                           if ($consulta->num_rows == 0) {
                                            header("Location: ./fornecedores-cadastrar.php?cnpj=$cnpjForm");
                                           } else {
                                                   $reg = $consulta->fetch_object();
                                                   header("Location: ./fornecedores-edit.php?cnpj=$reg->cnpj&razao=$reg->razao_social&fant=$reg->nome_fantasia&rua=$reg->rua&cep=$reg->cep&ba=$reg->bairro&tel=$reg->tel&cel=$reg->cel"); 
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
      function validarCNPJ(e) {

        cnpj = document.getElementById('cnpjForm');

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

      // Funções para verificação de campos vazios de formulários (submit)
      function validarCampoCNPJ() {
        cnpjVerificar = document.getElementById('cnpjForm').value;

        if (cnpjVerificar.length < 18) {
          window.alert(`O campo "CNPJ" possui 14 dígitos numéricos!`);
        } else {
          document.getElementById(`submit`).click();
        }
      }
    </script>
  </body>
</html>