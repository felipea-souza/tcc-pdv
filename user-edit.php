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
    <?php 
      $nome_pag = "Alterar Meus Dados";
      $icone_pag = "user.png";
      $iconeMouseOut = "user.png";
      $bread_crumb = "Página Inicial > Meus Dados";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./home.php"><img id="voltar-home" src="./_imagens/voltar.png"/></a>

      <?php 
        $id_user = $_SESSION['id_user'];

        $id_userForm = $_POST['idForm'] ?? null;
        $nome = $_POST['nome'] ?? null;
        $cpf = $_POST['cpf'] ?? null;
        $login = $_POST['login'] ?? null;
        $senha = $_POST['senha'] ?? null;

        if (isset($id_user) && !isset($id_userForm)) {
          $consulta = $conexao->query("SELECT nome, cpf, login, tipo FROM usuarios WHERE id_user = '$id_user'");

          if (!$consulta) {
            echo msgAviso("Não foi possível buscar os dados na base de dados!");
          } else {
                  $reg = $consulta->fetch_object();
                  echo "
                        <form class='editar' action='./user-edit.php' method='post'><fieldset><legend>Alterar Meus Dados</legend>
                          <p style='display: none;'><input type='text' name='idForm' value='$id_user' maxlength='3' size='3'></p>
                          <p>Nome: <input type='text' id='nome' name='nome' size='30' maxlength='40' value='$reg->nome'"; if(!isAdmin()) {echo "readonly style='background-color: #ebebe4;'";} echo "></p>
                          <p>CPF: <input type='text' id='cpf' name='cpf' size='14' maxlength='14' value='$reg->cpf'"; if(!isAdmin()) {echo "readonly style='background-color: #ebebe4;'";} echo "></p>
                          <p>Login: <input type='text' id='login' name='login' size='20' maxlength='30' value='$reg->login'"; if(!isAdmin()) {echo "readonly style='background-color: #ebebe4;'";} echo "></p>
                          <p>Senha: <input type='password' id='senha' name='senha' size='12' maxlength='16'></p>
                          <p>Tipo: <input type='text' id='tipo' name='tipo' maxlength='13' size='13' value='"; 
                                     if ($reg->tipo == 'adm') { echo "Administrador"; } else { echo "Usuário"; } 
                                     echo "' disabled></p>
                          <p><input type='button' value='Salvar' onclick='validarCampos()'></input></p>
                          <p style='display: none;'><input type='submit' id='submit' value='Salvar'></p></fieldset></form>";
          }
        } else {
                if ($conexao->query("UPDATE usuarios SET nome = '$nome', cpf = '$cpf', login = '$login', senha = md5('$senha') WHERE id_user = '$id_userForm'")) {
                  echo msgSucesso("Dados alterados com sucesso!");
                  if (empty($senha)) echo msgAviso("A senha foi mantida.");
                } else {
                        echo msgErro("Não foi possível alterar os dados do usuário!");
                  }
          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
    <script>
      var nome = document.getElementById('nome').value;
      var cpf = document.getElementById('cpf').value;
      var login = document.getElementById('login').value;
      //var senha = document.getElementById('senha').value;

      function validarCampos() {

        var nome2 = document.getElementById('nome').value;
        var cpf2 = document.getElementById('cpf').value;
        var login2 = document.getElementById('login').value;
        var senha2 = document.getElementById('senha').value;

        if (nome == nome2 && cpf == cpf2 && login == login2 && senha2.length == 0) {
          window.alert(`Não há alteração de dados!`);
        } else {
                if (nome2.length == 0 || cpf2.length == 0 || login2.length == 0) {
                  window.alert(`Todos os campos devem ser preenchidos!`);
                } else {
                        if (senha2.length > 0 && senha2.length < 6) {
                          window.alert(`A senha deve ter no mínimo 6 (seis) caracteres!`);
                        } else {
                                document.getElementById('submit').click();
                          }
                        
                }
          }
      }
    </script>
  </body>
</html>