<?php
	$logo = '/arquivos/imagens/logo.png';

    $controller->load->model('modulos/modulos_model_institucional', 'model_institucional');
    $depoimentos = $controller->model_institucional->buscar_depoimentos();
    if(!$depoimentos) return;

    $controller->load->library('Imgno_imagem', '', 'imagineImagem');

    $dir_p = '/arquivos/imagens/depoimento/';
?>
<?php if($depoimentos):?>

<div class="container depoimentos" id="depoimentos">
	<div class="btnsCaroseulContent">
		<div class="owl-carousel">
				<?php foreach($depoimentos as $depoimento):
						if($depoimento->imagem){
							$img_p = $controller->imagineImagem->otimizar($dir_p.$depoimento->imagem, 550, 413, false, true, 80);
						}else{
							// logo
							$img_p = $controller->imagineImagem->otimizar($logo, 550, 413, false, false, 80);
						}
					?>
					<div class="item">
						<div class="d-blocos">
							<div class="imgs">
								<img src="<?=$img_p?>" title="<?=$depoimento->nome?>" alt="<?=$depoimento->nome?>">
								<div class="nome"><?=$depoimento->nome?></div>
							</div>
							
							<div class="d-bloco txts">
								<div class="tituloPadrao">Depoimentos</div>
								<div class="txt textoPadrao"><?=$depoimento->descricao?></div>
							</div>
						</div>
						
					</div>
				<?php endforeach;?>
		</div>
    </div>
</div>

<script type="text/javascript">
	//jQuery(document).ready(function() {
		
		var owlDepo1 = jQuery("#depoimentos .owl-carousel");
		owlDepo1.owlCarousel({
			//autoplay:true,
			//autoplayTimeout:5000,
			items : 1,
			responsive : {
				0 : { 
					items : 1,
					margin: 30,
				},
				576 : { 
					items : 1,
					margin: 30,

				},
				991 : { 
					items : 1,
					margin: 30,
				},
			},
			dots:true,
			nav:false,
			loop:true,
		});
		jQuery("#depoimentos .btnsCaroseulContent .next").click(function(){
			owlDepo1.trigger('next.owl.carousel');
		});
		jQuery("#depoimentos .btnsCaroseulContent .prev").click(function(){
			owlDepo1.trigger('prev.owl.carousel');
		});
	//});			
</script>
<?php endif;?>