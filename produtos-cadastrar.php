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

        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p><p>Você não é administrador.");
        } else {
                if (isset($codBarra)) {
                  echo "<form class='cadastro' action='./produtos-cadastrar.php' method='get'><fieldset><legend>Cadastrar Novo Produto</legend>";
                    echo "<p>Cod. Barra: <input type='text' id='codBarraForm' name='codBarraForm' size='13' maxlenght='13' value='$codBarra' readonly style='background-color: #ebebe4;'></p>";
                    echo "<p>Produto: <input type='text' id='produtoForm' name='produtoForm' size='20' maxlength='40'/></p>";
                    echo "<p>Preço: R$ <input type='text' id='precoForm' name='precoForm' size='10' maxlength='10'></p>";

                    echo "<p><input type='button' value='Salvar' onclick='validarCamposProduto()'></p>";
                    echo "<p><input type='submit' id='submit' value='Salvar' style='display: none;'></p>";
                  echo "</fieldset></form>";
                } else {
                        $codBarraForm = $_GET['codBarraForm'] ?? null;
                        if (!isset($codBarraForm)) {
                          echo msgAviso("Nenhum produto foi previamente verificado para inclusão!");
                        } else {
                                # GRAVANDO novo produto no banco (parâmetros vindos do formulário
                                $produto = $_GET['produtoForm'] ?? null;
                                $preco= $_GET['precoForm'] ?? null;
                            
                                $id_user = $_SESSION['id_user'];
                                $preco = str_replace(',', '.', $preco);
                                $query = "INSERT INTO produtos (cod_barra, produto, preco, id_user_cadast, dt_hr_cadast) VALUES ('$codBarraForm', '$produto', '$preco', '$id_user', now())";
                                if ($conexao->query($query)) {
                                  echo msgSucesso("Produto cadastrado com sucesso!");
                                } else {
                                        echo msgErro("Não foi possível cadastrar o produto!");
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
      function validarCamposProduto() {
      produto = document.getElementById('produtoForm').value;
      preco = document.getElementById('precoForm').value;

      if(produto.length == 0 || preco.length == 0) {
        window.alert(`Todos os campos devem ser preenchidos!`);
      } else {
              document.getElementById('submit').click();
        }
      }
    </script>
  </body>

</html>