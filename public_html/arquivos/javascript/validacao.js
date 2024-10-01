// Verifica se há dados em um campo
function vazio(valor){return valor.trim() == ''? true : false;}

// Verifica se um valor termina com JPG, JPEG, GIF ou PNG
function validarArquivo(valor){ return valor && valor.match(/^(.*)(\.)((pdf)|(doc)|(docx)|(jpg)|(jpeg)|(gif)|(png))$/i); }

// Verifica se um valor termina com JPG, JPEG, GIF ou PNG
function validarCV(valor){ return valor && valor.match(/^(.*)(\.)((pdf)|(doc)|(docx))$/i); }

// Verifica se um valor é endereço de vídeo no YouTube
function validarVideo(valor){ return valor && (valor.match(/youtube.com\/watch\?.*&?v=.+/i) || valor.match(/youtube.com\/shorts\/.+/i)); }

// Verifica se um valor termina com JPG, JPEG, GIF ou PNG
function validarImagem(valor){ return valor && valor.match(/^(.*)(\.)((jpg)|(jpeg)|(gif)|(png))$/i); }

// Verifica a validade de um endereço de email
function validarEmail(valor){ return valor.match(/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,}$/); }

// Verifica a validade de um telefone
function validarTelefone(valor){ 
	valor = valor.replace(/\D/g, '');

	//verifica se tem a qtde de numero correto
	if (!(valor.length >= 10 && valor.length <= 11)) return false;

	//Se tiver 11 caracteres, verificar se começa com 9 o celular
	if (valor.length == 11 && parseInt(valor.substring(2, 3)) != 9) return false;
	
	if (valor.length == 10 && [2, 3, 4, 5,6, 7, 8, 9].indexOf(parseInt(valor.substring(2, 3))) == -1) return false;

	// verifica numero errado proposital 
	for (var n = 0; n < 10; n++) { if (valor == new Array(11).join(n) || valor == new Array(12).join(n)) return false; }
	//DDDs validos
	var codigosDDD = [11, 12, 13, 14, 15, 16, 17, 18, 19,21, 22, 24, 27, 28, 31, 32, 33, 34,35, 37, 38, 41, 42, 43, 44, 45, 46,47, 48, 49, 51, 53, 54, 55, 61, 62,64, 63, 65, 66, 67, 68, 69, 71, 73,74, 75, 77, 79, 81, 82, 83, 84, 85,86, 87, 88, 89, 91, 92, 93, 94, 95, 96, 97, 98, 99];
	//verifica se o DDD
	if (codigosDDD.indexOf(parseInt(valor.substring(0, 2))) == -1) return false;

	return true;
}

function validarTelefoneOld(valor){ 
	regex1 = /^(?:\+)[0-9]{2}\s?(?:\()[0-9]{2}(?:\))\s?(?:)[0-9]{1}(?:.)\s?[0-9]{4,4}(?:-)[0-9]{4}$/;
	regex2 = /^(?:\+)[0-9]{2}\s?(?:\()[0-9]{2}(?:\))\s?[0-9]{4,5}(?:-)[0-9]{4}$/;
	regex3 = /^(?:\()[0-9]{2}(?:\))\s?(?:)[0-9]{1}(?:.)\s?[0-9]{4,4}(?:-)[0-9]{4}$/;
	regex4 = /^(?:\()[0-9]{2}(?:\))\s?[0-9]{4,5}(?:-)[0-9]{4}$/;

	if(valor.match(regex1)) return true;
	if(valor.match(regex2)) return true;
	if(valor.match(regex3)) return true;
	if(valor.match(regex4)) return true;
	return false;
}

// Verifica a validade de um CEP
function validarCEP(valor){ return valor.match(/^\d{2}\.?\d{3}(-)?\d{3}$/); }


function validarCPFCNPJ(valor){
	valor = valor.replace(/\D/g, '');

	if(valor.length <= 11){
		return validarCPF(valor);
	}else{
		return validarCNPJ(valor);
	}
}

// Verifica a validade de um número de CNPJ
function validarCNPJ(valor){
	valor = valor.replace(/\D/g, '');
	if(valor.length != 14) return false;
	if(valor.match(/^(0{14})|(1{14})|(2{14})|(3{14})|(4{14})|(5{14})|(6{14})|(7{14})|(8{14})|(9{14})$/)) return false;
	var digito1 = valor.charAt(12);
	var digito2 = valor.charAt(13);
	var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];
	var total1 = 0;
	var total2 = 0;
	
	// Dígito 1
	for(i = 0; i < 12; i++) total1 += c[ i + 1 ] * parseInt(valor.charAt(i));
	total1 = 11 - (total1 % 11);
	if(total1 == 11 || total1 == 10) total1 = 0;
	
	// Dígito 2
	for(i = 0; i < 13; i++) total2 += c[i] * parseInt(valor.charAt(i));
	total2 = 11 - (total2 % 11);
	if(total2 == 11 || total2 == 10) total2 = 0;
	
	// Resultado
	return (digito1 == total1 && digito2 == total2);
}

// Verifica a validade de um número de CPF
function validarCPF(valor){
	valor = valor.replace(/\D/g, '');
	if(valor.length != 11) return false;
	if(valor.match(/(^0{11})|(1{11})|(2{11})|(3{11})|(4{11})|(5{11})|(6{11})|(7{11})|(8{11})|(9{11})$/)) return false;
	var digito1 = valor.charAt(9);
	var digito2 = valor.charAt(10);
	var total1 = 0;
	var total2 = 0;
	
	// Dígito 1
	for(i = 0; i < 9; i++) total1 += (10 - i) * parseInt(valor.charAt(i));
	total1 = 11 - (total1 % 11);
	if(total1 == 11 || total1 == 10) total1 = 0;
	
	// Dígito 2
	for(i = 0; i < 10; i++) total2 += (11 - i) * parseInt(valor.charAt(i));
	total2 = 11 - (total2 % 11);
	if(total2 == 11 || total2 == 10) total2 = 0;
	
	// Resultado
	return (digito1 == total1 && digito2 == total2);
}

// Verifica a validade de um número cartão de crédito
function validarNumeroCCredito(valor) {
	valor = valor.replace(/\D/g, '');
	if(!valor) return false;
	var soma = 0;
	var alternar = false;

	for (var i = valor.length - 1; i >= 0; i--) {
		var digito = parseInt(valor.charAt(i), 10);

		if (alternar) {
			digito *= 2;
			if (digito > 9) {
				digito -= 9;
			}
		}

		soma += digito;
		alternar = !alternar;
	}

	return soma % 10 === 0;
}

// Verificar se o mês está entre 01 e 12
function validarMes(valor) {
	valor = valor.replace(/\D/g, '');
	return /^(0[1-9]|1[0-2])$/.test(valor);
}

// Verificar se o ano tem quatro dígitos e está no formato adequado
function validarAno(valor) {
	valor = parseInt(valor.replace(/\D/g, ''));
	return /^\d{4}$/.test(valor);
}

// Verificar se o CVV é composto por 3 dígitos numéricos
function validarCVV(valor) {
	// Remover espaços em branco e caracteres não numéricos
    valor = valor.replace(/\D/g, '');

    return /^\d{3}$/.test(valor);
}

function toReal(val){
	if(!val) return 0;
	val = (''+(val/100).toFixed(2)).replace('.', ',');
	var len = val.length;
	if(len > 6) val = val.substring(0, len - 6) + '.' + val.substring(len - 6);
	return val;
}

function formatarData(data) {
	return (data.substr(0, 10).split('-').reverse().join('/'));
}