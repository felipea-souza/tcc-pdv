<?php

echo "
  <header id='cabecalho'>
    <nav id='menu'>
      <ul>
        <li onmouseover='mudaFoto(`./_imagens/produtos.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='produtos.php'>Produtos</a></li>
       <!-- <li onmouseover='mudaFoto(`_imagens/especificacoes.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='specs.html'>Especificações</a></li> -->
       <!-- <li onmouseover='mudaFoto(`_imagens/fotos.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='fotos.html'>Fotos</a></li> -->
       <!-- <li onmouseover='mudaFoto(`_imagens/multimidia.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='multimidia.html'>Multimídia</a></li> -->
       "; 
       if(isAdmin()) {
          echo "<li onmouseover='mudaFoto(`_imagens/fornecedores.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='fornecedores.php'>Fornecedores</a></li>
                <li onmouseover='mudaFoto(`_imagens/users.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='usuarios.php'>Usuários</a></li>";
        }
echo "
        <li onmouseover='mudaFoto(`_imagens/documento.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='institucional.php'>Institucional</a></li>
      </ul>
    </nav>

    <hgroup>
      <h1>Sistema de Gestão PDV</h1> 
      <h2>Armazém Santo Antônio</h2>
      <h2 class='nome_pag'>$nome_pag</h2>
    </hgroup>

    <a href='./home.php' title='Home'><img id='homepage' src='./_imagens/homepage.png'/></a> 
    <img id='icone' src='_imagens/$icone_pag'/>
    <!-- <a href='./user-logout.php' title='Sair'><img id='sair' src='_imagens/sair.png'/></a> -->

    <hr>
    
    <h3>$bread_crumb</h3>";

  $nome = explode(" ", $_SESSION['nome']);

  echo "<p>Olá, ";
    for ($i=0; $i<=1; $i++) {
      echo ("$nome[$i] ");
    }
  echo "| <a href='user-edit.php'>Meus dados</a> | <a href='./user-logout.php' title='Sair'>Sair</a></p>";
echo "</header>";