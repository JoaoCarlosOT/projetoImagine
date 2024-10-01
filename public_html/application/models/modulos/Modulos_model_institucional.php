<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Modulos_model_institucional extends Base_model {

    public function buscar_depoimentos(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_depoimentos WHERE situacao = 1')->result();

        return $dados;
    }

    public function buscar_bignumber($id = 0){

        if(!$id){
            return false;
        }

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_bignumbers WHERE id = '.$id.' AND situacao = 1')->row_array();

        return $dados;
    }

}