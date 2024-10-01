<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Admin_controller_faq extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
    }

    public function faqs() {
       
		// Valida o acesso a pagina
		$this->validar_acesso();
		
		if(!$this->model->verificar_permissao_admin('faqs')){
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

		$this->dados["titulo"] = "FAQs";
		$this->dados["dados"]["resultado"] = $this->model->buscar_faqs($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('faqs',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

    public function faq($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

        if(!$this->model->verificar_permissao_admin('faqs')){
			return $this->red_pagina('admin/login',FALSE);
		}

        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_faq($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('faq/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_faq($id);
		}

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "FAQ";

		$this->html_pagina('faq',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function faq_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		
		if(!$this->model->verificar_permissao_admin('faqs')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {

			$this->model->faq_excluir($post);
			$this->red_pagina('admin/faqs',FALSE,"#Exluídos com sucesso");
		}
	}
}