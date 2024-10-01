<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Admin_controller_blog extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
    }

    public function blog_artigos() {
       
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('blog')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->add_css(array(
			'style-admin'
		));

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}

		$this->dados["titulo"] = "Blog - Artigos";
		$this->dados["dados"]["resultado"] = $this->model->buscar_artigos($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('blog-artigos',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }
    
    public function blog_artigo($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('blog')){
			return $this->red_pagina('admin/login',FALSE);
		}
        
        
        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_artigo($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('blog-artigo/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_artigo($id);
			$this->dados["dados"]['categorias_selecionadas'] = $this->model->buscar_artigo_categorias_selecionado($id);
			$this->dados["dados"]['categorias_selecionadas_produto'] = $this->model->buscar_produto_categorias_selecionado($id);
		}

		$this->dados["dados"]["categorias"] = $this->model->buscar_artigo_categorias();
		$this->dados["dados"]["categorias_produto"] = $this->model->buscar_produto_categorias();

		$this->add_css(array(
			'style-admin'
		));
		
		$this->dados["titulo"] = "Blog - Artigo";

		$this->html_pagina('blog-artigo',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}
	
	public function excluir_artigo(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('blog')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {

			$this->model->excluir_artigo($post);
			$this->red_pagina('admin/blog-artigos',FALSE,"#Exluídos com sucesso");
		}
	}

	public function categorias() {
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('blog_categorias')){
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

		$this->html_pagina('blog-categorias',array(
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

		if(!$this->model->verificar_permissao_admin('blog_categorias')){
			return $this->red_pagina('admin/login',FALSE);
		}
        
        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_categoria($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('blog/categoria/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
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

		$this->html_pagina('blog-categoria',array(
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
		
		if(!$this->model->verificar_permissao_admin('blog_configuracao')){
			return $this->red_pagina('admin/login',FALSE);
		} 
		
		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_configuracao($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('blog/configuracao',null,"#".$resultado["msg"]);
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

		$this->html_pagina('blog-configuracao',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}
}