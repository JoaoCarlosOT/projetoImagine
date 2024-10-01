<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Modulos_model_modulo extends Base_model {

    public function buscar_modulo($id = 0){

        if(!$id){
            return false;
        }

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'modulo_personalizado WHERE id = '.$id.' AND situacao = 1')->row_array();

        return $dados;
    }

}