<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_banner extends Base_model {

    
    function buscar_banners($args = null){

        $where = '';

        if(!empty($args['nome'])){
            $where .= 'AND nome LIKE "%'.$args['nome'].'%" ';
        }
        
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'banner WHERE id != 0 '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_banner($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'banner WHERE id = '.$id)->row_array();

		return $dados;
    }

    function salvar_banner($dados){
        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;
       
        
        $this->load->model('admin/Admin_model_log', 'model_log');


        $args = array(
            '_tabela' => 'banner',
            'dados' => $dados
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou o banner (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'banner WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o banner (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'banner WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));

        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function buscar_slides($id_banner,$args = null){

        $where = '';

        if(!empty($args['nome'])){
            $where .= 'AND nome LIKE "%'.$args['nome'].'%" ';
        }
        
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'banner_slide WHERE banner_id = '.$id_banner.' '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_slide($id_banner,$id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'banner_slide WHERE banner_id = '.$id_banner.' AND id = '.$id)->row_array();

		return $dados;
    }

    function salvar_slide($id_banner,$dados){

        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;
        $dados['banner_id'] = $id_banner;

        // Carrega a biblioteca necessária
        $this->load->library('Imgno_upload', '', 'upload_arquivos');
        $dir = realpath('arquivos').'/imagens/banner/';

        if(!empty($_FILES['fullhd']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'fullhd', $dir, $nomes);
            $dados['fullhd'] =  $nomes[0];
        }

        if(!empty($_FILES['extralarge']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'extralarge', $dir, $nomes);
            $dados['extralarge'] =  $nomes[0];
        }

        if(!empty($_FILES['large']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'large', $dir, $nomes);
            $dados['large'] =  $nomes[0];
        }

        if(!empty($_FILES['medium']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'medium', $dir, $nomes);
            $dados['medium'] =  $nomes[0];
        }

        if(!empty($_FILES['small']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'small', $dir, $nomes);
            $dados['small'] =  $nomes[0];
        }

        if(!empty($_FILES['extrasmall']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'extrasmall', $dir, $nomes);
            $dados['extrasmall'] =  $nomes[0];
        }

        if(!empty($_FILES['imagemBanner']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'imagemBanner', $dir, $nomes);
            $dados['imagemBanner'] =  $nomes[0];
        }

        // videos
        if(!empty($_FILES['video_fullhd']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'video_fullhd', $dir, $nomes);
            $dados['video_fullhd'] =  $nomes[0];
        }

        if(!empty($_FILES['video_extralarge']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'video_extralarge', $dir, $nomes);
            $dados['video_extralarge'] =  $nomes[0];
        }

        if(!empty($_FILES['video_large']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'video_large', $dir, $nomes);
            $dados['video_large'] =  $nomes[0];
        }

        if(!empty($_FILES['video_medium']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'video_medium', $dir, $nomes);
            $dados['video_medium'] =  $nomes[0];
        }

        if(!empty($_FILES['video_small']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'video_small', $dir, $nomes);
            $dados['video_small'] =  $nomes[0];
        }

        if(!empty($_FILES['video_extrasmall']['name'][0])) {
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'video_extrasmall', $dir, $nomes);
            $dados['video_extrasmall'] =  $nomes[0];
        } 
        
        if(!$dados['ordem'] || $dados['ordem'] < 0){
            $dados['ordem'] = 0;
        }

        $args = array(
            '_tabela' => 'banner_slide',
            'dados' => $dados
        );

        if($dados["id"] > 0){
            $args['where'] = 'banner_id = '.$id_banner.' AND id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou o slider (#'.$resultado["dados"]["id"].')'.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'banner_slide WHERE id = '.$resultado["dados"]["id"])->row('nome').' ',
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o slider (#'.$resultado["dados"]["id"].')'.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'banner_slide WHERE id = '.$resultado["dados"]["id"])->row('nome').' ',
                // 'id_cliente' => null,
                // 'json' => null,
            ));

        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }
    
    function banner_excluir($post){
        $dados = $post;

        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu o banner (#'.$item.')'.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'banner WHERE id = '.$item)->row('nome').' ',
                // 'id_cliente' => null,
                // 'json' => null,
            ));
           
            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'banner',
                        'where' => 'id ='.$item
                    )
            );

            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'banner_slide',
                    'where' => 'banner_id = '.$item
                )
            );

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }
    
    function slides_excluir($post){
        $dados = $post;

        $this->load->model('admin/Admin_model_log', 'model_log');
        
        foreach($dados['id'] as $item){

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu o slider (#'.$item.')'.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'banner_slide WHERE id = '.$item)->row('nome').' ',
                // 'id_cliente' => null,
                // 'json' => null,
            ));

            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'banner_slide',
                    'where' => 'id = '.$item
                )
            );

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }
    
}
