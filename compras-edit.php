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
  </head>

  <body>
    <?php 
      $nome_pag = "Alterar Nota Fiscal";
      $icone_pag = "compras.png";
      $iconeMouseOut = "compras.png";
      $bread_crumb = "Página Inicial > Compras > Alterar Nota Fiscal";

      require_once './menu.php';
    ?>

    <div id="interface">
      <?php 
        require_once './cabecalho.php';
      ?>

      <a title="Voltar" href="./compras.php"><img id="voltar-home" src="./_imagens/voltar.png"></a>

      <?php 
        if (!isAdmin()) {
          echo msgAviso("Área restrita!</p> <p>Você não é administrador.");
        } else {
                if (isset($_GET['nf'])) {
                  $nf = $_GET['nf'] ?? null;
                  $razao = $_GET['razao'] ?? null;
                  $dtEmis = $_GET['de'] ?? null;
                  $dtReceb = $_GET['dr'] ?? null;
                  $total = $_GET['tot'] ?? null;
                  //$forma = $_GET['forma'] ?? null;
                  $forma = ($_GET['forma'] == 'din') ? 'Espécie': 'Boleto';
                 
                  echo "<form class='editar' action='./compras-edit.php' method='get'><fieldset><legend>Alterar Nota Fiscal</legend>
                          <p>Nota Fiscal: <input type='text' id='nfForm' name='nfForm' maxlenght='15' size='10'  value='$nf' readonly style='background-color: #ebebe4;'></p>
                          <p>Razão Social: <input type='text' id='razaoForm' name='razaoForm' maxlenght='40' size='40'  value='$razao' readonly style='background-color: #ebebe4;'></p>
                          <p>Emissão: <input type='date' id='dtEmForm' name='dtEmForm' maxlength='10' size='10'  value='$dtEmis'></p>
                          <p>Recebimento: <input type='date' id='dtRecForm' name='dtRecForm' maxlength='10' size='10'  value='$dtReceb'></p>";
                          $total = str_replace('.', ',', $total);
                          echo "<p>Total: R$ <input type='text' id='totForm' name='totForm' maxlength='10' size='10'  value='$total'></p>
                          <p>Pagto: <input type='text' id='pagtoForm' name='pagtoForm' value='$forma' readonly style='background-color: #ebebe4;'>";
                          if ($forma == 'Boleto') {
                            echo "<a href='./boletos-visualizar.php?nf=$nf' title='Ver boleto'><img src='./_imagens/ver.png' style='position: relative; left: 5px; top: 4px;'></a>";
                          }
                          echo "</p>";

                          echo "<input type='button' value='Salvar Alteração' onclick='validarCamposNF()'>
                          <input type='submit' id='submit' value='Salvar' style='display: none;'>
                        </fieldset></form>";

                } else {
                        $nfForm = $_GET['nfForm'] ?? null;
                        if (!isset($nfForm)) {
                          echo msgAviso("Nenhuma nota foi previamente verificada para alteração!");
                        } else {
                                $nfForm = $_GET['nfForm'];

                                /*$dtEmForm = explode("/", $_GET['dtEmForm']);
                                $dtEmForm2 = "";
                                for ($i=2 ; $i>=0 ; $i--) {
                                  $dtEmForm2 .= $dtEmForm[$i];
                                }*/
                                $dtEmForm = $_GET['dtEmForm'];

                                /*$dtRecForm = explode("/", $_GET['dtRecForm']);
                                $dtRecForm2 = "";
                                for ($i=2 ; $i>=0 ; $i--) {
                                  $dtRecForm2 .= $dtRecForm[$i];
                                }*/
                                $dtRecForm = $_GET['dtRecForm'];

                                $totForm = str_replace(",", ".", $_GET['totForm']);

                                if ($conexao->query("UPDATE compras SET dt_emissao = '$dtEmForm', dt_receb = '$dtRecForm', total = '$totForm' WHERE nf = '$nfForm'")) {
                                  echo msgSucesso("Nota fiscal alterada com sucesso!");
                                } else {
                                        echo msgErro("Não foi possível alterar os dados da nota fiscal!");
                                  }
                          }
                  }
          }

        

              ?>
    </div>

    <?php include_once "./rodape.php"; ?>

    <script type="text/javascript" src="./_javascript/funcoes.js"></script>
    <script>
      var dtEm = document.getElementById('dtEmForm').value;
      var dtRec = document.getElementById('dtRecForm').value;
      var tot = document.getElementById('totForm').value;

      // Funções para verificação de campos vazios de formulários (submit)
      function validarCamposNF() {
        dtEm2 = document.getElementById('dtEmForm').value;
        dtRec2 = document.getElementById('dtRecForm').value;
        tot2 = document.getElementById('totForm').value;

        if (dtEm == dtEm2 && dtRec == dtRec2 && tot == tot2) {
          window.alert(`Não há alteração de dados!`);
        } else {
                if (dtEm2.length < 10 || dtRec2.length < 10) {
                  window.alert(`Data incorreta!`);
                } else {
                        if(dtEm2.length == 0 || dtRec2.length == 0 || tot2.length == 0) {
                          window.alert(`Todos os campos devem ser preenchidos!`);
                        } else {
                                document.getElementById('submit').click();
                          }
                    }
                
          }
      }
   </script>
  </body>
</html>