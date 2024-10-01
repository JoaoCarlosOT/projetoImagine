<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Landingpage_controller_landingpage extends Base_controller {
	public function lpCidade($id_config = 0, $id = 0)
	{
		// Config da pagina
		$this->dados = array();
		
		$this->add_css(array(
			'template_1',
			'carousel',
			// 'landingpage',
		));

        $this->add_script(array(
			'carousel'
		));
		
		$this->load->library('Imgno_imagem', '', 'imagineImagem');		
		$this->load->library('Imgno_util', '', 'ImgnoUtil');	

		$conteudo = $this->model->lpCidade($id);
		$configuracao_lp = $this->model->buscar_configuracao_lp($id_config);

		if(!$conteudo || !$configuracao_lp) $this->red_pagina('erro');

		if($configuracao_lp["bigNumber"]){
			$configuracao_lp["bigNumber"] = json_decode($configuracao_lp['bigNumber'], true);	
		}else{
			$configuracao_lp["bigNumber"] = array();
		}
		$strings_replace = $this->model->buscar_configuracao_lp_replace($id_config);
		$strings_replace["Cidade"] = $conteudo->nome;
		$strings_replace["Estado"] = $conteudo->estado->nome;
		$strings_replace["Solicitar-Orcamento-Zap"] = "<a class='btn' href='https://api.whatsapp.com/send?1=pt_BR&phone=55".preg_replace('/[^0-9]/', '', $strings_replace['telefone1'])."&text=".urlencode("Olá, Gostaria de uma Orçamento!! \nSou de ".$conteudo->nome."/".$conteudo->estado->nome)."' class='botao-whatsapp-verde' target='_blank'><em class='icon-whatsapp'></em>Solicitar orçamento</a>";
		unset($strings_replace['telefone1']);
		
		$this->dados["dados"]["strings_replace"] = $strings_replace;
		$this->dados['titulo'] = $this->ImgnoUtil->replace_tags($conteudo->title_seo, $strings_replace);
		$this->dados['descricao'] = $this->ImgnoUtil->replace_tags($conteudo->descricao_seo, $strings_replace);
		$this->dados['keywords'] = $this->ImgnoUtil->replace_tags($conteudo->keywords_seo, $strings_replace);
		$this->dados['banner']['id_banner'] = $configuracao_lp["id_banner"];
		$this->dados["dados"]["resultado"] = $conteudo;
		$this->dados["dados"]["configuracao_lp"] = $configuracao_lp; 


		// Copywriters 
		$copywriters = json_decode($configuracao_lp['copywriters'], true);
		if(!$copywriters) $copywriters = array();
		
		$this->dados["dados"]["config_copy"] = $this->model->buscar_config_copywriter();
		$this->dados["dados"]["copywriters"] = array();
		if($copywriters){
			foreach($copywriters as $copywriter){
				$this->dados["dados"]["copywriters"][] = $this->model->buscar_copywriter($copywriter);
			}
		}
		

		$this->html_pagina('LP-cidade',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'banner' => array(
				'banner-lp'
			),
			'conteudo_1' => array(
				// 'galeria-1',
				// 'modulo33',
			),
			'conteudo_3' => array(
			),
			'rodape' => array(
				'site-rodape',
				'whatsapp-flutuante',
			)
        ));
	} 

	public function lpEstado($id_config = 0, $id = 0)
	{
		// Config da pagina
		$this->dados = array();
		//$this->dados['banner']['id_banner'] = 1;
		
		$this->add_css(array(
			'template_1',
			'carousel',
			// 'landingpage',
		));
		
        $this->add_script(array(
			'carousel'
		));

		$resultado = $this->model->lpEstado($id);

		if(!$resultado) $this->red_pagina('erro');

		$this->load->library('Imgno_imagem', '', 'imagineImagem');		
		$this->load->library('Imgno_util', '', 'ImgnoUtil');

		$strings_replace = $this->model->buscar_configuracao_lp_replace($id_config);
		$strings_replace["Estado"] = $strings_replace["Cidade"] = $resultado->nome;
		
		$this->dados["dados"]["alias_pre"] = $this->model->getAlias($this->ImgnoUtil->replace_tags("[Palavra-Chave-01]", $strings_replace));
		$this->dados["dados"]["resultado"] = $resultado;
		
		$this->dados['titulo'] = $this->ImgnoUtil->replace_tags($resultado->title_seo, $strings_replace);
		
		$this->html_pagina('LP-estado',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'banner' => array(
			),
			'conteudo_3' => array(
				'',
			),
			'rodape' => array(
				'site-rodape',
				'whatsapp-flutuante',		
			)
        ));
	} 

	function salvar_solicitacao(){
		$post = $this->input->post();

		if(!$post){
			exit;
		}

		/*$validarRe = $this->validarRecaptchaCalc($post);

		if ($validarRe == false){
			echo 'erro';
			exit;
		}*/

		$dados = $this->model->salvar_solicitacao($post);

		echo ($dados?'ok':'erro');
		exit;
	}
	
}