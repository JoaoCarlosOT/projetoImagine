<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Site_model_paginas extends Base_model {

    public function getUltimosArtigos($limit = null,$pag = null, $categoria = false){
        $from = '';
        $where = '';

        // Categoria
		if($categoria) {
            // $ordem =  ' ORDER BY pc.ordem';
            $categorias = ''.$categoria;

            $retorno_filhos = $this->db->query('SELECT id FROM '.$this->prefixo_db.'blog_categoria WHERE situacao = 1 AND categoria_pai = '.$categoria)->result();
            if($retorno_filhos){
                foreach($retorno_filhos as $filho){
                    $categorias .= ','.$filho->id;
                }
            }

			// SQL
            $from .= ' JOIN '.$this->prefixo_db .'blog_artigo_categoria ac ON ac.id_artigo = b.id';
            $where .= ' AND ac.id_categoria IN ('.$categorias.') ';
		}

        if($pag ==null){
            $artigos = $this->db->query('SELECT * FROM '.$this->prefixo_db.'blog b '.$from.' WHERE b.situacao = 1 '.$where.' ORDER BY b.cadastro DESC '.($limit?'LIMIT '.$limit:''))->result();
            return $artigos;
        }else{

            $limit_sql = ' LIMIT '.$pag['start'].','.$pag['limit'];

            $artigos = $this->db->query('SELECT b.* FROM '.$this->prefixo_db.'blog b '.$from.' WHERE b.situacao = 1 '.$where.' ORDER BY b.cadastro DESC '.$limit_sql)->result();

            $artigos_count = $this->db->query('SELECT count(b.id) as total FROM '.$this->prefixo_db.'blog b '.$from.' WHERE b.situacao = 1 '.$where.' ORDER BY b.cadastro DESC ')->row('total');

            $retorno['dados'] = $artigos;
            $retorno['dados_total'] = $artigos_count;
            
            return $retorno;
        }
        
    }

    public function getArtigo($alias){
        if(!$alias){
            return null;
        }

        $artigo = $this->db->query('SELECT * FROM '.$this->prefixo_db.'blog WHERE situacao = 1 AND alias = "'.$alias.'"')->row();

        return $artigo;
    }

    public function getIdCategoriaProduto($id_produto){
        $categoria_produto = $this->db->query('SELECT id_categoria FROM '.$this->prefixo_db.'institucional_produto_categoria WHERE id_produto = '.$id_produto)->row();

        return $categoria_produto->id_categoria;
    }

    public function getProdutosRelacionados($id_produto){

        $categoria_produto = $this->db->query('SELECT id_categoria FROM '.$this->prefixo_db.'institucional_produto_categoria WHERE id_produto = '.$id_produto)->row();

        if(!$categoria_produto) return null;

        $select = 'p.*, i.imagem as foto';
		$from = $this->prefixo_db .'institucional_produto p';
		$from .= ' LEFT JOIN '.$this->prefixo_db .'institucional_produto_imagem i ON p.id = i.id_produto';
        $where = 'p.situacao = 1';

        $from .= ' JOIN '.$this->prefixo_db .'institucional_produto_categoria pc ON pc.id_produto = p.id';
        $where .= ' AND pc.id_categoria IN ('.$categoria_produto->id_categoria.') ';

        $where .= ' AND p.id != '.$id_produto;

        $ordem =  ' ORDER BY p.id';

        $dados = $this->db->query('SELECT '.$select.' FROM '. $from .' WHERE '. $where.' '.$ordem)->result();
        return $dados;

    }

    public function getMenuInstitucionalCategoria(){

        $categorias = $this->db->query('SELECT c.id, c.nome, c.alias, c.imagem FROM '.$this->prefixo_db.'institucional_categoria c WHERE c.situacao = 1 AND c.exibir_menu = 1 ORDER BY c.ordem ASC, c.id ASC ')->result();

        if($categorias){
            foreach($categorias as $key => $categoria){ 
                $categorias[$key]->produtos = $this->db->query('SELECT p.nome, p.alias, p.icone FROM '.$this->prefixo_db.'institucional_produto p 
                JOIN '.$this->prefixo_db.'institucional_produto_categoria pc ON pc.id_produto = p.id
                WHERE p.situacao = 1 AND pc.id_categoria = '.(int)$categoria->id.' ORDER BY p.id ASC LIMIT 5')->result();           
            }
        } 
        
        return $categorias;
    }

    public function getProdutosMenu($categoria = null,$limit = null){

        $select = 'p.nome,p.alias';
		$from = $this->prefixo_db .'institucional_produto p';
        $where = 'p.situacao = 1';

        // Categoria
		if($categoria) {
            // $ordem =  ' ORDER BY pc.ordem';
            $categorias = ''.$categoria;

            $retorno_filhos = $this->db->query('SELECT id FROM '.$this->prefixo_db.'institucional_categoria WHERE situacao = 1 AND categoria_pai = '.$categoria)->result();
            if($retorno_filhos){
                foreach($retorno_filhos as $filho){
                    $categorias .= ','.$filho->id;
                }
            }
			// SQL
            $from .= ' JOIN '.$this->prefixo_db .'institucional_produto_categoria pc ON pc.id_produto = p.id';
            $where .= ' AND pc.id_categoria IN ('.$categorias.') ';
        } 
        
        $ordem =  ' ORDER BY p.nome';
        
        $dados = $this->db->query('SELECT '.$select.' FROM '. $from .' WHERE '. $where.' '.$ordem.''.($limit?'LIMIT '.$limit:''))->result();
        return $dados;
    }

    function buscar_artigo_categorias(){

        $dados = array();
        $categorias = $this->db->query('SELECT id, nome, categoria_pai FROM '. $this->prefixo_db .'blog_categoria WHERE situacao = 1  ORDER BY nome ASC')->result();

        /*if($categorias) {
			foreach($categorias as $cat) {
				$dados[$cat->categoria_pai][] = $cat;
			}
			// if(!empty($categorias)) $dados[] = $categorias;
		}*/

		return $categorias;
    }

    public function getProdutos($categoria = null,$limit = null, $args = false){

        $select = 'p.*, i.imagem as foto';
		$from = $this->prefixo_db .'institucional_produto p';
		$from .= ' LEFT JOIN '.$this->prefixo_db .'institucional_produto_imagem i ON p.id = i.id_produto';
        $where = 'p.situacao = 1';

        if(!empty($args['destaque'])){
            $where .= ' AND p.destaque = 1 ';
        }

        if(!empty($args['ativarVenda'])){
            $where .= ' AND p.ativarVenda = 1 ';
        }else if(isset($args['ativarVenda']) && $args['ativarVenda'] === false){
            $where .= ' AND p.ativarVenda = 0 ';
        }

        // Categoria
		if($categoria) {
            // $ordem =  ' ORDER BY pc.ordem';
            $categorias = ''.$categoria;

            $retorno_filhos = $this->db->query('SELECT id FROM '.$this->prefixo_db.'institucional_categoria WHERE situacao = 1 AND categoria_pai = '.$categoria)->result();
            if($retorno_filhos){
                foreach($retorno_filhos as $filho){
                    $categorias .= ','.$filho->id;
                }
            }
			// SQL
            $from .= ' JOIN '.$this->prefixo_db .'institucional_produto_categoria pc ON pc.id_produto = p.id';
            $where .= ' AND pc.id_categoria IN ('.$categorias.') ';
		}

        if(isset($_POST)){
            if(isset($_POST['busca']) && $_POST['busca']){
                $where .= ' AND p.nome LIKE "%'.$_POST['busca'].'%" ';
            }
        }


        $ordem =  ' ORDER BY p.nome';
        
        $dados = $this->db->query('SELECT '.$select.' FROM '. $from .' WHERE '. $where.' GROUP BY p.id '.$ordem.' '.($limit?'LIMIT '.$limit:''))->result();
        return $dados;
    }

    

    public function getProdutosDestaque($categoria = null,$limit = null){

        $select = 'p.*, i.imagem as foto';
		$from = $this->prefixo_db .'institucional_produto p';
		$from .= ' LEFT JOIN '.$this->prefixo_db .'institucional_produto_imagem i ON p.id = i.id_produto';
        $where = 'p.situacao = 1 AND p.destaque =1 ';

        // Categoria
		if($categoria) {
            // $ordem =  ' ORDER BY pc.ordem';
            $categorias = ''.$categoria;

            $retorno_filhos = $this->db->query('SELECT id FROM '.$this->prefixo_db.'institucional_categoria WHERE situacao = 1 AND categoria_pai = '.$categoria)->result();
            if($retorno_filhos){
                foreach($retorno_filhos as $filho){
                    $categorias .= ','.$filho->id;
                }
            }
			// SQL
            $from .= ' JOIN '.$this->prefixo_db .'institucional_produto_categoria pc ON pc.id_produto = p.id';
            $where .= ' AND pc.id_categoria IN ('.$categorias.') ';
		}

        if(isset($_POST)){
            if(isset($_POST['busca']) && $_POST['busca']){
                $where .= ' AND p.nome LIKE "%'.$_POST['busca'].'%" ';
            }
        }


        $ordem =  ' ORDER BY p.nome';
        
        $dados = $this->db->query('SELECT '.$select.' FROM '. $from .' WHERE '. $where.' GROUP BY p.id '.$ordem.''.($limit?' LIMIT '.$limit:''))->result();

        if($dados){
            foreach($dados as $k => $dado){
                $dados[$k]->fotos = $this->db->query('SELECT imagem FROM '.$this->prefixo_db .'institucional_produto_imagem WHERE id_produto = '.$dado->id)->result();
            }
        }
        return $dados;
    }

    public function getProduto($alias){
        if(!$alias){
            return null;
        }

        $dado = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_produto WHERE situacao = 1 AND alias = "'.$alias.'"')->row();
        if($dado) $dado->fotos = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_produto_imagem WHERE id_produto = '.(int)$dado->id.' ORDER BY ordem ASC')->result();

        return $dado;
    }

    public function getCategoriaProduto($categoria = null){
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_categoria WHERE situacao = 1 AND alias = "'.$categoria.'" ORDER BY nome ASC')->row();

        return $dados;
    }

    public function getCategoriasFilhas($categoria = null){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_categoria WHERE situacao = 1 AND categoria_pai = '.$categoria.' ORDER BY nome ASC')->result();

        return $dados;
    }

    public function getProfissionais($sql_extra = null){

        $select = 'p.*';
		$from = $this->prefixo_db .'institucional_profissionais p';
        $where = 'p.situacao = 1';

        if(isset($_POST)){
            if(isset($_POST['nome']) && $_POST['nome']){
                $where .= ' AND p.nome LIKE "%'.$_POST['nome'].'%" ';
            }
        }

        if($sql_extra){
            $where .= ' AND '.$sql_extra;
        }
        $ordem =  ' ORDER BY p.id';
        
        $dados = $this->db->query('SELECT '.$select.' FROM '. $from .' WHERE '. $where.' '.$ordem.'')->result();
        return $dados;
    }

    public function getGaleria($id){
        if(!$id){
            return null;
        }

        $dado = $this->db->query('SELECT * FROM '.$this->prefixo_db.'galeria WHERE situacao = 1 AND id = "'.$id.'"')->row();
        $dado->fotos = $this->db->query('SELECT * FROM '.$this->prefixo_db.'galeria_imagem WHERE id_galeria = '.(int)$dado->id.' ORDER BY ordem ASC')->result();

        return $dado;
    }

    public function salvar_solicitacao($post){
        $this->load->library('Imgno_validacao', '', 'validacao');
        
        if(isset($post['nm'])){
            $dados['nome'] = $post['nm'];
        }
        if(isset($post['em']) && $post['em']){

            if( !$this->validacao->validarEmail($post['em']) ){
                return false;
            }
            $dados['email'] = $post['em'];
        }
        if(isset($post['t1']) && $post['t1']){
            // if( !$this->validacao->validarTelefone($post['t1']) ){
            //     return false;
            // }
            $dados['telefone1'] = $post['t1'];
        }
        if(isset($post['t2']) && $post['t2']){
            // if( !$this->validacao->validarTelefone($post['t2']) ){
            //     return false;
            // }
            $dados['telefone2'] = $post['t2'];
        }

        if(isset($post['msg'])){
            $dados['observacoes'] = $post['msg'];
        }  
        
        if(isset($post['dialCode'])){
            $dados['dialCode'] = $post['dialCode'];
        }

        if(isset($post['dialCode2'])){
            $dados['dialCode2'] = $post['dialCode2'];
        }

        if(isset($post['termos'])){
            if(!$post['termos']){
                return false;
            }
            $dados['termos'] = $post['termos'];
        }

        if(isset($post['url'])){
            $dados['url'] = $post['url'];
        }

        $dados['situacao'] = 1; 

        $args = array(
            '_tabela' => 'institucional_solicitacao',
            'dados' => $dados
        );

        $args['retornar_id'] = true;
        $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
        $id = $this->inserir_dados_tabela($args);

        if($id){
            $this->load->library('Imgno_email', '', 'enviarEmail');
            
            // enviar para admin
            $this->enviarEmail->comporMensagem($post,'Nova solicitação',null,null,true,false);
            if(isset($post['em']) && $post['em']){
                $this->enviarEmail->comporMensagem(
                    array('tipo_email' => 'mensagem_solicitacao', 'params' => $post),
                    'Olá '.(!empty($post['nm'])?$post['nm']:'').', recebemos sua solicitação',
                    $post['em'],
                    null,
                    true,
                    true
                );
            }
        }

        return  $id;
    }

    public function salvar_contato($post){
        $this->load->library('Imgno_validacao', '', 'validacao');
        
        if(isset($post['nm'])){
            $dados['nome'] = $post['nm'];
        }
        if(isset($post['em']) && $post['em']){

            if( !$this->validacao->validarEmail($post['em']) ){
                return false;
            }
            $dados['email'] = $post['em'];
        }
        if(isset($post['t1']) && $post['t1']){
            // if( !$this->validacao->validarTelefone($post['t1']) ){
            //     return false;
            // }
            $dados['telefone1'] = $post['t1'];
        }
        if(isset($post['t2']) && $post['t2']){
            // if( !$this->validacao->validarTelefone($post['t2']) ){
            //     return false;
            // }
            $dados['telefone2'] = $post['t2'];
        }

        if(isset($post['msg'])){
            $dados['mensagem'] = $post['msg'];
        }

        if(isset($post['dialCode'])){
            $dados['dialCode'] = $post['dialCode'];
        }

        if(isset($post['dialCode2'])){
            $dados['dialCode2'] = $post['dialCode2'];
        }

        
        
        // $dados['empresa'] = '';
        // $dados['cargo'] = '';
        // $dados['empresa_atividade'] = '';
        $dados['situacao'] = 0;

        $args = array(
            '_tabela' => 'contato_mensagens',
            'dados' => $dados
        );

        $args['retornar_id'] = true;
        $args["dados"]["datahora_cadastro"] = date('Y-m-d H:i:s');
        $id = $this->inserir_dados_tabela($args);

        if($id){
            $this->load->library('Imgno_email', '', 'enviarEmail');
            
            // enviar para admin
            $this->enviarEmail->comporMensagem($post,'Novo contato',null,null,true,false);
            if(isset($post['em']) && $post['em']){
                $this->enviarEmail->comporMensagem(
                    array('tipo_email' => 'mensagem_contato', 'params' => $post),
                    'Olá '.(!empty($post['nm'])?$post['nm']:'').', Agradecemos o seu contato',
                    $post['em'],
                    null,
                    true,
                    true
                );
            }
        }

        return  $id;
    }

    function salvar_trabalhe($post){
        $dados['nome'] = $post['nm'];
        $dados['vaga'] = $post['vaga'];
        $dados['email'] = $post['em'];
        $dados['telefone1'] = $post['t1'];
        $dados['telefone2'] = $post['t2'];

        if(!empty($_FILES['imagem_anexo']['name'][0])) {
            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');
            // Define o caminho onde os arquivos serão salvos
            $dir = realpath('arquivos').'/imagens/contato/trabalhe/';
            $nomes = array();
            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);

            $dados['arquivo'] =  $nomes[0];
        
        }

        $args = array(
            '_tabela' => 'contato_trabalhe',
            'dados' => $dados
        );

        $args['retornar_id'] = true;
        $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
        
        $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);
    }

    public function getLGPD(){
        $dado = $this->db->query('SELECT * FROM '.$this->prefixo_db.'lgpd WHERE id = 1')->row();

        return $dado;
    }

    public function cadastrar_boletim($post){


        if(isset($post['nm'])){
            $dados['nome'] = $post['nm'];
        }
        if(isset($post['em'])){
            $dados['email'] = $post['em'];
        }

        $args = array(
            '_tabela' => 'contato_boletim',
            'dados' => $dados
        );

        $args['retornar_id'] = true;
        $args["dados"]["cadastro"] = date('Y-m-d H:i:s');

        $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);
    }


    public function buscar_carrinho_lista(){
        if(!$this->is_exite_carrinho()){
            return null;
        }

        $id_sessao = $this->carrinho_sessao();

        $dados = $this->db->query('SELECT ca.*, pr.nome as produto_nome, pr.preco as produto_preco, pr.preco_de as produto_preco_de, pr.alias FROM '.$this->prefixo_db.'financeiro_carrinho ca
                                    LEFT JOIN '.$this->prefixo_db.'institucional_produto pr ON (pr.id = ca.id_produto)
                                    WHERE ca.id_sessao = "'.$id_sessao.'" AND pr.ativarVenda = 1 AND pr.assinatura = 0 AND pr.situacao = 1 ORDER BY ca.id ASC')->result();

        return $dados;
    }   

}
