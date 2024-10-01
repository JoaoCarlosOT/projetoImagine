<?php
// Impede o acesso direto a este arquivo
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Classe de processamento do envio de imagens
class Imgno_imagem3 {
	// Envia imagens para o servidor, seguindo padrões específicos
	public function enviar_imagens($tmn_pdr = FALSE, $atual, $campo, $dir, $larg = 0, $alt = 0, $larg_mini = 0, $alt_mini = 0, $metodo = 0, $transparente = 0, $tamanho_fixo = 0, &$nomes = array(), $imagens_antigas = array(), $multi = FALSE){
		if(empty($_FILES[$campo]['name'])) return FALSE;
		$img_erro = FALSE;
		$imgs = $_FILES[$campo];
		$nomes = $imgs['name'];
		$types = array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png');
		
		// Caminhos do arquivos
		$upload = realpath('arquivos') .'/imagens/upload';
		$temp_dir = $upload .'/temp';
		$mini_dir = $dir . 'miniaturas/';
		
		foreach($nomes as $k => $nome) {
			$img_erro = $img_erro || $imgs['error'][$k];
			if(!in_array($imgs['type'][$k], $types)) {
				return 2;// ($erro = 'Para maior segurança e velocidade de acesso do seu site, as imagens enviadas devem ser do tipo jpg, gif ou png!#erro');
			}
			if($imagens_antigas) $nomes[$k] = $imagens_antigas[0];
			else $nomes[$k] = self::_parseName($nome, $dir, $mini_dir);
		}
		if(!$img_erro) {
			
			if($imagens_antigas) {
				foreach($imagens_antigas as $i) {
					if(file_exists($dir .'/'. $i)) unlink($dir .'/'. $i);
					if(file_exists($mini_dir . $i)) unlink($mini_dir . $i);
				}
			}
			
			// Carrega as bibliotecas necessárias
			$this->controller->load->library('upload');
			$this->controller->load->library('Imgno_multiupload', '', 'm_upload');
			
			// Configura a função de upload
			$config = array(
				'upload_path' => $dir,
				'allowed_types' => 'gif|jpg|png|jpeg',
				'max_size' => '8096',
				'max_width' => '8096',
				'max_height' => '8048',
				'file_name' => $nomes,
				'max_filename' => '100',
				'remove_spaces' => 'TRUE',
			);
			
			$this->controller->m_upload->initialize($config);
			
			if($this->controller->m_upload->do_multi_upload($campo)) {
				
				foreach($nomes as $k => $nome) {

					// Verifica o limite das dimensões da imagem
					$size = getimagesize($imgs['tmp_name'][$k]);
					$width = (int)$size[0];
					$height = (int)$size[1];
					//if(($width > 2048) || ($height > 1536)) return 0;
					if($tmn_pdr){
						if($width > 900){
							if($width == $height){
								$larg = 900;
								$alt = 900;
							}else{
								$larg = 900;
								$alt = ($height*$larg)/$width;
							}
						}
					}else{
						if($width > 360 || $height > 360){
							if($width == $height){
								$larg = 360;
								$alt = 360;
							}elseif($width < $height){
								$alt = 360;
								$larg = ($width*$alt)/$height;
							}else{
								$larg = 360;
								$alt = ($height*$larg)/$width;
							}
						}						
					}
					
					// Geração da miniatura da imagem
					if( $larg_mini && $alt_mini ) {
						$nome_mini = $nomes[$k];
						if(!$this->miniatura($nome_mini, $dir, $mini_dir, $larg_mini, $alt_mini, $metodo, $transparente, $tamanho_fixo)){
							if(file_exists($mini_dir . $nome_mini)) unlink($mini_dir . $nome_mini);
							return 0;
						}
					}
				
					if( $larg && $alt ) {
					
						// Redimensionamento da imagem
						if(!$this->miniatura($nomes[$k], $dir, $dir, $larg, $alt, $metodo, $transparente, $tamanho_fixo)) {
							if(file_exists($dir . $nomes[$k])) unlink($dir . $nomes[$k]);
							return FALSE;
						}
						
						/*
						if(!$this->miniatura($nome, $dir, $mini_dir, $larg_mini, $alt_mini, $metodo, $transparente, $tamanho_fixo)){
							if(file_exists($dir . $nome)) unlink($dir . $nome);
							$erros[$k]['imagem'] = 'Ocorreu um erro na imagem "'. $nome .'"';
						}
						*/
					} else {
						// Verifica se as dimensões da imagem excedem o limite permitido
						$max_width = 8000;
						$max_height = 8000;
						if(($width > $max_width) || ($height > $max_height)){
							$div_w = $width / $max_width;
							$div_h = $height / $max_height;
							if($div_w >= $div_h){
								$toWidth = $max_width;
								$toHeight = $height * $max_width / $width;
							} else {
								$toHeight = $max_height;
								$toWidth = $width * $max_height / $height;
							}
							$this->miniatura($nome, $dir, $dir, $toWidth, $toHeight, 2, $transparente, $tamanho_fixo);
						} else if($transparente) {
							$this->miniatura($nome, $dir, $dir, $width, $height, 0, $transparente, $tamanho_fixo);
						}
					}
				}
			} else {
				return 0;
			}
			
			return 1;
		} else return 0;
	}
	
	// Gera miniaturas de imagens
	private function miniatura(&$fname, $path, $pathToThumbs, $dst_w, $dst_h, $metodo, $transparente = false, $tamanho_fixo = false){
		// Descobre a extensão
		$info = pathinfo($path . $fname);
		$extensao = strtolower($info['extension']);
		
		// Dimensões da imagem
		$size = getimagesize($path . $fname);
		$src_w = $size[0];
		$src_h = $size[1];
		//if(($src_w > 2048) || ($src_h > 1536)) return false; // "Dimensões acima do limite: {$src_w} x {$src_h}";
		
		// Carrega a imagem em uma variável
		switch($extensao){
			case 'jpeg':
			case 'jpg': { $img = imagecreatefromjpeg($path . $fname); break; }
			case 'png': { $img = imagecreatefrompng($path . $fname); break; }
			case 'gif': { $img = imagecreatefromgif($path . $fname); break; }
			default: return false; // 'Formato de imagem inváldo: '. $extensao;
		}
		
		// Verifica se há a necessidade de redimensionar a imagem
		if(($path == $pathToThumbs) && ($dst_w == $src_w) && ($dst_h == $src_h) && !$transparente) return true;
		
		
		// Cria uma nova imagem( temporária ) e preenche com a cor branca ou com transparência
		$tmp_img = imagecreatetruecolor($dst_w, $dst_h);
		if($transparente && $extensao == 'png') {
			imagealphablending($tmp_img, false);
			imagefilledrectangle($tmp_img, 0, 0, $dst_w, $dst_h, imagecolorallocatealpha($tmp_img, 255, 255, 255, 127));
			imagealphablending($tmp_img, true);
		} else {
			imagefill($tmp_img, 0, 0, imagecolorallocate($tmp_img, 255, 255, 255));
		}
		
		// Copia a imagem original sobre a nova imagem, seguindo as dimensões adequadas
		$dim = $this->calcular_dimensoes($src_w, $src_h, $dst_w, $dst_h, $metodo);
		imagecopyresampled($tmp_img, $img, $dim[0], $dim[1], $dim[2], $dim[3], $dim[4], $dim[5], $dim[6], $dim[7]);
		
		// Caso seja solicitada ima imagem com transparência, a extensão será sempre png
		if($transparente && $extensao == 'png') {
			imagealphablending($tmp_img, false);
			imagesavealpha($tmp_img, true);
			$fname = preg_replace('/jpe?g$/', 'png', $fname);
			imagepng($tmp_img, "{$pathToThumbs}{$fname}");
			//imagepng($tmp_img);
		} else {
			// Caso contrário, será jpg ou jpeg
			imagejpeg($tmp_img, "{$pathToThumbs}{$fname}");
		}
		
		// Destrói a imagem temporária
		imagedestroy($tmp_img);
		
		// Confirma a operação
		return true;
	}
	
	// Calcula as dimensões que devem ser utilizadas na criação da imagem
	private function calcular_dimensoes($src_w, $src_h, $dst_w, $dst_h, $method) {
		// Razões entre as dimensões
		$div_w = $src_w / $dst_w;
		$div_h = $src_h / $dst_h;
		
		if($method == 0){
			// Centraliza a imagem original, sem redimensionar
			/*if(($src_w <= $dst_w) && ($src_h <= $dst_h)) {
				$dst_x = ($dst_w - $src_w) / 2;
				$dst_y = ($dst_h - $src_h) / 2;
			}
			// Centraliza a imagem original após ampliar o máximo possível
			else */
			if($div_w > $div_h) {
				$resize_w = $dst_w; // $src_w * $dst_w / $src_w;
				$resize_h =			   $src_h * $dst_w / $src_w;
				$dst_x = 0;
				$dst_y = (int)(($dst_h - $resize_h) / 2);
			} else {
				$resize_h = $dst_h; // $src_h * $dst_h / $src_h;
				$resize_w =			   $src_w * $dst_h / $src_h;
				$dst_y = 0;
				$dst_x = (int)(($dst_w - $resize_w) / 2);
			}
			
			// Copia e redimensiona a imagem antiga no lugar da nova
			return array($dst_x, $dst_y, 0, 0, $dst_w - (2 * $dst_x), $dst_h - (2 * $dst_y), $src_w, $src_h);
		} elseif($method == 1) { // Corta
			if($div_w > $div_h){
				$temp_h = $src_h;
				$temp_w = $src_h * $dst_w / $dst_h;
				$src_y = 0;
				$src_x = (int)(($src_w - $temp_w) / 2);
			} else {
				$temp_w = $src_w;
				$temp_h = $src_w * $dst_h / $dst_w;
				$src_x = 0;
				$src_y = (int)(($src_h - $temp_h) / 2);
			}
			
			// Copia e redimensiona a imagem antiga no lugar da nova
			return array(0, 0, $src_x, $src_y, $dst_w, $dst_h, $src_w - (2 * $src_x), $src_h - (2 * $src_y));
		}
		// Estica
		else return array(0, 0, 0, 0, $dst_w, $dst_h, $src_w, $src_h);
	}
	
	// Converte o nome de uma imagem
	private function _parseName($nomeImagem, $dir, $mini_dir) {
		$parts = @explode( '.', $nomeImagem );
		$extensao = strtolower( $parts[ count( $parts ) - 1 ] );
		unset( $parts[ count( $parts ) - 1 ] );
		$file = rand( 0, 20 ) . time() . rand( 0, 20 ) . $this->controller->util->getAlias(@implode( '.', $parts )) .'.'. $extensao;
		while(file_exists($dir . $file) || file_exists($mini_dir . $file)) $file = rand( 0, 20 ) . time() . rand( 0, 20 ) . $this->controller->util->getAlias(@implode( '.', $parts )) .'.'. $extensao;
		return $file;
	}
	
	public function __construct() {
		$this->controller =& get_instance();
	}
}
?>