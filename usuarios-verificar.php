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
      $nome_pag = "Usuários";
      $icone_pag = "users.png";
      $iconeMouseOut = "users.png";
      $bread_crumb = "Página Inicial > Usuários > Verificar Usuário";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"/></a>

      <?php
        $cpf = $_GET['cpf'] ?? null;

        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p><p>Você não é administrador.");
        } else {
                if (empty($cpf)){
                  # Verificar se o usuário se encontra ou não cadastrado no sistema
                  echo "<form class='cadastro' action='usuarios-verificar.php' method='get'><fieldset><legend>Usuário</legend>
                          <p>CPF: <input type='text' name='cpf' id='cpf' size='14' maxlength='14' placeholder='Somente nº' onkeypress='return validarCPF(event)'> 
                          <a href='javascript:validarCampoCPF()'><img src='./_imagens/buscar.png' id='busca' title='Buscar'></a>
                          <input type='submit' id='iBusca' name='tBuscar' title='Buscar' src='./_imagens/buscar.png' style='display: none;'></p>
                        </fieldset></form>";
                } else {
                        $query = "SELECT id_user, cpf, nome, login, tipo FROM usuarios WHERE cpf = '$cpf'";
                                   $consulta = $conexao->query($query);

                                   if (!$consulta) {
                                     echo "Não foi possível realizar a consulta!";
                                   } else { 
                                           if ($consulta->num_rows == 0) {
                                            header("Location: ./usuarios-cadastrar.php?cpfval=$cpf");
                                           } else {
                                                   $reg = $consulta->fetch_object();
                                                   header("Location: ./usuarios-edit.php?id=$reg->id_user"); 
                                             }
                                     }
               	  }
                

          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
    <script>
    	// Funções para verificação de campos vazios de formulários (submit)
      function validarCampoCPF() {
        var cpf = document.getElementById('cpf').value;

        if (cpf.length == 0 || cpf.length < 14) {
          window.alert(`O CPF é composto por 14 dígitos.`);
        } else {
          document.getElementById('iBusca').click();
          //window.alert(`Submit`);
        }
      }
    </script>
