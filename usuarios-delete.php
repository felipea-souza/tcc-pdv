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
        $nome_pag = "Excluir Usuário";
        $icone_pag = "users.png";
        $iconeMouseOut = "users.png";
        $bread_crumb = "Home > Usuários > Excluir Usuário";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./usuarios.php"><img id="voltar-home" src="./_imagens/voltar.png"/></a>
      
      <?php 
        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p> <p>Você não é administrador.");
        } else {
                $id = $_GET['id'] ?? null;
                $id2 = $_GET[''] ?? $id;

                $idDelete = $_GET['idd'] ?? null;

                if (isset($id)) {
                  $consulta = $conexao->query("SELECT id_user, nome, cpf, login, tipo FROM usuarios WHERE id_user = '$id'");

                  if (!$consulta) {
                    echo msgErro("Não foi possível realizar a consulta ao usuário!");
                  } else {
                          echo msgAviso("Atenção!<br/>Esta operação não poderá ser desfeita!");
                          $reg = $consulta->fetch_object();

                          echo "<fieldset class='editar'><legend>Excluir Usuário</legend><table class='excluir'>";
                            echo "<tr><td>Nome:</td><td>$reg->nome</td></tr>";
                            echo "<tr><td>CPF:</td><td>$reg->cpf</td></tr>";
                            echo "<tr><td>Login:</td><td>$reg->login</td></tr>";
                            echo "<tr><td>Tipo:</td><td>$reg->tipo</td></tr>";
                          echo "</table>";

                          echo "<a href='./usuarios-delete.php?idd=$id2'><button style='margin-left: 15px;'>EXCLUIR</button></a></fieldset>";
                  }
                } else {
                        if ($conexao->query("DELETE FROM usuarios WHERE id_user = '$idDelete'")) {
                          echo msgSucesso("Usuário excluído com sucesso!");
                        } else {
                                echo msgErro("Não foi possível excluir o usuário!");
                          }
                  }

          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>