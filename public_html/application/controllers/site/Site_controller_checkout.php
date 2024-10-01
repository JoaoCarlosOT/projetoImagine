<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Site_controller_checkout extends Base_controller {
	// Valida o acesso a pagina
	private function validar_acesso() {
		if(!$this->model->logado('cliente')) return $this->red_pagina('checkout-email',FALSE);
	}

	function criar_session_assinatura(){
		if($param = $this->input->get()){
			if(!empty($param['id_produto_assinatura'])){
				$produto = $this->model->db->query('SELECT * FROM '.$this->model->prefixo_db.'institucional_produto WHERE id = '.(int)$param['id_produto_assinatura'].' AND ativarVenda = 1 AND assinatura = 1 AND situacao = 1')->row();
				
				if($produto){
					$dados_produto = array(
						'id_produto' => $param['id_produto_assinatura'],
						'quantidade' => !empty($param['qtd'])?!empty($param['qtd']):1,
					);	

					$this->session->set_userdata('produto_assinatura', $dados_produto);
					return $dados_produto;
				}
			}
		}
		$this->session->unset_userdata('produto_assinatura');
		return false;
	}

    function email(){
		$this->criar_session_assinatura(); 
		$produto_assinatura = $this->session->userdata('produto_assinatura');

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'checkout email';

		if($this->model->logado('cliente')) $this->red_pagina('checkout',FALSE);

		$this->dados["dados"]["carrinho"] = $this->model->buscar_carrinho();
		if(!$this->dados["dados"]["carrinho"] && !$produto_assinatura){
			$this->red_pagina(false,false,'#erroSeu carrinho está vazio');
		}

		if($post = $this->input->post()) {
			$post['usuario'] =  $post['cpf_cnpj'];

			$args = array(
				'post' => $post,
				'erro' => '',
				'chave' => 'cpf_cnpj',
				'_tabela' => 'clientes'
			);
			
			if($this->model->login($args,'cliente')) {
				$this->red_pagina('checkout',false);
			}else{
				$this->msg["erro"] = 'Verifique se os dados estão corretos!';
				$this->dados["dados"]["resultado"] = $post;
			}
			
		}

		$this->dados["dados"]["buscar_configuracao"] = $this->model->buscar_configuracao();

        $this->add_css(array(
			'template_1',
		));

		$this->add_css(array(
			'template_2',
		),false,2);
		

		$this->html_pagina('checkout-email',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}
	
	function recuperar_senha(){

		if($this->model->logado('cliente')) $this->red_pagina('checkout',FALSE);
		$produto_assinatura = $this->session->userdata('produto_assinatura');

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Recuperar senha';

		$this->dados["dados"]["carrinho"] = $this->model->buscar_carrinho();
		if(!$this->dados["dados"]["carrinho"] && !$produto_assinatura){
			// $this->red_pagina(false,false,'#erroSeu carrinho está vazio');
		}

		if($post = $this->input->post()) {
			
			$this->load->model('cliente/Cliente_model_cliente','model_cli');

			$resultado = $this->model_cli->recuperar_senha($post['cpf_cnpj']);

			if($resultado["retorno"] == 1){
				$this->red_pagina('checkout-recuperar',FALSE,"#Enviamos para seu email");
			}else{
				$this->red_pagina('checkout-recuperar',FALSE,"#erro".$resultado["msg"]);
			}
			
			
		}

		$this->dados["dados"]["buscar_configuracao"] = $this->model->buscar_configuracao();

        $this->add_css(array(
			'template_1',
			'conta',
		)); 
		

		$this->html_pagina('checkout-recuperar',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}
	
	function alterar_senha($token = null){
		
		if(!$token){
			$this->red_pagina(false,FALSE,"#erroToken Inválido");
			exit;
		}

		$dados_token = $this->model->db->query('SELECT * FROM '.$this->model->prefixo_db.'clientes_recuperar_senha WHERE token = "'.$token.'" AND usado = 0 AND cadastrado >= "'.date('Y-m-d H:i:s', strtotime('-1 day')).'" ')->row();

		if(!$dados_token){
			$this->red_pagina(false,FALSE,"#erroToken Inválido");
			exit;
		}

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Alterar senha';

		if($post = $this->input->post()) {

			$this->load->model('cliente/Cliente_model_cliente','model_cli');
			$resultado = $this->model_cli->alterar_senha_token($post);
			
			if($resultado['retorno'] == 1){
				$this->red_pagina(false,FALSE,"#Salvo com sucesso");
			}else{
				$this->red_pagina(false,FALSE,"#erro".$resultado["msg"]);
			}
			
		}

		$this->dados["dados"]["token"] = $token;
		// $this->dados["dados"]["resultado"] = $dados_token;

		$this->add_css(array(
			'template_1',
			'conta',
		)); 

		$this->html_pagina('checkout-senha',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}

    function cadastrar(){

		if($this->model->logado('cliente')) $this->red_pagina('checkout',FALSE);

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Cadastro';

		$this->dados["dados"]["resultado"] = null;

		$resultado['msg'] = null;
		if($post = $this->input->post()) {

			$codigo_cupom = (!empty($post['codigo_cupom'])?'codigo_cupom='.$post['codigo_cupom']: "");

			$this->load->model('cliente/Cliente_model_cliente','model_cli');
			$resultado = $this->model_cli->cadastrar_conta($post);
			
			if($resultado['retorno'] == 1){

				$args = array(
					'post' => array(
						'usuario'=> $post['cpf_cnpj'],
						'senha'=> $post['senha'],
					),
					'erro' => '',
					'chave' => 'cpf_cnpj',
					'_tabela' => 'clientes'
				);

				if($this->model->login($args,'cliente')) {
					$this->red_pagina('checkout',false,$codigo_cupom);
				}

			}else{
				$this->msg["erro"] = $resultado["msg"];
				$this->dados["dados"]["resultado"] = $resultado['dados'];
			}
			
		}

		$this->red_pagina('checkout-email',FALSE,($resultado['msg']?'#erro'.$resultado['msg']:''));

        $this->add_css(array(
			'template_1',
			'popup'
		));

		$this->add_css(array(
			'template_2',
		),false,2);

		$this->add_script(array(
			'popup'
		));
		

		$this->html_pagina('checkout-cadastrar',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}
	
	function atualizar(){

		if(!$this->model->logado('cliente')) $this->red_pagina('checkout',FALSE);

		if($post = $this->input->post()) {

			$this->load->model('cliente/Cliente_model_cliente','model_cli');
			$resultado = $this->model_cli->salvar_conta($post);


			if($resultado['retorno'] == 1){

				$this->red_pagina('checkout',false,'#Salvo com sucesso');

			}else{
				$this->red_pagina('checkout',false,'#erro'.$resultado["msg"]);
			}
			
		}
		exit;
    }

    function checkout(){

		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Checkout';

		$this->dados["dados"]["carrinho"] = $this->model->buscar_carrinho();
		$this->dados["dados"]["produto_assinatura"] = $this->model->buscar_produto_assinatura();

		if(!$this->dados["dados"]["carrinho"] && !$this->dados["dados"]["produto_assinatura"]){
			$this->red_pagina(false,false);
		}

		$this->load->library('Imgno_asaas', '', 'asaas');

		if($this->model->logado('cliente')){
			$this->load->model('cliente/Cliente_model_cliente','model_cli');
			$resultado = $this->model_cli->buscar_conta();

			$this->dados["dados"]["logado"] = true;
			$this->dados["dados"]["cliente"] = $resultado;

			$this->model->atualizar_dados_carrinho(array(
				'cpf_cnpj' => $resultado->cpf_cnpj,
				'id_cliente' => $resultado->id
			));
		}else{
			$this->dados["dados"]["logado"] = false;
		}
		

		$this->dados["dados"]["login_cpf_cnpj"] = $this->model->session->userdata('login_cpf_cnpj');
		
		$this->dados["dados"]["buscar_configuracao"] = $this->model->buscar_configuracao();

        $this->add_css(array(
			'template_1',
			'popup'
		));

		$this->add_css(array(
			'template_2',
		),false,2);

		$this->add_script(array(
			'popup'
		));
		

		$this->html_pagina('checkout',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}

	function checkout_finalizar(){
		$post = $this->input->post();
		$carrinho = $this->model->buscar_carrinho();

		if(!$carrinho){
			$this->red_pagina(false,false);
		}

		if(!$post){
			$this->red_pagina(false,false);
		}

		$this->load->model('cliente/Cliente_model_cliente','model_cli');
		$cliente = $this->model_cli->buscar_conta();

		$nomes_produtos = '';
		$valorTotal = 0;
		foreach($carrinho as $car){
			$valorTotal += $car->produto_preco*$car->quantidade;

			// $produto = $this->model->db->query('SELECT nome FROM '.$this->model->prefixo_db.'institucional_produto WHERE id = "'.$car->id_produto.'"')->row();
			if($car->produto_nome) $nomes_produtos .= $car->produto_nome.', ';
		} 

		if($nomes_produtos){
			$nomes_produtos = trim($nomes_produtos);
			if(substr($nomes_produtos, -1) == ',') {
				$nomes_produtos = rtrim($nomes_produtos, ',');
			}
		} 

		$cupom = false;
		if(!empty($post['id_cupom'])){ 
			$cupom = $this->model->buscar_cupom($post['id_cupom']);
			if($cupom){
				if($cupom->desconto_tipo == 1){
					// porcentagem
					$valorTotal = $valorTotal - ($cupom->valor*$valorTotal/100);

					// $dados["discount"]['value'] = $cupom->valor;
					// $dados["discount"]['type'] = "PERCENTAGE";
				}else{
					// fixo
					$valorTotal = $valorTotal - $cupom->valor;

					// $dados["discount"]['value'] = $cupom->valor;
					// $dados["discount"]['type'] = "FIXED";
				}
			}
		}   

		if($valorTotal < 0) $valorTotal = 0;

		if($post['forma_pagamento'] == 1){
			// desconto no pix
			if(empty($post['descontoPix'])) $post['descontoPix'] = 0;
			$valorTotal = $valorTotal - ($post['descontoPix']*$valorTotal/100);
		}

		$this->load->library('Imgno_asaas', '', 'asaas');

		$pedido_id = $this->model->salvar_pedido($post['forma_pagamento'], $valorTotal);

		$post['pedido_id'] = $pedido_id;
		$post['nome'] = $nomes_produtos;

		$id_asaas = $this->asaas->gerarClientesAsaas($cliente->id); 

		if($post['forma_pagamento'] == 0 && $valorTotal == 0){
			$this->model->carrinho_remover_sessao();
			$this->red_pagina('checkout-finalizado',false);
			exit;
		}else if($post['forma_pagamento'] == 1){
			$post['customer'] = $id_asaas;
			$post['valorTotal'] = $valorTotal;

			// gerar cobrança 
			$retorno = $this->asaas->gerarCobrancaPix($post); 

			if(!empty($_GET['ajax'])){ 
				if(isset($retorno->success) && $retorno->success == 1){
					if($cupom) $this->model->cupom_utilizado($cupom->id, $cliente->id);
				}

				header('Access-Control-Allow-Origin: *');
				header('Content-Type: application/json;charset=utf-8');
				echo json_encode($retorno);

				exit;
			}

			if(isset($retorno->success) && $retorno->success == 1){
				if($cupom) $this->model->cupom_utilizado($cupom->id, $cliente->id);
            }else if(!empty($retorno->errors[0]->description)){
				$this->red_pagina('checkout',false,'#erro'.$retorno->errors[0]->description);
			}else{
				$this->red_pagina('checkout',false,'#erroFalha no pagamento, informe ao administrador do site!');
				// print_r($retorno);
			}

			exit;
		}
		else if($post['forma_pagamento'] == 2){
			$post['customer'] = $id_asaas;
			$post['valorTotal'] = $valorTotal;

			// gerar cobrança 
			$retorno = $this->asaas->gerarCobrancaBoleto($post); 

			if(!empty($_GET['ajax'])){ 
				if(isset($retorno->id)){
					if($cupom) $this->model->cupom_utilizado($cupom->id, $cliente->id);
				}

				header('Access-Control-Allow-Origin: *');
				header('Content-Type: application/json;charset=utf-8');
				echo json_encode($retorno);

				exit;
			}

			if(!empty($retorno->bankSlipUrl)){
				if($cupom) $this->model->cupom_utilizado($cupom->id, $cliente->id);
				header('Location: '.$retorno->bankSlipUrl);
            }else if(!empty($retorno->invoiceUrl)){
				if($cupom) $this->model->cupom_utilizado($cupom->id, $cliente->id);
				header('Location: '.$retorno->invoiceUrl);
            }else if(!empty($retorno->errors[0]->description)){
				$this->red_pagina('checkout',false,'#erro'.$retorno->errors[0]->description);
			}else{
				$this->red_pagina('checkout',false,'#erroFalha no pagamento, informe ao administrador do site!');
				// print_r($retorno);
			}
			exit;
		}
		else if($post['forma_pagamento'] == 3){
			if(empty($post['parcela'])) $post['parcela'] = 1;
			$post['customer'] = $id_asaas;
			$post['valorTotal'] = $valorTotal;
			// gerar cobrança 
			$retorno = $this->asaas->gerarCobrancaCartaoCredito($post);  

			if(isset($retorno->object) && $retorno->object == "payment"){
				if($cupom) $this->model->cupom_utilizado($cupom->id, $cliente->id);
				$this->model->carrinho_remover_sessao(); 
				$this->red_pagina('checkout-finalizado',false);
			}else if(!empty($retorno->errors[0]->description)){
				$this->model->carrinho_remover_sessao(); 
				$this->red_pagina('checkout',false,'#erro'.$retorno->errors[0]->description);
			}else{
				$this->red_pagina('checkout',false,'#erroFalha no pagamento, informe ao administrador do site!');
				// print_r($retorno);
			}
			exit;

		}else{
			$this->red_pagina(false,false);
			exit;
		}
		
	}
	
	function checkout_finalizar_assinatura(){
		$post = $this->input->post();
		$assinatura = $this->model->buscar_produto_assinatura();
		
		if(!$post){
			$this->red_pagina(false,false);
		}

		if(!$assinatura){
			$this->red_pagina(false,false);
		}

		$configuracao_checkout = $this->model->buscar_configuracao();

		$this->load->model('cliente/Cliente_model_cliente','model_cli');
		$cliente = $this->model_cli->buscar_conta();

		$valorTotal = $assinatura->produto_preco;
		$cupom = false;
		if(!empty($post['id_cupom'])){ 
			$cupom = $this->model->buscar_cupom($post['id_cupom']);
			if($cupom){
				if($cupom->desconto_tipo == 1){
					// porcentagem
					$valorTotal = $valorTotal - ($cupom->valor*$valorTotal/100);

					// $dados["discount"]['value'] = $cupom->valor;
					// $dados["discount"]['type'] = "PERCENTAGE";
				}else{
					// fixo
					$valorTotal = $valorTotal - $cupom->valor;

					// $dados["discount"]['value'] = $cupom->valor;
					// $dados["discount"]['type'] = "FIXED";
				}
			}
		} 
		if($valorTotal < 0) $valorTotal = 0;

		$pedido_id = $this->model->salvar_assinatura(3, $valorTotal);

		$this->load->library('Imgno_asaas', '', 'asaas');
		
		$id_asaas = $this->asaas->gerarClientesAsaas($cliente->id); 

		$post['customer'] = $id_asaas;
		$post['valorTotal'] = $valorTotal;
		$post['pedido_id'] = $pedido_id;
		$post['nome'] = $assinatura->produto_nome;
		// $post['cycle'] = $configuracao_checkout['frequencia_assina'];
		$post['cycle'] = $assinatura->frequencia_assina;

		$retorno = $this->asaas->gerarAssinaturaCartaoCredito($post);  

		if(!empty($retorno->id)){
			if($cupom) $this->model->cupom_utilizado($cupom->id, $cliente->id);

			$this->load->library('Imgno_email', '', 'enviarEmail');
			$this->enviarEmail->enviarEmailAssinaturaConfirmadaAsaas($retorno->id); 

			$this->session->unset_userdata('produto_assinatura');
			$this->red_pagina('checkout-finalizado',false);
		}else if(!empty($retorno->errors[0]->description)){
			$this->red_pagina('checkout',false,'#erro'.$retorno->errors[0]->description);
		}else{
			$this->red_pagina('checkout',false,'#erroFalha no pagamento, informe ao administrador do site!');
		}

	}

	function checkout_finalizado(){

		if(!$this->model->logado('cliente')) $this->red_pagina(false,false);
		// Config da pagina
		$this->dados = array();
		$this->dados['titulo'] = 'Compra Concluída';		

        $this->add_css(array(
			'template_1',
			'popup'
		));

		$this->add_css(array(
			'template_2',
		),false,2);

		$this->add_script(array(
			'popup'
		));

		$this->html_pagina('checkout-finalizado',array(
			'topo' => array(
				'site-topo',
				'cookie-lgpd',
				'carrinho-flutuante',
				'whatsapp-flutuante',
			),
			'rodape' => array(
				'site-rodape'
			)
        ));
	}

	function adicionar_carrinho(){
		$post = $this->input->post();

		$dados = $this->model->adicionar_carrinho($post);

		header('Content-Type: application/json;charset=utf-8');
        echo json_encode($dados);
		exit;
	}

	function remover_carrinho(){
		$post = $this->input->post();

		$dados = $this->model->remover_carrinho($post['id']);

		header('Content-Type: application/json;charset=utf-8');
        echo json_encode($dados);
		exit;
    }


	function AjaxGetCupom(){
		$post = $this->input->post();
		if(!$post) return; 

		$dados = $this->model->AjaxGetCupom($post);
		
		header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
		header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
		header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
		header("Cache-Control: post-check=0, pre-check=0", false);
		header("Pragma: no-cache");
		header("Connection: close");
		header('Content-Type: application/json;charset=utf-8');
        echo json_encode($dados);
		exit;
    }

	function getStatusCobrancaPix($id){
		$this->load->library('Imgno_asaas', '', 'asaas');

		header('Content-Type: text/event-stream');
		header('Cache-Control: no-cache');
		header('Connection: keep-alive');

		$data = $this->asaas->getStatusCobranca($id);
		if(!empty($data->status) && $data->status == "RECEIVED"){
			$this->model->carrinho_remover_sessao(); 
			// $this->asaas->atualizarStatusCobranca($id);
		} 

		$jsonData = json_encode($data);
		
		echo "data: $jsonData\n\n";
		flush();
		sleep(1);
	}

	public function webhook_asaas(){
		// Obtém os dados da requisição POST
		$payload = json_decode($this->input->raw_input_stream, true); 

		// Verifica se há dados no payload
		if (!empty($payload)) {
			$dados_prod = array();

			// Processa a notificação recebida
			// log_message('info', 'Recebi uma notificação do webhook: ' . json_encode($payload));
			if(!empty($payload['payment']['subscription'])){
				// Processar os dados da assinatura
				$this->load->library('Imgno_asaas', '', 'asaas');
				$this->asaas->atualizarStatusAssinatura($payload['payment']['subscription']); 
				
				$dados_pedido = $this->model->db->query('SELECT * FROM '.$this->model->prefixo_db.'financeiro_assinatura WHERE id_asaas_assinatura = "'.$payload['payment']['subscription'].'"')->row();
				if($dados_pedido){
					$dados_prod['id_cliente'] = $dados_pedido->id_cliente;
					$dados_prod['status_asaas'] = $dados_pedido->status_asaas;
					// $dados_prod['id_produto'] = $dados_pedido->id_produto;
					$dados_prod['id_assinatura'] = $dados_pedido->id;
					$dados_prod['valor'] = $dados_pedido->valor;
					$dados_prod['valor_total'] = $dados_pedido->valor_total;
					$dados_prod['id_asaas_cobranca'] = $payload['payment']['id'];
					$dados_prod['forma_pagamento'] = $dados_pedido->forma_pagamento;
					$dados_prod['id_asaas_assinatura'] = $dados_pedido->id_asaas_assinatura;
					$dados_prod['data_vencimento'] = $payload['payment']['dueDate'];
					$dados_prod['data_pagamento'] = $payload['payment']['clientPaymentDate'];
					$dados_prod['cadastrado'] = date("Y-m-d H:i:s");
					$dados_prod['assinatura'] = 1;
				}
			}else{
				// Processar os dados da cobrança
				$args = array(
					'_tabela' => 'financeiro_pedido',
					'dados' => array(
						'status_asaas' => $payload['payment']['status'],
						'data_pagamento' => $payload['payment']['clientPaymentDate'],
					),
					'where' => 'id_asaas_cobranca = "'.$payload['payment']['id'].'"'
				);
				$this->model->atualizar_dados_tabela($args);

				// enviar email de confirmacao
				if($payload['payment']['status'] == "RECEIVED" || $payload['payment']['status'] == "RECEIVED_IN_CASH" || $payload['payment']['status'] == "CONFIRMED"){
					$this->load->library('Imgno_email', '', 'enviarEmail');
					$this->enviarEmail->enviarEmailRCompraConfirmadaAsaas($payload['payment']['id']); 
				}

				$dados_pedido = $this->model->db->query('SELECT * FROM '.$this->model->prefixo_db.'financeiro_pedido WHERE id_asaas_cobranca = "'.$payload['payment']['id'].'"')->row();
				if($dados_pedido){
					if($dados_pedido){
						$dados_prod['forma_pagamento'] = $dados_pedido->forma_pagamento;
						$dados_prod['id_cliente'] = $dados_pedido->id_cliente;
						$dados_prod['valor'] = $dados_pedido->total;
						$dados_prod['valor_total'] = $dados_pedido->valor_total_pagar;
						$dados_prod['id_asaas_cobranca'] = $payload['payment']['id'];
						$dados_prod['status_asaas'] = $dados_pedido->status_asaas;
						$dados_prod['id_pedido'] = $dados_pedido->id;
						$dados_prod['data_vencimento'] = $payload['payment']['dueDate'];
						$dados_prod['data_pagamento'] = $payload['payment']['clientPaymentDate'];
						$dados_prod['cadastrado'] = date("Y-m-d H:i:s");
						$dados_prod['assinatura'] = 0;
					}
				}
			}
			if($payload['event'] == "PAYMENT_CREATED" && $dados_prod){
				$dados_financeiro = $this->model->db->query('SELECT * FROM '.$this->model->prefixo_db.'financeiro_lancamentos WHERE id_asaas_cobranca = "'.$payload['payment']['id'].'"')->row();

				if(!$dados_financeiro){
					// Ao criar um cobrança add em financeiro
					$args = array(
						'_tabela' => 'financeiro_lancamentos',
						'dados' => $dados_prod,
					); 
					$this->model->inserir_dados_tabela($args);
				}
			}

			// Atualizar financeiro
			$args = array(
				'_tabela' => 'financeiro_lancamentos',
				'dados' => array(
					'status_asaas' => $payload['payment']['status'],
					'data_pagamento' => $payload['payment']['clientPaymentDate'],
				),
				'where' => 'id_asaas_cobranca = "'.$payload['payment']['id'].'"'
			);
			$this->model->atualizar_dados_tabela($args);

			// Retorna uma resposta para indicar que a notificação foi recebida com sucesso
			$this->output->set_status_header(200);
			echo 'Notificação recebida com sucesso.';
		} else {
			// Se nenhum dado foi recebido, envie uma resposta de erro
			$this->output->set_status_header(400);
			echo 'Nenhum dado recebido.';
		}

        exit;   
	}

}