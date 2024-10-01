<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_contato extends Base_model {

    
    function buscar_mensagens($args = null){

        $where = '';

        if(isset($args['nome'])){
            $where .= 'AND nome LIKE "%'.$args['nome'].'%" ';
        }
        if(isset($args['email'])){
            $where .= 'AND email LIKE "%'.$args['email'].'%" ';
        }
        if(isset($args['telefone'])){
            $where .= ' AND (telefone1 = "'.$args['telefone'].'" OR telefone2 = "'.$args['telefone'].'" ) ';
        }

        if(isset($args['data_ini'])){
            $where .= 'AND datahora_cadastro >= "'.$args['data_ini'].' 00:00:00" AND datahora_cadastro <= "'.$args['data_ini'].' 23:59:59" ';
        }
        

        
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'contato_mensagens WHERE id != 0 '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_mensagem($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'contato_mensagens WHERE id = '.$id)->row_array();

		return $dados;
    }

    function salvar_mensagem($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;
       

        $args = array(
            '_tabela' => 'contato_mensagens',
            'dados' => array(
                'observacoes'=>$dados['observacoes']
            ),
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);

            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou a mensagem (#'.$resultado["dados"]["id"].') ',
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }


    function buscar_trabalhe_mensagens($args = null){

        $where = '';

        if(isset($args['nome'])){
            $where .= 'AND nome LIKE "%'.$args['nome'].'%" ';
        }
        if(isset($args['email'])){
            $where .= 'AND email LIKE "%'.$args['email'].'%" ';
        }
        if(isset($args['telefone'])){
            $where .= ' AND (telefone1 = "'.$args['telefone'].'" OR telefone2 = "'.$args['telefone'].'" ) ';
        }

        if(isset($args['data_ini'])){
            $where .= 'AND cadastro >= "'.$args['data_ini'].' 00:00:00" AND cadastro <= "'.$args['data_ini'].' 23:59:59" ';
        }
        

        
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'contato_trabalhe WHERE id != 0 '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_trabalhe_mensagem($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'contato_trabalhe WHERE id = '.$id)->row_array();

		return $dados;
    }

    function buscar_vagas($args = null){

        $where = '';

        if(isset($args['nome'])){
            $where .= 'AND nome LIKE "%'.$args['nome'].'%" ';
        }
        
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'contato_vagas WHERE id != 0 '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_vaga($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'contato_vagas WHERE id = '.$id)->row_array();

		return $dados;
    }

    function salvar_vaga($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;
       

        $args = array(
            '_tabela' => 'contato_vagas',
            'dados' => $dados
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou a vaga (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'contato_vagas WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);
            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou a vaga (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'contato_vagas WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));

        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function buscar_boletim_mensagens($args = null){

        $where = '';

        if(isset($args['nome'])){
            $where .= 'AND nome LIKE "%'.$args['nome'].'%" ';
        }
        if(isset($args['email'])){
            $where .= 'AND email LIKE "%'.$args['email'].'%" ';
        }
        
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'contato_boletim WHERE id != 0 '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_config(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'contato_config WHERE id = 1')->row_array();

		return $dados;
    }

    function salvar_config($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;
       
        $anexo = '';
        if(!empty($_FILES['imagem']['name'][0])) {
                // Carrega a biblioteca necessária
                $this->load->library('Imgno_upload', '', 'upload_arquivos');

                // Define o caminho onde os arquivos serão salvos
                $dir = realpath('arquivos').'/imagens/contato/';
                $nomes = array();

                $this->upload_arquivos->enviar_arquivos('', 'imagem', $dir, $nomes);

            $dados['logomarca'] =  $nomes[0];
            
        }

        if(isset($dados['campos']) && $dados['campos']){
            
            $dados['campos'] = json_encode($dados['campos']);

        }else{
            $dados['campos'] = null;
        }


        if(!isset($dados['popup_cookies'])){
            $dados['popup_cookies'] = 0;
        }

        $args = array(
            '_tabela' => 'contato_config',
            'dados' => $dados
        );
        $args['where'] = 'id = 1';
        
        $this->atualizar_dados_tabela($args);

        // log admin
        $this->model_log->salvar_log( array(
            'log' => 'Atualizou as configurações de contato',
            // 'id_cliente' => null,
            // 'json' => null,
        ));

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function buscar_config_email(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'contato_email_mensagem WHERE id = 1')->row_array();

		return $dados;
    }

    function salvar_config_email($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;
       

        $args = array(
            '_tabela' => 'contato_email_mensagem',
            'dados' => $dados
        );
        $args['where'] = 'id = 1';

        $this->atualizar_dados_tabela($args);

        // log admin
        $this->model_log->salvar_log( array(
            'log' => 'Atualizou as configurações de emails',
            // 'id_cliente' => null,
            // 'json' => null,
        ));

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }
   
    
}
