<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Admin_controller_copywriter extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
    }

    public function copywriters() {
       
		// Valida o acesso a pagina
		$this->validar_acesso();
		
		if(!$this->model->verificar_permissao_admin('copywriters')){
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

		$this->dados["titulo"] = "Copywriters";
		$this->dados["dados"]["resultado"] = $this->model->buscar_copywriters($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('copywriters',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

    public function copywriter($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

        if(!$this->model->verificar_permissao_admin('copywriters')){
			return $this->red_pagina('admin/login',FALSE);
		}

        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_copywriter($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('copywriter/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_copywriter($id);
		}

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "Copywriter";

		$this->html_pagina('copywriter',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function copywriter_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();
		
		if(!$this->model->verificar_permissao_admin('copywriters')){
			return $this->red_pagina('admin/login',FALSE);
		}

		if($post = $this->input->post()) {

			$this->model->copywriter_excluir($post);
			$this->red_pagina('admin/copywriters',FALSE,"#Exluídos com sucesso");
		}
	}

	public function configuracao() {
		// Valida o acesso a pagina
		$this->validar_acesso(); 
		
		if(!$this->model->verificar_permissao_admin('copywriter_configuracao')){
			return $this->red_pagina('admin/login',FALSE);
		} 
		
		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_configuracao($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('copywriter/configuracao',null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_configuracao();
		}

		$this->add_css(array(
			'style-admin',
			'popup'
		));

		$this->add_script(array(
			'popup'
        ));
		
		$this->dados["titulo"] = "Configurações";

		$this->html_pagina('copywriter-configuracao',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}
}