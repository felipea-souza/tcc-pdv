<?php

echo "
  <header id='cabecalho'>
    <hgroup>
      <h1>Sistema de Gestão PDV</h1> 
      <h2>Armazém Santo Antônio</h2>
      <h2 class='nome_pag'>$nome_pag</h2>
    </hgroup>

    <a href='./home.php' title='Página inicial'><img id='homepage' src='./_imagens/homepage.png'/></a> 
    <img id='icone' src='_imagens/$icone_pag'>
    <!-- <a href='./user-logout.php' title='Sair'><img id='sair' src='_imagens/sair.png'/></a> -->

    <hr>
    
    <h3>$bread_crumb</h3>";

  $nome = explode(" ", $_SESSION['nome']);

  echo "<p style='font-size: 14px;'>Olá, ";
    for ($i=0; $i<=1; $i++) {
      echo ("$nome[$i] ");
    }
  echo "| <a href='user-edit.php'>Meus dados</a> | <a href='./user-logout.php' title='Sair'>Sair</a></p>";
echo "</header>";