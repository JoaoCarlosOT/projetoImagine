<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Site_controller_paginas extends Base_controller {
	
	public function inicio()
	{
		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Início';

		$this->add_css(array(
			'template_1',
			'carousel',
		));

		$this->add_css(array(
			// 'template_2',
		),false,2);

        $this->add_script(array(
			'carousel'
		));
		
		$this->dados['banner']['id_banner'] = 1;
		$this->dados['copywriter']['ids'] = array(1,3);

		// $this->dados["dados"]["resultado"] = $this->model->getProdutos(null, null, array('destaque'=>true,'ativarVenda'=>false));
		// $this->dados["dados"]["resultado2"] = $this->model->getProdutos(null, null, array('destaque'=>true,'ativarVenda'=>true));
		// $this->dados["dados"]["categorias"] = $this->model->getCategoriasFilhas(4);
		
		$this->load->library('Imgno_imagem', '', 'imagineImagem');

		$this->html_pagina('inicio',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				// 'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			// 'banner' => array('banner'),
			'conteudo_1' => array(
				'copywriter-1',
				'big-numbers2',
				// 'modulo-13',
				'copywriter-2',
				'copywriter-3',
				// 'backaground-fundo-form',
			),
			'conteudo_3' => array(
				// 'servicos',
				// 'backaground-fundo',
				// 'galeria-1',
				// 'mapa',
				// 'servicos-4',
				// 'servicos-2',
				// 'servicos-3',
				// 'depoimentos-1',
				// 'formulario-solicitacoes',
				'big-numbers',
				'faq',
				// 'blog',
				// 'blog-2',
				// 'depoimentos-2',
				// 'depoimentos-3',
				// 'modulo-14',
				// 'backaground-fundo-2',
				// 'modulo-13',
				'big-numbers3',
				'galeria-1',
				'galeria-1',
			),
			'rodape' => array(
				// 'seguir-facebook',
				// 'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	} 

	public function quem_somos()
	{ 

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Quem somos';

		$this->add_css(array(
			'template_1',
			'carousel'
		));

        $this->add_script(array(
			'carousel'
		));

		$this->dados['banner']['id_banner'] = 8;
		$this->dados['copywriter']['ids'] = array(4,5);

		$this->html_pagina('quem-somos',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'banner' => array(
				'banner'
			),
			'posicao_1' => array(
				'copywriter-1'
			),
			'conteudo_3' => array(
				'copywriter-1',
				'big-numbers',
				'servicos-2',
				'blog-2',
			),
			'rodape' => array(
				'mapa',
				'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	}

	public function faq()
	{

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Faq';

		$this->add_css(array(
			'template_1',
			'carousel'
		)); 

        $this->add_script(array(
			'carousel'
		));

		$this->dados['banner']['id_banner'] = 6;
		$this->html_pagina('faq',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'banner' => array('banner'),
			'conteudo_3' => array(
			),
			'rodape' => array(
				'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	}

	public function servicos($alias_categoria = null)
	{

		if($alias_categoria){
			$categoria = $this->model->getCategoriaProduto($alias_categoria);
			if(!$categoria) $this->red_pagina('erro');
			$categoria_id = $categoria->id;
		}else{
			$categoria = null;
			$categoria_id = null;
		} 

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = $categoria?$categoria->nome:'Servicos';

		$this->add_css(array(
			'template_1',
			'carousel'
		));

		$this->add_css(array(
			'template_2',
		),false,2);


        $this->add_script(array(
			'carousel'
		));
				
		$this->dados["dados"]["resultado"] = $this->model->getProdutos($categoria_id);
		// $this->dados["dados"]["categorias"] = $this->model->getCategoriasFilhas(4);
		$this->load->library('Imgno_imagem', '', 'imagineImagem');

		$this->dados['banner']['id_banner'] = 3;

		$this->html_pagina('servicos',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'banner' => array('banner'),
			'conteudo_3' => array(
			),
			'rodape' => array(
				'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	}

	public function servico($alias)
	{
		if($post = $this->input->post()) {
			$this->red_pagina('checkout-email',false);
		}

		$dados_result = $this->model->getProduto($alias);

		if(!$dados_result){
			// show_404();
			$this->red_pagina('erro');
		}
		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = $dados_result->nome;
		$this->dados['descricao'] = mb_strimwidth(strip_tags ($dados_result->descricao), 0, 220, "...");

		$this->dados["dados"]["relacionados"] = $this->model->getProdutosRelacionados($dados_result->id);
		$this->dados["dados"]["resultado"] = $dados_result;
		$this->load->library('Imgno_imagem', '', 'imagineImagem');

		// Copywriters 
		$copywriters = json_decode($dados_result->copywriters, true);
		if(!$copywriters) $copywriters = array();
		$this->dados['copywriter']['ids'] = $copywriters;

		$this->dados['banner']['endereco_completo'] = $dados_result->banner?"/arquivos/imagens/produto/banner/".$dados_result->banner:"";

		$this->add_css(array(
			'template_1',
			'carousel',
		));

		$this->add_css(array(
			'template_2',
		),false,2);

		$this->add_script(array(
			'carousel',
			'jquery.zoom.min'
		));

		$conteudo_3 = array();
		for ($i = 1; $i < count($copywriters); $i++) {
			$conteudo_3[] = 'copywriter-1';
		}
		$conteudo_3[] = 'copywriter-1';
		$conteudo_3[] = 'depoimentos-1';

		$this->html_pagina('servico',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'banner' => array('banner-unico'),
			'conteudo_3' => $conteudo_3,
			'rodape' => array(
				'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	}

	public function profissionais()
	{

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'profissionais';

		$this->dados["dados"]["resultado"] = $this->model->getProfissionais();
		
		$this->load->library('Imgno_imagem', '', 'imagineImagem');

		
		$this->add_css(array(
			'template_1',
			'carousel',
			'popup'
		));

		$this->add_css(array(
			'template_2',
		),false,2);

        $this->add_script(array(
			'carousel',
			'popup'
		));

		$this->dados['banner']['id_banner'] = 4;
		$this->html_pagina('profissionais',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'banner' => array(
				'banner'
			),
			'conteudo_3' => array(
				'blog'
			),
			'rodape' => array(
				'mapa',
				'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	}

	public function blog()
	{

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Blog';

		$this->add_css(array(
			'template_1',
			'carousel',
			'views/modulos/blog-2',
		));

		// $this->add_css(array(
		// 	'template_2',
		// ),false,2);

        $this->add_script(array(
			'carousel'
		));
		
		$id_categoria = ($this->input->get('id_categoria')?$this->input->get('id_categoria'):FALSE);

		$this->dados["paginacao_limite"] = $pag['limit'] =  9;
		$this->dados['paginacao_start'] = $pag['start'] = ($this->input->get('pag')?$this->input->get('pag'):0);
		$retorno = $this->model->getUltimosArtigos(null,$pag,$id_categoria);

		$this->dados["dados"]["resultado"] = $retorno['dados'];
		$this->dados['paginacao_total'] = $retorno['dados_total'];

		$this->load->library('Imgno_imagem', '', 'imagineImagem');

		$this->dados['banner']['id_banner'] = 5;

		$this->html_pagina('blog',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'banner' => array(
				'banner'
			),
			'conteudo_3' => array(
			),
			'rodape' => array(
				'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	}

	public function blog_artigo($alias)
	{

		$dados_result = $this->model->getArtigo($alias);

		if(!$dados_result){
			$this->red_pagina('erro');
		}
		
		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = $dados_result->titulo;
		$this->dados['descricao'] = mb_strimwidth(strip_tags ($dados_result->descricao), 0, 220, "...");
		$this->dados["dados"]["resultado"] = $dados_result;
		$this->dados["dados"]["artigo_categorias"] = $this->model->buscar_artigo_categorias();

		$this->load->library('Imgno_imagem', '', 'imagineImagem');
		if($dados_result->imagem) $this->dados['og_image'] = $this->imagineImagem->otimizar('/arquivos/imagens/blog/'.$dados_result->imagem, 200, 200, false, true, 80);
		
		$this->add_css(array(
			'template_1',
			'carousel'
		));

		$this->add_css(array(
			// 'template_2',
			'views/modulos/servicos-2',
		),false,2);
		
		$this->add_script(array(
			'carousel'
		));

		$this->html_pagina('blog-artigo',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'conteudo_3' => array(
				// 'servicos',
				// 'servicos-2',
				// 'servicos-3',
				'formulario-solicitacoes',
			),
			'rodape' => array(
				'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	}

	function contato(){
		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Contato';

		if($post = $this->input->post()){
			
			// $validarRe = $this->validarRecaptcha($post);

			
			$validarRe = $this->validarRecaptchaCalc($post);

			if ($validarRe == false){

				$this->msg["erro"] = "#Marque o ReCaptcha para confirmar que você não é um robô";  
				$this->red_pagina('contato',FALSE,"#erroMarque o ReCaptcha para confirmar que você não é um robô");

			}else{

				$resultado = $this->model->salvar_contato($post);
				
				if(!$resultado){
					$this->msg["erro"] = "#Preencha os campos corretamente!";  
					$this->red_pagina('contato',FALSE,"#erroPreencha os campos corretamente!");
				}else{
					$this->msg["ok"] = "#Enviado com sucesso!";  
					$this->red_pagina('contato',FALSE,"#Enviado com sucesso!");
				}

			}
		}
		$this->add_css(array(
			'template_1',
			'carousel',
			// 'contato',
		)); 

        $this->add_script(array(
			'carousel'
		));

		$this->dados['banner']['id_banner'] = 7;

		$this->load->model('modulos/modulos_model_config', 'model_config');

		$this->html_pagina('contato',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'banner' => array(
				'banner'
			),
			'conteudo_3' => array(
				// 'mapa',
			),
			'rodape' => array(
				'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	}

	function trabalhe_conosco(){
		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Trabalhe conosco';

		if($post = $this->input->post()){

			$validarRe = $this->validarRecaptchaCalc($post);

			if ($validarRe == false){

				$this->msg["erro"] = "#Marque o ReCaptcha para confirmar que você não é um robô";  
				$this->red_pagina('trabalhe-conosco',FALSE,"#erroMarque o ReCaptcha para confirmar que você não é um robô");

			}else{

				$resultado = $this->model->salvar_trabalhe($post);
	
				$this->msg["ok"] = "#Enviado com sucesso!";  
				$this->red_pagina('trabalhe-conosco',FALSE,"#Enviado com sucesso!");

			}
		}
		$this->add_css(array(
			'template_1',
			'carousel',
			// 'contato'
		)); 

        $this->add_script(array(
			'carousel'
		));

		$this->dados['banner']['id_banner'] = 9;

		$this->html_pagina('trabalhe-conosco',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'banner' => array(
				'banner'
			),
			'rodape' => array(
				'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	}

	function validarRecaptcha($post){

		$secret = $this->model->db->query('SELECT recaptcha_secret FROM '.$this->model->prefixo_db.'contato_config WHERE id = 1')->row('recaptcha_secret');
		
		// $response = null;
		// include_once('/home/faculcesaedubr/public_html/application/libraries/ReCaptcha.php');
		// $reCaptcha = new ReCaptcha($secret);

		// if ($_POST["g-recaptcha-response"]) {
		// 	$response = $reCaptcha->verifyResponse($_SERVER["REMOTE_ADDR"], $_POST["g-recaptcha-response"]);
		// }

		// if ($response == null || !isset($response->success) || !$response->success){
		// 	return false;
		// }

		return true;

	}
	
	
	function validarRecaptchaCalc($post){

		if(!isset($post['recap_n1']) || !isset($post['recap_n2']) || !isset($post['recap_sinal'])){ return false;}

		eval("\$result_back = ".$post['recap_n1'].$post['recap_sinal'].$post['recap_n2'].";");

		if($result_back != $post['recap']){return false;}

		return true;
	}

	function cadastrar_boletim(){
		$post = $this->input->post();

		if(!$post){
			exit;
		}

		$dados = $this->model->cadastrar_boletim($post);

        // header('Content-Type: application/json;charset=utf-8');
		// echo json_encode($dados);
		
		echo 'ok';
		exit;
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

	function salvar_contato(){
		$post = $this->input->post();

		if(!$post){
			exit;
		}

		
		$validarRe = $this->validarRecaptchaCalc($post);

		if ($validarRe == false){
			echo 'erro';
			exit;
		}

		$dados = $this->model->salvar_contato($post);

		echo ($dados?'ok':'erro');
		exit;
	}

	public function posts_instagram_imagens(){
		
		if($post = $this->input->post()) {

			$insta_config = $this->model->db->query('SELECT * FROM '.$this->model->prefixo_db.'instagram_config WHERE id = 1')->row_array();
			if( strtotime($insta_config['atualizado']) < strtotime( date('Y-m-d') )){

				if(isset($post['posts_instagram']) && $post['posts_instagram']){
					
					$limite = (isset($post['limite'])? $post['limite'] : 8);

					 $ch = curl_init('https://www.imaginecomunicacao.com.br/index.php?option=com_imaginecontato&view=contato&layout=get_instagram&username='.$post['posts_instagram'].'&limite='.$limite);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$medias = json_decode(curl_exec($ch));

					if($medias){
						$this->load->library('Imgno_imagem', '', 'imagineImagem');
						
						//Deletar imagens no depositório
						foreach(glob(FCPATH . 'arquivos/imagens/instagram/*') as $arquivo) {
							if (!is_dir($arquivo)) {
								unlink($arquivo);
							}
						}

						//deletar todas a linha da tabela após deletar imagens no depositório
						$this->model->remover_dados_tabela(
							array(
								'_tabela' => 'instagram_imagem',
								'where' => 'id != 0'
							)
						);

						$i = 0;

						foreach($medias->data as $media){

							if($media->media_type == "VIDEO"){$url = $media->thumbnail_url;}else{$url = $media->media_url;}
							$media->caption = (isset($media->caption)?$media->caption:'');

							$nome_imagem_i = 'instagrams_'.$i.'_'.rand(1,1000).'.jpg';

							//Adicionar imagem no repositório
							/*$ch = curl_init($url);
							$fp = fopen('/arquivos/imagens/instagram/'.$nome_imagem_i, 'wb');
							curl_setopt($ch, CURLOPT_FILE, $fp);
							curl_setopt($ch, CURLOPT_HEADER, 0);
							curl_exec($ch);
							curl_close($ch);
							fclose($fp);*/

							//$this->imagineImagem->otimizarFacebook($url,'arquivos/imagens/instagram/'.$nome_imagem_i,240,240,false,true,100);
							$this->imagineImagem->otimizarFacebook($url,'arquivos/imagens/instagram/'.$nome_imagem_i,352,352,false,true,80);

							//Inserir dados do instagram no DB
							$this->model->inserir_dados_tabela(array(
								'_tabela' => 'instagram_imagem',
								'dados' => array(
									'img' =>  $nome_imagem_i,
									'link' =>  $media->permalink,
									'title' =>  'ver instagram'
								)
							));
						}
					}

					$this->model->atualizar_dados_tabela(
						array(
						'_tabela' => 'instagram_config',
						'dados' => array(
							'atualizado' =>  date('Y-m-d'),
						),
						'where' => 'id = 1'
					));
				
				}
			}
		}
		
		$get = $this->input->get();

		if(!isset($get['limit']) || !$get['limit']){
			$get['limit'] = 8;
		}

		$dados_result = $this->model->db->query('SELECT * FROM '.$this->model->prefixo_db.'instagram_imagem ORDER BY id ASC LIMIT  '.$get['limit'].' ')->result_array();

		$dados = array();

		if($dados_result){
			$dados = $dados_result;
		}

		header('Content-Type: application/json;charset=utf-8');
        echo json_encode($dados);
		exit;

	}  

	
	public function posts_facebook_imagens(){
		
		if($post = $this->input->post()) {

			$insta_config = $this->model->db->query('SELECT * FROM '.$this->model->prefixo_db.'facebook_config WHERE id = 1')->row_array();
			if( strtotime($insta_config['atualizado']) < strtotime( date('Y-m-d') )){

				if(isset($post['id_facebook']) && $post['id_facebook']){
					
					$limite = (isset($post['limite'])? $post['limite'] : 8);

					$ch = curl_init('https://www.imaginecomunicacao.com.br/index.php?option=com_imaginecontato&view=contato&layout=get_facebook_pagina&id='.$post['id_facebook'].'&limite='.$limite);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

					$medias = json_decode(curl_exec($ch)); 

					if($medias){
						$this->load->library('Imgno_imagem', '', 'imagineImagem');
						
						//Deletar imagens no depositório
						foreach(glob(FCPATH . 'arquivos/imagens/facebook/*') as $arquivo) {
							if (!is_dir($arquivo)) {
								unlink($arquivo);
							}
						}

						//deletar todas a linha da tabela após deletar imagens no depositório
						$this->model->remover_dados_tabela(
							array(
								'_tabela' => 'facebook_imagem',
								'where' => 'id != 0'
							)
						);

						$i = 0;

						foreach($medias->posts as $posts){
                            if(empty($posts)) continue;
							
							foreach($posts as $media){
								// if($media->media_type == "VIDEO"){$url = $media->thumbnail_url;}else{$url = $media->media_url;}
								// $media->caption = (isset($media->caption)?$media->caption:'');

								@$url = $media->full_picture;
                                @$permalink_url = $media->permalink_url;

								$nome_imagem_i = 'facebook_'.$i.'_'.rand(1,1000).'.jpg';

								//Adicionar imagem no repositório
								/*$ch = curl_init($url);
								$fp = fopen('/arquivos/imagens/facebook/'.$nome_imagem_i, 'wb');
								curl_setopt($ch, CURLOPT_FILE, $fp);
								curl_setopt($ch, CURLOPT_HEADER, 0);
								curl_exec($ch);
								curl_close($ch);
								fclose($fp);*/ 

								//$this->imagineImagem->otimizarFacebook($url,'arquivos/imagens/facebook/'.$nome_imagem_i,240,240,false,true,100);
								$this->imagineImagem->otimizarFacebook($url,'arquivos/imagens/facebook/'.$nome_imagem_i,352,352,false,true,80);

								//Inserir dados do facebook no DB
								$this->model->inserir_dados_tabela(array(
									'_tabela' => 'facebook_imagem',
									'dados' => array(
										'img' =>  $nome_imagem_i,
										'link' => $permalink_url,
										'title' =>  'ver facebook'
									)
								));
							}
						}
					}

					$this->model->atualizar_dados_tabela(
						array(
						'_tabela' => 'facebook_config',
						'dados' => array(
							'atualizado' =>  date('Y-m-d'),
						),
						'where' => 'id = 1'
					));
				
				}
			}
		}
		
		$get = $this->input->get();

		if(!isset($get['limit']) || !$get['limit']){
			$get['limit'] = 8;
		}

		$dados_result = $this->model->db->query('SELECT * FROM '.$this->model->prefixo_db.'facebook_imagem ORDER BY id ASC LIMIT  '.$get['limit'].' ')->result_array();

		$dados = array();

		if($dados_result){
			$dados = $dados_result;
		}

		header('Content-Type: application/json;charset=utf-8');
        echo json_encode($dados);
		exit;

	}  

	public function aceito_cookie(){
		$this->load->library('session');
		$post = $this->input->post();
		
		if($post){
			// setcookie("confimado_cookie", true, time()+ ((24 * 60 * 60)*7) );

			$this->input->set_cookie(array(
				'name'   => 'confimado_cookie',
				'value'  => true,
				'expire' => time()+ ((24 * 60 * 60)*7)
			));
		}


		$dados['aceito'] = false;
		if(isset($_COOKIE['confimado_cookie'])){
			$dados['aceito'] = true;
		}

        header('Content-Type: application/json;charset=utf-8');
        echo json_encode($dados);
		exit;
	}

	public function politica()
	{

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Política de privacidade';

		$this->add_css(array(
			'template_1',
			'carousel',
		));

		$this->add_css(array(
			'template_2',
		),false,2);

        $this->add_script(array(
			'carousel'
		));

		$this->dados["dados"]["resultado"] = $this->model->getLGPD();

		// $this->dados['banner']['id_banner'] = 6;
		$this->html_pagina('politica-privacidade',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	} 

	public function lgpd()
	{

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'LGPD';

		$this->add_css(array(
			'template_1',
			'carousel',
		));

		$this->add_css(array(
			'template_2',
		),false,2);

        $this->add_script(array(
			'carousel'
		));

		$this->load->library('Imgno_util', '', 'ImgnoUtil');	

		$this->dados["dados"]["resultado"] = $this->model->getLGPD();

		// $this->dados['banner']['id_banner'] = 6;
		$this->html_pagina('lgpd',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	}

	public function erro()
	{	
		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Erro';

		$this->add_css(array(
			'template_1',
		));

		$this->add_css(array(
			'template_2',
		),false,2);

		$this->html_pagina('erro',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'seguir-instagram',
				'site-rodape',
				'formulario-popup',
			)
        ));
	}

	
}