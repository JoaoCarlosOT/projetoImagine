<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_controller.php');

class Cron_controller_cron extends Base_controller {
    function formata_cpf_cnpj($cpf_cnpj){
        /*
            Pega qualquer CPF e CNPJ e formata

            CPF: 000.000.000-00
            CNPJ: 00.000.000/0000-00
        */

        ## Retirando tudo que não for número.
        $cpf_cnpj = preg_replace("/[^0-9]/", "", $cpf_cnpj);
        $tipo_dado = NULL;
        if(strlen($cpf_cnpj)==11){
            $tipo_dado = "cpf";
        }
        if(strlen($cpf_cnpj)==14){
            $tipo_dado = "cnpj";
        }
        switch($tipo_dado){
            default:
                $cpf_cnpj_formatado = '';
            break;

            case "cpf":
                $bloco_1 = substr($cpf_cnpj,0,3);
                $bloco_2 = substr($cpf_cnpj,3,3);
                $bloco_3 = substr($cpf_cnpj,6,3);
                $dig_verificador = substr($cpf_cnpj,-2);
                $cpf_cnpj_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."-".$dig_verificador;
            break;

            case "cnpj":
                $bloco_1 = substr($cpf_cnpj,0,2);
                $bloco_2 = substr($cpf_cnpj,2,3);
                $bloco_3 = substr($cpf_cnpj,5,3);
                $bloco_4 = substr($cpf_cnpj,8,4);
                $digito_verificador = substr($cpf_cnpj,-2);
                $cpf_cnpj_formatado = $bloco_1.".".$bloco_2.".".$bloco_3."/".$bloco_4."-".$digito_verificador;
            break;
        }
        return $cpf_cnpj_formatado;
    }

    public function pacientes(){
        $this->load->library('Imgno_medicinadireta', '', 'medicinadireta');
        $pacientes = $this->medicinadireta->pacientes();

        $count_i = 0;

        // echo '<pre>';
        //     var_dump($pacientes);exit;

        foreach($pacientes as $paciente){
            echo '<pre>';
            var_dump($paciente);exit;
            $paciente['cpf'] = $this->formata_cpf_cnpj($paciente['cpf']);
            if($paciente['cpf']){

                if($count_i == 10){

                    exit;
                }
                $dado = $this->model->db->query('SELECT id FROM '.$this->model->prefixo_db.'clientes WHERE cpf_cnpj = "'.$paciente['cpf'].'"')->row();

                if(!$dado){
                    $this->model->cadastrar_cliente($paciente);

                    $count_i++;
                }
                
            }
        }

        exit;
    }


}