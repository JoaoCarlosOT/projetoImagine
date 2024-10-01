<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Classe de validação
class Imgno_validacao {
	// Verifica a validade de um endereço de vídeo hospedado no YouTube
	static function validarVazio($valor) { return (bool)$valor; }
	
	static function validarCampoNumerico($valor) { return preg_match('/^\d+$/', $valor); }
	
	static function validarDATETIME($valor) { return strtotime($valor); }
	
	// Verifica a validade de um endereço de vídeo hospedado no YouTube
	static function validarURL($valor) { return preg_match('/^(https?:\/\/)?(www\.)?([a-zA-Z0-9_\-]+)+\.([a-zA-Z]{2,4})(?:\.([a-zA-Z]{2,4}))?\/?(.*)$/', $valor); }

	// Verifica a validade de um endereço de vídeo hospedado no YouTube
	static function validarVideo($valor) { return preg_match('@youtube.com/watch\?.*&?v=.+@', $valor); }
	
	// Verifica a validade de um documento CV
	static function validarCV($valor) { return preg_match('/(pdf)|(doc)|(docx)$/', $valor); }
	
	// Verifica a validade de um endereço de email
	static function validarEmail($valor) { return preg_match('/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,}$/', $valor); }
	
	// Verifica a validade de um telefone
	static function validarTelefone($valor) { 
		
		$valor = preg_replace('/[()]/', '', $valor);
		if (preg_match('/^(?:(?:\+|00)?(55)\s?)?(?:\(?([0-0]?[0-9]{1}[0-9]{1})\)?\s?)??(?:((?:9\d|[2-9])\d{3}\-?\d{4}))$/', $valor, $matches) === false) {
			return false;
		}

		$ddd = preg_replace('/^0/', '', $matches[2] ?? '');
		$number = $matches[3] ?? '';
		$number = preg_replace('/-/', '', $number);

		$codigosDDD = [11, 12, 13, 14, 15, 16, 17, 18, 19, 21, 22, 24, 27, 28, 31, 32, 33, 34, 35, 37, 38, 41, 42, 43, 44, 45, 46, 47, 48, 49, 51, 53, 54, 55, 61, 62, 64, 63, 65, 66, 67, 68, 69, 71, 73, 74, 75, 77, 79, 81, 82, 83, 84, 85, 86, 87, 88, 89, 91, 92, 93, 94, 95, 96, 97, 98, 99];

		if(!$ddd || !$number){ return false; }
		if(!in_array($ddd,$codigosDDD)){ return false; }
		if(!in_array(strlen($number),array(8,9))){ return false; }
		
		return true;
	}
	// Verifica a validade de um telefone
	static function validarTelefoneOld($valor) { 
		$regex1 = '/^(?:\+)[0-9]{2}\s?(?:\()[0-9]{2}(?:\))\s?(?:)[0-9]{1}(?:.)\s?[0-9]{4,4}(?:-)[0-9]{4}$/';
		$regex2 = '/^(?:\+)[0-9]{2}\s?(?:\()[0-9]{2}(?:\))\s?[0-9]{4,5}(?:-)[0-9]{4}$/';
		$regex3 = '/^(?:\()[0-9]{2}(?:\))\s?(?:)[0-9]{1}(?:.)\s?[0-9]{4,4}(?:-)[0-9]{4}$/';
		$regex4 = '/^(?:\()[0-9]{2}(?:\))\s?[0-9]{4,5}(?:-)[0-9]{4}$/';
		if(preg_match($regex1, $valor)) return true;
		if(preg_match($regex2, $valor)) return true;
		if(preg_match($regex3, $valor)) return true;
		if(preg_match($regex4, $valor)) return true;
		return false;
	}
	
	// Verifica a validade de uma data
	static function validarData($valor) { return preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $valor); }
	static function validarDataInversa($valor) { return preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $valor); }
	
	// Verifica a validade de um horário
	static function validarHorario($valor) { return preg_match('/^([01][0-9])|(2[0-3])(:[0-5][0-9]){1,2}$/', $valor); }
	
	// Verifica a validade de um apelido que compõe um link
	static function validarApelidoLink($valor) { return preg_match('/^[a-zA-Z0-9\-_]+$/', $valor); }
	
	// Verifica se uma data é futura
	static function validarDataFutura($data, $today = false, $strict = false) {
		$today = $today? implode('',array_reverse(explode('/', $today))) : JFactory::getDate(time())->toFormat('%Y%m%d');
		return $strict? implode('',array_reverse(explode('/', $data))) > $today: implode('',array_reverse(explode('/', $data))) >= $today;
	}
	
	// Verifica a validade de um CEP
	static function validarCEP($valor) { return preg_match('/^\d{2}\.?\d{3}(-)?\d{3}$/', $valor); }
	
	// Verifica a validade de uma senha
	static function validarSenha($valor) { return (JString::strlen($valor) >= 8) && preg_match('/\D/', $valor) && preg_match('/\d/', $valor); }
	
	// Verifica a validade de um número de CNPJ
	static function validarCNPJ($valor){
		$valor = preg_replace('/\D/', '', $valor);
		if(strlen($valor) != 14) return false;
		if(preg_match('/^(0{14})|(1{14})|(2{14})|(3{14})|(4{14})|(5{14})|(6{14})|(7{14})|(8{14})|(9{14})$/', $valor)) return false;
		$digito1 = $valor[12];
		$digito2 = $valor[13];
		$c = array(6,5,4,3,2,9,8,7,6,5,4,3,2);
		$total1 = 0;
		$total2 = 0;
		
		// Dígito 1
		for($i = 0; $i < 12; $i++) $total1 += $c[$i + 1] * (int)$valor[$i];
		$total1 = 11 - ($total1 % 11);
		if($total1 == 11 || $total1 == 10) $total1 = 0;
		
		// Dígito 2
		for($i = 0; $i < 13; $i++) $total2 += $c[$i] * (int)$valor[$i];
		$total2 = 11 - ($total2 % 11);
		if($total2 == 11 || $total2 == 10) $total2 = 0;
		
		// Resultado
		return ($digito1 == $total1 && $digito2 == $total2);
	}
	
	// Verifica a validade de um número de CPF
	static function validarCPF($valor){
		$valor = preg_replace('/\D/', '', $valor);
		if(strlen($valor) != 11) return false;
		if(preg_match('/(^0{11})|(1{11})|(2{11})|(3{11})|(4{11})|(5{11})|(6{11})|(7{11})|(8{11})|(9{11})$/', $valor)) return false;
		$digito1 = $valor[9];
		$digito2 = $valor[10];
		$total1 = 0;
		$total2 = 0;
		
		// Dígito 1
		for($i = 0; $i < 9; $i++) $total1 += (10 - $i) * (int)$valor[$i];
		$total1 = 11 - ($total1 % 11);
		if($total1 == 11 || $total1 == 10) $total1 = 0;
		
		// Dígito 2
		for($i = 0; $i < 10; $i++) $total2 += (11 - $i) * (int)$valor[$i];
		$total2 = 11 - ($total2 % 11);
		if($total2 == 11 || $total2 == 10) $total2 = 0;
		
		// Resultado
		return ($digito1 == $total1 && $digito2 == $total2);
	}
}
?>