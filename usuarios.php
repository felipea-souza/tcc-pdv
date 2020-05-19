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
        $nome_pag = "Usuários";
        $icone_pag = "users.png";
        $iconeMouseOut = "users.png";
        $bread_crumb = "Home > Usuários";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./home.php"><img id="voltar-home" src="./_imagens/voltar.png"/></a>

        <table class="busca">
        <?php 

          if ($_SESSION['tipo'] == 'adm') {
            $consulta = $conexao->query("SELECT id_user, nome, cpf, login, tipo FROM usuarios");
            if (!$consulta) {
              echo "<tr><td style='font-style: italic;'>Infelizmente, não foi possível realizar a consulta!</td></tr>";
            } else {
                    if ($consulta->num_rows == 0) {
                      echo "<tr><td style='font-style: italic;'>Nenhum registro encontrado!</td></tr>";
                      } else {
                              echo "<tr id='cabecalho'><td>Nome</td><td>CPF</td><td>Login</td><td>Tipo</td><td><a class='adicionar' href='./usuarios-cadastrar.php' title='Adicionar novo usuário'><img src='./_imagens/adicionar.png'/></a></td></tr>";
                              //$linha = 0;
                              while ($reg = $consulta->fetch_object()) {
                                echo "<tr><td>$reg->nome</td><td>$reg->cpf</td><td>$reg->login</td><td>$reg->tipo</td><td><a title='Editar' href='usuarios-edit.php?id=$reg->id_user'><img src='./_imagens/editar.png'/></a> <a title='Excluir' href='usuarios-delete.php?id=$reg->id_user'><img src='./_imagens/deletar.png'/></a></td></tr>";
                                //echo "<tr><td name='tdNome'>$reg->nome</td><td name='tdLogin'>$reg->login</td><td name='tdTipo'>$reg->tipo</td><td><a href='javascript:editarUsuario($linha)'><img src='./_imagens/editar.png'></a> <a title='Excluir' href='usuarios-delete.php?id=$idUser'><img src='./_imagens/deletar.png'/></a></td></tr>";
                                //$linha++;
                              }
                        }
              } 
          } else {
                  echo msgAviso("Você não tem permissão para acessar esta página!");
            }
        ?>

        <?php
           /*function removeUser($id) {
             $conexao->query("DELETE FROM usuarios WHERE id_user = '$id'");
           }*/
        ?>
      </table>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>