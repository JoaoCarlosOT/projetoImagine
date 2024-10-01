<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_copywriter extends Base_model {

    function buscar_copywriters($args = null){

        $where = '';

        if(isset($args['titulo']) && $args['titulo']){
            $where .= 'AND titulo LIKE "%'.$args['titulo'].'%" ';
        }
        
        if($args['situacao']){
            $where .= 'AND situacao = '.$args['situacao'].' ';
        }

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'copywriter WHERE id != 0 '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_copywriter($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'copywriter WHERE id = '.$id)->row_array();

		return $dados;
    }

    function salvar_copywriter($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;

        if(!empty($_FILES['imagem_anexo']['name'][0])) {
            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');

            // Define o caminho onde os arquivos serão salvos
            $dir = realpath('arquivos').'/imagens/copywriter/';
            if(!file_exists($dir)) mkdir($dir, 0777, true);
            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);

            $dados['imagem'] =  $nomes[0];
        
        }

        $args = array(
            '_tabela' => 'copywriter',
            'dados' => $dados
        );

        
        $args["dados"]["atualizado"] = date('Y-m-d H:i:s');

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou o copywriter (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT titulo FROM '.$this->prefixo_db.'copywriter WHERE id = '.$resultado["dados"]["id"])->row('titulo'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o copywriter (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT titulo FROM '.$this->prefixo_db.'copywriter WHERE id = '.$resultado["dados"]["id"])->row('titulo'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }
    
    function copywriter_excluir($post){
        $dados = $post;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu o copywriter (#'.$item.') '.$this->db->query('SELECT titulo FROM '.$this->prefixo_db.'copywriter WHERE id = '.$item)->row('titulo'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
           
            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'copywriter',
                        'where' => 'id ='.$item
                    )
            );

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }

    function buscar_configuracao(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'copywriter_config WHERE id = 1')->row_array();

		return $dados;
    }

    function salvar_configuracao($dados){

        $resultado["dados"] = $dados; 

        $args = array(
            '_tabela' => 'copywriter_config',
            'dados' => $dados,
        );
        $args['where'] = 'id = 1';
        
        $this->atualizar_dados_tabela($args);

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        // log admin
        $this->model_log->salvar_log( array(
            'log' => 'Atualizou as configuração de Copywriter',
            // 'id_cliente' => null,
            // 'json' => null,
        ));

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }  
}