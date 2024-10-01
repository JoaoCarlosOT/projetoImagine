<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Admin_controller_contato extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
	}

	public function mensagens() {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('contato_mensagens')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->add_css(array(
			'style-admin'
		));

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}

		$this->dados["titulo"] = "Mensagens";
		$this->dados["dados"]["resultado"] = $this->model->buscar_mensagens($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('contato-mensagens',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function mensagem($id = 0) {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('contato_mensagens')){
			return $this->red_pagina('admin/login',FALSE);
		}

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_mensagem($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('contato/mensagem/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_mensagem($id);
		}

		$this->add_css(array(
			'style-admin'
		));
		
		$this->dados["titulo"] = "Mensagem";

		$this->html_pagina('contato-mensagem',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function trabalhe_mensagens() {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('contato_trabalhe')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->add_css(array(
			'style-admin'
		));

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}

		$this->dados["titulo"] = "Trabalhe Mensagens";
		$this->dados["dados"]["resultado"] = $this->model->buscar_trabalhe_mensagens($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('contato-trabalhe-mensagens',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function trabalhe_mensagem($id = 0) {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('contato_trabalhe')){
			return $this->red_pagina('admin/login',FALSE);
		}

		if($post = $this->input->post()) {
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_trabalhe_mensagem($id);
		}

		$this->add_css(array(
			'style-admin'
		));
		
		$this->dados["titulo"] = "Trabalhe Mensagem";

		$this->html_pagina('contato-trabalhe-mensagem',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function vagas() {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('contato_trabalhe')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->add_css(array(
			'style-admin'
		));

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}
		
		$this->dados["titulo"] = "Vagas";
		$this->dados["dados"]["resultado"] = $this->model->buscar_vagas($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('contato-vagas',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function vaga($id = 0) {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('contato_trabalhe')){
			return $this->red_pagina('admin/login',FALSE);
		}

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_vaga($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('contato/vaga/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_vaga($id);
		}

		$this->add_css(array(
			'style-admin'
		));
		
		$this->dados["titulo"] = "Vaga";

		$this->html_pagina('contato-vaga',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function boletim_mensagens() {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('contato_boletim')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->add_css(array(
			'style-admin'
		));

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}
		
		$this->dados["titulo"] = "Boletim";
		$this->dados["dados"]["resultado"] = $this->model->buscar_boletim_mensagens($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('contato-boletim-mensagens',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function config() {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('contato_config')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_config($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('contato/configuracoes',null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_config();
		}

		$this->add_css(array(
			'template_1',
			'style-admin',
		));
		
		$this->dados["titulo"] = "Configurações de contato";

		$this->html_pagina('contato-config',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function config_email() {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('contato_config_emails')){
			return $this->red_pagina('admin/login',FALSE);
		}

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_config_email($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('contato/configuracoes-email',null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_config_email();
		}

		$this->add_css(array(
			'style-admin'
		));
		
		$this->dados["titulo"] = "Configurações de email";

		$this->html_pagina('contato-config-email',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}
}