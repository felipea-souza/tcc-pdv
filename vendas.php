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
    <style>
      div#interface {
        height: 1020px;
      }
      div#compra {
        #border: 1px solid green;
      }
      main {
        float: left;
        margin-right: 25px;
      }
      select {
        width: 500px;
      }
      option{
        font-size: 14px;
      }
      aside {
        float: center;
        #margin-top: 54px;
        padding-top: 50px;
      }
      table {
        border-collapse: collapse;
        #margin-left: 10px;
        background-color: #f2f2f2;
        #border: 1px solid red;
      }
      td {
          padding: 4px;
          #border: 1px solid red;
          font-size: 12px;  
      }
      input.qtd::-webkit-outer-spin-button,
      input.qtd::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
      }
      input.qtd[type=number] {
        -moz-appearance: textfield;
      }
      td.valor-direita {
        text-align: right;
      }
    </style>
  </head>

  <body>
    <?php 
      $nome_pag = "Vendas";
      $icone_pag = "vendas.png";
      $iconeMouseOut = "vendas.png";
      $bread_crumb = "Página Inicial > Vendas";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href=""><img id="voltar-home" src="./_imagens/voltar.png"></a>

      <?php

        $valorCaixa = $_POST['valorCaixa'] ?? null;

        if (!isset($valorCaixa)) {
          echo "<form class='cadastro' action='./vendas.php' method='post'><fieldset><legend>Abertura de Caixa</legend>
                  <p>Valor: R$ <input type='text' name='valorCaixa' id='valorCaixa' onKeyPress='return validarMoeda(this,`.`,`,`,event)'>

                  <input type='submit' value='Abrir caixa'></p>
                </fieldset></form>";
        } else {
                echo "
                <div id='compra'>
                        <main>
                          <p>Produto: <input type='text' name='cBusca' id='cBusca' placeholder='Cód. barra / descrição' onKeyUp='produtosBusca()'> <input class='buscar' type='image' src='./_imagens/buscar.png' onClick='produtosBusca()'></p>
                          
                          Cód. barra | Descrição | Lote | Validade | Estoque<br>
                          <select id='selProdutos' size='5'>
                            <!-- Produtos -->
                          </select>
                          
                          <input type='button' id='idInclui' value='>>' onClick='incluiItem()'>
                        </main>

                        <aside>
                          <table id='venda'>
                            <tr><td>ÍTEM</td><td>CÓDIGO</td><td>DESCRIÇÃO</td><td>QUANT.</td><td>VL UNIT</td><td>VL TOT</td><td></td></tr>
                            <!-- <tr></tr> -->
                          </table>
                          <table style='float: right'>
                            <tr><td>Total: R$</td><td id='total'>0,00</td></tr>
                            <tr><td><select style='width: 90px;'><option>Dinheiro</option>
                                                                 <option>Cartão</option></select></td></tr>
                          </table>
                        </aside>
                      </div>";
          }
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
      $(document).ready(produtosBusca());

      function produtosBusca() {
        var produto = document.getElementById('cBusca').value;
        $.ajax({  //  <- objeto da classe XmlHttpRequest
          url: './_includes/produto-buscar.php', //Diz pro Ajax para onde vai ser enviado o script (para ser executado)
          method: 'post',
          data: {chave: produto},  // O parâmetro 'data' recebe também um objeto

          success: function(resposta) {
            document.getElementById('selProdutos').innerHTML = resposta;
          }
        });
      }

      var i = 0;
      function incluiItem() {
        
        i++;
        var opcao = $(`#selProdutos`).find(`option:selected`);
        var cb = opcao.data(`cb`);
        var desc = opcao.data(`desc`);
        //var quant = opcao.data(`quant`);
        var preco = opcao.data(`preco`);

        preco = String(preco).replace(`.`, `,`);
        var linha = document.createElement('tr');
        linha.setAttribute('id', `item${i}`);
        linha.setAttribute('name', 'linha');
        linha.innerHTML = "<td name='numItem'>"+i+"</td><td>"+cb+"</td><td>"+desc+"</td><td><input type='number' class='qtd' name='qtd' id='qtd"+i+"' min='0' onKeyUp='vlTotal(`"+i+"`)' style='width: 35px;'></td><td class='valor-direita' name='pr' id='pr"+i+"'>"+preco+"</td><td class='valor-direita' name='vlTot' id='vlTot"+i+"'></td><td><a name='deletar' href='javascript:removeItem(`"+i+"`)' title='Remover ítem'><img src='./_imagens/retirar.png'></td></a>";

        var tabela = document.getElementById('venda');
        tabela.appendChild(linha);

        var quant = document.getElementById(`qtd${i}`).value;

        preco = preco.replace(`,`, `.`);
        var vlTot = quant * Number(preco);
        vlTot = vlTot.toFixed(2);
        vlTot = vlTot.replace(`.`, `,`);
        document.getElementById(`vlTot${i}`).innerText = vlTot;

      }

      function removeItem(num) {
        document.getElementById(`item${num}`).remove();
        i--;

        for (c=0 ; c<=i-1 ; c++) {
          document.getElementsByName(`linha`)[c].id = `item${c+1}`;
          document.getElementsByName(`numItem`)[c].innerText = c+1;
          document.getElementsByName('qtd')[c].id = `qtd${c+1}`;
          //document.getElementsByName('qtd')[c].onkeyup = "vlTotal(`"+(c+1)+"`)";
          document.getElementsByName('qtd')[c].setAttribute('onkeyup', "vlTotal(`"+(c+1)+"`)")
          document.getElementsByName(`pr`)[c].id = `pr${c+1}`;
          document.getElementsByName(`deletar`)[c].href = "javascript:removeItem(`"+(c+1)+"`)";
          document.getElementsByName(`vlTot`)[c].id = `vlTot${c+1}`
        }

        var soma = 0;
        for (c=1 ; c<=i ; c++) {
          var vlTot = document.getElementById(`vlTot${c}`).innerText;
          vlTot = Number.parseFloat(vlTot.replace(`,`, `.`));
          soma += vlTot;
        }
        soma = String(soma.toFixed(2));
        soma = soma.replace(`.`, `,`);
        document.getElementById('total').innerText = soma;
      }

      function vlTotal(pos) {
        var qtd =  Number(document.getElementById(`qtd${pos}`).value);

        var preco = document.getElementById(`pr${pos}`).innerText;
        preco = Number(preco.replace(`,`, `.`));

        var vlTot = qtd * preco;
        vlTot = vlTot.toFixed(2);
        //console.log(typeof vlTot);
        vlTot = String(vlTot);
        vlTot = vlTot.replace(`.`, `,`);

        document.getElementById(`vlTot${pos}`).innerText = vlTot;

        //var total = document.getElementById('total').innerText;
        var soma = 0;
        for (c=1 ; c<=i ; c++) {
          var vlTot = document.getElementById(`vlTot${c}`).innerText;
          vlTot = Number.parseFloat(vlTot.replace(`,`, `.`));
          soma += vlTot;
        }
        soma = String(soma.toFixed(2));
        soma = soma.replace(`.`, `,`);
        document.getElementById('total').innerText = soma;
        //console.log(typeof soma);
      }
    </script>
  </body>
</html>