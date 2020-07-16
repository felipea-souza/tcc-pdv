<?php

require_once './connection.php';

$produto = $_POST['chave'];

$query = "SELECT produto FROM produtos WHERE cod_barra LIKE '%$produto%' OR produto LIKE '%$produto%'";

$consulta = $conexao->query($query);
  if (!$consulta) {
    echo "Não foi possível ralizar a consulta!";
  } else {
					if ($consulta->num_rows == 0) {
						echo "<option disabled>Nenhum ítem encontrado!</option>";
					} else {
									while ($reg = $consulta->fetch_object()) {
				    	      echo "<option data-desc='$reg->produto'>$reg->produto</option>";
				  	      }
  					}
		}
