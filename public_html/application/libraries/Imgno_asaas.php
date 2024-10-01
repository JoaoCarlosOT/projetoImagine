<?php
	// Impede o acesso direto a este arquivo
    if ( ! defined('BASEPATH')) exit('No direct script access allowed');
    
	class Imgno_asaas extends CI_Model {

        private $ambiente_producao = false;
        public $url_sistema = null;
        public $versao_sistema = 'v3';

        public $token = '';

        private $desconto_tipo = '';
        private $desconto_valor = 0;
        private $desconto_dias = 0;

        private $juros_valor = 0;
        private $multa_valor = 0;

        public function __construct() {
            parent::__construct();  

            // Prefixo da database
		    $this->prefixo_db = $this->db->dbprefix;
            
            if($this->ambiente_producao == true){
                $this->url_sistema = 'https://api.asaas.com/';
            }else{
                $this->url_sistema = 'https://sandbox.asaas.com/api/';
            }
            
            $config = $this->db->query('SELECT assas_tokey,desconto_tipo,desconto_valor,desconto_dias,multa_valor,juros_valor FROM '.$this->prefixo_db.'financeiro_config WHERE id = 1')->row();

            $this->token = $config->assas_tokey;

            $this->juros_valor = $config->juros_valor;
            $this->multa_valor = $config->multa_valor;

            $this->desconto_tipo = $config->desconto_tipo;
            $this->desconto_valor = $config->desconto_valor;
            $this->desconto_dias = $config->desconto_dias; 
        }

        public function gerarCobranca($dados){

            if(in_array($dados["billingType"],array('BOLETO','CREDIT_CARD'))){
                $this->gerarCarneAsaas($dados);
            }else{
                $this->gerarCarne($dados);
            }

        }

        public function gerarCarne($dados){
            // validacao
            if (!(int)$dados['id_cliente']) {
                return array('Selecione o cliente');
            }

            if (!($dados['valor'] = $dados['valor'])) {
                return array('Informe o valor');
            }

            if (!($dados['nome'] = trim($dados['nome']))) {
                $erros[] = 'Informe o serviço';
            }

            if (!($dia_vencimento = (int)$dados['dia_vencimento'])) {
                return array('Informe o dia de vencimento');
            }

            if (!((int)$dados['qtd_carnes'])) {
                return array('Informe a quantidade de carnês');
            }

            if (!($frequencia = (int)$dados['frequencia'])) {
                return array('Informe a frequência');
            }

            $dados['desconto_tipo'] = $this->desconto_tipo;
            $dados['desconto_valor'] = $this->desconto_valor;
            $dados['desconto_dias'] = $this->desconto_dias;

            // Gravação dos dados
            $data_inicio = explode('-', $dados['data_inicio']);
            if ((int)$data_inicio[2] > $dados['dia_vencimento']) {
                if ((int)$data_inicio[1] == 12) {
                    $dados['data_primeiro_vencimento'] = implode('-', array((int)$data_inicio[0] + 1, sprintf('%02u', 1), sprintf('%02u', $dados['dia_vencimento'])));
                } else {
                    $dados['data_primeiro_vencimento'] = implode('-', array($data_inicio[0], sprintf('%02u', (int)$data_inicio[1] + 1), sprintf('%02u', $dados['dia_vencimento'])));
                }
            } else {
                $dados['data_primeiro_vencimento'] = implode('-', array($data_inicio[0], sprintf('%02u', (int)$data_inicio[1]), sprintf('%02u', $dados['dia_vencimento'])));
            }
            
            if ($dados['proximo_mes'] == 1) {
                $dados['data_primeiro_vencimento'] = date('Y-m-d', strtotime($dados['data_primeiro_vencimento'].' +1month'));
            }

            $data_vencimento[0] = $dados['data_primeiro_vencimento'];
            $i = $t = 0;

            for ($i = 1, $qtd = $dados['qtd_carnes']; $i < $qtd; $i++) {
                $t = strtotime("+". $frequencia ." months", strtotime($data_vencimento[$i-1]));
                $data_vencimento[$i] = date('Y-m-d', $t);
            }

            $data = $datas = $codigos = array();
            $i = 0;
            $parcelas = count($data_vencimento);

            if(!isset($dados["lancamento_automatico"])){
                $dados["lancamento_automatico"] = 0;
            }
            if(!isset($dados["proporcional"])){
                $dados["proporcional"] = 0;
            }
            if(!isset($dados["id_contrato"])){
                $dados["id_contrato"] = null;
            }
            if(!isset($dados["id_usuario"])){
                $dados["id_usuario"] = null;
            }

            foreach ($data_vencimento as $k => $item) {

                $datas[$i]['valor_pago'] = $dados['valor'];
                $datas[$i]['datahora_pagamento'] = date('Y-m-d H:i:s');

                $datas[$i]['codigo'] = $this->gerarCodigoUnico();
                $datas[$i]['id_contrato'] = (int)$dados['id_contrato'];
                $datas[$i]['id_usuario'] = (int)$dados['id_usuario'];
                $datas[$i]['id_cliente'] = (int)$dados['id_cliente'];
                $datas[$i]['lancamento_automatico'] = (int)$dados['lancamento_automatico'];
                $datas[$i]['nome'] = $dados['nome'];

                $datas[$i]['valor'] = $dados['valor'];
                $datas[$i]['desconto_valor'] = $dados['desconto_valor'];
                $datas[$i]['desconto_dias'] = $dados['desconto_dias'];
                $datas[$i]['desconto_tipo'] = $dados['desconto_tipo'];
                $datas[$i]['data_vencimento'] = $item;
                $datas[$i]['parcela'] = ($i+1) .':'. $parcelas;

                $datas[$i]['billingType'] = ($dados['billingType']?$dados['billingType']:'BOLETO');
                $codigos[$i] = $datas[$i]['codigo'];
                $i++;
            }
            
            if ($datas[0] && $dados['proporcional'] == 1) {
                $item = $data_vencimento[0];
                $dias = (strtotime($item) - strtotime($dados['data_inicio'])) / (24*60*60);
                
                if (($dias < 30) && ($dias > 0)) {
                    $v = ($dados['valor']/30) * $dias;
                    $v = number_format($v, 2, '.', '');
                    if ($v < 15) {
                        $v = 15;
                    }
                    $datas[0]['valor'] = $v;
                } elseif ($dados['proximo_mes'] == 1) {
                    $dias = (strtotime($item) - strtotime($dados['data_inicio'].' +1month')) / (24*60*60);
                    echo $dias.' - '.$item.' - '.$dados['data_inicio'];
                    $v = ($dados['valor']/30) * $dias;
                    $v += $dados['valor'];
                    $v = number_format($v, 2, '.', '');
                    $datas[0]['valor'] = $v;
                }
            }

            $linhas = $datas;

            foreach ($linhas as $linha) {

                $this->db->insert($this->prefixo_db . 'financeiro_lancamentos', $linha);
                $last_id = $this->db->insert_id();
            }

        }

        public function gerarCarneAsaas($dados){
            // validacao
            if (!(int)$dados['id_cliente']) {
                return array('Selecione o cliente');
            }

            if (!($dados['valor'] = $dados['valor'])) {
                return array('Informe o valor');
            }

            if (!($dados['nome'] = trim($dados['nome']))) {
                $erros[] = 'Informe o serviço';
            }

            if (!($dia_vencimento = (int)$dados['dia_vencimento'])) {
                return array('Informe o dia de vencimento');
            }

            if (!((int)$dados['qtd_carnes'])) {
                return array('Informe a quantidade de carnês');
            }

            if (!($frequencia = (int)$dados['frequencia'])) {
                return array('Informe a frequência');
            }

            $dados['desconto_tipo'] = $this->desconto_tipo;
            $dados['desconto_valor'] = $this->desconto_valor;
            $dados['desconto_dias'] = $this->desconto_dias;

            // Gravação dos dados
            $data_inicio = explode('-', $dados['data_inicio']);
            if ((int)$data_inicio[2] > $dados['dia_vencimento']) {
                if ((int)$data_inicio[1] == 12) {
                    $dados['data_primeiro_vencimento'] = implode('-', array((int)$data_inicio[0] + 1, sprintf('%02u', 1), sprintf('%02u', $dados['dia_vencimento'])));
                } else {
                    $dados['data_primeiro_vencimento'] = implode('-', array($data_inicio[0], sprintf('%02u', (int)$data_inicio[1] + 1), sprintf('%02u', $dados['dia_vencimento'])));
                }
            } else {
                $dados['data_primeiro_vencimento'] = implode('-', array($data_inicio[0], sprintf('%02u', (int)$data_inicio[1]), sprintf('%02u', $dados['dia_vencimento'])));
            }
            
            if ($dados['proximo_mes'] == 1) {
                $dados['data_primeiro_vencimento'] = date('Y-m-d', strtotime($dados['data_primeiro_vencimento'].' +1month'));
            }

            $data_vencimento[0] = $dados['data_primeiro_vencimento'];
            $i = $t = 0;

            for ($i = 1, $qtd = $dados['qtd_carnes']; $i < $qtd; $i++) {
                $t = strtotime("+". $frequencia ." months", strtotime($data_vencimento[$i-1]));
                $data_vencimento[$i] = date('Y-m-d', $t);
            }

            $data = $datas = $codigos = array();
            $i = 0;
            $parcelas = count($data_vencimento);

            //gerar carne
            $token = $this->token;
            $cliente = $this->db->query('SELECT * FROM '.$this->prefixo_db.'clientes WHERE id = '.(int)$dados["id_cliente"])->row();

            // if(!$cliente->id_asaas){
            //     $cliente->id_asaas = $this->gerarClientesAsaas($dados["id_cliente"]);
            // }

            if(!isset($dados["lancamento_automatico"])){
                $dados["lancamento_automatico"] = 0;
            }
            if(!isset($dados["proporcional"])){
                $dados["proporcional"] = 0;
            }
            if(!isset($dados["billingType"])){
                $dados["billingType"] = 'BOLETO';
            }
            if(!isset($dados["id_contrato"])){
                $dados["id_contrato"] = null;
            }
            if(!isset($dados["id_usuario"])){
                $dados["id_usuario"] = null;
            }
            

            $url = $this->url_sistema.$this->versao_sistema.'/payments';
            $params = array();
            $params['customer'] = $cliente->id_asaas;

            $params['billingType'] = $dados['billingType'];
            $params['installmentValue'] = ($dados['valor']);
            $params['installmentCount'] = $dados['qtd_carnes'];

            $params['dueDate'] = $dados['data_primeiro_vencimento'];

            if(!empty($dados['nome'])) $params['description'] = $dados['nome'];

            $params['externalReference'] = '';

            $params['fine']['value'] = $this->multa_valor; // percentual de multa sobre o valor da cobranca para pagamento apos o vencimento
            $params['interest']['value'] = $this->juros_valor; // percentual de juros ao mes sobre o valor da cobranca para pagamento apos o vencimento
            
            // aplicar descontos
            if($dados['desconto_valor']){
                $params['discount']['value'] = $dados['desconto_valor'];
                $params['discount']['dueDateLimitDays'] = $dados['desconto_dias'];
                $params['discount']['type'] = $dados['desconto_tipo'];
            }
            /*
            $retorno1 = json_decode($this->curl_asaas($token, $url, 'POST', $params));

            if (isset($retorno1->errors)) {
                foreach ($retorno1->errors as $k) {
                    $erros[] = $k->description;
                }
                if ($erros && $dados['valor'] != 1) {
                    return $erros;
                }
            }

            $url = $this->url_sistema.$this->versao_sistema.'/payments';

            $params = array();
            $params['installment'] = $retorno1->installment;
            // $params['installment'] = "ins_000001655665";
            $params['limit'] = 50;
            $params['offset'] = 0;

            $asaas_parcelas = json_decode($this->curl_asaas(null, $url, 'GET', $params));
            $asaas_parcelas = array_reverse($asaas_parcelas->data);

            if ($asaas_parcelas[0] && $dados['proporcional'] == 1) {
                    $item = $asaas_parcelas[0];
                    $dias = (strtotime($item->dueDate) - strtotime($dados['data_inicio'])) / (24*60*60);
                    if (($dias < 30) && ($dias > 0)) {
                        $v = ($dados['valor']/30) * $dias;
                        $v = number_format($v, 2, '.', '');
                        if ($v < 15) {
                            $v = 15;
                        }
                        $url = $this->url_sistema.$this->versao_sistema.'/payments/'.$item->id;

                        $params = array();
                        $params['value'] = $v;
                        $valor_primeira = $v;
                        $retorno = $this->curl_asaas($token, $url, 'POST', $params);
                        $retorno = json_decode($retorno);
                    } elseif ($dados['proximo_mes'] == 1) {
                        $dias = (strtotime($item->dueDate) - strtotime($dados['data_inicio'].' +1month')) / (24*60*60);
                        $v = ($dados['valor']/30) * $dias;
                        $v = number_format($v, 2, '.', '');
                        $v += $item->value;
                        
                        $url = $this->url_sistema.$this->versao_sistema.'/payments/'.$item->id;

                        $params = array();
                        $params['value'] = $v;
                        $valor_primeira = $v;
                        $retorno = $this->curl_asaas($token, $url, 'POST', $params);
                        $retorno = json_decode($retorno);
                    }
            }
            */
            $asaas_parcelas2 = array();
            foreach ($data_vencimento as $k => $item) {

                $datas[$i]['codigo'] = $this->gerarCodigoUnico();
                $datas[$i]['id_contrato'] = (int)$dados['id_contrato'];
                $datas[$i]['id_usuario'] = (int)$dados['id_usuario'];
                $datas[$i]['id_cliente'] = (int)$dados['id_cliente'];
                $datas[$i]['lancamento_automatico'] = (int)$dados['lancamento_automatico'];
                $datas[$i]['nome'] = $dados['nome'];

                $datas[$i]['valor'] = $dados['valor'];
                $datas[$i]['desconto_valor'] = $dados['desconto_valor'];
                $datas[$i]['desconto_dias'] = $dados['desconto_dias'];
                $datas[$i]['desconto_tipo'] = $dados['desconto_tipo'];
                $datas[$i]['data_vencimento'] = $item;
                $datas[$i]['parcela'] = ($i+1) .':'. $parcelas;

                // $asaas_parcelas2[$asaas_parcelas[$k]->id] = $asaas_parcelas[$k];
                // $datas[$i]['id_asaas'] = $asaas_parcelas[$k]->id;
                // $datas[$i]['installment_asaas'] = $asaas_parcelas[$k]->installment;

                $datas[$i]['billingType'] = ($dados['billingType']?$dados['billingType']:'BOLETO');
                $codigos[$i] = $datas[$i]['codigo'];
                $i++;
            }
            
            if ($datas[0] && $dados['proporcional'] == 1) {
                $item = $data_vencimento[0];
                $dias = (strtotime($item) - strtotime($dados['data_inicio'])) / (24*60*60);
                
                if (($dias < 30) && ($dias > 0)) {
                    $v = ($dados['valor']/30) * $dias;
                    $v = number_format($v, 2, '.', '');
                    if ($v < 15) {
                        $v = 15;
                    }
                    $datas[0]['valor'] = $v;
                } elseif ($dados['proximo_mes'] == 1) {
                    $dias = (strtotime($item) - strtotime($dados['data_inicio'].' +1month')) / (24*60*60);
                    echo $dias.' - '.$item.' - '.$dados['data_inicio'];
                    $v = ($dados['valor']/30) * $dias;
                    $v += $dados['valor'];
                    $v = number_format($v, 2, '.', '');
                    $datas[0]['valor'] = $v;
                }
            }

            $linhas = $datas;
            $asaas_parcelas = $asaas_parcelas2;

            // $id_asaas_array = array();
            // foreach ($linhas as $linha) {
            //     $id_asaas_array[] = $linha['id_asaas'];
            // }

            // $id_asaas_array = array_filter($id_asaas_array);
            // if ($id_asaas_array) {
            //     $retorno = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_lancamentos WHERE id_asaas IN("'.implode('","', $id_asaas_array).'") ')->row();
            //     if ($retorno) {
            //         return false;
            //     }
            // }

            foreach ($linhas as $linha) {

                $this->db->insert($this->prefixo_db . 'financeiro_lancamentos', $linha);
                $last_id = $this->db->insert_id();

                // if ($asaas_parcelas) {
                //     $asaas = $asaas_parcelas[$linha['id_asaas']];

                //     $args_a = array(
                //         'id_asaas' => $asaas->id,
                //         'installment_asaas' => $asaas->installment,
                //         'link' => $asaas->bankSlipUrl?$asaas->bankSlipUrl:$asaas->invoiceUrl,
                //         'id_lancamento' => $last_id,
                //         'id_cliente' => $linha['id_cliente'],
                //         'valor' => $asaas->value,
                //         'valor_liquido' => $asaas->netValue,
                //         'data_vencimento' => $asaas->dueDate,
                //     );

                //     $this->db->insert($this->prefixo_db . 'financeiro_lancamentos_asaas', $args_a);
                // }
            }

        } 

        public function getCicloAssinatura($ciclo = null){
            // Mapeamento
            $cycleMapping = array( 
                'WEEKLY' => 'Semanal',
                'BIWEEKLY' => 'Quinzenal',
                'MONTHLY' => 'Mensal',
                'BIMONTHLY' => 'Bimestral',
                'QUARTERLY' => 'Trimestral',
                'SEMIANNUALLY' => 'Semestral',
                'YEARLY' => 'Anual'
            );

            if($ciclo){
                if (array_key_exists($ciclo, $cycleMapping)) {
                    return $cycleMapping[$ciclo];
                } else {
                    return '';
                }
            }else if($ciclo === null){
                return $cycleMapping;
            }

        }

        public function getStatusMapping($statusDaAPI = null){
            // Mapeamento dos status
            $statusMapping = array(
                'PENDING' => 'Aguardando',
                'RECEIVED' => 'Recebido',
                'CONFIRMED' => 'Confirmado',
                'OVERDUE' => 'Em atraso',
                'REFUNDED' => 'Reembolsado',
                'RECEIVED_IN_CASH' => 'Recebido em dinheiro',
                'REFUND_REQUESTED' => 'Solicitação de reembolso',
                'REFUND_IN_PROGRESS' => 'Reembolso em andamento',
                'CHARGEBACK_REQUESTED' => 'Solicitação de chargeback',
                'CHARGEBACK_DISPUTE' => 'Disputa de chargeback',
                'AWAITING_CHARGEBACK_REVERSAL' => 'Aguardando reversão de chargeback',
                'DUNNING_REQUESTED' => 'Solicitação de dunning',
                'DUNNING_RECEIVED' => 'Cobrança recebido',
                'AWAITING_RISK_ANALYSIS' => 'Aguardando análise de risco',
            );

            if($statusDaAPI){
                if (array_key_exists($statusDaAPI, $statusMapping)) {
                    return $statusMapping[$statusDaAPI];
                } else {
                    return '';
                }
            }else if($statusDaAPI === null){
                return $statusMapping;
            }

        }

        public function getStatusCobranca($id){
            $token = $this->token;

            $url = $this->url_sistema.$this->versao_sistema.'/payments/'.$id.'/status';
            $retorno = json_decode($this->curl_asaas($token, $url, 'GET'));
             
            return $retorno;
        }

        public function atualizarStatusCobranca($id_cobranca){
            
            $status = '';
            $api_status = $this->getStatusCobranca($id_cobranca);
            if(!empty($api_status->status)){
                // $status = $this->getStatusMapping($api_status->status);
                $status = $api_status->status;
            } 
            
            if($status){
                $args = array(
                    '_tabela' => 'financeiro_pedido',
                    'dados' => array(
                        'status_asaas'=> $status,
                    ),
                    'where' => 'id_asaas_cobranca = "'. $id_cobranca .'" '
                );
    
                $this->db->where($args['where']);
                $this->db->update($this->prefixo_db . $args['_tabela'], $args['dados']);
            }
        }


        public function gerarCobrancaPix($dados){

            $params["customer"] = $dados['customer'];
			$params["billingType"] = 'PIX';
			$params["value"] = $dados['valorTotal'];
			$params["dueDate"] = date("Y-m-d"); 
            $params['externalReference'] = $dados['pedido_id'];
            if(!empty($dados['nome'])) $params['description'] = $dados['nome'];
            // nessesario cadastrar site em minha conta -> informacoes
            // $params['callback']['successUrl'] = 'https://www.sitecd.imcdigital.com.br/site/Site_controller_paginas/emailTeste';
            // $params['callback']['autoRedirect'] = false;

            $token = $this->token;
            $url = $this->url_sistema.$this->versao_sistema.'/payments';
            $retorno = json_decode($this->curl_asaas($token, $url, 'POST', $params));

            if(empty($retorno->id)) return $retorno;

            $url_2 = $this->url_sistema.$this->versao_sistema.'/payments/'.$retorno->id.'/pixQrCode';
            $retorno_2 = json_decode($this->curl_asaas($token, $url_2, 'POST', $params));
            
            $retorno_2->id = $retorno->id;

            // atualizar id assas combrança e status
            $status = '';
            $api_status = $this->asaas->getStatusCobranca($retorno->id);
            if(!empty($api_status->status)){
                // $status = $this->asaas->getStatusMapping($api_status->status);
                $status = $api_status->status;
            }

            $args = array(
                '_tabela' => 'financeiro_pedido',
                'dados' => array(
                    'id_asaas_cobranca'=> $retorno->id,
                    'status_asaas'=> $status,
                ),
                'where' => 'id = '. $dados['pedido_id']
            );

            $this->db->where($args['where']);
            $this->db->update($this->prefixo_db . $args['_tabela'], $args['dados']);

            return $retorno_2;

        }

        public function gerarCobrancaBoleto($dados){

            $params["customer"] = $dados['customer'];
			$params["billingType"] = 'BOLETO';
			$params["value"] = $dados['valorTotal'];
			$params["dueDate"] = date("Y-m-d");
            $params['externalReference'] = $dados['pedido_id'];
            if(!empty($dados['nome'])) $params['description'] = $dados['nome'];

            $token = $this->token;
            $url = $this->url_sistema.$this->versao_sistema.'/payments';

            $retorno = json_decode($this->curl_asaas($token, $url, 'POST', $params));

            if(empty($retorno->id)) return $retorno;

            $url_2 = $this->url_sistema.$this->versao_sistema.'/payments/'.$retorno->id.'/identificationField';
            $retorno_2 = json_decode($this->curl_asaas($token, $url_2, 'GET'));
            
            $retorno_2->id = $retorno->id;
            $retorno_2->bankSlipUrl = $retorno->bankSlipUrl; 

            // atualizar id assas combrança
            $status = '';
            $api_status = $this->asaas->getStatusCobranca($retorno->id);
            if(!empty($api_status->status)){
                // $status = $this->asaas->getStatusMapping($api_status->status);
                $status = $api_status->status;
            }
            $args = array(
                '_tabela' => 'financeiro_pedido',
                'dados' => array(
                    'id_asaas_cobranca'=> $retorno->id,
                    'status_asaas'=> $status,
                ),
                'where' => 'id = '. $dados['pedido_id']
            );

            $this->db->where($args['where']);
            $this->db->update($this->prefixo_db . $args['_tabela'], $args['dados']);

            return $retorno_2;

        }

        public function gerarAssinaturaCartaoCredito($dados){

            $params["customer"] = $dados['customer'];
			$params["billingType"] = 'CREDIT_CARD';
			$params["value"] = $dados['valorTotal'];
			$params["nextDueDate"] = date("Y-m-d");
            $params['externalReference'] = $dados['pedido_id'];
			$params["cycle"] = (!empty($dados['cycle'])?$dados['cycle']:'MONTHLY');
			$params["remoteIp"] = (!empty($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'');
            if(!empty($dados['nome'])) $params['description'] = $dados['nome'];  

            $params['creditCard']['holderName'] = $dados['holderName'];
            $params['creditCard']['number'] = $this->OnlyInt($dados['number']);
            $params['creditCard']['expiryMonth'] = $dados['expiryMonth'];
            $params['creditCard']['expiryYear'] = $dados['expiryYear'];
            $params['creditCard']['ccv'] = $dados['ccv'];

            $params['creditCardHolderInfo']['name'] = $dados['name'];
            $params['creditCardHolderInfo']['email'] = $dados['email'];
            $params['creditCardHolderInfo']['cpfCnpj'] = $this->OnlyInt($dados['cpfCnpj']);
            $params['creditCardHolderInfo']['postalCode'] = $this->OnlyInt($dados['postalCode']);
            $params['creditCardHolderInfo']['addressNumber'] = $dados['addressNumber'];
            if(!empty($dados['addressComplement'])) $params['creditCardHolderInfo']['addressComplement'] = $dados['addressComplement'];
            $params['creditCardHolderInfo']['phone'] = $this->OnlyInt($dados['phone']);
            if(!empty($dados['mobilePhone'])) $params['creditCardHolderInfo']['mobilePhone'] = $this->OnlyInt($dados['mobilePhone']);

            $token = $this->token;
            $url = $this->url_sistema.$this->versao_sistema.'/subscriptions';

            $retorno = json_decode($this->curl_asaas($token, $url, 'POST', $params));

            if(empty($retorno->id)) return $retorno; 

            // atualizar id assas
            $args = array(
                '_tabela' => 'financeiro_assinatura',
                'dados' => array(
                    'id_asaas_assinatura'=> $retorno->id,
                    'status_asaas'=> $retorno->status,
                ),
                'where' => 'id = '. $dados['pedido_id']
            );

            $this->db->where($args['where']);
            $this->db->update($this->prefixo_db . $args['_tabela'], $args['dados']);

            return $retorno;
        }

        public function atualizarStatusAssinatura($id_assinatura){
            $token = $this->token;
            $url = $this->url_sistema.$this->versao_sistema.'/subscriptions/'.$id_assinatura;
            $retorno = json_decode($this->curl_asaas($token, $url, 'GET'));
            
            if(!empty($retorno->id)){
                $args = array(
                    '_tabela' => 'financeiro_assinatura',
                    'dados' => array(
                        'status_asaas' => $retorno->status,
                    ),
                    'where' => 'id_asaas_assinatura = "'.$retorno->id.'"'
                );
    
                $this->db->where($args['where']);
                $this->db->update($this->prefixo_db . $args['_tabela'], $args['dados']);
            }
        }

        public function getStatusMappingAssinatura($statusDaAPI = null){
            // Mapeamento dos status
            $statusMapping = array(
                'ACTIVE' => 'Ativo',
                'INACTIVE' => 'Inativo',
                'EXPIRED' => 'Expirado',
            );

            if($statusDaAPI){
                if (array_key_exists($statusDaAPI, $statusMapping)) {
                    return $statusMapping[$statusDaAPI];
                } else {
                    return '';
                }
            }else if($statusDaAPI === null){
                return $statusMapping;
            }

        }


        public function assinatura_cancelar($id_cliente = 0, $log_texto = null) {
            if(!$id_cliente) return false;
    
            $assinatura = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_assinatura WHERE id = '.$id_cliente)->row();
    
            if($assinatura->id_asaas_assinatura){
                
                $token = $this->token;
                $url = $this->url_sistema.$this->versao_sistema.'/subscriptions/'.$assinatura->id_asaas_assinatura;
    
                $retorno = json_decode($this->curl_asaas($token, $url, 'DELETE'));

                if(!empty($retorno->deleted)){
                    $args = array(
                        '_tabela' => 'financeiro_assinatura',
                        'dados' => array(
                            'cancelado' => 1,
                            'log_texto' => $log_texto,
                        ),
                        'where' => 'id = '. $id_cliente
                    );
        
                    $this->db->where($args['where']);
                    $this->db->update($this->prefixo_db . $args['_tabela'], $args['dados']);

                    return $retorno;
                }else{
                    return false;
                }
            }
    
            return $assinatura;
        }

        public function gerarCobrancaCartaoCredito($dados){

            $params["customer"] = $dados['customer'];
			$params["billingType"] = 'CREDIT_CARD';
			$params["value"] = $dados['valorTotal'];
			$params["dueDate"] = date("Y-m-d");
            $params['externalReference'] = $dados['pedido_id'];
			$params["installmentCount"] = $dados['parcela'];
			$params["installmentValue"] = $dados['valorTotal']/$dados['parcela'];
            if(!empty($dados['nome'])) $params['description'] = $dados['nome'];

            $token = $this->token;
            $url = $this->url_sistema.$this->versao_sistema.'/payments';

            $retorno = json_decode($this->curl_asaas($token, $url, 'POST', $params));
            
            if(empty($retorno->id)) return $retorno;

            $params_2['creditCard']['holderName'] = $dados['holderName'];
            $params_2['creditCard']['number'] = $this->OnlyInt($dados['number']);
            $params_2['creditCard']['expiryMonth'] = $dados['expiryMonth'];
            $params_2['creditCard']['expiryYear'] = $dados['expiryYear'];
            $params_2['creditCard']['ccv'] = $dados['ccv'];

            $params_2['creditCardHolderInfo']['name'] = $dados['name'];
            $params_2['creditCardHolderInfo']['email'] = $dados['email'];
            $params_2['creditCardHolderInfo']['cpfCnpj'] = $this->OnlyInt($dados['cpfCnpj']);
            $params_2['creditCardHolderInfo']['postalCode'] = $this->OnlyInt($dados['postalCode']);
            $params_2['creditCardHolderInfo']['addressNumber'] = $dados['addressNumber'];
            if(!empty($dados['addressComplement'])) $params_2['creditCardHolderInfo']['addressComplement'] = $dados['addressComplement'];
            $params_2['creditCardHolderInfo']['phone'] = $this->OnlyInt($dados['phone']);
            if(!empty($dados['mobilePhone'])) $params_2['creditCardHolderInfo']['mobilePhone'] = $this->OnlyInt($dados['mobilePhone']);

            $url_2 = $this->url_sistema.$this->versao_sistema.'/payments/'.$retorno->id.'/payWithCreditCard';
            $retorno_2 = json_decode($this->curl_asaas($token, $url_2, 'POST', $params_2));

            $retorno_2->id = $retorno->id;

            // atualizar id assas combrança
            $status = '';
            $api_status = $this->asaas->getStatusCobranca($retorno->id);
            if(!empty($api_status->status)){
                // $status = $this->asaas->getStatusMapping($api_status->status);
                $status = $api_status->status;
            }
            $args = array(
                '_tabela' => 'financeiro_pedido',
                'dados' => array(
                    'id_asaas_cobranca'=> $retorno->id,
                    'status_asaas'=> $status,
                ),
                'where' => 'id = '. $dados['pedido_id']
            );

            $this->db->where($args['where']);
            $this->db->update($this->prefixo_db . $args['_tabela'], $args['dados']);

            return $retorno_2;
        }

        public function validarIdClienteAssas($id_asaas){
            $cli_id = "";
            
            if(!$id_asaas) return $cli_id;

            $url = $this->url_sistema.$this->versao_sistema.'/customers?id='.$id_asaas;
            $cli = $this->curl_asaas($this->token, $url, 'GET');
             
            $cli_r = json_decode($cli);

            if (isset($cli_r->id) && $cli_r->id) {
                $cli_id = $cli_r->id;
            }

            return $cli_id;
        } 
        
        public function gerarClientesAsaas($id_cliente)
        {

            $token = $this->token;
            $cliente = $this->db->query('SELECT * FROM '.$this->prefixo_db.'clientes WHERE id = '.(int)$id_cliente)->row();

            $cli_id = $this->validarIdClienteAssas($cliente->id_asaas);

            if($cli_id) return $cli_id; 

            $cpf_cnpj = $this->OnlyInt($cliente->cpf_cnpj);

            $url = $this->url_sistema.$this->versao_sistema.'/customers?cpfCnpj='.$cpf_cnpj;
            $cli = $this->curl_asaas($token, $url, 'GET');
            $cli_r = json_decode($cli); 

            if (!empty($cli_r->data) && $cli_d = $cli_r->data[0]) {
                $cli_id = $cli_d->id; 
            } else {
                // $dados_endereco = $this->db->query('SELECT * FROM '.$this->prefixo_db.'clientes_dados_endereco WHERE id_cliente = '.(int)$id_cliente)->row();
                // $dados_cobranca = $this->db->query('SELECT * FROM '.$this->prefixo_db.'clientes_dados_cobranca WHERE id_cliente = '.(int)$id_cliente)->row();

                $url = $this->url_sistema.$this->versao_sistema.'/customers';
                $parametros = array();
                
                $parametros['externalReference'] = $cliente->id;
                $parametros['name'] = $cliente->nome;
                $parametros['cpfCnpj'] = $this->OnlyInt($cpf_cnpj);

                // $parametros['stateInscription'] = $cliente->inscricao_estadual;

                if(!empty($cliente->email)){
                    $parametros['email'] = $cliente->email;
                } 

                if(!empty($cliente->telefone)){
                    $parametros['phone'] = $this->OnlyInt($cliente->telefone);
                } 

                if(!empty($cliente->celular)){
                    $parametros['mobilePhone'] = $this->OnlyInt($cliente->celular);
                } 

                if(!empty($cliente->logradouro)) $parametros['address'] = $cliente->logradouro;
                if(!empty($cliente->numero)) $parametros['addressNumber'] = $cliente->numero;
                if(!empty($cliente->complemento)) $parametros['complement'] = $cliente->complemento;
                if(!empty($cliente->bairro)) $parametros['province'] = $cliente->bairro;
                if(!empty($cliente->cep)) $parametros['postalCode'] = $this->OnlyInt($cliente->cep);

                //email extra
                // $parametros['additionalEmails'] = implode(',', json_decode($cliente->email_extra));

                $parametros['notificationDisabled'] = false; 

                $cli = $this->curl_asaas($token, $url, 'POST', $parametros);
                $cli_r = json_decode($cli);
                $cli_id = $cli_r->id;
            } 

            $args = array(
                '_tabela' => 'clientes',
                'dados' => array('id_asaas'=>$cli_id),
                'where' => 'id = '. $id_cliente
            );

            $this->db->where($args['where']);
            $this->db->update($this->prefixo_db . $args['_tabela'], $args['dados']);
            
            return $cli_id;
        }

        public function curl_asaas($token, $url, $request = 'POST', $postData = array())
        {
            if (!$token) {
                $token = $this->token;
            }

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $request);

            if ($postData) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
            }

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'access_token:'.$token));
            // $httpcode = curl_getinfo($ch);
            $output = curl_exec($ch);
            curl_close($ch);
            
            return $output;
        }

        public function OnlyInt($n)
        {
            return preg_replace('/\D/', '', $n);
        }

        public function dataPrimeiroVencimento($data_inicio,$dia_vencimento,$proximo_mes = 1){

            $dados['data_inicio'] = $data_inicio;
            $dados['dia_vencimento'] = $dia_vencimento;

            $data_inicio = explode('-', $dados['data_inicio']);
            if ((int)$data_inicio[2] > $dados['dia_vencimento']) {
                if ((int)$data_inicio[1] == 12) {
                    $dados['data_primeiro_vencimento'] = implode('-', array((int)$data_inicio[0] + 1, sprintf('%02u', 1), sprintf('%02u', $dados['dia_vencimento'])));
                } else {
                    $dados['data_primeiro_vencimento'] = implode('-', array($data_inicio[0], sprintf('%02u', (int)$data_inicio[1] + 1), sprintf('%02u', $dados['dia_vencimento'])));
                }
            } else {
                $dados['data_primeiro_vencimento'] = implode('-', array($data_inicio[0], sprintf('%02u', (int)$data_inicio[1]), sprintf('%02u', $dados['dia_vencimento'])));
            }
            
            if ($proximo_mes == 1) {
                $dados['data_primeiro_vencimento'] = date('Y-m-d', strtotime($dados['data_primeiro_vencimento'].' +1month'));
            }

            return $dados['data_primeiro_vencimento'];
        }

        // Gera um código numérico único de 8 dígitos
        public function gerarCodigoUnico()
        {
            $unico = false;
            while (!$unico) {
                $codigo = (int)(mt_rand(10, 99) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9) . mt_rand(0, 9));
                $rs = $this->db->query('SELECT id FROM '.$this->prefixo_db.'financeiro_lancamentos WHERE codigo = '. (int)$codigo)->row_array();
                if (!$rs) {
                    $unico = true;
                }
            }
            return $codigo;
        }
    }
?>