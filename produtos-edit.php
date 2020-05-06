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

    <link rel="stylesheet" type="text/css" href="./_css/estilo.css"/>
    <link rel="stylesheet" type="text/css" href="./_css/produtos.css"/>
  </head>

  <body>
    <div id="interface">

      <?php 
        $nome_pag = "Alterar Produto";
        $icone_pag = "produtos.png";
        $iconeMouseOut = "produtos.png";
        $bread_crumb = "Home > Produtos > Listar Produtos > Alterar Produto";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./produtos.php"><img id="voltar-produto" src="./_imagens/voltar.png"/></a>
      
      <?php 

        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p> <p>Você não é administrador.");
        } else {
                $codBarra = $_GET['cb'] ?? null;

                $codBarraForm = $_GET['codBarraForm'] ?? null;
                $produto = $_GET['produto'] ?? null;
                $quant = $_GET['quant'] ?? null;
                $preco = $_GET['preco'] ?? null;

                if (isset($codBarra)) {

                  $consulta = $conexao->query("SELECT cod_barra, produto, quant, preco FROM estoque WHERE cod_barra = '$codBarra'");

                  if (!$consulta) {
                    echo msgErro("Não foi possível buscar o registro deste produto na base de dados!");
                  } else {
                          $reg = $consulta->fetch_object();

                          echo "<form class='editar' action='./produtos-edit.php' method='get'><fieldset><legend>Alterar Produto</legend>";
                            echo "<p>Cod. Barra: <input type='text' id='codBarraForm' name='codBarraForm' size='13' maxlenght='13' value='".$codBarra."' readonly style='background-color: #ebebe4;'/></p>";
                            echo "<p>Produto: <input type='text' id='produto' name='produto' size='20' maxlength='40' value='$reg->produto'/></p>";
                            echo "<p>Quant.: <input type='number' min='0' id='quant' name='quant' size='3' maxlength='3' value='$reg->quant'/></p>";
                            $preco = str_replace('.', ',', $reg->preco);
                            echo "<p>Preço: R$ <input type='text' id='preco' name='preco' size='10' maxlength='10' value='$preco'/></p>";

                            echo "<p><input type='button' value='Salvar' onclick='validarCampos()'></p>";
                            echo "<p><input type='submit' id='submit' value='Salvar' style='display: none;'></p>";
                          echo "</fieldset></form>";
                    }
                } else {
                        $preco = str_replace(",", ".", $preco);
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
        produto = document.getElementById('produto').value;
        preco = document.getElementById('preco').value;

        if(produto.length == 0 || preco.length == 0) {
          window.alert(`Todos os campos devem ser preenchidos!`);
        } else {
          document.getElementById('submit').click();
        }
      }
    </script>
  </body>

</html>