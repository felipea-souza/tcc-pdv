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
    <link rel="stylesheet" type="text/css" href="./_css/produtos.css">
    <link rel="shortcut icon" href="./_imagens/fav-icon.png" type="image/x-icon">
  </head>

  <body>
    <?php 
      $nome_pag = "Alterar Produto";
      $icone_pag = "produtos.png";
      $iconeMouseOut = "produtos.png";
      $bread_crumb = "Página Inicial > Produtos > Alterar Produto";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php
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

                  echo "<fieldset class='editar'><legend>Alterar Produto</legend><form action='./produtos-edit.php' method='get'>";
                    echo "<p>Cod. Barra: <input type='text' id='codBarraForm' name='codBarraForm' size='13' maxlenght='13' value='$codBarra' readonly style='background-color: #ebebe4;'/></p>";
                    echo "<p>Produto: <input type='text' id='produtoForm' name='produtoForm' size='20' maxlength='40' value='$produto'/></p>";
                    $preco = str_replace('.', ',', $preco);
                    echo "<p>Preço: R$ <input type='text' id='precoForm' name='precoForm' size='10' maxlength='10' value='$preco'/></p>";

                    echo "<ul class='botoes'>";
                      echo "<li><input type='button' value='Salvar Alteração' onclick='validarCamposProduto()'></li>";
                      echo "<li><input type='submit' id='submit' value='Salvar' style='display: none;'></li>";
                  echo "</form><li><a href='javascript:confirmacaoProduto(`$codBarra`)'><input type='button' value='EXCLUIR'></a></li></ul></fieldset>";
                } else {
                        $codBarraForm = $_GET['codBarraForm'] ?? null;
                        if (!isset($codBarraForm)) {
                          echo msgAviso("Nenhum produto foi previamente verificado para alteração!");
                        } else {
                                $produtoForm = $_GET['produtoForm'];
                                $precoForm = str_replace(",", ".", $_GET['precoForm']);
                                if ($conexao->query("UPDATE produtos SET produto = '$produtoForm', preco = '$precoForm' WHERE cod_barra = '$codBarraForm'")) {
                                  echo msgSucesso("Produto alterado com sucesso!");
                                } else {
                                        echo msgErro("Não foi possível alterar os dados do produto!");
                                  }
                          }
                        
                  }
          }
      ?>
      
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
    <script>
      var produto = document.getElementById('produtoForm').value;
      var preco = document.getElementById('precoForm').value;

      // Funções para verificação de campos vazios de formulários (submit)
      function validarCamposProduto() {
        produto2 = document.getElementById('produtoForm').value;
        preco2 = document.getElementById('precoForm').value;

        if (produto == produto2 && preco == preco2) {
          window.alert(`Não há alteração de dados!`);
        } else {
                if(produto2.length == 0 || preco2.length == 0) {
                  window.alert(`Todos os campos devem ser preenchidos!`);
                } else {
                        document.getElementById('submit').click();
                  }
          }
      }
   </script>
    
  </body>

</html>