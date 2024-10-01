<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_cliente extends Base_model {
    function buscar_clientes($args = null){

        $where = '';

        if(isset($args['nome']) && $args['nome']){
            $where .= 'AND nome LIKE "%'.$args['nome'].'%" ';
        }
        
        if($args['situacao']){
            $where .= 'AND situacao = '.$args['situacao'].' ';
        }

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'clientes WHERE id != 0 '.$where.' ORDER BY nome ASC')->result();

		return $dados;
    }

    function buscar_cliente($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'clientes WHERE id = '.$id)->row_array();

		return $dados;
    }
    function salvar_cliente($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');


        if(!$dados["nome"]) {
            $resultado["msg"] = "Informe o nome do usuário!";
            return $resultado;
        }

        if(!$dados["senha"]){
            unset($dados["senha"]);
        }else{
            $this->load->library('Imgno_pass', '', 'gerador_hash');

            $dados["senha"] = $this->gerador_hash->gerar_senha($dados["senha"]);
        }

        $args = array(
            '_tabela' => 'clientes',
            'dados' => $dados
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou os dados do cliente',
                'id_cliente' => $resultado["dados"]["id"],
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastrado"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o cliente',
                'id_cliente' => $resultado["dados"]["id"],
                // 'json' => null,
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function excluir_clientes($post){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $dados = $post;

        foreach($dados['id'] as $item){

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu o cliente',
                'id_cliente' => $item,
                // 'json' => null,
            ));
           
            $this->atualizar_dados_tabela(
                    array(
                        '_tabela' => 'clientes',
                        'dados' => array('situacao'=>-1),
                        'where' => 'id = '.$item
                    )
            );

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }
}