<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_blog extends Base_model {

    function buscar_artigos($args = null){

        $where = '';

        if(isset($args['titulo'])){
            $where .= 'AND titulo LIKE "%'.$args['titulo'].'%" ';
        }
        
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'blog WHERE id != 0 '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_artigo($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'blog WHERE id = '.$id)->row_array();

		return $dados;
    }

    function salvar_artigo($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;

        if(!$dados['alias']){
            $dados['alias'] = $this->getAlias($dados['titulo']);
        }else{
            $dados['alias'] = $this->getAlias($dados['alias']);
        }

        if(!empty($_FILES['imagem_anexo']['name'][0])) {
            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');

            // Define o caminho onde os arquivos serão salvos
            $dir = realpath('arquivos').'/imagens/blog/';
            if(!(file_exists($dir))) mkdir($dir, 0777, true); 

            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);

            $dados['imagem'] =  $nomes[0];
        
        }

        if(!$dados['ordem'] || $dados['ordem'] < 0){
            $dados['ordem'] = 0;
        }
       
        $categoria_yes = false;  
        if(!empty($dados['categorias_yes'])){
            $categoria_yes = true;
            $categorias = isset($dados['categorias'])?$dados['categorias']:'';
        }
        unset($dados['categorias']);
        unset($dados['categorias_yes']);

        $categoria_yes_produto = false;  
        if(isset($dados['tipo_vinculo_produto']) && $dados['tipo_vinculo_produto'] == 1 && !empty($dados['categorias_yes_produto'])){
            $categoria_yes_produto = true;
            $categorias_produto = isset($dados['categorias_produto'])?$dados['categorias_produto']:'';
        }else if(isset($dados['tipo_vinculo_produto']) && $dados['tipo_vinculo_produto'] == 0){
            $categoria_yes_produto = true;
            $categorias_produto = '';
        }
        unset($dados['categorias_produto']);
        unset($dados['categorias_yes_produto']);

        $args = array(
            '_tabela' => 'blog',
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
                'log' => 'Atualizou o artigo (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT titulo FROM '.$this->prefixo_db.'blog WHERE id = '.$resultado["dados"]["id"])->row('titulo'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o artigo (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT titulo FROM '.$this->prefixo_db.'blog WHERE id = '.$resultado["dados"]["id"])->row('titulo'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        if($categoria_yes){
            $this->salvarCategoria($resultado["dados"]["id"],$categorias);   
        }

        if($categoria_yes_produto){
            $this->salvarCategoriaProduto($resultado["dados"]["id"],$categorias_produto);   
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function buscar_produto_categorias_selecionado($id){
        if(!$id) return null;

        $retorno = $this->db->query('SELECT id_categoria FROM '. $this->prefixo_db .'blog_produto_categoria WHERE id_artigo = '. (int)$id.'')->result_array();

        $dados = array();
        foreach($retorno  as $row){
            $dados[] =  $row['id_categoria'];
        }
        
        return $dados;
        
    }

    function buscar_produto_categorias(){

        $dados = array();
        $categorias = $this->db->query('SELECT id, nome, categoria_pai FROM '. $this->prefixo_db .'institucional_categoria WHERE situacao = 1  ORDER BY nome ASC')->result();

        if($categorias) {
			foreach($categorias as $cat) {
				$dados[$cat->categoria_pai][] = $cat;
			}
			// if(!empty($categorias)) $dados[] = $categorias;
		}

		return $dados;
    }

    function buscar_artigo_categorias_selecionado($id){
        if(!$id) return null;

        $retorno = $this->db->query('SELECT id_categoria FROM '. $this->prefixo_db .'blog_artigo_categoria WHERE id_artigo = '. (int)$id.'')->result_array();

        $dados = array();
        foreach($retorno  as $row){
            $dados[] =  $row['id_categoria'];
        }
        
        return $dados;
        
    }

    function buscar_artigo_categorias(){

        $dados = array();
        $categorias = $this->db->query('SELECT id, nome, categoria_pai FROM '. $this->prefixo_db .'blog_categoria WHERE situacao = 1  ORDER BY nome ASC')->result();

        if($categorias) {
			foreach($categorias as $cat) {
				$dados[$cat->categoria_pai][] = $cat;
			}
			// if(!empty($categorias)) $dados[] = $categorias;
		}

		return $dados;
    }

    function salvarCategoria($id_artigo, $categorias){
		// Salva as novas categorias

        $this->remover_dados_tabela(array(
            '_tabela' => 'blog_artigo_categoria',
            'where' => 'id_artigo = '. $id_artigo
        ));

		if($categorias) {	
			foreach($categorias as $categoria) {

                $this->inserir_dados_tabela(array(
                    '_tabela' => 'blog_artigo_categoria',
                    'dados' => array(
                        'id_artigo' => $id_artigo,
                        'id_categoria' => $categoria,
                        'ordem' => 0
                    )
                ));
			}
		}

    }

    function salvarCategoriaProduto($id_artigo, $categorias){
		// Salva as novas categorias

        $this->remover_dados_tabela(array(
            '_tabela' => 'blog_produto_categoria',
            'where' => 'id_artigo = '. $id_artigo
        ));

		if($categorias) {	
			foreach($categorias as $categoria) {

                $this->inserir_dados_tabela(array(
                    '_tabela' => 'blog_produto_categoria',
                    'dados' => array(
                        'id_artigo' => $id_artigo,
                        'id_categoria' => $categoria,
                        'ordem' => 0
                    )
                ));
			}
		}

    }

    function getAlias($str){
        
        $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º','.' );
        $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','' );
        $string_format = str_replace($what, $by, $str);

        return $string_format;
    }

    function excluir_artigo($post){
        $dados = $post;
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){

            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu o artigo (#'.$item.') '.$this->db->query('SELECT titulo FROM '.$this->prefixo_db.'blog WHERE id = '.$item)->row('titulo'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
           
            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'blog',
                        'where' => 'id = '.$item
                    )
            );

            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'blog_artigo_categoria',
                    'where' => 'id_artigo = '.$item
                )
            );

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }

    function buscar_categorias($args = null){

        $dados = $this->db->query('SELECT id, nome, imagem, categoria_pai, ordem,cadastro,situacao FROM '.$this->prefixo_db.'blog_categoria ORDER BY (categoria_pai = 0), ordem ASC, nome ASC')->result();

		return $dados;
    }

    function buscar_categoria($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'blog_categoria WHERE id = '.$id)->row_array();

		return $dados;
    }

    function salvar_categoria($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;

        if(!$dados['alias']){
            $dados['alias'] = $this->getAlias($dados['nome']);
        }else{
            $dados['alias'] = $this->getAlias($dados['alias']);
        }

        if(!empty($_FILES['imagem_anexo']['name'][0])) {
            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');

            // Define o caminho onde os arquivos serão salvos
            $dir = realpath('arquivos').'/imagens/blog/categoria';
            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);

            $dados['imagem'] =  $nomes[0];
        
        }
        
       
        $args = array(
            '_tabela' => 'blog_categoria',
            'dados' => $dados
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou a categoria de blog (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'blog_categoria WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou a categoria de blog (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'blog_categoria WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function buscar_configuracao(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'blog_config WHERE id = 1')->row_array();

		return $dados;
    }

    function salvar_configuracao($dados){

        $resultado["dados"] = $dados; 

        $args = array(
            '_tabela' => 'blog_config',
            'dados' => $dados,
        );
        $args['where'] = 'id = 1';
        
        $this->atualizar_dados_tabela($args);

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        // log admin
        $this->model_log->salvar_log( array(
            'log' => 'Atualizou as configuração de Blog',
            // 'id_cliente' => null,
            // 'json' => null,
        ));

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }  

}