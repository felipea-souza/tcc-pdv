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
        $nome_pag = "Excluir Fornecedor";
        $icone_pag = "fornecedores.png";
        $iconeMouseOut = "fornecedores.png";
        $bread_crumb = "Home > Fornecedores > Excluir Fornecedor";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./fornecedores.php"><img id="voltar-home" src="./_imagens/voltar.png"/></a>

      <?php 
        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p> <p>Você não é administrador.");
        } else {
                $cnpj = $_GET['cnpj'] ?? null;
                $cnpj2 = $cnpj;

                $cnpjDelete = $_GET['cnpjdel'] ?? null;

                if (isset($cnpj)) {

                  $consulta = $conexao->query("SELECT cnpj, razao_social, nome_fantasia, rua, cep, bairro, tel, cel FROM fornecedores WHERE cnpj = '$cnpj'");

                  if (!$consulta) {
                    echo msgErro("Não foi possível realizar a consulta ao fornecedor!");
                  } else {
                          echo msgAviso("Atenção!<br/>Esta operação não poderá ser desfeita!");
                          $reg = $consulta->fetch_object();

                          echo "<fieldset class='editar'><legend>Excluir Fornecedor</legend><table class='excluir'>";
                            echo "<tr><td>CNPJ:</td><td>$reg->cnpj</td></tr>";
                            echo "<tr><td>Razão Social:</td><td>$reg->razao_social</td></tr>";
                            echo "<tr><td>Nome Fantasia:</td><td>$reg->nome_fantasia</td></tr>";
                            echo "<tr><td>Rua:</td><td>$reg->rua</td></tr>";
                            echo "<tr><td>CEP:</td><td>$reg->cep</td></tr>";
                            echo "<tr><td>Bairro:</td><td>$reg->bairro</td></tr>";
                            echo "<tr><td>Tel.:</td><td>$reg->tel</td></tr>";
                            echo "<tr><td>Cel.:</td><td>$reg->cel</td></tr>";
                          echo "</table>";

                          echo "<a href='./fornecedores-delete.php?cnpjdel=$cnpj2'><button style='margin-left: 15px;'>EXCLUIR</button></a></fieldset>";
                    }
                } else {
                        if ($conexao->query("DELETE FROM fornecedores WHERE cnpj = '$cnpjDelete'")) {
                          echo msgSucesso("Fornecedor excluído com sucesso!");
                        } else {
                                echo msgErro("Não foi possível excluir o fornecedor!");
                          }
                  }
          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>