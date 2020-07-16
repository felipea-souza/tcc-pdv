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
      $nome_pag = "Editar Usuário";
      $icone_pag = "users.png";
      $iconeMouseOut = "users.png";
      $bread_crumb = "Página Inicial > Usuários > Editar Usuário";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./usuarios.php"><img id="voltar-produto" src="./_imagens/voltar.png"/></a>

      <?php
        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p> <p>Você não é administrador.");
        } else {
                $id_user = $_GET['id']?? null;

                $id_userForm = $_GET['idForm'] ?? null;
                $nome = $_GET['nome'] ?? null;
                $cpf = $_GET['cpf'] ?? null;
                $login = $_GET['login'] ?? null;
                $tipo = $_GET['sTipo'] ?? null;

                if(isset($id_user)) {
                  $consulta = $conexao->query("SELECT id_user, nome, cpf, login, tipo FROM usuarios WHERE id_user = '$id_user'");

                  if (!$consulta) {
                    echo msgErro("Não foi possível buscar o registro deste usuário na base de dados!");
                  } else {
                          $reg = $consulta->fetch_object();

                          echo "<form class='editar' action='./usuarios-edit.php' method='get'><fieldset><legend>Alterar Usuário</legend>";
                            echo "<p style='display: none;'><input type='text' name='idForm' value='$id_user' maxlength='3' size='3'></p>";
                            echo "<p>Nome: <input type='text' id='nome' name='nome' size='20' maxlength='40' value='$reg->nome'/></p>";
                            echo "<p>CPF: <input type='text' id='cpf' name='cpf' value='$reg->cpf' maxlength='14' size='14' placeholder='Somente nº' onkeypress='return validarCPF(event)'></p>";
                            echo "<p>Login: <input type='text' id='login' name='login' size='20' maxlength='30' value='$reg->login'/></p>";
                            echo "<p>Tipo: <select name='sTipo' id='sTipo'>";
                                             if($reg->tipo == "usr") {
                                              echo "<option value='usr'>Usuário</option>
                                                    <option value='adm'>Administrador</option>
                                                    </select>";
                                             } else {
                                                     echo "<option value='adm'>Administrador</option>
                                                    <option value='usr'>Usuário</option>
                                                    </select>";
                                             }

                            echo "<p><input type='button' value='Salvar' onclick='validarCamposUserEdit()'></input></p>
                                  <p style='display: none;'><input type='submit' id='submit' value='Salvar'></p>
                                  </fieldset></form>";
                          }
                } else {
                        if ($conexao->query("UPDATE usuarios SET nome = '$nome', cpf = '$cpf', login = '$login', tipo = '$tipo' WHERE id_user = '$id_userForm'")) {
                          echo msgSucesso("Usuário alterado com sucesso!");
                        } else {
                                echo msgErro("Não foi possível alterar os dados do usuário!");
                          }
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
      var tipo = document.getElementById('sTipo').value;

      function validarCamposUserEdit() {
        var nome2 = document.getElementById('nome').value;
        var cpf2 = document.getElementById('cpf').value;
        var login2 = document.getElementById('login').value;
        var tipo2 = document.getElementById('sTipo').value;

        if (nome == nome2 && cpf == cpf2 && login == login2 && tipo == tipo2) {
          window.alert("Não há alteração de dados!");
        } else {
                if (nome2.length == 0 || cpf2.length == 0 || login2.length == 0) {
                  window.alert(`Você deve preencher todos os campos!`);
                } else {
                        if (login2.indexOf(`@`) == -1 || login2.indexOf(`.`) == -1) {
                          window.alert(`O campo "Login" deve conter um endereço de e-mail válido.`);
                        } else {
                                document.getElementById('submit').click();
                                //window.alert(`Submit`);
                          }  
                  }
          }

        
      }
    </script>
  </body>
</html>