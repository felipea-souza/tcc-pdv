<?php

session_start();
require_once './connection.php';

if(empty($_POST["tLogin"]) || empty($_POST["tSenha"])) {
	header("Location: ../index.php");
	exit(); #<- "exit() para fechar os cabeçalhos"
}

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
	header('Location: ../painel.php');
	exit();
} else {
	$_SESSION['nao_autenticado'] = true;
	header("Location: ../index.php");
	exit();
}
