<?php

$galeria = $controller->model->getGaleria(2);

$controller->load->library('Imgno_imagem', '', 'imagineImagem');

$dir_p = '/arquivos/imagens/galeria/';
?>
<?php if($galeria):?>

<div class="bloco-enontros" id="menu3">
	<div class="container">
		<h2 class="tituloPadrao tituloEft">Nosso espaço</h2>
		<div class="blcos-lista" id="thumbs">
				<?php 
					$kg =  0;
					foreach($galeria->fotos as $foto_extra):?>
						<?php if($kg == 0):  ?>
							<div class="bloco-tmb-1">
								<a class="item" href="<?=  $controller->imagineImagem->otimizar($dir_p.$foto_extra->imagem, 700, 500, false, true,100); ?>">
									<img src="<?=  $controller->imagineImagem->otimizar($dir_p.$foto_extra->imagem, 400, 300, false, true,100); ?>" title="" alt="">
								</a>
							</div>
						<?php elseif($kg == 1): ?>
							<div class="bloco-tmb-2">
								<a class="item" href="<?=  $controller->imagineImagem->otimizar($dir_p.$foto_extra->imagem, 700, 500, false, true,100); ?>">
									<img src="<?=  $controller->imagineImagem->otimizar($dir_p.$foto_extra->imagem, 280, 300, false, true,100); ?>" title="" alt="">
								</a>
							</div>

						<?php else:?>
							<?php if($kg == 2):?>
								<div class="bloco-tmb-3">
							<?php endif;?>
							<a class="item" href="<?=  $controller->imagineImagem->otimizar($dir_p.$foto_extra->imagem, 700, 500, false, true,100); ?>">
								<img src="<?=  $controller->imagineImagem->otimizar($dir_p.$foto_extra->imagem, 200, 145, false, true,100); ?>" title="" alt="">
							</a>
						<?php endif;?>
				<?php 
					$kg++;
					endforeach;?>
						<?php if($kg >= 2): ?>
								</div>
						<?php endif;?>
		</div>
    </div>
</div>

<script src="/arquivos/javascript/touchTouch.jquery.js"></script>
<script>
$(function(){

// Initialize the gallery
$('#thumbs a').touchTouch();

});
</script>
<style>
/* The gallery overlay */

#galleryOverlay{
	width:100%;
	height:100%;
	position:fixed;
	top:0;
	left:0;
	opacity:0;
	z-index:100000;
	background-color:#222;
	background-color:rgba(0,0,0,0.8);
	overflow:hidden;
	display:none;
	
	-moz-transition:opacity 1s ease;
	-webkit-transition:opacity 1s ease;
	transition:opacity 1s ease;
}

/* This class will trigger the animation */

#galleryOverlay.visible{
	opacity:1;
}

#gallerySlider{
	height:100%;
	
	left:0;
	top:0;
	
	width:100%;
	white-space: nowrap;
	position:absolute;
	
	-moz-transition:left 0.4s ease;
	-webkit-transition:left 0.4s ease;
	transition:left 0.4s ease;
}

#gallerySlider .placeholder{
	background: url("preloader.gif") no-repeat center center;
	height: 100%;
	line-height: 1px;
	text-align: center;
	width:100%;
	display:inline-block;
}

/* The before element moves the
 * image halfway from the top */

#gallerySlider .placeholder:before{
	content: "";
	display: inline-block;
	height: 50%;
	width: 1px;
	margin-right:-1px;
}

#gallerySlider .placeholder img{
	display: inline-block;
	max-height: 100%;
	max-width: 100%;
	vertical-align: middle;
}

#gallerySlider.rightSpring{
	-moz-animation: rightSpring 0.3s;
	-webkit-animation: rightSpring 0.3s;
}

#gallerySlider.leftSpring{
	-moz-animation: leftSpring 0.3s;
	-webkit-animation: leftSpring 0.3s;
}

/* Firefox Keyframe Animations */

@-moz-keyframes rightSpring{
	0%{		margin-left:0px;}
	50%{	margin-left:-30px;}
	100%{	margin-left:0px;}
}

@-moz-keyframes leftSpring{
	0%{		margin-left:0px;}
	50%{	margin-left:30px;}
	100%{	margin-left:0px;}
}

/* Safari and Chrome Keyframe Animations */

@-webkit-keyframes rightSpring{
	0%{		margin-left:0px;}
	50%{	margin-left:-30px;}
	100%{	margin-left:0px;}
}

@-webkit-keyframes leftSpring{
	0%{		margin-left:0px;}
	50%{	margin-left:30px;}
	100%{	margin-left:0px;}
}

/* Arrows */

#prevArrow,#nextArrow{
	border:none;
	text-decoration:none;
	background:url('/arquivos/imagens/arrows.png') no-repeat;
	opacity:0.5;
	cursor:pointer;
	position:absolute;
	width:43px;
	height:58px;
	
	top:50%;
	margin-top:-29px;
	
	-moz-transition:opacity 0.2s ease;
	-webkit-transition:opacity 0.2s ease;
	transition:opacity 0.2s ease;
}

#prevArrow:hover, #nextArrow:hover{
	opacity:1;
}

#prevArrow{
	background-position:left top;
	left:40px;
}

#nextArrow{
	background-position:right top;
	right:40px;
}


</style>
<style>
.bloco-enontros {
    padding-top: 20px;
    padding-bottom: 20px;
}

.bloco-enontros .tituloPadrao {
    margin-bottom: 25px;
}

.bloco-enontros .blcos-lista .bloco-tmb-3 a {
    display: block;
    width: 100%;
}

.bloco-enontros .blcos-lista .item {
    margin-bottom: 10px;
}

.bloco-enontros .blcos-lista > div {
    margin: 0px 10px;
}
.bloco-enontros .blcos-lista {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
}

@media(max-width:767px){
	.bloco-enontros .blcos-lista > div {
		width: 100%;
		margin-bottom: 13px;
	}

	.bloco-enontros .blcos-lista .item img {
		width: 100%;
	}

	.bloco-enontros .blcos-lista .bloco-tmb-3 {
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
	}

	.bloco-enontros .blcos-lista .bloco-tmb-3 a {
		width: 150px;
		margin: 6px 5px;
	}
}
</style>

<?php endif;?>