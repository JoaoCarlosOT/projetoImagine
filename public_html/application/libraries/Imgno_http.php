<?php
// Impede o acesso direto a este arquivo
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Classe que busca conteúdo externo acessando links HTTP
class Imgno_http {
	// Retorna os dados de uma dada url formatados em UTF-8
	static function conteudo($url, $encode = true){
		// Busca por CURL
		if(function_exists('curl_init')){
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:8.0.1) Gecko/20100101 Firefox/8.0.1');
			$conteudo = curl_exec($ch);
			$curl_erro = curl_errno($ch);
			if(curl_errno($ch) != 0) return false;
			curl_close($ch);
		}
		// Busca por FOpen
		else if(ini_get('allow_url_fopen') == '1') $conteudo = @file_get_contents($url);
		
		// Retorna o conteúdo obtido
		return $conteudo? ($encode? utf8_encode($conteudo) : $conteudo) : false;
	}
}
?>