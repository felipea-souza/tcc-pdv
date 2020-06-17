<?php

echo "
		<nav id='menu'>
      <ul>
       <!-- <li onmouseover='mudaFoto(`_imagens/especificacoes.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='specs.html'>Especificações</a></li> -->
       <!-- <li onmouseover='mudaFoto(`_imagens/fotos.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='fotos.html'>Fotos</a></li> -->
       <!-- <li onmouseover='mudaFoto(`_imagens/multimidia.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='multimidia.html'>Multimídia</a></li> -->
       "; 
       if(isAdmin()) {
          echo "<li onmouseover='mudaFoto(`./_imagens/produtos.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='produtos.php'>Produtos</a></li>
                <li onmouseover='mudaFoto(`_imagens/fornecedores.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='fornecedores.php'>Fornecedores</a></li>
                <li onmouseover='mudaFoto(`_imagens/users.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='usuarios.php'>Usuários</a></li>";
        }
echo "
        <!-- <li onmouseover='mudaFoto(`_imagens/documento.png`)' onmouseout='mudaFoto(`_imagens/$iconeMouseOut`)'><a href='institucional.php'>Institucional</a></li> -->
      </ul>

      <ul id='relatorios' onmouseover='abreMenu(); mudaFoto(`_imagens/documento.png`)' onmouseout='fechaMenu(); mudaFoto(`_imagens/$iconeMouseOut`)'>
        <li>Relatórios</li>
        <li class='items'><a href='./produtos-relatorio.php'>Produtos</a></li>
        <li class='items'><a href='./fornecedores-relatorio.php'>Fornecedores</a></li>
        <li class='items'><a href='./estoque-relatorio.php'>Estoque</a></li>
        <li class='items'><a href='./contas-relatorio.php'>Contas a Pagar</a></li>
        <li class='items'><a href='./notas-fiscais-relatorio.php'>Notas Fiscais</a></li>
        <li class='items'><a href='./compras-relatorio.php'>Compras</a></li>
      </ul>
    </nav>";