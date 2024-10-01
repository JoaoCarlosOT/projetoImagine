<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(APPPATH .'base/Base_modulo.php');

class Base_controller extends CI_Controller{
	public $msg =  null;
	/* HTML das páginas */
	public function html_pagina($pagina = NULL, $modulos_se = null, $instance = TRUE, $return_conteudo = FALSE) {

		// Config
		$html_pag = array();
		$html_pag['conteudo_2'] = '';
		
		if(!$pagina) $pagina = $this->pagina_inicial;
		
		// Nome da pagina
		$this->com_nome[4] = $pagina;
		
		// Carrega os módulos da página
		if($modulos_se) {
			$modulos = new Base_modulo();
			if($mds = $modulos->carregar_modulos($modulos_se)) {
				$html_pag['modulos'] = $mds;
			}
		}
		
		$this->criar_css_separados($modulos_se, $pagina);

		// Carrega o conteudo página
		$html_pag['conteudo_2'] = $this->load->view($this->local_pagina . $this->pasta_nome .'/'. (isset($this->com_nome[3]) ? $this->com_nome[3] .'/' : '') . $pagina .'.php', ($instance ? array('controller' => &$this) : NULL), TRUE);
		
		// HTML do conteúdo da página
		if($return_conteudo) return $html_pag['conteudo_2'];
		
		// HTML extra do Header
		if(isset($this->header)) $html_pag['header'] = $this->header;
		
		// HTML extra do Topo
		if(isset($this->topo)) $html_pag['topo'] = $this->topo;
		
		// HTML extra do rodape
		if(isset($this->rodape)) $html_pag['rodape'] = $this->rodape;
		
		// Carrega o corpo da página
		$this->load->view($this->t_pasta .'/pagina', $html_pag);
	}

	private function criar_css_separados($modulos, $pagina){
		if($this->pasta_nome == 'admin' || !$pagina) return;

		$dir_original_mod = FCPATH .'arquivos/css/views/modulos/';
		// $dir_comprimido_mod = FCPATH .'arquivos/css/views/modulos/min-auto/';
		if(!file_exists($dir_original_mod)) mkdir($dir_original_mod, 0777, true);

		$dir_original_pag = FCPATH .'arquivos/css/views/paginas/'.$this->pasta_nome.'/';
		$dir_comprimido_pag = FCPATH .'arquivos/css/views/paginas/'.$this->pasta_nome.'/min-auto/';
		if(!file_exists($dir_comprimido_pag)) mkdir($dir_comprimido_pag, 0777, true);

		// Arquivo comprimido 1
		$pagina_min_1 = $pagina.'_1.min.css';
		$dir_comprimido_pag_min_1 = $dir_comprimido_pag.$pagina_min_1;
		$juncoes_css_1 = array();

		// Arquivo comprimido 2
		$pagina_min_2 = $pagina.'_2.min.css';
		$dir_comprimido_pag_min_2 = $dir_comprimido_pag.$pagina_min_2;
		$juncoes_css_2 = array();

		// Parte daqui ser adicionado em comprimido 1
		if(!empty($modulos['topo'])){
			foreach($modulos['topo'] as $modulo){
				if(!file_exists($dir_original_mod.$modulo.'.css')){
					$modulo_css = fopen($dir_original_mod.$modulo.'.css', 'w');
					fclose($modulo_css);
				} 
				$juncoes_css_1[] = $dir_original_mod.$modulo.'.css';

				if(file_exists($dir_comprimido_pag_min_1) && filemtime($dir_original_mod.$modulo.'.css') > filemtime($dir_comprimido_pag_min_1)) {
					unlink($dir_comprimido_pag_min_1);
				}
			}
		} 

		if(!empty($modulos['banner'])){
			foreach($modulos['banner'] as $modulo){
				if(!file_exists($dir_original_mod.$modulo.'.css')){
					$modulo_css = fopen($dir_original_mod.$modulo.'.css', 'w');
					fclose($modulo_css);
				} 
				$juncoes_css_1[] = $dir_original_mod.$modulo.'.css';

				if(file_exists($dir_comprimido_pag_min_1) && filemtime($dir_original_mod.$modulo.'.css') > filemtime($dir_comprimido_pag_min_1)) {
					unlink($dir_comprimido_pag_min_1);
				}
			}
		}

		// Parte daqui ser adicionado em comprimido 2 exceto o primeiro modulo
		$exibir_modulo_css_1 = 0;
		if(!empty($modulos['posicao_1'])){
			foreach($modulos['posicao_1'] as $modulo){
				$exibir_modulo_css_1++;
				if(!file_exists($dir_original_mod.$modulo.'.css')){
					$modulo_css = fopen($dir_original_mod.$modulo.'.css', 'w');
					fclose($modulo_css);
				} 

				if($exibir_modulo_css_1 == 1){
					$juncoes_css_1[] = $dir_original_mod.$modulo.'.css';	
				}else{
					$juncoes_css_2[] = $dir_original_mod.$modulo.'.css';
				}

				if(file_exists($dir_comprimido_pag_min_1) && filemtime($dir_original_mod.$modulo.'.css') > filemtime($dir_comprimido_pag_min_1)) {
					unlink($dir_comprimido_pag_min_1);
				}
				if(file_exists($dir_comprimido_pag_min_2) && filemtime($dir_original_mod.$modulo.'.css') > filemtime($dir_comprimido_pag_min_2)) {
					unlink($dir_comprimido_pag_min_2);
				}
			}
		}

		if(!empty($modulos['posicao_2'])){
			foreach($modulos['posicao_2'] as $modulo){
				$exibir_modulo_css_1++;
				if(!file_exists($dir_original_mod.$modulo.'.css')){
					$modulo_css = fopen($dir_original_mod.$modulo.'.css', 'w');
					fclose($modulo_css);
				} 
				
				if($exibir_modulo_css_1 == 1){
					$juncoes_css_1[] = $dir_original_mod.$modulo.'.css';	
				}else{
					$juncoes_css_2[] = $dir_original_mod.$modulo.'.css';
				}

				if(file_exists($dir_comprimido_pag_min_1) && filemtime($dir_original_mod.$modulo.'.css') > filemtime($dir_comprimido_pag_min_1)) {
					unlink($dir_comprimido_pag_min_1);
				}
				if(file_exists($dir_comprimido_pag_min_2) && filemtime($dir_original_mod.$modulo.'.css') > filemtime($dir_comprimido_pag_min_2)) {
					unlink($dir_comprimido_pag_min_2);
				}
			}
		}

		if(!empty($modulos['conteudo_1'])){
			foreach($modulos['conteudo_1'] as $modulo){
				$exibir_modulo_css_1++;
				if(!file_exists($dir_original_mod.$modulo.'.css')){
					$modulo_css = fopen($dir_original_mod.$modulo.'.css', 'w');
					fclose($modulo_css);
				} 
				
				if($exibir_modulo_css_1 == 1){
					$juncoes_css_1[] = $dir_original_mod.$modulo.'.css';	
				}else{
					$juncoes_css_2[] = $dir_original_mod.$modulo.'.css';
				}

				if(file_exists($dir_comprimido_pag_min_1) && filemtime($dir_original_mod.$modulo.'.css') > filemtime($dir_comprimido_pag_min_1)) {
					unlink($dir_comprimido_pag_min_1);
				}
				if(file_exists($dir_comprimido_pag_min_2) && filemtime($dir_original_mod.$modulo.'.css') > filemtime($dir_comprimido_pag_min_2)) {
					unlink($dir_comprimido_pag_min_2);
				}
			}
		}
				
		// pagina conteudo 2
		$exibir_modulo_css_1++;
		if(!file_exists($dir_original_pag.$pagina.'.css')){
			$modulo_css = fopen($dir_original_pag.$pagina.'.css', 'w');
			fclose($modulo_css);
		} 

		if($exibir_modulo_css_1 == 1){
			$juncoes_css_1[] = $dir_original_pag.$pagina.'.css';
		}else{
			$juncoes_css_2[] = $dir_original_pag.$pagina.'.css';
		}

		if(file_exists($dir_comprimido_pag_min_1) && filemtime($dir_original_pag.$pagina.'.css') > filemtime($dir_comprimido_pag_min_1)) {
			unlink($dir_comprimido_pag_min_1);
		}
		if(file_exists($dir_comprimido_pag_min_2) && filemtime($dir_original_pag.$pagina.'.css') > filemtime($dir_comprimido_pag_min_2)) {
			unlink($dir_comprimido_pag_min_2);
		}

		if(!empty($modulos['conteudo_3'])){
			foreach($modulos['conteudo_3'] as $modulo){
				$exibir_modulo_css_1++;
				if(!file_exists($dir_original_mod.$modulo.'.css')){
					$modulo_css = fopen($dir_original_mod.$modulo.'.css', 'w');
					fclose($modulo_css);
				} 
				
				if($exibir_modulo_css_1 == 1){
					$juncoes_css_1[] = $dir_original_mod.$modulo.'.css';	
				}else{
					$juncoes_css_2[] = $dir_original_mod.$modulo.'.css';
				}

				if(file_exists($dir_comprimido_pag_min_1) && filemtime($dir_original_mod.$modulo.'.css') > filemtime($dir_comprimido_pag_min_1)) {
					unlink($dir_comprimido_pag_min_1);
				}
				if(file_exists($dir_comprimido_pag_min_2) && filemtime($dir_original_mod.$modulo.'.css') > filemtime($dir_comprimido_pag_min_2)) {
					unlink($dir_comprimido_pag_min_2);
				}
			}
		}

		if(!empty($modulos['rodape'])){
			foreach($modulos['rodape'] as $modulo){
				$exibir_modulo_css_1++;
				if(!file_exists($dir_original_mod.$modulo.'.css')){
					$modulo_css = fopen($dir_original_mod.$modulo.'.css', 'w');
					fclose($modulo_css);
				} 
				
				if($exibir_modulo_css_1 == 1){
					$juncoes_css_1[] = $dir_original_mod.$modulo.'.css';	
				}else{
					$juncoes_css_2[] = $dir_original_mod.$modulo.'.css';
				}

				if(file_exists($dir_comprimido_pag_min_1) && filemtime($dir_original_mod.$modulo.'.css') > filemtime($dir_comprimido_pag_min_1)) {
					unlink($dir_comprimido_pag_min_1);
				}
				if(file_exists($dir_comprimido_pag_min_2) && filemtime($dir_original_mod.$modulo.'.css') > filemtime($dir_comprimido_pag_min_2)) {
					unlink($dir_comprimido_pag_min_2);
				}
			}
		} 

		// Criar Arquivo comprimido 1
		if(!file_exists($dir_comprimido_pag_min_1)){
		 	$css_f = fopen($dir_comprimido_pag_min_1, 'w');
			$css_script_original = ''; 
			$juncoes_css_1 = array_unique($juncoes_css_1);
			foreach($juncoes_css_1 as $juncao){
				$css_script_original .= file_get_contents($juncao);
			}
			// escrita na copia comprimidas
			fwrite($css_f, $this->comprimir_css($css_script_original));
		 	fclose($css_f);
		}
		$to_add_1[$pagina_min_1] = '<link href="'.base_url().'arquivos/css/views/paginas/'.$this->pasta_nome.'/min-auto/'.$pagina_min_1.'?'.( filemtime($dir_comprimido_pag_min_1) ).'" rel="stylesheet" type="text/css"></link>';
		$this->header['css'] = array_merge($this->header['css'], $to_add_1);

		// Criar Arquivo comprimido 2
		if(!file_exists($dir_comprimido_pag_min_2)){
			$css_f = fopen($dir_comprimido_pag_min_2, 'w');
			$css_script_original = ''; 
			$juncoes_css_2 = array_unique($juncoes_css_2);
			foreach($juncoes_css_2 as $juncao){
				$css_script_original .= file_get_contents($juncao);
			}
			// escrita na copia comprimidas
			fwrite($css_f, $this->comprimir_css($css_script_original));
			fclose($css_f);
	   	}
	   	$to_add_2[$pagina_min_2] = '<link href="'.base_url().'arquivos/css/views/paginas/'.$this->pasta_nome.'/min-auto/'.$pagina_min_2.'?'.( filemtime($dir_comprimido_pag_min_2) ).'" rel="stylesheet" type="text/css"></link>';
		$this->header['css_fim'] = array_merge($this->header['css_fim'], $to_add_2);
	}
	
	// Redirecionamento de página
	public function red_pagina($pagina = NULL, $pasta_nome = NULL, $dados_url = NULL){
		// if(!$pagina) $pagina = $this->pagina_inicial;
		if(!$pasta_nome && $pasta_nome !== FALSE) $pasta_nome = $this->pasta_nome;
		if(!$pagina) return redirect(($pasta_nome? $pasta_nome .'/' : '/').($dados_url != NULL?'?'.$dados_url:''));
		return redirect(($pasta_nome? $pasta_nome .'/' : '') . $pagina .'.html'.($dados_url != NULL?'?'.$dados_url:''));
	}

	// Url da página
	public function url_pagina($pagina = NULL, $pasta_nome = NULL){
		if(!$pagina) $pagina = $this->pagina_inicial;
		if(!$pasta_nome && $pasta_nome !== FALSE) $pasta_nome = $this->pasta_nome[2];
		return base_url(($pasta_nome? $pasta_nome .'/' : '') . $pagina .'.html');
	}	
	
	// Scripts da página
	public function add_script($scripts = NULL, $externo = FALSE) {
		if(!isset($this->header['javascript'])) $this->header['javascript'] = array();
		if(!$scripts) return FALSE;
		$to_add = array();
		if($externo && is_array($scripts)){
			foreach($scripts as $k => $script) $to_add[$script] = '<script src="'. $script .'" type="text/javascript"></script>';
		} elseif(!$externo && is_array($scripts)) {
			foreach($scripts as $k => $script) $to_add[$script] = '<script src="'. base_url() .'arquivos/javascript/'. $script .'.js" type="text/javascript"></script>';
		} elseif($externo && !is_array($scripts)) {
			$to_add = array($scripts => '<script src="'. $scripts .'" type="text/javascript"></script>');
		} else {
			$to_add = array($scripts => '<script src="'. base_url() .'arquivos/javascript/'. $scripts .'.js" type="text/javascript"></script>');
		}
		
		$this->header['javascript'] = array_merge($this->header['javascript'], $to_add);
	}
	
	// CSS da página
	public function add_css($arquivos = NULL, $externo = FALSE, $css_posicao = 1) {
		if(!isset($this->header['css'])) $this->header['css'] = array();
		if(!isset($this->header['css_fim'])) $this->header['css_fim'] = array();

		if(!$arquivos) return FALSE;

		$to_add = array();
		if($externo && is_array($arquivos)) {
			foreach($arquivos as $k => $arquivo) {
				$to_add[$arquivo] = '<link href="'. $arquivo .'" rel="stylesheet" type="text/css"></link>';
			}
		}elseif($externo && !is_array($arquivos)) {
			$to_add = array($arquivos => '<link href="'. $arquivos .'" rel="stylesheet" type="text/css"></link>');
		}elseif(!$externo && is_array($arquivos)) {
			if($this->pasta_nome == 'admin'){
				// admin
				foreach($arquivos as $k => $arquivo) {
					if(!file_exists(FCPATH.'arquivos/css/'.$arquivo.'.css')) continue;

					$to_add[$arquivo] = '<link href="'.base_url().'arquivos/css/'.$arquivo.'.css?'.( filemtime(FCPATH.'arquivos/css/'.$arquivo.'.css') ).'" rel="stylesheet" type="text/css"></link>';
				}
			}else{
				// site e outros
				foreach($arquivos as $k => $arquivo) {
					if(!file_exists(FCPATH.'arquivos/css/'.$arquivo.'.css')) continue;

					$dir_e_arquivo_utilizar = $this->criar_arquivo_comprimido_css($arquivo);
					if($dir_e_arquivo_utilizar){
						$to_add[$arquivo] = '<link href="'.base_url().$dir_e_arquivo_utilizar.'?'.( filemtime(FCPATH.'arquivos/css/'.$arquivo.'.css') ).'" rel="stylesheet" type="text/css"></link>';
					}
				}
			}

		}else {
			if($this->pasta_nome == 'admin'){
				// admin
				$to_add[$arquivos] = '<link href="'.base_url().'arquivos/css/'.$arquivos.'.css?'.( filemtime(FCPATH.'arquivos/css/'.$arquivos.'.css') ).'" rel="stylesheet" type="text/css"></link>';

			}else{
				// site e outros
				$dir_e_arquivo_utilizar = $this->criar_arquivo_comprimido_css($arquivos);
				if($dir_e_arquivo_utilizar){
					$to_add[$arquivos] = '<link href="'.base_url().$dir_e_arquivo_utilizar.'?'.( filemtime(FCPATH.'arquivos/css/'.$arquivos.'.css') ).'" rel="stylesheet" type="text/css"></link>';
				}
			}
		}
		
		if($css_posicao == 2){
			$this->header['css_fim'] = array_merge($this->header['css_fim'], $to_add);
		}else{
			$this->header['css'] = array_merge($this->header['css'], $to_add);
		}
	}
	
	public function remove_css($remover = NULL) {
		if(!$remover) return NULL;
		if(is_array($remover)) {
			foreach($remover as $r) if(isset($this->header['css'][$r])) unset($this->header['css'][$r]);
		} else if(isset($this->header['css'][$remover])) unset($this->header['css'][$remover]);
	} 

	function criar_arquivo_comprimido_css($nome_arquivo_original,$rota_extra='',$retorno_url = false) {
		// obrigatoriamente o arquivo original css precisa ja esta no diretorio arquivos/css
		$dir_original = 'arquivos/css/'.$rota_extra;
		$dir_comprimido = 'arquivos/css/min-auto/';

		if(!file_exists(FCPATH.$dir_original.$nome_arquivo_original.'.css')) return null;

		$dir_comp = dirname(FCPATH.$dir_comprimido.$nome_arquivo_original.'.min.css');
		if(!file_exists($dir_comp)) {
			mkdir($dir_comp, 0777, true);
		};

		// nome arquivo comprimido
		// $nome_arquivo_min = $nome_arquivo_original.'-'.filemtime(FCPATH.$dir_original.$nome_arquivo_original.'.css').'.min';
		$nome_arquivo_min = $nome_arquivo_original.'.min'; 
		
		// tentar gerar um arquivo comprimido de css com base no original
		if( !file_exists(FCPATH.$dir_comprimido.$nome_arquivo_min.'.css') || filemtime(FCPATH.$dir_comprimido.$nome_arquivo_min.'.css') < filemtime(FCPATH.$dir_original.$nome_arquivo_original.'.css')){
			// criar um arquivo para receber o css comprimido
			$arquivo_min = fopen(FCPATH.$dir_comprimido.$nome_arquivo_min.'.css', 'w');
			if( file_exists(FCPATH.$dir_comprimido.$nome_arquivo_min.'.css') ){
				// script do css original
				$css_script_original = file_get_contents(FCPATH.$dir_original.$nome_arquivo_original.'.css');

				// escrita na copia comprimidas
				fwrite($arquivo_min, $this->comprimir_css($css_script_original));
				fclose($arquivo_min);

				return ($retorno_url == true?base_url():'').$dir_comprimido.$nome_arquivo_min.'.css';
			}else{
				// caos nao consiga criar a copia, retorna o arquivo original
				return ($retorno_url == true?base_url():'').$dir_original.$nome_arquivo_original.'.css';
			}
		}else{
			return ($retorno_url == true?base_url():'').$dir_comprimido.$nome_arquivo_min.'.css';
		}
	}

	function comprimir_css($css) {
		// Remover comentarios
		$css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
		// Remover Tabs e NewLines
		$css = preg_replace('#(\r|\n|\t)#', '', $css);
		// Remover caracteres com espaços extras
		$css = preg_replace('#[ ]*([,:;\{\}])[ ]*#', '$1', $css);
		// Extras
		$css = strtr($css, array(
			';}' => '}'
		));
		return $css;
	}
	
	public function remove_script($remover = NULL) {
		if(!$remover) return NULL;
		if(is_array($remover)) {
			foreach($remover as $r) if(isset($this->header['javascript'][$r])) unset($this->header['javascript'][$r]);
		} else if(isset($this->header['javascript'][$remover])) unset($this->header['javascript'][$remover]);
	}
	
	public function carregar_editor() {
		// Scripts
		$this->remove_script(array('jquery/js/jquery-modif-1.11.1.min', 'jquery/js/jquery-ui-modif-1.10.4.custom.min', 'validacoes', 'funcoes', 'carregando', 'lista'));
		$this->add_script(array(base_url() . 'arquivos/javascript/jquery2/jquery.1.7.2.min.js', base_url() . 'arquivos/javascript/jquery2/jquery-ui.1.8.21.min.js'), TRUE);
		$this->add_script(array('validacoes', 'funcoes', 'carregando', 'lista', 'elrte/js/elrte.min', 'elrte/js/i18n/elrte.pt_BR', 'elfinder/js/elfinder.min', 'elfinder/js/i18n/elfinder.pt_BR'));
		
		// CSS
		$this->remove_css(array(base_url() .'arquivos/javascript/jquery/css/smoothness/jquery-ui-1.10.4.custom.min.css'));
		$this->add_css(array(
			base_url() .'arquivos/javascript/jquery2/jquery-ui.1.8.21.css',
			base_url() . 'arquivos/javascript/elrte/css/elrte.min.css', 
			base_url() . 'arquivos/javascript/elfinder/css/elfinder.min.css'
		), TRUE);
	}
	
	public function mensagem_aviso($msg = NULL, $exibir = FALSE, $local = 'mensagem') {
		if($exibir) { $this->util->dump($msg); }
		if($msg) {
			// Salva os dados
			if($serial = serialize($msg)) return $this->model->salvar_sessao($local, $serial);
		} else {
			// Obtem os dados armazenados
			$data = unserialize($this->model->buscar_sessao($local));
			
			// Remove os dados desnecessários
			$this->model->remover_sessao($local);
			
			return $data;
		}
	}
	
	public function set_dados_pagina($dados_pagina) {
		$dados_req = array();
		if(isset($dados_pagina['segmentos_nomeados_pagina'])) $dados_req = array_merge($dados_req, $this->get_request_from_url($dados_pagina['segmentos_nomeados_pagina'], $dados_pagina['argumentos_pagina']));
		if(isset($dados_pagina['limite_paginacao'])) $this->model->dados_req['limit'] = $dados_req['limit'] = $dados_pagina['limite_paginacao'];
		if(isset($dados_pagina['prefixo_pagina'])) {
			// Seta o prefixo da página
			$this->prefixo_pagina = $dados_pagina['prefixo_pagina'];
			
			if(isset($dados_pagina['ordenamento_pagina'])) {
				$dados_req = array_merge($dados_req, $this->get_posted_userdata(array_keys($dados_pagina['ordenamento_pagina']), $dados_pagina['prefixo_pagina'], $dados_pagina['ordenamento_pagina']));
				
				// Salva os valores de ordenamento na variavel de dados
				$this->model->dados_req['novo_ordenamento_ordem'] = $dados_req['ordenamento_ordem'] == 'DESC' ? 'ASC' : 'DESC';
				$this->model->dados_req['seta'] = array($dados_req['ordenamento_tipo'] => $dados_req['ordenamento_ordem'] == 'DESC' ? '▼' : '▲');
			}
			if(isset($dados_pagina['post_pagina'])) {
				$dados_req = array_merge($dados_req, $this->get_posted_userdata($dados_pagina['post_pagina'], $dados_pagina['prefixo_pagina']));
			}
			
			if(!empty($dados_pagina['post_redirect']) && $this->input->post()) redirect($dados_pagina['post_redirect']);
		}

		return $dados_req;
	}
	
	public function get_posted_userdata($chaves, $prefixo = '', $default = array()) {
		// Dados da busca
		$chs = array();
		foreach($chaves as $k => $c) {
			$chs[$prefixo .'_'. (is_numeric($k) ? $c : $k)] = $c;
		}
		
		$dados = array();
		if($ds = $this->get_userdata_from_request($chs, $default)) {
			foreach($ds as $k => $d) $this->model->dados_req[$k] = $dados[$k] = $d;
		}
		
		return $dados;
	}
	
	public function get_userdata_from_request($data, $default = array()) {
		// Remove os dados antigos para retornar as páginas não acessadas ao estado inicial e diminuir o consumo de memória
		$this->remove_old_userdata();
	
		$resultado = array();
		$post = $this->input->post();
		if($post) {
			foreach($data as $k => $d) {
				$this->model->salvar_sessao($k, $r = isset($post[$d]) ? $post[$d] : (isset($default[$d]) ? $default[$d] : NULL));
			}
		} elseif($default) {
			foreach($data as $k => $d) {
				if(!$this->model->buscar_sessao($k)) $this->model->salvar_sessao($k, $r = isset($default[$d]) ? $default[$d] : NULL);
			}
		}
		foreach($data as $k => $d) $resultado[$d] = $this->model->buscar_sessao($k);
		
		return $resultado;
	}
	
	public function remove_old_userdata() {
		if($previous = $this->model->buscar_toda_sessao()) {
			foreach($previous as $k => $p) {
				$var[$k] = strpos($k, $this->com_classe);
				if(strpos($k, $this->com_classe) !== FALSE && strpos($k, $this->prefixo_pagina) === FALSE) $this->model->remover_sessao($k);
			}
		}
	}
	
	public function no_cache() {
        $this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
        $this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
    }
	
	public function redirect_request_to_url($chaves = array(), $pagina = array()) {
		if($post = $this->input->post()) {
			$url = array();
			$post['start'] = $this->page_start;
			foreach($chaves as $k => $c) {
				if($post[$c]) {
					$url[] = $this->util->url_slug($c);
					$url[] = $this->util->url_slug($post[$c]);
				}
			}
			$this->red_pagina($pagina[0] . implode('/', $url), $pagina[1]);
		}
	}
	
	public function get_request_from_url($segments, $args) {
		$request = array();

		foreach($segments as $k => $seg) {
			if(($key = array_search($k, $args, TRUE)) !== FALSE && isset($args[$key+1])) $this->model->dados_req[$seg[0]] = $request[$seg[0]] = $args[$key+1];
			else $this->model->dados_req[$seg[0]] = $request[$seg[0]] = $seg[1];
		}

		return $request;
	}
	
	public function _classes_pagina() {
		return 'com_'. $this->com_nome[0] ."_". $this->com_nome[2] .' '. $this->com_nome[4];
	}
	
	public function gerar_paginacao($link = NULL, $info_add = NULL) {
		if(!$link) return NULL;
		if($this->dados['paginacao_total'] > $this->dados['paginacao_limite']) {
			$this->load->library('Imgno_paginacao', '', 'paginacao');
			return $this->paginacao->links($link, $this->dados['paginacao_total'], $this->dados['paginacao_limite'], $this->dados['paginacao_start'] / $this->dados['paginacao_limite'], $info_add);
		}
		else return NULL;
	}
	
	public function validar_acesso_usuario($acesso = null){
		if(!$this->model->logado('usuario')) $this->red_pagina('login', FALSE);
		if(!empty($acesso)){
			if($acesso == 'admin'){
				if(!$this->model->admin && $this->model->usuario_pai) $this->red_pagina('usuario/inicio/vau', FALSE);
			}elseif(!$this->model->validar_acesso_usuario($acesso)){
				if($this->model->usuario_bloqueado) $this->red_pagina('usuario/inicio/vau2', FALSE);
				$this->red_pagina('usuario/inicio/vau', FALSE);
			}
		}
	}
	
	function set_var($campo, $item, $padrao = ''){
		return ($n = $this->input->post($campo)) ? $n : (!empty($item->{$campo})? $item->{$campo} : $padrao);
	}
	
	public function __construct($config = array()) {
		// Construtor da classe pai
		parent::__construct();
		
		$idioma = 'portuguese';
		// if(isset($_COOKIE['idioma'])){
		// 	$idioma = $_COOKIE['idioma'];
		// }
		
		$en = $this->input->get('lang');
		if(isset($en)){
			switch($en):
				case 'pt':
					$idioma = 'portuguese';
				break;
				case 'en':
					$idioma = 'english';
				break;
			endswitch;
		}
		setcookie("idioma", $idioma, time()+(24 * 60 * 60));  
		$this->lang->load(array('inicial'),$idioma);
		
		$padrao = array(
			'load' => array(
				array('library', 'session', NULL, NULL),
				array('helper', 'url', NULL, NULL),
				array('library', 'Imgno_util', NULL, 'util')
			),
			'model' => TRUE
		);
		$config = array_merge($padrao, $config);
		
		// Utilidades
		foreach($config['load'] as $val) $this->load->{$val[0]}($val[1], $val[2], $val[3]);
		
		// Pasta padrão dos templates do site
		$this->t_pasta = 'templates';
		
		// Pasta padrão dos módulos do site
		$this->m_pasta = 'modulos/';
		
		// Pasta padrão de imagens
		$this->ftp_imagens = APPPATH .'arquivos/imagens/';
		$this->url_imagens = base_url() .'arquivos/imagens/';
		$this->favicon = !(isset($config['favicon']) && $config['favicon'] === FALSE) && file_exists(realpath('arquivos') . '/favicon.ico') ? '<link type="image/vnd.microsoft.icon" rel="shortcut icon" href="'. base_url() .'arquivos/favicon.ico">' : NULL;

		// Local das views
		$this->local_pagina = 'paginas/';
		
		// Nome do controller
		$this->com_classe = get_class($this);
		$this->com_nome = !isset($config['com_classe']) ? explode('_', $this->com_classe) : explode('_', $config['com_classe']);
		$this->pasta_nome = strtolower($this->com_nome[0]);
		// Model do controller atual
		if($config['model']) $this->load->model( $this->pasta_nome .'/'. $this->com_nome[0]."_model_".$this->com_nome[2], 'model');
		
		// $this->usuario_bloqueado = $this->model->usuario_bloqueado;
	}
}

/* Fim do arquivo Base_controller.php */
/* Local: ./application/base/Base_controller.php */