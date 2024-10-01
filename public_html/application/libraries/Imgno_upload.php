<?php
// Impede o acesso direto a este arquivo
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Classe de processamento do envio de imagens
class Imgno_upload {
	// Envia imagens para o servidor, seguindo padrões específicos
	public function enviar_arquivos($atual, $campo, $dir, &$nomes = array(), $arquivos_antigos = array()){
		if(empty($_FILES[$campo]['name'])) return FALSE;
		$img_erro = FALSE;
		$imgs = $_FILES[$campo];
		$nomes = $imgs['name'];
		$types = array(
				'image/jpeg', 
				'image/pjpeg', 
				'image/gif', 
				'image/png', 
				'application/x-rar-compressed',
				'application/zip',
				'application/vnd.ms-excel',
				'application/xml',
				'application/pdf',
				'application/msword',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'application/vnd.oasis.opendocument.text',
				'application/vnd.oasis.opendocument.spreadsheet',
				'application/vnd.oasis.opendocument.presentation',
				'application/excel',
				'application/powerpoint',
				'application/vnd.ms-powerpoint',
				'application/msexcel',
				'application/excel',
				'video/mp4',
				);
		
		// Caminhos do arquivos
		$upload = realpath('arquivos') .'/imagens/upload';
		$temp_dir = $upload .'/temp';
		$mini_dir = $dir . 'miniaturas/';
		
		if($atual && file_exists($dir . $atual)) unlink($dir . $atual);
		
		foreach($nomes as $k => $nome) {
			$img_erro = $img_erro || $imgs['error'][$k];
			if($imgs['error'][$k]){
				$nomes = '';

        switch ($imgs['error'][$k]) {
          case UPLOAD_ERR_INI_SIZE:
            $nomes = "O arquivo enviado excede o limite definido ".ini_get('upload_max_filesize');
            break;
          case UPLOAD_ERR_FORM_SIZE:
            $nomes = "O arquivo excede o limite definido em ".ini_get('max_file_size');
            break;
          case UPLOAD_ERR_PARTIAL:
            $nomes = "O upload do arquivo foi feito parcialmente.";
            break;
          case UPLOAD_ERR_NO_FILE:
            $nomes = "Nenhum arquivo foi enviado.";
            break;
          case UPLOAD_ERR_NO_TMP_DIR:
            $nomes = "Pasta temporária ausênte";
            break;
          case UPLOAD_ERR_CANT_WRITE:
            $nomes = "Falha em escrever o arquivo em disco";
            break;
          case UPLOAD_ERR_EXTENSION:
            $nomes = "Uma extensão do PHP interrompeu o upload do arquivo";
            break;

          default:
            $nomes = "Erro Desconhecido";
            break;
        } 

				return 0;
			}
			if(!in_array($imgs['type'][$k], $types)) {
				$nomes = 'Para maior segurança os arquivos enviados devem ser do tipo jpg, gif, png, word, pdf, ppt, exel!';
				return 0;
				return 2;// ($erro = 'Para maior segurança e velocidade de acesso do seu site, as imagens enviadas devem ser do tipo jpg, gif ou png!#erro');
			}
			if(!empty($arquivos_antigos[$k])) $nomes[$k] = $arquivos_antigos[$k];
			else $nomes[$k] = self::_parseName($nome, $dir, $mini_dir);
		}
		if(!$img_erro) {
			
			if(isset($imagens_antigas)) {
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
				'allowed_types' => 'gif|jpg|png|jpeg|txt|doc|ppt|pptx|odp|ods|odt|zip|docx|pdf|xls|xlsx|mp4',
				'max_size' => '8048',
				'max_width' => '8096',
				'max_height' => '8048',
				'file_name' => $nomes,
				'max_filename' => '100',
				'remove_spaces' => 'TRUE',
			);
			
			$this->controller->m_upload->initialize($config);
			
			if($this->controller->m_upload->do_multi_upload($campo)) {
			} else {
				$nomes = $this->controller->m_upload->error_msg[0];
				return 0;
			}
			
			return 1;
		} else return 0;
	}
	public function enviar_arquivos_file($atual, $file, $dir, &$nomes = array(), $arquivos_antigos = array()){
		if(empty($file['name'])) return FALSE;
		$img_erro = FALSE;
		$imgs = $file;
		$nomes = $imgs['name'];
		$types = array(
				'image/jpeg', 
				'image/pjpeg', 
				'image/gif', 
				'image/png', 
				'application/x-rar-compressed',
				'application/zip',
				'application/vnd.ms-excel',
				'application/xml',
				'application/pdf',
				'application/msword',
				'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
				'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
				'application/vnd.oasis.opendocument.text',
				'application/vnd.oasis.opendocument.spreadsheet',
				'application/vnd.oasis.opendocument.presentation',
				'application/excel',
				'application/powerpoint',
				'application/vnd.ms-powerpoint',
				'application/msexcel',
				'application/excel',
				);
		
		// Caminhos do arquivos
		$upload = realpath('arquivos') .'/imagens/upload';
		$temp_dir = $upload .'/temp';
		$mini_dir = $dir . 'miniaturas/';
		
		if($atual && file_exists($dir . $atual)) unlink($dir . $atual);
		
		foreach($nomes as $k => $nome) {
			$img_erro = $img_erro || $imgs['error'][$k];
			if($imgs['error'][$k]){
				$nomes = '';

        switch ($imgs['error'][$k]) {
          case UPLOAD_ERR_INI_SIZE:
            $nomes = "O arquivo enviado excede o limite definido ".ini_get('upload_max_filesize');
            break;
          case UPLOAD_ERR_FORM_SIZE:
            $nomes = "O arquivo excede o limite definido em ".ini_get('max_file_size');
            break;
          case UPLOAD_ERR_PARTIAL:
            $nomes = "O upload do arquivo foi feito parcialmente.";
            break;
          case UPLOAD_ERR_NO_FILE:
            $nomes = "Nenhum arquivo foi enviado.";
            break;
          case UPLOAD_ERR_NO_TMP_DIR:
            $nomes = "Pasta temporária ausênte";
            break;
          case UPLOAD_ERR_CANT_WRITE:
            $nomes = "Falha em escrever o arquivo em disco";
            break;
          case UPLOAD_ERR_EXTENSION:
            $nomes = "Uma extensão do PHP interrompeu o upload do arquivo";
            break;

          default:
            $nomes = "Erro Desconhecido";
            break;
        } 

				return 0;
			}
			if(!in_array($imgs['type'][$k], $types)) {
				$nomes = 'Para maior segurança os arquivos enviados devem ser do tipo jpg, gif, png, word, pdf, ppt, exel!';
				return 0;
				return 2;// ($erro = 'Para maior segurança e velocidade de acesso do seu site, as imagens enviadas devem ser do tipo jpg, gif ou png!#erro');
			}
			if(!empty($arquivos_antigos[$k])) $nomes[$k] = $arquivos_antigos[$k];
			else $nomes[$k] = self::_parseName($nome, $dir, $mini_dir);
		}
		if(!$img_erro) {
			
			if(isset($imagens_antigas)) {
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
				'allowed_types' => 'gif|jpg|png|jpeg|txt|doc|ppt|pptx|odp|ods|odt|zip|docx|pdf|xls|xlsx',
				'max_size' => '8048',
				'max_width' => '8096',
				'max_height' => '8048',
				'file_name' => $nomes,
				'max_filename' => '100',
				'remove_spaces' => 'TRUE',
			);
			
			$this->controller->m_upload->initialize($config);
			
			if($this->controller->m_upload->do_multi_upload_file($file)) {
			} else {
				$nomes = $this->controller->m_upload->error_msg[0];
				return 0;
			}
			
			return 1;
		} else return 0;
	}
	
	
	private function _parseName($nomeImagem, $dir, $mini_dir) {
		$parts = @explode( '.', $nomeImagem );
		$extensao = strtolower( $parts[ count( $parts ) - 1 ] );
		unset( $parts[ count( $parts ) - 1 ] );
		$file = $this->controller->util->getAlias(@implode( '.', $parts )) .'.'. $extensao;
		$n1 = 1;
		while(file_exists($dir . $file) || file_exists($mini_dir . $file)){
			$file = $this->controller->util->getAlias(@implode( '.', $parts )) .'-'.$n1.'.'. $extensao;
			$n1++;
		} 
		return $file;
	}
	
	public function __construct() {
		$this->controller =& get_instance();
	}
}
?>