<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_galeria extends Base_model {
    function buscar_galerias($args = null){

        $where = '';
        $join = '';

        if(isset($args['nome']) && $args['nome']){
            $where .= 'AND  p.nome LIKE "%'.$args['nome'].'%" ';
        }
        
        if($args['situacao']){
            $where .= 'AND  p.situacao = '.$args['situacao'].' ';
        }

        if($args['categoria']){

            $categorias = ''.$args['categoria'];

            $retorno_filhos = $this->db->query('SELECT id FROM '.$this->prefixo_db.'galeria_categorias WHERE situacao = 1 AND categoria_pai = '.$args['categoria'])->result();
            if($retorno_filhos){
                foreach($retorno_filhos as $filho){
                    $categorias .= ','.$filho->id;
                }
            }

            $join .= 'LEFT JOIN '.$this->prefixo_db .'galeria_categoria pc ON pc.id_galeria = p.id';
            $where .= ' AND pc.id_categoria IN ('.$categorias.') ';
        }

        $dados = $this->db->query('SELECT p.* FROM '.$this->prefixo_db.'galeria p '.$join.' WHERE p.id != 0 '.$where.' ORDER BY p.id DESC')->result();

		return $dados;
    }

    function buscar_galeria($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'galeria WHERE id = '.$id)->row_array();

        $dados['imagens'] = $this->db->query('SELECT * FROM '.$this->prefixo_db.'galeria_imagem WHERE id_galeria = '.$id.' ORDER BY ordem ASC')->result_array();

		return $dados;
    }
    function buscar_galeria_categorias_selecionado($id){
        if(!$id) return null;

        $retorno = $this->db->query('SELECT id_categoria FROM '. $this->prefixo_db .'galeria_categoria WHERE id_galeria = '. (int)$id.'')->result_array();

        $dados = array();
        foreach($retorno  as $row){
            $dados[] =  $row['id_categoria'];
        }
        
        return $dados;
        
    }

    function buscar_galeria_categorias(){

        $dados = array();
        $categorias = $this->db->query('SELECT id, nome, categoria_pai FROM '. $this->prefixo_db .'galeria_categorias WHERE situacao = 1  ORDER BY nome ASC')->result();

        if($categorias) {
			foreach($categorias as $cat) {
				$dados[$cat->categoria_pai][] = $cat;
			}
			if(!empty($categorias)) $dados[] = $categorias;
		}

		return $dados;
    }

    function bucarCategoriasPaiFilhaGaleria($id){
        if(!$id) return null;

        $retorno_filhas = $this->db->query('SELECT id_categoria FROM '. $this->prefixo_db .'galeria_categoria WHERE id_galeria = '. (int)$id)->result();

        $dados = array();
        foreach($retorno_filhas as $k => $f){
            $d = $this->db->query('SELECT id, nome, categoria_pai FROM '. $this->prefixo_db .'galeria_categorias WHERE id = '.$f->id_categoria.' AND situacao = 1')->row();

            if($d){
                $dados[$k] = $d;
                $retorno_pai = $this->db->query('SELECT id, nome, categoria_pai FROM '. $this->prefixo_db .'galeria_categorias WHERE id = '.$dados[$k]->categoria_pai)->row();
                $dados[$k]->pai = $retorno_pai;
            }

        }
        return  $dados;

    }

    function buscar_galeria_imagens($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '. $this->prefixo_db .'galeria_imagem WHERE id_galeria = '. (int)$id.' ORDER BY ordem ASC')->result_array();

		return $dados;
    }

    function salvar_galeria($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');


        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;

        if(!$dados['alias']){
            $dados['alias'] = $this->getAlias($dados['nome']);
        }else{
            $dados['alias'] = $this->getAlias($dados['alias']);
        }

        if($dados['alias']){
            $retorno_alias = $this->db->query('SELECT * FROM '. $this->prefixo_db .'galeria WHERE alias= "'.$dados['alias'].'" AND id != '. (int)$dados["id"].' ')->row_array();
            $i_alias = 1;
            $alias = $dados['alias'];
            while($retorno_alias){
                $i_alias++;

                $alias = $dados['alias'].'-'.$i_alias;
                
                $retorno_alias = $this->db->query('SELECT * FROM '. $this->prefixo_db .'galeria WHERE alias= "'.$alias.'" AND id != '. (int)$dados["id"].' ')->row_array();
            }

            $dados['alias'] = $alias;
        }
        
       
        $categoria_yes = false;
        

        if(isset($dados['categorias_yes'])){
            $categoria_yes = true;
            $categorias =$dados['categorias'];

            unset($dados['categorias']);
            unset($dados['categorias_yes']);

        }

        $ids_imagens =  $ids_imagens_array = null;
        $dados_info_galeria = null;

        if(isset($dados['ids_imagem']) && $dados['ids_imagem']){
            $ids_imagens = implode(',',$dados['ids_imagem']);
            $ids_imagens_array = $dados['ids_imagem'];

            $dados_info_galeria['txt1_imagem'] = $dados['txt1_imagem'];
            $dados_info_galeria['txt2_imagem'] = $dados['txt2_imagem'];
            // $dados_info_galeria['ordem_imagem'] = $dados['ordem_imagem'];

            
        }

        unset($dados['txt1_imagem']);
        unset($dados['txt2_imagem']);
        // unset($dados['ordem_imagem']);
        unset($dados['ids_imagem']);
        
        $args = array(
            '_tabela' => 'galeria',
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
                'log' => 'Atualizou a galeria (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'galeria WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou a galeria (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'galeria WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        if($categoria_yes){
            $this->salvarCategoria($resultado["dados"]["id"],$categorias);   
        }

        
        
        $this->remover_dados_tabela(array(
                '_tabela' => 'galeria_imagem',
                'where' => ($ids_imagens?' id NOT IN ('. $ids_imagens.') AND ':'') .' id_galeria = '.$resultado["dados"]["id"]
        ));

        if($ids_imagens_array){

            foreach($ids_imagens_array as $id_imagem){ 
                $this->atualizar_dados_tabela(array(
                    '_tabela' => 'galeria_imagem',
                    'dados' => array(
                        'txt1' => $dados_info_galeria['txt1_imagem'][$id_imagem],
                        'txt2' => $dados_info_galeria['txt2_imagem'][$id_imagem],
                        // 'ordem' => $dados_info_galeria['ordem_imagem'][$id_imagem],
                    ),
                    'where' => 'id = '.$id_imagem
                ));
            }

        }

        if(!empty($_FILES['imagem_anexo']['name'][0])) {
            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');
            // Define o caminho onde os arquivos serão salvos
            $dir = realpath('arquivos').'/imagens/galeria/';
            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);
            
            if($nomes){
                foreach($nomes as $imagem) {
                    $this->inserir_dados_tabela(array(
                        '_tabela' => 'galeria_imagem',
                        'dados' => array(
                            'id_galeria' =>  $resultado["dados"]["id"],
                            'imagem' =>$imagem,
                            'ordem' =>0,
                            'cadastro' => date('Y-m-d H:i:s'),
                        )
                    ));
                }
            }

        }
        
        if(!empty($_FILES['nova_imagem']['name'])) {

            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');
            $dir = realpath('arquivos').'/imagens/galeria/';
            foreach($_FILES['nova_imagem']['name'] as $id_imagem => $row){
                if(!$row) continue;

                $file = array(
                    'name' => array( $_FILES['nova_imagem']['name'][$id_imagem]),
                    'type' =>  array( $_FILES['nova_imagem']['type'][$id_imagem]),
                    'tmp_name' =>  array( $_FILES['nova_imagem']['tmp_name'][$id_imagem]),
                    'error' =>  array( $_FILES['nova_imagem']['error'][$id_imagem]),
                    'size' =>  array( $_FILES['nova_imagem']['size'][$id_imagem]),
                );
               
                $nomes = array();
                $this->upload_arquivos->enviar_arquivos_file('', $file, $dir, $nomes);

                if($nomes){
                    $this->atualizar_dados_tabela(array(
                        '_tabela' => 'galeria_imagem',
                        'dados' => array(
                            'imagem' => $nomes[0],
                        ),
                        'where' => 'id = '.$id_imagem
                    ));
                }


            }

        }


        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function salvarCategoria($id_galeria, $categorias){
		// Salva as novas categorias

        $this->remover_dados_tabela(array(
            '_tabela' => 'galeria_categoria',
            'where' => 'id_galeria = '. $id_galeria
        ));

		if($categorias) {	
			foreach($categorias as $categoria) {

                $this->inserir_dados_tabela(array(
                    '_tabela' => 'galeria_categoria',
                    'dados' => array(
                        'id_galeria' => $id_galeria,
                        'id_categoria' => $categoria,
                        'ordem' => 0
                    )
                ));
			}
		}

    }
    
    function excluir_galerias($post){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $dados = $post;

        foreach($dados['id'] as $item){

            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu a galeria (#'.$item.') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'galeria WHERE id = '.$item)->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
           
            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'galeria',
                        'where' => 'id = '.$item
                    )
            );

            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'galeria_categoria',
                    'where' => 'id_galeria = '.$item
                )
            );

            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'galeria_imagem',
                    'where' => 'id_galeria = '.$item
                )
            );

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }

    function getAlias($str){
        
        $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );
        $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-' );
        $string_format = str_replace($what, $by, $str);

        return strtolower($string_format);
    }

    function buscar_categorias($args = null){

        $dados = $this->db->query('SELECT id, nome, imagem, categoria_pai, ordem,cadastro,situacao FROM '.$this->prefixo_db.'galeria_categorias ORDER BY (categoria_pai = 0), ordem ASC, nome ASC')->result();

		return $dados;
    }

    function buscar_categoria($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'galeria_categorias WHERE id = '.$id)->row_array();

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
            $dir = realpath('arquivos').'/imagens/galeria/categoria';
            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);

            $dados['imagem'] =  $nomes[0];
        
        }
        
       
        $args = array(
            '_tabela' => 'galeria_categorias',
            'dados' => $dados
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];

            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou a categoria de galeria (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'galeria_categorias WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);
            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou a categoria de galeria (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'galeria_categorias WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

}