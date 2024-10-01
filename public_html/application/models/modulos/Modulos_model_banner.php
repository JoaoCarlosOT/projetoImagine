<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH .'base/Base_model.php');

class Modulos_model_banner extends Base_model {

    public function getSlides($bannerId = 0){

        $dataAtual = date('Y-m-d H:i:s');

        $desativarBanners = $this->db->query('SELECT * FROM '.$this->prefixo_db.'banner_slide WHERE dataFim <> "0000-00-00 00:00:00" AND "'.$dataAtual.'" >= dataFim AND situacao = 1 ORDER BY ordem ASC')->result();
        if($desativarBanners){        
            foreach($desativarBanners as $desativarBanner){ 
                             
                $this->atualizar_dados_tabela(array(
                    '_tabela' => 'banner_slide',
                    'dados' => array('situacao' => 0),
                    'where' => 'id = '.$desativarBanner->id
                ));
            }   
        }
        
        $banner = $this->db->query('SELECT * FROM '.$this->prefixo_db.'banner WHERE id = '.$bannerId.' AND situacao = 1')->row();
        if(!$banner) return;
        
        $bannerSlides = $this->db->query('SELECT * FROM '.$this->prefixo_db.'banner_slide WHERE (dataInicio = "0000-00-00 00:00:00" OR "'.$dataAtual.'" >= dataInicio) AND (dataFim = "0000-00-00 00:00:00" OR "'.$dataAtual.'" <= dataFim) AND banner_id = '.$bannerId.' AND situacao = 1 ORDER BY ordem ASC')->result();
    	if(!$bannerSlides) return;
    	
    	$slides = array();
        $this->load->library('Imgno_imagem', '', 'imagineImagem');
        $dir ='/arquivos/imagens/banner/';

        $tamanhos = array(
            'fullhd',
            'extralarge',
            'large',
            'medium',
            'small',
            'extrasmall',
        );

        $tamanhos2 = array(
            'extrasmall' => 576,
            'small' => 576,
            'medium' => 768,
            'large' => 922,
            'extralarge' => 1200,
            'fullhd' => 1440,
        );

        $qualidade = 90;
    	
        foreach($bannerSlides as $value){ 
            $slide = array(
                'fullhd' => '',
                'extralarge' => '',
                'large' => '',
                'medium' => '',
                'small' => '',
                'extrasmall' => '',

                'imagemBanner' => '',
                'cta_status' => '',
                'cta_titulo' => '',
                'cta_btn_nomenclatura' => '',

                'video_fullhd' => '',
                'video_extralarge' => '',
                'video_large' => '',
                'video_medium' => '',
                'video_small' => '',
                'video_extrasmall' => '',
            );

            if($value->tipo_banner == 0 && $value->fullhd){    
                
                foreach($tamanhos as $k => $v){
                    if(empty($value->{$tamanhos[0]})) $value->{$tamanhos[0]} = $value->{$tamanhos[$k+1]};
                    if($value->{$tamanhos[0]}) break;
                }  

                foreach($tamanhos as $k => $v){
                    if(!$value->{$v}) $value->{$v} = $value->{$tamanhos[$k-1]};
                }
                
                if($banner->fullhd_status == 1 && $value->fullhd_status == 1){
                    $slide['fullhd'] = $this->imagineImagem->otimizar($dir.$value->fullhd, 1920, 0, true, true, $qualidade);
                }
                if($banner->extralarge_status == 1 && $value->extralarge_status == 1){
                    $slide['extralarge'] = $this->imagineImagem->otimizar($dir.$value->extralarge, 1440, 0, true, true, $qualidade);
                }
                if($banner->large_status == 1 && $value->large_status == 1){
                    $slide['large'] = $this->imagineImagem->otimizar($dir.$value->large, 1200, 0, true, true, $qualidade);
                }
                if($banner->medium_status == 1 && $value->medium_status == 1){
                    $slide['medium'] = $this->imagineImagem->otimizar($dir.$value->medium, 992, 0, true, true, $qualidade);
                }
                if($banner->small_status == 1 && $value->small_status == 1){
                    $slide['small'] = $this->imagineImagem->otimizar($dir.$value->small, 768, 0, true, true, $qualidade);
                }
                if($banner->extrasmall_status == 1 && $value->extrasmall_status == 1){
                    $slide['extrasmall'] = $this->imagineImagem->otimizar($dir.$value->extrasmall, 576, 0, true, true, $qualidade);
                } 

                $slide['titulo'] = $value->nome;
                $slide['link'] = $value->link;
                $slide['color'] = $value->color;
                $slide['colorFonte'] = $value->colorFonte;
                $slide['descricao'] = $value->descricao;
                $slide['texto_btn'] = $value->texto_btn;
                $slide['tipo_texto'] = $value->tipo_texto;
                $slide['tipo_banner'] = $value->tipo_banner;

            }else if($value->tipo_banner == 1 && $value->imagemBanner){

                if($value->imagemBanner && $value->imagemBanner_status == 1){
                    $slide['imagemBanner'] = $this->imagineImagem->otimizar($dir.$value->imagemBanner, 650, 440, false, true, $qualidade);
                }else{
                    $slide['imagemBanner'] = '';
                }

                $slide['cta_status'] = $value->cta_status;
                $slide['cta_titulo'] = $value->cta_titulo;
                $slide['cta_btn_nomenclatura'] = $value->cta_btn_nomenclatura;

                $slide['titulo'] = $value->nome;
                $slide['link'] = $value->link;
                $slide['color'] = $value->color;
                $slide['colorFonte'] = $value->colorFonte;
                $slide['descricao'] = $value->descricao;
                $slide['texto_btn'] = $value->texto_btn;
                $slide['tipo_texto'] = $value->tipo_texto;
                $slide['tipo_banner'] = $value->tipo_banner;

            }else if($value->tipo_banner == 2 && $value->video_fullhd){ 

                if($banner->fullhd_status == 1 && $value->video_fullhd_status == 1 && $value->video_fullhd){
                    $slide['video_fullhd'] = $dir.$value->video_fullhd;
                }
                if($banner->extralarge_status == 1 && $value->video_extralarge_status == 1 && $value->video_extralarge){
                    $slide['video_extralarge'] = $dir.$value->video_extralarge;
                }
                if($banner->large_status == 1 && $value->video_large_status == 1 && $value->video_large){
                    $slide['video_large'] = $dir.$value->video_large;
                }
                if($banner->medium_status == 1 && $value->video_medium_status == 1 && $value->video_medium){
                    $slide['video_medium'] = $dir.$value->video_medium;
                }
                if($banner->small_status == 1 && $value->video_small_status == 1 && $value->video_small){
                    $slide['video_small'] =  $dir.$value->video_small;
                }
                if($banner->extrasmall_status == 1 && $value->video_extrasmall_status == 1 && $value->video_extrasmall){
                    $slide['video_extrasmall'] = $dir.$value->video_extrasmall;
                } 

                $slide['titulo'] = $value->nome;
                $slide['link'] = $value->link;
                $slide['color'] = $value->color;
                $slide['colorFonte'] = $value->colorFonte;
                $slide['descricao'] = $value->descricao;
                $slide['texto_btn'] = $value->texto_btn;
                $slide['tipo_texto'] = $value->tipo_texto;
                $slide['tipo_banner'] = $value->tipo_banner;

            }else{
                continue;
            }
            
            $slides[] = $slide;
    	}

        if($banner->tipo == 'fixo'){
            foreach($tamanhos as $k => $v){
                if(!$banner->{$tamanhos[0]}) $banner->{$tamanhos[0]} = $banner->{$tamanhos[$k+1]};
                if($banner->{$tamanhos[0]}) break;
            }
            foreach($tamanhos as $k => $v){
                if(!$banner->{$v}) $banner->{$v} = $banner->{$tamanhos[$k-1]};
            }
        }
        if($banner->tipo == 'responsivo'){
            foreach($tamanhos as $k => $v){
                if(!$banner->{$tamanhos[0]}){
                    $h = $banner->{$tamanhos[$k+1]};
                    $w = $tamanhos2[$tamanhos[$k+1]];
                    $fw = $tamanhos2[$tamanhos[0]]; 
                    $banner->{$tamanhos[0]} = ($h * $fw) / $w;
                } 
                if($banner->{$tamanhos[0]}) break;
            }
            foreach($tamanhos as $k => $v){
                if(!$banner->{$v}){
                    $h = $banner->{$tamanhos[$k-1]};
                    $w = $tamanhos2[$tamanhos[$k-1]];
                    $fw = $tamanhos2[$v]; 
                    $banner->{$v} = ($h * $fw) / $w;
                } 
            }
        }

   		return array('banner' => $banner, 'slides' => $slides);

    }  

    public function getSlidesUnicaImg($bannerId = 0, $slideId = 0){

        $dataAtual = date('Y-m-d H:i:s');

        $desativarBanners = $this->db->query('SELECT * FROM '.$this->prefixo_db.'banner_slide WHERE dataFim <> "0000-00-00 00:00:00" AND "'.$dataAtual.'" >= dataFim AND situacao = 1 ORDER BY ordem ASC')->result();
        if($desativarBanners){        
            foreach($desativarBanners as $desativarBanner){ 
                             
                $this->atualizar_dados_tabela(array(
                    '_tabela' => 'banner_slide',
                    'dados' => array('situacao' => 0),
                    'where' => 'id = '.$desativarBanner->id
                ));
            }   
        }
        
        $banner = $this->db->query('SELECT * FROM '.$this->prefixo_db.'banner WHERE id = '.$bannerId.' AND situacao = 1')->row();
        if(!$banner) return;
        
        $bannerSlide = $this->db->query('SELECT * FROM '.$this->prefixo_db.'banner_slide WHERE (dataInicio = "0000-00-00 00:00:00" OR "'.$dataAtual.'" >= dataInicio) AND (dataFim = "0000-00-00 00:00:00" OR "'.$dataAtual.'" <= dataFim) AND banner_id = '.$bannerId.'  AND id = '.$slideId.' AND situacao = 1 ORDER BY ordem ASC')->row();

    	if(!$bannerSlide) return;
    	
        $this->load->library('Imgno_imagem', '', 'imagineImagem');
        $dir ='/arquivos/imagens/banner/';

        $tamanhos = array(
            'fullhd',
            'extralarge',
            'large',
            'medium',
            'small',
            'extrasmall',
        );

        $tamanhos2 = array(
            'extrasmall' => 576,
            'small' => 576,
            'medium' => 768,
            'large' => 922,
            'extralarge' => 1200,
            'fullhd' => 1440,
        );

        $slide = array(
            'fullhd' => '',
            'extralarge' => '',
            'large' => '',
            'medium' => '',
            'small' => '',
            'extrasmall' => '',
        );

        $qualidade = 90;

        if($bannerSlide->tipo_banner == 0 && $bannerSlide->fullhd){    
            
            foreach($tamanhos as $k => $v){
                if(empty($bannerSlide->{$tamanhos[0]})) $bannerSlide->{$tamanhos[0]} = $bannerSlide->{$tamanhos[$k+1]};
                if($bannerSlide->{$tamanhos[0]}) break;
            }  

            foreach($tamanhos as $k => $v){
                if(!$bannerSlide->{$v}) $bannerSlide->{$v} = $bannerSlide->{$tamanhos[$k-1]};
            }
            
            if($banner->fullhd_status == 1 && $bannerSlide->fullhd_status == 1){
                $slide['fullhd'] = $this->imagineImagem->otimizar($dir.$bannerSlide->fullhd, 1920, 0, true, true, $qualidade);
            }
            if($banner->extralarge_status == 1 && $bannerSlide->extralarge_status == 1){
                $slide['extralarge'] = $this->imagineImagem->otimizar($dir.$bannerSlide->extralarge, 1440, 0, true, true, $qualidade);
            }
            if($banner->large_status == 1 && $bannerSlide->large_status == 1){
                $slide['large'] = $this->imagineImagem->otimizar($dir.$bannerSlide->large, 1200, 0, true, true, $qualidade);
            }
            if($banner->medium_status == 1 && $bannerSlide->medium_status == 1){
                $slide['medium'] = $this->imagineImagem->otimizar($dir.$bannerSlide->medium, 992, 0, true, true, $qualidade);
            }
            if($banner->small_status == 1 && $bannerSlide->small_status == 1){
                $slide['small'] = $this->imagineImagem->otimizar($dir.$bannerSlide->small, 768, 0, true, true, $qualidade);
            }
            if($banner->extrasmall_status == 1 && $bannerSlide->extrasmall_status == 1){
                $slide['extrasmall'] = $this->imagineImagem->otimizar($dir.$bannerSlide->extrasmall, 576, 0, true, true, $qualidade);
            } 

            $slide['titulo'] = $bannerSlide->nome;
            $slide['link'] = $bannerSlide->link;
            $slide['color'] = $bannerSlide->color;
            $slide['colorFonte'] = $bannerSlide->colorFonte;
            $slide['descricao'] = $bannerSlide->descricao;
            $slide['texto_btn'] = $bannerSlide->texto_btn;
            $slide['tipo_texto'] = $bannerSlide->tipo_texto;
            $slide['tipo_banner'] = $bannerSlide->tipo_banner;

        }

   		return array('banner' => $banner, 'slide' => $slide);
    }

    public function otimizarBannerUnico($dir, $arquivo){
		if(!$arquivo || !file_exists($_SERVER['DOCUMENT_ROOT'].$dir.$arquivo)) return;		

        $this->load->library('Imgno_imagem', '', 'imagineImagem');

		$slide = array(
			'fullhd' => '',
			'extralarge' => '',
			'large' => '',
			'medium' => '',
			'small' => '',
			'extrasmall' => '',
		);

		$slide['fullhd'] = $this->imagineImagem->otimizar($dir.$arquivo, 1920, 0, false, true, 80);
		$slide['extralarge'] = $this->imagineImagem->otimizar($dir.$arquivo, 1440, 0, false, true, 80);
		$slide['large'] = $this->imagineImagem->otimizar($dir.$arquivo, 1200, 0, false, true, 80);
		$slide['medium'] = $this->imagineImagem->otimizar($dir.$arquivo, 992, 0, false, true, 80);
		$slide['small'] = $this->imagineImagem->otimizar($dir.$arquivo, 768, 0, false, true, 80);
		$slide['extrasmall'] = $this->imagineImagem->otimizar($dir.$arquivo, 576, 0, false, true, 80);

		return $slide;

	}
}
