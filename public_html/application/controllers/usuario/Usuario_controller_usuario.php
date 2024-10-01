<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Usuario_controller_usuario extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('usuario')) return $this->red_pagina('login',FALSE);
	}

	public function login() {
		
		if($this->model->logado('usuario')) $this->red_pagina('minha-conta',FALSE);
 
		$this->add_css(array(
			'template_1',
			'style-login',
			'style-login',
		));

		$this->add_css(array(
			'template_2',
		),false,2);
		
		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Login';
		
		if($post = $this->input->post()) {
			// Argumentos da pagina
			$args = array(
				'post' => $post,
				'erro' => '',
				'chave' => 'usuario',
				'_tabela' => 'usuarios'
			);
			
			if($this->model->login($args)) {
				$this->red_pagina('minha-conta',FALSE);
			} else {
				$this->mensagem_aviso($args['erro']);
				$this->red_pagina('login', FALSE);
			}
		}

		$this->html_pagina('login',array(
			'topo' => array(
				'sistema-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'sistema-rodape'
			)
        ));
	}

	// Pagina de logout
	public function sair() {

		// Valida o acesso a pagina
		$this->validar_acesso();
		
		$this->model->logout();
		$this->red_pagina('login',FALSE);
	}

	public function inicio()
	{	
		// Valida o acesso a pagina
		$this->validar_acesso();

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Inicio';

		$this->add_css(array(
			'template_1',
			'style',
		));

		$this->add_css(array(
			'template_2',
		),false,2);

		$this->html_pagina('inicio',array(
			'topo' => array(
				'sistema-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'sistema-rodape'
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
				$this->red_pagina('admin-usuario/conta',FALSE,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["usuario"] = $resultado['usuario'];
			}
			
		}

		$this->dados["dados"]["usuario"] = $this->model->buscar_conta();

		$this->add_css(array(
			'template_1',
			'style',
		));

		$this->add_css(array(
			'template_2',
		),false,2);
        
		$this->html_pagina('conta',array(
			'topo' => array(
				'sistema-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'sistema-rodape'
			)
        ));
    }
}