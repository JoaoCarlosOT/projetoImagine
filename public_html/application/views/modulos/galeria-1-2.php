<?php

$galeria = $controller->model->getGaleria(2);

$controller->load->library('Imgno_imagem', '', 'imagineImagem');

$dir_p = '/arquivos/imagens/galeria/';
?>
<?php if($galeria):?>
<style>
.imagineGaleriaF2 .tituloPadrao {
    margin-bottom: 10px;
}
.imagineGaleriaF2 {
    padding-top: 30px;
    padding-bottom: 30px;
}
</style>

<div class="container imagineGaleriaF2" id="imagineGaleriaCS2">
	<h2 class="tituloPadrao textCenter">Projetos já executados</h2>
	<div class="btnsCaroseulContent">
		<div class="owl-carousel">
				<?php foreach($galeria->fotos as $foto_extra):?>
					<div class="item">
						<img src="<?=  $controller->imagineImagem->otimizar($dir_p.$foto_extra->imagem, 250, 150, false, false,100); ?>" title="" alt="">
					</div>
				<?php endforeach;?>
		</div>
    </div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function() {
		
		var owl2 = jQuery("#imagineGaleriaCS2 .owl-carousel");
		owl2.owlCarousel({
			autoplay:true,
			autoplayTimeout:5000,
			items : 1,
			responsive : {
				0 : { items : 1.3 },
				700 : { items : 3},
				991 : { items : 4 },
			},
			nav:true,
			margin:0,
			loop:true,
		});
		jQuery("#imagineGaleriaCS2 .btnsCaroseulContent .next").click(function(){
			owl2.trigger('next.owl.carousel');
		});
		jQuery("#imagineGaleriaCS2 .btnsCaroseulContent .prev").click(function(){
			owl2.trigger('prev.owl.carousel');
		});
	});			
</script>
<?php endif;?>