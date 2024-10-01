<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Usuario_model_usuario extends Base_model {

    function buscar_conta(){

        $id = $this->usuario;

        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'usuarios WHERE id = '.$id)->row();
        unset($dados->senha);

		return $dados;
    }
    function salvar_conta($dados){
        $dados["id"] = $this->usuario;

        if(!$dados["id"]) return null;

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
       

        $args = array(
            '_tabela' => 'usuarios',
            'dados' => $dados,
        );

        $args['where'] = 'id = '.$dados["id"];
        unset($args["dados"]["id"]);

        $this->atualizar_dados_tabela($args);
        $resultado["usuario"]["id"] = $dados["id"];

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function buscar_usuario_permissao($id_usuario, $permissao = null){
        if(!$id_usuario) return null;

        $dados = $this->db->query('SELECT permissao FROM '.$this->prefixo_db.'usuarios_permissoes WHERE id_usuario = '.$id_usuario)->result();
        
		return $dados;
    }

    function validar_usuario_nome($id_usuario,$usuario){

        if(!$id_usuario) $id_usuario = 0 ;
        $dado = $this->db->query('SELECT id FROM '.$this->prefixo_db.'usuarios WHERE id != '.$id_usuario.' AND usuario = "'.$usuario.'" ')->row();

        if(!$dado){
            return false;
        }else{
            return true;
        }
    }

    
    
}
