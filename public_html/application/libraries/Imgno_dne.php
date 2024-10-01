<?php
// Impede o acesso direto a este arquivo
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Classe de acesso ao DNE - Diretório Nacional de Endereços
class Imaginedne {
	// Busca as UFs
	static function buscar_ufs($return = false, $teste = false){
		$c_dne =& get_instance();
		$response = null;
		if(!($response = $c_dne->session->userdata('dne.buscar_ufs'))){
			$response = $c_dne->imaginehttp->conteudo('http://www.dne.imagineseusite.com.br/dne2.php?task=buscar_ufs');
			$c_dne->session->set_userdata('dne.buscar_ufs', $response);
		}
		if($teste) return true;
		if($return) return json_decode($response);
		echo $response; exit;
	}
	
	// Busca cidades de um dado estado
	static function buscar_cidades($return = false, $uf = null){
		$c_dne =& get_instance();
		$response = null;
		if($uf){
			if(!($response = $c_dne->session->userdata('dne.buscar_cidades'. $uf))){
				$response = $c_dne->imaginehttp->conteudo('http://www.dne.imagineseusite.com.br/dne2.php?task=buscar_cidades&uf='. $uf);
				$c_dne->session->set_userdata('dne.buscar_cidades'. $uf, $response);
			}
		} else $response = '[]';
		if($return) return json_decode($response);
		echo $response; exit;
	}
	
	// Busca as coordenadas de um dado endereço
	static function buscar_mapa($dados, $completo = 1){
		// Validação
		$c_dne =& get_instance();
		$c_dne->load->library('imaginevalidacao');
		if(!isset($dados['uf']) || !($dados['uf'] = trim($dados['uf'])))
			if(!isset($dados['f_uf']) || !($dados['uf'] = trim($dados['f_uf']))) $erros[] = 'Informe a UF (estado)';
		if(!isset($dados['cidade']) || !($dados['cidade'] = trim($dados['cidade'])))
			if(!isset($dados['f_cidade']) || !($dados['cidade'] = trim($dados['f_cidade']))) $erros[] = 'Informe a cidade';
		if(!isset($dados['bairro']) || !($dados['bairro'] = trim($dados['bairro']))) $erros[] = 'Informe o bairro';
		if($completo){
			if(!isset($dados['cep']) || !$c_dne->imaginevalidacao->validarCEP($dados['cep'])) $erros[] = 'Informe o CEP corretamente';
			if(!isset($dados['endereco']) || !($dados['endereco'] = trim($dados['endereco']))) $erros[] = 'Informe o endereço';
			if(!isset($dados['numero']) || !($dados['numero'] = trim($dados['numero']))) $erros[] = 'Informe o número';
		}
		if(isset($erros)) return 'var resultadoMapa = {"erro":true,"mensagem":"'. implode("\n", $erros) .'"}';
		
		// Consulta de coordenadas geográficas no Google
		if($completo) $address = str_replace(' ', '+', "{$dados['endereco']}, {$dados['numero']} - {$dados['bairro']}, {$dados['cidade']} - {$dados['uf']}, {$dados['cep']}, Brasil");
		else $address = str_replace(' ', '+', "{$dados['bairro']}, {$dados['cidade']}, {$dados['uf']}, Brasil");
		$json = @json_decode($c_dne->imaginehttp->conteudo('http://maps.google.com/maps/api/geocode/json?sensor=false&address='. $address));
		if($json->status == 'OK'){
			$g_lat = $json->results[0]->geometry->location->lat;
			$g_lng = $json->results[0]->geometry->location->lng;
			return 'var resultadoMapa = {"erro":false,"lat":"'. $g_lat .'","lng":"'. $g_lng .'"}';
		} else {
			return 'var resultadoMapa = {"erro":true,"mensagem":"Não foi possível gerar o mapa do endereço buscado. Altere o endereço e tente novamente."}';
		}
	}
	
	// Busca o endereco de acordo com o CEP
	static function buscar_endereco($cep, $dump = false){
		$c_dne =& get_instance();
		$config = array('hostname' => 'localhost',
						'username' => 'imagines_dne',
						'password' => 'W}krl6g?Gop_',
						'database' => 'imagines_dne_2012',
						'dbdriver' => 'mysql',
						'dbprefix' => 'imgn_',
						'pconnect' => FALSE,
						'db_debug' => TRUE,
						'cache_on' => TRUE,
						'cachedir' => '',
						'char_set' => 'utf8',
						'dbcollat' => 'utf8_general_ci',
						);
		$db =& $c_dne->load->database($config, TRUE);
		if(!($db)){
			$dbe = 1;
			if($dump){
				if(@is_array($row = explode(';;;', self::_buscarEndereco($cep, true))) && (count($row) == 5)){
					$obj->uf = $row[0];
					$obj->cidade = $row[1];
					$obj->bairro = $row[2];
					$obj->endereco = $c_dne->imagineutil->extenso($row[3]);
					if(strpos(utf8_strtolower($obj->endereco), utf8_strtolower($row[4])) !== 0) $obj->endereco = $row[4] .' '. $obj->endereco;
					return $obj;
				} else return '{"erro":true}';
			} else return self::_buscarEndereco($cep);
		}
		$cep = preg_replace(array('/\D/', '@(\d{5})(\d{3})@'), array('', '$1-$2'), $cep);
		$select = 'e.logradouro, e.endereco, b.id_bairro, b.bairro, c.id_cidade, c.cidade, uf.uf';
		$from = 'tend_endereco e JOIN tend_bairro b ON b.id_bairro = e.id_bairro JOIN tend_cidade c ON c.id_cidade = e.id_cidade JOIN tend_estado uf ON uf.id_estado = c.id_estado';
		$where = 'e.cep = ?';
		$sql = 'SELECT '. $select .' FROM '. $from .' WHERE '. $where;
		$variaveis = array($cep);
		$query = $db->query($sql, $variaveis);
		if($row = $query->row_array(0)){
			if($dump){
				$obj->uf = $row['uf'];
				$obj->cidade = $row['cidade'];
				$obj->bairro = $row['bairro'];
				$obj->endereco = $c_dne->imagineutil->extenso($row['endereco']);
				if(strpos(utf8_strtolower($obj->endereco), utf8_strtolower($row['logradouro'])) !== 0) $obj->endereco = $row['logradouro'] .' '. $obj->endereco;
				if($dump) return $obj;
			}
			$array['uf'] = $row['uf'];
			$array['cidade']['id'] = $row['id_cidade'];
			$array['cidade']['nome'] = $row['cidade'];
			$array['bairro']['id'] = $row['id_bairro'];
			$array['bairro']['nome'] = $row['bairro'];
			$array['tipo_logradouro'] = $row['logradouro'];
			$array['logradouro'] = $row['endereco'];
			$array['erro'] = false;
			return json_encode($array);
		} else return '{"erro":true}';
	}
	
	// Busca o endereco de acordo com o CEP
	static function _buscar_endereco($cep, $dump = 0){
		$c_dne =& get_instance();
		return $c_dne->imaginehttp->conteudo('http://www.dne2.imagineseusite.com.br/dne.php?task=buscar_endereco&cep='. $cep .'&dump='. (int)$dump);
	}
	
	// Construtor
	public function __construct(){
		$c_dne =& get_instance();
		// Carrega classe http
		$c_dne->load->library('imaginehttp');
		$c_dne->load->library('session');
	}
}
?>