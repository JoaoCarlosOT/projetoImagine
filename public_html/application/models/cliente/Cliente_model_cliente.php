<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Cliente_model_cliente extends Base_model {

    function buscar_conta(){

        $id = $this->cliente;

        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'clientes WHERE id = '.$id)->row();
        unset($dados->senha);

		return $dados;
    }

    function salvar_conta($dados){
        $dados["id"] = $this->cliente;

        if(!$dados["id"]) return null;

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;

        if(!$dados["nome"]) {
            $resultado["msg"] = "Informe o Nome!";
            return $resultado;
        }

        if(!$dados["cpf_cnpj"]) {
            $resultado["msg"] = "Informe o CPF/CNPJ!";
            return $resultado;
        }

        if(!$dados["email"]) {
            $resultado["msg"] = "Informe o Email!";
            return $resultado;
        }

        if($this->validar_usuario_nome($dados["id"],$dados["cpf_cnpj"])){
            $resultado["msg"] = "CPF/CNPJ já cadastrado";
            return $resultado;
        }
        
        $this->load->library('Imgno_pass', '', 'gerador_hash');
        $dados["senha"] = $this->gerador_hash->gerar_senha($dados["senha"]);

        if(!$dados["senha"]){
            unset($dados["senha"]);
        }else{
            $this->load->library('Imgno_pass', '', 'gerador_hash');
            $dados_envio["senha"] = $this->gerador_hash->gerar_senha($dados["senha"]);
        }

        $dados_envio['nome'] = $dados["nome"];
        $dados_envio['cpf_cnpj'] = $dados["cpf_cnpj"];
        $dados_envio['email'] = $dados["email"];
        $dados_envio['telefone'] = $dados["telefone"];
        $dados_envio['celular'] = (isset($dados["celular"])?$dados["celular"]:'');

        // $dados_envio['data_nascimento'] = $dados["data_nascimento"];
        // $dados_envio['responsavel'] = $dados["responsavel"];
        // $dados_envio['responsavel_cpf'] = $dados["responsavel_cpf"];
        // $dados_envio['grau_parentesco'] = $dados["grau_parentesco"];

        if(isset($dados["notificacao"])){
            $dados_envio['notificacao'] = 1;
            $dados_envio['receber_email'] = 1;
            $dados_envio['receber_sms'] = 1;
        }else{
            $dados_envio['notificacao'] = 0;
        }

        $args = array(
            '_tabela' => 'clientes',
            'dados' => $dados_envio,
        );

        $args['where'] = 'id = '.$dados["id"];
        unset($args["dados"]["id"]);

        $this->atualizar_dados_tabela($args);
        $resultado["usuario"]["id"] = $dados["id"];

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function cadastrar_conta($dados){

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;

        if(!$dados["nome"]) {
            $resultado["msg"] = "Informe o Nome!";
            return $resultado;
        }

        if(!$dados["cpf_cnpj"]) {
            $resultado["msg"] = "Informe o CPF/CNPJ!";
            return $resultado;
        }

        if(!$dados["email"]) {
            $resultado["msg"] = "Informe o Email!";
            return $resultado;
        }

        if($this->validar_usuario_nome(0,$dados["cpf_cnpj"])){
            $resultado["msg"] = "CPF/CNPJ já cadastrado";
            return $resultado;
        }

        if(!$dados["senha"]) {
            $resultado["msg"] = "Informe a Senha!";
            return $resultado;
        }
        
        $this->load->library('Imgno_pass', '', 'gerador_hash');
        $dados["senha"] = $this->gerador_hash->gerar_senha($dados["senha"]);

        $dados_envio['nome'] = $dados["nome"];
        $dados_envio['cpf_cnpj'] = $dados["cpf_cnpj"];
        $dados_envio['email'] = $dados["email"];
        $dados_envio['senha'] = $dados["senha"];
        $dados_envio['telefone'] = $dados["telefone"];
        $dados_envio['celular'] = (isset($dados["celular"])?$dados["celular"]:'');
        $dados_envio['situacao'] = 1;

        // $dados_envio['data_nascimento'] = $dados["data_nascimento"];
        // $dados_envio['responsavel'] = $dados["responsavel"];
        // $dados_envio['responsavel_cpf'] = $dados["responsavel_cpf"];
        // $dados_envio['grau_parentesco'] = $dados["grau_parentesco"];

        if(isset($dados["notificacao"])){
            $dados_envio['notificacao'] = 1;
            $dados_envio['receber_email'] = 1;
            $dados_envio['receber_sms'] = 1;
        }else{
            $dados_envio['notificacao'] = 0;
        }
        

        $args = array(
            '_tabela' => 'clientes',
            'dados' => $dados_envio
        );
        $args['retornar_id'] = true;
        $args["dados"]["cadastrado"] = $args["dados"]["ultimo_login"] = date('Y-m-d H:i:s');

        $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

        $resultado["retorno"] = 1;

        return $resultado;

    }

    function validar_usuario_nome($id_usuario,$usuario){

        if(!$id_usuario) $id_usuario = 0 ;
        $dado = $this->db->query('SELECT id FROM '.$this->prefixo_db.'clientes WHERE id != '.$id_usuario.' AND cpf_cnpj = "'.$usuario.'" ')->row();

        if(!$dado){
            return false;
        }else{
            return true;
        }
    }

    function recuperar_senha($cpf_cnpj){
        $resultado["retorno"] = 0;
        $resultado["cpf_cnpj"] = $cpf_cnpj;

        $dado = $this->db->query('SELECT * FROM '.$this->prefixo_db.'clientes WHERE cpf_cnpj = "'.$cpf_cnpj.'" ')->row();

        if(!$dado){
            $resultado["msg"] = "Cliente não encontrado";
        }

        if(!$dado->email){
            $resultado["msg"] = "Cliente não possui email associado";
        }

        $token = '';

        if (function_exists('random_bytes')) {
			$token = substr(bin2hex(random_bytes(26)), 0, 26);
		} else {
			$token = substr(bin2hex(openssl_random_pseudo_bytes(26)), 0, 26);
        }
        
        $args = array(
            '_tabela' => 'clientes_recuperar_senha',
            'dados' => array(
                'id_cliente'=> $dado->id,
                'token'=> $token,
                'usado'=> 0,
            )
        );
        $args['retornar_id'] = true;
        $args["dados"]["cadastrado"] = date('Y-m-d H:i:s');

        $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

        // $this->load->library('Imgno_email_envios', '', 'email_envios');
        // $this->email_envios->enviarEmailRecuperacao($token);
        $this->load->library('Imgno_email', '', 'enviarEmail');
        $this->enviarEmail->enviarEmailRecuperacao($token); 

        $resultado["retorno"] = 1;

        return $resultado;

    }

    function alterar_senha_token($post){

        $resultado["retorno"] = 0;
        $resultado["dados"] = $post;

        $dados_token = $this->db->query('SELECT * FROM '.$this->prefixo_db.'clientes_recuperar_senha WHERE token = "'.$post['token'].'" AND usado = 0 AND cadastrado >= "'.date('Y-m-d H:i:s', strtotime('-1 day')).'" ')->row();

        if(!$dados_token) {
            $resultado["msg"] = "Token inválido!";
            return $resultado;
        }

        $this->load->library('Imgno_pass', '', 'gerador_hash');
        $dados["senha"] = $this->gerador_hash->gerar_senha($post["senha"]);

        $args = array(
            '_tabela' => 'clientes',
            'dados' => $dados,
            'where'=> 'id = '.$dados_token->id_cliente
        );
        $this->atualizar_dados_tabela($args);


        $this->atualizar_dados_tabela(array(
            '_tabela' => 'clientes_recuperar_senha',
            'dados' => array(
                    'usado' => 1
                ),
            'where' => 'token = "'.$dados_token->token.'"'
        ));

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function buscar_informativos(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_informativos WHERE situacao = 1 ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_informativo($id){
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_informativos WHERE situacao = 1 AND id = '.$id.'')->row();

		return $dados;
    }

    
    
}
