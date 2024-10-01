<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Admin_controller_landingpage extends Base_controller {
    // Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
    }
    
    // Estado/Cidade
    public function estados_cidades() {
        // Valida o acesso a pagina
        $this->validar_acesso(); 

        if(!$this->model->verificar_permissao_admin('landingpage_estado_cidade')){
            return $this->red_pagina('admin/login',FALSE);
        }

        $this->add_css(array(
            'style-admin'
        ));

        $args = null;

        if($post = $this->input->post()) {
            $args = $post;
        }

        $this->dados["titulo"] = "Estados/Cidades";
        $this->dados["dados"]["resultado"] = $this->model->buscar_estados_cidades($args);
        $this->dados["dados"]["args"] = $args;

        $this->html_pagina('landing-page-estados-cidades',array(
            'topo' => array(
                'admin-topo'
            ),
            'rodape' => array(
                'admin-rodape'
            )
        ));
    }

    public function estado_cidade($id = 0) {

        // Valida o acesso a pagina
        $this->validar_acesso();

        if(!$this->model->verificar_permissao_admin('landingpage_estado_cidade')){
            return $this->red_pagina('admin/login',FALSE);
        }
        
        $this->load->library('Imgno_imagem', '', 'lib_imagem');

        if($post = $this->input->post()) {
            $resultado = $this->model->salvar_estado_cidade($post);

            if($resultado['retorno'] == 1){
                $this->msg["ok"] = $resultado["msg"];  
                $this->red_pagina('landing-page/estado-cidade/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
            }else{
                $this->msg["erro"] = $resultado["msg"];
                $this->dados["dados"]["resultado"] = $resultado['dados'];
            }
            
        }else{
            $this->dados["dados"]["resultado"] = $this->model->buscar_estado_cidade($id);
        } 
        
        $this->dados["dados"]["estados"] = $this->model->buscar_estados_cidades(array('categoria_pai' => 0));
        $this->dados["dados"]["regioes"] = $this->model->buscar_regioes();

        $this->add_css(array(
            'style-admin'
        ));
        
        $this->add_script(array(
            'inputmask'
        ));
        
        $this->dados["titulo"] = "Estado/Cidade";

        $this->html_pagina('landing-page-estado-cidade',array(
            'topo' => array(
                'admin-topo'
            ),
            'rodape' => array(
                'admin-rodape'
            )
        ));
    }


    public function configuracoes_lp() {
        // Valida o acesso a pagina
        $this->validar_acesso();

        if(!$this->model->verificar_permissao_admin('landingpage_config_lp')){
            return $this->red_pagina('admin/login',FALSE);
        } 
        
        $this->add_css(array(
            'style-admin'
        ));
        
        $this->add_script(array(
            'inputmask'
        ));
        

        $args = null;

        if($post = $this->input->post()) {
            $args = $post;
        } 

        $this->dados["titulo"] = "Produtos/Servicos da LP";
        $this->dados["dados"]["resultado"] = $this->model->buscar_configuracoes_lp($args);
        $this->dados["dados"]["args"] = $args;
        
        $this->html_pagina('landing-page-configuracoes-lp',array(
            'topo' => array(
                'admin-topo'
            ),
            'rodape' => array(
                'admin-rodape'
            )
        ));
    }

    public function configuracao_lp($id = 0) {

        // Valida o acesso a pagina
        $this->validar_acesso();

        
        if(!$this->model->verificar_permissao_admin('landingpage_config_lp')){
            return $this->red_pagina('admin/login',FALSE);
        }
        
        $this->load->library('Imgno_imagem', '', 'lib_imagem');

        if($post = $this->input->post()) {
            if(!empty($post['bigNumber'])){
                $post['bigNumber'] = json_encode($post['bigNumber'], JSON_UNESCAPED_UNICODE);
            }else{
                $post['bigNumber'] = "";
            }
            
            $resultado = $this->model->salvar_configuracao_lp($post);

            if($resultado['retorno'] == 1){
                $this->msg["ok"] = $resultado["msg"];  
                $this->red_pagina('landing-page/configuracao-lp/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
            }else{
                $this->msg["erro"] = $resultado["msg"];
                $this->dados["dados"]["resultado"] = $resultado['dados'];
            }
            
        }else{
            $this->dados["dados"]["resultado"] = $this->model->buscar_configuracao_lp($id); 
        }

		$this->dados["dados"]["copywriters"] = $this->model->buscar_copywriters(array('situacao'=>1));

     	$this->add_css(array(
			'style-admin',
			'selectize',
        ));
        
        $this->add_script(array(
			'inputmask',
			'selectize.min',
			'jquery-ui',
		));

        $this->dados["titulo"] = "Configurações da LP";

        $this->html_pagina('landing-page-configuracao-lp',array(
            'topo' => array(
                'admin-topo'
            ),
            'rodape' => array(
                'admin-rodape'
            )
        ));	
    }

    public function configuracao_excluir(){
        // Valida o acesso a pagina
        $this->validar_acesso();

        if(!$this->model->verificar_permissao_admin('landingpage_config_lp')){
            return $this->red_pagina('admin/login',FALSE);
        }


        if($post = $this->input->post()) {

            $this->model->excluir_configuracoes($post);
            $this->red_pagina('admin/landing-page/configuracoes-lp',FALSE,"#Excluídos com sucesso");
        }
    }

    public function repositorio_lp($id_config = 0) {
        // Valida o acesso a pagina
        $this->validar_acesso(); 

        $configuracao_lp = $this->model->buscar_configuracao_lp($id_config); 
        if(!$configuracao_lp) $this->red_pagina('landing-page/configuracoes-lp',null);

        if(!$this->model->verificar_permissao_admin('landingpage_repositorio_lp_geradas')){
            return $this->red_pagina('admin/login',FALSE);
        }

        $this->load->library('Imgno_util', '', 'ImgnoUtil');
        $strings_replace = $this->model->buscar_configuracao_lp_replace($id_config); 
        $alias_pre = $this->model->getAlias($this->ImgnoUtil->replace_tags("[Palavra-Chave-01]", $strings_replace));

        $this->add_css(array(
            'style-admin'
        ));

        $args = null;

        if($post = $this->input->post()) {
            $args = $post;
        }

        $this->dados["titulo"] = "Repositório de LP Geradas";
        $this->dados["dados"]["resultado"] = $this->model->buscar_estados_cidades($args);
        $this->dados["dados"]["configuracao_lp"] = $configuracao_lp; 
        $this->dados["dados"]["alias_pre"] = $alias_pre; 
        $this->dados["dados"]["args"] = $args;

        $this->html_pagina('landing-page-repositorio-lp-geradas',array(
            'topo' => array(
                'admin-topo'
            ),
            'rodape' => array(
                'admin-rodape'
            )
        ));
    }

    public function solicitacoes() {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('landingpage_solicitacoes')){
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

		$this->html_pagina('landing-page-solicitacoes',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function solicitacao($id = 0) {

		// Valida o acesso a pagina
		$this->validar_acesso();

		if(!$this->model->verificar_permissao_admin('landingpage_solicitacoes')){
			return $this->red_pagina('admin/login',FALSE);
		}

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_solicitacao($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('landing-page/solicitacao/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
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
		
		$this->dados["titulo"] = "Solicitação";

		$this->html_pagina('landing-page-solicitacao',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

    public function copywriters() {
       
		// Valida o acesso a pagina
		$this->validar_acesso();
		
		if(!$this->model->verificar_permissao_admin('landingpage_copywriters')){
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

		$this->dados["titulo"] = "Copywriters";
		$this->dados["dados"]["resultado"] = $this->model->buscar_copywriters($args);
		$this->dados["dados"]["args"] = $args;

		$this->html_pagina('landing-page-copywriters',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
    }

    public function copywriter($id = 0) {

		// Valida o acesso a pagina
        $this->validar_acesso();

        if(!$this->model->verificar_permissao_admin('landingpage_copywriters')){
			return $this->red_pagina('admin/login',FALSE);
		}

        $this->load->library('Imgno_imagem', '', 'lib_imagem');

		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_copywriter($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('landing-page/copywriter/'.$resultado['dados']['id'],null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_copywriter($id);
		}

		$this->add_css(array(
			'style-admin'
        ));
        
        $this->add_script(array(
			'inputmask'
		));
		
		$this->dados["titulo"] = "Copywriter";

		$this->html_pagina('landing-page-copywriter',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function copywriter_excluir(){
		// Valida o acesso a pagina
		$this->validar_acesso();
		
		if(!$this->model->verificar_permissao_admin('landingpage_copywriters')){
			return $this->red_pagina('admin/login',FALSE);
		}

		if($post = $this->input->post()) {

			$this->model->copywriter_excluir($post);
			$this->red_pagina('admin/landing-page/copywriters',FALSE,"#Exluídos com sucesso");
		}
	}

	public function copywriter_configuracao() {
		// Valida o acesso a pagina
		$this->validar_acesso(); 
		
		if(!$this->model->verificar_permissao_admin('landingpage_copywriter_configuracao')){
			return $this->red_pagina('admin/login',FALSE);
		} 
		
		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_copywriter_configuracao($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('landing-page/copywriter-configuracao',null,"#".$resultado["msg"]);
			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}else{
			$this->dados["dados"]["resultado"] = $this->model->buscar_copywriter_configuracao();
		}

		$this->add_css(array(
			'style-admin',
			'popup'
		));

		$this->add_script(array(
			'popup'
        ));
		
		$this->dados["titulo"] = "Configurações";

		$this->html_pagina('landing-page-copywriter-configuracao',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

    public function gerar_landing_pages($id_config = 0) {
        if(!$this->model->verificar_permissao_admin('landingpage_repositorio_lp_geradas')){
            return $this->red_pagina('admin/login',FALSE);
        }

        $strings_replace = $this->model->buscar_configuracao_lp_replace($id_config);
        if(!$strings_replace) $this->red_pagina('landing-page/configuracoes-lp',null);

        $this->load->library('Imgno_util', '', 'ImgnoUtil');

        $alias_pre = $this->model->getAlias($this->ImgnoUtil->replace_tags("[Palavra-Chave-01]", $strings_replace));

        // landingpage/Landingpage_controller_landingpage/lpCidade/$id_config/5
        // landingpage/Landingpage_controller_landingpage/lpEstado/$id_config/1   

        $args['situacao'] = 1;
        $dados = $this->model->buscar_estados_cidades($args);

        if(!file_exists(APPPATH .'config/routes-lp/')) mkdir(APPPATH .'config/routes-lp/', 0777, true);
        // Criar arquivo da LP
        $conteudo = "<?php\n";
        if($dados){
            foreach($dados as $dado){
                if($dado->categoria_pai){
                    $conteudo .= '$route["'.$alias_pre.'-'.$dado->alias.'(?:\.html)?"] = "landingpage/Landingpage_controller_landingpage/lpCidade/'.$id_config.'/'.$dado->id.'";'."\n";
                }else{
                    $conteudo .= '$route["'.$alias_pre.'-'.$dado->alias.'(?:\.html)?"] = "landingpage/Landingpage_controller_landingpage/lpEstado/'.$id_config.'/'.$dado->id.'";'."\n";
                }
            }
        }
        $conteudo .= "?>"; 
        $arquivo = fopen(APPPATH .'config/routes-lp/lp-'.$id_config.'.php', 'w');
        fwrite($arquivo, $conteudo);
        fclose($arquivo);

        // Criar arquivo conexao para obter os arquivos das LP
        $dados_config = $this->model->buscar_configuracoes_lp();
        $conteudo = "<?php\n";
        if($dados_config){
            foreach($dados_config as $dado){
                $conteudo .= 'if(file_exists(APPPATH."/config/routes-lp/lp-'.$dado->id.'.php")) include APPPATH."/config/routes-lp/lp-'.$dado->id.'.php";'."\n";
            }
        }
        $conteudo .= "?>"; 
        $arquivo = fopen(APPPATH .'config/routes-lp/lp-conexao.php', 'w');
        fwrite($arquivo, $conteudo);
        fclose($arquivo);

        // excluir seo
        $this->model->gerar_lp_seo_excluir($id_config); 

        $this->red_pagina('landing-page/repositorio-lp-geradas/'.$id_config,null,"#Gerado com sucesso");

        exit;
    }
}
?>