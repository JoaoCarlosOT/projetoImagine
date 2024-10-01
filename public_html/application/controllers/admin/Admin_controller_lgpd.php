<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Admin_controller_lgpd extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
	}

	public function lgpd() {

		// Valida o acesso a pagina
		$this->validar_acesso();
		
		if(!$this->model->verificar_permissao_admin('lgpd')){
			return $this->red_pagina('admin/login',FALSE);
		}
		
		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_lgpd($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('lgpd',null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_lgpd();
		}

		$this->add_css(array(
			'style-admin',
			'popup'
		));

		$this->add_script(array(
			'popup'
        ));
		
		$this->dados["titulo"] = "LGPD";

		$this->html_pagina('lgpd',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}  

}