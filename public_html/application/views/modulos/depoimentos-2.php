<?php 
$logo = '/arquivos/imagens/logo.png';

$controller->load->model('modulos/modulos_model_institucional', 'model_institucional');
$depoimentos = $controller->model_institucional->buscar_depoimentos();
if(!$depoimentos) return;
$dir_p = '/arquivos/imagens/depoimento/';

$controller->load->library('Imgno_imagem', '', 'imagineImagem');

$id_banner = 21;
$controller->load->model('modulos/modulos_model_banner', 'model_banner');
$info = $controller->model_banner->getSlidesUnicaImg($id_banner,26);

if(!$info) return null;

$slide = $info['slide'];
$banner = $info['banner'];
if(empty($slide['fullhd'])) return;

$cls = 'imaginebanner'.$id_banner;
$tamanhos = array(
	'fullhd' => 1920,
	'extralarge' => 1440,
	'large' => 1200,
	'medium' => 922,
	'small' => 768,
	'extrasmall' => 576,
);
$tamanhos2 = array(
	'extrasmall' => 576,
	'small' => 576,
	'medium' => 768,
	'large' => 922,
	'extralarge' => 1200,
	'fullhd' => 1440,
);

$css_tamanhos = array();
$s = 1;
?>
<div class="<?php echo $cls; ?>">
	<div class="slides">
        <span title="<?php echo $slide["titulo"];?>" class="slide slide<?php echo $s; ?>"><?php
        foreach($tamanhos as $k => $tamanho):
            if(!isset($css_tamanhos[$k])){
                $css_tamanhos[$k] = null;
            }

            if(isset($slide[$k]) && isset($banner->{$k.'_status'}) && $banner->{$k.'_status'} == 1){
                $css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
                    background-image: url("'.$slide[$k].'");
                    align-items: center;
                    display: flex;
                    min-height:'.$banner->{$k}.'px;
                }';
            }else{
                $css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
                    display:none;
                }';
            }
        endforeach; ?>

            <div class="container blocos-informacoes-slide modelo-texto-<?php echo $slide["tipo_texto"]?>">
                <div class="bloco">
                    <div class="head">
                        <h3 class="tituloPadrao textCenter"><?php echo $slide["titulo"];?></h3>
                        <?php if($slide["descricao"]):?>
                            <div class="texto headline textCenter">
                                <?php echo $slide["descricao"];?>
                            </div>
                        <?php endif;?>
                    </div> 
                    
                    <div class="depoimentos2" id="depoimentos2" style="width: 100%;">
                        <div class="btnsCaroseulContent">
                            <div class="owl-carousel">
                                    <?php foreach($depoimentos as $depoimento):
                                            if($depoimento->imagem){
                                                $img_p = $controller->imagineImagem->otimizar($dir_p.$depoimento->imagem, 100, 100, false, true, 80);
                                            }else{
                                                // logo
                                                $img_p = $controller->imagineImagem->otimizar($logo, 100, 100, false, false, 80);
                                            }
                                        ?>
                                        <div class="item">
                                            <div class="img">
                                                <img src="<?=$img_p?>" title="<?=$depoimento->nome?>" alt="<?=$depoimento->nome?>">
                                            </div>
                                            <div class="txt textoPadrao"><?=$depoimento->descricao?></div>
                                            <div class="nome"><?=$depoimento->nome?></div> 
                                        </div>
                                    <?php endforeach;?>
                            </div>
                        </div>
                    </div>





                    <?php if($slide["texto_btn"]):?>
                        <div class="botao">
                            <a href="<?php echo $slide["link"];?>" class="btn3"><?php echo $slide["texto_btn"];?></a>
                        </div>
                    <?php endif;?>

                </div>
            </div>

        </span> 
	</div>
</div>

<style type="text/css">
.<?php echo $cls; ?> .slides{
    position: relative;
}
.<?php echo $cls; ?> .slides .slide{
    width: 100%;
    background-size: cover;
    background-position: center center;
    display: block;
    /* background-color: rgba(0, 0, 0,0.6); */
    background-blend-mode: color;
}
.<?php echo $cls; ?> .slides.diagonal-left span{
    padding-top: 60px;
}
.<?php echo $cls; ?> .slides.diagonal-left::before{
    content: '';
    position: absolute;
    top: 0;
    width: 100%;
    height: 60px;
    background: #000;
    clip-path: polygon(100% 0, 0 0, 0 100%);
}
.<?php echo $cls; ?> .slides.diagonal-rigth span{
    padding-top: 60px;
}
.<?php echo $cls; ?> .slides.diagonal-rigth::before{
    content: '';
    position: absolute;
    top: 0;
    width: 100%;
    height: 60px;
    background: #000;
    clip-path: polygon(100% 0, 0 0, 100% 100%);
}
<?php
    foreach($tamanhos2 as $k =>$tamanho){
        if($k != 'extrasmall') echo '@media (min-width: '.$tamanho.'px){';	
        echo $css_tamanhos[$k];
        if($k != 'extrasmall') echo '}';	
    }
?> 
.<?php echo $cls; ?> .blocos-informacoes-slide {
    display: flex;
    padding: 60px 0;
    align-items: center;
}
.<?php echo $cls; ?> .blocos-informacoes-slide .bloco{
	font-size: 18px;
    line-height: 20px; 
    width: 100%;
}
/* modelo 0 */
.<?php echo $cls; ?> .blocos-informacoes-slide .bloco{
	color: #fff;
    margin: 0 auto; 
	/* text-align: center; */
	display: flex;
    justify-content: center;
    height: 100%;
	flex-direction: column;
    align-items: center;
} 
.<?php echo $cls; ?> .blocos-informacoes-slide .bloco .head{
    max-width: 700px;
    margin: 0 auto; 
}
.<?php echo $cls; ?> .blocos-informacoes-slide .tituloPadrao{
	color: #fff;
	padding-top: 0; 
}
.<?php echo $cls; ?> .blocos-informacoes-slide .texto{
    margin: 15px 0;
}
.<?php echo $cls; ?> .blocos-informacoes-slide .botao{
    margin-top: 30px;
}
/* modelo 1 */ 
/* modelo 2 */ 
@media(max-width:767px){
	.<?php echo $cls; ?> .blocos-informacoes-slide {
        flex-direction: column;
		gap: 20px;
		padding: 50px 0;
    }
	.<?php echo $cls; ?> .blocos-informacoes-slide .bloco{
		max-width: 100%;
	}
	
	/* modelo 1 */ 
}
</style>

<script type="text/javascript">
	// jQuery(document).ready(function() {
		
		var owlDepo2 = jQuery("#depoimentos2 .owl-carousel");
		owlDepo2.owlCarousel({
			//autoplay:true,
			//autoplayTimeout:5000,
			items : 1,
			responsive : {
				0 : { 
					items : 1,
        			margin: 20,
				},
                769 : { 
					items : 2,
        			margin: 40,
				},
				993 : { 
					items : 3,
        			margin: 80,
				},
			},
			dots:true,
			nav:false,
			loop:true,
		});
		jQuery("#depoimentos2 .btnsCaroseulContent .next").click(function(){
			owlDepo2.trigger('next.owl.carousel');
		});
		jQuery("#depoimentos2 .btnsCaroseulContent .prev").click(function(){
			owlDepo2.trigger('prev.owl.carousel');
		});
	// });			
</script>