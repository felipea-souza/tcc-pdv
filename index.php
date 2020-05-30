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
    
    <link rel="stylesheet" type="text/css" href="./_css/estilo.css">
    <link rel="stylesheet" type="text/css" href="./_css/pag_index.css">
    <link rel="shortcut icon" href="./_imagens/fav-icon.png" type="image/x-icon">
  </head>

  <body>
    <div id="interface">
      <header id="cabecalho">
        <hgroup>
          <h1>Sistema de Gestão PDV</h1> 
          <h2>Armazém Santo Antônio</h2>
          <h2 class="nome_pag">Página de Login</h2>
        </hgroup>
      </header>

      <hr>

      <div id="quem_somos">
        <h1>Quem Somos</h1>
        <p><em>O armazém Santo Antônio é uma micro empresa familiar formada no ano 2000 que tem o intuito de trazer aos nossos clientes produtos de qualidade e variedade, ótimos preços, atendimento com excelência e um ambiente aconchegante para atender o público</em>.</p>
        <img src="./_imagens/fachada.jpeg">
      </div>

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
              echo "<input type='submit' id='bEntrar' value='Entrar'>";
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

      <div id="missao">
        <h1>Missão</h1>
        <p><em>Nossa missão é oferecer produtos e atendimento que superem as expectativas de nossos clientes, garantindo a qualidade e a procedência de todo o nosso estoque e investindo na qualidade, eficiência do atendimento e ótimos preços</em>.</p>
      </div> 

      <div id="visao">
        <h1>Visão</h1>
        <p><em>Nossa visão é ser uma empresa e estar sempre estar a frente dos demais no mercado de armazéns e ser reconhecida pelos clientes pelos ótimos preços e atendimento de qualidade e a cada dia evoluir no mercado comercial</em>.</p>
      </div>
    </div>

    <?php require_once "./rodape.php"; ?>

  </body>
</html>

