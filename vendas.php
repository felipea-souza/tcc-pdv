<!DOCTYPE html>

<?php
  session_start();
  $usuario = $_SESSION['id_user'];
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
        #margin-right: 5px;
        #border-right: 1px solid red;
      }
      select {
        width: 490px;
      }
      option{
        font-size: 14px;
      }
      input#fecharVenda {
        color: white;
        background-color: #0099cc;
        padding: 4px;
        border-radius: 5px;
      }
      div#finalizar-venda {
        border-radius: 5px;
        box-shadow: 0px 0px 40px rgba(0,0,0, .7);
        width: 400px;
        height: 200px;
        background-color: #006633;
        font-weight: bold;
        color: #ffffff;
        position: absolute;
        top: 45%;
        left: 36%;
        display: none;
      }
      table#resumo {
        width: 100%;
        height: 100%;
      }
      table#resumo td {
        font-size: 22px;
        #border: 1px solid red;
      }
      aside {
        float: right;
        #margin-top: 54px;
        padding-top: 50px;
      }
      table.notinha {
        border-collapse: collapse;
        #margin-left: 10px;
        background-color: #f2f2f2;
        width: 415px;
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
      .oculto {
        display: none;
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

      <a title="Voltar" href="./home.php"><img id="voltar-home" src="./_imagens/voltar.png"></a>

      <?php
        
        echo "
          <div id='compra'>
            <main>
              <p>Produto: <input type='text' style='' name='cBusca' id='cBusca' placeholder='Cód. barra / descrição' onKeyUp='produtosBusca()'> <input class='buscar' type='image' src='./_imagens/buscar.png' onClick='produtosBusca()'></p>
              
              Cód. barra | Descrição | Lote | Validade | Estoque<br>
              <select id='selProdutos' size='5'>
                <!-- Produtos -->
              </select>
              
              <input type='button' id='idInclui' value='>>' title='Incluir' onClick='incluiItem()'>
              <p><input type='button' name='fecharVenda' id='fecharVenda' value='FECHAR VENDA' onClick='fecharVenda()'></p>

              <div id='finalizar-venda'>
                  <table id='resumo'>
                    <tr><td>Total: R$</td><td name='totalVenda' id='totalVenda'>0,00</td></tr>
                    <tr><td>Pagto:</td><td><select onClick='desabilitarReceb()' id='selPagto' style='width: 90px;'>
                                             <option value='din'>Dinheiro</option>
                                             <option value='car'>Cartão</option></select></td></tr>
                    <tr><td>Recebido: R$</td><td><input type='text' name='recebido' id='recebido' onKeyPress='return validarMoeda(this,`.`,`,`,event)' style='width: 90px;'></td></tr>
                    <tr class='oculto'><td>Troco:</td><td><input type='text' id='troco'></td></tr>
                    <tr><td><input type='button' value='FINALIZAR' onClick='finalizarVenda()' style='background-color: #0099cc; color: #ffffff;font-weight: bolder; border-radius: 5px;'></td><td><input type='button' value='CANCELAR' onClick='cancelarVenda()' style='background-color: #cc3300; color: #ffffff; font-weight: bolder; border-radius: 5px;'></td></tr>
                  </table>
              </div>
            </main>

            <aside>
              <table class='notinha' id='venda'>
                <tr><td>ÍTEM</td><td>CÓDIGO</td><td class='oculto'>LOTE</td><td>DESCRIÇÃO</td><td>QUANT.</td><td>VL UNIT</td><td>VL TOT</td><td></td></tr>
                <!-- <tr></tr> -->
              </table>
              <table class='notinha' style='float: right; text-align: right'>
                <tr><td>Total: R$</td><td id='total'>0,00</td></tr>
              </table>
            </aside>
          </div>";    
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
        var lote = opcao.data(`lote`);
        var desc = opcao.data(`desc`);
        //var quant = opcao.data(`quant`);
        var preco = opcao.data(`preco`);

        preco = String(preco).replace(`.`, `,`);
        var linha = document.createElement('tr');
        linha.setAttribute('id', `item${i}`);
        linha.setAttribute('name', 'linha');
        linha.innerHTML = "<td name='numItem'>"+i+"</td><td>"+cb+"</td><td class='oculto' name='lote' id='lote"+i+"'>"+lote+"</td><td>"+desc+"</td><td><input type='number' class='qtd' name='qtd' id='qtd"+i+"' min='0' onKeyUp='vlTotal(`"+i+"`)' style='width: 35px;'></td><td class='valor-direita' name='pr' id='pr"+i+"'>"+preco+"</td><td class='valor-direita' name='vlTot' id='vlTot"+i+"'></td><td><a name='deletar' href='javascript:removeItem(`"+i+"`)' title='Remover ítem'><img src='./_imagens/retirar.png'></td></a>";

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
          document.getElementsByName(`lote`)[c].id = `lote${c+1}`;
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

      function desabilitarReceb() {
        //var opcao = $(`#selPagto`).find(`option:selected`);
        var pagto = document.getElementById('selPagto');
        var valor = pagto.options[pagto.selectedIndex].value;
        //opcao.val(`value`);
        if (valor == `car`) {
          var recebido = document.getElementById('recebido');
          recebido.value = ``;
          recebido.disabled = `true`;
        } else {
                document.getElementById('recebido').removeAttribute(`disabled`);
          }
      }

      function fecharVenda() {
        var ok = true;
        var total = document.getElementById('total').innerText; //capturando valor total da "notinha"
        if (total == `0,00`) {
          window.alert(`A venda não pode ser finalizada!`);
          ok = false;
        }

        if (ok) {
          var qtd;
          for (var c=1 ; c<=i ; c++) {
            qtd = document.getElementById(`qtd${c}`).value;

            if (qtd.length == 0) {
              window.alert(`A venda não pode ser finalizada!`);
              ok = false;
              break;
            }
          }
        }

        if (ok) {
          document.getElementById('totalVenda').innerText = total; // valor total da "notinha" sendo impresso na modal de finalização de compra  
          document.getElementById('finalizar-venda').style.display = `block`;
        }
      }

      function finalizarVenda() {
        var totalVenda = document.getElementById('totalVenda').innerText;
        totalVenda = Number.parseFloat(totalVenda.replace(`,`, `.`));

        var pagto = document.getElementById('selPagto');
        var forma = pagto.options[pagto.selectedIndex].value;

        var recebido = document.getElementById('recebido').value;

        var idUsuario = <?php echo $usuario; ?>;

        if (forma == `din` && recebido.length == 0) {
          window.alert(`Informe o valor recebido!`);
        } else {
                //console.log(typeof recebido);
                //console.log(recebido);
                recebido = Number.parseFloat(recebido.replace(`,`, `.`));

                var ok = true;
                var troco = 0;
                if (forma == `car`) {
                  troco = troco.toFixed(2);
                  document.getElementById('troco').value = troco;
                } else {
                        if (recebido < totalVenda) {
                          window.alert(`Valor recebido menor do que o total da venda!`);
                          ok = false;
                        } else {
                                var troco = recebido - totalVenda;
                                troco = troco.toFixed(2);
                                troco = troco.replace(`.`, `,`);
                                document.getElementById('troco').value = troco;
                                troco = troco.replace(`,`, `.`);
                          }
                  }
                  if (ok) {
                      var itens = i;
                      var finalURL = ``;
                      for (c=1 ; c<=i ; c++) {
                        var lote = document.getElementById(`lote${c}`).innerText;
                        var quant = document.getElementById(`qtd${c}`).value

                        var preco = document.getElementById(`pr${c}`).innerText;
                        preco = preco.replace(`,`, `.`);

                        //id_venda (via select)
                        finalURL += `&lote${c}=${lote}&quant${c}=${quant}&preco${c}=${preco}`;
                      }
                      document.location.href = `./vendas-finalizar.php?itens=${itens}&total=${totalVenda}&forma=${forma}&recebido=${recebido}&troco=${troco}&id=${idUsuario}&${finalURL}`;
                  }
                  
          }
      }

      function cancelarVenda() {
        document.getElementById('finalizar-venda').style.display = `none`;
      }
    </script>
  </body>
</html>