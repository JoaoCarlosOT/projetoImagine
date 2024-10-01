<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_lgpd extends Base_model {


    function buscar_lgpd(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'lgpd WHERE id = 1')->row_array();

		return $dados;
    }

    function salvar_lgpd($dados){
        $dados['data_atualizado'] = date("Y-m-d");
        $resultado["dados"] = $dados;

        $args = array(
            '_tabela' => 'lgpd',
            'dados' => $dados,
        );
        $args['where'] = 'id = 1';
        
        $this->atualizar_dados_tabela($args);

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        // log admin
        $this->model_log->salvar_log( array(
            'log' => 'Atualizou as LGPDs',
            // 'id_cliente' => null,
            // 'json' => null,
        ));

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }  
}
