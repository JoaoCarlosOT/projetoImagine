<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_financeiro extends Base_model {

    function buscar_lancamentos($args){
        $where = '';

        if(isset($args['tipo_servico']) && $args['tipo_servico']){

        }

        if(isset($args['tipo_data']) && $args['tipo_data'] == 2){
            $tipo_data = 'l.data_pagamento'; 
        }else if(isset($args['tipo_data']) && $args['tipo_data'] == 3){
            $tipo_data = 'l.cadastrado'; 
        }else{
            $tipo_data = 'l.data_vencimento'; 
        } 

        if(isset($args['data_ini']) && isset($args['data_ate']) && $args['data_ini'] && $args['data_ate']){
            // $data_atual_ini = $args['ano'].'-'.sprintf('%02u',$args['mes'])."-01";
            // $data_atual_fim = date("Y-m-t", strtotime($data_atual_ini));
            $data_atual_ini = $args['data_ini'];
            $data_atual_fim = $args['data_ate'];

            $where .= ' AND '.$tipo_data.' >= "'.$data_atual_ini.'" AND '.$tipo_data.' <= "'.$data_atual_fim.'"';
        }

        if(isset($args['assinatura']) && $args['assinatura'] != ""){
            $where .= ' AND l.assinatura = '.$args['assinatura'].'';
        }

        if(isset($args['id_cliente']) && $args['id_cliente']){
            $where .= ' AND l.id_cliente = '.$args['id_cliente'].'';
        } 

        if(isset($args['situacao']) && $args['situacao']){
            if($args['situacao'] == 1){
                //pago
                $where .= ' AND l.data_pagamento is not NULL AND l.data_pagamento != "0000-00-00"';
            }elseif($args['situacao'] == 2){
                //atrasado
                $where .= ' AND l.data_vencimento < "'.date("Y-m-d").'" AND (l.data_pagamento is NULL || l.data_pagamento = "0000-00-00")';
            }elseif($args['situacao'] == 3){
                //no prazo
                $where .= ' AND l.data_vencimento >= "'.date("Y-m-d").'" AND (l.data_pagamento is NULL || l.data_pagamento = "0000-00-00")';
            }
        }

        if(isset($args['forma_pagamento']) && $args['forma_pagamento'] != ""){
            $where .= ' AND l.forma_pagamento = "'.$args['forma_pagamento'].'"';
        }

        if(isset($args['status']) && $args['status'] != ""){
            if($args['status'] == 1){
                $where .= ' AND l.data_pagamento is not NULL AND l.data_pagamento != "0000-00-00"';
                // $where .= ' AND l.status_asaas = "RECEIVED" OR l.status_asaas = "CONFIRMED" OR l.status_asaas = "RECEIVED_IN_CASH"';
            }else if($args['status'] == 2){
                $where .= ' AND l.status_asaas = "PENDING" AND (l.data_pagamento is NULL OR l.data_pagamento = "0000-00-00")';
                // $where .= ' AND l.status_asaas = "PENDING"';
            }else if($args['status'] == 3){
                $where .= ' AND l.status_asaas != "PENDING" AND (l.data_pagamento is NULL OR l.data_pagamento = "0000-00-00")';
                // $where .= ' AND l.status_asaas <> "RECEIVED" OR l.status_asaas <> "CONFIRMED" OR l.status_asaas <> "PENDING"';
            }
        }

        $dados = $this->db->query('SELECT l.*,c.nome as cliente FROM '.$this->prefixo_db.'financeiro_lancamentos l 
                                    LEFT JOIN '.$this->prefixo_db.'clientes c ON(l.id_cliente = c.id) 
                                    WHERE l.id != 0  '.$where.'  ORDER BY l.id DESC')->result();

		return $dados;
    }

    function buscar_lancamentos_relatorio($args,$month = null){
        $where = '';

        $data_atual_ini = $args['ano'].'-'.sprintf('%02u',$args['mes'])."-01";
        $data_atual_fim = date("Y-m-t", strtotime($data_atual_ini));
        if($month != null){
            $data_atual_ini = date("Y-m-d", strtotime($data_atual_ini." ".$month." month"));
            $data_atual_fim = date("Y-m-t", strtotime($data_atual_ini));
        }

        if(isset($args['tipo_data']) && $args['tipo_data'] == 2){
            $tipo_data = 'l.data_pagamento'; 
        }else if(isset($args['tipo_data']) && $args['tipo_data'] == 3){
            $tipo_data = 'l.cadastrado'; 
        }else{
            $tipo_data = 'l.data_vencimento'; 
        } 

        if($args['ano'] && $args['mes']){
            $where .= ' AND '.$tipo_data.' >= "'.$data_atual_ini.'" AND '.$tipo_data.' <= "'.$data_atual_fim.'"';
        }

        if(isset($args['assinatura']) && $args['assinatura'] != ""){
            $where .= ' AND l.assinatura = '.$args['assinatura'].'';
        }

        if($args['id_cliente']){
            $where .= ' AND l.id_cliente = '.$args['id_cliente'].'';
        }

        if(isset($args['forma_pagamento']) && $args['forma_pagamento'] != ""){
            $where .= ' AND l.forma_pagamento = "'.$args['forma_pagamento'].'"';
        }

        $dados["data"] = date("m/Y", strtotime($data_atual_ini));
        // $dados["valor_total"] = $this->db->query('SELECT SUM(l.valor_total) as resultado FROM '.$this->prefixo_db.'financeiro_lancamentos l  WHERE l.id != 0 '.$where.' ')->row()->resultado;
        $dados["valor_total"] = $this->db->query('SELECT SUM(l.valor_total) as resultado FROM '.$this->prefixo_db.'financeiro_lancamentos l WHERE l.id != 0 '.$where.' AND l.data_vencimento >= "'.date("Y-m-d").'" AND (l.data_pagamento is NULL || l.data_pagamento = "0000-00-00")')->row()->resultado;
        $dados["valor_pago"] = $this->db->query('SELECT SUM(l.valor_total) as resultado FROM '.$this->prefixo_db.'financeiro_lancamentos l WHERE l.id != 0 '.$where.' AND l.data_pagamento is not NULL AND l.data_pagamento != "0000-00-00"')->row()->resultado;
        $dados["valor_nao_pago"] = $this->db->query('SELECT SUM(l.valor_total) as resultado FROM '.$this->prefixo_db.'financeiro_lancamentos l WHERE l.id != 0 '.$where.' AND l.data_vencimento < "'.date("Y-m-d").'" AND (l.data_pagamento is NULL || l.data_pagamento = "0000-00-00")')->row()->resultado;

        $dados["num_atrasado"]= $this->db->query('SELECT COUNT(l.id) as resultado FROM '.$this->prefixo_db.'financeiro_lancamentos l WHERE l.id != 0 '.$where.' AND l.data_vencimento < "'.date("Y-m-d").'" AND (l.data_pagamento is NULL || l.data_pagamento = "0000-00-00")')->row()->resultado;
        $dados["num_prazo"]= $this->db->query('SELECT COUNT(l.id) as resultado FROM '.$this->prefixo_db.'financeiro_lancamentos l WHERE l.id != 0 '.$where.' AND l.data_vencimento >= "'.date("Y-m-d").'" AND (l.data_pagamento is NULL || l.data_pagamento = "0000-00-00")')->row()->resultado;
        $dados["num_pago"]= $this->db->query('SELECT COUNT(l.id) as resultado FROM '.$this->prefixo_db.'financeiro_lancamentos l WHERE l.id != 0 '.$where.' AND l.data_pagamento is not NULL AND l.data_pagamento != "0000-00-00"')->row()->resultado;

		return $dados;
    }

    function buscar_carrinhos_abandonados($args){
        $where = '';

        if(isset($args['tipo_servico']) && $args['tipo_servico']){

        }

        if(isset($args['data_ini']) && isset($args['data_ate']) && $args['data_ini'] && $args['data_ate']){
            // $data_atual_ini = $args['ano'].'-'.sprintf('%02u',$args['mes'])."-01";
            // $data_atual_fim = date("Y-m-t", strtotime($data_atual_ini));
            $data_atual_ini = $args['data_ini'];
            $data_atual_fim = $args['data_ate'];

            $where .= ' AND l.cadastrado >= "'.$data_atual_ini.'" AND l.cadastrado <= "'.$data_atual_fim.'"';
        } 

        if($args['id_cliente']){
            $where .= ' AND c.id_cliente = '.$args['id_cliente'].'';
        }

        $dados_retorno = $this->db->query('SELECT DISTINCT id_sessao FROM '.$this->prefixo_db.'financeiro_carrinho c 
                                    LEFT JOIN '.$this->prefixo_db.'clientes cli ON(c.id_cliente = cli.id) 
                                    WHERE c.id != 0  '.$where.'  ORDER BY c.cadastrado DESC')->result();
        $dados = null;
        if($dados_retorno){
            foreach($dados_retorno as $k => $row){
                
                $historicos = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_carrinho WHERE id_sessao = "'.$row->id_sessao.'"')->result();
                $valor_total = 0;

                foreach($historicos as $historico){
                    $result_historico = $this->db->query('SELECT preco FROM '.$this->prefixo_db.'institucional_produto WHERE id = '.$historico->id_produto)->row();

                    $valor_total += ($result_historico->preco * $historico->quantidade);
                }

                $dados[$k]['id_sessao'] = $row->id_sessao;
                
                $dados[$k]['valor_total'] = $valor_total;
                $dados[$k]['cliente'] = null;

                $dados[$k]['cpf_cnpj'] = $historicos[0]->cpf_cnpj;
                $dados[$k]['cadastrado'] = $historicos[0]->cadastrado;
                
                if($historicos[0]->id_cliente){
                    $dados[$k]['cliente'] =  $this->db->query('SELECT id,nome,cpf_cnpj,email,telefone,celular FROM '.$this->prefixo_db.'clientes WHERE id = '.$historicos[0]->id_cliente)->row();
                }
                

                
            }
        }

		return $dados;
    }

    function buscar_carrinho($id_sessao){
        $dados = null;
        
        if($id_sessao){
                
                $historicos = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_carrinho WHERE id_sessao = "'.$id_sessao.'"')->result();
                
                $valor_total = 0;
                foreach($historicos as $hk => $historico){
                    $result_historico = $this->db->query('SELECT nome,preco FROM '.$this->prefixo_db.'institucional_produto WHERE id = '.$historico->id_produto)->row();
                    $historicos[$hk]->nome = $result_historico->nome;
                    $historicos[$hk]->preco = $result_historico->preco;
                    $valor_total += ($result_historico->preco * $historico->quantidade);
                }

                $dados['id_sessao'] = $id_sessao;
                $dados['historicos'] = $historicos;
                $dados['valor_total'] = $valor_total;
                $dados['cliente'] = null;
                $dados['cpf_cnpj'] = $historicos[0]->cpf_cnpj;
                
                if($historicos[0]->id_cliente){
                    $dados['cliente'] =  $this->db->query('SELECT id,nome,cpf_cnpj FROM '.$this->prefixo_db.'clientes WHERE id = '.$historicos[0]->id_cliente)->row();
                }
        }

		return $dados;
    }

    function buscar_configuracao(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_config WHERE id = 1')->row_array();

		return $dados;
    }

    function salvar_configuracao($dados){

        $resultado["dados"] = $dados; 
        
        $args = array(
            '_tabela' => 'financeiro_config',
            'dados' => $dados,
        );
        $args['where'] = 'id = 1';
        
        $this->atualizar_dados_tabela($args);

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        // log admin
        $this->model_log->salvar_log( array(
            'log' => 'Atualizou as configurações de financeiro',
            // 'id_cliente' => null,
            // 'json' => null,
        ));

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }   


    function buscar_cupons($args = null){

        $where = '';

        if(isset($args['codigo_cupom']) && $args['codigo_cupom']){
            $where .= 'AND codigo_cupom LIKE "%'.$args['codigo_cupom'].'%" ';
        }
        
        if($args['situacao']){
            $where .= 'AND situacao = '.$args['situacao'].' ';
        }

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_cupom WHERE id != 0 '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_cupom($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_cupom WHERE id = '.$id)->row_array();

        if($dados){
            $dados['cupom_utilizado'] = $this->db->query('SELECT cli.nome as nome_cliente, fcu.cadastro FROM '.$this->prefixo_db.'financeiro_cupom_utilizado fcu 
            LEFT JOIN '.$this->prefixo_db.'clientes cli on cli.id = fcu.id_cliente
            WHERE id_cupom = '.$id.' ORDER BY fcu.id DESC')->result_array(); 
        }

		return $dados;
    }

    function salvar_cupom($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

		// $dados['valor'] = (float)$this->remover_caracteres_moeda($dados['valor']);

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;

        $args = array(
            '_tabela' => 'financeiro_cupom',
            'dados' => $dados
        ); 

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou o cupom (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT codigo_cupom FROM '.$this->prefixo_db.'financeiro_cupom WHERE id = '.$resultado["dados"]["id"])->row('codigo_cupom'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o cupom (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT codigo_cupom FROM '.$this->prefixo_db.'financeiro_cupom WHERE id = '.$resultado["dados"]["id"])->row('codigo_cupom'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }
    
    function cupom_excluir($post){
        $dados = $post;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu o cupom (#'.$item.') '.$this->db->query('SELECT codigo_cupom FROM '.$this->prefixo_db.'financeiro_cupom WHERE id = '.$item)->row('codigo_cupom'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
           
            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'financeiro_cupom',
                        'where' => 'id ='.$item
                    )
            );

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    } 

    function buscar_assinaturas($args = null){

        $where = '';

        if(isset($args['nome']) && $args['nome']){
            $where .= 'AND a.nome LIKE "%'.$args['nome'].'%" ';
        }
        
        // if($args['situacao']){
        //     $where .= 'AND situacao = '.$args['situacao'].' ';
        // }

        $dados = $this->db->query('SELECT a.*, c.nome as nome_cliente, c.email, c.telefone, c.celular FROM '.$this->prefixo_db.'financeiro_assinatura a 
        LEFT JOIN '.$this->prefixo_db.'clientes c ON c.id = a.id_cliente 
        WHERE a.id != 0 '.$where.' ORDER BY a.id DESC')->result();

		return $dados;
    }

    function buscar_assinatura($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_assinatura WHERE id = '.$id)->row_array();

        if($dados){
            $dados['cliente'] = $this->db->query('SELECT * FROM '.$this->prefixo_db.'clientes WHERE id = '.$dados['id_cliente'])->row_array();
            $dados['lancamentos'] = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_lancamentos WHERE assinatura = 1 AND id_assinatura = '.$dados['id'].' ORDER BY id DESC')->result_array();
        }

		return $dados;
    }

    function salvar_assinatura($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

		// $dados['valor'] = (float)$this->remover_caracteres_moeda($dados['valor']);

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;

        $args = array(
            '_tabela' => 'financeiro_assinatura',
            'dados' => $dados
        ); 

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou assinatura (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'financeiro_assinatura WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou assinatura (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'financeiro_assinatura WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    } 
}