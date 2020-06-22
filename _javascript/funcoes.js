
function mudaFoto(foto) {
  document.getElementById("icone").src = foto;
}

function abreMenu() {
  var i = 0;
  while (i <= 7) {
   var item = document.getElementsByClassName('items')[i];
   item.style.display = `list-item`;
   i++;
  }
}

function fechaMenu() {
  var i = 0;
  while (i <= 7) {
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

function validarEmissao(e) {
  dt = document.getElementById('dtEmForm');

  var charCode = e.charCode ? e.charCode : e.keyCode;

  if (charCode != 8 && charCode != 9) {
      if (charCode < 48 || charCode > 57) {
          return false;
      } else {
              if (dt.value.length == 2 || dt.value.length == 5) {
                dt.value += `/`;
              }
        }
  }
}

function validarReceb(e) {
  dt = document.getElementById('dtRecForm');

  var charCode = e.charCode ? e.charCode : e.keyCode;

  if (charCode != 8 && charCode != 9) {
      if (charCode < 48 || charCode > 57) {
          return false;
      } else {
              if (dt.value.length == 2 || dt.value.length == 5) {
                dt.value += `/`;
              }
        }
  }
}

function validarVenc(e) {
  dt = document.getElementById('vctForm1');

  var charCode = e.charCode ? e.charCode : e.keyCode;

  if (charCode != 8 && charCode != 9) {
      if (charCode < 48 || charCode > 57) {
          return false;
      } else {
              if (dt.value.length == 2 || dt.value.length == 5) {
                dt.value += `/`;
              }
        }
  }
}

function validarValid(e) {
  dt = document.getElementById('vldForm1');

  var charCode = e.charCode ? e.charCode : e.keyCode;

  if (charCode != 8 && charCode != 9) {
      if (charCode < 48 || charCode > 57) {
          return false;
      } else {
              if (dt.value.length == 2 || dt.value.length == 5) {
                dt.value += `/`;
              }
        }
  }
}

function validarMoeda(objTextBox, SeparadorMilesimo, SeparadorDecimal, e){
  var sep = 0;  
  var key = '';  
  var i = j = 0;  
  var len = len2 = 0;  
  var strCheck = '0123456789';  
  var aux = aux2 = '';  
  var whichCode = (window.Event) ? e.which : e.keyCode;  
  if (whichCode == 13 || whichCode == 8) return true;  
  key = String.fromCharCode(whichCode); // Valor para o código da Chave  
  if (strCheck.indexOf(key) == -1) return false; // Chave inválida  
  len = objTextBox.value.length;  
  for(i = 0; i < len; i++)
    if ((objTextBox.value.charAt(i) != '0') && (objTextBox.value.charAt(i) != SeparadorDecimal)) break;  
  aux = '';  
  for(; i < len; i++)
      if (strCheck.indexOf(objTextBox.value.charAt(i))!=-1) aux += objTextBox.value.charAt(i);  
  aux += key;  
  len = aux.length;  
  if (len == 0) objTextBox.value = '';  
  if (len == 1) objTextBox.value = '0'+ SeparadorDecimal + '0' + aux;  
  if (len == 2) objTextBox.value = '0'+ SeparadorDecimal + aux;  
  if (len > 2) {  
      aux2 = '';  
      for (j = 0, i = len - 3; i >= 0; i--) {  
        if (j == 3) {  
            aux2 += SeparadorMilesimo;  
            j = 0;  
        }  
        aux2 += aux.charAt(i);  
        j++;  
      }  
      objTextBox.value = '';  
      len2 = aux2.length;  
      for (i = len2 - 1; i >= 0; i--)  
      objTextBox.value += aux2.charAt(i);  
      objTextBox.value += SeparadorDecimal + aux.substr(len - 2, len);  
  }  
  return false;
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



//Funções para confirmação de exclusão
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

function confirmacaoBoleto(bol) {
  var ok = window.confirm(`Deseja realmente excluir este boleto do sistema? Esta operação não poderá ser desfeita.`);

  if (ok) {
    location.href=`./boleto-delete.php?bol=${bol}`;
  }
}
