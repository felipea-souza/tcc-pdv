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
      img {
        margin-top: 10px;
      }
    </style>
  </head>

  <body>
    <div id="interface">

      <?php 
        $nome_pag = "Cadastrar Compra";
        $icone_pag = "compras.png";
        $iconeMouseOut = "compras.png";
        $bread_crumb = "Home > Compras > Cadastrar Compra";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./compras.php"><img id="voltar-home" src="./_imagens/voltar.png"></a>
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
                          <p>Fornecedor: <select id='cnpjForm' name='cnpjForm'>";
                                           while ($reg = $consulta->fetch_object()) {
                                            echo "<option value='$reg->cnpj'>$reg->razao_social</option>";
                                           }
                  echo "                  </select></p>
                          <p>Emissão: <input type='date' id='dtEmForm' name='dtEmForm'></p>
                          <p>Recebimento: <input type='date' id='dtRecForm' name='dtRecForm'></p>
                          <p>Total R$: <input type='text' id='totForm' name='totForm' maxlength='10' size='10'></p>
                          <p class='oculto'>Forma pagto: <input type='text' id='pagtoForm' name='pagtoForm' value='bol'></p>
                          </fieldset>
                          
                          <p>À vista em espécie <input type='checkbox' id='checkbox' onclick='desabilitar()'></p>
                          <!-- Tabela 'contas_a_pagar' -->
                          <fieldset id='conjunto2'><legend>Duplicatas</legend>
                          <table id='duplicatas'>
                            <tr><td>Nº boleto</td><td>Valor</td><td>Venc.</td></tr>
                            <tr id='duplicata1'><td><input type='text' id='boletoForm1' name='boletoForm1' maxlength='50' size='50'></td>
                            <td><input type='text' id='valorForm1' name='valorForm1' maxlength='10' size='10'></td>
                            <td><input type='date' id='vctForm1' name='vctForm1'></td></tr>
                          </table><a href='javascript:novaDuplicata()' title='Nova duplicata'><img src='./_imagens/adicionar.png'></a><a href='javascript:removeDuplicata()' title='Remover duplicata'><img src='./_imagens/remover.png'></a>
                          <p class='oculto'>Número de boletos: <input type='text' id='numBoletosForm' name='numBoletosForm' value='1'></p></fieldset> 
                          

                          <!-- Tabelas 'produtos_compra' e 'estoque' -->
                         <fieldset><legend>Produtos</legend>
                          <!-- * campo 'produtos_compra.id_prod_compra' (chave primária, AUTO_INCREMENT) -->
                          <table id='produtos'>
                            <tr><td>Cód. barra</td><td>Lote</td><td>Validade</td><td>Quant.</td><td>Preço</td></tr>
                            <tr><td><input type='text' id='cbForm1' name='cbForm1' maxlength='13' size='13'>
                            <td><input type='text' id='loteForm1' name='loteForm1' maxlength='8' size='8'></td>
                            <td><input type='date' id='vldForm1' name='vldForm1'></td>
                            <td><input type='text' id='quantForm1' name='quantForm1' maxlength='3' size='3'></td>
                            <td><input type='text' id='precoForm1' name='precoForm1' maxlength='10' size='10'></td></tr>
                          </table><a href='javascript:novoCodBarra()'><img src='./_imagens/adicionar.png' title='Novo cód. barra'></a><a href='javascript:removeCodBarra()' title='Remover cód. barra'><img src='./_imagens/remover.png'></a>
                          <p class='oculto'>Número de códigos de barra: <input type='text' id='numCodigosForm' name='numCodigosForm' value='1'></p></fieldset></fieldset>
                          
                          <p><input type='button' value='Salvar' onclick='validarCamposNF()'></p>
                          <p class='oculto'><input type='submit' id='submit' value='Salvar'></p>
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

                                $tot = str_replace(',', '.', $_GET['totForm']) ?? null;
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
                                    $valor = str_replace(',', '.', $_GET['valorForm'.$i]) ?? null;
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
                                        //$preco = $_GET['precoForm'.$i] ?? null;
                                        $preco = str_replace(',', '.', $_GET['precoForm'.$i]) ?? null;

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
          
          linha.innerHTML = `<td><input type='text' id='boletoForm${i}' name='boletoForm${i}' maxlength='50' size='50'></td>
                                     <td><input type='text' id='valorForm${i}' name='valorForm${i}' maxlength='10' size='10'></td>
                                     <td><input type='date' id='vctForm${i}' name='vctForm${i}'></td>`;
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
          
          linha.innerHTML += `<td><input type='text' id='cbForm${j}' name='cbForm${j}' maxlength='13' size='13'>
                                   <td><input type='text' id='loteForm${j}' name='loteForm${j}' maxlength='8' size='8'></td>
                                   <td><input type='date' id='vldForm${j}' name='vldForm${j}'></td>
                                   <td><input type='text' id='quantForm${j}' name='quantForm${j}' maxlength='3' size='3'></td>
                                   <td><input type='text' id='precoForm${j}' name='precoForm${j}' maxlength='10' size='10'></td>`;
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
        var dtEm = document.getElementById('dtEmForm').value;
        var dtRec = document.getElementById('dtRecForm').value;
        var tot = document.getElementById('totForm').value;



        if(dtEm.length == 0 || dtRec.length == 0 || tot.length == 0) {
          window.alert(`Existem campos a serem preenchidos!`);
        } else {
                var cb = document.getElementById('cbForm1').value;
                var lote = document.getElementById('loteForm1').value;
                var vld = document.getElementById('vldForm1').value;
                var quant = document.getElementById('quantForm1').value;
                var preco = document.getElementById('precoForm1').value;

                if (cb.length == 0 || lote.length == 0 || vld.length == 0 || quant.length == 0 || preco.length == 0) {
                  window.alert(`Existem campos a serem preenchidos!`);
                } else {
                        document.getElementById('submit').click();
                }
          }
      }

    </script>
  </body>
</html>