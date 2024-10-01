<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_modulo{

	public function carregar_modulos($modulos) {
		if($modulos) {
			foreach($modulos as $k => $ms) {
				foreach($ms as $m) {
					if($d = $this->html_modulo($m)) {
						$mods[$k][] = $d;
					}
				}
			}
			return $mods;
		} else return NULL;
	}
	
	/* HTML dos módulos */
	private function html_modulo($modulo) {
		// Carrega o conteudo do módulo
		$caminho_modulo = 'modulos/'. $modulo.'.php';
		if(file_exists(APPPATH .'views/'. $caminho_modulo)) {
			$html_mod = array();
			$html_mod['modulo'] = $this->controller->load->view($caminho_modulo, array('controller' => &$this->controller, 'classe' => $modulo), TRUE);
			$html_mod['titulo'] = $modulo;
			$html_mod['classe'] = $modulo;
		} else return NULL;
		
		// Carrega e retorna o corpo do módulo
		return $this->controller->load->view($this->controller->t_pasta .'/modulo.php', $html_mod, TRUE);
	}
	
	public function __construct() {
		//Congiguração da classe
		$this->controller = get_instance();
	}
}

/* Fim do arquivo Base_controller.php */
/* Local: ./application/base/Base_controller.php */