<?php
$controller->load->model('site/Site_model_paginas', 'model_site');
$galeria = $controller->model_site->getGaleria(4);
if(empty($galeria->fotos)) return;

$controller->load->library('Imgno_imagem', '', 'imagineImagem');

$dir_p = '/arquivos/imagens/galeria/';
?> 

<div class="galeria-1">
	<div class="" id="imagineGaleria1">
		<h2 class="tituloPadrao textCenter"><?=$galeria->nome?></h2>
		<?php if($galeria->headline):?><div class="headline2 textCenter"><?=$galeria->headline?></div><?php endif;?>
		<div class="btnsCaroseulContent">
			<div class="owl-carousel">
				<?php foreach($galeria->fotos as $foto_extra):?>
					<div class="item">
						<img src="<?=$controller->imagineImagem->otimizar($dir_p.$foto_extra->imagem, 500, 174, true, false, 80); ?>" title="" alt="" class="img_galeria">
					</div>
				<?php endforeach;?>
			</div>
		</div>
	</div>
</div>
<style>
	#imagineGaleria1 .owl-carousel .owl-item{
		transition: opacity 3000ms linear;
	}
	#imagineGaleria1 .owl-carousel .owl-item:not(.active) {
        opacity: 0;
    }
	#imagineGaleria1 .owl-carousel .owl-item.active{
        opacity: 1;
	}
	#imagineGaleria1 .owl-carousel .owl-item.opaque {
        opacity: 0.1; 
    }
    #imagineGaleria1 .owl-carousel .owl-item.penultimate {
        opacity: 0.4; 
    }
	#imagineGaleria1 .owl-carousel .owl-item.antipenultimate {
        opacity: 0.8; 
    }
</style> 
<script type="text/javascript">
	jQuery(document).ready(function() {
		
		var owl2GLR1 = jQuery("#imagineGaleria1 .owl-carousel");
		owl2GLR1.owlCarousel({
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
		
		owl2GLR1.trigger('next.owl.carousel');

		jQuery("#imagineGaleria1 .btnsCaroseulContent .next").click(function(){
			owl2GLR1.trigger('next.owl.carousel');
		});
		jQuery("#imagineGaleria1 .btnsCaroseulContent .prev").click(function(){
			owl2GLR1.trigger('prev.owl.carousel');
		});  

		// function callback(event) {
		owl2GLR1.on('translate.owl.carousel next.owl.carousel', function(event) {
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

<style>
	.img_galeria{
		padding: 20px;
		width: 300px;
	}
	
</style>