<?php

if(!isset($id_banner)){
	$id_banner = $controller->dados["banner"]['id_banner'];
}

if(!$id_banner) return null;

$controller->load->model('modulos/modulos_model_banner', 'model_banner');
$info = $controller->model_banner->getSlides($id_banner);

if(!$info) return null;

$slides = $info['slides'];
$banner = $info['banner'];
if(!$slides) return;

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
$cta_ativo = false;
?> 

<link rel="stylesheet" href="/arquivos/css/intl-tel-input/build/css/intlTelInput.min.css">
<script src="/arquivos/css/intl-tel-input/build/js/intlTelInput.min.js"></script>

<div class="<?php echo $cls; ?> scroll-banner">
	<div class="slides owl-carousel">
    <?php
	foreach($slides as $slide):?>
		<?php if($slide['video_fullhd'] && $slide['link']): ?>
			<a <?php echo ($slide['link']?'href="'.$slide['link'].'"':''); ?> title="<?php echo $slide["titulo"];?>" class="slide slide<?php echo $s; ?>">
				<video autoplay loop muted playsinline>
					<?php if(!empty($slide['video_extrasmall'])):?>
						<source media="(max-width: 576px)" src="<?=$slide['video_extrasmall']?>" type="video/mp4">
					<?php endif;?>

					<?php if(!empty($slide['video_small'])):?>
						<source media="(max-width: 768px)" src="<?=$slide['video_small']?>" type="video/mp4">
					<?php endif;?>

					<?php if(!empty($slide['video_medium'])):?>
						<source media="(max-width: 992px)" src="<?=$slide['video_medium']?>" type="video/mp4">
					<?php endif;?>

					<?php if(!empty($slide['video_large'])):?>
						<source media="(max-width: 1200px)" src="<?=$slide['video_large']?>" type="video/mp4">
					<?php endif;?>

					<?php if(!empty($slide['video_extralarge'])):?>
						<source media="(max-width: 1440px)" src="<?=$slide['video_extralarge']?>" type="video/mp4">
					<?php endif;?> 

					<source src="<?=$slide['video_fullhd']?>" type="video/mp4">
				</video>
			</a>
			
			<?php foreach($tamanhos as $k => $tamanho):
                if(!isset($css_tamanhos[$k])){
                    $css_tamanhos[$k] = null;
                }
				if(isset($slide[$k]) && isset($banner->{$k.'_status'}) && $banner->{$k.'_status'} == 1){
					$css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
						background: transparent;
						display:block;
						height:'.$banner->{$k}.'px;
					}';
				}else{
					$css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
						display:none;
					}';
				}
			endforeach; ?>
		<?php elseif($slide['video_fullhd']): ?>
			<span title="<?php echo $slide["titulo"];?>" class="slide slide<?php echo $s; ?>">
				<video autoplay loop muted playsinline>
					
					<?php if(!empty($slide['video_extrasmall'])):?>
						<source media="(max-width: 576px)" src="<?=$slide['video_extrasmall']?>" type="video/mp4">
					<?php endif;?>

					<?php if(!empty($slide['video_small'])):?>
						<source media="(max-width: 768px)" src="<?=$slide['video_small']?>" type="video/mp4">
					<?php endif;?>

					<?php if(!empty($slide['video_medium'])):?>
						<source media="(max-width: 992px)" src="<?=$slide['video_medium']?>" type="video/mp4">
					<?php endif;?>

					<?php if(!empty($slide['video_large'])):?>
						<source media="(max-width: 1200px)" src="<?=$slide['video_large']?>" type="video/mp4">
					<?php endif;?>

					<?php if(!empty($slide['video_extralarge'])):?>
						<source media="(max-width: 1440px)" src="<?=$slide['video_extralarge']?>" type="video/mp4">
					<?php endif;?> 

					<source src="<?=$slide['video_fullhd']?>" type="video/mp4">

				</video>

				<?php if($slide["descricao"]): ?>
					<div class="bloco-desc-banner modelo-texto-<?php echo $slide["tipo_texto"]?>">
						<?php echo $slide["descricao"];?>
					</div>
				<?php endif; ?>
			</span>

			<?php foreach($tamanhos as $k => $tamanho):
                if(!isset($css_tamanhos[$k])){
                    $css_tamanhos[$k] = null;
                }
				if(isset($slide[$k]) && isset($banner->{$k.'_status'}) && $banner->{$k.'_status'} == 1){
					$css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
						background: transparent;
						display:block;
						height:'.$banner->{$k}.'px;
					}';
				}else{
					$css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
						display:none;
					}';
				}
			endforeach; ?>  
		<?php elseif($slide['imagemBanner'] && $slide['link']): $cta_ativo = true;?>
			<a <?php echo ($slide['link']?'href="'.$slide['link'].'"':''); ?> title="<?php echo $slide["titulo"];?>" class="slide slide<?php echo $s; ?> base_formCta_fundo">
				<div class="container base_inter">
					<?php if($slide["descricao"] || $slide["texto_btn"]): ?>
						<div class="descricoes">
							<div class="textos-banner">
								<?php if($slide["texto_btn"]): ?>
									<div class="tituloPadrao3 titulodesc" style="color: <?php echo $slide["colorFonte"];?>;">
										<?php echo $slide["texto_btn"]?>
									</div>
								<?php endif; ?>
								<?php if($slide["descricao"]): ?>
									<div class="headline2 bloco-desc-banner-2 modelo-texto-<?php echo $slide["tipo_texto"]?>" style="color: <?php echo $slide["colorFonte"];?>;">
										<?php echo $slide["descricao"];?>
									</div>
								<?php endif; ?> 
							</div>

							<div class="imagem-banner">
								<?php if($slide["imagemBanner"]):?>
									<img src="<?php echo $slide["imagemBanner"];?>" title="<?php echo $slide["titulo"];?>" alt="<?php echo $slide["titulo"];?>">
								<?php endif;?>
							</div>
						</div>
					<?php endif; ?>
                </div>
			</a>
			<?php foreach($tamanhos as $k => $tamanho):
                if(!isset($css_tamanhos[$k])){
                    $css_tamanhos[$k] = null;
                }
				if(isset($slide[$k]) && isset($banner->{$k.'_status'}) && $banner->{$k.'_status'} == 1){
					$css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
						display:block;
						height: 100%;
						/*height:'.$banner->{$k}.'px;*/
                        background-color:'.$slide['color'].';
					}';
				}else{
					$css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
						display:none;
					}';
				}
			endforeach; ?> 

		<?php elseif($slide['imagemBanner']): $cta_ativo = true;?>
			<span title="<?php echo $slide["titulo"];?>" class="slide slide<?php echo $s; ?> base_formCta_fundo">
				<div class="container base_inter">
					<?php if($slide["descricao"] || $slide["texto_btn"]): ?>
						<div class="descricoes">
							<div class="textos-banner">
								<?php if($slide["texto_btn"]): ?>
									<div class="tituloPadrao3 titulodesc" style="color: <?php echo $slide["colorFonte"];?>;">
										<?php echo $slide["texto_btn"]?>
									</div>
								<?php endif; ?>
								<?php if($slide["descricao"]): ?>
									<div class="headline2 bloco-desc-banner-2 modelo-texto-<?php echo $slide["tipo_texto"]?>" style="color: <?php echo $slide["colorFonte"];?>;">
										<?php echo $slide["descricao"];?>
									</div>
								<?php endif; ?> 
							</div>

							<div class="imagem-banner">
								<?php if($slide["imagemBanner"]):?>
									<img src="<?php echo $slide["imagemBanner"];?>" title="<?php echo $slide["titulo"];?>" alt="<?php echo $slide["titulo"];?>">
								<?php endif;?>
							</div>
						</div>
					<?php endif; ?>



					<?php if($slide["cta_status"]): ?>
						<form class="formCta" id="formCta" name="formCtabanner_<?=$s?>" method="post">
							<div class="blocos-form">
								
								<div class="blocos-form-base"></div>
								<div class="blocos-form-base-ciclo"></div>

								<?php if($slide['cta_titulo']):?>
									<div class="form-txt">
										<div class="tituloBarra3"><?=$slide['cta_titulo']?></div>
									</div>
								<?php endif;?>
								
								<div class="corpo-form">

									<input type="hidden" name="url" value="<?=$this->uri->uri_string();?>"> 

									<div class="linha input-container linha-cta">
										<input type="text" placeholder="" name="nm" class="campo-padrao2">
										<label class="textoPadrao txtCampo">Nome</label>
									</div>

									<div class="linha input-container">
										<input type="text" placeholder="" name="t1" id="phone_cta_banner" class="campo-padrao2 phone_cta_banner_<?=$s?>">
										<label class="textoPadrao txtCampo">Telefone/WhatsApp</label>
										<input type="hidden" name="dialCode">
									</div>

									<div class="linha input-container linha-cta" >
										<input type="text" placeholder="" name="em" class="campo-padrao2">
										<label class="textoPadrao txtCampo">E-mail</label>
									</div>

									<div class="linha" >
										<a target="_blank" class="btn" onclick="form_cta_banner(event, <?=$s?>);"><?=$slide['cta_btn_nomenclatura']?></a>
									</div>
								</div>

							</div>
						</form>  
					<?php endif; ?>
                </div>
			</span>

			<?php foreach($tamanhos as $k => $tamanho):
                if(!isset($css_tamanhos[$k])){
                    $css_tamanhos[$k] = null;
                }
				if(isset($slide[$k]) && isset($banner->{$k.'_status'}) && $banner->{$k.'_status'} == 1){
					$css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
						display:block;
						height: 100%;
						/*height:'.$banner->{$k}.'px;*/
                        background-color:'.$slide['color'].';
					}';
				}else{
					$css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
						display:none;
					}';
				}
			endforeach; ?> 
		<?php elseif($slide['link']): ?>
			<a <?php echo ($slide['link']?'href="'.$slide['link'].'"':''); ?> title="<?php echo $slide["titulo"];?>" class="slide slide<?php echo $s; ?>">
				<?php if($slide["descricao"]): ?>
					<div class="bloco-desc-banner modelo-texto-<?php echo $slide["tipo_texto"]?>">
						<?php echo $slide["descricao"];?>
						<?php if($slide["texto_btn"]): ?><span class="btn-desc-banner"><?php echo $slide["texto_btn"];?></span><?php endif; ?>
					</div>
				<?php endif; ?>
			</a>
			<?php foreach($tamanhos as $k => $tamanho):
                if(!isset($css_tamanhos[$k])){
                    $css_tamanhos[$k] = null;
                }
				if(isset($slide[$k]) && isset($banner->{$k.'_status'}) && $banner->{$k.'_status'} == 1){
					$css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
						background-image: url("'.$slide[$k].'");
						display:block;
						height:'.$banner->{$k}.'px;
					}';
				}else{
					$css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
						display:none;
					}';
				}
			endforeach; ?>
		<?php else: ?>
			<span title="<?php echo $slide["titulo"];?>" class="slide slide<?php echo $s; ?>">
				<?php if($slide["descricao"]): ?>
					<div class="bloco-desc-banner modelo-texto-<?php echo $slide["tipo_texto"]?>">
						<?php echo $slide["descricao"];?>
					</div>
				<?php endif; ?>
			</span>

			<?php foreach($tamanhos as $k => $tamanho):
                if(!isset($css_tamanhos[$k])){
                    $css_tamanhos[$k] = null;
                }
				if(isset($slide[$k]) && isset($banner->{$k.'_status'}) && $banner->{$k.'_status'} == 1){
					$css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
						background-image: url("'.$slide[$k].'");
						display:block;
						height:'.$banner->{$k}.'px;
					}';
				}else{
					$css_tamanhos[$k] .= '.'.$cls.' .slides .slide'.$s.'{
						display:none;
					}';
				}
			endforeach; ?>
		<?php endif; ?>

		<?php
		$s++;
    endforeach; ?>
	</div>
</div>

<style type="text/css"><?php
$style_css_b = "";
foreach($tamanhos2 as $k =>$tamanho){
	if($k != 'extrasmall'){
		$style_css_b  .= "@media (min-width: ".$tamanho."px){".$css_tamanhos[$k]."}";
	}else{
		$style_css_b  .= "@media (max-width: ".($tamanho)."px){".$css_tamanhos[$k]."}";
	}
}

$style_css_b .= "
.".$cls." .slides .slide{
	width: 100%;
	/*height: 100%;*/
	background-size: cover;
	background-position: center center;
	display: block;
}
.slides .slide video{
	width: 100%;
    object-fit: cover;
    height: 100%;
}
/* slide imagem e com de fundo*/ 
.descricoes {
	margin-top: 90px;
    display: flex;
    /*height: 100%;*/
	gap: 10px;
}	
.textos-banner, .imagem-banner {
    width: 50%;
    margin: auto;
}
.textos-banner .titulodesc {
    color: #fff;
}
.bloco-desc-banner-2 {
    max-width: 640px;
    padding-top: 25px !important;
}
/* formulario */
/*.slides .slide.base_formCta_fundo{
    display: flex;
    justify-content: center;
    align-items: center;
}*/
.slides .slide.base_formCta_fundo .base_inter{
	height: 100%;
	display: flex;
	flex-direction: column;
	justify-content: center; 
	gap: 30px; 
	padding: 30px 0;
}
.slides .slide .formCta .corpo-form{
	display: flex;
	gap: 10px;
	flex-grow: 1;
	align-items: center;
}
.slides .slide .formCta .blocos-form{
	display: flex;
	background: #fff;
	padding: 30px;
	border: 2px solid #ed8534;
	position: relative;
	gap: 10px;
	align-items: center;
}
.slides .slide .formCta .blocos-form .linha{
    display: flex;
    align-items: center;
	flex-grow: 1;
}
/*.slides .slide .formCta .blocos-form .input-container label{
	top: 19px;
}*/
.slides .slide .formCta .form-txt{
	width: 255px;
	margin-bottom: 0;
}
.slides .slide .formCta .tituloBarra3{    
    font-size: 30px;
    line-height: 30px;
}
.slides .slide .formCta input, .slides .slide .formCta select, .slides .slide .formCta textarea{
	background: #EAE9EA;
	border: none;
}
.slides .slide .formCta{
    /* marbottom: 50px; */
    width: 100%;
	position: relative;
    z-index: 1;
}
.slides .slide .formCta .blocos-form-base{
	position: absolute;
    top: -8px;
    bottom: -8px;
    left: -8px;
    right: -8px;
    -webkit-width: calc(100% + 16px);
    width: calc(100% + 16px);
	height: calc(100% + 16px);
    background: #ed8534;
    opacity: .2;
	z-index: -1;
}
.slides .slide .formCta .blocos-form-base-ciclo {
    -webkit-animation: widgetPulseForm infinite 1.5s;
    animation: widgetPulseForm infinite 1.5s;
}
.slides .slide .formCta .blocos-form-base-ciclo {
	position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    border: 1px solid #ed8534;
	z-index: -1;
}
.slides .slide .formCta .btn{
    max-width: 100%;
    width: 100%;
    padding: 12px 20px;
    border-radius: 0;
}
@keyframes widgetPulseForm {
	50% {
		-webkit-transform: scale(1,1);
		transform: scale(1,1);
		opacity: 1;
	}

	100% {
		-webkit-transform: scale(1.1,1.5);
		transform: scale(1.1,1.5);
		opacity: 0;
	}
}
@media (max-width: 768px){
	.descricoes { 
		margin-top: 50px;
		flex-direction: column;
	}
	.textos-banner, .imagem-banner {
		width: 100%;
	}
	
	.bloco-desc-banner-2 {
		max-width: 100%;
		margin-bottom: 20px;
	}
	
	/* formulario */
	.slides .slide .formCta .form-txt {
		width: 100%;
		margin-bottom: 10px;
	}
	.slides .slide .formCta .blocos-form {
		flex-direction: column;
	}
	.slides .slide .formCta .corpo-form {
		flex-direction: column;
		width: 100%;
	}
	.slides .slide .formCta .blocos-form .linha{
		width: 100%;
	}
}
/* modelo 0 */
.bloco-desc-banner {
    color: #fff;
    max-width: 640px;
    margin: 0 auto;
    padding-top: 10%;
}

.bloco-desc-banner .txt1 {
	font-size: 22px;
	margin-bottom: 10px;
}

.bloco-desc-banner .txt2 {
    font-size: 3.8em;
    font-weight: bold;
	text-transform: uppercase;
	line-height: 1em;
	margin-bottom: 10px;
}

.bloco-desc-banner .txt3 {
	font-size: 16px;
	margin-bottom: 10px;
}
.bloco-desc-banner .btn-desc-banner {
    color: #fa5044;
    font-size: 13px;
    display: block;
    width: 140px;
    text-align: center;
    text-transform: uppercase;
	font-family: 'Zilla Slab';
	font-weight: bold;
    cursor: pointer;
    border: 1px solid #fff;
    border-radius: 7px;
    padding: 7px 0px;
    margin-top: 15px;
}

/* modelo 1 */
.bloco-desc-banner.modelo-texto-1 {
    text-align: center;
}

.bloco-desc-banner.modelo-texto-1 .btn-desc-banner {
    margin: 0 auto;
    width: 300px;
    color: #fff;
    background: #fa5044;
    border: none;
}

/* modelo 2 */
.bloco-desc-banner.modelo-texto-2 {
    padding-top: 3% !important;
    text-align: center;
}

.bloco-desc-banner.modelo-texto-2 .btn-desc-banner {
    color: #fff;
    position: absolute;
    bottom: 8%;
    left: 0;
    right: 0;
    margin: 0 auto;
}

@media(max-width:767px){
	.bloco-desc-banner {
		max-width: 315px;
	}
	.bloco-desc-banner .txt1 {
		font-size: 10px;
		margin-bottom: 2px;
	}
	.bloco-desc-banner .txt2 {
		font-size: 10px;
		margin-bottom: 2px;
	}
	.bloco-desc-banner .txt3 {
		font-size: 10px;
		margin-bottom: 2px;
	}

	.bloco-desc-banner .btn-desc-banner {
		font-size: 8px;
		width: 70px;
		margin-top: 4px;
		padding: 3px 0px;
	}

	/* modelo 1 */
	.bloco-desc-banner.modelo-texto-1 .btn-desc-banner {
		width: 160px;
	}
}
";
echo $controller->comprimir_css($style_css_b);?></style>

<script>
class ImagineBanner{
constructor(div, fullhd, extralarge, large, medium, small, extrasmall){
	this.div = $('.'+div);
	this.fullhd = fullhd;
	this.extralarge = extralarge;
	this.large = large;
	this.medium = medium;
	this.small = small;
	this.extrasmall = extrasmall;
	this.div.find('.slide').map((a,b)=>{ if($(b).css('display') == 'none'){ $(b).remove(); } });
}
alturamaxima(){
	let htopo = 0;
	let fh = $(window).height();
	this.div.find('.slide').height(fh);
	// this.div.find('.slide').css('min-height', fh);
	this.div.find('.slide .bloco-desc-banner').css('padding-top',((25/100)*fh)+'px');
}
alturamaximaCTA(){
	let htopo = 0;
	let fh = $(window).height();
	
	var maxHeight = 0;
	this.div.find('.slide').each(function() {
		var slideHeight = $(this).height();
		if (slideHeight > maxHeight) {
			maxHeight = slideHeight;
		}
	});

	this.div.find('.slide').height(fh);
	this.div.find('.slide').css('min-height', maxHeight);
	this.div.find('.slide .bloco-desc-banner').css('padding-top',((25/100)*fh)+'px');
}
fixo(){
	let htopo = $('.modulo-site-topo').height();
	// this.div.find('.slide .bloco-desc-banner').css('padding-top',(htopo+9)+'px');
}
responsivo(){
	let fw = $(window).width(); let h = this.extrasmall; let w = 576;
	if(fw > 576){
		h = this.small; w = 768;
	}
	if(w >= 768 ){
		h = this.medium; w = 922;
	}
	if(w >= 922){
		h = this.large; w = 1200;
	}
	if(w >= 1200){
		h = this.extralarge; w = 1440;
	}
	if(w >= 1440){
		h = this.fullhd; w = 1920;
	}
	let fh = (fw * h) / w;
	this.div.find('.slide').height(fh);
	this.div.find('.bloco-desc-banner').css('padding-top',((20/100)*fh)+'px' );
}
}
</script>
<script type="text/javascript">
	
	// $(document).ready(function() {
		<?php if(isset($info['banner']->exibir_banner_mobile) && $info['banner']->exibir_banner_mobile == 0): ?>
			if(jQuery(window).width() >= 768){
		<?php endif; ?>
			let imgn = new ImagineBanner('<?php echo $cls; ?>' <?php foreach($tamanhos as $k => $tamanho) echo ', '.$banner->{$k}; ?>);
			// if($(window).width() >= 768){
				<?php if($banner->tipo == 'responsivo'): ?>
					imgn.responsivo();
					$(window).resize(()=>imgn.responsivo());
				<?php elseif($banner->tipo == 'alturamaxima' && $cta_ativo): ?>
					imgn.alturamaximaCTA();
					$(window).resize(()=>imgn.alturamaximaCTA());
				<?php elseif($banner->tipo == 'alturamaxima'): ?>
					imgn.alturamaxima();
					$(window).resize(()=>imgn.alturamaxima());
				<?php elseif($banner->tipo == 'fixo'): ?>
					imgn.fixo();
				<?php endif; ?>
			/*}else{
				imgn.responsivo();
			}*/

			<?php if(count($slides)>1): ?>
			let owl2_b = $(".<?php echo $cls; ?> .owl-carousel");
			owl2_b.owlCarousel({
				autoplay: true,
				autoplayTimeout:10000,
				autoplayHoverPause: true,
				items : 1,
				margin:0,
				dots:true,
				nav:true,
				loop:true,
			}); 

			owl2_b.on('translated.owl.carousel', function () {
				var input_b = $(".<?php echo $cls; ?> .active #phone_cta_banner")[0];
				window['iti_cta_banner'] = window.intlTelInput(input_b, {
					countrySearch: false,
					initialCountry: "br",
					nationalMode: true,
					preferredCountries: ['br', 'us'],
					utilsScript: "/arquivos/css/intl-tel-input/build/js/utils.js"
				}); 

				// lib iti: mover label abaixo do campo
				$('.<?php echo $cls; ?> .input-container .iti--allow-dropdown').each(function(){
					$(this).append($(this).next('label'));
				})  
			});
			
			owl2_b.trigger('translated.owl.carousel');

			<?php else: ?>

				var input_b = $(".<?php echo $cls; ?> #phone_cta_banner")[0];
				window['iti_cta_banner'] = window.intlTelInput(input_b, {
					countrySearch: false,
					initialCountry: "br",
					nationalMode: true,
					preferredCountries: ['br', 'us'],
					utilsScript: "/arquivos/css/intl-tel-input/build/js/utils.js"
				}); 

			<?php endif; ?>
			jQuery('.scroll-banner').css('display','block');
		<?php if(isset($info['banner']->exibir_banner_mobile) && $info['banner']->exibir_banner_mobile == 0): ?>
			}else{
				jQuery('.scroll-banner').css('display','none');
				jQuery('.scroll-banner').html('');
			}
		<?php endif; ?>
	// });
</script>

<script>
	function form_cta_banner(event, tipo){ 
		<?php if(count($slides)>1): ?>
			var f = document.querySelectorAll('.<?php echo $cls; ?> .active')[0].querySelectorAll('[name="formCtabanner_' + tipo + '"]')[0];
		<?php else: ?>
			var f = document.getElementsByName('formCtabanner_' + tipo)[0];
		<?php endif; ?>


        if(vazio(f.nm.value)){
            return exibirAviso("Por favor, informe seu nome");
        }
        
        if(vazio(f.t1.value)){
            return exibirAviso("Por favor, informe seu telefone");
        }
       
        // if(!validarTelefone(f.t1.value)){
        //     return exibirAviso("Por favor, informe seu telefone corretamente");
        // } 
		const countryData = window['iti_cta_banner'].getSelectedCountryData();      

        f.dialCode.value = countryData.dialCode;
        const isValid = window['iti_cta_banner'].isValidNumber();
        if(!isValid){
            return exibirAviso("Por favor, informe seu telefone corretamente");
        }   

        if(!validarEmail(f.em.value)){
            return exibirAviso("Por favor, informe seu email corretamente");
        }
        
        carregando();
        if(typeof imgn_cmnc_sender == 'function'){
            var args = [
                {nome:'nome',valor:f.nm.value},
                {nome:'email',valor:f.em.value},
                {nome:'telefone',valor:f.t1.value},
            ];
            imgn_cmnc_sender(args);
        }

		var dados = jQuery(f).serialize();

        jQuery.ajax({
            type: 'POST',
            url: "/ajax/salvar_solicitacao",
            data: dados,
            success: function(response) {
                console.log(response)
                carregado(); 

                if(response == 'ok') {
					f.reset();
					exibirAviso('OBRIGADO, nós acabamos de receber sua solicitação.','ok');
				}
                else {
					exibirAviso('Não enviado');
					return;
				}
            }
        });

    }
</script>