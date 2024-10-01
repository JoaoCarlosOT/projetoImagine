<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');
include(APPPATH .'config/routes.php');

define('ROUTESRESULT', json_encode($route) );

class Admin_controller_seo extends Base_controller {
    
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
    }

    public function links() {
        // Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('seo')){
			return $this->red_pagina('admin/login',FALSE);
		}
		
		$tem_novo = $this->model->verificar_cadastrar( json_decode(ROUTESRESULT,true) );

		if($tem_novo){
			$this->gerar_sitemap(0);
		}

		$this->add_css(array(
			'style-admin',
			'selectize',
			'popup'
        ));
        $this->add_script(array(
			'inputmask',
			'selectize.min',
			'popup'
        ));

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}else{
        }

		$this->dados["titulo"] = "SEO Links";
		$this->dados["dados"]["resultado"] = $this->model->buscar_links($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('seo-links',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

    public function link($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();
		
		if(!$this->model->verificar_permissao_admin('seo')){
			return $this->red_pagina('admin/login',FALSE);
		}
        

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_link($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('seo/link/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}
		
		$this->dados["dados"]["resultado"] = $this->model->buscar_link($id);

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "SEO Link";

		$this->html_pagina('seo-link',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}
	

    public function mapa() {
        // Valida o acesso a pagina
		$this->validar_acesso();

		
		if(!$this->model->verificar_permissao_admin('seo')){
			return $this->red_pagina('admin/login',FALSE);
		}

		$this->add_css(array(
			'style-admin',
			'selectize',
			'popup'
        ));
        $this->add_script(array(
			'inputmask',
			'selectize.min',
			'popup'
        ));

		$args = null;

		if($post = $this->input->post()) {
			$args = $post;
		}else{
        }

		$this->dados["titulo"] = "SEO Mapa";
		$this->dados["dados"]["resultado"] = $this->model->buscar_links($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('seo-mapa',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }
	

    function gerar_sitemap($redirect = 1) {
		
		if(!$this->model->verificar_permissao_admin('seo')){
			return $this->red_pagina('admin/login',FALSE);
		}
		
		$links = $this->model->buscar_links();
		
		#versao do encoding xml
		$dom = new DOMDocument("1.0", "UTF-8");

		#retirar os espacos em branco
		$dom->preserveWhiteSpace = false;

		#gerar o codigo
		$dom->formatOutput = true;
		
		$urlset = $dom->createElement("urlset");
		$urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
		$urlset->setAttribute('xmlns:xsi','http://www.w3.org/2001/XMLSchema-instance');
		$urlset->setAttribute('xsi:schemaLocation','http://www.sitemaps.org/schemas/sitemap/0.9
		http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd');
		
		foreach($links as $row){	
			
			if($row->link){
				$url = $dom->createElement("url");
				$row->link = base_url().($row->link != "/"?$row->link:"");


				$url->appendChild($dom->createElement("loc", $row->link));
				$url->appendChild($dom->createElement("lastmod", date('Y-m-d\TH:i:s\+00:00')));
				$url->appendChild($dom->createElement("changefreq", "weekly"));
				$url->appendChild($dom->createElement("priority", "1"));
				$urlset->appendChild($url);
			}
		}
		
		$dom->appendChild($urlset);
		#Salvar o arquivo
		$dom->save( str_replace("application/", "", $this->uri->config->_config_paths[0])."sitemap.xml");

		
		if($redirect == 0){
			return true;
			exit;
		}
		else if($redirect == 1){
			$this->red_pagina('seo/mapa',null,"#Gerado com sucesso");
		}
		else if($redirect == 2){
			#cabeçalho da página
			header("Content-Type: text/xml");
			print $dom->saveXML();
			exit;
		}
    }

	function salvar_link_ajax(){
		$post = $this->input->post();

		$resultado = $this->model->salvar_link($post);
		$dados = false;
		if($resultado['retorno'] == 1){
			$dados = true;
		}

        header('Content-Type: application/json;charset=utf-8');
        echo $dados;
		exit;
	}

    public function config() {

		// Valida o acesso a pagina
        $this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('seo')){
			return $this->red_pagina('admin/login',FALSE);
		}

        $this->load->library('Imgno_imagem', '', 'lib_imagem');
		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_config($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('seo/configuracao',null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}
		
		$this->dados["dados"]["resultado"] = $this->model->buscar_config();

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "SEO Configurações";

		$this->html_pagina('seo-config',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

    public function excluir_link(){
		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('seo')){
			return $this->red_pagina('admin/login',FALSE);
		}

        $post = $this->input->post();

		$ok_post = true;

		if('123456asd' != $post['senha']){
			
			$ok_post = false;

			$this->msg["erro"] = 'Senha incorreta!';
			$this->red_pagina('admin/seo/links',FALSE,"#erro".$this->msg["erro"]);
			$this->dados["dados"]["resultado"] = null;
		}
		if($ok_post){
			unset($post['senha']);
			
			$this->model->excluir_link($post);
			$this->red_pagina('admin/seo/links',FALSE,"#Redefinido com sucesso");
		}
	}
}