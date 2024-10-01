<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_landingpage extends Base_model {
    // Estado/Cidade
    function buscar_estados_cidades($args = null){

        $where = "1 ";

        if(isset($args['situacao']) && $args['situacao']){
            $where .= 'AND  cd.situacao = '.$args['situacao'].' ';
        }

        if(isset($args['categoria_pai']) && ($args['categoria_pai'] || $args['categoria_pai'] === 0)){
            $where .= 'AND cd.categoria_pai = '.$args['categoria_pai'].' ';
        } 


        $dados = $this->db->query('SELECT cd.*, rg.nome as nome_regiao, rg2.nome as nome_regiao_filho FROM '.$this->prefixo_db.'institucional_estado_cidade cd 
        LEFT JOIN '.$this->prefixo_db.'institucional_estado_cidade cd2 ON cd2.id = cd.categoria_pai 
        LEFT JOIN '.$this->prefixo_db.'institucional_regiaoLP rg ON rg.id = cd.regiao 
        LEFT JOIN '.$this->prefixo_db.'institucional_regiaoLP rg2 ON rg2.id = cd2.regiao 
        WHERE '.$where.' ORDER BY (cd.categoria_pai = 0), cd.ordem ASC, cd.nome ASC')->result();

		return $dados;
    }

    function buscar_estado_cidade($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_estado_cidade WHERE id = '.$id)->row_array();

		return $dados;
    }

    function buscar_regioes(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_regiaoLP ORDER BY nome ASC')->result();

		return $dados;
    }

    function salvar_estado_cidade($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;

		$this->load->library('Imgno_util', '', 'ImgnoUtil');	        
        //$strings_replace = $dados['strings_replace'];
        //unset($dados['strings_replace']);

        if(!$dados['alias']){
            // $dados['alias'] = $this->getAlias($this->ImgnoUtil->replace_tags("[Palavra-Chave-01]-".$dados['nome'], $strings_replace));
            $dados['alias'] = $this->getAlias($dados['nome']);
        }else{
            $dados['alias'] = $this->getAlias($dados['alias']);
        } 

        $i = 0;
        do{
            $i++;
            $validarAlias = $this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_estado_cidade WHERE alias = "'.$dados['alias'].'"'.(!empty($dados["id"])?' AND id <> '.$dados["id"]:''))->row();
            if($validarAlias) $dados['alias'] = $dados['alias']."-".$i++;

        }while($validarAlias);

        $args = array(
            '_tabela' => 'institucional_estado_cidade',
            'dados' => $dados
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];
 
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou o estado/cidade (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_estado_cidade WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // Add ou Criar arquivo da LP  
			$dados_config = $this->buscar_configuracoes_lp();
            if($dados_config){
                if(!file_exists(APPPATH .'config/routes-lp/')) mkdir(APPPATH .'config/routes-lp/', 0777, true);

				foreach($dados_config as $dado){
			        $strings_replace = $this->buscar_configuracao_lp_replace($dado->id);
                    $alias_pre = $this->getAlias($this->ImgnoUtil->replace_tags("[Palavra-Chave-01]", $strings_replace)); 
                    $conteudo = "<?php\n";
					if($dados["categoria_pai"]){
						$conteudo .= '$route["'.$alias_pre.'-'.$dados['alias'].'(?:\.html)?"] = "landingpage/Landingpage_controller_landingpage/lpCidade/'.$dado->id.'/'.$resultado["dados"]["id"].'";'."\n";
                    }else{
						$conteudo .= '$route["'.$alias_pre.'-'.$dados['alias'].'(?:\.html)?"] = "landingpage/Landingpage_controller_landingpage/lpEstado/'.$dado->id.'/'.$resultado["dados"]["id"].'";'."\n";
                    }
                    $conteudo .= "?>";  
                    $arquivo = fopen(APPPATH .'config/routes-lp/lp-'.$dado->id.'.php', 'a');
                    fwrite($arquivo, $conteudo);
                    fclose($arquivo);
				}
			}

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o estado/cidade (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT nome FROM '.$this->prefixo_db.'institucional_estado_cidade WHERE id = '.$resultado["dados"]["id"])->row('nome'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function buscar_solicitacoes($args = null){

        $where = '';

        if(!empty($args['nome'])){
            $where .= 'AND sol.nome LIKE "%'.$args['nome'].'%" ';
        }
        if(!empty($args['email'])){
            $where .= 'AND sol.email LIKE "%'.$args['email'].'%" ';
        }
        if(!empty($args['telefone'])){
            $where .= ' AND sol.telefone = "'.$args['telefone'].'" ';
        }

        if(!empty($args['estado'])){
            $where .= 'AND est.nome LIKE "%'.$args['estado'].'%" ';
        }

        if(!empty($args['cidade'])){
            $where .= 'AND cid.nome LIKE "%'.$args['cidade'].'%" ';
        }

        if(!empty($args['servico'])){
            $where .= 'AND config.`Palavra-Chave-01` LIKE "%'.$args['servico'].'%" ';
        }

        if(!empty($args['data_ini'])){
            $where .= 'AND sol.cadastro >= "'.$args['data_ini'].' 00:00:00" AND sol.cadastro <= "'.$args['data_ini'].' 23:59:59" ';
        } 

        
        $dados = $this->db->query('SELECT sol.*, cid.nome as nome_estado_cid, est.nome as nome_estado_est, cid.alias as alias_estado_cid, config.`Palavra-Chave-01` as nome_config FROM '.$this->prefixo_db.'institucional_solicitacoes_lp sol 
        LEFT JOIN '.$this->prefixo_db.'institucional_estado_cidade cid ON cid.id = sol.id_estado_cidade 
        LEFT JOIN '.$this->prefixo_db.'institucional_estado_cidade est ON est.id = cid.categoria_pai 
        LEFT JOIN '.$this->prefixo_db.'institucional_configuracao_lp config ON config.id = sol.id_configuracao 
        WHERE sol.id != 0 '.$where.' GROUP BY sol.id ORDER BY sol.id DESC')->result();

		return $dados;
    }

    function buscar_solicitacao($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT sol.*, cid.nome as nome_estado_cid, est.nome as nome_estado_est, cid.alias as alias_estado_cid, config.`Palavra-Chave-01` as nome_config FROM '.$this->prefixo_db.'institucional_solicitacoes_lp sol 
        LEFT JOIN '.$this->prefixo_db.'institucional_estado_cidade cid ON cid.id = sol.id_estado_cidade 
        LEFT JOIN '.$this->prefixo_db.'institucional_estado_cidade est ON est.id = cid.categoria_pai 
        LEFT JOIN '.$this->prefixo_db.'institucional_configuracao_lp config ON config.id = sol.id_configuracao 
        WHERE sol.id = '.$id)->row_array();

		return $dados;
    }

    function salvar_solicitacao($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        $resultado["retorno"] = 0;
        $resultado["dados"] = $dados;
       

        $args = array(
            '_tabela' => 'institucional_solicitacoes_lp',
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
                'log' => 'Atualizou a solicitação (#'.$resultado["dados"]["id"].') ',
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }

    function buscar_configuracoes_lp($args = null){

        $where = "1 ";

        if(isset($args['situacao']) && $args['situacao']){
            $where .= 'AND  clp.situacao = '.$args['situacao'].' ';
        } 

        $dados = $this->db->query('SELECT clp.* FROM '.$this->prefixo_db.'institucional_configuracao_lp clp WHERE '.$where.' ORDER BY clp.id DESC')->result();

		return $dados;
    }

    function buscar_configuracao_lp($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_configuracao_lp WHERE id = '.$id)->row_array();

        if($dados) $dados['imagens'] = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_configuracao_lp_imagem WHERE id_configuracao_lp = '.$id.' ORDER BY ordem ASC')->result_array();

		return $dados;
    }

    function salvar_configuracao_lp($dados){
        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');
        
        if(!empty($dados['copywriters'])){
            $dados['copywriters'] = json_encode($dados['copywriters']);
        }else{
            $dados['copywriters'] = json_encode(array());
        }

        $resultado["dados"] = $dados;

        $ids_imagens = null;

        if(isset($dados['ids_imagem']) && $dados['ids_imagem']){
            $ids_imagens = implode(',',$dados['ids_imagem']);
        }

        unset($dados['ids_imagem']);

        $args = array(
            '_tabela' => 'institucional_configuracao_lp',
            'dados' => $dados,
        );

        if($dados["id"] > 0){
           
            $args['where'] = 'id = '.$dados["id"];

            unset($args["dados"]["id"]);
            $this->atualizar_dados_tabela($args);

            $resultado["dados"]["id"] = $dados["id"];
 
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Atualizou a Configurações da LP (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT `Palavra-Chave-01` FROM '.$this->prefixo_db.'institucional_configuracao_lp WHERE id = '.$resultado["dados"]["id"])->row('Palavra-Chave-01'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{

            //banner automático
            $dados_banner = array(
                'nome' => 'Banner Principal LP '.$dados['Palavra-Chave-01'],
                'tipo' => 'responsivo',
                'exibir_banner_mobile' => 1,
                'situacao' => 1,
                'fullhd_status' => 1,
                'extralarge_status' => 1,
                'large_status' => 1,
                'medium_status' => 1,
                'small_status' => 1,
                'extrasmall_status' => 1,
                'fullhd' => 685,
                'extralarge' => 514,
                'large' => 428,
                'medium' => 354,
                'small' => 274,
                'extrasmall' => 205,
                'cadastro' => date('Y-m-d H:i:s'),
            ); 

            $args["dados"]["id_banner"] = $this->inserir_dados_tabela(
                array(
                    '_tabela' => 'banner',
                    'retornar_id' => true,
                    'dados' => $dados_banner
                )
            );

            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // Criar arquivo conexao para obter os arquivos das LP
			if(!file_exists(APPPATH .'config/routes-lp/')) mkdir(APPPATH .'config/routes-lp/', 0777, true);
            $conteudo = "<?php\n";
            $conteudo .= 'if(file_exists(APPPATH."/config/routes-lp/lp-'.$resultado["dados"]["id"].'.php")) include APPPATH."/config/routes-lp/lp-'.$resultado["dados"]["id"].'.php";'."\n";
            $conteudo .= "?>"; 
            $arquivo = fopen(APPPATH .'config/routes-lp/lp-conexao.php', 'a');
            fwrite($arquivo, $conteudo);
            fclose($arquivo);            

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou a Configurações da LP (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT `Palavra-Chave-01` FROM '.$this->prefixo_db.'institucional_configuracao_lp WHERE id = '.$resultado["dados"]["id"])->row('Palavra-Chave-01'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));

        }

        $this->remover_dados_tabela(array(
                '_tabela' => 'institucional_configuracao_lp_imagem',
                'where' => '1 '.($ids_imagens?'AND id NOT IN ('. $ids_imagens.')':'').' AND id_configuracao_lp = '.$resultado["dados"]["id"]
        )); 

        if(!empty($_FILES['imagem_anexo']['name'][0])) {
            // Carrega a biblioteca necessária
            $this->load->library('Imgno_upload', '', 'upload_arquivos');
            // Define o caminho onde os arquivos serão salvos
            $dir = realpath('arquivos').'/imagens/LP/galeria/';
            if(!(file_exists($dir))) mkdir($dir, 0777, true); 
            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);
            
            if($nomes){
                foreach($nomes as $imagem) {
                    $this->inserir_dados_tabela(array(
                        '_tabela' => 'institucional_configuracao_lp_imagem',
                        'dados' => array(
                            'imagem' => $imagem,
                            'id_configuracao_lp' => $resultado["dados"]["id"],
                            'ordem' => 0,
                            'cadastro' => date('Y-m-d H:i:s'),
                        )
                    ));
                }
            }

        } 

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }    
    
    function excluir_configuracoes($post){ 
        $dados = $post;

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        foreach($dados['id'] as $item){

            $lp = $this->db->query('SELECT `Palavra-Chave-01`, `id_banner`, `id` FROM '.$this->prefixo_db.'institucional_configuracao_lp WHERE id = '.$item)->row();
            
            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Excluiu a Configurações da LP (#'.$item.') '.$lp->{'Palavra-Chave-01'},
                // 'id_cliente' => null,
                // 'json' => null,
            ));

            // banner
            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'banner',
                    'where' => 'id = '.$lp->id_banner
                )
            );

            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'banner_slide',
                    'where' => 'banner_id = '.$lp->id_banner
                )
            );

            // LP
            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'institucional_configuracao_lp',
                        'where' => 'id = '.$item
                    )
            ); 

            $this->remover_dados_tabela(
                array(
                    '_tabela' => 'institucional_configuracao_lp_imagem',
                    'where' => 'id_configuracao_lp = '.$item
                )
            );

			if(file_exists(APPPATH .'config/routes-lp/lp-'.$item.'.php')) unlink(APPPATH .'config/routes-lp/lp-'.$item.'.php');

            $this->gerar_lp_seo_excluir($lp->id);

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }

    function buscar_configuracao_lp_replace($id){

        $dados = $this->db->query('SELECT `Palavra-Chave-01`,`Palavra-Chave-02`,`Palavra-Chave-03`,`Palavra-Chave-04`,`Palavra-Chave-05`,`Palavra-Chave-06`,`Palavra-Chave-07`,`Palavra-Chave-08`,`Descricao-SEO` FROM '.$this->prefixo_db.'institucional_configuracao_lp WHERE id = '.$id)->row_array();

		return $dados;
    }

    function buscar_copywriters($args = null){

        $where = '';

        if(isset($args['titulo']) && $args['titulo']){
            $where .= 'AND titulo LIKE "%'.$args['titulo'].'%" ';
        }
        
        if($args['situacao']){
            $where .= 'AND situacao = '.$args['situacao'].' ';
        }

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'copywriter_lp WHERE id != 0 '.$where.' ORDER BY id DESC')->result();

		return $dados;
    }

    function buscar_copywriter($id){
        if(!$id) return null;

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'copywriter_lp WHERE id = '.$id)->row_array();

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
            $dir = realpath('arquivos').'/imagens/LP/copywriter/';
            if(!file_exists($dir)) mkdir($dir, 0777, true);
            $nomes = array();

            $this->upload_arquivos->enviar_arquivos('', 'imagem_anexo', $dir, $nomes);

            $dados['imagem'] =  $nomes[0];
        
        }

        $args = array(
            '_tabela' => 'copywriter_lp',
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
                'log' => 'Atualizou o copywriter da landing page (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT titulo FROM '.$this->prefixo_db.'copywriter_lp WHERE id = '.$resultado["dados"]["id"])->row('titulo'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastro"] = date('Y-m-d H:i:s');
            $resultado["dados"]["id"] = $this->inserir_dados_tabela($args);

            // log admin
            $this->model_log->salvar_log( array(
                'log' => 'Cadastrou o copywriter da landing page (#'.$resultado["dados"]["id"].') '.$this->db->query('SELECT titulo FROM '.$this->prefixo_db.'copywriter_lp WHERE id = '.$resultado["dados"]["id"])->row('titulo'),
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
                'log' => 'Excluiu o copywriter da landing page (#'.$item.') '.$this->db->query('SELECT titulo FROM '.$this->prefixo_db.'copywriter_lp WHERE id = '.$item)->row('titulo'),
                // 'id_cliente' => null,
                // 'json' => null,
            ));
           
            $this->remover_dados_tabela(
                    array(
                        '_tabela' => 'copywriter_lp',
                        'where' => 'id ='.$item
                    )
            );

        }
        

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;

    }

    function buscar_copywriter_configuracao(){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'copywriter_config_lp WHERE id = 1')->row_array();

		return $dados;
    }

    function salvar_copywriter_configuracao($dados){

        $resultado["dados"] = $dados; 

        $args = array(
            '_tabela' => 'copywriter_config_lp',
            'dados' => $dados,
        );
        $args['where'] = 'id = 1';
        
        $this->atualizar_dados_tabela($args);

        // log admin
        $this->load->model('admin/Admin_model_log', 'model_log');

        // log admin
        $this->model_log->salvar_log( array(
            'log' => 'Atualizou as configuração de Copywriter da landing page',
            // 'id_cliente' => null,
            // 'json' => null,
        ));

        $resultado["msg"] = "Salvo com sucesso!";
        $resultado["retorno"] = 1;

        return $resultado;
    }  

    function gerar_lp_seo_excluir($id_config){

        $this->remover_dados_tabela(
            array(
                '_tabela' => 'seo_link',
                'where' => 'route_value LIKE "%landingpage/Landingpage_controller_landingpage/lpCidade/'.$id_config.'%" OR route_value LIKE "%landingpage/Landingpage_controller_landingpage/lpEstado/'.$id_config.'%"' 
            )
        );

    }
    
    function getAlias($str){
        
        $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º','"',"'");
        $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-');
        $string_format = str_replace($what, $by, $str);

        return strtolower($string_format);
    }
}
?>