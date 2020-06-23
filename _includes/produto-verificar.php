<?php

require_once './connection.php';
require_once './funcoes.php';

$cb = $_POST['chave'];
$pos = $_POST['pos'];

$query = "SELECT cod_barra FROM produtos WHERE cod_barra = '$cb'";

$consulta = $conexao->query($query);

if (!$consulta) {
  echo msgAviso("Não foi possível verificar o cadastro deste ítem: $cb<br><br>Consulte-o no <i>Relatório de Produtos</i>, clicando <a href='./produtos-relatorio.php' target='_blank'>aqui</a><br><br><a href='javascript:fecharDiv(`$pos`)' title='Fechar'><img src='./_imagens/fechar.png'></a>");
} else {
				if ($consulta->num_rows == 0) {
					echo msgAviso("Produto não cadastrado: $cb<br><br>Ir para <a href='./produtos-cadastrar.php?cb=$cb' target='_blank'>página de cadastro</a> de produtos.<br><br><a href='javascript:fecharDiv(`$pos`)' title='Fechar'><img src='./_imagens/fechar.png'></a>");
				}
	}