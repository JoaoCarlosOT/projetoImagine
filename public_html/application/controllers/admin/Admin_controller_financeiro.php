<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Admin_controller_financeiro extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
	}

	public function financeiro_dados(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('financeiro')){
			return $this->red_pagina('admin/login',FALSE);
		}

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Financeiro';

		$args = null;
		if($post = $this->input->post()) {
			$args = $post;

			$args["mes"] = date('m',strtotime($args['data_ini']));
			$args["ano"] = date('Y',strtotime($args['data_ini']));
		}else{
			
			$args["tipo_data"] = 1;
			$args['data_ini'] = date('Y-m-').'01'; 
			$args['data_ate'] = date('Y-m-t'); 

			$args["mes"] = date('m',strtotime('now'));
			$args["ano"] = date('Y',strtotime('now'));

			$args["id_cliente"] = null;
			$args["tipo_servico"] = null;
			$args["situacao"] = null;
			$args["tipo_cobranca"] = null;
		} 
		
		$this->dados["dados"]["args"] = $args;

		$this->dados["dados"]["resultado"]["dados"] = $this->model->buscar_lancamentos($args);

		$this->dados["dados"]["relatorio"]["relatorio_atual"] = $this->model->buscar_lancamentos_relatorio($args);
		$this->dados["dados"]["relatorio"]["relatorio_r1"] = $this->model->buscar_lancamentos_relatorio($args,"-2");
		$this->dados["dados"]["relatorio"]["relatorio_r2"] = $this->model->buscar_lancamentos_relatorio($args,"-1");
		$this->dados["dados"]["relatorio"]["relatorio_r3"] = $this->model->buscar_lancamentos_relatorio($args,"+1");
		$this->dados["dados"]["relatorio"]["relatorio_r4"] = $this->model->buscar_lancamentos_relatorio($args,"+2");

		$this->add_css(array(
			'style-admin',
			'popup',
			'selectize'
        ));
        $this->add_script(array(
			'inputmask',
			'popup',
			'selectize.min'
        ));
        
        
		$this->html_pagina('financeiro-dados',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }
    
    function carrinhos_abandonados(){
        // Valida o acesso a pagina
        $this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('financeiro')){
			return $this->red_pagina('admin/login',FALSE);
		}

        $this->dados = array();
		$this->dados['titulo'] = 'Carrinhos abandonados';

		$args = null;
		if($post = $this->input->post()) {
			$args = $post;
		}

		if(!$args){
			$args["mes"] = date('m',strtotime('now'));
			$args["ano"] = date('Y',strtotime('now'));

			$args["id_cliente"] = null;
			$args["tipo_servico"] = null;
			$args["situacao"] = null;
			$args["tipo_cobranca"] = null;
		}
		
		$this->dados["dados"]["args"] = $args;

		$this->dados["dados"]["resultado"]["dados"] = $this->model->buscar_carrinhos_abandonados($args);

		$this->dados["dados"]["relatorio"]["relatorio_atual"] = $this->model->buscar_lancamentos_relatorio($args);
		$this->dados["dados"]["relatorio"]["relatorio_r1"] = $this->model->buscar_lancamentos_relatorio($args,"-2");
		$this->dados["dados"]["relatorio"]["relatorio_r2"] = $this->model->buscar_lancamentos_relatorio($args,"-1");
		$this->dados["dados"]["relatorio"]["relatorio_r3"] = $this->model->buscar_lancamentos_relatorio($args,"+1");
		$this->dados["dados"]["relatorio"]["relatorio_r4"] = $this->model->buscar_lancamentos_relatorio($args,"+2");

        $this->add_css(array(
			'style-admin',
			'popup',
			'selectize'
        ));
        $this->add_script(array(
			'inputmask',
			'popup',
			'selectize.min'
        ));
        
        $this->html_pagina('financeiro-carrinhos-abandonados',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}
	
	function carrinho($id_sessao = null){
        // Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('financeiro')){
			return $this->red_pagina('admin/login',FALSE);
		}
		
		if(!$id_sessao) exit;

        $this->dados = array();
		$this->dados['titulo'] = 'Carrinho';

		$this->dados["dados"]["resultado"] = $this->model->buscar_carrinho($id_sessao);
		

        $this->add_css(array(
			'style-admin',
			'popup',
			'selectize'
        ));
        $this->add_script(array(
			'inputmask',
			'popup',
			'selectize.min'
        ));
        
        $this->html_pagina('financeiro-carrinho',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

	public function configuracao() {

		// Valida o acesso a pagina
		$this->validar_acesso();

		
		if(!$this->model->verificar_permissao_admin('financeiro_configuracoes')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->load->library('Imgno_asaas', '', 'asaas');
		
		if($post = $this->input->post()) {
			if(!empty($post['formas_pagamento'])){
				$post['formas_pagamento'] = json_encode($post['formas_pagamento']);
			}else{
				$post['formas_pagamento'] = json_encode(array());
			}

			$resultado = $this->model->salvar_configuracao($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('financeiro/configuracao',null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_configuracao();
		}

		$this->add_css(array(
			'style-admin'
		));
		
		$this->dados["titulo"] = "Configurações";

		$this->html_pagina('financeiro-configuracao',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	} 

	public function cupons() {
       
		// Valida o acesso a pagina
		$this->validar_acesso();
		
		if(!$this->model->verificar_permissao_admin('financeiro_cupons')){
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

		$this->dados["titulo"] = "Cupons";
		$this->dados["dados"]["resultado"] = $this->model->buscar_cupons($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('financeiro-cupons',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

    public function cupom($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

        if(!$this->model->verificar_permissao_admin('financeiro_cupons')){
			return $this->red_pagina('admin/login',FALSE);
		}

        
        // $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_cupom($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('financeiro/cupom/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_cupom($id);
		}

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "Cupom";

		$this->html_pagina('financeiro-cupom',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function cupom_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		
		if(!$this->model->verificar_permissao_admin('modulos')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {

			$this->model->cupom_excluir($post);
			$this->red_pagina('admin/financeiro/cupons',FALSE,"#Exluídos com sucesso");
		}
	} 

	public function assinaturas() {

		// Valida o acesso a pagina
		$this->validar_acesso();
		
		if(!$this->model->verificar_permissao_admin('financeiro_assinaturas')){
			return $this->red_pagina('admin/login',FALSE);
		} 

		$this->add_css(array(
			'style-admin'
		));

		$this->load->library('Imgno_asaas', '', 'asaas');

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}else{
            $args['situacao'] =  1;
        }

		$this->dados["titulo"] = "Assinaturas";
		$this->dados["dados"]["resultado"] = $this->model->buscar_assinaturas($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('financeiro-assinaturas',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

    public function assinatura($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

        if(!$this->model->verificar_permissao_admin('financeiro_assinaturas')){
			return $this->red_pagina('admin/login',FALSE);
		} 

		$this->load->library('Imgno_asaas', '', 'asaas');
        // $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_assinatura($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('financeiro/assinatura/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_assinatura($id);
		}

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "Assinatura";

		$this->html_pagina('financeiro-assinatura',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	} 

    public function assinatura_cancelar($id = 0) {
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('financeiro_assinaturas')){
			return $this->red_pagina('admin/login',FALSE);
		} 

		if(!$id) $this->red_pagina('financeiro/assinaturas',null);

		$this->load->model('admin/Admin_model_admin','model_admin');
		$dados_admin = $this->model_admin->buscar_conta();
		$assinatura = $this->db->query('SELECT * FROM '.$this->model->prefixo_db.'financeiro_assinatura WHERE id = '.$id)->row();
		$log_texto = $dados_admin->nome . ' Cancelou assinatura <b>(#'.$id.') ' . $assinatura->nome . '</b> do cliente <b>(#'.$assinatura->id_cliente.') '.$this->db->query('SELECT nome FROM '.$this->model->prefixo_db.'clientes WHERE id = '.$assinatura->id_cliente)->row('nome') . '</b> na data ' . date('d/m/Y \à\s H:i');

		$this->load->library('Imgno_asaas', '', 'asaas');
		$resultado = $this->asaas->assinatura_cancelar($id, $log_texto); 

		if(!empty($resultado->deleted)){ 

			// log admin			
			$this->load->model('admin/Admin_model_log', 'model_log');
			$this->model_log->salvar_log( array(
				'log' => $log_texto,
				// 'id_cliente' => null,
				// 'json' => null,
			));

			$this->red_pagina('financeiro/assinatura/'.$id,null,'#Assinatura Cancelada com sucesso');
		}else if(!$resultado) {
			$this->red_pagina('financeiro/assinaturas',null);
		}

		exit;
	}
}