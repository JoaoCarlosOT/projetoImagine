<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Modulos_model_faq extends Base_model {

    public function buscar_faqs(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'faq WHERE situacao = 1')->result();

        return $dados;
    }

}