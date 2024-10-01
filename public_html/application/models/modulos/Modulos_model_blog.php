<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Modulos_model_blog extends Base_model {

    public function getUltimosArtigos($limit = null){

        $artigos = $this->db->query('SELECT * FROM '.$this->prefixo_db.'blog WHERE situacao = 1 ORDER BY cadastro DESC '.($limit?'LIMIT '.$limit:''))->result();
        return $artigos;
    }

    public function getArtigosOrdem($limit = null){

        $artigos = $this->db->query('SELECT * FROM '.$this->prefixo_db.'blog WHERE situacao = 1 ORDER BY ordem ASC '.($limit?'LIMIT '.$limit:''))->result();
        return $artigos;
    }

    public function getProdutosRelacionados($id, $tipo = 0, $limit = null){
        $select = 'p.*, i.imagem as foto';
		$from = $this->prefixo_db .'institucional_produto p';
		$from .= ' LEFT JOIN '.$this->prefixo_db .'institucional_produto_imagem i ON p.id = i.id_produto';
        $where = 'p.situacao = 1';

        if($tipo == 1){
            $categorias = $this->db->query('SELECT id_categoria FROM '. $this->prefixo_db .'blog_produto_categoria WHERE id_artigo = '.(int)$id)->result();    

            if($categorias){
                $ids_c = implode(',',array_map(function($c) {return $c->id_categoria;}, $categorias));

                $cate_prod  = $this->db->query('SELECT id_produto FROM '. $this->prefixo_db .'institucional_produto_categoria WHERE id_categoria IN('.$ids_c.')')->result(); 

                if($cate_prod){
                    $ids_prod = implode(',',array_map(function($c) {return $c->id_produto;}, $cate_prod));
                    $where .= ' AND p.id IN('.$ids_prod.')';
                }
            }
        } 
        
        $ordem =  ' ORDER BY p.nome';
        
        $dados = $this->db->query('SELECT '.$select.' FROM '. $from .' WHERE '. $where.' '.$ordem.' '.($limit?'LIMIT '.$limit:''))->result();

        return $dados;
    }

    public function buscar_config($select = ''){

        if(!$select){
            $select = '*';
        }

        $dados = $this->db->query('SELECT '.$select.' FROM '.$this->prefixo_db.'blog_config WHERE id = 1')->row_array();

        return $dados;
    }
}