<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Modulos_model_copywriter extends Base_model {

    public function buscar_copywriter($id = 0){

        if(!$id){
            return false;
        }

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'copywriter WHERE id = '.$id.' AND situacao = 1')->row_array();

        return $dados;
    }

    public function buscar_config($select = ''){

        if(!$select){
            $select = '*';
        }

        $dados = $this->db->query('SELECT '.$select.' FROM '.$this->prefixo_db.'copywriter_config WHERE id = 1')->row_array();

        return $dados;
    }
}