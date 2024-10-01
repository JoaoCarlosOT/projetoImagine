<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imgno_util{
	// Data / Hora
	static function datahora($dh){ return self::data($dh) .' às '. self::hora($dh) .'h'; }
	static function dataSQL($dh){ return implode('-', array_reverse(explode('/', substr($dh, 0, 10)))); }
	static function data($dh){ return implode('/', array_reverse(explode('-', substr($dh, 0, 10)))); }
	static function hora($dh){ return substr($dh, 11, 5); }
	static function horario($minutos){ return (($hora=floor($minutos/60)%24)<10? '0' : '') . $hora .':'. (($minutos=floor($minutos%60))<10? '0' : '') . $minutos; }
	
	// Valores
	static function float($valor){ return (float)preg_replace('/,/', '.', preg_replace('/\./', '', $valor)); }
	static function real($valor){ return number_format($valor/100, 2, ',', '.'); }
	
	// URLs
	static function string_url_safe($string, $delimiter = '-') {
		// Armazena o local pdrão
		$antigo = setlocale(LC_ALL, 0);
		
		// Seta o lacal para o en_US.UTF8
		setlocale(LC_ALL, 'en_US.UTF8');
		
		// Processa a string
		$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
		$clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
		//$clean = strtolower(trim($clean, '-'));
		$clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
		
		// Retorna o lacal para o padrão
		setlocale(LC_ALL, $antigo);
		
		// Retorna a string
		return $clean;
	}
	
	static function getAlias($string){ return strtolower(self::url_slug(trim($string))); }
	static function getMenu($option, $urlvars, $view = 'sistema'){ return JSite::getMenu()->getItems('link', 'index.php?option='.$option.'&view='.$view.($urlvars? '&'.$urlvars:''), true)->id; }
	static function route($valor){ return preg_replace('/\/$/', '.html', preg_replace('/\/\?format=([a-z]{3})$/', '.$1', JRoute::_($valor, false, -1))); }
	
	// Logs
	static function dump(){header('Content-Type: text/html; charset=utf-8'); echo '<pre>'; print_r(count($a=func_get_args())>1?$a:$a[0]); echo '</pre>'; exit; }
	static function sql($after = null){ $CI =& get_instance(); if($after){ echo $CI->db->last_query(); } else { echo $CI->db->_compile_select(); } exit;}
	
	// Utilidades
	static function imagine(){ $u = JFactory::getUser(); return !(($u->gid != 25) || !preg_match('/@imagineseusite.com.br$/', $u->email)); }
	static function completar_zeros($v, $qtd){ if(($i = strlen($v = (int)$v)) < $qtd) for($i; $i < $qtd; $i++) $v = '0'. $v; return $v; }
	
	// Encontra a pasta do template atual
	static function getTemplate(){
		if(!($v = JRequest::getString('versao_template'))){
			$db =& JFactory::getDBO();
			$db->setQuery('SELECT template FROM #__templates_menu WHERE client_id = 0');
			JRequest::setVar('versao_template', $v = $db->loadResult());
		}
		return $v;
	}
	
	// API do FaceBook e do Twitter
	static function facebook_api(){ self::carregar_script('fb_api', '//connect.facebook.net/pt_BR/all.js#xfbml=1'); }
	static function twitter_api(){ self::carregar_script('tw_api', '//platform.twitter.com/widgets.js'); }
	
	// API javascript
	static function carregar_script($api, $src, $root = false){
		if(!JRequest::getInt($api)){
			JRequest::setVar($api, 1);
			JFactory::getDocument()->addScriptDeclaration('jQuery(function(){carregar_script("'. $api .'_tag", "'. ($root? JURI::root() : '') . $src .'");});');
		}
	}
	
	// Adiciona script para geração de mapas com API GMapsV3
	static function gmaps_script($readonly = true){
		if(!$readonly){
			JHTML::script('gmapsv3.js', 'libraries/imagine/js/', false);
			self::carregar_script('extenso', JURI::root() . 'libraries/imagine/js/extenso.js');
		} else JHTML::script('mapa.js', 'libraries/imagine/js/', false);
		JFactory::getDocument()->addCustomTag('<script type="text/javascript" src="https://maps.google.com/maps/api/js?v=3&sensor=false"></script>');
	}
	
	// Retorna o texto correspondente à valores menores que 1000
	static function extenso($numero) {
		$c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
		$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
		$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
		$u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
		$inteiro = ''.(int)$numero;
		if(($len = strlen($inteiro)) > 3) return $numero;
		else if($len == 2) $inteiro = '0'.$inteiro;
		else if($len == 1) $inteiro = '00'.$inteiro;
		else if($len == 0) return $numero;
		if($inteiro == '000') return ($numero == '0')? 'zero' : $numero;
		$centena = (($inteiro > 100) && ($inteiro < 200)) ? "cento" : $c[$inteiro[0]];
		$dezena = ($inteiro[1] < 2) ? "" : $d[$inteiro[1]];
		$unidade = ($inteiro > 0) ? (($inteiro[1] == 1) ? $d10[$inteiro[2]] : $u[$inteiro[2]]) : "";
		return $centena.(($centena && ($dezena || $unidade)) ? " e " : "").$dezena.(($dezena && $unidade) ? " e " : "").$unidade;
	}
	
	static function getCryptedPassword($plaintext, $salt = '', $encryption = 'sha512', $show_encrypt = false){
			// Get the salt to use.
			$salt = self::getSalt($encryption, $salt, $plaintext);
	 
			// Encrypt the password.
			switch ($encryption){
				case 'sha512' :
				default :
						$encrypted = ($salt) ? hash('sha512', $plaintext.$salt) : hash('sha512', $plaintext);
						return ($show_encrypt) ? '{sha512}'.$encrypted : $encrypted;
			}
	}
	
	static function getSalt($encryption = 'sha512', $seed = '', $plaintext = ''){
			// Encrypt the password.
			switch ($encryption){
					default :
							$salt = '';
							if ($seed) {
									$salt = $seed;
							}
							return $salt;
							break;
			}
	}
	
	static function porcentagem($total = 0, $parte = 0, $casas = 2) {
		if(!$total) return number_format(0, $casas) . '%';
		if(!$parte) {
			$res = '';
			for($i = 0; $i < $casas; $i++) $res .= '0';
			return '0.'. $res .'%';
		}
		return number_format(($parte * 100) / $total, $casas) . '%';
	}
	
	static function m_data($time, $string) {
		$time = strtotime($time);
		$find_array = array('%d','%m','%Y','%H','%i','%s');
		$replacement_array = array(date('d', $time),date('m', $time),date('Y', $time),date('H', $time),date('i', $time),date('s', $time));
		return str_replace($find_array, $replacement_array, $string);
	}

	function getYoutubeEmbedUrl($url){
		$shortUrlRegex = '/youtu.be\/([a-zA-Z0-9_-]+)\??/i';
        $longUrlRegex = '/youtube.com\/((?:embed)|(?:watch)|(?:shorts))((?:\?v\=)|(?:\/))([a-zA-Z0-9_-]+)/i';               

        $youtube_id = "";

        if (preg_match($longUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        } elseif (preg_match($shortUrlRegex, $url, $matches)) {
            $youtube_id = $matches[count($matches) - 1];
        } 
        
    	return 'https://www.youtube.com/embed/' . $youtube_id;
	}

	function replace_tags($value, $dados){
		if(!$value) return;

		if (preg_match_all("/\[(.*?)\]/", $value, $matches)) {
			do{
				$valueAntes = $value;

				foreach ($matches[1] as $match) {
					if(!isset($dados[$match])) continue;
					$value = str_replace('['.$match.']', $dados[$match], $value);
				}

			}while(preg_match_all("/\[(.*?)\]/", $value, $matches) && $valueAntes != $value);
			
			return $value;
		} else {
			return $value;
		}
    }
	
	/**
	* Create a web friendly URL slug from a string.
	*
	* Although supported, transliteration is discouraged because
	* 1) most web browsers support UTF-8 characters in URLs
	* 2) transliteration causes a loss of information
	*
	* @author Sean Murphy <sean@iamseanmurphy.com>
	* @copyright Copyright 2012 Sean Murphy. All rights reserved.
	* @license http://creativecommons.org/publicdomain/zero/1.0/
	*
	* @param string $str
	* @param array $options
	* @return string
	*/
	static function url_slug($str, $options = array()) {
		// Make sure string is in UTF-8 and strip invalid UTF-8 characters
		$str = self::ConverterNome($str);
		$str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());
		$defaults = array(
			'delimiter' => '-',
			'underscore' => false,
			'limit' => null,
			'lowercase' => true,
			'replacements' => array(),
			'transliterate' => true,
		);
		// Merge options
		$options = array_merge($defaults, $options);
		$char_map = array(
			// Latin
			'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
			'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
			'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
			'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
			'ß' => 'ss',
			'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
			'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
			'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
			'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
			'ÿ' => 'y',
			 
			// Latin symbols
			'©' => '(c)',
			 
			// Greek
			'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
			'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
			'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
			'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
			'Ϋ' => 'Y',
			'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
			'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
			'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
			'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
			'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
			 
			// Turkish
			'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
			'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
			 
			// Russian
			'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
			'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
			'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
			'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
			'Я' => 'Ya',
			'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
			'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
			'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
			'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
			'я' => 'ya',
			 
			// Ukrainian
			'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
			'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
			 
			// Czech
			'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
			'Ž' => 'Z',
			'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
			'ž' => 'z',
			 
			// Polish
			'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
			'Ż' => 'Z',
			'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
			'ż' => 'z',
			 
			// Latvian
			'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
			'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
			'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
			'š' => 's', 'ū' => 'u', 'ž' => 'z'
		);
		// Make custom replacements
		$str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);
		// Transliterate characters to ASCII
		if ($options['transliterate']) {
			$str = str_replace(array_keys($char_map), $char_map, $str);
		}
		// Replace non-alphanumeric characters with our delimiter
		$str = preg_replace('/'. ($options['underscore'] ? '(?!_)' : '') .'[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);
		// Remove duplicate delimiters
		$str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);
		// Truncate slug to max. characters
		$str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');
		// Remove delimiter from ends
		$str = trim($str, $options['delimiter']);
		return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
	}

	static function ConverterNome($nome_arquivo){
		$nome_arquivo = str_replace('_','-',$nome_arquivo);
		$nome_arquivo = str_replace(' ','-',$nome_arquivo);
		$nome_arquivo = preg_replace('/[^A-Za-z0-9\-]/', '', $nome_arquivo);
		$bk = null;
		while($nome_arquivo != $bk):
			$bk = $nome_arquivo;
			$nome_arquivo = str_replace('--','-',$nome_arquivo);
		endwhile;
		return $nome_arquivo;
	}
	
	// Busca o conteúdo de ua dada URL
	function post_to_url($url = NULL, $data = array(), $optional_headers = NULL) {
		if(!$url) return FALSE;
		if(function_exists('curl_init') && 0) {
			// Conecta ao site
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_URL, $url);
			curl_setopt($curl, CURLOPT_POST, TRUE);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl, CURLOPT_HEADER, FALSE);
			curl_setopt($curl, CURLOPT_TIMEOUT, 20);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			$response = trim(curl_exec($curl));
			curl_close($curl);
			
			return ($response == '1')? TRUE : FALSE;
		} else {
			// Array com os dados do post
			$params = array('http' => array(
				'method' => 'POST',
				'content' => http_build_query($data, "", "&")
			));
			
			// Paraetro opicionais
			if($optional_headers !== NULL) $params['http']['header'] = $optional_headers;
			
			// Inicia a conexão
			$ctx = stream_context_create($params);
			$fp = @fopen($url, 'rb', FALSE, $ctx);
			if(!$fp) throw new Exception("Problem with $url, $php_errormsg");
			
			// Obtem e verfica a resposa
			$response = @stream_get_contents($fp);
			//if($response === FALSE) throw new Exception("Problem reading data from $url, $php_errormsg");
			
			// retorna a resposta
			return $response;
			return ($response == '1')? TRUE : FALSE;
		}
	}
	
	public function base64tourltobase64($string, $revert = FALSE) {
		return $revert ? str_replace(array('-', '_'), array('=', '/'), $string) : str_replace(array('=', '/'), array('-', '_'), $string);
	}
	
	public function get_url($url, $query = array()) {
		$r = new HttpRequest($url, HttpRequest::METH_GET);
		$r->addQueryData($query);
		try {
			return $r->send()->getBody();
		} catch (HttpException $ex) {
			return FALSE;
		}
		
		return FALSE;
	}
	
	static function erros() {
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
	}
}
?>