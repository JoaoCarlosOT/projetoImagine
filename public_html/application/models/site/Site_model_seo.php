<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Site_model_seo extends Base_model {

    function verificar_cadastrar(){
        $ignorar = array('admin','cron','ajax','default_controller','404_override');

        $route = $this->uri;
        
        $link = (isset($route->uri_string) && $route->uri_string? str_replace('.html', '',$route->uri_string):'/');

        $title = @$this->dados['titulo'].'';
        $description = @$this->dados['descricao'].'';
        $keywords = @$this->dados['keywords'].'';

        $description = ($description?$description:'Página de '.$title);

        $retorno = $this->db->query('SELECT * FROM '. $this->prefixo_db .'seo_link WHERE id != 0  '.($link?' AND (link != "" AND (link LIKE "%'.$link.'.html" OR link LIKE "%'.$link.'"))':''))->row_array();
        if($retorno){

            if($link && ($title || $description || $keywords)){
                $retorno = $this->db->query('SELECT id,title,keywords,description FROM '. $this->prefixo_db .'seo_link WHERE id != 0  AND (link != "" AND (link LIKE "%'.$link.'.html" OR link LIKE "%'.$link.'")) AND (description = "" OR title = "" OR keywords = "") ')->row_array();
                if($retorno){

                    $inserir_title = $this->limparText($retorno['title']?$retorno['title']:$title);
                    $inserir_desc = $this->limparText($retorno['description']?$retorno['description']:$description);

                    $inserir_keywords =($retorno['keywords']? $retorno['keywords']:$keywords);
                    if(!$inserir_keywords){
                        $inserir_keywords =$this->getKeywordsAuto($link);
                    }

                    $this->atualizar_dados_tabela(array(
                        '_tabela' => 'seo_link',
                        'dados' => array(
                            'title' => ($inserir_title),
                            'description' =>  ($inserir_desc),
                            'keywords' =>  $inserir_keywords,
                            'canonical' =>  base_url().($link == '/'?'':$link),
                        ),
                       'where' => 'id = '.$retorno['id']
                    ));
                }

            }

            return false;
        }

        $route_link = str_replace(".html", "", implode('/', $route->segments));
        $route_value =  $this->pasta_nome.'/'.implode('/', $route->rsegments);
        
        if( isset($route_link) && !in_array($this->pasta_nome,$ignorar) ){
            
            $title = $this->limparText($title);
            $description = $this->limparText($description);

            $inserir_keywords = $keywords;
            if(!$inserir_keywords){
                $inserir_keywords = $this->getKeywordsAuto($link);
            }

            $this->inserir_dados_tabela(array(
                '_tabela' => 'seo_link',
                'dados' => array(
                    'link' =>  $link,
                    'route' =>  $route_link,
                    'route_value' =>  $route_value,
                    'title' =>  ($title) ,
                    'description' =>   ($description) ,
                    'keywords' =>  $keywords,
                    'canonical' =>  base_url().($link == '/'?'':$link),
                )
            ));
            return true;
        }

        return false;
    }

    function limparText($inserir_desc) {
        $inserir_desc = html_entity_decode($inserir_desc, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        // remover html
        $inserir_desc = strip_tags($inserir_desc);
        // substituir multiplos espacos e quebras de linha para espaco unico
        $inserir_desc = preg_replace('/\s+/', ' ', $inserir_desc);

        return $inserir_desc;
    }

    function getPageContent($url) {
        // Usar cURL para buscar o conteúdo da página
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $content = curl_exec($ch);
        curl_close($ch);
        return $content;
    }

    function getKeywordsAuto($url) {
        if(!$url) return '';

        $url = ($url == '/'?base_url().'?seo_keywords_ready=1':base_url().$url.'?seo_keywords_ready=1');
        $htmlContent = $this->getPageContent($url);
        if(!$htmlContent) return '';

        $htmlContent = trim(preg_replace('/\s+/', ' ', $htmlContent));

        $dom = new DOMDocument();
        // Carregar o HTML no DOMDocument
        libxml_use_internal_errors(true);
        $dom->loadHTML($htmlContent);
        libxml_clear_errors();
        
        $xpath = new DOMXPath($dom);

        // Consultar os elementos <h1>, <h2>,<h3>
        $h1Nodes = $xpath->query('//h1');
        $h2Nodes = $xpath->query('//h2');
        $h3Nodes = $xpath->query('//h3');
        
        // extrair texto
        function extractTextFromNodes($nodes) {
            $texts = [];
            foreach ($nodes as $node) {
                $texts[] = trim($node->textContent);
            }
            return $texts;
        }
        $lista_keys = array();
        $max_keys_auto = 4;
    
        if(count($lista_keys) < $max_keys_auto){
            foreach (extractTextFromNodes($h1Nodes) as $text) {
                if($text && count($lista_keys) < $max_keys_auto){
                    $lista_keys[] =  str_replace(',',' e ',preg_replace('/[^\p{L}\p{N}\s,]/u', '', $text));

                }else{
                    break;
                }
            }
        }
    
        if(count($lista_keys) < $max_keys_auto){
            foreach (extractTextFromNodes($h2Nodes) as $text) {
                if($text && count($lista_keys) < $max_keys_auto){
                    $lista_keys[] =  str_replace(',',' e ',preg_replace('/[^\p{L}\p{N}\s,]/u', '', $text));
                }else{
                    break;
                }
            }
        }
    
        if(count($lista_keys) < $max_keys_auto){
            foreach (extractTextFromNodes($h3Nodes) as $text) {
                if($text && count($lista_keys) < $max_keys_auto){
                    $lista_keys[] =  str_replace(',',' e ',preg_replace('/[^\p{L}\p{N}\s,]/u', '', $text));
                }else{
                    break;
                }
            }
        }
        
        $txt_keys = '';
        if($lista_keys){
            $txt_keys = implode(',',$lista_keys);
        }

        return $txt_keys;
    }

    function dados_pagina_seo($link = null){

        if(!$link){
            $link = $this->uri->uri_string;
        }

        if(!$link){
            //inicio
            $link = "/";
            $retorno = $this->db->query('SELECT * FROM '. $this->prefixo_db .'seo_link WHERE id != 0  AND link != "" AND link = "'.$link.'" ')->row_array();
            return $retorno;
        }

        $retorno = $this->db->query('SELECT * FROM '. $this->prefixo_db .'seo_link WHERE id != 0  '.($link?' AND (link != "" AND (link LIKE "%'.$link.'.html" OR link LIKE "%'.$link.'")) ':''))->row_array();

        return $retorno;
    }

    function dados_pagina_seo_config(){
        $retorno = $this->db->query('SELECT * FROM '. $this->prefixo_db .'seo_config WHERE id = 1 ')->row_array();
        return $retorno;
    }


}