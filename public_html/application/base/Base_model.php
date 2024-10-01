<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_model extends CI_Model{
	// Login
	public function login_imagine(&$args = array()) {
		// Verifica se há post
		if(!$args || !$args['post']) return !(bool)($args['erro'] = 'Ocorreu um erro tente novamente em alguns minutos#erro');
		
		// Valida os dados do post
		if(!$this->validar_post($args)) return FALSE;

		// Verifica se o usuário existe
		$args['valor'] = trim($args['post']['email']);
		$args['_tabela'] = 'admin_conta';
		if(!($usuario = $this->verificar_usuario($args))) return !(bool)($args['erro'] = 'Login ou senha incorretos, verifique suas informações e tente novamente#erro');

		// Verifica se a senha está correta
		$this->load->library('Imgno_pass', '', 'gerador_hash');
		if(!$this->gerador_hash->validar_senha($args['post']['senha'], $usuario->senha)) return !(bool)($args['erro'] = 'Login ou senha incorretos#erro');

		// Atualiza o ultimo login do usuário
		$dados = array('dados' => array('ultimo_login' => date('Y-m-d H:i:s')), 'where' => array('id' => $usuario->id));
		$this->atualizar_dados_tabela($dados);
		
		// Inicia a sessão
		$this->atualizar_sessao_usuario_imagine($usuario);
		
		// retorno
		return TRUE;
	}
	
	// Login
	public function login(&$args = array(), $sessao = null) {
		// Verifica se há post
		if(!$args || !$args['post']) return !(bool)($args['erro'] = 'Ocorreu um erro tente novamente em alguns minutos#erro');
		
		// Valida os dados do post
		if(!$this->validar_post($args)) return FALSE;


		// Verifica se o usuário existe
		$args['valor'] = trim($args['post']['usuario']);
		if(!($usuario = $this->verificar_usuario($args))) return !(bool)($args['erro'] = 'Usuário ou senha incorretos, verifique suas informações e tente novamente#erro');
		
		// Verifica se o usuario pode acessar o site
		if($args['_tabela'] == 'usuarios' && !$usuario->situacao == 1) {
			return !(bool)($args['erro'] = 'Usuário desabilitado no momento#erro');
		}
		// Verifica se a senha está correta
		$this->load->library('Imgno_pass', '', 'gerador_hash');
		if(!$this->gerador_hash->validar_senha($args['post']['senha'], $usuario->senha)) return !(bool)($args['erro'] = 'Login ou senha incorretos#erro');

		// Atualiza o ultimo login do usuário
		$dados = array('_tabela'=> $args['_tabela'],'dados' => array('ultimo_login' => date('Y-m-d H:i:s')), 'where' => array('id' => $usuario->id));
		$this->atualizar_dados_tabela($dados);
		
		
		if($sessao){
			$this->sessao_tipo  =$sessao;
		}
		// Inicia a sessão
		$this->atualizar_sessao_usuario($usuario,$sessao);
		
		// returno
		return TRUE;
	}
	
	public function carregar_model($args) {
		if(empty($args['path']) || empty($args['name'])) { echo 'Os parâmetros do model não foram informados corretamente'; exit; }
		$path = $args['path']; $name = $args['name'];
		if(isset($this->$name)) return TRUE;
		$this->load->model($args['path'], $args['name']);
		return $this;
	}
	
	public function verificar_usuario(&$args = array()) {
		$_args = array(
			'select' => '*', 
			'where' => array(
				$args['chave'] => $args['valor']
			)
		);
		if(isset($args['_tabela'])) $_args['_tabela'] = $args['_tabela'];
		elseif(isset($args['tabela'])) $_args['tabela'] = $args['tabela'];
		else $_args['tabela'] = '';
		return $this->buscar_resultado($_args);
	}
	
	// Logout
	public function logout() {
		$this->atualizar_sessao_usuario_imagine();
	}
	
	private function atualizar_sessao_usuario($user = NULL, $sessao = NULL) {
		if(!$sessao) $sessao = $this->sessao_tipo == 'convidado' ? 'usuario' : $this->sessao_tipo;
		if($user) {
			$this->session->set_userdata($sessao .'.id_usuario', $user->id);
			if(!empty($user->user_pai)) $this->session->set_userdata($sessao .'_pai.id_usuario', $user->user_pai);
		} else {
			$this->session->unset_userdata($sessao .'.id_usuario');
			$this->session->unset_userdata($sessao .'_pai.id_usuario');
			$ts = $this->buscar_toda_sessao();
			foreach($ts as $k => $s) if(strpos($k, $sessao .'.permissoes.') !== FALSE) $this->session->unset_userdata($k);
		}
		return TRUE;
	}
	
	private function atualizar_sessao_usuario_imagine($user = NULL, $sessao = NULL) {
		if(!$sessao) $sessao = $this->sessao_tipo == 'convidado' ? 'usuario' : $this->sessao_tipo;
		if($user) {
			$contas = explode(',',$user->contas);
			if(!$contas[1]) return false;
			$this->session->set_userdata($sessao .'.id_usuario', $contas[1]);
			$this->session->set_userdata($sessao .'.id_usuario_imagine', $user->id);
		} else {
			$this->session->unset_userdata($sessao .'.id_usuario');
			$this->session->unset_userdata($sessao .'.id_usuario_imagine');
			$this->session->unset_userdata($sessao .'_pai.id_usuario');
			$ts = $this->buscar_toda_sessao();
			foreach($ts as $k => $s) if(strpos($k, $sessao .'.permissoes.') !== FALSE) $this->session->unset_userdata($k);
		}
		return TRUE;
	}
	
	// Cadastro
	public function cadastro(&$args) {
		if(empty($args['post'])) return !(bool)($args['erro'] = 'Ocorreu um erro tente novamente em alguns minutos#erro');
		
		// Valida os dados do post
		if(!$this->validar_post($args)) return FALSE;
		
		// Verifica se o email informado ja esta sendo usado
		if(!$this->campo_unico(trim($args['post']['email']), 'email', 'usuario')) return !(bool)($args['erro'] = 'O email informado ja esta sendo usado#erro');
		
		// Efetua o cadastro
		if(!($user = $this->inserir_usuario($args))) return FALSE;
		
		// Retorno
		return $user;
	}
	
	public function buscar_pasta_usuario($args = array()) {
		if(empty($args['usuario'])) {
			$args['usuario'] = $this->buscar_dados_usuario($this->usuario_pai ? $this->usuario_pai : $this->usuario);
			if(empty($args['usuario'])) return NULL;
		} else $args['usuario'] = is_object($args['usuario']) ? $args['usuario'] : $this->buscar_dados_usuario($args['usuario']);
		
		if(empty($args['local'])) {
			$args['local'] = realpath('arquivos') . '/imagens/upload/';
		}
		
		return $this->gerar_nome_pasta_usuario($args['usuario'], $args['local'], TRUE);
	}

	public function buscar_pasta_cliente($args = array()) {
		if(empty($args['cliente'])) {
			return NULL;
		}
		
		if(empty($args['local'])) {
			$args['local'] = realpath('arquivos') . '/imagens/upload/';
		}

		$args['cliente'] = $this->buscar_dados_cliente($args['cliente']);
		
		return $this->gerar_nome_pasta_usuario($args['cliente'], $args['local'], TRUE);
	}
	
	public function criar_pasta($user = NULL) {
		if(!$user) return FALSE;
		
		// Cria a pasta
		$data = '<html><body bgcolor="#FFFFFF"></body></html>';
		$base = realpath('arquivos');
		
		if(!($nome = $this->gerar_nome_pasta_usuario($user, $base . '/imagens/upload/'))) return FALSE;
		
		$imagens = '/imagens/upload/'. $nome;
		$img_path = $base . $imagens;
		if(!file_exists($img_path)) {
			mkdir($img_path , 0777, true);
			$this->load->helper('file');
			write_file('arquivos'. $imagens .'/index.html', $data);
		}
		
		$u_imagens = $imagens . '/imagens';
		$u_imagens_path = $img_path . '/imagens';
		if(!file_exists($u_imagens_path)) {
			mkdir($u_imagens_path , 0777, true);
			$this->load->helper('file');
			write_file('arquivos'. $u_imagens .'/index.html', $data);
		}
		
		$config = $u_imagens . '/config';
		$config_path = $u_imagens_path . '/config';
		if(!file_exists($config_path)) {
			mkdir($config_path , 0777, true);
			$this->load->helper('file');
			write_file('arquivos'. $config .'/index.html', $data);
		}
		
		return TRUE;
	}
	
	private function inserir_usuario(&$args) {
		if(empty($args['post'])) return !(bool)($args['erro'] = 'Ocorreu um erro, tente novamente mais tarde#erro');
		
		// Bibliotaca de gerarção de senhas
		$this->load->library('Imgno_pass', '', 'gerador_hash');
		
		// Dados do usuário
		$dados = array(
			'dados' => array(
				'nome' => $args['post']['nome'],
				'data_nascimento' => $args['post']['data_nascimento'],
				'telefone1' => $args['post']['telefone1'],
				'telefone2' => $args['post']['telefone2'],
				'email' => $args['post']['email'],
				'identificador' => $args['post']['identificador'],
				'senha' => $this->gerador_hash->gerar_senha($args['post']['senha']),
				'cadastro' => date('Y-m-d H:i:s'),
				'consideracoes_iniciais' => 'Prezado cliente, caso deseje obter mais informações sobre esta mensagem, favor acessar o nosso site ou entrar em contato com o nosso atendimento. Estaremos sempre a disposição para melhor atendê-lo.',
				'consideracoes_finais' => 'Caso deseje sair da nossa lista vip de assinantes, basta acessar o seguinte link: {Cancelar}.',
			),
			'retornar_id' => TRUE
		);
		if($args['post']['contato_email']) $dados['dados']['contato_nome'] = $args['post']['contato_nome'];
		if($args['post']['contato_email']) $dados['dados']['contato_email'] = $args['post']['contato_email'];
		if(isset($args['config'])) $dados['dados']['config'] = $args['config'];
		else {
			$dados['dados']['config'] = serialize(array(
				'usuario_acesso_todos_emails' => 0,
				'usuario_acesso_todas_listas' => 0,
				'usuario_acesso_modulo_email_marketing' => 1,
				'usuario_acesso_modulo_crm' => 1,
				'usuario_acesso_modulo_crm_cliente' => 1,
				'usuario_acesso_modulo_crm_lead' => 1,
				'usuario_acesso_modulo_crm_relatorios' => 1,
				'usuario_acesso_modulo_crm_emails_automaticos' => 1,
				'usuario_acesso_modulo_crm_configuracoes' => 1,
				'usuario_acesso_modulo_gestor' => 1,
				'usuario_acesso_modulo_usuarios' => 1,
				'usuario_acesso_config_principal' => 1,
				'usuario_acesso_modulo_gestor_colaboradores' => 1,
				'usuario_acesso_modulo_gestor_configuracoes' => 1
			));
		}
		
		if(!empty($args['post']['emails_rastreados'])) {
			$dados['dados']['emails_rastreados'] = $args['post']['emails_rastreados'];
		}
		
		if(isset($args['post']['user_pai']) && (int)$args['post']['user_pai'] == $this->logado('usuario')) {
			$dados['dados']['user_pai'] = (int)$args['post']['user_pai'];
			if(isset($args['post']['habilitar'])) $dados['dados']['habilitar'] = (int)$args['post']['habilitar'];
		}
		if(isset($args['_tabela'])) $dados['_tabela'] = $args['_tabela'];
		
		// Insere o usuário na database
		$user_id = $this->inserir_dados_tabela($dados);
		
		// Verifica se o usuário foi inserido corretmanente
		if(!($user = $this->buscar_dados_usuario($user_id))) return !(bool)($args['erro'] = 'Ocorreu um erro, tente novamente mais tarde#erro');
		
		// Cria as pastas do usuário
		if(empty($args['sem_pasta'])) if(!$this->criar_pasta($user)) return !(bool)($args['erro'] = 'Cadastro completado com pendencias, verifique junto à administração para resolver o problema#erro');
		
		// Realiza o login automático e retorana para a pagina inicial, caso esteja configurado no admin
		if(empty($args['nao_logar']) && ($admin_config = $this->buscar_config_admin()) && !empty($admin_config['cadastro_aprovacao'])) {
			$this->atualizar_sessao_usuario($user);
			$args['cadastro_aprovacao'] = $admin_config['cadastro_aprovacao'];
			
			// Atualiza o acesso do usuário
			$dados = array('dados' => array('habilitar' => 1), 'where' => array('id' => $user->id));
			$this->atualizar_dados_tabela($dados);
		}
		
		return $user;
	}
	
	public function inserir_dados_tabela($args = array()) {
		if(!$args || empty($args['dados'])) return FALSE;
		
		// Confirgurações
		if(!isset($args['tabela'])) $args['tabela'] = '';
		if(!isset($args['_tabela'])) $args['_tabela'] = NULL;
		if(!isset($args['retornar_id'])) $args['retornar_id'] = FALSE;
		if(!isset($args['batch'])) $args['batch'] = FALSE;
		
		if(!$args['batch']) {
			$this->db->insert($args['_tabela'] ? $this->prefixo_db . $args['_tabela'] : $this->_tabela . $args['tabela'], $args['dados']);
			return $args['retornar_id'] ? $this->db->insert_id() : TRUE;
		} else {
			$this->db->insert_batch($args['_tabela'] ? $this->prefixo_db . $args['_tabela'] : $this->_tabela . $args['tabela'], $args['dados']);
		}
	}
	
	public function atualizar_dados_tabela($args) {
		if(!$args) return FALSE;
		
		// Confirgurações
		if(!isset($args['tabela'])) $args['tabela'] = '';
		if(!isset($args['_tabela'])) $args['_tabela'] = NULL;
		if(!isset($args['batch'])) $args['batch'] = FALSE;
		if(!isset($args['batch_by_key'])) $args['batch_by_key'] = NULL;
		
		// Onde inserir
		if(isset($args['where_in'])) foreach($args['where_in'] as $k => $o) $this->db->where_in($k, $o);
		if(isset($args['where'])) $this->db->where($args['where']);
		if(isset($args['or_where'])) foreach($args['or_where'] as $k => $o) $this->db->or_where($o[0], $o[1]);
		if(isset($args['or_where_in'])) foreach($args['or_where_in'] as $k => $o) $this->db->or_where_in($o[0], $o[1]);
		if(isset($args['like'])) $this->db->like($args['like']);
		if(isset($args['or_like'])) foreach($args['or_like'] as $o) $this->db->or_like($o[0], $o[1]);
		if(isset($args['not_like'])) foreach($args['not_like'] as $o) $this->db->not_like($o[0], $o[1]);
		if(isset($args['set'])) foreach($args['set'] as $key => $val) {
			if(is_array($val)) $this->db->set($key, $val[0], $val[1]);
			else $this->db->set($key, $val);
		}
		
		if(!$args['batch'] && !$args['batch_by_key']) {
			if(empty($args['dados'])) {
				if($args['_tabela']) {
					if(is_array($args['_tabela'])) {
						foreach($args['_tabela'] as &$t) $t = $this->prefixo_db . $t;
						return $this->db->update(implode(', ', $args['_tabela']));
					} else {
						return $this->db->update($this->prefixo_db . $args['_tabela']);
					}
				} else {
					if(is_array($args['tabela'])) {
						foreach($args['tabela'] as &$t) $t = $this->prefixo_db . $t;
						return $this->db->update(implode(', ', $args['tabela']));
					} else {
						return $this->db->update($this->_tabela . $args['tabela']);
					}
				}
			} else {
				if($args['_tabela']) {
					if(is_array($args['_tabela'])) {
						foreach($args['_tabela'] as &$t) $t = $this->prefixo_db . $t;
						return $this->db->update(implode(', ', $args['_tabela']), $args['dados']);
					} else {
						return $this->db->update($this->prefixo_db . $args['_tabela'], $args['dados']);
					}
				} else {
					if(is_array($args['tabela'])) {
						foreach($args['tabela'] as &$t) $t = $this->prefixo_db . $t;
						return $this->db->update(implode(', ', $args['tabela']), $args['dados']);
					} else {
						return $this->db->update($this->_tabela . $args['tabela'], $args['dados']);
					}
				}
			}
		} else {
			if(empty($args['dados'])) {
				return FALSE;
			} else {
				if($args['_tabela']) {
					if(is_array($args['_tabela'])) {
						foreach($args['_tabela'] as &$t) $t = $this->prefixo_db . $t;
						return $this->db->update_batch(implode(', ', $args['_tabela']), $args['dados'], $args['batch_by_key']);
					} else {
						return $this->db->update_batch($this->prefixo_db . $args['_tabela'], $args['dados'], $args['batch_by_key']);
					}
				} else {
					if(is_array($args['tabela'])) {
						foreach($args['tabela'] as &$t) $t = $this->prefixo_db . $t;
						return $this->db->update_batch(implode(', ', $args['tabela']), $args['dados'], $args['batch_by_key']);
					} else {
						return $this->db->update_batch($this->_tabela . $args['tabela'], $args['dados'], $args['batch_by_key']);
					}
				}
			}
		}
	}
	
	public function executar_query_string($args) {
		if(empty($args['query'])) return NULL;
		
		$tipo = isset($args['tipo']) ? $args['tipo'] : FALSE;
		if(!empty($args['associative'])) {
			if($itens = $this->db->query($args['query'])->result()) {
				$its = array();
				$name = $args['associative'];
				foreach($itens as $item) $its[$item->$name] = $item;
				$itens = $its;
			}
			return $itens;
		}
		
		return $tipo ? $this->db->query($args['query'])->$tipo() : $this->db->query($args['query']);
	}
	
	public function remover_dados_tabela($args = array()) {
		if(!$args) return FALSE;
		
		// Confirgurações
		if(!isset($args['tabela'])) $args['tabela'] = '';
		if(!isset($args['_tabela'])) $args['_tabela'] = NULL;
		
		// Onde inserir
		if(isset($args['where_in'])) foreach($args['where_in'] as $k => $o) $this->db->where_in($k, $o);
		if(isset($args['where'])) $this->db->where($args['where']);
		if(isset($args['or_where'])) foreach($args['or_where'] as $k => $o) $this->db->or_where($o[0], $o[1]);
		if(isset($args['like'])) $this->db->like($args['like']);
		if(isset($args['or_like'])) foreach($args['or_like'] as $k => $o) $this->db->or_like($o[0], $o[1]);
		
		return $this->db->delete($args['_tabela'] ? $this->prefixo_db . $args['_tabela'] : $this->_tabela . $args['tabela']);
	}
	
	public function buscar_nome_sessao() {
		return $this->sessao();
	}
	
	public function campo_unico($valor = NULL, $campo = NULL, $tabela = NULL) {
		if(!$valor || !$campo || !$tabela) return FALSE;
		
		$args = array('where' => array($campo => $valor), '_tabela' => $tabela);
		return ! ($this->buscar_qtd_resultados($args) > 0);
	}
	
	private function buscar_config_admin() {
		if(!empty($this->___dados_usados['admin_config'])) return $this->___dados_usados['admin_usuario'];
		$config = $this->buscar_resultado(array('_tabela' => 'admin', 'select' => 'usuarios_config', 'where' => array('id' => 1), 'linha' => 'usuarios_config'));
		return $config ? ($this->___dados_usados['admin_config'] = unserialize($config)) : NULL;
	}
	
	public function gerar_nome_pasta_usuario($user = NULL, $caminho_imagem = NULL, $retornar_nome = FALSE) {
		if(!$user || !$caminho_imagem) return NULL;
		$nome_pasta = $this->gerar_token($user->id . $user->cadastrado, 'md5');

		if(!file_exists($caminho_imagem . $nome_pasta)){
			
			$img_path = $caminho_imagem . $nome_pasta;
			if(!file_exists($img_path)) {
				mkdir($img_path , 0777, true);
			}
		}

		return $retornar_nome ? $nome_pasta : (file_exists($caminho_imagem . $nome_pasta) ? NULL : $nome_pasta);
	}
	
	public function validar_post(&$args = array()) {
		if(!$args || !$args['post'] || !is_array($args['post'])) return !(bool)($args['erro'] = 'Ocorreu um erro tente novamente mais tarde#erro');
		
		// Array de validações padrão
		// O terceiro item do subarray indica se o campo é opcional
		// 'telefone2' => array('validarTelefone', 'Informe o segundo telefone corretamente#erro', 1), // Telefone 2 fica opcional
		$validar = array(
			'nome' => array('validarVazio', 'Informe o nome#erro'),
			'email' => array('validarEmail', 'Informe o email corretamente#erro'),
			'telefone1' => array('validarTelefone', 'Informe o primeiro telefone corretamente#erro'),
			'cep' => array('validarCEP', 'Informe o cep corretamente#erro'),
			'cnpj' => array('validarCNPJ', 'Informe o cnpj corretamente#erro'),
			'cpf' => array('validarCPF', 'Informe o cpf corretamente#erro'),
			'data_nascimento' => array('validarData', 'Informe a data de nascimento corretamente#erro')
		);
		
		// Acrescenta as novas validacoes
		if(isset($args['validacao_campos']) && is_array($args['validacao_campos'])) $validar = array_merge($validar, $args['validacao_campos']);
		
		// Ordena as validações da forma desejada
		if(isset($args['validacao_ordem']) && is_array($args['validacao_ordem'])) $validar = array_merge($args['validacao_ordem'], $validar);
		
		// Valida os dados do post um a um
		$this->load->library('Imgno_validacao', '', 'validacao');
		foreach($args['post'] as $k => $p) {
			if(!empty($validar[$k])) {
				if(empty($validar[$k][2]) || !empty($p)) {
					if(!$this->validacao->{$validar[$k][0]}($p)) return !(bool)($args['erro'] = $validar[$k][1]);
				}
			}
		}
		
		if(!isset($args['senha_obg'])) $args['senha_obg'] = TRUE;
		if(isset($args['post']['senha']) && (!empty($args['post']['senha']) || $args['senha_obg'])) {
			if(empty($args['post']['senha'])) return !(bool)($args['erro'] = 'Informe a senha#erro');
			if(isset($args['post']['senha2']) && empty($args['post']['senha2'])) return !(bool)($args['erro'] = 'Confirme sua senha#erro');
			if(!empty($args['post']['senha2']) && ($args['post']['senha'] != $args['post']['senha2'])) return !(bool)($args['erro'] = 'Confirmação de senha incorreta#erro');
		}
		
		// Retorna true caso o post seja valido
		return TRUE;
	}
	
	public function validar_token($token = NULL, $valor = NULL, $tipo = 'sha512', $chave = array('ç~ç66~/;;?*&5ç@dç~nÇ294', '~65ç46@nÇ29@')) {
		if(!$valor || !$token) return FALSE;
		return hash($tipo, $chave[0] . $valor . $chave[1]) === $token;
	}
	
	public function gerar_token($valor = NULL, $tipo = 'sha512', $chave = array('ç~ç66~/;;?*&5ç@dç~nÇ294', '~65ç46@nÇ29@')) {
		return hash($tipo, $chave[0] . $valor . $chave[1]);
	}
	
	public function buscar_dados_usuario($id = NULL) {
		if(!empty($this->___dados_usados['usuario']) && (($id !== NULL) && ($this->___dados_usados['usuario']->id == $id))) return $this->___dados_usados['usuario'];
		if(!$id) {
			if($this->usuario) $id = $this->usuario;
			else return NULL;
		}
		return $this->___dados_usados['usuario'] = $this->buscar_resultado(array('where' => array('id' => (int)$id), '_tabela' => 'usuarios'));
	}

	public function buscar_dados_cliente($id = NULL) {
		if(!$id) {
			return NULL;
		}
		return $this->___dados_usados['cliente'] = $this->buscar_resultado(array('where' => array('id' => (int)$id), '_tabela' => 'clientes'));
	}
	
	public function buscar_config_usuario($id = NULL) {
		if(!empty($this->___dados_usados['usuario_config'])) return $this->___dados_usados['usuario_config'];
		if(!$id) {
			if($this->usuario) $id = $this->usuario;
			else return NULL;
		}
		return $this->___dados_usados['usuario_config'] = unserialize($this->buscar_resultado(array('select' => 'config', '_tabela' => 'usuarios', 'linha' => 'config', 'where' => array('id' => $id))));
	}
	
	public function buscar_nome_usuario($id = NULL) {
		if(!empty($this->___dados_usados['usuario_nome'])) return $this->___dados_usados['usuario_nome'];
		if(!$id) {
			if($this->usuario) $id = $this->usuario;
			else return NULL;
		}
		return $this->___dados_usados['usuario_nome'] = $this->buscar_resultado(array('select' => 'nome', '_tabela' => 'usuarios', 'linha' => 'nome', 'where' => array('id' => $id)));
	}
	
	public function buscar_nome_admin($id = NULL) {
		if(!empty($this->___dados_usados['admin_nome'])) return $this->___dados_usados['admin_nome'];
		if(!$id) {
			if($this->admin) $id = $this->admin;
			else return NULL;
		}
		return $this->___dados_usados['admin_nome'] = $this->buscar_resultado(array('select' => 'nome', '_tabela' => 'admin', 'linha' => 'nome', 'where' => array('id' => $id)));
	}
	
	// Verifica se o usuario tem as permissões necessárias
	public function permissao(&$args) {
		if(empty($args['tipo'])) return FALSE;
		$sessao = empty($args['sessao']) ? $this->sessao_tipo : $args['sessao'];
		$permissao_tipo = $args['tipo'];
		$permissao_nome = $sessao .'.permissoes.'. $permissao_tipo;
		if(isset($this->permissoes[$permissao_nome])) return $this->permissoes[$permissao_nome];
		if(isset($this->permissoes['user_config'][$permissao_tipo])) return $this->permissoes['user_config'][$permissao_tipo];
		
		$permissao = FALSE;
		switch($permissao_tipo) {
			case 'usuario_acesso_todas_listas':
				// Verifica se o usuario tem acesso a todas as listas
				$query = array('select' => 'config', 'where' => array('id' => (int)$this->logado('usuario')), 'linha' => 'config');
				if(!($permissoes = $this->buscar_resultado($query))) return FALSE;
				if(($permissoes = unserialize($permissoes)) && empty($this->permissoes['user_config'])) $this->permissoes['user_config'] = $permissoes;
				$this->permissoes[$permissao_nome] = $permissao = !(bool)empty($permissoes['usuario_acesso_todas_listas']);
				//$this->salvar_sessao($permissao_nome, $permissao);
			break;
			
			case 'usuario_acesso_modulo_email_marketing':
				// Verifica se o usuario tem acesso a todas as listas
				$query = array('select' => 'config', 'where' => array('id' => (int)$this->logado('usuario')), 'linha' => 'config');
				if(!($permissoes = $this->buscar_resultado($query))) return FALSE;
				if(($permissoes = unserialize($permissoes)) && empty($this->permissoes['user_config'])) $this->permissoes['user_config'] = $permissoes;
				$this->permissoes[$permissao_nome] = $permissao = !(bool)empty($permissoes['usuario_acesso_modulo_email_marketing']);
				//$this->salvar_sessao($permissao_nome, $permissao);
			break;
			
			case 'usuario_acesso_modulo_crm':
				// Verifica se o usuario tem acesso a todas as listas
				$query = array('select' => 'config', 'where' => array('id' => (int)$this->logado('usuario')), 'linha' => 'config');
				if(!($permissoes = $this->buscar_resultado($query))) return FALSE;
				if(($permissoes = unserialize($permissoes)) && empty($this->permissoes['user_config'])) $this->permissoes['user_config'] = $permissoes;
				$this->permissoes[$permissao_nome] = $permissao = !(bool)empty($permissoes['usuario_acesso_modulo_crm']);
			break;
			
			case 'usuario_acesso_modulo_crm_cliente':
				if($this->permissao($perm = array('tipo' => 'usuario_acesso_modulo_crm'))) {
					// Verifica se o usuario tem acesso a todas as listas
					$query = array('select' => 'config', 'where' => array('id' => (int)$this->logado('usuario')), 'linha' => 'config');
					if(!($permissoes = $this->buscar_resultado($query))) return FALSE;
					if(($permissoes = unserialize($permissoes)) && empty($this->permissoes['user_config'])) $this->permissoes['user_config'] = $permissoes;
					$this->permissoes[$permissao_nome] = $permissao = !(bool)empty($permissoes['usuario_acesso_modulo_crm_clientes']);
				}
			break;
			
			case 'usuario_acesso_modulo_crm_emails_automaticos':
				if($this->permissao($perm = array('tipo' => 'usuario_acesso_modulo_crm'))) {
					// Verifica se o usuario tem acesso a todas as listas
					$query = array('select' => 'config', 'where' => array('id' => (int)$this->logado('usuario')), 'linha' => 'config');
					if(!($permissoes = $this->buscar_resultado($query))) return FALSE;
					if(($permissoes = unserialize($permissoes)) && empty($this->permissoes['user_config'])) $this->permissoes['user_config'] = $permissoes;
					$this->permissoes[$permissao_nome] = $permissao = !(bool)empty($permissoes['usuario_acesso_modulo_crm_emails_automaticos']);
				}
			break;
			
			case 'usuario_acesso_modulo_crm_configuracoes':
				if($this->permissao($perm = array('tipo' => 'usuario_acesso_modulo_crm'))) {
					// Verifica se o usuario tem acesso a todas as listas
					$query = array('select' => 'config', 'where' => array('id' => (int)$this->logado('usuario')), 'linha' => 'config');
					if(!($permissoes = $this->buscar_resultado($query))) return FALSE;
					if(($permissoes = unserialize($permissoes)) && empty($this->permissoes['user_config'])) $this->permissoes['user_config'] = $permissoes;
					$this->permissoes[$permissao_nome] = $permissao = !(bool)empty($permissoes['usuario_acesso_modulo_crm_configuracoes']);
				}
			break;
			
			case 'usuario_acesso_modulo_gestor':
				// Verifica se o usuario tem acesso a todas as listas
				$query = array('select' => 'config', 'where' => array('id' => (int)$this->logado('usuario')), 'linha' => 'config');
				if(!($permissoes = $this->buscar_resultado($query))) return FALSE;
				if(($permissoes = unserialize($permissoes)) && empty($this->permissoes['user_config'])) $this->permissoes['user_config'] = $permissoes;
				$this->permissoes[$permissao_nome] = $permissao = !(bool)empty($permissoes['usuario_acesso_modulo_gestor']);
				//$this->salvar_sessao($permissao_nome, $permissao);
			break;
			
			case 'usuario_acesso_modulo_usuarios':
				// Verifica se o usuario tem acesso a todas as listas
				$query = array('select' => 'config', 'where' => array('id' => (int)$this->logado('usuario')), 'linha' => 'config');
				if(!($permissoes = $this->buscar_resultado($query))) return FALSE;
				if(($permissoes = unserialize($permissoes)) && empty($this->permissoes['user_config'])) $this->permissoes['user_config'] = $permissoes;
				$this->permissoes[$permissao_nome] = $permissao = !(bool)empty($permissoes['usuario_acesso_modulo_usuarios']);
				//$this->salvar_sessao($permissao_nome, $permissao);
			break;
			
			case 'usuario_acesso_config_principal':
				// Verifica se o usuario tem acesso a todas as listas
				$query = array('select' => 'config', 'where' => array('id' => (int)$this->logado('usuario')), 'linha' => 'config');
				if(!($permissoes = $this->buscar_resultado($query))) return FALSE;
				if(($permissoes = unserialize($permissoes)) && empty($this->permissoes['user_config'])) $this->permissoes['user_config'] = $permissoes;
				$this->permissoes[$permissao_nome] = $permissao = !(bool)empty($permissoes['usuario_acesso_config_principal']);
				//$this->salvar_sessao($permissao_nome, $permissao);
			break;
			
			case 'todos_emails':
				// Verifica se o usuario tem acesso a todos os emails
				$query = array('select' => 'acesso_todas_listas', 'where' => array('id' => (int)$this->logado('usuario')));
				$res = $this->buscar_resultado($query);
				$this->permissoes[$permissao_nome] = $permissao = @(int)$res->acesso_todas_listas;
			break;
			
			// Verifica se o usuario tem permissão para atualizar seus envios( Somente o admin tem permissão )
			case 'atualizar_envios_usuario':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('admin');
			break;
			
			case 'atualizar_usuario':
				// Verfica se essa requisição vem do mesmo usuário que será modificado ou do admin
				if(!($admin = (int)$this->logado('admin')) && ($usuario = (int)$this->logado('usuario'))) {
					$_args = array(
						'_tabela' => 'usuarios',
						'select' => 'id',
						'where' => array('user_pai' => (int)$usuario),
						'where_in' => array('id' => @$args['itens'])
					);
					if(!($args['item'] = $this->busca_array_valores($this->buscar_resultados($_args), 'id'))) return FALSE;
				}
				
				// Concede ou a permissão caso seja mesmo usuário logado ou o admin
				$this->permissoes[$permissao_nome] = $permissao = $admin ? TRUE : ($this->usuario_pai ? FALSE : $usuario);
				//$this->salvar_sessao($permissao_nome, $permissao = (int)$this->logado('admin') ? TRUE : ($this->logado('usuario_pai') ? FALSE : $usuario));
			break;
			
			case 'excluir_usuario':
				// Verfica se essa requisição vem do mesmo usuário que será modificado ou do admin
				if(!($admin = (int)$this->logado('admin')) && ($usuario = (int)$this->logado('usuario'))) {
					$_args = array(
						'_tabela' => 'usuarios',
						'select' => 'id',
						'where' => array('user_pai' => (int)$usuario),
						'where_in' => array('id' => $args['itens'])
					);
					if(!($args['item'] = $this->busca_array_valores($this->buscar_resultados($_args), 'id'))) return FALSE;
				}
				
				// Concede ou a permissão caso seja mesmo usuário logado ou o admin
				$this->permissoes[$permissao_nome] = $permissao = $admin ? TRUE : ($this->usuario_pai ? FALSE : $usuario);
			break;
			
			case 'zerar_envios':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('admin');
			break;
			
			case 'excluir_contas_smtp':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('admin');
			break;
			
			case 'excluir_contas':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('admin');
			break;
			
			case 'atualizar_conta_smtp':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('admin');
			break;
			
			case 'verificar_contas_smtp_selecionadas':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('admin');
			break;
			
			case 'buscar_lancamentos_abertos_cliente':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('admin');
			break;
			
			case 'confirmar_pagamento':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('admin');
			break;
			
			case 'desabilitar_lancamentos':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('admin');
			break;
			
			case 'arquivar_lancamentos':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('admin');
			break;
			
			case 'excluir_listas_usuario':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'converter_em_cliente':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'parar_envio_campanha':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'enviar_email_padrao':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'atualizar_rastreio_emails':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'excluir_leads':
				// Verfica se essa requisição vem do mesmo usuário que será modificado
				if($usuario = (int)$this->logado('usuario')) {
					$_args = array(
						'_tabela' => 'usuario_solicitacao',
						'select' => 'id',
						'where' => array('user_id' => (int)$usuario, 'cli_status' => 0),
						'where_in' => array('id' => $args['itens'])
					);
					if(!$this->buscar_resultados($_args)) return FALSE;
				}
				$this->permissoes[$permissao_nome] = $permissao = !$this->usuario_pai && $usuario;
			break;
			
			case 'excluir_clientes':
				// Verfica se essa requisição vem do mesmo usuário que será modificado
				if($usuario = (int)$this->logado('usuario')) {
					$_args = array(
						'_tabela' => 'usuario_solicitacao',
						'select' => 'id',
						'where' => array('user_id' => (int)$usuario, 'cli_status' => 1),
						'where_in' => array('id' => $args['itens'])
					);
					if(!$this->buscar_resultados($_args)) return FALSE;
				}
				$this->permissoes[$permissao_nome] = $permissao = !$this->usuario_pai && $usuario;
			break;
			
			case 'excluir_assinantes_usuario':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'excluir_campanhas_usuario':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'duplicar_campanhas_usuario':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'enviar_campanha_teste':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'enviar_campanha':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'atualizar_erros_envio':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'excluir_emails_automaticos_usuario':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'excluir_assinantes_entre':
				$this->permissoes[$permissao_nome] = $permissao = (int)$this->logado('usuario');
			break;
			
			case 'descadastrar_assinante':
				$this->permissoes[$permissao_nome] = $permissao = 1;
			break;
			
			default :
				$permissao = FALSE;
			break;
		}
		
		return $permissao;
	}
	
	// Converte Min para hora e hora para min
	public function hora_min_hora($t, $h = FALSE) {
		if($h){ return sprintf("%02d:%02d", ($t - ($a = $t%60))/60, $a); }
		else { if(!preg_match('@^([01]\d|2[0-3]):([0-5]\d)$@', $t)) return FALSE; $t = explode(':', $t); return $t[0]*60 + $t[1]; }
	}
	
	public function busca_array_valores($res = NULL, $key = NULL) {
		if(!$res || !is_array($res) || !$key) return array();
		
		$resultados = array();
		foreach($res as $r) $resultados[] = $r->$key;
		return $resultados;
	}
	
	public function buscar_qtd_resultados($args = array()) {
		
		// Configurações
		if(empty($args['select'])) $args['select'] = 'id';
		if(!isset($args['tabela'])) $args['tabela'] = '';
		if(!isset($args['_tabela'])) $args['_tabela'] = NULL;
		if(!isset($args['escape_select'])) $args['escape_select'] = NULL;
		
		// Itens buscadas no momento
		$this->db->select($args['select'], $args['escape_select']);
		$this->db->from($args['_tabela'] ? $this->prefixo_db . $args['_tabela'] : $this->_tabela . $args['tabela']);
		if(isset($args['join'])) {
			if($args['_tabela']) {
				foreach($args['join'] as $join) {
					if(isset($join[2])) $this->db->join($this->prefixo_db . $join[0], $join[1], $join[2]);
					else $this->db->join($this->prefixo_db . $join[0], $join[1]);
				}
			} else {
				foreach($args['join'] as $join) {
					if(isset($join[2])) $this->db->join($this->_tabela . $join[0], $join[1], $join[2]);
					else $this->db->join($this->_tabela . $join[0], $join[1]);
				}
			}
		}
		if(isset($args['where_in'])) foreach($args['where_in'] as $k => $o) $this->db->where_in($k, $o);
		if(isset($args['where'])) $this->db->where($args['where']);
		if(isset($args['or_where'])) foreach($args['or_where'] as $k => $o) $this->db->or_where($o[0], $o[1]);
		if(isset($args['like'])) {
			foreach($args['like'] as $k => $row){
				if(is_array($row)){
					$this->db->like($k,$row[0],$row[1]);
				}else{
					$this->db->like($k,$row);
				}
			}
		}
		//if(isset($args['like'])) $this->db->like($args['like']);
		if(isset($args['or_like'])) foreach($args['or_like'] as $k => $o) $this->db->or_like($o[0], $o[1]);
		if(isset($args['group_by'])) $this->db->group_by($args['group_by']);
		if(isset($args['having'])) $this->db->having($args['having']);
		if(isset($args['distinct'])) $this->db->distinct();

		return $this->db->count_all_results();
	}
	
	public function buscar_resultados($args = array()) {
		// Caso não haja ragumentos, retorna um array vazio
		if(!$args) return array();
		
		// Configurações
		if(!isset($args['associative'])) $args['associative'] = NULL;
		if(!isset($args['return_array'])) $args['return_array'] = NULL;
		if(!isset($args['select'])) $args['select'] = '*';
		if(!isset($args['tabela'])) $args['tabela'] = '';
		if(!isset($args['_tabela'])) $args['_tabela'] = NULL;
		if(!isset($args['escape_select'])) $args['escape_select'] = NULL;
		if(!isset($args['return_query'])) $args['return_query'] = FALSE;
		
		// Itens buscados no momento
		$this->db->select($args['select'], $args['escape_select']);
		$this->db->from($args['_tabela'] ? $this->prefixo_db . $args['_tabela'] : $this->_tabela . $args['tabela']);
		if(isset($args['join'])) {
			if($args['_tabela']) {
				foreach($args['join'] as $join) {
					if(isset($join[2])) $this->db->join($this->prefixo_db . $join[0], $join[1], $join[2]);
					else $this->db->join($this->prefixo_db . $join[0], $join[1]);
				}
			} else {
				foreach($args['join'] as $join) {
					if(isset($join[2])) $this->db->join($this->_tabela . $join[0], $join[1], $join[2]);
					else $this->db->join($this->_tabela . $join[0], $join[1]);
				}
			}
		}
		if(isset($args['where_in'])) foreach($args['where_in'] as $k => $o) $this->db->where_in($k, $o);
		if(isset($args['where'])) $this->db->where($args['where']);
		if(isset($args['or_where'])) foreach($args['or_where'] as $k => $o) $this->db->or_where($o[0], $o[1]);
		if(isset($args['like'])) $this->db->like($args['like']);
		if(isset($args['or_like'])) foreach($args['or_like'] as $k => $o) $this->db->or_like($o[0], $o[1]);
		if(isset($args['order_by'])) foreach($args['order_by'] as $k => $o) $this->db->order_by($k, $o);
		if(isset($args['having'])) $this->db->having($args['having']);
		if(isset($args['group_by'])) $this->db->group_by($args['group_by']);
		if(isset($args['limit'])) $this->db->limit($args['limit'][0], $args['limit'][1]); // start, limit
		
		// Retorna o objeto da query
		if($args['return_query']) return $this->db->get();
		
		if(($itens = $this->db->get()->result()) && $args['associative']) {
			$its = array();
			$name = $args['associative'];
			foreach($itens as $item) $its[$item->$name] = $item;
			$itens = $its;
		} elseif($itens && $args['return_array']) {
			$itens = $this->busca_array_valores($itens, $args['return_array']);
		}
		
		return $itens;
	}
	
	public function buscar_resultado($args = array()) {
		// Configurações
		if(!isset($args['select'])) $args['select'] = '*';
		if(!isset($args['tabela'])) $args['tabela'] = '';
		if(!isset($args['_tabela'])) $args['_tabela'] = NULL;
		if(!isset($args['linha'])) $args['linha'] = 0;
		if(!isset($args['escape_select'])) $args['escape_select'] = NULL;
		if(!isset($args['return_query'])) $args['return_query'] = FALSE;
		
		// Itens buscados no momento
		$this->db->select($args['select'], $args['escape_select']);
		$this->db->from($args['_tabela'] ? $this->prefixo_db . $args['_tabela'] : $this->_tabela . $args['tabela']);
		if(isset($args['join'])) {
			if($args['_tabela']) {
				foreach($args['join'] as $join) {
					if(isset($join[2])) $this->db->join($this->prefixo_db . $join[0], $join[1], $join[2]);
					else $this->db->join($this->prefixo_db . $join[0], $join[1]);
				}
			} else {
				foreach($args['join'] as $join) {
					if(isset($join[2])) $this->db->join($this->_tabela . $join[0], $join[1], $join[2]);
					else $this->db->join($this->_tabela . $join[0], $join[1]);
				}
			}
		}
		if(isset($args['where_in'])) foreach($args['where_in'] as $k => $o) $this->db->where_in($k, $o);
		if(isset($args['where'])) $this->db->where($args['where']);
		if(isset($args['or_where'])) foreach($args['or_where'] as $k => $o) $this->db->or_where($o[0], $o[1]);
		if(isset($args['like'])) $this->db->like($args['like']);
		if(isset($args['or_like'])) foreach($args['or_like'] as $k => $o) $this->db->or_like($o[0], $o[1]);
		
		// Retorna o objeto da query
		if($args['return_query']) return $this->db->get();

		return $this->db->get()->row($args['linha']);
	}
	
	public function buscar_toda_sessao() {
		return $this->session->all_userdata();
	}
	
	public function buscar_sessao($chave = NULL) {
		return $chave ? $this->session->userdata($chave) : FALSE;
	}
	
	public function remover_sessao($chave = NULL) {
		return $chave ? $this->session->unset_userdata($chave) : FALSE;
	}
	
	public function salvar_sessao($chave, $valor = NULL) {
		if(is_array($chave)) $this->session->set_userdata($chave);
		else $this->session->set_userdata($chave, $valor);
	}
	
	public function csv_in_array($url, $head = FALSE, $delm = ";", $encl = "\"") {
	   
		$csvxrow = file($url);   // ---- csv rows to array ----
		$csvxrow[0] = preg_replace('#^(.*?)(;)?$#', '$1', chop($csvxrow[0]));
		$csvxrow[0] = str_replace($encl,'',$csvxrow[0]);
		$keydata = explode($delm,$csvxrow[0]);
		$keynumb = count($keydata);

		if($head === true) {
			$anzdata = count($csvxrow);
			$z=0;
			for($x=1; $x<$anzdata; $x++) {
				$csvxrow[$x] = preg_replace('#^(.*?)(;)?$#', '$1', chop($csvxrow[$x]));
				$csvxrow[$x] = str_replace($encl,'',$csvxrow[$x]);
				$csv_data[$x] = explode($delm,$csvxrow[$x]);
				$i=0;
				foreach($keydata as $key) {
					$out[$z][$key] = isset($csv_data[$x][$i]) ? $csv_data[$x][$i] : '';
					$i++;
				}
				$z++;
			}
		} else {
			$i=0;
			foreach($csvxrow as $item) {
				$item = chop($item);
				$item = str_replace($encl,'',$item);
				$csv_data = explode($delm,$item);
				for ($y=0; $y<$keynumb; $y++) {
				   $out[$i][$y] = $csv_data[$y];
				}
				$i++;
			}
		}
		
		return $out;
	}
	
	public function sort_array_by_key(&$array = array(), $key = NULL, $ordem = 'desc') {
		if(!$array || !$key) return array();
		$this->sort_array_key = $key;
		usort($array, array($this, 'sort_array_by_key_'. strtolower($ordem)));
	}
	
	public function sort_array_by_key_desc($a, $b) {
		$key = $this->sort_array_key;
		return $b->$key - $a->$key;
	}
	
	public function sort_array_by_key_asc($a, $b) {
		$key = $this->sort_array_key;
		return $a->$key - $b->$key;
	}
	
	// Converte o nome
	public function parseName( $nome ) {
		$parts = @explode( '.', $nome );
		$extensao = strtolower( $parts[ count( $parts ) - 1 ] );
		unset( $parts[ count( $parts ) - 1 ] );
		return rand( 0, 20 ) . time() . rand( 0, 20 ) . $this->util->getAlias(@implode( '.', $parts )) .'.'. $extensao;
	}
	
	public function logado($sessao = '') {
		return $this->buscar_sessao($sessao .'.id_usuario');
	}
	
	public function validar_token_usuario($token = NULL, $remover_token = FALSE) {
		if(!$token) return FALSE;
		$args = array(
			'like' => array('token_crm_envio' => $token = $this->util->base64tourltobase64($token, TRUE)),
			'_tabela' => 'usuario'
		);
		if($this->usuario_pai || $this->usuario) $args['where'] = array('id' => ($this->usuario_pai ? $this->usuario_pai : $this->usuario));
		if(!($usuario = $this->buscar_resultado($args)) || !($token_real = $usuario->token_crm_envio)) return FALSE;
		
		// Valida o token de acesso
		$token_real = explode(':', $token_real);
		if($this->util->base64tourltobase64($token, TRUE) === $token_real[2]) {
			if($remover_token) $this->remover_token_crm_envio($usuario);
			return $usuario;
		} else $this->remover_token_crm_envio($usuario);
		
		return FALSE;
	}
	
	//Buscar Tags
	public function buscar_info_tags(){
		$d =  $this->db->query('SELECT * FROM '.$this->prefixo_db.'usuario_tags')->result();
		$dados = array();
		foreach($d as $row) $dados[$row->id] = $row;
		return $dados;
	}
	
	public function validar_acesso_usuario($acesso){
		if($this->usuario_bloqueado) return false;
		if(!$this->buscar_sessao('usuario.id_usuario')) return false;
		if($this->buscar_sessao('admin.id_usuario')) return true;
		if($this->buscar_sessao('usuario.id_usuario_imagine')) return true;
		$usuario = (int)$this->buscar_sessao('usuario.id_usuario');
		$config = $this->db->query('SELECT config FROM '.$this->prefixo_db.'usuario WHERE id = '.(int)$usuario)->row('config');
		$config = unserialize($config);
		if($config[$acesso] == 1) return true;
		else return false;
	}
	
	
	public function mensagem_aviso($msg = NULL, $exibir = FALSE, $local = 'mensagem') {
		if($msg) {
			// Salva os dados
			if($serial = serialize($msg)) return $this->salvar_sessao($local, $serial);
		} else {
			// Obtem os dados armazenados
			$data = unserialize($this->buscar_sessao($local));
			
			// Remove os dados desnecessários
			$this->remover_sessao($local);
			
			return $data;
		}
	}

	public function remover_caracteres_moeda($valor){
		return str_replace(',', ".", str_replace(array('R$ ','.'), "", $valor) );
	}

	public function moeda($valor, $cifrao = false){

		return ($cifrao == true?'R$ ':'').number_format($valor,2,",",".");;
	}

	public function formatarHorarioPT($string){
		$data_object = new DateInterval($string); // PT18H30M

		return date('H:i',strtotime($data_object->h.':'.$data_object->i.':'.$data_object->s));
	}


	public function carrinho_remover_sessao() {
		$this->session->unset_userdata('carrinho_sessao');
	}

	public function is_exite_carrinho() {
		
		$chave = 'carrinho_sessao';
		$result = $this->session->userdata($chave);

		if(!$result){
			return false;
		}else{
			return true;
		}
		
		
	}
	
	public function carrinho_sessao() {
		
		$chave = 'carrinho_sessao';
		$result = $this->session->userdata($chave);

		if(!$result){
			$sessao = $this->carrinhoCodigoSessao();
			$this->session->set_userdata($chave, $sessao);
		}else{
			$sessao = $result;
		}
		
		return $sessao;
	}

	public function carrinhoCodigoSessao()
	{
		
		if (function_exists('random_bytes')) {
			$session_id = substr(bin2hex(random_bytes(26)), 0, 26);
		} else {
			$session_id = substr(bin2hex(openssl_random_pseudo_bytes(26)), 0, 26);
		}

		return $session_id;
	}

	

	public function verificar_permissao($permissao){
		if(!$permissao) return false;

		$dado = $this->db->query('SELECT id FROM '.$this->prefixo_db.'usuarios_permissoes WHERE id_usuario = '.$this->usuario.' AND permissao = "'.$permissao.'"')->row_array();

		if($dado){
			return true;
		}else{
			return false;
		}
	}

	

	public function verificar_permissao_admin($permissao){
		if(!$permissao) return false;

		$dado = $this->db->query('SELECT id FROM '.$this->prefixo_db.'admin_permissoes WHERE id_usuario = '.$this->admin.' AND permissao = "'.$permissao.'"')->row_array();

		if($dado){
			return true;
		}else{
			return false;
		}
	}
	
	// Construtor
	public function __construct($args = NULL) {
		parent::__construct();
		// Carrega database
		$this->load->database();
		// Incialização da variável de dados
		$this->dados_req = array();
		// Prefixo da database
		$this->prefixo_db = $this->db->dbprefix;
		
		// Configurações do model
		$nome_model = explode('_', strtolower(get_class($this)));
		$this->_tabela = $this->prefixo_db . $nome_model[2];
		
		// Id do usuario
		$sessoes = array(
			'admin' => 'admin', 
			'usuario' => 'usuario',
			'cliente' => 'cliente',
		);
		foreach($sessoes as $sessao) $this->$sessao = $this->session->userdata($sessao .'.id_usuario');
		$this->sessao_tipo = isset($sessoes[$nome_model[2]]) ? $sessoes[$nome_model[2]] : ($this->session->userdata('admin.id_usuario') ? 'admin' : 'usuario');
		$name_class = get_class($this);
		if(!in_array($name_class,array('Admin_model_admin','Ajax_model_ajax'))){
			$usuario_pai = $this->session->userdata('usuario_pai.id_usuario');
			$usuario = $this->session->userdata('usuario.id_usuario');
			
			$user_id = $this->session->userdata('usuario.id_usuario');
		}else{
			$admin = $this->session->userdata('admin.id_usuario');
		}
		// Lista de permissoes do usuário
		if(empty($this->permissoes['user_config'])) $this->permissoes['user_config'] = array();
	}
	
	// Variaveis
	
	public $___dados_usados = array();
	public $permissoes = array();
}

/* Fim do arquivo Base_model.php */
/* Local: ./application/base/Base_model.php */