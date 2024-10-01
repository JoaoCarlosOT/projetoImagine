<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_admin extends Base_model {

    function buscar_conta(){

        $id = $this->admin;

        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'admin WHERE id = '.$id)->row();
        unset($dados->senha);

		return $dados;
    }

    function salvar_conta($dados){
        $dados["id"] = $this->admin;

        if(!$dados["id"]) return null;

        $resultado["retorno"] = 0;
        $resultado["usuario"] = $dados;

        if(!$dados["nome"]) {
            $resultado["msg"] = "Informe o nome do usuário!";
            return $resultado;
        }

        if($this->validar_admin_nome($dados["id"],$dados["usuario"])){
            $resultado["msg"] = "Usuário já existente!";
            return $resultado;
        }


        if(!$dados["senha"]){
            unset($dados["senha"]);
        }else{
            $this->load->library('Imgno_pass', '', 'gerador_hash');

            $dados["senha"] = $this->gerador_hash->gerar_senha($dados["senha"]);
        }
       

        $args = array(
            '_tabela' => 'admin',
            'dados' => $dados,
        );

        $args['where'] = 'id = '.$dados["id"];
        unset($args["dados"]["id"]);

        $this->atualizar_dados_tabela($args);
        $resultado["usuario"]["id"] = $dados["id"];

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        
        $this->load->model('admin/Admin_model_log', 'model_log');
        // log admin
        $this->model_log->salvar_log( array(
            'log' => 'Atualizou a conta',
            // 'id_cliente' => null,
            // 'json' => null,
        ));


        return $resultado;
    }

    function buscar_usuarios($args = null){

        $where = '';

        if(!empty($args['nome'])){
            $where .= 'AND nome LIKE "%'.$args['nome'].'%" ';
        }

        
        $dados = $this->db->query('SELECT id,nome,email,situacao,ultimo_login FROM '.$this->prefixo_db.'admin WHERE id != 0 '.$where.' ORDER BY nome')->result();

		return $dados;
    }

    function buscar_usuario($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'admin WHERE id = '.$id)->row();
        unset($dados->senha);
        $dados->permissoes =  $this->buscar_usuario_permissao($dados->id);

		return $dados;
    }

    function buscar_lista_permissoes(){
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'admin_lista_permissoes ORDER BY ordem ASC, aba ASC, nome ASC')->result();
        
        $abas_permissoes = array();

        foreach($dados as $dado){
            $abas_permissoes[$dado->aba][] = $dado;
        }

		return $abas_permissoes;
    }

    function buscar_usuario_permissao($id_usuario, $permissao = null){
        if(!$id_usuario) return null;

        $dados = $this->db->query('SELECT permissao FROM '.$this->prefixo_db.'admin_permissoes WHERE id_usuario = '.$id_usuario)->result();
        
		return $dados;
    }

    function salvar_usuario($dados){
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["usuario"] = $dados;

        if(!$dados["nome"]) {
            $resultado["msg"] = "Informe o nome do usuário!";
            return $resultado;
        }

        if($this->validar_usuario_nome($dados["id"],$dados["usuario"])){
            $resultado["msg"] = "Usuário já existente!";
            return $resultado;
        }


        if(!$dados["senha"]){
            unset($dados["senha"]);
        }else{
            $this->load->library('Imgno_pass', '', 'gerador_hash');

            $dados["senha"] = $this->gerador_hash->gerar_senha($dados["senha"]);
        }

        $permissoes = null;

        if(isset($dados["permissoes"])){
            $permissoes = $dados["permissoes"];
            unset($dados["permissoes"]);
        }
       

        $args = array(
            '_tabela' => 'admin',
            'dados' => $dados,
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);

            $this->atualizar_dados_tabela($args);

            $resultado["usuario"]["id"] = $dados["id"];


            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou o usuário (#'.$resultado["usuario"]["id"].') '.$dados["nome"],
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastrado"] = date('Y-m-d H:i:s');
            $resultado["usuario"]["id"] = $this->inserir_dados_tabela($args);

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o usuário (#'.$resultado["usuario"]["id"].') '.$dados["nome"],
                // 'id_cliente' => null,
                // 'json' => null,
            ));

        }

        // Permissoes
        $this->remover_dados_tabela(array(
            '_tabela' => 'admin_permissoes',
            'where' => 'id_usuario = '.$resultado["usuario"]["id"]
        ));
        if($permissoes){
            foreach($permissoes as $permissao => $v){
            $this->inserir_dados_tabela(array(
                                '_tabela' => 'admin_permissoes',
                                'dados' => array(
                                    'id_usuario' => $resultado["usuario"]["id"],
                                    'permissao' => $permissao,
                                )
                            ));
            }
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }
    
    function admin_excluir($post){
        $dados = $post;

        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu o usuário (#'.$item.') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'admin WHERE id = '.$item)->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
           
            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'admin',
                        'where' => 'id ='.$item
                    )
            );

            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'admin_permissoes',
                    'where' => 'id_usuario = '.$item
                )
            );

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }

    function validar_admin_nome($id_usuario,$usuario){

        if(!$id_usuario) $id_usuario = 0 ;
        $dado = $this->db->query('SELECT id FROM '.$this->prefixo_db.'admin WHERE id != '.$id_usuario.' AND usuario = "'.$usuario.'" ')->row();

        if(!$dado){
            return false;
        }else{
            return true;
        }
    }

    function validar_usuario_nome($id_usuario,$usuario){

        if(!$id_usuario) $id_usuario = 0 ;
        $dado = $this->db->query('SELECT id FROM '.$this->prefixo_db.'admin WHERE id != '.$id_usuario.' AND usuario = "'.$usuario.'" ')->row();

        if(!$dado){
            return false;
        }else{
            return true;
        }
    }

   
    
}
