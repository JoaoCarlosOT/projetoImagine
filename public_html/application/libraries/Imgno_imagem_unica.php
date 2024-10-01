<?php
// Impede o acesso direto a este arquivo
// if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Classe de processamento do envio de imagens
class Imgno_imagem_unica {
	// Envia imagens para o servidor, seguindo padrões específicos
	public function enviar_imagens($tmn_pdr = FALSE, $imgs, $dir, $larg = 0, $alt = 0, $larg_mini = 0, $alt_mini = 0, $metodo = 1, $transparente = 0, $tamanho_fixo = 0, $imagens_antigas = array()){
		if(empty($imgs['name'])) return FALSE;
		
		$img_erro = FALSE;
		$novos_nomes = array();
		$nomes = $imgs['name'];
		$types = array('image/jpeg', 'image/pjpeg', 'image/gif', 'image/png');
		
		// Caminhos do arquivos
		$upload = realpath('arquivos') .'/imagens/upload';
		$temp_dir = $upload .'/temp';
		$mini_dir = $dir . 'miniaturas/';
		if(!file_exists($mini_dir)){
			mkdir($dir.'/miniaturas', 0755);
		}
		
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
			// $this->controller->load->library('upload');
			// $this->controller->load->library('Imgno_multiupload', '', 'm_upload');
			
			// Configura a função de upload
			// $config = array(
				// 'upload_path' => $dir,
				// 'allowed_types' => 'gif|jpg|png|jpeg',
				// 'max_size' => '8096',
				// 'max_width' => '8096',
				// 'max_height' => '8048',
				// 'file_name' => $nomes,
				// 'max_filename' => '100',
				// 'remove_spaces' => 'TRUE',
			// );
			
			// $this->controller->m_upload->initialize($config);
			
			// if($this->controller->m_upload->do_multi_upload($campo)) {
				
				foreach($nomes as $k => $nome) {
				
					// Verifica o limite das dimensões da imagem
					$size = getimagesize($imgs['tmp_name'][$k]);
					$width = (int)$size[0];
					$height = (int)$size[1];
					$max_lm = 800;
					//if(($width > 2048) || ($height > 1536)) return 0;
					if($tmn_pdr){
						$max_width = 1920;
						$max_height = 800;
						if($larg && $alt){
							$max_width = $larg;
							$max_height = $alt;
						}
						if(($width > $max_width) || ($height > $max_height)){
							$div_w = $width / $max_width;
							$div_h = $height / $max_height;
							if($div_w >= $div_h){
								$larg = $max_width;
								$alt = $height * $max_width / $width;
							} else {
								$alt = $max_height;
								$larg = $width * $max_height / $height;
							}
						}else{
							$larg = (int)$size[0];
							$alt = (int)$size[1];
							
						}
					}
					
					move_uploaded_file($imgs['tmp_name'][$k], $dir.$nome);
					
					// Geração da miniatura da imagem
					if( $larg_mini && $alt_mini ) {
						$nome_mini = $nomes[$k];
						if(!$this->miniatura($nome_mini, $dir, $mini_dir, $larg_mini, $alt_mini, $metodo, $transparente, $tamanho_fixo)){
							if(file_exists($mini_dir . $nome_mini)) unlink($mini_dir . $nome_mini);
							unset($nomes[$k]);
						}
					}
				
					if( $larg && $alt ) {
					
						// Redimensionamento da imagem
						if(!$this->miniatura($nomes[$k], $dir, $dir, $larg, $alt, $metodo, $transparente, $tamanho_fixo)) {
							if(file_exists($dir . $nomes[$k])) unlink($dir . $nomes[$k]);
							unset($nomes[$k]);
						}
						
						/*
						if(!$this->miniatura($nome, $dir, $mini_dir, $larg_mini, $alt_mini, $metodo, $transparente, $tamanho_fixo)){
							if(file_exists($dir . $nome)) unlink($dir . $nome);
							$erros[$k]['imagem'] = 'Ocorreu um erro na imagem "'. $nome .'"';
						}
						*/
					} else {
						// Verifica se as dimensões da imagem excedem o limite permitido
						$max_width = 800;
						$max_height = 800;
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
			// } else {
				// return 0;
			// }
			
			return $nomes;
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
			imagejpeg($tmp_img, "{$pathToThumbs}{$fname}", 60);
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
	function _parseName($nomeImagem,$dir, $mini_dir){
		$ext = '.'.strtolower($parts[count($parts = @explode('.', $nomeImagem)) - 1]);
		$arquivo = $this->ConverterNome($parts[0]).$ext;
		$nome_arquivo = $this->ConverterNome($parts[0]);
		$k = 1;
		while(file_exists($dir.$arquivo)){
			$arquivo = $nome_arquivo.'-'.$k.$ext;
			$k++;
		}
		return $arquivo;
	}

	function ConverterNome($nome_arquivo){
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
	
	public function Otimizar($caminho, $width = 0, $height = 0, $transparente = false, $cortar = true, $qualidade = 80, $tipo_corte = 'cc'){
		$root = $_SERVER['DOCUMENT_ROOT'];
		$ori_caminho = $caminho;
		if(!$caminho) return ($caminho);
		$c = '';
		$f = '';
		$cc = strlen($caminho);
		$nkey = 0;
		for($i = $cc; $i >= 0; $i--){
			if($caminho[$i] == '/'){
				$nkey = $i;
				break;
			}
		}
			
		if(!$nkey) return ($caminho.$foto);
		$nkey++;
		for($i = 0; $i < $nkey; $i++){ 
			$c .= $caminho[$i];
		}
		for($i = $nkey; $i < $cc; $i++){ 
			$f .= $caminho[$i];
		}
		$caminho = $c;
		$foto = $f;

		if($caminho[0] != '/') $caminho = '/'.$caminho;
		if(!file_exists($caminho . $foto)) $foto = ''.urldecode($foto);
		if(!file_exists($root . $caminho . $foto)) return ($ori_caminho);
		$info = pathinfo($caminho . $foto);
		$ext = strtolower($info['extension']);
		$jr_caminho = $root . $caminho;
		$size = getimagesize($jr_caminho . $foto);
		if(!$info) return ($caminho.$foto);
		$t = 'n';
		$c = 'n'; 
		
		$new_caminho = '/cache/imagens'.$caminho;
		$new_jr_caminho = $root . $new_caminho;
		
		$o_w = $size[0];
		$o_h = $size[1];
		$metodo = 0;
		if($cortar && $width && $height){ 
			// $c = 'y';
			$c = $tipo_corte;
			$metodo = 1;
		}
		if(!$width && $height) $width = ($height*$o_w)/$o_h;
		if($width && !$height) $height = ($width*$o_h)/$o_w;
		if(!$width && !$height){
			$width = $o_w;
			$height = $o_h;
		}
		if($o_h < $height && $o_w < $width){
			if(number_format($width/$o_w,3) == number_format($height/$o_h,3)){
				$width = $o_w;
				$height = $o_h;
			}
		}
		
		$formato = 'jpg';
		if($transparente) $formato = 'png';
		if($transparente) $t = 'y';
		$nome = $info['filename'].'_'.$width.$t.$qualidade.$c.$height;
		$nome_ext = $nome.'.'.$formato;

		if(image_type_to_mime_type($size[2]) == 'image/gif') return ($caminho.$foto);

		if(file_exists($new_jr_caminho .$nome_ext)){
			if(filemtime($root.$caminho.$foto) <= filemtime($root.$new_caminho.$nome_ext)) return ($new_caminho.$nome_ext);
		}
		if(!file_exists($new_jr_caminho)){
			$n = explode('/',$new_jr_caminho);
			$np = '';
			foreach($n as $d){
				$np .= '/'.$d;
				if(!file_exists($np)){
					mkdir($np, 0755);
				}
			}
		}
		
		switch(image_type_to_mime_type($size[2])){
			case 'image/jpeg':
			case 'image/jpg': { $img = imagecreatefromjpeg( $jr_caminho . $foto); break; }
			case 'image/png': { $img = imagecreatefrompng( $jr_caminho . $foto); break; }
			case 'image/gif': { $img = imagecreatefromgif($jr_caminho . $foto); break; }
			default: return false; // 'Formato de imagem inváldo: '. $extensao;
		}
		
		// Verifica se há a necessidade de redimensionar a imagem
		if(($width == $o_w) && ($height == $o_h) && !$transparente && $qualidade == 100) return ($caminho.$foto);
		
		// Cria uma nova imagem( temporária ) e preenche com a cor branca ou com transparência
		
		$tmp_img = imagecreatetruecolor($width, $height);
		if($transparente){
			imagealphablending($tmp_img, false);
			imagefilledrectangle($tmp_img, 0, 0, $width, $height, imagecolorallocatealpha($tmp_img, 255, 255, 255, 127));
			imagealphablending($tmp_img, true);
		} else {
			imagefill($tmp_img, 0, 0, imagecolorallocate($tmp_img, 255, 255, 255));
		}
		
		// Copia a imagem original sobre a nova imagem, seguindo as dimensões adequadas
		$dim = $this->calcular_dimensoes($o_w, $o_h, $width, $height, $metodo);

		if($tipo_corte == 'cr'){
			$dim[2] = 0;
		}elseif($tipo_corte == 'cl'){
			$dim[2] = 2*$dim[2];
		}		

		imagecopyresampled($tmp_img, $img, $dim[0], $dim[1], $dim[2], $dim[3], $dim[4], $dim[5], $dim[6], $dim[7]);
		
		// Caso seja solicitada ima imagem com transparência, a extensão será sempre png
		if($transparente){
			imagealphablending($tmp_img, false);
			imagesavealpha($tmp_img, true);
			if($qualidade == 100) $qualidade = 0;
			else $qualidade = (100 - $qualidade)/10;
			imagepng($tmp_img, "{$new_jr_caminho}{$nome_ext}",$qualidade);
		} else {
			// Caso contrário, será jpg ou jpeg
			imagejpeg($tmp_img, "{$new_jr_caminho}{$nome_ext}",$qualidade);
		}
		
		// Destrói a imagem temporária
		imagedestroy($tmp_img);
		
		return ($new_caminho.$nome_ext);
	}
	
	public function __construct() {
		// $this->controller =& get_instance();
	}
}
?>