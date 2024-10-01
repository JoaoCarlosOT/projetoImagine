<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Admin_controller_banner extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
	}

	public function banners() {
       
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('banners')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->add_css(array(
			'style-admin'
		));

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}

		$this->dados["titulo"] = "Banners";
		$this->dados["dados"]["resultado"] = $this->model->buscar_banners($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('banners',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function banner($id = 0) {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('banners')){
			return $this->red_pagina('admin/login',FALSE);
		}

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_banner($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('banner/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_banner($id);
		}

		$this->add_css(array(
			'style-admin'
		));
		
		$this->dados["titulo"] = "Banner";

		$this->html_pagina('banner',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

	public function banner_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('banners')){
			return $this->red_pagina('admin/login',FALSE);
		}


		if($post = $this->input->post()) {

			$this->model->banner_excluir($post);
			$this->red_pagina('admin/banners',FALSE,"#Exluídos com sucesso");
		}
	}
    
    public function slides($id_banner = 0,$nm = 0) {

        if(!$id_banner){
            $this->red_pagina('banners',null);
            exit;
        }
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('banners')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->add_css(array(
			'style-admin'
		));

        $args = null;
        
        $this->dados["dados"]['id_banner'] = $id_banner;

		if($post = $this->input->post()) {
			$args = $post;
		}

		$this->dados["titulo"] = "Slides";
		$this->dados["dados"]["resultado"] = $this->model->buscar_slides($id_banner,$args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('banner-slides',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function slide($id_banner = 0,$id = 0) {
        if(!$id_banner){
            $this->red_pagina('banners',null);
            exit;
        }

		// Valida o acesso a pagina
        $this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('banners')){
			return $this->red_pagina('admin/login',FALSE);
		}

        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_slide($id_banner,$post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('banner/'.$id_banner.'/slide/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_slide($id_banner,$id);
        }
        
        $this->dados["dados"]['id_banner'] = $id_banner;


		$this->add_css(array(
            'style-admin',
            'popup'
		));
		
		$this->dados["titulo"] = "Slide";

		$this->html_pagina('banner-slide',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

	public function slides_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('banners')){
			return $this->red_pagina('admin/login',FALSE);
		}

		if($post = $this->input->post()) {
			
			$this->model->slides_excluir($post);
			$this->red_pagina('admin/banner/'.$post['id_banner'].'/slides',FALSE,"#Exluídos com sucesso");
		}
	}

	
}