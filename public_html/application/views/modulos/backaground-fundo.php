<?php 
$id_banner = 20;
$controller->load->model('modulos/modulos_model_banner', 'model_banner');
$info = $controller->model_banner->getSlidesUnicaImg($id_banner,20);
// $info = $controller->model_banner->getSlides($id_banner);

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
	<div class="slides diagonal-left">
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
                    <h3 class="tituloPadrao textCenter"><?php echo $slide["titulo"];?></h3>
                    <div class="texto headline2 textCenter">
                        <?php echo $slide["descricao"];?>
                    </div>
                    <?php if($slide["texto_btn"]):?>
                    <div class="botao">
                        <a href="<?php echo $slide["link"];?>" class="btn"><?php echo $slide["texto_btn"];?></a>
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
	background-color: rgba(0, 0, 0,0.6);
    background-blend-mode: color;
    padding: 60px 0;
}
.<?php echo $cls; ?> .slides.diagonal-left span{
    position: relative;
    padding: calc(60px + var(--tamanho-reta)) 0 60px 0;
    margin: 0;
    clip-path: polygon(0 var(--tamanho-reta), 100% 0, 100% 100%, 0% 100%);
}
.<?php echo $cls; ?> .slides.diagonal-rigth span{
    position: relative;
    padding: calc(60px + var(--tamanho-reta)) 0 60px 0;
    margin: 0;
    clip-path: polygon(0 0, 100% var(--tamanho-reta), 100% 100%, 0% 100%);
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
    padding: 20px 0;
    align-items: center;
}
.<?php echo $cls; ?> .blocos-informacoes-slide .bloco{
	font-size: 18px;
    line-height: 20px; 
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
.<?php echo $cls; ?> .blocos-informacoes-slide .tituloPadrao{
	color: #fff;
	padding-top: 0;
	padding-bottom: 15px;
    margin-bottom: 15px;
}
.<?php echo $cls; ?> .blocos-informacoes-slide .botao{
    margin-top: 30px;
}

/* modelo 1 */ 

/* modelo 2 */ 
@media(max-width:768px){ 
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


