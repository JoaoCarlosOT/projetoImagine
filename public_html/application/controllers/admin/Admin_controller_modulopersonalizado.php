<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Admin_controller_modulopersonalizado extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
    }

    public function modulos() {
       
		// Valida o acesso a pagina
		$this->validar_acesso();
		
		if(!$this->model->verificar_permissao_admin('modulos')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->add_css(array(
			'style-admin'
		));

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}else{
            $args['situacao'] =  1;
        }

		$this->dados["titulo"] = "Módulos";
		$this->dados["dados"]["resultado"] = $this->model->buscar_modulos($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('modulos-personalizado',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

    public function modulo($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

        if(!$this->model->verificar_permissao_admin('modulos')){
			return $this->red_pagina('admin/login',FALSE);
		}

        
        // $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_modulo($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('modulo-personalizado/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_modulo($id);
		}

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "Módulo";

		$this->html_pagina('modulo-personalizado',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function modulo_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		
		if(!$this->model->verificar_permissao_admin('modulos')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {

			$this->model->modulo_excluir($post);
			$this->red_pagina('admin/modulos-personalizado',FALSE,"#Exluídos com sucesso");
		}
	}
}