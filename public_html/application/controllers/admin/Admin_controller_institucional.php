<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Admin_controller_institucional extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
    }

    public function produtos() {
       
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_produtos')){
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
            $args['categoria'] =  '';
            $args['nome'] =  '';
        }

		$this->dados["titulo"] = "Produtos/Serviços";
		$this->dados["dados"]["resultado"] = $this->model->buscar_produtos($args);
		$this->dados["dados"]["args"] = $args;

		$this->dados["dados"]["categorias"] = $this->model->buscar_categorias();

		$this->dados["dados"]["categorias_arvore"] = $this->model->buscar_categorias(null);

		$this->html_pagina('institucional-produtos',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

    public function produto($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_produtos')){
			return $this->red_pagina('admin/login',FALSE);
		}
        
		$this->load->library('Imgno_asaas', '', 'asaas');
        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_produto($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('institucional/produto/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_produto($id);
			$this->dados["dados"]['categorias_selecionadas'] = $this->model->buscar_produto_categorias_selecionado($id);
			
		}
		
		$this->dados["dados"]["categorias"] = $this->model->buscar_produto_categorias();
		$this->dados["dados"]["copywriters"] = $this->model->buscar_copywriters();

		$this->add_css(array(
			'style-admin',
			'selectize',
        ));
        
        $this->add_script(array(
			'inputmask',
			'selectize.min',
			'jquery-ui',
		));
		
		$this->dados["titulo"] = "Produto/Serviço";

		$this->html_pagina('institucional-produto',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function produto_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_produtos')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {

			$this->model->excluir_produtos($post);
			$this->red_pagina('admin/institucional/produtos',FALSE,"#Excluídos com sucesso");
		}
	}
	
	public function solicitacoes() {
       
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_solicitacoes')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->add_css(array(
			'style-admin'
		));

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}

		$this->dados["titulo"] = "Solicitações";
		$this->dados["dados"]["resultado"] = $this->model->buscar_solicitacoes($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('institucional-solicitacoes',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function solicitacao_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_solicitacoes')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {
			$this->model->solicitacao_excluir($post);
			$this->red_pagina('admin/institucional/solicitacoes',FALSE,"#Excluídos com sucesso");
		}
	}

	public function solicitacao($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_solicitacoes')){
			return $this->red_pagina('admin/login',FALSE);
		}

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_solicitacao($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('institucional/solicitacao/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_solicitacao($id);
		}

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "Solicitação";

		$this->html_pagina('institucional-solicitacao',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}
	
	public function categorias() {
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_produtos_categorias')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->add_css(array(
			'style-admin'
		));

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}

		$this->dados["titulo"] = "Categorias";
		$this->dados["dados"]["resultado"] = $this->model->buscar_categorias($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('institucional-categorias',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function categoria($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_produtos_categorias')){
			return $this->red_pagina('admin/login',FALSE);
		}
        
        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_categoria($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('institucional/categoria/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_categoria($id);
		}

		$this->dados["dados"]["categorias"] = $this->model->buscar_categorias();

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "Categoria";

		$this->html_pagina('institucional-categoria',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function categoria_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_produtos_categorias')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {
			$this->model->categoria_excluir($post);
			$this->red_pagina('admin/institucional/categorias',FALSE,"#Excluídos com sucesso");
		}
	}

	public function profissionais() {
       
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_profissionais')){
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

		$this->dados["titulo"] = "Profissionais";
		$this->dados["dados"]["resultado"] = $this->model->buscar_profissionais($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('institucional-profissionais',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

    public function profissional($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_profissionais')){
			return $this->red_pagina('admin/login',FALSE);
		}
        
        
        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_profissional($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('institucional/profissional/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_profissional($id);
			
		}

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "Profissional";

		$this->html_pagina('institucional-profissional',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}
	
	public function profissionais_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_profissionais')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {

			$this->model->excluir_profissional($post);
			$this->red_pagina('admin/institucional/profissionais',FALSE,"#Excluídos com sucesso");
		}
	}

	public function depoimentos(){
       
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_depoimentos')){
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
            $args['nome'] =  '';
        }

		$this->dados["titulo"] = "Depoimentos";
		$this->dados["dados"]["resultado"] = $this->model->buscar_depoimentos($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('institucional-depoimentos',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

    public function depoimento($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_depoimentos')){
			return $this->red_pagina('admin/login',FALSE);
		}
        
        
        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_depoimento($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('institucional/depoimento/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_depoimento($id);
			
		}

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "Depoimento";

		$this->html_pagina('institucional-depoimento',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function depoimento_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_depoimentos')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {

			$this->model->excluir_depoimentos($post);
			$this->red_pagina('admin/institucional/depoimentos',FALSE,"#Excluídos com sucesso");
		}
	} 

	public function bignumbers(){
       
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_bignumbers')){
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
            $args['nome'] =  '';
        }

		$this->dados["titulo"] = "Big Numbers";
		$this->dados["dados"]["resultado"] = $this->model->buscar_bignumbers($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('institucional-bignumbers',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

    public function bignumber($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_bignumbers')){
			return $this->red_pagina('admin/login',FALSE);
		}
        
        
        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			if(!empty($post['bigNumber'])){
                $post['bigNumber'] = json_encode($post['bigNumber'], JSON_UNESCAPED_UNICODE);
            }else{
                $post['bigNumber'] = "";
            }
			
			$resultado = $this->model->salvar_bignumber($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('institucional/bignumber/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_bignumber($id);
			
		}

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "Big Number";

		$this->html_pagina('institucional-bignumber',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function bignumber_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('institucional_bignumbers')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {

			$this->model->excluir_bignumbers($post);
			$this->red_pagina('admin/institucional/bignumbers',FALSE,"#Excluídos com sucesso");
		}
	}
}