<?php
$strings_replace = $this->dados["dados"]["strings_replace"];
$configuracao_lp = $this->dados["dados"]["configuracao_lp"];
$conteudo = $this->dados["dados"]["resultado"];

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
?>
<div class="<?php echo $cls; ?> scroll-banner">
	<div class="slides owl-carousel">
    <?php
	foreach($slides as $slide):
		if($slide["texto_btn"]){
            $slide["texto_btn"] = $controller->ImgnoUtil->replace_tags($slide["texto_btn"], $strings_replace);
        }
        if($slide["descricao"]){
            $slide["descricao"] = $controller->ImgnoUtil->replace_tags($slide["descricao"], $strings_replace);
        }
	?>
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
		<?php elseif($slide['imagemBanner'] && $slide['link']): ?>
			<a <?php echo ($slide['link']?'href="'.$slide['link'].'"':''); ?> title="<?php echo $slide["titulo"];?>" class="slide slide<?php echo $s; ?>">
				<div class="container" style="height: 100%;">
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

		<?php elseif($slide['imagemBanner']): ?>
			<span title="<?php echo $slide["titulo"];?>" class="slide slide<?php echo $s; ?>">
				<div class="container" style="height: 100%;">
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

	<?php if($configuracao_lp['situacao_cta_1']):?>
		<link rel="stylesheet" href="/arquivos/css/intl-tel-input/build/css/intlTelInput.min.css">
		<script src="/arquivos/css/intl-tel-input/build/js/intlTelInput.min.js"></script>

		<form class="formCta1" id="formCta1" name="formCta1" method="post">
			<div class="container">
			
				<div class="blocos-form">
					
					<div class="blocos-form-base"></div>
					<div class="blocos-form-base-ciclo"></div>

					<?php if($configuracao_lp['titulo_cta_1']):?>
						<div class="form-txt">
							<div class="tituloBarra3"><?=$configuracao_lp['titulo_cta_1']?></div>
						</div>
					<?php endif;?>

					<div class="corpo-form">
						
						<input type="hidden" name="url" value="<?=$this->uri->uri_string();?>">
						<input type="hidden" name="id_estado_cidade" value="<?=$conteudo->id?>">
						<input type="hidden" name="nome_estado_cidade_lp" value="<?=$conteudo->nome?>">
						<input type="hidden" name="nome_estado_lp" value="<?=$conteudo->estado->nome?>">
						<input type="hidden" name="id_configuracao" value="<?=$configuracao_lp['id']?>">
						<input type="hidden" name="nome_configuracao_lp" value="<?=$configuracao_lp['Palavra-Chave-01']?>">

						<?php if($configuracao_lp['nome_situacao_cta_1']):?>
							<div class="linha <?=$configuracao_lp['nome_nomenclatura_cta_1']?'input-container linha-cta':''?>" style="order: <?=$configuracao_lp['nome_ordem_cta_1']?>;">
								<input type="text" placeholder="" name="nm" class="campo-padrao2">
								<?php if($configuracao_lp['nome_nomenclatura_cta_1']):?><label class="textoPadrao txtCampo"><?=$configuracao_lp['nome_nomenclatura_cta_1']?></label><?php endif;?>
							</div>
						<?php endif;?>

						<?php if($configuracao_lp['telefone_situacao_cta_1']):?>
							<div class="linha <?=$configuracao_lp['telefone_nomenclatura_cta_1']?'input-container':''?>" style="order: <?=$configuracao_lp['telefone_ordem_cta_1']?>;">
								<input type="text" placeholder="" name="t1" id="phone_lp_t1" class="campo-padrao2">
								<?php if($configuracao_lp['telefone_nomenclatura_cta_1']):?><label class="textoPadrao txtCampo"><?=$configuracao_lp['telefone_nomenclatura_cta_1']?></label><?php endif;?>
                                <input type="hidden" name="dialCode">
							</div>
						<?php endif;?> 

						<?php if($configuracao_lp['email_situacao_cta_1']):?>
							<div class="linha <?=$configuracao_lp['email_nomenclatura_cta_1']?'input-container linha-cta':''?>" style="order: <?=$configuracao_lp['email_ordem_cta_1']?>;">
								<input type="text" placeholder="" name="em" class="campo-padrao2">
								<?php if($configuracao_lp['email_nomenclatura_cta_1']):?><label class="textoPadrao txtCampo"><?=$configuracao_lp['email_nomenclatura_cta_1']?></label><?php endif;?>
							</div>
						<?php endif;?> 

						<?php if($configuracao_lp['aberto_situacao_cta_1']):?>
							<div class="linha <?=$configuracao_lp['aberto_nomenclatura_cta_1']?'input-container linha-cta':''?>" style="order: <?=$configuracao_lp['aberto_ordem_cta_1']?>;">
								<input type="text" placeholder="" name="abe" class="campo-padrao2">
								<?php if($configuracao_lp['aberto_nomenclatura_cta_1']):?><label class="textoPadrao txtCampo"><?=$configuracao_lp['aberto_nomenclatura_cta_1']?></label><?php endif;?>
							</div>
						<?php endif;?>   

						<div class="linha" style="order: 10;">
							<span class="btn-contato3" onclick="form_cta_1();"><?=$configuracao_lp['nomenclatura_bt_cta_1']?></span>
						</div>
					</div>
					
				</div>
			</div>		
		</form> 
		<script>
			// $(document).ready(function() {
				const input = $("#phone_lp_t1");
				const iti_lp = window.intlTelInput(input[0], {
					countrySearch: false,
					initialCountry: "br",
					nationalMode: true,
					preferredCountries: ['br', 'us'],
					utilsScript: "/arquivos/css/intl-tel-input/build/js/utils.js"
				}); 
			// });
			

			var ordens = [];
			<?php if($configuracao_lp['nome_situacao_cta_1']):?>
				ordens.push(<?=$configuracao_lp['nome_ordem_cta_1']?>)
			<?php endif;?>   
			
			<?php if($configuracao_lp['email_situacao_cta_1']):?>
				ordens.push(<?=$configuracao_lp['email_ordem_cta_1']?>)
			<?php endif;?>
			
			<?php if($configuracao_lp['telefone_situacao_cta_1']):?>
				ordens.push(<?=$configuracao_lp['telefone_ordem_cta_1']?>)
			<?php endif;?>

			<?php if($configuracao_lp['aberto_situacao_cta_1']):?>
				ordens.push(<?=$configuracao_lp['aberto_ordem_cta_1']?>)
			<?php endif;?>

			ordens.sort((a, b) => a - b);
			
			function form_cta_1(){
				var f = document.formCta1; 

				var args = [];

				<?php if($configuracao_lp['nome_situacao_cta_1']):?>
					if(vazio(f.nm.value)){
						return exibirAviso("Informe o campo <?=$configuracao_lp['nome_nomenclatura_cta_1']?>");
					}
					args.push({nome: '<?=$configuracao_lp['nome_nomenclatura_cta_1']?>', valor: f.nm.value});
				<?php endif;?>   

				<?php if($configuracao_lp['email_situacao_cta_1']):?>
					if(vazio(f.em.value)){
						return exibirAviso("Informe o campo <?=$configuracao_lp['email_nomenclatura_cta_1']?>");
					}else if(!validarEmail(f.em.value)){
						return exibirAviso("Informe seu <?=$configuracao_lp['email_nomenclatura_cta_1']?> corretamente");
					}
	
					args.push({nome: '<?=$configuracao_lp['email_nomenclatura_cta_1']?>', valor: f.em.value});
				<?php endif;?>  

				<?php if($configuracao_lp['telefone_situacao_cta_1']):?>
					const countryData = iti_lp.getSelectedCountryData();        
        			f.dialCode.value = countryData.dialCode;
        			const isValid = iti_lp.isValidNumber();

					if(vazio(f.t1.value)){
						return exibirAviso("Informe o campo <?=$configuracao_lp['telefone_nomenclatura_cta_1']?>");
					}else if(!isValid){
						return exibirAviso("Informe seu <?=$configuracao_lp['telefone_nomenclatura_cta_1']?> corretamente");
					}
					

					args.push({nome: '<?=$configuracao_lp['telefone_nomenclatura_cta_1']?>', valor: f.t1.value});
				<?php endif;?>  

				<?php if($configuracao_lp['aberto_situacao_cta_1']):?>
					if(vazio(f.abe.value)){
						return exibirAviso("Informe o campo <?=$configuracao_lp['aberto_nomenclatura_cta_1']?>");
					}
					args.push({nome: '<?=$configuracao_lp['aberto_nomenclatura_cta_1']?>', valor: f.abe.value});
				<?php endif;?>  
					 
				carregando();
				
				if(typeof imgn_cmnc_sender == 'function'){ 
					imgn_cmnc_sender(args);
				}

				var dados = jQuery(f).serialize();

				jQuery.ajax({
					type: 'POST',
					url: "ajax/salvar_solicitacao_lp",
					data: dados,
					success: function(response) {
						
						carregado(); 

						if(response == 'ok') {
							f.reset();

							exibirAviso('OBRIGADO, nós acabamos de receber sua solicitação.','ok');
						}else {
							exibirAviso('Não enviado');
							return;
						}
					}
				});  

				window.open("https://api.whatsapp.com/send?1=pt_BR&phone=55<?=preg_replace('/[^0-9]/', '', $configuracao_lp['telefone1']);?>&text=<?=urlencode($controller->ImgnoUtil->replace_tags($configuracao_lp['txt_zap_cta_1'], $strings_replace))?>", '_blank');
			}
			</script>
		<?php endif;?>


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
.".$cls." {
	position: relative;
}
.".$cls." .slides .slide{
	width: 100%;
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
    display: flex;
    height: 100%;
	gap: 10px;
	padding-top: 90px;
    padding-bottom: 225px;
}	
.textos-banner, .imagem-banner {
    width: 50%;
    margin: auto;
}
.imagem-banner img{
	width: 100%;
    height: 100%;
    max-width: 715px;
    max-height: 484px;
}
.textos-banner .titulodesc {
    color: #fff;
}
.bloco-desc-banner-2 {
    max-width: 640px;
    /* margin: 0 auto; */
    padding-top: 25px !important;
}

/* formulario */
.btn-contato3{
	padding: 11px 20px;
    width: 100%;
    background: #52B947;
    color: #fff;
    font-weight: 700;
    text-align: center;
    font-size: 17px;
    border-radius: 0;
    cursor: pointer;
    display: inline-block;
}
.formCta1 .corpo-form{
	display: flex;
	gap: 10px;
	flex-grow: 1;
	align-items: center;
}
.formCta1 .blocos-form{
	display: flex;
	background: #fff;
	padding: 30px;
	border: 2px solid #ed8534;
	position: relative;
	gap: 10px;
	align-items: center;
}
.formCta1 .blocos-form .linha{
    display: flex;
    align-items: center;
	flex-grow: 1;
}
.formCta1 .form-txt{
	width: 255px;
	margin-bottom: 0;
}
.formCta1 .tituloBarra3{    
    font-size: 30px;
    line-height: 30px;
}
.formCta1 input, .formCta1 select, .formCta1 textarea{
	background: #EAE9EA;
	border: none;
}
.formCta1{
    bottom: 50px;
    position: absolute;
    width: 100%;
    z-index: 1;
}
.formCta1 .blocos-form-base{
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
.formCta1 .blocos-form-base-ciclo {
    -webkit-animation: widgetPulseForm infinite 1.5s;
    animation: widgetPulseForm infinite 1.5s;
}
.formCta1 .blocos-form-base-ciclo {
	position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    border: 1px solid #ed8534;
	z-index: -1;
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

@media (max-width: 767px){
	.descricoes { 
		flex-direction: column;
		padding-top: 90px;
		padding-bottom: 420px;
	}
	.textos-banner, .imagem-banner {
		width: 100%;
	}
	
	.bloco-desc-banner-2 {
		max-width: 100%;
		margin-bottom: 20px;
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

@media(max-width:768px){
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

	/* Formulario */
	.formCta1 .form-txt {
		width: 100%;
		margin-bottom: 10px;
	}
	.formCta1 .blocos-form {
		flex-direction: column;
	}
	.formCta1 .corpo-form {
		flex-direction: column;
		width: 100%;
	}
	.formCta1 .blocos-form .linha{
		width: 100%;
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
	let fh = $(window).height();
	this.div.find('.slide').height(fh);
	this.div.find('.slide .bloco-desc-banner').css('padding-top',((25/100)*fh)+'px');
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
	// this.div.find('.slide').height(fh);
	// this.div.find('.bloco-desc-banner').css('padding-top',((20/100)*fh)+'px' );
}
}
</script>
<script type="text/javascript">
	// $(document).ready(function() {
		<?php if(isset($info['banner']->exibir_banner_mobile) && $info['banner']->exibir_banner_mobile == 0): ?>
			if(jQuery(window).width() >= 768){
		<?php endif; ?>
			let imgn = new ImagineBanner('<?php echo $cls; ?>' <?php foreach($tamanhos as $k => $tamanho) echo ', '.$banner->{$k}; ?>);
				<?php if($banner->tipo == 'responsivo'): ?>
					imgn.responsivo();
					$(window).resize(()=>imgn.responsivo());
				<?php elseif($banner->tipo == 'alturamaxima'): ?>
					imgn.alturamaxima();
					$(window).resize(()=>imgn.alturamaxima());
				<?php endif; ?>
			

			<?php if(count($slides)>1): ?>
			let owl2 = $(".<?php echo $cls; ?> .owl-carousel");
			owl2.owlCarousel({
				autoplay:true,
				autoplayTimeout:10000,
				autoplayHoverPause: true,
				items : 1,
				margin:0,
				dots:true,
				nav:true,
				loop:true,
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