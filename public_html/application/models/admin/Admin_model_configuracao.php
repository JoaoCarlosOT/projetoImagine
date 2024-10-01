<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_configuracao extends Base_model {


    function buscar_configuracao(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'config WHERE id = 1')->row_array();

		return $dados;
    }

    function salvar_configuracao($dados){

        $resultado["dados"] = $dados;

        /*
        if(!isset($dados['popup_cookies'])){
            $dados['popup_cookies'] = 0;
        }*/

        $args = array(
            '_tabela' => 'config',
            'dados' => $dados,
        );
        $args['where'] = 'id = 1';
        
        $this->atualizar_dados_tabela($args);

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        // log admin
        $this->model_log->salvar_log( array(
            'log' => 'Atualizou as configurações',
            // 'id_cliente' => null,
            // 'json' => null,
        ));

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }    

    function excluirPermissoes($permissoes){
        if(!$permissoes) return;

        $like = "'".implode("', '", $permissoes)."'";

        $valores = $this->db->query('SELECT valor FROM '.$this->prefixo_db.'admin_lista_permissoes WHERE aba IN('.$like.')')->result_array();

        if($valores){

            $permi = array_map(function($valor) {
                return $valor['valor'];
            }, $valores); 

            $like_2 = "'".implode("', '", $permi)."'";
            
            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'admin_permissoes',
                    'where' => 'permissao IN('.$like_2.')'
                )
            );

            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'admin_lista_permissoes',
                    'where' => 'aba IN('.$like.')'
                )
            );
            
        }
        
    }

    function excluirTabelas($tabelas){
        if(!$tabelas) return;
        
        foreach($tabelas as $tabela){
            $this->db->query('DROP TABLE IF EXISTS '.$tabela);
        }
    }
}
