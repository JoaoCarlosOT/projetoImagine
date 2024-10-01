<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_seo extends Base_model {

    function verificar_cadastrar($routes){
        $ignorar = array('admin','webhook','cron','ajax','default_controller','404_override','posts-instagram(?:\.html)?','posts-facebook(?:\.html)?','erro(?:\.html)?');
        $ignorar2 = array('([A-Za-z0-9_\-]+)');

        $novo = false;
        if($routes){

            foreach($routes as $k => $route){

                $routek_ex = explode('/',$k);
                $route_ex = explode('/',$route);

                $tem_sub = false;
                foreach($routek_ex as $routek_ex_1){

                    $routek_ex_1 = str_replace("(?:\.html)?", "", $routek_ex_1);
                    if( isset($routek_ex_1) && in_array($routek_ex_1,$ignorar2)  ){
                        $tem_sub = true;
                    }
                }

                if($tem_sub){
                    continue;
                }

                if( isset($route_ex[0]) && isset($routek_ex[0]) && !in_array($route_ex[0],$ignorar) && !in_array($routek_ex[0],$ignorar) ){

                    $link = str_replace("(?:\.html)?", ".html", $k);
                    $route_link = str_replace("(?:\.html)?", "", $k);
                    $route_value = (isset($route_ex[0])?$route_ex[0].'/':''). (isset($route_ex[1])?$route_ex[1]:''). (isset($route_ex[2])?'/'.$route_ex[2]:'');

                    $retorno_alias = $this->db->query('SELECT id FROM '. $this->prefixo_db .'seo_link WHERE (link != "" AND link LIKE "%'.$link.'%") ')->row();

                    if($retorno_alias){
                        continue; 
                    }

                   
                    $this->inserir_dados_tabela(array(
                        '_tabela' => 'seo_link',
                        'dados' => array(
                            'link' =>  $link,
                            'route' =>  $route_link,
                            'route_value' =>  $route_value,
                            'title' =>  '',
                            'description' =>  '',
                            'keywords' =>  '',
                            'canonical' =>  base_url().$link,
                        )
                    ));

                    $novo = true;

                }

            }
        }


        return $novo;

    }

    function buscar_links($args = null){

        $where = '';

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'seo_link WHERE id != 0 AND habilitado = 0'.$where.' ORDER BY link ASC')->result();

		return $dados;
    }

    function buscar_link($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'seo_link WHERE id = '.$id)->row_array();
		return $dados;
    }

    function salvar_link($dados){
        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;
        $dados_enviar = array();

        $dados_enviar['title'] = $dados['title'];
        $dados_enviar['description'] = $dados['description'];
        $dados_enviar['keywords'] = $dados['keywords'];
        $dados_enviar['canonical'] = $dados['canonical'];

        $args = array(
            '_tabela' => 'seo_link',
            'dados' => $dados_enviar
        );
        $args['where'] = 'id = '.$dados["id"];

        unset($args["dados"]["id"]);
        $this->atualizar_dados_tabela($args);

        $resultado["dados"]["id"] = $dados["id"];

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');
        // log admin
        $this->model_log->salvar_log( array(
            'log' => 'Atualizou o link SEO (#'.$dados["id"].') '.$this->db->query('SELECT link FROM '.$this->prefixo_db.'seo_link WHERE id = '.$dados["id"])->row('link'),
            // 'id_cliente' => null,
            // 'json' => null,
        ));

        return $resultado;
    }

    function buscar_config(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'seo_config WHERE id = 1')->row_array();
		return $dados;
    }

    function salvar_config($dados){
        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;
        $dados_enviar = array();

        $dados_enviar['author'] = $dados['author'];
        $dados_enviar['publisher'] = $dados['publisher'];
        $dados_enviar['designer'] = $dados['designer'];
        $dados_enviar['copyright'] = $dados['copyright'];
        $dados_enviar['replyto'] = $dados['replyto'];
        $dados_enviar['generator'] = $dados['generator'];
        $dados_enviar['site_name'] = $dados['site_name'];

        if(!empty($_FILES['imagem_anexo']['name'][0])) {
            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');
            // Define o caminho onde os arquivos serão salvos
            $dir = realpath('arquivos').'/imagens/seo/';
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);

            $dados_enviar['imagem'] =  $nomes[0];
        }

        $args = array(
            '_tabela' => 'seo_config',
            'dados' => $dados_enviar
        );
        $args['where'] = 'id = 1';

        $this->atualizar_dados_tabela($args);

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        // log admin
        $this->model_log->salvar_log( array(
            'log' => 'Atualizou as configurações de SEO',
            // 'id_cliente' => null,
            // 'json' => null,
        ));

        return $resultado;
    }

    function excluir_link($post){
        $dados = $post;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        if(isset($dados['id']) && $dados['id']){
            foreach($dados['id'] as $item){

                
                // log admin
                $this->model_log->salvar_log( array(
                    'log' => 'Excluiu o link (#'.$item.') '.$this->db->query('SELECT link FROM '.$this->prefixo_db.'seo_link WHERE id = '.$item)->row('link'),
                    // 'id_cliente' => null,
                    // 'json' => null,
                ));

           
                $this->remover_dados_tabela(
                        array(
                            '_tabela' => 'seo_link',
                            'where' => 'id = '.$item
                        )
                );
    
            }
        }else{
            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'seo_link',
                    'where' => 'id > 0 '
                )
            );

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Redefiniu os links SEO',
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }
        
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }
}