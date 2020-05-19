<!DOCTYPE html>

<?php
  session_start();
  require_once './_includes/funcoes.php';
  #require_once './_includes/verifica_login.php';
  isLogged();
  require_once './_includes/connection.php';
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
        $nome_pag = "Alterar Produto";
        $icone_pag = "produtos.png";
        $iconeMouseOut = "produtos.png";
        $bread_crumb = "Home > Produtos > Alterar Produto";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./produtos.php"><img id="voltar-produto" src="./_imagens/voltar.png"/></a>
      
      <?php 

        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p> <p>Você não é administrador.");
        } else {
                if (isset($_GET['cb'])) {
                  $codBarra = $_GET['cb'] ?? null;
                  $produto = $_GET['pdt'] ?? null;
                  $quant = $_GET['qtd'] ?? null;
                  $preco = $_GET['prc'] ?? null;

                  echo "<form class='editar' action='./produtos-edit.php' method='get'><fieldset><legend>Alterar Produto</legend>";
                    echo "<p>Cod. Barra: <input type='text' id='codBarraForm' name='codBarraForm' size='13' maxlenght='13' value='$codBarra' readonly style='background-color: #ebebe4;'/></p>";
                    echo "<p>Produto: <input type='text' id='produtoForm' name='produtoForm' size='20' maxlength='40' value='$produto'/></p>";
                    echo "<p>Quant.: <input type='number' min='0' id='quantForm' name='quantForm' size='3' maxlength='3' value='$quant'/></p>";
                    $preco = str_replace('.', ',', $preco);
                    echo "<p>Preço: R$ <input type='text' id='precoForm' name='precoForm' size='10' maxlength='10' value='$preco'/></p>";
                    echo "<ul id='botoes'>";
                      echo "<li><input type='button' value='Salvar Alteração' onclick='validarCampos()'></li>";
                      echo "<li><input type='submit' id='submit' value='Salvar' style='display: none;'></li>";
                      echo "<li><a href='./produtos-delete.php?$codBarra'><input type='button' href='www.google.com' value='EXCLUIR'></a></li>";
                    echo "</ul>";
                  echo "</fieldset></form>";
                } else {
                        $codBarraForm = $_GET['codBarraForm'];
                        $produto = $_GET['produtoForm'];
                        $quant = $_GET['quantForm'];
                        $preco = str_replace(",", ".", $_GET['precoForm']);
                        if ($conexao->query("UPDATE estoque SET produto = '$produto', quant = '$quant', preco = '$preco' WHERE cod_barra = '$codBarraForm'")) {
                          echo msgSucesso("Produto alterado com sucesso!");
                        } else {
                                echo msgErro("Não foi possível alterar os dados do produto!");
                          }
                  }
          }
      ?>
      
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="_javascript/funcoes.js"></script>
    <script>
      function validarCampos() {
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