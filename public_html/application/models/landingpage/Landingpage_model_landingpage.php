<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Landingpage_model_landingpage extends Base_model {

    function buscar_configuracao_lp_replace($id){

        $dados = $this->db->query('SELECT `Palavra-Chave-01`,`Palavra-Chave-02`,`Palavra-Chave-03`,`Palavra-Chave-04`,`Palavra-Chave-05`,`Palavra-Chave-06`,`Palavra-Chave-07`,`Palavra-Chave-08`,`Descricao-SEO`,`telefone1` FROM '.$this->prefixo_db.'institucional_configuracao_lp WHERE id = '.$id)->row_array();

		return $dados;
    }

    function buscar_configuracao_lp($id){

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_configuracao_lp WHERE id = '.$id)->row_array();

        if($dados) $dados['imagens'] = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_configuracao_lp_imagem WHERE id_configuracao_lp = '.$id.' ORDER BY ordem ASC')->result_array();

		return $dados;
    }

    public function lpCidade($id){
        $dado = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_estado_cidade WHERE id = "'.$id.'" AND categoria_pai <> 0 AND situacao = 1')->row();

        if($dado){
            $dado->estado = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_estado_cidade WHERE id = "'.$dado->categoria_pai.'" AND categoria_pai = 0')->row();
        }

        return $dado;
    }

    public function lpEstado($id){
        $dado = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_estado_cidade WHERE id = "'.$id.'" AND categoria_pai = 0 AND situacao = 1')->row();
        if($dado){
            $dado->cidades = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_estado_cidade WHERE categoria_pai = "'.$id.'" AND situacao = 1')->result();
        } 

        return $dado;
    }

    public function buscarEstadoAll(){ 
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_estado_cidade WHERE categoria_pai = 0 AND situacao = 1 ORDER BY nome ASC')->result();
        
        return $dados;
    }

    public function buscarEstadoCapitalAll(){ 
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_estado_cidade WHERE categoria_pai <> 0 AND situacao = 1 AND capital = 1 ORDER BY nome ASC')->result();
        
        return $dados;
    }

    public function buscarConfigAll(){ 
        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'institucional_configuracao_lp ORDER BY id ASC')->result();
        
        return $dados;
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
        
        if(isset($post['dialCode'])){
            $dados['dialCode'] = $post['dialCode'];
        }

        if(isset($post['abe'])){
            $dados['aberto'] = $post['abe'];
        }

        if(isset($post['id_configuracao'])){
            $dados['id_configuracao'] = $post['id_configuracao'];
        }

        if(isset($post['id_estado_cidade'])){
            $dados['id_estado_cidade'] = $post['id_estado_cidade'];
        } 

        if(isset($post['url'])){
            $dados['url'] = $post['url'];
        }

        // $dados['situacao'] = 1; 

        $args = array(
            '_tabela' => 'institucional_solicitacoes_lp',
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
                    array('tipo_email' => 'mensagem_solicitacao_lp', 'params' => $post),
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

    public function buscar_copywriter($id = 0){

        if(!$id){
            return false;
        }

        $dados = $this->db->query('SELECT * FROM '.$this->prefixo_db.'copywriter_lp WHERE id = '.$id.' AND situacao = 1')->row_array();

        return $dados;
    } 

    public function buscar_config_copywriter($select = ''){

        if(!$select){
            $select = '*';
        }

        $dados = $this->db->query('SELECT '.$select.' FROM '.$this->prefixo_db.'copywriter_config_lp WHERE id = 1')->row_array();

        return $dados;
    }

    function getAlias($str){
        
        $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º','"',"'");
        $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-','-');
        $string_format = str_replace($what, $by, $str);

        return strtolower($string_format);
    }

}
