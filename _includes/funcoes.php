<?php 

#Funções para mensagens de sistema
function msgSucesso($msg) {
	$resp = "<div id='sucesso' class='mensagem'><p><img class-'alerta' src='./_imagens/checked.png'> $msg </p></div>";
	return $resp;
}

function msgAviso($msg) {
	$resp = "<div id='aviso' class='mensagem'><p><img class='alerta' src='./_imagens/exclamacao.png'> $msg </p></div>";
	return $resp;
}

function msgErro($msg) {
	$resp = "<div id='erro' class='mensagem'><p><img class='alerta' src='./_imagens/exclamacao.png'> $msg </p></div>";
	return $resp;
}


#Funções para verificação de sessão
function isLogged() {
  if(!$_SESSION['login']) {
    header('Location: ./index.php');
    exit();
  }
}

function isAdmin() {
  $tipo = $_SESSION['tipo'] ?? null;
  if (is_null($tipo)) {
  	return false;
  } else {
  	      if ($tipo == 'adm') {
  	      	return true;
  	      } else {
  	      	      return false;
  	        }
    }
}