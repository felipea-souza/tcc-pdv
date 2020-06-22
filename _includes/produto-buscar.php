<?php

require_once './connection.php';

$produto = $_POST['chave'];

$query = "SELECT produtos_compra.cod_barra, produtos.produto, estoque.lote, date_format(estoque.validade, '%d/%m/%Y') AS validade, estoque.quant, produtos.preco  FROM estoque
					INNER JOIN produtos_compra
					ON estoque.lote = produtos_compra.lote

					INNER JOIN produtos
					ON produtos_compra.cod_barra = produtos.cod_barra

					WHERE produtos_compra.cod_barra LIKE '%$produto%' OR produtos.produto LIKE '%$produto%'
					HAVING estoque.quant > '0'
					ORDER BY produtos_compra.cod_barra";

$consulta = $conexao->query($query);
  if (!$consulta) {
    echo "Não foi possível ralizar a consulta!";
  } else {
					if ($consulta->num_rows == 0) {
						echo "<option disabled>Nenhum ítem encontrado!</option>";
					} else {
									while ($reg = $consulta->fetch_object()) {
										//$preco = str_replace(".", ",", $reg->preco);
				    	      echo "<option data-cb='$reg->cod_barra' data-lote='$reg->lote' data-desc='$reg->produto' data-quant='$reg->quant' data-preco='$reg->preco'>$reg->cod_barra | $reg->produto | $reg->lote | $reg->validade | $reg->quant</option>";
				  	      }
  					}
		}
  				