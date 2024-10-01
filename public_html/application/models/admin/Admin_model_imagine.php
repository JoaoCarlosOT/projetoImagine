<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Admin_model_imagine extends Base_model {

    public function autenticar_usuario_imagine( $dados_info) {
        if(!isset($dados_info['usuario']) || !$dados_info['usuario']){
            return false;
        }

        $ver_usuario = $this->db->query('SELECT id FROM '.$this->prefixo_db.'admin WHERE  usuario = "'. $dados_info['usuario'] .'" AND usuario_imagine = 1')->row();
        if($ver_usuario){
            // return false;
        }

        // Busca os dados associados à este login no talci
        if( $dados = $this->buscarDados( $dados_info['usuario'] , $this->getToken($dados_info['senha']) ) ){

            // $dados = explode( ':', $dados );
            $dados = json_decode($dados,true);

            if(!$dados){
                return false;
            }

            $settings['id'] = 0;
            $settings['usuario'] = $dados['email'];
            $settings['senha'] = $dados['senha'];
            $settings['email'] = $dados['email'];
            $settings['nome'] = $dados['nome'];
            $settings['situacao'] = (int) ( (int)$dados['published'] == 1?1:0);

            if($ver_usuario){
                $settings['id'] = $ver_usuario->id;
            }

            $this->salvarUsuario($settings);

            return true;
        }

        return false;
        // exit;
    }

    // Salva os dados de um dado usuario
    function salvarUsuario( $dados ) {


        if(!$dados["senha"]){
            unset($dados["senha"]);
        }

        $args = array(
            '_tabela' => 'admin',
            'dados' => $dados,
        );
        unset($args["dados"]["id"]);

        if($dados["id"] > 0){
            $args['where'] = 'id = '.$dados["id"];
            
            $this->atualizar_dados_tabela($args);
            
        }else{
            
            $args['retornar_id'] = true;
            $args["dados"]["cadastrado"] = date('Y-m-d H:i:s');
            $args["dados"]["usuario_imagine"] = 1;
            $id_admin = $this->inserir_dados_tabela($args);


            $lista_permissoes = $this->db->query('SELECT valor FROM '.$this->prefixo_db.'admin_lista_permissoes')->result();

            if($lista_permissoes){
                foreach($lista_permissoes as $permissao){

                    $this->inserir_dados_tabela(array(
                        '_tabela' => 'admin_permissoes',
                        'dados' => array(
                            'id_usuario' => $id_admin,
                            'permissao' => $permissao->valor,
                        ),
                    ));
                }
            }
        }
    }
    
    // Verifica se o site está habilitado
    function buscarDados( $login , $senha ){
        $retorno = $this->getURL('https://talci.com.br/login_integracao_imagine?dados_imagine='.urlencode( '{ "login":"'. $login .'","token":"'. $this->getToken( $senha ).'"}' ));
        return @preg_replace( '/^.+##return/', '' , @preg_replace( '/(<\/?[^>]+>)|([\n\r\t])/', '' , @preg_replace( '/<.*body[^>]*>/', '##return' , @preg_replace( '/<\/\s*body.*/', '', $retorno ) ) ) );
    }
    
    // Busca o conteúdo de ua dada URL
    function getURL( $url_busca ) {
        if( ini_get( 'allow_url_fopen' ) == '1' ) $conteudo = @file_get_contents( $url_busca );
        else{
            
            if( function_exists( 'curl_init' ) ){
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, $url_busca );
                curl_setopt( $ch, CURLOPT_HEADER, 0 );
                curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
                curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0' );
                $conteudo = curl_exec( $ch );
                curl_close( $ch );
            }
        }
        return $conteudo;
    }

    // Retorna o token MD5 deste site
    function getToken( $valor ){
        $this->load->library('Imgno_pass', '', 'gerador_hash');

        return  $this->gerador_hash->gerar_senha( $valor );
    }
    

}