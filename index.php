<?php
  session_start();
  require_once './_includes/funcoes.php';
  require_once './_includes/connection.php';
?>

<!DOCTYPE html/>

<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <title>Sistema PDV</title>

    <link rel="stylesheet" type="text/css" href="./_css/estilo.css"/>
    <link rel="stylesheet" type="text/css" href="./_css/pag_index.css"/>
  </head>

  <body>
    <div id="interface">
      <header id="cabecalho" style="margin-bottom: 0px;">
        <hgroup>
          <h1>Sistema de Gestão PDV</h1> 
          <h2>Armazém Santo Antônio</h2>
          <h2 class="nome_pag">Página de Login</h2>
        </hgroup>
      </header>
      <hr style="margin-top: 0px;">
      <?php
        if (empty($_POST['tLogin']) || empty($_POST['tSenha'])) {

          if (isset($_SESSION["nao_autenticado"])) {
            echo msgErro("ERRO: Usuário ou senha inválidos!");
            unset($_SESSION["nao_autenticado"]);
          }
          
        
          echo "<form id='fLogin' action='./index.php' method='post'>";
            echo "<fieldset id='usuario'><legend>Identificação do Usuário</legend>";
              echo "<p><img class='icone_login' src='./_imagens/user_login.png'/> <input type='text' name='tLogin' id='cLogin' size='20px' maxlength='30' placeholder='Nome de usuário'/></p>";
              echo "<p><img class='icone_login' src='./_imagens/password.png'/> <input type='password' name='tSenha' id='cSenha' size='20px' maxlength='18' placeholder='Senha'/></p>";
              echo "<input type='submit' id='bEntrar' value='Entrar'/>";
            echo "</fieldset>"; 
          echo "</form>";
        } else {
                // Validação para prevenção à ataques de SQL Injection
                $login = mysqli_real_escape_string($conexao, $_POST["tLogin"]);
                $senha = mysqli_real_escape_string($conexao, $_POST["tSenha"]);

                #$query = "SELECT id_user, nome, tipo FROM usuarios WHERE login = '$login' AND senha = md5('$senha')";

                #$result = mysqli_query($conexao, $query);
                $result = $conexao->query("SELECT id_user, nome, tipo FROM usuarios WHERE login = '$login' AND senha = md5('$senha')");

                #$row = mysqli_num_rows($result);
                $row = $result->num_rows;

                if($row == 1) {
                  $_SESSION['login'] = $login;
                  $reg = $result->fetch_object();
                  $_SESSION['id_user'] = $reg->id_user;
                  $_SESSION['nome'] = $reg->nome;
                  $_SESSION['tipo'] = $reg->tipo;
                  //header('Location: ./home.php');
                  echo "<script>location.href='./home.php';</script>";
                  exit();
                } else {
                        $_SESSION['nao_autenticado'] = true;
                        header("Location: ./index.php");
                        //echo "<script>location.href='./index.php';</script>";
                        exit();
                  }
          }
      ?>
    </div>

    <?php require_once "./rodape.php"; ?>

  </body>
</html>

