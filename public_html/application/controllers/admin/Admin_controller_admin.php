<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');


class Admin_controller_admin extends Base_controller {
	

	// Valida o acesso a pagina
	private function validar_acesso() {

		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
	}

	public function login() {
		
		if($this->model->logado('admin')) $this->red_pagina('admin/conta',FALSE);

		$req = $this->input->get('ImAgInE-13-05-DiGiTaL');
		if(!$req) $this->red_pagina('inicio', FALSE,"#Acesso negado!");

		$this->add_css(array(
			'style-admin',
			'style-admin-login'
		));
		
		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Login';
		
		if($post = $this->input->post()) {

			$this->load->model('admin/Admin_model_imagine','model_imagine');
			$imagine_dados = $this->model_imagine->autenticar_usuario_imagine($post);

			// Argumentos da pagina
			$args = array(
				'post' => $post,
				'erro' => '',
				'chave' => 'usuario',
				'_tabela' => 'admin'
			);
			
			if($this->model->login($args)) {
				$this->red_pagina('admin/conta',FALSE);
			} else {
				$this->mensagem_aviso($args['erro']);
				$this->red_pagina('admin/login', FALSE);
			}
		}

		$this->html_pagina('login',array(
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	// Pagina de logout
	public function sair() {

		// Valida o acesso a pagina
		$this->validar_acesso();
		
		$this->model->logout();
		$this->red_pagina('admin/login',FALSE,'ImAgInE-13-05-DiGiTaL=1');
	}

	public function inicio()
	{	
		// Valida o acesso a pagina
		$this->validar_acesso();

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Inicio';


		$this->add_css(array(
			'style-admin',
		));

		$this->html_pagina('inicio',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	// Pagina de conta
	public function conta()
	{	
		// Valida o acesso a pagina
		$this->validar_acesso();

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Minha Conta';

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_conta($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('admin/conta',FALSE,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["usuario"] = $resultado['usuario'];
			}
			
		}

		$this->dados["dados"]["usuario"] = $this->model->buscar_conta();

		$this->add_css(array(
			'style-admin'
        ));
        
		$this->html_pagina('conta',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

	// Pagina de usuarios
	public function usuarios() {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('usuarios')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->add_css(array(
			'style-admin'
		));

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}

		$this->dados["titulo"] = "Usuários";
		$this->dados["dados"]["usuarios"] = $this->model->buscar_usuarios($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('usuarios',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function usuario($id = 0) {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('usuarios')){
			return $this->red_pagina('admin/login',FALSE);
		}

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_usuario($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('usuario/'.$resultado['usuario']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["usuario"] = $resultado['usuario'];
			}
			
		}else{
			$this->dados["dados"]["usuario"] = $this->model->buscar_usuario($id);

			if(isset($this->dados["dados"]["usuario"]->permissoes)){
				$permissoes = null;
				foreach($this->dados["dados"]["usuario"]->permissoes as $k => $permissao){
					$permissoes[$k] = $permissao->permissao;
				}
				$this->dados["dados"]["usuario"]->permissoes = $permissoes;
			}

		}

		$this->dados["dados"]["permissoes"] = $this->model->buscar_lista_permissoes();

		$this->add_css(array(
			'style-admin'
		));
		
		$this->dados["titulo"] = "Usuário";

		$this->html_pagina('usuario',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function admin_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('usuarios')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {

			$this->model->admin_excluir($post);
			$this->red_pagina('admin/usuarios',FALSE,"#Exluídos com sucesso");
		}
	}
}