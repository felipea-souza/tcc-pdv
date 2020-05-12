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
    <meta charset="utf-8"/>
    <title>Sistema PDV</title>

    <link rel="stylesheet" type="text/css" href="./_css/estilo.css"/>
    <link rel="stylesheet" type="text/css" href="./_css/produtos.css"/>
  </head>

  <body>
    <div id="interface">

      <?php 
        $nome_pag = "Página de Cadastro de Produtos";
        $icone_pag = "produtos.png";
        $iconeMouseOut = "produtos.png";
        $bread_crumb = "Home > Produtos > Adicionar Produto";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./produtos.php"><img class="voltar" src="./_imagens/voltar.png"/></a>

      <?php
        $codBarraVerificar = $_GET['codBarra'] ?? null;
        $codBarraForm = $_GET['codBarraForm'] ?? null;

        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p><p>Você não é administrador.");
        } else {
                if (empty($codBarraForm)) {
                
                  if (empty($codBarraVerificar)) { # Verificar se o produto já se encontra cadastrado  #1: 'codBarra'
                            echo "<form class='cadastro' action='produtos-cadastrar.php' method='get'><fieldset><legend>Cadastrar Novo Produto</legend>";
                              echo "<p>Cód. barra: <input type='text' name='codBarra' id='codBarra' size='13' maxlength='13' placeholder='Somente nº' onkeypress='return validarCodBarra(event)'> 
                              <a href='javascript:validarCampoCodBarra()'><img src='./_imagens/buscar.png' id='busca' title='Buscar'></a>
                              <input type='submit' id='iBusca' name='tBuscar' title='Buscar' src='./_imagens/buscar.png' style='display: none;'></p>";
                            echo "</fieldset></form>";
                  } else {
                          $query = "SELECT cod_barra, produto, quant, preco FROM estoque WHERE cod_barra = '$codBarraVerificar'";
                                   $consulta = $conexao->query($query);

                                   if (!$consulta) {
                                     echo "Não foi possível realizar a consulta!";
                                   } else {                                                               #2: 'codBarraForm'
                                           if ($consulta->num_rows == 0) { # Não está cadastrado (pode cadastrar produto)
                                             echo "<form class='cadastro' action='./produtos-cadastrar.php' method='get'><fieldset><legend>Cadastrar Novo Produto</legend>";
                                               echo "<p>Cod. Barra: <input type='text' id='codBarraForm' name='codBarraForm' size='13' maxlenght='13' value='".$codBarraVerificar."' readonly style='background-color: #ebebe4;'/></p>";
                                               echo "<p>Produto: <input type='text' id='produto' name='produto' size='20' maxlength='40'/></p>";
                                               echo "<p>Quant.: <input type='number' min='0' value='0' id='quant' name='quant' size='3' maxlength='3'/></p>";
                                               echo "<p>Preço: R$ <input type='text' id='preco' name='preco' size='10' maxlength='10'></p>";

                                               echo "<p><input type='button' value='Salvar' onclick='validarCampos()'></p>";
                                               echo "<p><input type='submit' id='submit' value='Salvar' style='display: none;'></p>";
                                             echo "</fieldset></form>";
                                           } else { # Já está cadastrado (não pode cadastrar produto)
                                                   echo msgAviso("O produto já se encontra cadastrado no sistema!");
                                                   $reg = $consulta->fetch_object();
                                                   $preco = str_replace('.', ',', $reg->preco);
                                                   echo "<table class='busca'>";
                                                     echo "<tr id='cabecalho'><td>Cód. barra</td><td>Produto</td><td>Quant.</td><td>Preço</td><td></td></tr>";
                                                     echo "<tr><td>$reg->cod_barra</td><td>$reg->produto</td><td>$reg->quant</td><td>R$ $preco</td><td></td></tr>";
                                                   echo "</table>";
                                             }      
                                     }
                    }
                } else { # GRAVANDO novo produto no banco (parâmetros vindos do formulário id='cadastro-produto' acima)
                        $codBarraForm;
                        $produto = $_GET['produto'] ?? null;
                        $quant = $_GET['quant'];
                        $preco= $_GET['preco'] ?? null;
                    
                        $id_user = $_SESSION['id_user'];
                        $preco = str_replace(',', '.', $preco);
                        $query = "INSERT INTO estoque (cod_barra, produto, quant, preco, id_user_cadast, dt_hr_cadast) VALUES ('$codBarraForm', '$produto', '$quant', '$preco', '$id_user', now())";
                        if ($conexao->query($query)) {
                          echo msgSucesso("Produto cadastrado com sucesso!");
                        } else {
                                echo msgErro("Não foi possível cadastrar o produto!");
                        }
                        
                        

                  }
          }
      ?>
    </div>
    <?php include_once "./rodape.php"; ?>
    
    <script language="javascript" src="_javascript/funcoes.js"></script>
    <script>
      function validarCampoCodBarra() {
        var codBarra = document.getElementById('codBarra').value;

        if (codBarra.length == 0 || codBarra.length < 13) {
          window.alert(`Impossível verificar! Um código de barras é composto por 13 dígitos.`);
        } else {
          document.getElementById('iBusca').click();
        }
      }

      function validarCampos() {
        var produto = document.getElementById('produto').value;
        var preco = document.getElementById('preco').value;

        if (produto.length == 0 || preco.length == 0) {
          window.alert(`Todos os campos devem ser preenchidos!`);
        } else {
          document.getElementById('submit').click();
        }
      }

      function validarCodBarra(e) {
        codBarra = document.getElementById('codBarra');

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