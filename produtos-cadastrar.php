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

    <link rel="stylesheet" type="text/css" href="./_css/estilo.css">
    <link rel="stylesheet" type="text/css" href="./_css/produtos.css">
    <link rel="shortcut icon" href="./_imagens/fav-icon.png" type="image/x-icon">
  </head>

  <body>
    <div id="interface">

      <?php 
        $nome_pag = "Cadastrar Produto";
        $icone_pag = "produtos.png";
        $iconeMouseOut = "produtos.png";
        $bread_crumb = "Home > Produtos > Cadastrar Produto";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./produtos.php"><img class="voltar" src="./_imagens/voltar.png"/></a>

      <?php
        $codBarra = $_GET['cb'] ?? null;
        $codBarraForm = $_GET['codBarraForm'] ?? null;

        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p><p>Você não é administrador.");
        } else {
                if (isset($codBarra)) {
                  echo "<form class='cadastro' action='./produtos-cadastrar.php' method='get'><fieldset><legend>Cadastrar Novo Produto</legend>";
                    echo "<p>Cod. Barra: <input type='text' id='codBarraForm' name='codBarraForm' size='13' maxlenght='13' value='$codBarra' readonly style='background-color: #ebebe4;'></p>";
                    echo "<p>Produto: <input type='text' id='produto' name='produto' size='20' maxlength='40'/></p>";
                    echo "<p>Quant.: <input type='number' min='0' value='0' id='quant' name='quant' size='3' maxlength='3'/></p>";
                    echo "<p>Preço: R$ <input type='text' id='preco' name='preco' size='10' maxlength='10'></p>";

                    echo "<p><input type='button' value='Salvar' onclick='validarCampos()'></p>";
                    echo "<p><input type='submit' id='submit' value='Salvar' style='display: none;'></p>";
                  echo "</fieldset></form>";
                } else {
                        # GRAVANDO novo produto no banco (parâmetros vindos do formulário
                        $codBarraForm;
                        $produto = $_GET['produto'] ?? null;
                        $quant = $_GET['quant'] ?? null;
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
      function validarCampos() {
        var produto = document.getElementById('produto').value;
        var preco = document.getElementById('preco').value;

        if (produto.length == 0 || preco.length == 0) {
          window.alert(`Todos os campos devem ser preenchidos!`);
        } else {
          document.getElementById('submit').click();
        }
      }
    </script>
  </body>

</html>