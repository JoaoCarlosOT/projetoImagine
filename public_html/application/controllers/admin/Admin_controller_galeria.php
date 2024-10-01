<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Admin_controller_galeria extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
    }

    public function galerias() {
       
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('galerias')){
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

		$this->dados["titulo"] = "Galerias";
		$this->dados["dados"]["resultado"] = $this->model->buscar_galerias($args);
		$this->dados["dados"]["args"] = $args;

		$this->dados["dados"]["categorias"] = $this->model->buscar_categorias();

		$this->dados["dados"]["categorias_arvore"] = $this->model->buscar_categorias(null);

		$this->html_pagina('galerias',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

    public function galeria($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('galerias')){
			return $this->red_pagina('admin/login',FALSE);
		}
        
        
        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_galeria($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('galeria/galeria/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_galeria($id);
			$this->dados["dados"]['categorias_selecionadas'] = $this->model->buscar_galeria_categorias_selecionado($id);
			
		}
		
		$this->dados["dados"]["categorias"] = $this->model->buscar_galeria_categorias();

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "Galeria";

		$this->html_pagina('galeria',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	function galeria_ordem(){

		$post = $this->input->post();
		$item = $post['itens'];
		$galeria = $post['id'];

		parse_str($item, $saida);
		$ordem = 0;

		foreach($saida['imagem_item'] as $imagem){
			$ordem++;

			$this->model->atualizar_dados_tabela(array(
				'_tabela' => 'galeria_imagem',
				'dados' => array(
					'ordem' => $ordem,
				),
				'where' => 'id = "'.$imagem.'" AND id_galeria = "'.$galeria.'"'
			));

		}

		header('Content-Type: application/json;charset=utf-8');
        echo json_encode(array('ok'));
		exit;
	}

	public function galeria_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('galerias')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {

			$this->model->excluir_galerias($post);
			$this->red_pagina('admin/galeria/galerias',FALSE,"#Exluídos com sucesso");
		}
	}
	
	public function categorias() {
       
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('galerias')){
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

		$this->html_pagina('galeria-categorias',array(
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

		if(!$this->model->verificar_permissao_admin('galerias')){
			return $this->red_pagina('admin/login',FALSE);
		}
        
        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_categoria($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('galeria/categoria/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
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

		$this->html_pagina('galeria-categoria',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}


}