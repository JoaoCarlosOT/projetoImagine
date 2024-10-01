<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Cron_model_cron extends Base_model {

    public function cadastrar_cliente($dados){

        $dados_envio['nome'] = $dados["nome"];
        $dados_envio['cpf_cnpj'] = $dados["cpf"];
        $dados_envio['email'] = $dados["email"];
        $dados_envio['telefone'] = $dados["telefone"];
        // $dados_envio['celular'] = $dados["celular"];
        $dados_envio['situacao'] = 1;

        $dados_envio['notificacao'] = 0;

        $this->load->library('Imgno_pass', '', 'gerador_hash');
        $dados_envio["senha"] = $this->gerador_hash->gerar_senha( substr($dados["cpf"],0,3) );
        // $dados_envio["senha"] = substr($dados["cpf"],0,3);

        $args = array(
            '_tabela' => 'clientes',
            'dados' => $dados_envio,
        );

        $args["dados"]["cadastrado"] = date('Y-m-d H:i:s');

        $this->inserir_dados_tabela($args);

    }
}
