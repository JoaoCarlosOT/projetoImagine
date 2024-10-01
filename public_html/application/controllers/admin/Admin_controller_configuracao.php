<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Admin_controller_configuracao extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('admin')) return $this->red_pagina('admin/login',FALSE);
	}

	public function configuracao() {

		// Valida o acesso a pagina
		$this->validar_acesso();

		
		if(!$this->model->verificar_permissao_admin('configuracao')){
			return $this->red_pagina('admin/login',FALSE);
		}

		
		if($post = $this->input->post()) {
			$resultado = $this->model->salvar_configuracao($post);

			if($resultado['retorno'] == 1){
				$this->msg["ok"] = $resultado["msg"];  
				$this->red_pagina('configuracao',null,"#".$resultado["msg"]);
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

		$this->html_pagina('configuracao',array(
			'topo' => array(
				'admin-topo'
			),
			'rodape' => array(
				'admin-rodape'
			)
        ));
	}

	public function con_excluir() { 

		if($post = $this->input->post()) {

			if('123456asd' != $post['senha']){
				$this->red_pagina('configuracao',null,"#erroSenha incorreta!");
				exit;
			} 

			if(!isset($post['tipo_componente'])) return ;
			$tipo_componente = $post['tipo_componente']; 

			if($tipo_componente == 1){ 
				// Seo
				$arquivos_existente = array(
					// Controllers
					FCPATH .'application/controllers/admin/Admin_controller_seo.php',

					// Models
					FCPATH .'application/models/admin/Admin_model_seo.php',
					FCPATH .'application/models/site/Site_model_seo.php',

					// Views
					FCPATH .'application/views/paginas/admin/seo-config.php',
					FCPATH .'application/views/paginas/admin/seo-link.php',
					FCPATH .'application/views/paginas/admin/seo-links.php',
					FCPATH .'application/views/paginas/admin/seo-mapa.php', 

					// Sitemap
					FCPATH .'sitemap.xml', 
				); 

				// excluir os arquivos
				$this->excluirArquivosAndDir($arquivos_existente);

				$tabelas = array(
					'ess_seo_config',
					'ess_seo_link',
				); 

				$this->model->excluirTabelas($tabelas);

				$permissoes_aba = array(
					'SEO',
				);

				$this->model->excluirPermissoes($permissoes_aba);

				$this->red_pagina('configuracao',null,"#Componente Seo excluído com sucesso");

			}else if($tipo_componente == 2){
				// Landing Page
				$arquivos_existente = array(
					// Controllers
					FCPATH .'application/controllers/admin/Admin_controller_landingpage.php',
					FCPATH .'application/controllers/landingpage',

					// Models
					FCPATH .'application/models/admin/Admin_model_landingpage.php',
					FCPATH .'application/models/landingpage',

					// Views
					FCPATH .'application/views/paginas/admin/landing-page-solicitacao.php',
					FCPATH .'application/views/paginas/admin/landing-page-solicitacoes.php',
					FCPATH .'application/views/paginas/admin/landing-page-configuracao-lp.php',
					FCPATH .'application/views/paginas/admin/landing-page-configuracoes-lp.php',
					FCPATH .'application/views/paginas/admin/landing-page-repositorio-lp-geradas.php',
					FCPATH .'application/views/paginas/admin/landing-page-copywriter-configuracao.php',
					FCPATH .'application/views/paginas/admin/landing-page-copywriter.php',
					FCPATH .'application/views/paginas/admin/landing-page-copywriters.php',
					FCPATH .'application/views/paginas/landingpage',
					FCPATH .'application/views/modulos/banner-lp.php',

					// Arquivos
					// FCPATH .'arquivos/css/landingpage.css', 
					FCPATH .'arquivos/css/views/paginas/landingpage/LP-cidade.css', 
					FCPATH .'arquivos/css/views/paginas/landingpage/LP-estado.css', 

					// Router
					FCPATH .'application/config/routes-lp', 
				); 

				// excluir os arquivos
				$this->excluirArquivosAndDir($arquivos_existente);

				$tabelas = array(
					'ess_institucional_solicitacoes_lp',
					'ess_institucional_estado_cidade',
					'ess_institucional_configuracao_lp',
					'ess_institucional_configuracao_lp_imagem',
					'ess_copywriter_lp',
					'ess_copywriter_config_lp',
					'ess_institucional_regiaoLP',
				);  

				$this->model->excluirTabelas($tabelas);

				$permissoes_aba = array(
					'Landing Page',
				);

				$this->model->excluirPermissoes($permissoes_aba);

				$this->red_pagina('configuracao',null,"#Componente Landing Page excluído com sucesso");

			}else if($tipo_componente == 3){
				// Loja Virtual
				$arquivos_existente = array(
					// Controllers
					FCPATH .'application/controllers/admin/Admin_controller_financeiro.php',
					FCPATH .'application/controllers/admin/Admin_controller_cliente.php',
					FCPATH .'application/controllers/site/Site_controller_checkout.php',
					FCPATH .'application/controllers/cliente',
					FCPATH .'application/controllers/usuario',

					// Models
					FCPATH .'application/models/admin/Admin_model_financeiro.php',
					FCPATH .'application/models/admin/Admin_model_cliente.php',
					FCPATH .'application/models/cliente',
					FCPATH .'application/models/usuario',

					// Views
					FCPATH .'application/views/paginas/admin/financeiro-carrinho.php',
					FCPATH .'application/views/paginas/admin/financeiro-carrinhos-abandonados.php',
					FCPATH .'application/views/paginas/admin/financeiro-configuracao.php',
					FCPATH .'application/views/paginas/admin/financeiro-cupom.php',
					FCPATH .'application/views/paginas/admin/financeiro-cupons.php',
					FCPATH .'application/views/paginas/admin/financeiro-assinatura.php',
					FCPATH .'application/views/paginas/admin/financeiro-assinaturas.php',
					FCPATH .'application/views/paginas/admin/financeiro-dados.php',
					FCPATH .'application/views/paginas/cliente',
					FCPATH .'application/views/paginas/site/checkout-cadastrar.php',
					FCPATH .'application/views/paginas/site/checkout-email.php',
					FCPATH .'application/views/paginas/site/checkout-finalizado.php',
					FCPATH .'application/views/paginas/site/checkout-recuperar.php',
					FCPATH .'application/views/paginas/site/checkout-senha.php',
					FCPATH .'application/views/paginas/site/checkout.php',
					FCPATH .'application/views/paginas/usuario',

					// Arquivos
					FCPATH .'arquivos/css/conta.css', 
				); 

				// excluir os arquivos
				$this->excluirArquivosAndDir($arquivos_existente);

				$tabelas = array(
					'ess_financeiro_carrinho',
					'ess_financeiro_config',
					'ess_financeiro_cupom',
					'ess_financeiro_cupom_utilizado',
					'ess_financeiro_lancamentos',
					'ess_financeiro_pedido',
					'ess_financeiro_pedido_produto',
					'ess_financeiro_pedido_situacao',
					'ess_financeiro_assinatura',

					'ess_clientes',
					'ess_clientes_recuperar_senha',

					'ess_usuarios',
					'ess_usuarios_permissoes',
				);  

				$this->model->excluirTabelas($tabelas);

				$permissoes_aba = array(
					'Loja Virtual',
					'Clientes',
				);

				$this->model->excluirPermissoes($permissoes_aba);

				$this->red_pagina('configuracao',null,"#Componente Loja Virtual excluído com sucesso");
			}
			

		}else{

		}
		exit;
	}

	public function con_exportar() {

		if($post = $this->input->post()) {
			if('123456asd' != $post['senha']){	
				$this->red_pagina('configuracao',null,"#erroSenha incorreta!");
				exit;
			}

			if(empty($post['tipo_componente'])) return $this->red_pagina('configuracao',null,"#erroPor favor, Informe o tipo de componente para ser exportado");
			$tipo_componente = $post['tipo_componente']; 

			if($tipo_componente == 1){
				// Seo
				$tabelas = array(
					array('nome' => 'ess_seo_config', 'create_table' => true, 'insert' => true),
					array('nome' => 'ess_seo_link', 'create_table' => true, 'insert' => false),
				);
				$permissoes = array(
					'SEO',
				);
				$sql = $this->backupTables($tabelas,$permissoes);

				// Criar uma instância de ZipArchive
				$zip = new ZipArchive(); 

				// Nome do arquivo ZIP a ser criado
				$nome_arquivo_zip = 'componente.zip';	 

				$caminho_arquivo_zip = 'temp_componente/'.$nome_arquivo_zip;

				if (!is_dir('temp_componente')) {
					mkdir('temp_componente', 0777, true);
				}
				
				// Abre ou cria o arquivo ZIP
				if ($zip->open($caminho_arquivo_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
 
					// Adicionar arquivos existentes ao ZIP
					$arquivos_existente = array(
						// Controllers
						'application/controllers/admin/Admin_controller_seo.php',

						// Models
						'application/models/admin/Admin_model_seo.php',
						'application/models/site/Site_model_seo.php',

						// Views
						'application/views/paginas/admin/seo-config.php',
						'application/views/paginas/admin/seo-link.php',
						'application/views/paginas/admin/seo-links.php',
						'application/views/paginas/admin/seo-mapa.php',

						// SQL
						$sql,
					);  

					foreach ($arquivos_existente as $arquivo) {
						// Adiciona o arquivo ao ZIP
						$zip->addFile($arquivo);
					}

					// Fechar o arquivo ZIP
					$zip->close(); 

					// Fazer o download do arquivo ZIP
					$this->load->helper('download');
					force_download($caminho_arquivo_zip, NULL);

					// Remover o arquivo ZIP após o download
					unlink($caminho_arquivo_zip);

					$this->red_pagina('configuracao',null,"#Arquivo ZIP para exportação criado com sucesso: $nome_arquivo_zip");
				} else {
					$this->red_pagina('configuracao',null,"#erroFalha ao criar o arquivo ZIP para exportação");
				}

			}else if($tipo_componente == 2){
			
				// Landing Page
				$tabelas = array(
					array('nome' => 'ess_institucional_solicitacoes_lp', 'create_table' => true, 'insert' => false),
					array('nome' => 'ess_institucional_estado_cidade', 'create_table' => true, 'insert' => true),
					array('nome' => 'ess_institucional_configuracao_lp', 'create_table' => true, 'insert' => false),
					array('nome' => 'ess_institucional_configuracao_lp_imagem', 'create_table' => true, 'insert' => false),
					array('nome' => 'ess_copywriter_lp', 'create_table' => true, 'insert' => false),
					array('nome' => 'ess_copywriter_config_lp', 'create_table' => true, 'insert' => true),
					array('nome' => 'ess_institucional_regiaoLP', 'create_table' => true, 'insert' => false),
				);
				$permissoes = array(
					'Landing Page',
				);
				$sql = $this->backupTables($tabelas,$permissoes);

				// Criar uma instância de ZipArchive
				$zip = new ZipArchive(); 

				// Nome do arquivo ZIP a ser criado
				$nome_arquivo_zip = 'componente.zip';	 

				$caminho_arquivo_zip = 'temp_componente/'.$nome_arquivo_zip;

				if (!is_dir('temp_componente')) {
					mkdir('temp_componente', 0777, true);
				}
				
				// Abre ou cria o arquivo ZIP
				if ($zip->open($caminho_arquivo_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
 
					// Adicionar arquivos existentes ao ZIP
					$arquivos_existente = array(
						// Controllers
						'application/controllers/admin/Admin_controller_landingpage.php',
						'application/controllers/landingpage/Landingpage_controller_landingpage.php',

						// Models
						'application/models/admin/Admin_model_landingpage.php',
						'application/models/landingpage/Landingpage_model_landingpage.php',

						// Views
						'application/views/paginas/admin/landing-page-solicitacao.php',
						'application/views/paginas/admin/landing-page-solicitacoes.php',
						'application/views/paginas/admin/landing-page-configuracao-lp.php',
						'application/views/paginas/admin/landing-page-configuracoes-lp.php',
						'application/views/paginas/admin/landing-page-repositorio-lp-geradas.php',
						'application/views/paginas/admin/landing-page-copywriter-configuracao.php',
						'application/views/paginas/admin/landing-page-copywriter.php',
						'application/views/paginas/admin/landing-page-copywriters.php',
						'application/views/paginas/landingpage/LP-cidade.php',
						'application/views/paginas/landingpage/LP-estado.php',
						'application/views/modulos/banner-lp.php',

						// Arquivos
						// 'arquivos/css/landingpage.css',
						'arquivos/css/views/paginas/landingpage/LP-cidade.css', 
						'arquivos/css/views/paginas/landingpage/LP-estado.css', 

						// SQL
						$sql,
					);  

					foreach ($arquivos_existente as $arquivo) {
						// Adiciona o arquivo ao ZIP
						$zip->addFile($arquivo);
					}

					// Fechar o arquivo ZIP
					$zip->close(); 

					// Fazer o download do arquivo ZIP
					$this->load->helper('download');
					force_download($caminho_arquivo_zip, NULL);

					// Remover o arquivo ZIP após o download
					unlink($caminho_arquivo_zip);

					$this->red_pagina('configuracao',null,"#Arquivo ZIP para exportação criado com sucesso");
				} else {
					$this->red_pagina('configuracao',null,"#erroFalha ao criar o arquivo ZIP para exportação");
				}
			
			}else{
				return $this->red_pagina('configuracao',null,"#erroPor favor, Informe o tipo de componente para ser exportado");	
			}
		}else{
			return $this->red_pagina('configuracao',null,"#erroPor favor, Informe o tipo de componente para ser exportado");	
		}
		exit;
	}

	public function con_importar() {
		if($post = $this->input->post()) {

			if('123456asd' != $post['senha']){
				$this->red_pagina('configuracao',null,"#erroSenha incorreta!");
				exit;
			}

			if(!empty($_FILES["arquivo"]) && $_FILES["arquivo"]["error"] == 0) {
				
				$tipoArquivo = mime_content_type($_FILES["arquivo"]["tmp_name"]);
				if ($tipoArquivo != "application/zip") { 
					return $this->red_pagina('configuracao',null,"#erroPor favor, envie um arquivo ZIP válido para importar");	
				}

				$this->load->database();

				// Caminho para o arquivo ZIP
				$caminho_arquivo_zip = $_FILES["arquivo"]["tmp_name"];

				// Criar uma instância de ZipArchive
				$zip = new ZipArchive;

				// Tentar abrir o arquivo ZIP
				if ($zip->open($caminho_arquivo_zip) === TRUE) {
					// Iterar sobre os arquivos e pastas dentro do ZIP
					for ($i = 0; $i < $zip->numFiles; $i++) {
						$nome_arquivo = $zip->getNameIndex($i);

						if( strpos($nome_arquivo, 'application/controllers/') === 0 ||
							strpos($nome_arquivo, 'application/models/') === 0 ||
							strpos($nome_arquivo, 'application/views/') === 0 ||
							strpos($nome_arquivo, 'arquivos/') === 0){

							$nome_arquivo = FCPATH . $nome_arquivo;

							// Verificar se é um diretório
							if (substr($nome_arquivo, -1) == '/') {
								if(!file_exists($nome_arquivo)) mkdir($nome_arquivo, 0777, true);

							} else {
								if(!file_exists(dirname($nome_arquivo))) mkdir(dirname($nome_arquivo), 0777, true);

								// Ler e exibir o conteúdo do arquivo
								$conteudo = $zip->getFromIndex($i);

								// Criar o arquivo
								$arquivo = fopen($nome_arquivo, 'w');
								fwrite($arquivo, $conteudo);
								fclose($arquivo);        
							}

						}else if(strpos($nome_arquivo, 'application/sql/') === 0){
							// Verificar se é um diretório
							if (substr($nome_arquivo, -1) == '/') {

							} else {
								// Ler e exibir o conteúdo do arquivo
								$conteudo = $zip->getFromIndex($i);
								$instrucoes_sql = explode(';', $conteudo);

								// Executar cada instrução individualmente
								foreach ($instrucoes_sql as $instrucao) {
									// Remover espaços em branco extras
									$instrucao = trim($instrucao);

									// Verificar se a instrução não está vazia
									if (!empty($instrucao)) {

										// Executar a instrução SQL usando a biblioteca de banco de dados do CodeIgniter
										$this->db->query($instrucao);
									}
								}
							}
						}

					}

					// Fechar o arquivo ZIP
					$zip->close();

					$this->red_pagina('configuracao',null,"#Importado com sucesso");
					
				} else {
					return $this->red_pagina('configuracao',null,"#erroFalha ao abrir o arquivo ZIP para importar");	
				}
			}else{
				return $this->red_pagina('configuracao',null,"#erroPor favor, informe um arquivo ZIP para importar");	
			}
		}
		exit;
	} 

	public function backupTables($tabelas, $permissoes = false) {
		if(!$tabelas) return;

		$this->load->database();
		$this->load->helper('file');
		$this->load->library('zip'); 

		// Diretório temporário para armazenar os arquivos SQL
		$diretorio_temporario = 'application/sql';

		// Certifique-se de que o diretório temporário exista
		if (!is_dir($diretorio_temporario)) {
			mkdir($diretorio_temporario, 0777, true);
		}

		// Nome do arquivo SQL a ser criado
		$nome_arquivo_sql = $diretorio_temporario . '/backup.sql';

		// Abre o arquivo SQL para escrita
		$handle = fopen($nome_arquivo_sql, 'w');

		// Loop através das tabelas
		foreach ($tabelas as $tabela) {
			$nome_tabela = $tabela['nome'];

			$info_colunas = $this->db->query("SHOW COLUMNS FROM `$nome_tabela`")->result_array();
			foreach ($info_colunas as $coluna_info) {
				if (isset($coluna_info['Extra']) && $coluna_info['Extra'] === 'auto_increment') {
					// A coluna é AUTO_INCREMENT
					$id = $coluna_info['Field'];
				} 
			} 
			
			if (!empty($tabela['create_table'])) {

				fwrite($handle, "DROP TABLE IF EXISTS `$nome_tabela`;\n");

				// Obter a estrutura da tabela
				$estrutura_tabela = $this->db->query("SHOW CREATE TABLE `$nome_tabela`")->row_array();

				// Adicionar instrução CREATE TABLE ao arquivo SQL
				fwrite($handle, $estrutura_tabela['Create Table'] . ";\n\n");
			
			}else{

				// Obter a estrutura da tabela
				$estrutura_tabela = $this->db->query("SHOW CREATE TABLE `$nome_tabela`")->row_array();

				$estrutura_tabela['Create Table'] = str_replace("CREATE TABLE","CREATE TABLE IF NOT EXISTS",$estrutura_tabela['Create Table']);

				// Adicionar instrução CREATE TABLE ao arquivo SQL
				fwrite($handle, $estrutura_tabela['Create Table'] . ";\n\n");
			}

			if (!empty($tabela['insert'])) {
				// Adicionar instruções INSERT ao arquivo SQL
				$query_sql = $this->db->get($nome_tabela);
				$resultado = $query_sql->result_array();

				if (!empty($resultado)) {
					// Obter os nomes das colunas da tabela
					$colunas_tabela = $this->db->list_fields($nome_tabela);
					if(!empty($id) && empty($tabela['create_table'])) $colunas_tabela = array_diff($colunas_tabela, array($id));

					fwrite($handle, "INSERT INTO `$nome_tabela` (`" . implode('`, `', $colunas_tabela) . "`) VALUES\n");

					foreach ($resultado as $key => $linha) {
	
						if(!empty($id) && empty($tabela['create_table'])) unset($linha[$id]);

						$linha_values = array_map(function($valor) {
							return "'" . $this->db->escape_str($valor) . "'";
						}, $linha);  

						if(isset($resultado[$key+1])){
							fwrite($handle, "(" . implode(', ', $linha_values) . "),\n");
						}else{
							fwrite($handle, "(" . implode(', ', $linha_values) . ")");
						}
					}

					// Remover a última vírgula e quebra de linha
					fwrite($handle, ";\n\n");
				}
			}
		}

		if($permissoes){
			$nome_tabela_per = 'ess_admin_lista_permissoes';

			// Obter a estrutura da tabela
			$estrutura_tabela = $this->db->query("SHOW CREATE TABLE `$nome_tabela_per`")->row_array();

			$estrutura_tabela['Create Table'] = str_replace("CREATE TABLE","CREATE TABLE IF NOT EXISTS",$estrutura_tabela['Create Table']);

			// Adicionar instrução CREATE TABLE ao arquivo SQL
			fwrite($handle, $estrutura_tabela['Create Table'] . ";\n\n");

			$info_colunas = $this->db->query("SHOW COLUMNS FROM `$nome_tabela_per`")->result_array();
			foreach ($info_colunas as $coluna_info) {
				if (isset($coluna_info['Extra']) && $coluna_info['Extra'] === 'auto_increment') {
					// A coluna é AUTO_INCREMENT
					$id_per = $coluna_info['Field'];
				} 
			}

			foreach($permissoes as $permissao){
				$this->db->or_where('aba', $permissao);
			}

			$query_sql = $this->db->get($nome_tabela_per);
			$resultado = $query_sql->result_array(); 

			if($resultado){
				
				// Obter os nomes das colunas da tabela
				$colunas_tabela_per = $this->db->list_fields($nome_tabela_per);

				if(!empty($id_per)) $colunas_tabela_per = array_diff($colunas_tabela_per, array($id_per));

				fwrite($handle, "INSERT INTO `$nome_tabela_per` (`" . implode('`, `', $colunas_tabela_per) . "`) VALUES\n");

				foreach ($resultado as $key => $linha) {
					
					if(!empty($id_per)) unset($linha[$id_per]);

					$linha_values = array_map(function($valor) {
						return "'" . $this->db->escape_str($valor) . "'";
					}, $linha);  

					if(isset($resultado[$key+1])){
						fwrite($handle, "(" . implode(', ', $linha_values) . "),\n");
					}else{
						fwrite($handle, "(" . implode(', ', $linha_values) . ")");
					}
				}

				// Remover a última vírgula e quebra de linha
				fwrite($handle, ";\n\n");
				
			}
		} 

		// Fechar o arquivo SQL
		fclose($handle);  

		return $nome_arquivo_sql;

		// Adicionar arquivo SQL ao arquivo ZIP
		// $this->zip->add_data(basename($nome_arquivo_sql), read_file($nome_arquivo_sql));

		// Remover arquivos temporários após o backup
		// unlink($nome_arquivo_sql);

		// Nome do arquivo ZIP a ser criado
		// $nome_arquivo_zip = 'backup_multitabelas.zip';

		// Fazer o download do arquivo ZIP
		// $this->zip->download($nome_arquivo_zip);

		// Remover diretório temporário após o download
		// $this->rrmdir($diretorio_temporario);
    } 

    // Função para remover recursivamente um diretório e seu conteúdo
    private function rrmdir($diretorio) {
        foreach (glob($diretorio . '/*') as $arquivo) {
            if (is_dir($arquivo)) {
                $this->rrmdir($arquivo);
            } else {
                unlink($arquivo);
            }
        }
        rmdir($diretorio);
    }

	private function excluirArquivosAndDir($arquivos_existente){
		if(!$arquivos_existente) return;

		foreach($arquivos_existente as $arquivo){
			if(file_exists($arquivo)){
				if (is_dir($arquivo)) {
					$this->rrmdir($arquivo);
				}else{
					unlink($arquivo);
				}
			}
		}
	} 

}