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
      table.busca {
        font-size: 11px;
      }
    </style>
  </head>

  <body>
    <?php 
      $nome_pag = "Relatório de Fornecedores";
      $icone_pag = "documento.png";
      $iconeMouseOut = "documento.png";
      $bread_crumb = "Página Inicial > Relatório > Relatório de Fornecedores";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php
        $chave = $_GET["cBusca"] ?? "";
        $ordem = $_GET["o"] ?? "";

        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="javascript:history.go(-1)"><img id="voltar-home" src="./_imagens/voltar.png"></a>

      <div id="alinhar">
        <form id="busca" action="./fornecedores-relatorio.php" method="get">
          Ordenar: 
          <a href="./fornecedores-relatorio.php?o=cnpj&cBusca=<?php echo $chave ?>">CNPJ</a> |
          <a href="./fornecedores-relatorio.php?o=rs&cBusca=<?php echo $chave ?>">Razão Social</a> | 
          <a href="./fornecedores-relatorio.php?o=nf&cBusca=<?php echo $chave ?>">Nome Fant.</a> | 
          <a href="./fornecedores-relatorio.php?o=ba&cBusca=<?php echo $chave ?>">Bairro</a> |

          Buscar: <input type="text" name="cBusca" id="cBusca" size="20" maxlength="30"> <input type="image" class="buscar" id="iBusca" name="iBusca" title="Buscar" src="./_imagens/buscar.png">
        </form>
      </div>

      <?php
        $query = "SELECT cnpj, razao_social, nome_fantasia, rua, cep, bairro, tel, cel FROM fornecedores";

        if(!empty($chave)) {
          $query .= " WHERE cnpj LIKE '%$chave%' OR razao_social LIKE '%$chave%' OR nome_fantasia LIKE '%$chave%' OR rua LIKE '%$chave%' OR cep LIKE '%$chave%' OR bairro LIKE '%$chave%' OR tel LIKE '%$chave%' OR cel LIKE '%$chave%'";
        }

        switch ($ordem) {
          case "cnpj":
                $query .= " ORDER BY cnpj";
                break;
          case "rs":
                $query .= " ORDER BY razao_social";
                break;
          case "nf":
                $query .= " ORDER BY nome_fantasia";
                break;
          case "ba":
                $query .= " ORDER BY bairro";
                break;
          /*default :
              $query .= " ORDER BY cnpj"; <- não precisou do 'default', pois verifiquei que com o parâmetro "o" vazio, o select padrão é realizado (ordenando pelo 'cnpj') */
        }

        echo "<table class='busca'>";
          $consulta = $conexao->query($query);
          if (!$consulta) {
            echo "<tr><td style='font-style: italic;'>Infelizmente, não foi possível realizar a consulta!</td></tr>";
          } else {
                  if ($consulta->num_rows == 0) {
                      echo "<tr><td style='font-style: italic;'>Nenhum registro encontrado!</td></tr>";
                  } else {
                          echo "<tr id='cabecalho'><td>CNPJ</td><td>Razão Social</td><td>Nome Fantasia</td><td>Rua</td><td>CEP</td><td>Bairro</td><td>Telefone</td><td>Celular</td></tr>";
                                  while ($reg = $consulta->fetch_object()) {
                                    echo "<tr><td>$reg->cnpj</td><td>$reg->razao_social</td><td>$reg->nome_fantasia</td><td>$reg->rua</td><td>$reg->cep</td><td>$reg->bairro</td><td>$reg->tel</td><td>$reg->cel</td></tr>";
                                  }      
                    }  
            }
        echo "</table>";   
      ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
  </body>
</html>