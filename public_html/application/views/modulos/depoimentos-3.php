<?php
	$logo = '/arquivos/imagens/logo.png';

    $controller->load->model('modulos/modulos_model_institucional', 'model_institucional');
    $depoimentos = $controller->model_institucional->buscar_depoimentos();
    if(!$depoimentos) return;

    $controller->load->library('Imgno_imagem', '', 'imagineImagem');

    $dir_p = '/arquivos/imagens/depoimento/';
?>
<?php if($depoimentos):?>

<div class="container depoimentos-3" id="depoimentos-3">
	<div class="tituloPadrao textCenter tituloP">Nossos clientes contam suas experiências memoráveis.</div>
	<div class="btnsCaroseulContent">
		<div class="owl-carousel">
				<?php foreach($depoimentos as $depoimento):
						if($depoimento->imagem){
							$img_p = $controller->imagineImagem->otimizar($dir_p.$depoimento->imagem, 345, 285, false, true, 80);
						}else{
							// logo
							$img_p = $controller->imagineImagem->otimizar($logo, 345, 258, false, false, 80);
						}
					?>
					<div class="item">
						<div class="d-blocos">
							<div class="d-bloco txts textoPadrao">
								<div class="txt"><?=$depoimento->descricao?></div>
								<div class="nome"><?=$depoimento->nome?></div>
							</div>

							<div class="imgs">
								<img src="<?=$img_p?>" title="<?=$depoimento->nome?>" alt="<?=$depoimento->nome?>">
							</div>
						</div>
						
					</div>
				<?php endforeach;?>
		</div>
    </div>
</div>

<script type="text/javascript">
	//jQuery(document).ready(function() {
		
		var owlDepo1 = jQuery("#depoimentos-3 .owl-carousel");
		owlDepo1.owlCarousel({
			//autoplay:true,
			//autoplayTimeout:5000,
			items : 1,
			responsive : {
				0 : { 
					items : 1,
					margin: 30,
				},
				769 : { 
					items : 1,
					margin: 50,
				},
				993 : { 
					items : 2,
					margin: 50,
				},
			},
			dots:true,
			nav:false,
			loop:true,
		});
		jQuery("#depoimentos-3 .btnsCaroseulContent .next").click(function(){
			owlDepo1.trigger('next.owl.carousel');
		});
		jQuery("#depoimentos-3 .btnsCaroseulContent .prev").click(function(){
			owlDepo1.trigger('prev.owl.carousel');
		});
	//});			
</script>
<?php endif;?>