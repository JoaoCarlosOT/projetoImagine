<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_faq extends Base_model {

    function buscar_faqs($args = null){

        $where = '';

        if(isset($args['pergunta']) && $args['pergunta']){
            $where .= 'AND pergunta LIKE "%'.$args['pergunta'].'%" ';
        }
        
        if($args['situacao']){
            $where .= 'AND situacao = '.$args['situacao'].' ';
        }

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'faq WHERE id != 0 '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_faq($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'faq WHERE id = '.$id)->row_array();

		return $dados;
    }

    function salvar_faq($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;

        $args = array(
            '_tabela' => 'faq',
            'dados' => $dados
        );

        
        $args["dados"]["atualizado"] = date('Y-m-d H:i:s');

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou o FAQ (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT pergunta FROM '.$this->prefixo_db.'faq WHERE id = '.$resultado["dados"]["id"])->row('pergunta'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o FAQ (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT pergunta FROM '.$this->prefixo_db.'faq WHERE id = '.$resultado["dados"]["id"])->row('pergunta'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }
    
    function faq_excluir($post){
        $dados = $post;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu o FAQ (#'.$item.') '.$this->db->query('SELECT pergunta FROM '.$this->prefixo_db.'faq WHERE id = '.$item)->row('pergunta'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
           
            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'faq',
                        'where' => 'id ='.$item
                    )
            );

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }

}