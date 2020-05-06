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
                $cnpj = $_GET['cnpj'] ?? null;

                $cnpjForm = $_GET['cnpjForm'] ?? null;
                $razao = $_GET['razao'] ?? null;
                $fant = $_GET['fant'] ?? null;
                $rua = $_GET['rua'] ?? null;
                $cep = $_GET['cep'] ?? null;
                $bairro = $_GET['bairro'] ?? null;
                $tel = $_GET['tel'] ?? null;
                $cel = $_GET['cel'] ?? null;

                if (isset($cnpj)) {

                  $consulta = $conexao->query("SELECT cnpj, razao_social, nome_fantasia, rua, cep, bairro, tel, cel FROM fornecedores WHERE cnpj = '$cnpj'");

                  if (!$consulta) {
                    echo msgErro("Não foi possível buscar o registro deste fornecedor na base de dados!");
                  } else {
                          $reg = $consulta->fetch_object();

                          echo "<form class='editar' action='./fornecedores-edit.php' method='get'><fieldset><legend>Alterar Fornecedor</legend>";
                            echo "<p>CNPJ: <input type='text' id='cnpjForm' name='cnpjForm' size='18' maxlenght='18' value='".$cnpj."' readonly style='background-color: #ebebe4;'/></p>";
                            echo "<p>Razão Social: <input type='text' id='razao' name='razao' size='20' maxlength='40' value='$reg->razao_social'/></p>";
                            echo "<p>Nome Fantasia.: <input type='text' id='fant' name='fant' size='20' maxlength='20' value='$reg->nome_fantasia'/></p>";
                            echo "<p>Rua: <input type='text' id='rua' name='rua' size='30' maxlength='40' value='$reg->rua'/></p>";
                            echo "<p>CEP: <input type='text' id='cep' name='cep' size='10' maxlength='10' value='$reg->cep' onkeypress='return validarCEP(event)'></p>";
                            echo "<p>Bairro: <input type='text' id='bairro' name='bairro' size='15' maxlength='20' value='$reg->bairro'/></p>";
                            echo "<p>Tel.: <input type='text' id='tel' name='tel' size='14' maxlength='14' value='$reg->tel' onkeypress='return validarTel(event)'></p>";
                            echo "<p>Cel.: <input type='text' id='cel' name='cel' size='15' maxlength='15' value='$reg->cel' onkeypress='return validarCel(event)'></p>";

                            echo "<p><input type='button' value='Salvar' onclick='validarCampos()'></p>";
                            echo "<p><input type='submit' id='submit' value='Salvar' style='display: none;'></p>";
                          echo "</fieldset></form>";
                    }
                } else {
                        if ($conexao->query("UPDATE fornecedores SET cnpj = '$cnpjForm', razao_social = '$razao', nome_fantasia = '$fant', rua = '$rua', cep = '$cep', bairro = '$bairro', tel = '$tel', cel = '$cel' WHERE cnpj = '$cnpjForm'")) {
                        echo msgSucesso("Fornecedor alterado com sucesso!");
                        } else {
                                echo msgErro("Não foi possível alterar os dados do fornecedor!");
                          }
                  }
        }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>