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
      h1.titulo-relatorios {
        text-align: center;
        color: #606060;
      }
      form {
        width: 500px;
        margin-bottom: 25px;
      }
      select#selProduto {
        width: 300px;
      }
    </style>
  </head>

  <body>
    <?php 
      $nome_pag = "Relatórios Gerenciais";
      $icone_pag = "gerencial.png";
      $iconeMouseOut = "gerencial.png";
      $bread_crumb = "Página Inicial > Relatórios > Gerenciais";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"></a>

      <h1 class="titulo-relatorios">Relatórios de Vendas</h1>
      
      <form action="./relatorio-grafico-produtos-mensal.php" method="get" target="_blank"><fieldset><legend>Top 5 Mensal</legend>
        <p>Mês: <select name="mes">
                      <option value="01">Jan</option>
                      <option value="02">Fev</option>
                      <option value="03">Mar</option>
                      <option value="04">Abr</option>
                      <option value="05">Mai</option>
                      <option value="06">Jun</option>
                      <option value="07">Jul</option>
                      <option value="08">Ago</option>
                      <option value="09">Set</option>
                      <option value="10">Out</option>
                      <option value="11">Nov</option>
                      <option value="12">Dez</option>
                    </select></p>
        <p>Ano: <select name="ano">
                  <?php 
                    for ($ano=2020 ; $ano<=date('Y') ; $ano++) {
                      echo "<option value='$ano'>$ano</option>";
                    }
                  ?>
                </select></p>

        <input type="submit" value="GERAR">
      </fieldset></form>

      <form action="./relatorio-grafico-produto-mensal.php" method="get" target="_blank"><fieldset><legend>Produto Mensal (Período Anual)</legend>
        <p>Produto: <input type="text" id="cBusca" placeholder="Cód. barra / descrição" onKeyUp="produtoBusca()"></p>
                <p><select name="selProduto" id="selProduto" size="3">
                  <!--<option>Produtos</option>-->
                </select></p>
        <p>Ano: <select name="ano">
                  <?php 
                    for ($ano=2020 ; $ano<=date('Y') ; $ano++) {
                      echo "<option value='$ano'>$ano</option>";
                    }
                  ?>
                </select></p>

        <input type="submit" value="GERAR">
      </fieldset></form>

      <h1 class="titulo-relatorios">Relatório de Receitas</h1>

      <form action="./relatorio-grafico-receita-mensal.php" method="get" target="_blank"><fieldset><legend>Receita Mensal (Período Anual)</legend>
        <p>Ano: <select name="ano">
                  <?php 
                    for ($ano=2020 ; $ano<=date('Y') ; $ano++) {
                      echo "<option value='$ano'>$ano</option>";
                    }
                  ?>
                </select></p>
                <input type="submit" value="GERAR">
      </fieldset></form>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script>
      function produtoBusca() {
        var produto = document.getElementById('cBusca').value;
        $.ajax({
          url: './_includes/produto-buscar-relatorio.php',
          method: 'post',
          data: {chave: produto},

          success: function(resposta) {
            document.getElementById('selProduto').innerHTML = resposta;
          }
        });
      }
    </script>
  </body>
</html>