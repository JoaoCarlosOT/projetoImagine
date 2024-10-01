<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Site_model_checkout extends Base_model {

    

    function buscar_configuracao(){
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_config WHERE id = 1')->row_array();

        if(isset($dados['formas_pagamento'])) $dados['formas_pagamento'] = json_decode($dados['formas_pagamento'],true);

		return $dados;
    }

    function buscar_carrinho(){
        $id_sessao = $this->carrinho_sessao();

        $dados = $this->db->query('SELECT ca.*, pr.descricao as produto_descricao, pr.nome as produto_nome,  pr.preco as produto_preco, pr.preco_de as produto_preco_de, pri.imagem as imagem FROM '.$this->prefixo_db.'financeiro_carrinho ca
            LEFT JOIN '.$this->prefixo_db.'institucional_produto pr ON (pr.id = ca.id_produto)
            LEFT JOIN '.$this->prefixo_db.'institucional_produto_imagem pri ON pri.id_produto = pr.id 
            WHERE ca.id_sessao = "'.$id_sessao.'" AND pr.ativarVenda = 1 AND pr.assinatura = 0 AND pr.situacao = 1 GROUP BY ca.id ORDER BY ca.id ASC')->result(); 

        return $dados;
    }
    
    function buscar_produto_assinatura(){
        $produto_assinatura = $this->session->userdata('produto_assinatura');

        if($produto_assinatura){
            $dados = $this->db->query('SELECT pr.id, pr.frequencia_assina, pr.descricao as produto_descricao, pr.nome as produto_nome, pr.preco as produto_preco, pr.preco_de as produto_preco_de, pri.imagem as imagem FROM '.$this->prefixo_db.'institucional_produto pr 
            LEFT JOIN '.$this->prefixo_db.'institucional_produto_imagem pri ON pri.id_produto = pr.id 
            WHERE pr.id = "'.$produto_assinatura['id_produto'].'" AND pr.ativarVenda = 1 AND pr.assinatura = 1 AND pr.situacao = 1')->row(); 

            if($dados){
                $dados->quantidade = $produto_assinatura['quantidade'];
                
                return $dados;
            }
        }

        return false;
    }

    function atualizar_dados_carrinho($dados = array()){

        if($dados){

            $args = array(
                '_tabela' => 'financeiro_carrinho',
                'dados' => $dados,
                'where' => 'id_sessao = "'.$this->carrinho_sessao().'"'
            );
            $this->atualizar_dados_tabela($args);
        }
    }

    function adicionar_carrinho($dados){
        $resultado = array();

        $opcao = null;

        if(isset($dados['opcao']) && $dados['opcao']){
            $opcao = json_encode($dados['opcao']);
        }

        $dados_enviar['id_cliente'] = (isset($this->cliente) && $this->cliente?$this->cliente:0);
        $dados_enviar['cpf_cnpj'] = '';
        $dados_enviar['email'] = '';
        $dados_enviar['id_produto'] = $dados['id_produto'];
        $dados_enviar['opcao'] = $opcao;
        $dados_enviar['quantidade'] = $dados['quantidade'];
        $dados_enviar['id_sessao'] = $this->carrinho_sessao();

        $args = array(
            '_tabela' => 'financeiro_carrinho',
            'dados' => $dados_enviar
        );

        $args['retornar_id'] = true;
        $args["dados"]["cadastrado"] = date('Y-m-d H:i:s');
        $resultado["id"] = $this->inserir_dados_tabela($args);

		// $this->session->unset_userdata('produto_assinatura');

        return $resultado;
    }

    function remover_carrinho($id_carrinho){

        $resultado = array();
        $this->remover_dados_tabela(array(
                                        '_tabela' => 'financeiro_carrinho',
                                        'where' => 'id_sessao = "'.$this->carrinho_sessao().'" AND id = '.$id_carrinho
                                    ));

        $resultado['id'] = $id_carrinho;
        return $resultado;
    }

    function salvar_assinatura($forma_pagamento, $valorTotalPagar = 0){
        $assinatura = $this->model->buscar_produto_assinatura();

        $dados_enviar['id_cliente'] = (isset($this->cliente) && $this->cliente?$this->cliente:0);
        $dados_enviar['id_produto'] = $assinatura->id;
        $dados_enviar['nome'] = $assinatura->produto_nome;
        $dados_enviar['valor'] = $assinatura->produto_preco;
        $dados_enviar['quantidade'] = $assinatura->quantidade;
        $dados_enviar['frequencia_assina'] = $assinatura->frequencia_assina;
        $dados_enviar['imagem'] = $assinatura->imagem;
        $dados_enviar['valor_total'] = $valorTotalPagar;
        $dados_enviar['forma_pagamento'] = $forma_pagamento;

        $args = array(
            '_tabela' => 'financeiro_assinatura',
            'dados' => $dados_enviar
        );
        $args['retornar_id'] = true;
        $args["dados"]["cadastro"] = date('Y-m-d H:i:s');

        $id_pedido = $this->inserir_dados_tabela($args);

        return $id_pedido;
    }

    function salvar_pedido($forma_pagamento, $valorTotalPagar = 0){
        $resultado = array();

        $opcao = null;

        if(isset($dados['opcao']) && $dados['opcao']){
            $opcao = json_encode($dados['opcao']);
        }


        $carrinho = $this->model->buscar_carrinho();

        // echo '<pre>';
        // var_dump($carrinho);
        // exit;

        $valor_total = 0;
        foreach($carrinho as $pedido){
            $valor_total +=  $pedido->produto_preco*$pedido->quantidade;
        }   

        $dados_enviar['situacao'] = 1;
        $dados_enviar['forma_pagamento'] = $forma_pagamento;
        $dados_enviar['id_cliente'] = $this->cliente;
        $dados_enviar['total'] =$valor_total;
        $dados_enviar['valor_total_pagar'] =$valorTotalPagar;

        $args = array(
            '_tabela' => 'financeiro_pedido',
            'dados' => $dados_enviar
        );

        $args['retornar_id'] = true;
        $args["dados"]["cadastrado"] = $args["dados"]["atualizado"] = date('Y-m-d H:i:s');

        $id_pedido = $this->inserir_dados_tabela($args);


        foreach($carrinho as $pedido){
            $dados_enviar_p['id_pedido'] = $id_pedido;
            $dados_enviar_p['id_produto'] =  $pedido->id_produto;
            $dados_enviar_p['nome'] = $pedido->produto_nome;
            $dados_enviar_p['valor'] = $pedido->produto_preco;
            $dados_enviar_p['quantidade'] = $pedido->quantidade;
            $dados_enviar_p['imagem'] = $pedido->imagem;
            $dados_enviar_p['opcao'] = $pedido->opcao;

            $args = array(
                '_tabela' => 'financeiro_pedido_produto',
                'dados' => $dados_enviar_p
            );
            $this->inserir_dados_tabela($args);
        }  


        return $id_pedido;
    }

    function buscar_assinaturas(){
        if(!$this->cliente) return;
        $this->load->library('Imgno_asaas', '', 'asaas');

        $dados = $this->db->query('SELECT a.*, p.alias FROM '.$this->prefixo_db.'financeiro_assinatura a
        LEFT JOIN '.$this->prefixo_db.'institucional_produto p ON p.id = a.id_produto
        WHERE id_cliente = '.(int)$this->cliente.' ORDER BY cadastro DESC')->result();

        return $dados;
    }

    function buscar_pedidos(){
        if(!$this->cliente) return;
        $this->load->library('Imgno_asaas', '', 'asaas');

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_pedido WHERE id_cliente = ~~'.(int)$this->cliente.' ORDER BY cadastrado DESC')->result();
        
        if($dados){
            foreach($dados as $key => $dado){
                /*if($dado->id_asaas_cobranca){
                    $api_status = $this->asaas->getStatusCobranca($dado->id_asaas_cobranca);
                    if(!empty($api_status->status)){
                        $mapeado = $this->asaas->getStatusMapping($api_status->status);
                        if($mapeado != $dado->status_asaas){
                            $args = array(
                                '_tabela' => 'financeiro_pedido',
                                'dados' => array(
                                    'status_asaas' => $mapeado,
                                ),
                                'where' => 'id = "'.$dado->id.'"'
                            );
                            $this->atualizar_dados_tabela($args);
                            $dado->status_asaas = $mapeado;
                        }
                    }
                }else if($dado->forma_pagamento == 0){
                    $mapeado = $this->asaas->getStatusMapping('RECEIVED');
                    if($mapeado != $dado->status_asaas){
                        $args = array(
                            '_tabela' => 'financeiro_pedido',
                            'dados' => array(
                                'status_asaas' => $mapeado,
                            ),
                            'where' => 'id = "'.$dado->id.'"'
                        );
                        $this->atualizar_dados_tabela($args);
                        $dado->status_asaas = $mapeado;
                    }
                }*/

                $dados[$key]->produtos = $this->db->query('SELECT pp.*, p.alias FROM '.$this->prefixo_db.'financeiro_pedido_produto pp
                LEFT JOIN '.$this->prefixo_db.'institucional_produto p ON p.id = pp.id_produto
                WHERE pp.id_pedido = '.(int)$dado->id)->result();
            }
        } 

        return $dados;
    }

    function buscar_cupom($id){
        if(!$id) return null;

        $dataAtual = date('Y-m-d');

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_cupom WHERE situacao = 1 AND (data_validade = "0000-00-00" OR "'.$dataAtual.'" <= data_validade) AND id = '.$id)->row();

		return $dados;
    }

    function cupom_utilizado($id_cupom, $id_cliente){
        $args = array(
            '_tabela' => 'financeiro_cupom_utilizado',
            'dados' => array(
                'id_cupom' => $id_cupom,
                'id_cliente' => $id_cliente,
                'cadastro' => date('Y-m-d H:i:s'),
            )
        );
        $this->inserir_dados_tabela($args); 
    }

    function AjaxGetCupom($cupom){
        if(!isset($cupom['cupom'])) return;

        $dataAtual = date('Y-m-d');

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_cupom WHERE situacao = 1 AND codigo_cupom = "'.$cupom['cupom'].'" AND (data_validade = "0000-00-00" OR "'.$dataAtual.'" <= data_validade)')->row_array();

        return $dados;
    }
}