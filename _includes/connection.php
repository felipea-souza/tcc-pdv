<?php

// Constantes para conexões à base de dados
define('DB_HOSTNAME','localhost');
define('DB_USERNAME','root');
define('DB_PASSWORD','');
define('DB_DATABASE','bd_pdv');
define('DB_PREFIX','cw');
define('DB_CHARSET','utf8');

$conexao = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE); # $conexao é um novo objeto da classe mysqli. Deve-se enviar esses 4 parâmtros para o construtor':
                                                                           # host, usuario_bd, senha_bd e nome_bd

if ($conexao->connect_errno) {
	echo "<p>Encontrei um erro $conexao->errno --> $conexao->connect_error</p>";
  die();
}

$conexao->query("SET NAMES 'utf8'");
$conexao->query("SET character_set_connection=utf8");
$conexao->query("SET character_set_client=utf8");
$conexao->query("SET character_set_results=utf8");
