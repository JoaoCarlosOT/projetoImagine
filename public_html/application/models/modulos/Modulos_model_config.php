<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Modulos_model_config extends Base_model {

    public function buscar_config($select = ''){

        if(!$select){
            $select = '*';
        }

        $dados = $this->db->query('SELECT '.$select.' FROM '.$this->prefixo_db.'contato_config WHERE id = 1')->row_array();

        return $dados;
    }

    public function buscar_config_checkout(){

        if($this->db->table_exists($this->prefixo_db.'financeiro_config')){
            $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'financeiro_config WHERE id = 1')->row_array();

            if(isset($dados['formas_pagamento'])) $dados['formas_pagamento'] = json_decode($dados['formas_pagamento'],true);

            return $dados;
        }else{
            return  false;
        }
    }

}