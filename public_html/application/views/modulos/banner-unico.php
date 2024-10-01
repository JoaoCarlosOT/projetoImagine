<?php
$endereco_completo = $this->dados['banner']['endereco_completo'];
if(!$endereco_completo) return;

$controller->load->model('modulos/modulos_model_banner', 'model_banner');
$banners = $controller->model_banner->otimizarBannerUnico('', $endereco_completo);
if(!$banners) return null;

$destino = $this->dados["dados"]["resultado"];

if(!empty($this->dados["banner"]["tamanhos"])){
	$altura = $this->dados["banner"]["tamanhos"];
}else{
	$altura = array(
		'fullhd' => 450,
		'extralarge' => 450,
		'large' => 350,
		'medium' => 350,
		'small' => 200,
		'extrasmall' => 200,
	);
}

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

?>

<div class="slides-unico">
    <span class="slide-unico">
        <?php 
            foreach($tamanhos as $k => $tamanho):
                if(!isset($css_tamanhos[$k])){
                    $css_tamanhos[$k] = null;
                }

                $css_tamanhos[$k] .= '.slides-unico .slide-unico{
                    background-image: url("'.$banners[$k].'");
                    display: block;
                    height: '.$altura[$k].'px;
                }';

            endforeach;
        ?>
    </span>
</div>

<style type="text/css">
/*topo flutuante*/
.modulo-site-topo {
    position: absolute;
    z-index: 2;
    width: 100%;
}
/*Banner*/
.slides-unico .slide-unico{
	width: 100%;
	background-size: cover;
	background-position: center center;
	display: block;
    position: relative;
}
<?php

foreach($tamanhos2 as $k =>$tamanho){
	if($k != 'extrasmall') echo '@media (min-width: '.$tamanho.'px){';	
	echo $css_tamanhos[$k];
	if($k != 'extrasmall') echo '}';	
}
?>

</style>