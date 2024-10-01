<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_log extends Base_model {

	public function salvar_log($dados){
		$args = array(
            '_tabela' => 'admin_logs',
            'dados' => $dados
		);
		
        $args["dados"]["id_usuario"] = $this->admin;
        $args["dados"]["cadastrado"] = date('Y-m-d H:i:s');

        $this->inserir_dados_tabela($args);
	}

    function buscar_usuarios($args = null){
        $where = '';

        if(isset($args['situacao']) && $args['situacao']){
            $where .= ' AND situacao = '.$args['situacao'].'';
        }

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'admin WHERE id != 0 '.$where)->result_array();

		return $dados;
    }

    function buscar_logs($args = null){

        $where = '';

        if(isset($args['log']) && $args['log']){
            $where .= ' AND l.log LIKE "%'.$args['log'].'%" ';
        }

        if(isset($args['id_usuario']) && $args['id_usuario']){
            $where .= ' AND l.id_usuario = '.$args['id_usuario'].'';
        }

        if(isset($args['id_cliente']) && $args['id_cliente']){
            $where .= ' AND l.id_cliente = '.$args['id_cliente'].'';
        }

        if(isset($args['data_ini']) && $args['data_ini']){
            $where .= ' AND l.cadastrado >= "'.$args['data_ini'].' 00:00:00"';
        }

        if(isset($args['data_fim']) && $args['data_fim']){
            $where .= ' AND l.cadastrado <= "'.$args['data_fim'].' 23:59:59"';
        }
        
        if(file_exists(APPPATH .'models/admin/Admin_model_cliente.php')){
            $dados = $this->db->query('SELECT l.*,adm.nome as usuario_nome,c.nome as cliente_nome FROM '.$this->prefixo_db.'admin_logs as l 
            LEFT JOIN '.$this->prefixo_db.'admin as adm ON (adm.id = l.id_usuario) 
            LEFT JOIN '.$this->prefixo_db.'clientes as c ON (c.id = l.id_cliente) 
            WHERE l.id != 0 '.$where.' ORDER BY l.cadastrado DESC')->result_array();
        }else{
            $dados = $this->db->query('SELECT l.*,adm.nome as usuario_nome FROM '.$this->prefixo_db.'admin_logs as l 
            LEFT JOIN '.$this->prefixo_db.'admin as adm ON (adm.id = l.id_usuario) 
            WHERE l.id != 0 '.$where.' ORDER BY l.cadastrado DESC')->result_array();
        }

		return $dados;
    }

}