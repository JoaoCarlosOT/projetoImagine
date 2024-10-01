<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Cliente_controller_cliente extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('cliente')) return $this->red_pagina('login',FALSE);
	}

	public function login() {
		
		// if($this->model->logado('cliente')) $this->red_pagina('minha-conta',FALSE);
		if($this->model->logado('cliente')) $this->red_pagina('minha-conta/pedidos',FALSE);

		$this->add_css(array(
			'template_1',
			'style-login'
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
				'chave' => 'cpf_cnpj',
				'_tabela' => 'clientes'
			);
			
			if($this->model->login($args,'cliente')) {
				// $this->red_pagina('minha-conta',FALSE);
				$this->red_pagina('minha-conta/pedidos',FALSE);
			} else {
				$this->mensagem_aviso($args['erro']);
				$this->red_pagina('login', FALSE);
			}
		}

		$this->load->model('site/site_model_checkout', 'model_checkout'); 
		$this->dados["dados"]["buscar_configuracao"] = $this->model_checkout->buscar_configuracao();

		$this->html_pagina('login',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}

	// Pagina de logout
	public function sair() {

		// Valida o acesso a pagina
		$this->validar_acesso();
		
		$this->model->logout('cliente');
		$this->red_pagina('login',FALSE);
	}

	// Pagina de conta
	public function inicio()
	{	
		
		// Valida o acesso a pagina
		$this->validar_acesso();

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Minha Conta';

		$this->add_css(array(
			'template_1',
			'conta'
		));
        
		$this->add_css(array(
			'template_2',
		),false,2);

		$this->html_pagina('inicio',array(
			'topo' => array(
				'site-topo',
				'cliente-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
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
				$this->red_pagina('minha-conta/conta',FALSE,"#Salvo com sucesso");
			}else{
				$this->msg["erro"] = $resultado["msg"];
			}
			
		}

		$resultado = $this->model->buscar_conta();
		$this->dados["dados"]["cliente"] = $resultado;
		
		$this->load->model('site/site_model_checkout', 'model_checkout'); 
		$this->dados["dados"]["buscar_configuracao"] = $this->model_checkout->buscar_configuracao();

		$this->add_css(array(
			'template_1',
			'conta',
			'popup'
		));

		$this->add_css(array(
			'template_2',
		),false,2);

		$this->add_script(array(
			'popup'
		));

		$this->html_pagina('conta',array(
			'topo' => array(
				'site-topo',
				'cliente-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}

	public function agendamentos()
	{	
		// Valida o acesso a pagina
		$this->validar_acesso();

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Minha Conta';


		$this->add_css(array(
			'template_1',
			'conta'
		));

		$this->add_css(array(
			'template_2',
		),false,2);

		$this->html_pagina('agendamentos',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}

	public function pedidos()
	{	
		// Valida o acesso a pagina
		$this->validar_acesso();

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Pedidos';

		$this->add_css(array(
			'template_1',
			'conta'
		));

		$this->add_css(array(
			'template_2',
		),false,2);

        $this->load->library('Imgno_asaas', '', 'asaas');
		$this->load->model('site/Site_model_checkout','model_check');

		$this->dados["dados"]["resultado"] = $this->model_check->buscar_pedidos();

		$this->html_pagina('pedidos',array(
			'topo' => array(
				'site-topo',
				'cliente-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}

	public function assinaturas()
	{	
		// Valida o acesso a pagina
		$this->validar_acesso();

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Assinaturas';

		$this->add_css(array(
			'template_1',
			'conta'
		));

		$this->add_css(array(
			'template_2',
		),false,2);

        $this->load->library('Imgno_asaas', '', 'asaas');
		$this->load->model('site/Site_model_checkout','model_check');

		$this->dados["dados"]["resultado"] = $this->model_check->buscar_assinaturas();

		$this->html_pagina('assinaturas',array(
			'topo' => array(
				'site-topo',
				'cliente-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}

	public function informativos()
	{	
		// Valida o acesso a pagina
		$this->validar_acesso();

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Minha Conta';

		$resultado = $this->model->buscar_informativos();
		$this->dados["dados"]["resultado"] = $resultado;

		$this->add_css(array(
			'template_1',
			'conta'
		));

		$this->add_css(array(
			'template_2',
		),false,2);

		$this->html_pagina('informativos',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}

	public function informativo($id)
	{	
		// Valida o acesso a pagina
		$this->validar_acesso();

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Minha Conta';

		$resultado = $this->model->buscar_informativo($id);
		$this->dados["dados"]["resultado"] = $resultado;

		$this->add_css(array(
			'template_1',
			'conta'
		));

		$this->add_css(array(
			'template_2',
		),false,2);

		$this->html_pagina('informativo',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}

	public function gLogin()
	{
		$post = $this->input->post();
		$token = $post['token'];

	    $url ='https://oauth2.googleapis.com/tokeninfo?id_token='.$token;
	    $dados = json_decode(file_get_contents($url));
	    
	    if(isset($dados->email)){


			$args = array(
				'post' => $post,
				'erro' => '',
				'chave' => 'email',
				'_tabela' => 'clientes'
			);
			
			if($this->model->login($args)) {
				$this->red_pagina('checkout',FALSE);
			}else{
				$post_env['nome'] = $dados->name;
				$post_env['email'] = $dados->email;
			}

		}
	}
	
	function verificar_usuario_ajax(){
		$post = $this->input->get();
		$retorno['retorno'] = 0;

		if(!$post['query']) return $retorno;

		$dado = $this->model->db->query('SELECT id FROM '. $this->model->prefixo_db.'clientes WHERE cpf_cnpj = "'.$post['query'].'"')->row();

		if($dado){
			$retorno['retorno'] = 1;
		}else{
			$this->model->session->set_userdata('login_cpf_cnpj', $post['query']);

			$this->load->model('site/Site_model_checkout','model_check');
			$this->model_check->atualizar_dados_carrinho(array(
															'cpf_cnpj' => $post['query']
														));
		}

        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($retorno);
		exit;
	}
}