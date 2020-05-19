<!DOCTYPE html>

<?php
  session_start();
  #require_once './_includes/verifica_login.php';
  require_once './_includes/funcoes.php';
  isLogged();
  require_once "./_includes/connection.php";
?>

<html lang="pt-br">
  <head>
    <meta charset="utf-8"/>
    <title>Sistema PDV</title>

    <link rel="stylesheet" type="text/css" href="./_css/estilo.css">
    <link rel="shortcut icon" href="./_imagens/fav-icon.png" type="image/x-icon">
  </head>

  <body>
    <div id="interface">

      <?php 
        $nome_pag = "Página de Cadastro de Novo Usuário";
        $icone_pag = "users.png";
        $iconeMouseOut = "users.png";
        $bread_crumb = "Home > Usuários > Adicionar Novo Usuário";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./usuarios.php"><img class="voltar" src="./_imagens/voltar.png"/></a>

      <?php

        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p><p>Você não é administrador.");
        } else {
                $nome = $_POST['nome'] ?? "";
                $cpf = $_POST['cpf'] ?? "";
                $login = $_POST['login'] ?? "";
                $senha = $_POST['senha'] ?? "";
                $tipo = $_POST['tipo'] ?? "";

                if (empty($nome)) {
                  echo "
                        <form class='cadastro' action='./usuarios-cadastrar.php' method='post'><fieldset><legend>Cadastrar Novo Usuário</legend>
                        <p>Nome: <input type='text' id='nome' name='nome' size='20' maxlenght='40'></p>
                        <p>CPF: <input type='text' id='cpf' name='cpf' size='14' maxlength='14' placeholder='Somente nº' onkeypress='return validarCPF(event)'></p>
                        <p>Login: <input type='text' id='login' name='login' size='20' maxlength='30'></p>
                        <p>Senha: <input type='password' id='senha' name='senha' size='12' maxlength='16'/></p>
                        <p>Tipo: <select name='tipo'>
                                   <option value='usr' selected>Usuário</option>
                                   <option value='adm'>Administrador</option>     
                                 </select></p>

                        <p><input type='button' value='Salvar' onclick='validarCampos()'></p>
                        <input type='submit' id='submit' style='display: none;'>
                        </fieldset></form>";
                } else {
                        if ($conexao->query("INSERT INTO usuarios (nome, cpf, login, senha, tipo) VALUES ('$nome', '$cpf', '$login', md5('$senha'), '$tipo')")) {
                          echo msgSucesso("Novo usuário cadastrado com sucesso!");
                        } else {
                          echo msgErro("Não foi possível cadastrar o usuário!");
                        }

                  }
          }
      ?>
    </div>
    <?php include_once "./rodape.php"; ?>
    
    <script language="javascript" src="_javascript/funcoes.js"></script>
    <script>
      function validarCampos() {
        var nome = document.getElementById('nome').value;
        var cpf = document.getElementById('cpf').value;
        var login = document.getElementById('login').value;
        var senha = document.getElementById('senha').value;
        
        if (nome.length == 0 || cpf.length == 0 || login.length == 0 || senha.length == 0) {
          window.alert(`Você deve preencher todos os campos!`);
        } else {
                if (login.indexOf(`@`) == -1 || login.indexOf(`.`) == -1) {
                  window.alert(`O campo "Login" deve conter um endereço de e-mail válido.`);
                } else {
                        if (senha.length < 6) {
                          window.alert(`A senha deve conter, no mínimo, 6 (seis) dígitos!`);
                        } else {
                                document.getElementById('submit').click();
                        }
                }  
        }
      }
    </script>
  </body>

</html>