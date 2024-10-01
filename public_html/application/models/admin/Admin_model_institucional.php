<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_institucional extends Base_model {
    function buscar_produtos($args = null){

        $where = '';
        $join = '';

        if(isset($args['nome']) && $args['nome']){
            $where .= 'AND  p.nome LIKE "%'.$args['nome'].'%" ';
        }
        
        if(isset($args['situacao']) && $args['situacao'] != ''){
            $where .= 'AND  p.situacao = '.$args['situacao'].' ';
        }

        if($args['categoria']){

            // $join = 'LEFT JOIN '.$this->prefixo_db.'institucional_produto_categoria pc ON (pc.id_produto = p.id) ';
            // $where .= 'AND  pc.id_categoria = '.$args['categoria'].' ';

            // $ordem =  ' ORDER BY pc.ordem';
            $categorias = ''.$args['categoria'];

            $retorno_filhos = $this->db->query('SELECT id FROM '.$this->prefixo_db.'institucional_categoria WHERE situacao = 1 AND categoria_pai = '.$args['categoria'])->result();
            if($retorno_filhos){
                foreach($retorno_filhos as $filho){
                    $categorias .= ','.$filho->id;
                }
            }

            $join .= 'LEFT JOIN '.$this->prefixo_db .'institucional_produto_categoria pc ON pc.id_produto = p.id';
            $where .= ' AND pc.id_categoria IN ('.$categorias.') ';
        }

        $dados = $this->db->query('SELECT p.* FROM '.$this->prefixo_db.'institucional_produto p '.$join.' WHERE p.id != 0 '.$where.' ORDER BY p.id DESC')->result();

		return $dados;
    }

    function buscar_produto($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_produto WHERE id = '.$id)->row_array();

        $dados['imagens'] = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_produto_imagem WHERE id_produto = '.$id.' ORDER BY ordem ASC')->result_array();

		return $dados;
    }

    function buscar_copywriters(){ 

        $dados = $this->db->query('SELECT id, titulo FROM '.$this->prefixo_db.'copywriter WHERE id != 0 AND situacao = 1 ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_produto_categorias_selecionado($id){
        if(!$id) return null;

        $retorno = $this->db->query('SELECT id_categoria FROM '. $this->prefixo_db .'institucional_produto_categoria WHERE id_produto = '. (int)$id.'')->result_array();

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

    function bucarCategoriasPaiFilhaProduto($id){
        if(!$id) return null;

        $retorno_filhas = $this->db->query('SELECT id_categoria FROM '. $this->prefixo_db .'institucional_produto_categoria WHERE id_produto = '. (int)$id)->result();

        $dados = array();
        foreach($retorno_filhas as $k => $f){
            $d = $this->db->query('SELECT id, nome, categoria_pai FROM '. $this->prefixo_db .'institucional_categoria WHERE id = '.$f->id_categoria.' AND situacao = 1')->row();

            if($d){
                $dados[$k] = $d;
                $retorno_pai = $this->db->query('SELECT id, nome, categoria_pai FROM '. $this->prefixo_db .'institucional_categoria WHERE id = '.$dados[$k]->categoria_pai)->row();
                $dados[$k]->pai = $retorno_pai;
            }

        }
        return  $dados;

    }

    function buscar_produto_imagens($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '. $this->prefixo_db .'institucional_imagem WHERE id_produto = '. (int)$id.' ORDER BY ordem ASC')->result_array();

		return $dados;
    }

    function salvar_produto($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;

        if(!empty($dados['copywriters'])){
            $dados['copywriters'] = json_encode($dados['copywriters']);
        }else{
            $dados['copywriters'] = json_encode(array());
        } 
        
        $resultado["dados"] = $dados;

        if(!$dados['alias']){
            $dados['alias'] = $this->getAlias($dados['nome']);
        }else{
            $dados['alias'] = $this->getAlias($dados['alias']);
        }

        if($dados['alias']){
            $retorno_alias = $this->db->query('SELECT * FROM '. $this->prefixo_db .'institucional_produto WHERE alias= "'.$dados['alias'].'" AND id != '. (int)$dados["id"].' ')->row_array();
            $i_alias = 1;
            $alias = $dados['alias'];
            while($retorno_alias){
                $i_alias++;

                $alias = $dados['alias'].'-'.$i_alias;
                
                $retorno_alias = $this->db->query('SELECT * FROM '. $this->prefixo_db .'institucional_produto WHERE alias= "'.$alias.'" AND id != '. (int)$dados["id"].' ')->row_array();
            }

            $dados['alias'] = $alias;
        }

        //icone unico
        if(!empty($_FILES['icone']['name'][0])) {
            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');

            // Define o caminho onde os arquivos serão salvos
            $dir = realpath('arquivos').'/imagens/produto/icone/';
            if(!file_exists($dir)) mkdir($dir, 0755);
            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'icone', $dir, $nomes);

            $dados['icone'] =  $nomes[0];
        }

        //banner unico
        if(!empty($_FILES['banner']['name'][0])) {
            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');

            // Define o caminho onde os arquivos serão salvos
            $dir = realpath('arquivos').'/imagens/produto/banner/';
            if(!file_exists($dir)) mkdir($dir, 0755);
            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'banner', $dir, $nomes);

            $dados['banner'] =  $nomes[0];
        }

        $dados['preco_de'] = (float) $this->remover_caracteres_moeda($dados['preco_de']);
        $dados['preco'] = (float) $this->remover_caracteres_moeda($dados['preco']);
        
       
        $categoria_yes = false;

        if(!empty($dados['categorias_yes'])){
            $categoria_yes = true;
            $categorias =$dados['categorias'];
        }

        unset($dados['categorias']);
        unset($dados['categorias_yes']);

        $ids_imagens = null;
        if(isset($dados['ids_imagem']) && $dados['ids_imagem']){
            $ids_imagens = implode(',',$dados['ids_imagem']);
        }
        unset($dados['ids_imagem']);
        
        $args = array(
            '_tabela' => 'institucional_produto',
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
                'log' => 'Atualizou o produto (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_produto WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
                        
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o produto (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_produto WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        if($categoria_yes){
            $this->salvarCategoria($resultado["dados"]["id"],$categorias);   
        }

        
        
        $this->remover_dados_tabela(array(
                '_tabela' => 'institucional_produto_imagem',
                'where' => ($ids_imagens?' id NOT IN ('. $ids_imagens.') AND ':'') .' id_produto = '.$resultado["dados"]["id"]
        ));

        if(!empty($_FILES['imagem_anexo']['name'][0])) {
            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');

            // Define o caminho onde os arquivos serão salvos
            $dir = realpath('arquivos').'/imagens/produto/';
            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);

            $imagem =  $nomes[0];
            $this->inserir_dados_tabela(array(
                '_tabela' => 'institucional_produto_imagem',
                'dados' => array(
                    'id_produto' =>  $resultado["dados"]["id"],
                    'imagem' =>$imagem,
                    'ordem' =>0,
                    'cadastro' => date('Y-m-d H:s:i'),
                )
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function salvarCategoria($id_produto, $categorias){
		// Salva as novas categorias

        $this->remover_dados_tabela(array(
            '_tabela' => 'institucional_produto_categoria',
            'where' => 'id_produto = '. $id_produto
        ));

		if($categorias) {	
			foreach($categorias as $categoria) {

                $this->inserir_dados_tabela(array(
                    '_tabela' => 'institucional_produto_categoria',
                    'dados' => array(
                        'id_produto' => $id_produto,
                        'id_categoria' => $categoria,
                        'ordem' => 0
                    )
                ));
			}
		}

    }
    
    function excluir_produtos($post){
        $dados = $post;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu o produto (#'.$item.') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_produto WHERE id = '.$item)->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
           
            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'institucional_produto',
                        'where' => 'id = '.$item
                    )
            );

            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'institucional_produto_categoria',
                    'where' => 'id_produto = '.$item
                )
            );

            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'institucional_produto_imagem',
                    'where' => 'id_produto = '.$item
                )
            );

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }

    function solicitacao_excluir($post){
        $dados = $post;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu a solicitação de institucional (#'.$item.') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_solicitacao WHERE id = '.$item)->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
           
            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'institucional_solicitacao',
                    'where' => 'id = '.$item
                )
            );  
        }
        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }

    function categoria_excluir($post){
        $dados = $post;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu a categoria de institucional (#'.$item.') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_categoria WHERE id = '.$item)->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
           
            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'institucional_categoria',
                    'where' => 'id = '.$item
                )
            ); 
            
            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'institucional_produto_categoria',
                    'where' => 'id_categoria = '.$item
                )
            );

        }
        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }

    function getAlias($str){
        
        $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º','"',"'");
        $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-');
        $string_format = str_replace($what, $by, $str);

        return strtolower($string_format);
    }

    function buscar_solicitacoes($args = null){

        $where = '';

        if(isset($args['nome']) && $args['nome']){
            $where .= 'AND nome LIKE "%'.$args['nome'].'%" ';
        }
        
        if(isset($args['situacao']) && $args['situacao'] != ''){
            $where .= 'AND situacao = '.$args['situacao'].' ';
        }

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_solicitacao WHERE id != 0 '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_solicitacao($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_solicitacao WHERE id = '.$id)->row_array();

		return $dados;
    }

    function salvar_solicitacao($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;
        
        $dados_cad['situacao'] = $dados['situacao'];
        $dados_cad['observacoes_cliente'] = $dados['observacoes_cliente'];

        $args = array(
            '_tabela' => 'institucional_solicitacao',
            'dados' => $dados_cad
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou a solicitação (#'.$resultado["dados"]["id"].') ',
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function buscar_categorias($args = null){

        $dados = $this->db->query('SELECT id, nome, imagem, categoria_pai, ordem,cadastro,situacao FROM '.$this->prefixo_db.'institucional_categoria ORDER BY (categoria_pai = 0), ordem ASC, nome ASC')->result();

		return $dados;
    }

    function buscar_categoria($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_categoria WHERE id = '.$id)->row_array();

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
            $dir = realpath('arquivos').'/imagens/institucional/categoria';
            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);

            $dados['imagem'] =  $nomes[0];
        
        }
        
       
        $args = array(
            '_tabela' => 'institucional_categoria',
            'dados' => $dados
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou a categoria de produto (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_categoria WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou a categoria de produto (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_categoria WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function buscar_profissionais($args = null){

        $where = '';

        if(isset($args['nome']) && $args['nome']){
            $where .= 'AND nome LIKE "%'.$args['nome'].'%" ';
        }
        
        if(isset($args['situacao']) && $args['situacao'] != ''){
            $where .= 'AND situacao = '.$args['situacao'].' ';
        }

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_profissionais WHERE id != 0 '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_profissional($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_profissionais WHERE id = '.$id)->row_array();

		return $dados;
    }

    function salvar_profissional($dados){
        
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;

        

        if(!empty($_FILES['imagem_anexo']['name'][0])) {
            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');

            // Define o caminho onde os arquivos serão salvos
            $dir = realpath('arquivos').'/imagens/profissional/';
            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);

            $dados['imagem'] =  $nomes[0];
        
        }
        
        $args = array(
            '_tabela' => 'institucional_profissionais',
            'dados' => $dados
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou o profissional (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_profissionais WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);
            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o profissional (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_profissionais WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function excluir_profissional($post){
        $dados = $post;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){

            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu o profissional (#'.$item.') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_profissionais WHERE id = '.$item)->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
           
            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'institucional_profissionais',
                        'where' => 'id = '.$item
                    )
            );

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }

    function buscar_depoimentos($args = null){

        $where = '';
        $join = '';

        if(isset($args['nome']) && $args['nome']){
            $where .= 'AND  p.nome LIKE "%'.$args['nome'].'%" ';
        }
        
        if(isset($args['situacao']) && $args['situacao'] != ''){
            $where .= 'AND  p.situacao = '.$args['situacao'].' ';
        }

        $dados = $this->db->query('SELECT p.* FROM '.$this->prefixo_db.'institucional_depoimentos p '.$join.' WHERE p.id != 0 '.$where.' ORDER BY p.id DESC')->result();

		return $dados;
    }

    function buscar_depoimento($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_depoimentos WHERE id = '.$id)->row_array();

		return $dados;
    }

    function salvar_depoimento($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;
        
        if(!empty($_FILES['imagem_anexo']['name'][0])) {
            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');

            // Define o caminho onde os arquivos serão salvos
            $dir = realpath('arquivos').'/imagens/depoimento/';
            if(!file_exists($dir)) mkdir($dir, 0777, true);
            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);

            $dados['imagem'] =  $nomes[0];
        
        }

        $args = array(
            '_tabela' => 'institucional_depoimentos',
            'dados' => $dados
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou o depoimento (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_depoimentos WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o depoimento (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_depoimentos WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function excluir_depoimentos($post){
        $dados = $post;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu o depoimento (#'.$item.') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_depoimentos WHERE id = '.$item)->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));

            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'institucional_depoimentos',
                        'where' => 'id = '.$item
                    )
            );
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    } 

    function buscar_bignumbers($args = null){

        $where = '';
        $join = '';

        if(isset($args['nome']) && $args['nome']){
            $where .= 'AND  p.nome LIKE "%'.$args['nome'].'%" ';
        }
        
        if(isset($args['situacao']) && $args['situacao'] != ''){
            $where .= 'AND  p.situacao = '.$args['situacao'].' ';
        }

        $dados = $this->db->query('SELECT p.* FROM '.$this->prefixo_db.'institucional_bignumbers p '.$join.' WHERE p.id != 0 '.$where.' ORDER BY p.id DESC')->result();

		return $dados;
    }

    function buscar_bignumber($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_bignumbers WHERE id = '.$id)->row_array();

		return $dados;
    }

    function salvar_bignumber($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;

        $args = array(
            '_tabela' => 'institucional_bignumbers',
            'dados' => $dados
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou o big number (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_bignumbers WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o big number (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_bignumbers WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function excluir_bignumbers($post){
        $dados = $post;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu o big number (#'.$item.') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_bignumbers WHERE id = '.$item)->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));

            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'institucional_bignumbers',
                        'where' => 'id = '.$item
                    )
            );
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }
}