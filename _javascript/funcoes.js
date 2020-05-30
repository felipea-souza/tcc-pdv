
function mudaFoto(foto) {
  document.getElementById("icone").src = foto;
}

function abreMenu() {
  var i = 0;
  while (i <= 2) {
   var item = document.getElementsByClassName('items')[i];
   item.style.display = `list-item`;
   i++;
  }
}

function fechaMenu() {
  var i = 0;
  while (i <= 2) {
   var item = document.getElementsByClassName('items')[i];
   item.style.display = `none`;
   i++;
  }
}

//Funções para validação de formatos de campos (onkeypress)
function validarCPF(e) {
  cpf = document.getElementById('cpf');

  var charCode = e.charCode ? e.charCode : e.keyCode;

  if (charCode != 8 && charCode != 9) {
      if (charCode < 48 || charCode > 57) {
          return false;
      } else {
              if (cpf.value.length == 3 || cpf.value.length == 7) {
                cpf.value += `.`;
              }

              if (cpf.value.length == 11) {
                cpf.value += `-`;
              }
        }
  }
}

function validarCEP(e) {
  cep = document.getElementById('cepForm');

  var charCode = e.charCode ? e.charCode : e.keyCode;

  if (charCode != 8 && charCode != 9) {
      if (charCode < 48 || charCode > 57) {
          return false;
      } else {
              if (cep.value.length == 2) {
                cep.value += `.`;
              }

              if (cep.value.length == 6) {
                cep.value += `-`;
              }
        }
  }
}

function validarTel(e) {
  tel = document.getElementById('telForm');

  var charCode = e.charCode ? e.charCode : e.keyCode;

  if (charCode != 8 && charCode != 9) {
      if (charCode < 48 || charCode > 57) {
          return false;
      } else {
              if (tel.value.length == 0) {
                tel.value += `(`;
              }
              if (tel.value.length == 3) {
                tel.value += `)`;
              }
              if (tel.value.length == 4) {
                tel.value += ` `;
              }
              if (tel.value.length == 9) {
                tel.value += `-`;
              }
        }
  }
}

function validarCel(e) {
  cel = document.getElementById('celForm');

  var charCode = e.charCode ? e.charCode : e.keyCode;

  if (charCode != 8 && charCode != 9) {
      if (charCode < 48 || charCode > 57) {
          return false;
      } else {
              if (cel.value.length == 0) {
                cel.value += `(`;
              }
              if (cel.value.length == 3) {
                cel.value += `)`;
              }
              if (cel.value.length == 4) {
                cel.value += ` `;
              }
              if (cel.value.length == 10) {
                cel.value += `-`;
              }
        }
  }
}


//====================

// Funções para verificação de campos vazios de formulários (submit)
/*function validarCamposProduto() {
  produto = document.getElementById('produtoForm').value;
  preco = document.getElementById('precoForm').value;

  if(produto.length == 0 || preco.length == 0) {
    window.alert(`Todos os campos devem ser preenchidos!`);
  } else {
          document.getElementById('submit').click();
    }
  }*/



//Função para confirmação de exclusão de produto
function confirmacaoProduto(cod_barra) {
  var ok = window.confirm(`Deseja realmente excluir este produto? Esta operação não poderá ser desfeita.`);

  if (ok) {
    location.href=`./produtos-delete.php?cb=${cod_barra}`;
  }
}

function confirmacaoForn(cnpj) {
  var ok = window.confirm(`Deseja realmente excluir este fornecedor? Esta operação não poderá ser desfeita.`);

  if (ok) {
    location.href=`./fornecedores-delete.php?cnpj=${cnpj}`;
  }
}
