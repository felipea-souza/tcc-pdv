
function mudaFoto(foto) {
  document.getElementById("icone").src = foto;
}


//Funções para validação de formatos de campos
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
  cep = document.getElementById('cep');

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
  tel = document.getElementById('tel');

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
  cel = document.getElementById('cel');

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

// Funções para validação de campos preenchidos (não ficarem em branco)
function validarCampos() {
  var cnpj = document.getElementById('cnpjForm').value;
  var razao = document.getElementById('razao').value;
  var fant = document.getElementById('fant').value;
  var rua = document.getElementById('rua').value;
  var cep = document.getElementById('cep').value;
  var bairro = document.getElementById('bairro').value;

  if (cnpj.length == 0 || razao.length == 0 || fant.length == 0 || rua.length == 0 || cep.length == 0 || bairro.length == 0) {
    window.alert(`Existem campos a serem preenchidos!`);
  } else {
          if (cep.length < 10) {
            window.alert(`O campo "CEP" possui 8 (oito) dígitos numéricos`);
          } else {
                  document.getElementById('submit').click();
          	}
  	}
}


//====================

function editarUsuario(linha) {
	var linha = linha;

	nome = document.getElementsByName('tdNome')[linha];
	login = document.getElementsByName('tdLogin')[linha];
	tipo = document.getElementsByName('tdTipo')[linha];	

	var iNome = document.createElement('input');
	iNome.setAttribute('type', 'text');
	iNome.setAttribute('value', nome.innerText);
	nome.innerHTML = ``;
	nome.appendChild(iNome);

	var iLogin = document.createElement('input');
	iLogin.setAttribute('type', 'text');
	iLogin.setAttribute('value', login.innerText);
	login.innerHTML = ``;
	login.appendChild(iLogin);

	
	//CAMPO TIPO                                  //    tipo = <td>
	var sTipo = document.createElement('select'); //<-- stipo = <select>
	sTipo.setAttribute('name', 'sTipo');          //    opt1 = <option>

	//<option 1>
	var opt1 = document.createElement('option');
	opt1.text = (tipo.innerText == 'usr' ? 'Usuário':'Administrador');
	opt1.setAttribute('value', (opt1.text == 'Usuário' ? 'usr' : 'adm'));
	tipo.innerHTML = ``;
	tipo.appendChild(sTipo);
	document.getElementsByName('sTipo')[linha].appendChild(opt1);

	//<option 2>
	var opt2 = document.createElement('option');
	if (opt1.text == 'Usuário') {
		opt2.text = 'Administrador';
		opt2.setAttribute('value', 'adm');
	} else {
		opt2.text = 'Usuário';
		opt2.setAttribute('value', 'usr'); 
	}
	document.getElementsByName('sTipo')[linha].appendChild(opt2);

	var img = document.createElement('img');
	img.setAttribute('name', 'salvar')
	img.setAttribute('src', './_imagens/salvar.png');
	tipo.appendChild(img);
	document.getElementsByName('salvar')[linha].style.margin = 'auto auto auto 15px';

}