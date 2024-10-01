<?php
$controller->load->model('modulos/modulos_model_config', 'model_config');
$config = $controller->model_config->buscar_config();

$resultado = $this->dados["dados"]["resultado"];
$configuracao_lp = $this->dados["dados"]["configuracao_lp"];
$strings_replace = $this->dados["dados"]["strings_replace"];

$dir_lp_glr ='/arquivos/imagens/LP/galeria/';
$dir_lp ='/arquivos/imagens/LP/';

// Copywriter
$dir_lp_cp ='/arquivos/imagens/LP/copywriter/';
$config_copy = $this->dados["dados"]["config_copy"];
$copywriters = $this->dados["dados"]["copywriters"];

?>

<h1 class="tituloPadrao textCenter"><?=$controller->ImgnoUtil->replace_tags("[Palavra-Chave-01] em [Cidade]", $strings_replace)?></h1>

<?php if($configuracao_lp['imagens']):?>
<div class="galeria-lp">
	<div class="" id="imagineGaleriaLP">
        <div class="tituloPadrao textCenter"><?=$controller->ImgnoUtil->replace_tags($configuracao_lp['Titulo-Foto'], $strings_replace)?></div>
		<!-- <div class="headline2 textCenter"></div> -->
		<div class="btnsCaroseulContent">
			<div class="owl-carousel">
                <?php foreach($configuracao_lp['imagens'] as $foto): ;?>
					<div class="item">
						<img src="<?=$controller->imagineImagem->otimizar($dir_lp_glr.$foto['imagem'], 230, 130, false, false, 80); ?>" title="<?=$controller->ImgnoUtil->replace_tags("[Palavra-Chave-01] em [Cidade] ".$foto['imagem'], $strings_replace)?>" alt="<?=$controller->ImgnoUtil->replace_tags("[Palavra-Chave-01] em [Cidade] ".$foto['imagem'], $strings_replace)?>">
					</div>
				<?php endforeach;?>
			</div>
		</div>
	</div>
</div>
<style>
	#imagineGaleriaLP .owl-carousel .owl-item{
		transition: opacity 3000ms linear;
	}
	#imagineGaleriaLP .owl-carousel .owl-item:not(.active) {
        opacity: 0;
    }
	#imagineGaleriaLP .owl-carousel .owl-item.active{
        opacity: 1;
	}
	#imagineGaleriaLP .owl-carousel .owl-item.opaque {
        opacity: 0.1; 
    }
    #imagineGaleriaLP .owl-carousel .owl-item.penultimate {
        opacity: 0.4; 
    }
	#imagineGaleriaLP .owl-carousel .owl-item.antipenultimate {
        opacity: 0.8; 
    }
</style> 
<script type="text/javascript">
	jQuery(document).ready(function() {
		
		var owl2Cliente = jQuery("#imagineGaleriaLP .owl-carousel");
		owl2Cliente.owlCarousel({
			autoplay:true,
			autoplayTimeout: 3000,
			smartSpeed: 3000,
    		slideTransition: 'linear',
			items : 1,
			responsive : {
				0 : { items : 5 },
				769 : { items : 7},
				993 : { items : 9 },
				1200 : { items : 11 },
			},
			nav:false,
			dots:false,
			margin: 0,
			loop:true,
			mouseDrag: false, 
            // onInitialized: callback,
            // onTranslated: callback,
            // onChange: callback,
		});
		
		owl2Cliente.trigger('next.owl.carousel');

		jQuery("#imagineGaleriaLP .btnsCaroseulContent .next").click(function(){
			owl2Cliente.trigger('next.owl.carousel');
		});
		jQuery("#imagineGaleriaLP .btnsCaroseulContent .prev").click(function(){
			owl2Cliente.trigger('prev.owl.carousel');
		});  

		// function callback(event) {
        owl2Cliente.on('translate.owl.carousel next.owl.carousel', function(event) {
			setTimeout(function() {
				var $items = $(event.target).find('.owl-item');
				$items.removeClass('opaque penultimate antipenultimate');
				
				// Get the visible items
				var visibleItems = $(event.target).find('.owl-item.active');
				
				// primeiros
				visibleItems.last().addClass('opaque');
				visibleItems.first().addClass('opaque');
				
				// penultimate
				visibleItems.eq(-2).addClass('penultimate');
				visibleItems.eq(1).addClass('penultimate');

				// antipenultimate
				visibleItems.eq(-3).addClass('antipenultimate');
				visibleItems.eq(2).addClass('antipenultimate');
			}, 0); 

        // } 
		});	 
	});	 
</script>
<?php endif;?>

<?php if($copywriters):?>
    <?php foreach($copywriters as $copywriter):
            $id = $copywriter['id'];

            // background color do bloco
            if($copywriter['background_color_ativado']){
                $background_color = "background-color:".$copywriter['background_color'].";";
            }else if($config_copy['background_color_ativ']){
                $background_color = "background-color:".$config_copy['background_color'].";";
            }else{
                $background_color = "";
            }

            // classe do titulo
            if($copywriter['font_family_titulo']){
                $classe_titulo = $copywriter['font_family_titulo'];
            }else if($config_copy['font_family_titulo']){
                $classe_titulo = $config_copy['font_family_titulo'];
            }else{
                $classe_titulo = "";
            }

            // Cor do titulo
            if($copywriter['font_color_titulo_ativ']){
                $cor_titulo = "color:".$copywriter['font_color_titulo'].";";
            }else if($config_copy['font_color_titulo_ativ']){
                $cor_titulo = "color:".$config_copy['font_color_titulo'].";";
            }else{
                $cor_titulo = "";
            }

            // classe do headline
            if($copywriter['font_family_titulo']){
                $classe_headline = $copywriter['font_family_headline'];
            }else if($config_copy['font_family_titulo']){
                $classe_headline = $config_copy['font_family_headline'];
            }else{
                $classe_headline = "";
            }

            // Cor do headline
            if($copywriter['font_color_headline_ativ']){
                $cor_headline = "color:".$copywriter['font_color_headline'].";";
            }else if($config_copy['font_color_headline_ativ']){
                $cor_headline = "color:".$config_copy['font_color_headline'].";";
            }else{
                $cor_headline = "";
            }

            // Cor do headline
            if($copywriter['tipo_corte']){
                $tipo_corte = 'corte-'.$copywriter['tipo_corte'];
            }else{
                $tipo_corte = "";
            }
        ?>

        <div class="copywriters-lp copywriter-blocos <?=(!$tipo_corte && $background_color?'back ':($tipo_corte?$tipo_corte:' margin '))?>" style="<?=($background_color?$background_color:'')?>" id="copywriter-<?=$id?>">
            <div class="container">
                <div class="itens">
                    <div class="item texts <?=$copywriter['pos_tex']?"direito":"esquerdo"?>">
                        <?php if($copywriter['titulo']): 
                            $copywriter['titulo'] = $controller->ImgnoUtil->replace_tags($copywriter['titulo'], $strings_replace);?>
                            <h3 class="<?=$classe_titulo?>" style="<?=$cor_titulo?>"><?=$copywriter['titulo']?></h3>
                        <?php endif;?>
                        <?php if($copywriter['headline']):
                            $copywriter['headline'] = $controller->ImgnoUtil->replace_tags($copywriter['headline'], $strings_replace);?>
                            <div class="<?=$classe_headline?> subtitulo" style="<?=$cor_headline?>"><?=$copywriter['headline']?></div>
                        <?php endif;?>
                        <?php if($copywriter['descricao']):
                            $copywriter['descricao'] = $controller->ImgnoUtil->replace_tags($copywriter['descricao'], $strings_replace);?>
                            <div class="textoPadrao descricao"><?=$copywriter['descricao']?></div>
                        <?php endif;?>
                        <?php if($copywriter['text_botao'] && $copywriter['link']):
                            $copywriter['text_botao'] = $controller->ImgnoUtil->replace_tags($copywriter['text_botao'], $strings_replace);?>
                            <div class="botao">
                                <a href="<?=$copywriter['link']?>" class="btn" target="_blank" title="<?=$copywriter['text_botao']?>"><?=$copywriter['text_botao']?></a>
                            </div>
                        <?php elseif($copywriter['text_botao']):
                            $copywriter['text_botao'] = $controller->ImgnoUtil->replace_tags($copywriter['text_botao'], $strings_replace);?>
                            <div class="botao">
                                <a href="https://api.whatsapp.com/send?1=pt_BR&phone=55<?=preg_replace('/[^0-9]/', '', $config['telefone1']);?>&text=Olá, Vim do site e gostaria de mais informações" class="btn" target="_blank" title="<?=$copywriter['text_botao']?>"><?=$copywriter['text_botao']?></a>
                            </div>
                        <?php endif;?>
                    </div>
                    <div class="item imagem">
                        <?php if($copywriter['imagem']):?>
                            <img src="<?=$controller->imagineImagem->otimizar($dir_lp_cp.$copywriter['imagem'], 700, $copywriter['altura'], false, true, 80); ?>" title="<?=$copywriter['text_botao']?>" alt="<?=$copywriter['text_botao']?>">
                        <?php elseif($copywriter['link_yt']):?>

                            <iframe <?=!empty($copywriter['altura'])?'height="'.$copywriter['altura'].'"':''?> title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
                            
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>

        <?php if(!$copywriter['imagem'] && $copywriter['link_yt']):?>
            <script type="text/javascript">

                var ctdr_copy_<?=$id?> = false;
                        
                $(window).scroll(function(){
                    if(!ctdr_copy_<?=$id?>){
                        var p_copy_<?=$id?> = $('.copywriters-lp#copywriter-<?=$id?>').offset().top;
                        var s_copy_<?=$id?> = $(window).scrollTop();
                        var h_copy_<?=$id?> = jQuery(window).height();

                        if(s_copy_<?=$id?> >= (p_copy_<?=$id?> - h_copy_<?=$id?>)){
                            ctdr_copy_<?=$id?> = true;
                            $(".copywriters-lp#copywriter-<?=$id?> .imagem iframe").attr('src','<?=$controller->ImgnoUtil->getYoutubeEmbedUrl($copywriter['link_yt'])?>');
                        }
                    }				
                });	
            </script>
        <?php endif;?>

    <?php endforeach;?>
<?php endif;?> 

<div class="bloco-moduloLP2">
    <div class="container"> 
        <?php if($configuracao_lp["bigNumber"]): ?>
            <div class="blocosEm3" id="bigNumber">
                <?php foreach($configuracao_lp["bigNumber"] as $key => $bigNumber):?>
                    <div class="bloc bloc<?=$key?>">
                        <p>
                            <?php if($bigNumber['classFontello']):?><em class="<?=$bigNumber['classFontello']?>"></em><?php endif;?>
                                <?=$bigNumber['prefixo']?><span class="numero" inicio="0" fim="<?=preg_replace('/[^0-9]/', '', $bigNumber['numero'])?>"><?=$bigNumber['numero']?></span><?=$bigNumber['sufixo']?><br>
                            <span  class="textoAbaixo"><?=$bigNumber['texto']?></span>
                        </p>
                    </div>
                <?php endforeach;?>
            </div>


            <script type="text/javascript"> 

                var ctdr = false;
                jQuery(window).scroll(function(){
                    if(!ctdr){
                        var p = jQuery('#bigNumber').offset().top;
                        var s = jQuery(window).scrollTop();
                        var h = jQuery(window).height();
                        
                        
                        if(s >= (p - h + 15)){
                            ctdr = true;
                            jQuery('#bigNumber .bloc .numero').each(function(){
                                var start = jQuery(this).attr('inicio');
                                var end = jQuery(this).attr('fim');
                                jQuery(this).animate( {count:end} , {
                                    duration: 2000,
                                    easing: 'swing',
                                    step: function (a,b) {
                                        jQuery(this).text(number_format(Math.ceil(a), 0, ',', '.'));
                                    }
                                });
                            });
                        }
                    }				
                });

            </script>
        <?php endif;?>
    </div>
</div>

<script>
    $(".bloco-moduloLP4 .blocos .prt2 .item .titulo").on('click', function(){
        if($(this).hasClass("ativado")) return;

        $('.bloco-moduloLP4 .blocos .prt2 .item p').slideUp();
        $('.bloco-moduloLP4 .blocos .prt2 .item .titulo').removeClass("ativado")

        $(this).addClass("ativado")
        $(this).next().slideDown(); 
    })
</script>
<div class="tags_seo">
    <?php if($configuracao_lp['Palavra-Chave-02']):?>
        <h2><?=$controller->ImgnoUtil->replace_tags("[Palavra-Chave-02] em [Cidade]", $strings_replace)?></h2>
    <?php endif;?>
    <?php if($configuracao_lp['Palavra-Chave-03']):?>
        <h2><?=$controller->ImgnoUtil->replace_tags("[Palavra-Chave-03] em [Cidade]", $strings_replace)?></h2>
    <?php endif;?>
    <?php if($configuracao_lp['Palavra-Chave-04']):?>
        <h2><?=$controller->ImgnoUtil->replace_tags("[Palavra-Chave-04] em [Cidade]", $strings_replace)?></h2>
    <?php endif;?>
    <?php if($configuracao_lp['Palavra-Chave-05']):?>
        <h2><?=$controller->ImgnoUtil->replace_tags("[Palavra-Chave-05] em [Cidade]", $strings_replace)?></h2>
    <?php endif;?>
    <?php if($configuracao_lp['Palavra-Chave-06']):?>
        <h2><?=$controller->ImgnoUtil->replace_tags("[Palavra-Chave-06] em [Cidade]", $strings_replace)?></h2>
    <?php endif;?>
    <?php if($configuracao_lp['Palavra-Chave-07']):?>
        <h2><?=$controller->ImgnoUtil->replace_tags("[Palavra-Chave-07] em [Cidade]", $strings_replace)?></h2>
    <?php endif;?>
    <?php if($configuracao_lp['Palavra-Chave-08']):?>
        <h2><?=$controller->ImgnoUtil->replace_tags("[Palavra-Chave-08] em [Cidade]", $strings_replace)?></h2>
    <?php endif;?>
</div>
