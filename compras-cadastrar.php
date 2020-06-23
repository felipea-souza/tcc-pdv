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
      .oculto {
        display: none;
      }
      fieldset {
        margin-bottom: 20px;
      }
      img.adiciona_remove {
        margin-top: 10px;
      }
      div#aviso-produto {
        display: none;
      }
    </style>
  </head>

  <body>
    <?php 
      $nome_pag = "Cadastrar Compra";
      $icone_pag = "compras.png";
      $iconeMouseOut = "compras.png";
      $bread_crumb = "Página Inicial > Compras > Cadastrar Compra";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"></a>
      <?php 
        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p><p>Você não é administrador.");
        } else {
                $nf = $_GET['nf'] ?? null;
                if (isset($nf)) {
                  $i = 1;
                  $consulta = $conexao->query("SELECT cnpj, razao_social FROM fornecedores");

                  echo "  <!-- Tabela 'compras' -->
                          <form class='conjunto_campos' action='./compras-cadastrar.php' method='get'>
                          <fieldset><legend>Nota Fiscal</legend>
                          <p>Nota Fiscal: <input type='text' id='nfForm' name='nfForm' maxlenght='15' size='8'  value='$nf' readonly style='background-color: #ebebe4;'></p>
                          <p>Fornecedor: <select id='cnpjForm' name='cnpjForm' id='cnpjForm'>
                                           <option value='' disabled selected>&lt;Selecione&gt;</option>";
                                           while ($reg = $consulta->fetch_object()) {
                                            echo "<option value='$reg->cnpj'>$reg->razao_social</option>";
                                           }
                  echo "                  </select></p>
                          <p>Emissão: <input type='date' id='dtEmForm' name='dtEmForm'></p>
                          <p>Recebimento: <input type='date' id='dtRecForm' name='dtRecForm'></p>
                          <p>Total R$: <input type='text' id='totForm' name='totForm' maxlength='10' size='10' value='0,00' onKeyPress='return validarMoeda(this,`.`,`,`,event)'></p>
                          <p class='oculto'>Forma pagto: <input type='text' id='pagtoForm' name='pagtoForm' value='bol'></p>
                          </fieldset>
                          
                          <p>À vista em espécie <input type='checkbox' id='checkbox' onclick='desabilitar()'></p>
                          <!-- Tabela 'contas_a_pagar' -->
                          <fieldset id='conjunto2'><legend>Duplicatas</legend>
                          <table id='duplicatas'>
                            <tr><td>Nº boleto</td><td>Valor R$</td><td>Venc.</td></tr>
                            <tr id='duplicata1'><td><input type='text' id='boletoForm1' name='boletoForm1' maxlength='50' size='50'></td>
                            <td><input type='text' id='valorForm1' name='valorForm1' maxlength='10' size='10' value='0,00'  onKeyPress='return validarMoeda(this,`.`,`,`,event)'></td>
                            <td><input type='date' id='vctForm1' name='vctForm1'></td></tr>
                          </table><a href='javascript:novaDuplicata()' title='Nova duplicata'><img class='adiciona_remove' src='./_imagens/adicionar.png'></a><a href='javascript:removeDuplicata()' title='Remover duplicata'><img class='adiciona_remove' src='./_imagens/remover.png'></a>
                          <p class='oculto'>Número de boletos: <input type='text' id='numBoletosForm' name='numBoletosForm' value='1'></p></fieldset> 
                          

                          <div id='aviso-produto'>

                          </div>

                          <!-- Tabelas 'produtos_compra' e 'estoque' -->
                         <fieldset><legend>Produtos</legend>
                          <!-- * campo 'produtos_compra.id_prod_compra' (chave primária, AUTO_INCREMENT) -->
                          <table id='produtos'>
                            <tr><td>Cód. barra</td><td>Lote</td><td>Validade</td><td>Quant.</td><td>Preço R$</td></tr>
                            <tr><td><input type='text' id='cbForm1' name='cbForm1' maxlength='13' size='13' onKeyUp='verificaProduto(`1`)'>
                            <td><input type='text' id='loteForm1' name='loteForm1' maxlength='8' size='8'></td>
                            <td><input type='date' id='vldForm1' name='vldForm1'></td>
                            <td><input type='number' id='quantForm1' name='quantForm1' min='0' maxlength='3' size='3'></td>
                            <td><input type='text' id='precoForm1' name='precoForm1' maxlength='10' size='10' value='0,00' onKeyPress='return validarMoeda(this,`.`,`,`,event)'></td></tr>
                          </table><a href='javascript:novoCodBarra()'><img class='adiciona_remove' src='./_imagens/adicionar.png' title='Novo cód. barra'></a><a href='javascript:removeCodBarra()' title='Remover cód. barra'><img class='adiciona_remove' src='./_imagens/remover.png'></a>
                          <p class='oculto'>Número de códigos de barra: <input type='text' id='numCodigosForm' name='numCodigosForm' value='1'></p></fieldset></fieldset>
                          
                          <p><input type='button' value='Salvar' onclick='validarCamposNF()'></p>
                          <p class='oculto'><input type='submit' id='submit' value='SUBMIT'></p>
                        </fieldset></form>"; 
                } else { 
                        //início TODOS OS INSERTS
                        $nf = $_GET['nfForm'] ?? null;
                        if (!isset($nf)) {
                          echo msgAviso("Nenhuma nota fiscal foi previamente verificada para inclusão no sistema!");
                        } else { //4
                                # GRAVANDO dados da nota fiscal nas tabelas do banco 

                                // Tabela 'compras'
                                $cnpj = $_GET['cnpjForm'] ?? null;
                                $dtEm = $_GET['dtEmForm'] ?? null;
                                $dtRec = $_GET['dtRecForm'] ?? null;

                                $tot = str_replace('.', '', $_GET['totForm']);
                                $tot = str_replace(',', '.', $tot);

                                $pagto = $_GET['pagtoForm'] ?? null;

                                $query = "INSERT INTO compras (nf, cnpj_forn, dt_emissao, dt_receb, total, pagto) VALUES ('$nf', '$cnpj', '$dtEm', '$dtRec', '$tot', '$pagto')";
                                if ($conexao->query($query)) {//5
                                  echo msgSucesso("Nota Fiscal cadastrada com sucesso!");
                                  //Insert da tabela 'contas_a_pagar'
                                  if ($pagto == "bol") {
                                  $numBoletos = $_GET['numBoletosForm'] ?? null;

                                  for ($i=1 ; $i<=$numBoletos; $i++) {
                                    $boleto = $_GET['boletoForm'.$i] ?? null;
                                    //$valor = $_GET['valorForm'.$i] ?? null;
                                    $valor = str_replace('.', '', $_GET['valorForm'.$i]);
                                    $valor = str_replace(',', '.', $valor);
                                    $vct = $_GET['vctForm'.$i] ?? null;
                                    //$nf */

                                    $query = "INSERT INTO contas_a_pagar (cod_barra_boleto, valor, vcto, nf_compra) VALUES ('$boleto', '$valor', '$vct', '$nf')";
                                    if (!$conexao->query($query)) {
                                      msgAviso("Não foi possível inserir na segunda tabela: 'contas_a_pagar'");
                                    }
                                  }
                                }
                                // Tabelas 'estoque' e 'produtos_compra'
                                $numCodigos = $_GET['numCodigosForm'];

                                for ($i=1 ; $i<=$numCodigos ; $i++) {
                                  //$cb = $_GET['cbForm'.$i] ?? null;
                                  $lote = $_GET['loteForm'.$i] ?? null;
                                  $quant = $_GET['quantForm'.$i] ?? null;
                                  $vld = $_GET['vldForm'.$i] ?? null;
                                  
                                  //$preco = $_GET['precoForm'.$i] ?? null;

                                  $query = "INSERT INTO estoque (lote, quant, validade) VALUES ('$lote', '$quant', '$vld')";

                                  if ($conexao->query($query)){//6 - início
                                        $cb = $_GET['cbForm'.$i] ?? null;
                                        $preco = str_replace('.', '', $_GET['precoForm'.$i]);
                                        $preco = str_replace(',', '.', $preco);

                                        $query = "INSERT INTO produtos_compra (id_prod_compra, cod_barra, lote, quant, preco, nf_compra) VALUES (default, '$cb', '$lote', '$quant', '$preco', '$nf')";

                                        if(!$conexao->query($query)) {
                                          echo "Não foi possível cadastrar o produto na quarta tabela: 'produtos_compra'";
                                        }
                                  } else {
                                          echo "Não foi possível cadastrar o produto na terceira tabela: 'estoque'";
                                    }
                                }
                                } else { 
                                        echo msgAviso("Não foi possível inserir na primeira tabela: 'compras'"); 
                                  } 
                          }
                  }//fim TODOS OS INSERTS
          }                       

      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
      function desabilitar() {
        var checkbox = document.getElementById('checkbox');
        var duplicatas = document.getElementById('conjunto2');
        var forma = document.getElementById('pagtoForm');
        if(checkbox.checked) {
          duplicatas.disabled = `true`;
          forma.value = `din`;

          for (var n=i ; n>=2 ; n--) {
            document.getElementById(`duplicata${n}`).remove();
          }
          document.getElementById('boletoForm1').value = ``;
          document.getElementById('valorForm1').value = ``;
          document.getElementById('vctForm1').value = ``;
          i = 0;
          document.getElementById('numBoletosForm').value = `${i}`;
        } else {
                duplicatas.removeAttribute(`disabled`);
                forma.value = `bol`;
                i = 1;
                document.getElementById('numBoletosForm').value = `${i}`;
          }
      }

      var i = 1;
      var j = 1;
      function novaDuplicata() {
        if (i <= 2) {
          i++;
          var linha = window.document.createElement(`tr`);
          linha.id = `duplicata${i}`;
          
          linha.innerHTML += "<td><input type='text' id='boletoForm"+j+"' name='boletoForm"+j+"' maxlength='50' size='50'></td>"+
                                     "<td><input type='text' id='valorForm"+j+"' name='valorForm"+j+"' maxlength='10' size='10' value='0,00' onKeyPress='return validarMoeda(this,`.`,`,`,event)'></td>"+
                                     "<td><input type='date' id='vctForm"+j+"' name='vctForm"+j+"'></td>";
          var duplicatas = document.getElementById('duplicatas');
          duplicatas.appendChild(linha);
          document.getElementById('numBoletosForm').value = `${i}`;
        }
      }

      function removeDuplicata() {
        if (i >= 2) {
          document.getElementById(`duplicata${i}`).remove();
          i--;
          document.getElementById('numBoletosForm').value = `${i}`;
        }
      }

      function novoCodBarra() {
        if (j <= 14) {
          j++;
          var linha = window.document.createElement(`tr`);
          linha.id = `produto${j}`;
          
          linha.innerHTML += "<td><input type='text' id='cbForm"+j+"' name='cbForm"+j+"' maxlength='13' size='13' onKeyUp='verificaProduto(`"+j+"`)'>"+
                                   "<td><input type='text' id='loteForm"+j+"' name='loteForm"+j+"' maxlength='8' size='8'></td>"+
                                   "<td><input type='date' id='vldForm"+j+"' name='vldForm"+j+"'></td>"+
                                   "<td><input type='number' id='quantForm"+j+"' name='quantForm"+j+"' min='0' maxlength='3' size='3'></td>"+
                                   "<td><input type='text' id='precoForm"+j+"' name='precoForm"+j+"' maxlength='10' size='10' value='0,00' onKeyPress='return validarMoeda(this,`.`,`,`,event)'></td>";
          var produtos = document.getElementById('produtos');
          produtos.appendChild(linha);
          document.getElementById('numCodigosForm').value = `${j}`;
        }
      }

      function removeCodBarra() {
        if (j >= 2) {
          document.getElementById(`produto${j}`).remove();
          j--;
          document.getElementById('numCodigosForm').value = `${j}`;
        }
      }

      // Funções para verificação de campos vazios de formulários (submit)
      function validarCamposNF() {
        var cnpj = document.getElementById('cnpjForm').value;
        var dtEm = document.getElementById('dtEmForm').value;
        var dtRec = document.getElementById('dtRecForm').value;
        var tot = document.getElementById('totForm').value;


        var ok = true;
        if(cnpj.length == 0 || dtEm.length == 0 || dtRec.length == 0 || tot.length == 0 || tot == `0,00`) {
          ok = false;
          window.alert(`Existem campos a serem preenchidos!`);
        }

        if (ok) {
          var numBoletos = document.getElementById('numBoletosForm').value;

          var boleto;
          var valor;
          var vct;
          for (var i=1 ; i<=numBoletos ; i++) {
            boleto = document.getElementById('boletoForm'+i).value;
            valor = document.getElementById('valorForm'+i).value;
            vct = document.getElementById('vctForm'+i).value;

            if (boleto.length == 0 || valor.length == 0 || valor == `0,00` || vct.length == 0) {
              window.alert(`Existem campos a serem preenchidos!`);
              ok = false;
              break;
            }
          }

          if (ok) {
            var numCodigos = document.getElementById('numCodigosForm').value;

            var cb;
            var lote;
            var vld;
            var quant;
            var preco;
            for (var i=1 ; i<=numCodigos ; i++) {
              cb = document.getElementById(`cbForm`+i).value;
              lote = document.getElementById(`loteForm`+i).value;
              vld = document.getElementById(`vldForm`+i).value;
              quant = document.getElementById(`quantForm`+i).value;
              preco = document.getElementById(`precoForm`+i).value;

              if (cb.length == 0 || lote.length == 0 || vld.length == 0 || quant.length == 0 || preco.length == 0 || preco == `0,00`) {
                window.alert(`Existem campos a serem preenchidos!`);
                ok = false;
                break;
              }
            }

            if (ok) {
                    //window.alert(`Testando: apertou o <SUBMIT>`);
                    document.getElementById('submit').click();
            } 
          }   
        }
      }

      function validarCamposDuplicatas() {
        var numBoletos = document.getElementById('numBoletosForm').value;
        

        var ok = `true`;
        for (var i=1 ; i<=numBoletos ; i++) {
          boleto = document.getElementById('boletoForm'+i).value;
          valor = document.getElementById('valorForm'+i).value;
          vct = document.getElementById('vctForm'+i).value;

          if (boleto.length == 0 || valor.length == 0 || vct.length == 0) {
            window.alert(`Existem campos a serem preenchidos!`);
            ok = `false`;
            break;
          }
        }
        if (ok == `true`) {
                 window.alert(`Apertou o "Sibmit"!`);
        } 
      }

      function verificaProduto(pos) {
        var cb = document.getElementById(`cbForm${pos}`);
        if (cb.value.length == 13) {
          $.ajax({  //  <- objeto da classe XmlHttpRequest
            url: './_includes/produto-verificar.php', //Diz pro Ajax para onde vai ser enviado o script (para ser executado)
            method: 'post',
            data: {chave: cb.value, pos: pos},  // O parâmetro 'data' recebe também um objeto

            success: function(resposta) {
              if (resposta) {
                var div = document.getElementById('aviso-produto');
                div.innerHTML = resposta;
                div.style.display = `block`;
                cb.style.background = `#efdfde`;
              }
            }
          });
        }
      }

      function fecharDiv(pos) {
        var div = document.getElementById('aviso-produto');
        div.style.display = `none`;
        div.innerHTML = ``;
        document.getElementById(`cbForm${pos}`).style.background = ``;
      }

    </script>
  </body>
</html>