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
      div#venda-finalizada {
        border-radius: 5px;
        box-shadow: 0px 0px 40px rgba(0,0,0, .7);
        width: 500px;
        height: 200px;
        background-color: #006633;
        font-size: 26px;
        font-weight: bold;
        margin: auto;
      }
      div#venda-finalizada p, div#venda-finalizada a {
        text-align: center;
        color: #ffffff;
      }
      div#venda-finalizada p#troco {
        padding-top: 40px;
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

      <?php
        $itens = $_GET['itens'];
        $total = $_GET['total'];
        $forma = $_GET['forma'];
        $recebido = $_GET['recebido'];
        $troco = $_GET['troco'];
        $idUser = $_GET['id'];

        //Tabela 'vendas'
        $query = "INSERT INTO vendas (id_venda, dt_hr, total, forma, valor_receb, troco, id_user) VALUES (default, now(), '$total', '$forma', '$recebido', '$troco', '$idUser')";

        if ($conexao->query($query)) {
            //Adquirindo identificação da venda ('id_venda'), recém inserida acima, na tabela 'vendas'
            $consulta = $conexao->query("SELECT id_venda FROM vendas ORDER BY id_venda DESC LIMIT 1");
            $reg = $consulta->fetch_object();
            $idVenda = $reg->id_venda;

            for ($i=1 ; $i<=$itens ; $i++) {
              $lote = $_GET['lote'.$i];
              $quant = $_GET['quant'.$i];
              $preco = $_GET['preco'.$i];

              //Tabela 'produtos_venda'
              $query = "INSERT INTO produtos_venda (id_prod_venda, lote_estoque, quant, preco, id_venda) VALUES (default, '$lote', '$quant', '$preco', '$idVenda')";
              if (!$conexao->query($query)) {
                echo msgAviso("Não foi possível inserir na segunda tabela: 'produtos_venda'.");
                break;
              } else {
                      $conexao->query("UPDATE estoque SET quant = quant - '$quant' WHERE lote = '$lote'");
                }
            }
            echo msgSucesso("Venda finalizada!");
            $troco = str_replace('.', ',', $troco);
            echo "<div id='venda-finalizada'>
                    <p id='troco'>Troco: R$ $troco</p>
                    <p><a href='./vendas.php'><< Próxima Venda</a></p>
                  </div>";
        } else {
                echo msgAviso("Não foi possível inserir na primeira tabela: 'vendas'.");
          }

          
          
        
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>